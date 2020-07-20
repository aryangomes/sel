<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\NaturalPerson;
use App\Models\Provider;
use App\Models\Utils\Regex;
use Faker\Generator as Faker;

$factory->define(NaturalPerson::class, function (Faker $faker) {
    return [
       'idProvider'=>factory(Provider::class),
       'cpf'=>$faker->regexify(Regex::CPF),
    ];
});
