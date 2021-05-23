<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\CollectionType;
use Faker\Generator as Faker;

$factory->define(CollectionType::class, function (Faker $faker) {
    return [
        'type' => $faker->word,
    ];
});
