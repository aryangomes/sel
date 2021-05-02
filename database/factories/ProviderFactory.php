<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Provider;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'email' =>  $faker->email,
        'streetAddress' => $faker->streetAddress,
        'neighborhoodAddress' => $faker->state,
        'numberAddress' => $faker->buildingNumber,
        'phoneNumber' => $faker->phoneNumber,
        'cellNumber' =>  $faker->phoneNumber,
        'complementAddress' => $faker->secondaryAddress,
    ];
});
