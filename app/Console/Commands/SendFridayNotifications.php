<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class SendFridayNotifications extends Command
{
    protected $signature = 'notifications:friday {--date=} {--force} {--time=}';

    protected $description = 'Send Firebase notifications on Fridays';

    public function handle()
    {
        $now = $this->resolveNow();
        $this->info('Starting notification command...');
        $this->info("Current time: {$now->toDateTimeString()}");
        Log::info('Starting notification command', ['time' => $now->toDateTimeString()]);

        if ($this->option('time')) {
            $this->warn('Option --time is ignored (broadcast mode).');
        }

        if (! $this->option('force') && $now->format('l') !== 'Friday') {
            $this->info('Not Friday — skipping notifications.');
            Log::info("Skipping notifications: today is {$now->format('l')}");

            return;
        }

        $this->info('✅ Running Friday notifications broadcast');
        Log::info('Running Friday notifications broadcast');

        try {
            $users = User::whereNotNull('fcm_token')
                ->where('fcm_token', '!=', '')
                ->get();
            $this->info("Found {$users->count()} users with FCM tokens");
            Log::info("Found {$users->count()} users with FCM tokens");
        } catch (QueryException $e) {
            $this->error('Database connection failed. Configure DB and try again.');
            Log::error('Friday notifications failed: database connection error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return;
        }

        if ($users->isEmpty()) {
            $this->info('No users to notify');
            Log::info('No users to notify');

            return;
        }

        $messaging = null;
        try {
            $messaging = Firebase::messaging();
            $this->info('Using Firebase HTTP v1 (service account via Laravel Firebase package)');
            Log::info('Firebase HTTP v1 initialized successfully via Laravel Firebase package');
        } catch (\Throwable $e) {
            $this->warn('Firebase service account is configured but could not be loaded.');
            Log::error('Firebase service account could not be loaded', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }

        $sent = 0;
        $failed = 0;
        foreach ($users as $user) {
            $this->info("Processing user {$user->id}...");
            
            if ($messaging) {
                try {
                    $channel = env('FIREBASE_ANDROID_CHANNEL', 'weekly_check');
                    $title = 'Weekly Check';
                    $body = "Your Weekly Check-In is here\nTake a moment to reflect on the wins and blessings of your week.";

                    $message = CloudMessage::withTarget('token', $user->fcm_token)
                        ->withNotification(Notification::create($title, $body))
                        ->withAndroidConfig(AndroidConfig::fromArray([
                            'priority' => 'high',
                            'notification' => [
                                'channel_id' => $channel,
                            ],
                        ]))
                        ->withData([
                            'screen' => 'checkin',
                            'checkin' => 'Weekly Check-In screen',
                        ]);

                    $messaging->send($message);

                    $sent++;
                    $this->info("✅ Firebase delivered to user {$user->id}");
                    Log::info("Firebase delivered to user {$user->id}");
                } catch (\Throwable $e) {
                    $failed++;
                    $this->warn("❌ Firebase failed user {$user->id}: {$e->getMessage()}");
                    Log::error('Firebase send failed', [
                        'user_id' => $user->id,
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }

                continue;
            }

            $this->error('Firebase messaging not available, cannot send notifications.');
            $failed++;
        }

        $this->info("✅ Broadcast completed. Delivered: {$sent}, Failed: {$failed}");
        $this->info('✅ Friday notifications completed at '.now());
        Log::info('Friday notifications command completed', [
            'delivered' => $sent,
            'failed' => $failed,
            'completed_at' => now()->toDateTimeString(),
        ]);
    }

    protected function resolveNow(): Carbon
    {
        $date = $this->option('date');
        if (is_string($date) && $date !== '') {
            try {
                return Carbon::parse($date);
            } catch (\Throwable $e) {
                return Carbon::now();
            }
        }

        return Carbon::now();
    }
}
