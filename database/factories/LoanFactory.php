<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\CollectionCopy;
use App\Models\Loan\Loan;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Loan::class, function (Faker $faker) {
    return [
        'loansIdentifier' => $faker->numerify('LOAN##.####.##'),
        'returnDate' => $faker->dateTimeInInterval('now', '+6 days'),
        'expectedReturnDate' => $faker->dateTimeInInterval('now', '+7 days'),
        'observation' => $faker->text(),
        'status' => Loan::status()[0],
        'idOperatorUser' => factory(User::class),
        'idBorrowerUser' => factory(User::class),
    ];
});
