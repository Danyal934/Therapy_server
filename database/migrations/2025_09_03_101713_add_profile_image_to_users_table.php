<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('users', 'profile_image')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('profile_image')->nullable()->after('premium_access');
            });
        }
    }

    public function down()
    {
        Schema::table('users', create: function (Blueprint $table) {
            $table->dropColumn('profile_image');
        });
    }
};
