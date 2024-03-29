<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Year;
use Faker\Generator as Faker;

$factory->define(Year::class, function (Faker $faker) {
    return [
        'academic_year' => $faker->year(),
        'name' => $faker->safeColorName.'year'
    ];
});
