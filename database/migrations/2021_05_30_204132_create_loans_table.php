<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->bigIncrements('idLoan');
            $table->string('loansIdentifier', 30);
            $table->dateTime('returnDate')->nullable();
            $table->dateTime('expectedReturnDate');
            $table->string('observation', 200);
            $table->timestamps();
            $table->softDeletes();

            $table->uuid('idOperatorUser');

            $table->foreign('idOperatorUser')
                ->references('id')
                ->on('users');

            $table->uuid('idBorrowerUser');

            $table->foreign('idBorrowerUser')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
