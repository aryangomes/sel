<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'permission' => $faker->lexify('can.?????'),
        'can' => $faker->boolean(),
        'description' => $faker->text(150),
    ];
});
