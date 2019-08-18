<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cart_id');
            $table->unsignedInteger('quantity');

            $table->unsignedBigInteger('product_reference_id')->nullable();
//            Replicate product_reference informations
//            Replicate product informations
//            when cart is " locked "

            $table->unsignedDecimal('amount_excluding_taxes', 10, 2);
            $table->unsignedDecimal('amount_including_taxes', 10, 2);

            $table->foreign('cart_id')->references('id')->on('carts');
            $table->foreign('product_reference_id')->references('id')->on('product_references');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('cart_items');
    }
}
