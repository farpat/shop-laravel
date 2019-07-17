<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateModuleParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up ()
    {
        Schema::create('module_parameters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('module_id');
            $table->text('label');
            $table->text('value');
            $table->text('description')->nullable();

            $table->unique(['module_id', 'label']);

//            $table->foreign('module_id')->references('id')->on('modules');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down ()
    {
        Schema::dropIfExists('module_parameters');
    }
}
