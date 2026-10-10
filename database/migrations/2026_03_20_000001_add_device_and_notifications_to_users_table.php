<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'type')) {
                $table->string('type')->nullable()->after('password');
            }

            if (! Schema::hasColumn('users', 'notification_time')) {
                $table->string('notification_time', 5)->nullable()->after('profile_image');
            }

            if (! Schema::hasColumn('users', 'fcm_token')) {
                $table->text('fcm_token')->nullable()->after('notification_time');
            }

            if (! Schema::hasColumn('users', 'device_type')) {
                $table->string('device_type', 20)->nullable()->after('fcm_token');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'device_type')) {
                $table->dropColumn('device_type');
            }
            if (Schema::hasColumn('users', 'fcm_token')) {
                $table->dropColumn('fcm_token');
            }
            if (Schema::hasColumn('users', 'notification_time')) {
                $table->dropColumn('notification_time');
            }
            if (Schema::hasColumn('users', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};
