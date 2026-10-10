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
        Schema::table('orders', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            $foreignKeys = \DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME = 'orders_user_id_foreign'");
            if (!empty($foreignKeys)) {
                $table->dropForeign(['user_id']);
            }
            $table->foreignId('user_id')->nullable()->change()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            $foreignKeys = \DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders' AND CONSTRAINT_TYPE = 'FOREIGN KEY' AND CONSTRAINT_NAME = 'orders_user_id_foreign'");
            if (!empty($foreignKeys)) {
                $table->dropForeign(['user_id']);
            }
            $table->foreignId('user_id')->change()->constrained()->onDelete('cascade');
        });
    }
};
