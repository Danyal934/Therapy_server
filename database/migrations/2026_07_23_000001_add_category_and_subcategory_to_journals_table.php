<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->bigInteger('category_id')->default(-1);
            $table->bigInteger('sub_category_id')->default(-1);
        });
    }

    public function down()
    {
        Schema::table('journals', function (Blueprint $table) {
            $table->dropColumn(['category_id', 'sub_category_id']);
        });
    }
};
