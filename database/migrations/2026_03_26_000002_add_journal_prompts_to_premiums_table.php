<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('premiums', function (Blueprint $table) {
            if (! Schema::hasColumn('premiums', 'journal_prompts')) {
                $table->json('journal_prompts')->nullable()->after('premium');
            }
        });
    }

    public function down(): void
    {
        Schema::table('premiums', function (Blueprint $table) {
            if (Schema::hasColumn('premiums', 'journal_prompts')) {
                $table->dropColumn('journal_prompts');
            }
        });
    }
};

