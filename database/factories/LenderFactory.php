<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Lender;
use Faker\Generator as Faker;

$factory->define(Lender::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'email' =>  $faker->email,
        'streetAddress' => $faker->streetAddress,
        'neighborhoodAddress' => $faker->state,
        'numberAddress' => $faker->buildingNumber,
        'phoneNumber' => $faker->phoneNumber,
        'cellNumber' =>  $faker->phoneNumber,
        'complementAddress' => $faker->secondaryAddress,
        'site' => $faker->url,
    ];
});
