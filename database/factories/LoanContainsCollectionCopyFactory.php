<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\CollectionCopy;
use App\Models\Loan\Loan;
use App\Models\Loan\LoanContainsCollectionCopy;
use Faker\Generator as Faker;

$factory->define(LoanContainsCollectionCopy::class, function (Faker $faker) {
    return [
        'idLoan' => factory(Loan::class),
        'idCollectionCopy' => factory(CollectionCopy::class),
    ];
});
