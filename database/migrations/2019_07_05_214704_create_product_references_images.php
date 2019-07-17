<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductReferencesImages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('product_references_images', function (Blueprint $table) {
            $table->unsignedBigInteger('product_reference_id');
            $table->unsignedBigInteger('image_id');

            $table->foreign('product_reference_id')->references('id')->on('product_references')->onDelete('cascade');
            $table->foreign('image_id')->references('id')->on('images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('product_references_images');
    }
}
