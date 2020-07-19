<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcquisitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acquisitions', function (Blueprint $table) {
            $table->bigIncrements('idAcquisition');
            $table->double('price')->default(0);
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->unsignedBigInteger('idLender');

            $table->foreign('idLender')
                ->references('idLender')
                ->on('lenders');


            $table->unsignedBigInteger('idProvider');

            $table->foreign('idProvider')
                ->references('idProvider')
                ->on('providers');


            $table->unsignedBigInteger('idAcquisitionType');

            $table->foreign('idAcquisitionType')
                ->references('idAcquisitionType')
                ->on('acquisition_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acquisitions');
    }
}
