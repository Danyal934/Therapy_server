<?php

namespace App\Http\Controllers;

use App\Models\IosSubscription;
use App\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PlanController extends Controller
{
    public function savePlan(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'message' => __('messages.unauthenticated'),
            ], 401);
        }

        $request->validate([
            'plan_type' => 'required|in:1_month,1_year',
            'payment_intent_id' => 'required|string',
        ]);

        $durations = [
            '1_month' => 30,
            '1_year' => 365,
        ];

        $prices = [
            '1_month' => 9.99,
            '1_year' => 79.99,
        ];

        $planType = $request->plan_type;
        $durationDays = $durations[$planType];
        $expectedAmount = (int) round($prices[$planType] * 100);

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);

        if (UserPlan::where('payment_intent_id', $request->payment_intent_id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.payment_already_used'),
                'reason' => 'payment_already_used',
            ], 403);
        }

        if (($paymentIntent->status ?? null) !== 'succeeded') {
            return response()->json([
                'success' => false,
                'message' => __('messages.payment_verification_failed'),
                'reason' => 'payment_not_succeeded',
            ], 403);
        }

        if ((int) ($paymentIntent->amount ?? 0) !== $expectedAmount) {
            return response()->json([
                'success' => false,
                'message' => __('messages.payment_verification_failed'),
                'reason' => 'payment_amount_mismatch',
            ], 403);
        }

        $intentUserId = null;
        $intentPlanType = null;
        if (isset($paymentIntent->metadata)) {
            try {
                $intentUserId = $paymentIntent->metadata['user_id'] ?? null;
                $intentPlanType = $paymentIntent->metadata['plan_type'] ?? null;
            } catch (\Throwable $e) {
                $intentUserId = null;
                $intentPlanType = null;
            }
        }

        if ($intentUserId !== (string) $user->id) {
            $response = [
                'success' => false,
                'message' => __('messages.payment_verification_failed'),
                'reason' => 'payment_user_mismatch',
            ];

            if (app()->environment('local', 'development')) {
                $response['debug'] = [
                    'token_user_id' => (int) $user->id,
                    'intent_metadata_user_id' => $intentUserId,
                    'intent_id' => $paymentIntent->id ?? null,
                    'intent_status' => $paymentIntent->status ?? null,
                ];
            }

            return response()->json($response, 403);
        }

        if ($intentPlanType !== (string) $planType) {
            return response()->json([
                'success' => false,
                'message' => __('messages.payment_verification_failed'),
                'reason' => 'payment_plan_mismatch',
            ], 403);
        }

        $start = Carbon::now();
        $end = $start->copy()->addDays($durationDays);

        $plan = UserPlan::create([
            'user_id' => $user->id,
            'plan_type' => $planType,
            'price' => $prices[$planType],
            'duration_days' => $durationDays,
            'status' => true,
            'start_date' => $start,
            'end_date' => $end,
            'payment_intent_id' => $request->payment_intent_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('messages.plan_saved'),
            'plan' => $plan,
            'user' => $user,
        ]);
    }

    public function saveIosPlan(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return response()->json([
                'message' => __('messages.unauthenticated'),
            ], 401);
        }

        $validated = $request->validate([
            'plan_type' => 'required|in:1_month,1_year',
            'transaction_id' => 'required|string',
            'device_id' => 'required|string|max:255',
            'product_id' => 'nullable|string|max:255',
            'original_transaction_id' => 'nullable|string|max:255',
        ]);

        $durations = [
            '1_month' => 30,
            '1_year' => 365,
        ];

        $planType = $validated['plan_type'];
        $durationDays = $durations[$planType];
        $expiresAt = Carbon::now()->addDays($durationDays);

        $subscription = IosSubscription::updateOrCreate(
            ['transaction_id' => $validated['transaction_id']],
            [
                'user_id' => $user->id,
                'device_id' => $validated['device_id'],
                'product_id' => $validated['product_id'] ?? $planType,
                'original_transaction_id' => $validated['original_transaction_id'] ?? null,
                'environment' => 'disabled',
                'expires_at' => $expiresAt,
                'last_verified_at' => Carbon::now(),
                'raw' => [
                    'source' => 'ios_save_api',
                    'bypass' => true,
                ],
            ]
        );

        if (! ($user->premium_access ?? false)) {
            $user->update(['premium_access' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => __('messages.plan_saved'),
            'active' => true,
            'expires_at' => $subscription->expires_at?->toIso8601String(),
            'product_id' => $subscription->product_id,
            'user' => $user,
        ]);
    }

    // Check expired plans
    public function checkExpiredPlans(Request $request)
    {
        $user = $request->user();
        if (! ($user?->admin ?? false)) {
            return response()->json([
                'message' => __('messages.unauthorized_admin'),
            ], 403);
        }

        $now = Carbon::now();

        $expiredPlans = UserPlan::where('status', true)
            ->where('end_date', '<', $now)
            ->get();

        foreach ($expiredPlans as $plan) {
            $plan->update(['status' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => __('messages.plans_expired', ['count' => $expiredPlans->count()]),
        ]);
    }
}
