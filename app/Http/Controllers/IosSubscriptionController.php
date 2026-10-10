<?php

namespace App\Http\Controllers;

use App\Models\IosSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class IosSubscriptionController extends Controller
{
    public function storeDevice(Request $request)
    {
        return $this->verify($request);
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string',
            'device_id' => 'required|string|max:255',
            'product_id' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => __('messages.validation_failed'), 'errors' => $validator->errors()], 422);
        }

        if (! filter_var(env('IOS_IAP_VERIFICATION_ENABLED', true), FILTER_VALIDATE_BOOL)) {
            $expiresAt = Carbon::now()->addYear();
            IosSubscription::updateOrCreate(
                ['transaction_id' => $request->input('transaction_id')],
                [
                    'device_id' => $request->input('device_id'),
                    'product_id' => $request->input('product_id'),
                    'environment' => 'disabled',
                    'expires_at' => $expiresAt,
                    'last_verified_at' => Carbon::now(),
                    'raw' => [
                        'bypass' => true,
                    ],
                ]
            );

            return response()->json([
                'active' => true,
                'expires_at' => $expiresAt->toIso8601String(),
                'product_id' => $request->input('product_id'),
            ]);
        }

        $config = config('services.app_store', []);
        $issuerId = $config['issuer_id'] ?? null;
        $keyId = $config['key_id'] ?? null;
        $bundleId = $config['bundle_id'] ?? null;
        $privateKey = $this->loadPrivateKey($config);

        if (! $issuerId || ! $keyId || ! $bundleId || ! $privateKey) {
            $expiresAt = Carbon::now()->addYear();
            $subscription = IosSubscription::updateOrCreate(
                ['transaction_id' => $request->input('transaction_id')],
                [
                    'device_id' => $request->input('device_id'),
                    'product_id' => $request->input('product_id'),
                    'environment' => 'disabled',
                    'expires_at' => $expiresAt,
                    'last_verified_at' => Carbon::now(),
                    'raw' => ['bypass' => true],
                ]
            );

            return response()->json([
                'active' => true,
                'expires_at' => $subscription->expires_at?->toIso8601String(),
                'product_id' => $subscription->product_id,
            ]);
        }

        $transactionId = $request->input('transaction_id');
        $jwt = $this->generateAppStoreJwt($issuerId, $keyId, $bundleId, $privateKey);

        $response = Http::withToken($jwt)
            ->acceptJson()
            ->get("https://api.storekit.itunes.apple.com/inApps/v1/transactions/{$transactionId}");

        if (! $response->ok()) {
            return response()->json([
                'message' => 'Subscription verification failed.',
                'status' => $response->status(),
                'body' => $response->json(),
            ], 422);
        }

        $payload = $response->json();
        $signedTransactionInfo = $payload['signedTransactionInfo'] ?? null;

        if (! $signedTransactionInfo) {
            return response()->json(['message' => 'Subscription verification failed.'], 422);
        }

        $transactionInfo = $this->decodeJwtPayload($signedTransactionInfo);

        $expiresAt = $this->parseExpiresAt($transactionInfo);
        $productId = $transactionInfo['productId'] ?? $request->input('product_id') ?? null;
        $originalTransactionId = $transactionInfo['originalTransactionId'] ?? null;
        $environment = $payload['environment'] ?? ($transactionInfo['environment'] ?? null);

        if (! $productId || ! $expiresAt) {
            return response()->json(['message' => 'Subscription verification failed.'], 422);
        }

        $subscription = IosSubscription::updateOrCreate(
            ['transaction_id' => $transactionId],
            [
                'device_id' => $request->input('device_id'),
                'product_id' => $productId,
                'original_transaction_id' => $originalTransactionId,
                'environment' => $environment,
                'expires_at' => $expiresAt,
                'last_verified_at' => Carbon::now(),
                'raw' => [
                    'transaction' => $transactionInfo,
                    'lookup' => $payload,
                ],
            ]
        );

        $active = $subscription->expires_at && $subscription->expires_at->isFuture();

        return response()->json([
            'active' => $active,
            'expires_at' => $subscription->expires_at?->toIso8601String(),
            'product_id' => $subscription->product_id,
        ]);
    }

    public function link(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => __('messages.validation_failed'), 'errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        if (! $user) {
            return response()->json(['message' => __('messages.unauthenticated')], 401);
        }
        $deviceId = $request->input('device_id');

        IosSubscription::where('device_id', $deviceId)
            ->whereNull('user_id')
            ->update(['user_id' => $user->id]);

        $hasActive = IosSubscription::activeForUser($user->id)->exists();
        if ($hasActive && ! ($user->premium_access ?? false)) {
            $user->update(['premium_access' => true]);
        }

        return response()->json([
            'success' => true,
            'premium_access' => (bool) ($user->premium_access ?? false),
        ]);
    }

    protected function loadPrivateKey(array $config): ?string
    {
        $path = $config['private_key_path'] ?? null;
        if ($path && is_string($path) && file_exists($path)) {
            $content = file_get_contents($path);

            return $content === false ? null : $content;
        }

        $key = $config['private_key'] ?? null;
        if (! $key || ! is_string($key)) {
            return null;
        }

        return str_replace('\\n', "\n", $key);
    }

    protected function generateAppStoreJwt(string $issuerId, string $keyId, string $bundleId, string $privateKey): string
    {
        $header = ['alg' => 'ES256', 'kid' => $keyId, 'typ' => 'JWT'];
        $now = time();
        $payload = [
            'iss' => $issuerId,
            'iat' => $now,
            'exp' => $now + 900,
            'aud' => 'appstoreconnect-v1',
            'bid' => $bundleId,
        ];

        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES)),
            $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES)),
        ];

        $signingInput = implode('.', $segments);
        $signature = $this->signEs256($signingInput, $privateKey);
        $segments[] = $signature;

        return implode('.', $segments);
    }

    protected function signEs256(string $input, string $privateKeyPem): string
    {
        $key = openssl_pkey_get_private($privateKeyPem);
        if ($key === false) {
            throw new \RuntimeException('Invalid App Store private key.');
        }

        $signatureDer = '';
        $ok = openssl_sign($input, $signatureDer, $key, OPENSSL_ALGO_SHA256);
        if (! $ok) {
            throw new \RuntimeException('Unable to sign App Store JWT.');
        }

        $signatureRaw = $this->derToJose($signatureDer, 64);

        return $this->base64UrlEncode($signatureRaw);
    }

    protected function derToJose(string $der, int $length): string
    {
        $offset = 0;
        if (ord($der[$offset]) !== 0x30) {
            throw new \RuntimeException('Invalid DER signature.');
        }
        $offset++;
        $this->readDerLength($der, $offset);

        if (ord($der[$offset]) !== 0x02) {
            throw new \RuntimeException('Invalid DER signature.');
        }
        $offset++;
        $rLen = $this->readDerLength($der, $offset);
        $r = substr($der, $offset, $rLen);
        $offset += $rLen;

        if (ord($der[$offset]) !== 0x02) {
            throw new \RuntimeException('Invalid DER signature.');
        }
        $offset++;
        $sLen = $this->readDerLength($der, $offset);
        $s = substr($der, $offset, $sLen);

        $r = ltrim($r, "\x00");
        $s = ltrim($s, "\x00");
        $half = intdiv($length, 2);
        $r = str_pad($r, $half, "\x00", STR_PAD_LEFT);
        $s = str_pad($s, $half, "\x00", STR_PAD_LEFT);

        return $r.$s;
    }

    protected function readDerLength(string $der, int &$offset): int
    {
        $len = ord($der[$offset]);
        $offset++;
        if ($len < 0x80) {
            return $len;
        }

        $numBytes = $len & 0x7F;
        $len = 0;
        for ($i = 0; $i < $numBytes; $i++) {
            $len = ($len << 8) | ord($der[$offset]);
            $offset++;
        }

        return $len;
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected function decodeJwtPayload(string $jwt): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) < 2) {
            return [];
        }
        $payload = $this->base64UrlDecode($parts[1]);
        $decoded = json_decode($payload, true);

        return is_array($decoded) ? $decoded : [];
    }

    protected function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/')) ?: '';
    }

    protected function parseExpiresAt(array $transactionInfo): ?Carbon
    {
        $ms = $transactionInfo['expiresDate'] ?? $transactionInfo['expirationDate'] ?? null;
        if (! $ms || ! is_numeric($ms)) {
            return null;
        }

        return Carbon::createFromTimestampMs((int) $ms);
    }
}
