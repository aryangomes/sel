<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name',200);
            $table->string('streetAddress',200);
            $table->string('neighborhoodAddress',200);
            $table->string('numberAddress',30);
            $table->string('phoneNumber',30)->nullable();
            $table->string('cellNumber',30)->nullable();
            $table->string('complementAddress')->nullable();
            $table->string('cpf',11);
            $table->boolean('isAdmin')->default(0);
            $table->boolean('photo')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
