<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoanContainsCollectionCopiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_contains_collection_copies', function (Blueprint $table) {
            $table->bigIncrements('idLoanContainsCollectionCopy');
            $table->timestamps();
            $table->softDeletes();

            $table->unsignedBigInteger('idLoan');

            $table->foreign('idLoan')
                ->references('idLoan')
                ->on('loans');

            $table->unsignedBigInteger('idCollectionCopy');

            $table->foreign('idCollectionCopy')
                ->references('idCollectionCopy')
                ->on('collection_copies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_contains_collection_copies');
    }
}
