<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('items_count')->default(0);
            $table->unsignedDecimal('total_amount_excluding_taxes', 10, 2)->default(0);
            $table->unsignedDecimal('total_amount_including_taxes', 10, 2)->default(0);
            $table->enum('status', ['ORDERING', 'ORDERED', 'DELIVRED']);
            $table->text('comment');

            $table->unsignedBigInteger('user_id');
            //Replicate user informations

            $table->unsignedBigInteger('address_id')->nullable();
            //Replicate address informations

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('address_id')->references('id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
