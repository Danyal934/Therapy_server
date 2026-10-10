<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('reflections', 'journal_prompts')) {
            Schema::table('reflections', function (Blueprint $table) {
                $table->json('journal_prompts')->nullable()->after('audio_file');
            });
        }
    }

    public function down(): void
    {
        Schema::table('reflections', function (Blueprint $table) {
            $table->dropColumn('journal_prompts');
        });
    }
};
