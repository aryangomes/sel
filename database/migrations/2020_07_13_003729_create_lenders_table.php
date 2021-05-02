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
            $table->string('name',200);
            $table->string('streetAddress',200);
            $table->string('neighborhoodAddress',200);
            $table->string('numberAddress',20);
            $table->string('phoneNumber',30)->nullable();
            $table->string('cellNumber',30)->nullable();
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
