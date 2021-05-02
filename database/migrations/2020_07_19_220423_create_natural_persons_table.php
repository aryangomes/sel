<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNaturalPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('natural_persons', function (Blueprint $table) {
            $table->bigIncrements('idNaturalPerson');
            $table->string('cpf', 11);
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('idProvider');

            $table->foreign('idProvider')
                ->references('idProvider')
                ->on('providers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('natural_persons');
    }
}
