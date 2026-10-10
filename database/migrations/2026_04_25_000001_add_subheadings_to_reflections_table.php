<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reflections', function (Blueprint $table) {
            if (! Schema::hasColumn('reflections', 'subheadings')) {
                $table->json('subheadings')->nullable()->after('title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reflections', function (Blueprint $table) {
            if (Schema::hasColumn('reflections', 'subheadings')) {
                $table->dropColumn('subheadings');
            }
        });
    }
};
