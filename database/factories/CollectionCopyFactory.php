<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Collection;
use App\Models\CollectionCopy;
use Faker\Generator as Faker;

$factory->define(CollectionCopy::class, function (Faker $faker) {
    return [
        'reference' => $faker->numerify('Loc. ###-###-###'),
        'isAvailable' => $faker->boolean,
        'idCollection' => factory(Collection::class),
    ];
});
