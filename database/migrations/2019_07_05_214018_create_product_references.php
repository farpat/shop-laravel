<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateProductReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('product_references', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('label');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('main_image_id')->nullable();
            $table->unsignedDecimal('unit_price_excluding_taxes', 10, 2);
            $table->unsignedDecimal('unit_price_including_taxes', 10, 2);
            $table->json('filled_product_fields');

            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('main_image_id')->references('id')->on('images')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('product_references');
    }
}
