<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderWorkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_work', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('order_number')->unique();
            $table->unsignedBigInteger('types_work_id');
            $table->unsignedBigInteger('status_id');
            $table->foreign('types_work_id')->references('id')->on('types_work');
            $table->foreign('status_id')->references('id')->on('status');
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
        Schema::dropIfExists('order_work.');
    }
}
