<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJuridicPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('juridic_persons', function (Blueprint $table) {
            $table->bigIncrements('idJuridicPerson');
            $table->string('cnpj', 14);
            $table->softDeletes();
            $table->timestamps();

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
        Schema::dropIfExists('juridic_persons');
    }
}
