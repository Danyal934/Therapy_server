<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeController extends Controller
{
    public function createPaymentIntent(Request $request)
    {
        $validated = $request->validate([
            'plan_type' => 'required|in:1_month,1_year',
            'currency' => 'nullable|string|size:3',
            'paymentMethodType' => 'nullable|string',
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            $amounts = [
                '1_month' => 999,
                '1_year' => 7999,
            ];

            $amount = $amounts[$validated['plan_type']] ?? null;
            if ($amount === null) {
                return response()->json([
                    'error' => 'Invalid plan_type',
                ], 422);
            }

            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => strtolower($validated['currency'] ?? 'gbp'),
                'payment_method_types' => [$validated['paymentMethodType'] ?? 'card'],
                'metadata' => [
                    'user_id' => (string) $request->user()->id,
                    'plan_type' => (string) $validated['plan_type'],
                ],
            ]);

            $response = [
                'clientSecret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
            ];

            if (app()->environment('local', 'development')) {
                $response['debug'] = [
                    'user_id' => (int) $request->user()->id,
                    'plan_type' => (string) $validated['plan_type'],
                    'amount' => (int) $paymentIntent->amount,
                    'currency' => (string) $paymentIntent->currency,
                ];
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Stripe PaymentIntent error', [
                'message' => $e->getMessage(),
                'user_id' => $request->user()?->id,
            ]);

            return response()->json([
                'error' => 'Payment creation failed',
            ], 500);
        }
    }
}
