<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_copies', function (Blueprint $table) {
            $table->bigIncrements('idCollectionCopy');
            $table->string('reference', 50)->nullable();
            $table->boolean('isAvailable')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('idCollection');

            $table->foreign('idCollection')
                ->references('idCollection')
                ->on('collections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_copies');
    }
}
