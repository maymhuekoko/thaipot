<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsumptionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consumption_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('total_consumption_id');
            $table->unsignedInteger('pi_category_id');
            $table->unsignedInteger('purchase_item_id');
            $table->string('name');
            $table->string('consumption_no');
            $table->string('unit');
            $table->integer('stock_quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consumption_histories');
    }
}
