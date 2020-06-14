<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Http\Models\Utils\Regex;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'id' => Str::uuid(),
        'name' => $faker->name,
        'streetAddressstreetAddress' => $faker->streetName,
        'neighborhoodAddress' => $faker->citySuffix,
        'numberAddress' => (string) $faker->randomDigit,
        'phoneNumber' => $faker->phoneNumber,
        'cpf' => $faker->regexify(Regex::CPF),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('12345678'),
        'remember_token' => Str::random(10),
        'isAdmin' => 0,
    ];
});
