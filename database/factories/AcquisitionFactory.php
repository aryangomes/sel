<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Acquisition;
use App\Models\AcquisitionType;
use App\Models\Lender;
use App\Models\Provider;
use Faker\Generator as Faker;

$factory->define(Acquisition::class, function (Faker $faker) {
    return [
        'price' => $faker->randomFloat(6, 0.01, 1000000),
        'quantity' => $faker->numberBetween(1, 1000000),
        'idLender' => factory(Lender::class),
        'idProvider' => factory(Provider::class),
        'idAcquisitionType' => factory(AcquisitionType::class),
    ];
});
