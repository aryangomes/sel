<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\AcquisitionType;
use Faker\Generator as Faker;

$factory->define(AcquisitionType::class, function (Faker $faker) {
    return [
        'type' => $faker->word,
    ];
});
