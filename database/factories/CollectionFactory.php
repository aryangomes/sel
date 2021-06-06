<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Acquisition;
use App\Models\Collection;
use App\Models\CollectionCategory;
use App\Models\CollectionType;
use Faker\Generator as Faker;

$factory->define(Collection::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(5),
        'author' => "{$faker->firstName} {$faker->lastName}",
        'cdd' => $faker->numerify('#########'),
        'cdu' =>  $faker->numerify('#########'),
        'isbn' => $faker->isbn13(10),
        'publisherCompany' => $faker->company,
        'idCollectionType' => factory(CollectionType::class),
        'idCollectionCategory' => factory(CollectionCategory::class),
        'idAcquisition' => factory(Acquisition::class),
    ];
});
