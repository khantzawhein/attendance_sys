<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Semester;
use Faker\Generator as Faker;

$factory->define(Semester::class, function (Faker $faker) {
    return [
        'year_id' => factory('App\Year'),
        'semester_name' => $faker->colorName.'Semester',
        'start_date' => $faker->date(),
        'end_date' => $faker->date()
    ];
});
