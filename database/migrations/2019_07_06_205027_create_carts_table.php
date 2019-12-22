<?php

use App\Models\Billing;
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
            $table->unsignedInteger('items_count')->default(0);
            $table->unsignedDecimal('total_amount_excluding_taxes', 10, 2)->default(0);
            $table->unsignedDecimal('total_amount_including_taxes', 10, 2)->default(0);
            $table->text('comment')->nullable();

            //user informations duplicated in billing table
            $table->unsignedBigInteger('user_id');

            //address informations duplicated in billing table
            $table->unsignedBigInteger('address_id')->nullable();

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
