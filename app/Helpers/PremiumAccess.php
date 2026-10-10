<?php

namespace App\Helpers;

use App\Models\IosSubscription;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class PremiumAccess
{
    /**
     * Get detailed premium status for a user.
     * Returns an array with:
     * - has_access: bool (whether user currently has premium access)
     * - had_plan: bool (whether user ever had a plan)
     * - plan_expired: bool (whether user's last plan expired)
     */
    public static function getUserPremiumStatus(?User $user): array
    {
        $status = [
            'has_access' => false,
            'had_plan' => false,
            'plan_expired' => false,
        ];

        if (! $user) {
            return $status;
        }

        $now = Carbon::now();

        // Check user_plans first
        if (Schema::hasTable('user_plans')) {
            // Check for active plan
            $activePlan = UserPlan::query()
                ->where('user_id', $user->id)
                ->where('status', true)
                ->where('start_date', '<=', $now)
                ->where('end_date', '>', $now)
                ->first();

            if ($activePlan) {
                $status['has_access'] = true;
                $status['had_plan'] = true;
                return $status;
            }

            // Check if user ever had any plan
            $anyPlan = UserPlan::query()->where('user_id', $user->id)->exists();
            if ($anyPlan) {
                $status['had_plan'] = true;
                // Check if last plan expired
                $lastPlan = UserPlan::query()
                    ->where('user_id', $user->id)
                    ->where('status', true)
                    ->orderBy('end_date', 'desc')
                    ->first();
                if ($lastPlan && $lastPlan->end_date <= $now) {
                    $status['plan_expired'] = true;
                }
            }
        }

        // Check ios_subscriptions
        if (Schema::hasTable('ios_subscriptions')) {
            $activeSubscription = IosSubscription::activeForUser($user->id)->first();
            if ($activeSubscription) {
                $status['has_access'] = true;
                $status['had_plan'] = true;
                return $status;
            }

            // Check if user ever had an iOS subscription
            $anySubscription = IosSubscription::where('user_id', $user->id)->exists();
            if ($anySubscription) {
                $status['had_plan'] = true;
                $status['plan_expired'] = true; // For iOS, assume if not active, it's expired
            }
        }

        return $status;
    }

    public static function userHasPremium(?User $user): bool
    {
        return self::getUserPremiumStatus($user)['has_access'];
    }

    public static function deviceHasPremium(?string $deviceId): bool
    {
        if (! is_string($deviceId) || $deviceId === '') {
            return false;
        }

        if (! Schema::hasTable('ios_subscriptions')) {
            return false;
        }

        return IosSubscription::activeForDevice($deviceId)->exists();
    }
}
