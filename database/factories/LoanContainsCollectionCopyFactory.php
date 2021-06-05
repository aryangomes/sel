<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\CollectionCopy;
use App\Models\Loan;
use App\Models\LoanContainsCollectionCopy;
use Faker\Generator as Faker;

$factory->define(LoanContainsCollectionCopy::class, function (Faker $faker) {
    return [
        'quantity' => $faker->numberBetween(1, 1000000),
        'idLoan' => factory(Loan::class),
        'idCollectionCopy' => factory(CollectionCopy::class),
    ];
});
