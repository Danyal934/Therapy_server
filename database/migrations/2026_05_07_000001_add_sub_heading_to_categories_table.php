<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('categories', 'sub_heading')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('sub_heading')->nullable()->after('name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('sub_heading');
        });
    }
};
