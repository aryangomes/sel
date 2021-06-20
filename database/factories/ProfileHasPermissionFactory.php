<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Permission;
use App\Models\Profile;
use App\Models\ProfileHasPermission;
use Faker\Generator as Faker;

$factory->define(ProfileHasPermission::class, function (Faker $faker) {
    return [
        'idProfile' => factory(Profile::class),
        'idPermission' => factory(Permission::class),
    ];
});
