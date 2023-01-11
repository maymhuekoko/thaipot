<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPiCategoryIdColumnToPiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pi', function (Blueprint $table) {
            $table->unsignedBigInteger('pi_category_id')->after('id');
            $table->foreign('pi_category_id')->references('id')->on('pi_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pi', function (Blueprint $table) {
            //
        });
    }
}
