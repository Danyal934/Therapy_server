<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('reflections')) {
            Schema::create('reflections', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->string('pic');
                $table->string('audio_file');
                $table->boolean('premium')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reflections');
    }
};
