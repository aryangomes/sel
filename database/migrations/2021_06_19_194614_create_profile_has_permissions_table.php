<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileHasPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_has_permissions', function (Blueprint $table) {
            $table->bigIncrements('idProfileHasPermission');
            $table->timestamps();
            $table->softDeletes();
            
            $table->unsignedBigInteger('idProfile');
            $table->foreign('idProfile')
            ->references('idProfile')
            ->on('profiles');
            
            $table->unsignedBigInteger('idPermission');
            $table->foreign('idPermission')
            ->references('idPermission')
            ->on('permissions');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_has_permissions');
    }
}
