<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserPlan;
use Illuminate\Support\Facades\Log;

class DeactivateExpiredPlans extends Command
{
    protected $signature = 'plans:deactivate';
    protected $description = 'Deactivate expired user plans daily';

    public function handle()
    {
        $now = now();
        $expiredPlans = UserPlan::where('status', true)
            ->where('end_date', '<', $now)
            ->get();

        foreach ($expiredPlans as $plan) {
            $plan->status = false;
            $plan->save();
        }

        Log::info('✅ Expired plans deactivated at ' . $now);
    }
}
