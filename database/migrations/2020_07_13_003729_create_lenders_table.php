<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLendersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lenders', function (Blueprint $table) {
            $table->bigIncrements('idLender');
            $table->string('name');
            $table->string('streetAddress');
            $table->string('neighborhoodAddress');
            $table->string('numberAddress');
            $table->string('phoneNumber')->nullable();
            $table->string('cellNumber')->nullable();
            $table->string('complementAddress')->nullable();
            $table->string('site')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lenders');
    }
}
