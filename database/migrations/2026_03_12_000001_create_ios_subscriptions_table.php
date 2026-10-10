<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ios_subscriptions')) {
            Schema::create('ios_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('device_id')->index();
                $table->string('product_id')->index();
                $table->string('transaction_id')->unique();
                $table->string('original_transaction_id')->nullable()->index();
                $table->string('environment')->nullable();
                $table->timestamp('expires_at')->nullable()->index();
                $table->timestamp('last_verified_at')->nullable();
                $table->json('raw')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ios_subscriptions');
    }
};

