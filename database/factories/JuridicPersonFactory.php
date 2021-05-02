<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\JuridicPerson;
use App\Models\Provider;
use App\Models\Utils\Regex;
use Faker\Generator as Faker;

$factory->define(JuridicPerson::class, function (Faker $faker) {
    return [
        'idProvider'=>factory(Provider::class),
        'cnpj'=>$faker->regexify(Regex::CNPJ),
    ];
});
