<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_work_id');
            $table->unsignedBigInteger('users_id');
            $table->date('date_assignment');	
            $table->date('date_execution')->nullable();	
            $table->double('value_work')->nullable();	
            
            $table->foreign('order_work_id')->references('id')->on('order_work');
            $table->foreign('users_id')->references('id')->on('users');
           
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
        Schema::dropIfExists('order');
    }
}
