<?php

use App\Models\Cart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number')->nullable();
            $table->unsignedInteger('items_count')->default(0);
            $table->unsignedDecimal('total_amount_excluding_taxes', 10, 2)->default(0);
            $table->unsignedDecimal('total_amount_including_taxes', 10, 2)->default(0);
            $table->enum('status', [Cart::ORDERING_STATUS, Cart::ORDERED_STATUS, Cart::DELIVRED_STATUS]);
            $table->text('comment')->nullable();

            //user informations
            $table->unsignedBigInteger('user_id');
            $table->string('user_name');
            $table->string('user_email');

            //address informations
            $table->unsignedBigInteger('address_id')->nullable();
            $table->text('address_text')->nullable();
            $table->text('address_line1')->nullable();
            $table->text('address_line2')->nullable();
            $table->string('address_postal_code')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_country')->nullable();
            $table->unsignedDecimal('address_latitude', 9, 6)->nullable();
            $table->unsignedDecimal('address_longitude', 9, 6)->nullable();

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
    public function down ()
    {
        Schema::dropIfExists('carts');
    }
}
