<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Utils\Regex;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

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
        'streetAddress' => $faker->streetName,
        'neighborhoodAddress' => $faker->citySuffix,
        'numberAddress' => (string) $faker->randomDigit,
        'phoneNumber' => $faker->phoneNumber,
        'cellNumber' => $faker->phoneNumber,
        'cpf' => $faker->regexify(Regex::CPF),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => Hash::make(config('user.default_password_not_admin')),
        'remember_token' => Str::random(10),
        'isAdmin' => 0,
        'idProfile' => factory(Profile::class),
    ];
});


$factory->state(User::class, 'admin', [
    'password' => Hash::make(config('user.default_password_admin')),
    'isAdmin' => 1,

]);


$factory->state(User::class, 'notAdmin', [
    'password' => Hash::make(config('user.default_password_not_admin')),
    'isAdmin' => 0,

]);
