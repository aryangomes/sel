<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->bigIncrements('idCollection');
            $table->string('title', 200);
            $table->string('author', 200);
            $table->string('cdd', 20)->nullable();
            $table->string('cdu', 20)->nullable();
            $table->string('isbn', 20)->nullable();
            $table->string('publisherCompany', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('idAcquisition');

            $table->foreign('idAcquisition')
                ->references('idAcquisition')
                ->on('acquisitions');



            $table->unsignedBigInteger('idCollectionType');

            $table->foreign('idCollectionType')
                ->references('idCollectionType')
                ->on('collection_types');


            $table->unsignedBigInteger('idCollectionCategory');

            $table->foreign('idCollectionCategory')
                ->references('idCollectionCategory')
                ->on('collection_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collections');
    }
}
