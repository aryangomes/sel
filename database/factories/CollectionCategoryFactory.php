<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\CollectionCategory;
use Faker\Generator as Faker;

$factory->define(CollectionCategory::class, function (Faker $faker) {
    return [
        'type' => $faker->word,
    ];
});
