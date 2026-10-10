<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('categories', 'audio_file')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->string('audio_file')->nullable()->after('pic');
            });
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('audio_file');
        });
    }
};

