<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Rule;
use App\Models\UserProfile;
use Faker\Generator as Faker;

$factory->define(Rule::class, function (Faker $faker) {
    return [
        'rule' => $faker->lexify('can.?????'),
        'can' => $faker->boolean(),
        'idUserProfile' => factory(UserProfile::class),
    ];
});
