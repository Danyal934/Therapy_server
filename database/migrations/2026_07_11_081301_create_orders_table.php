<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('delivery_option'); // 'collection', 'standard_uk'
                $table->text('delivery_address')->nullable();
                $table->decimal('delivery_cost', 8, 2);
                $table->decimal('total_amount', 8, 2);
                $table->string('payment_intent_id')->nullable();
                $table->string('payment_status')->default('pending'); // 'pending', 'succeeded', 'failed'
                $table->string('order_status')->default('pending'); // 'pending', 'confirmed', 'dispatched', 'completed', 'cancelled'
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
