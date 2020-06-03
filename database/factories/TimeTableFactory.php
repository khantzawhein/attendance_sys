<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Timetable;
use Faker\Generator as Faker;

$factory->define(Timetable::class, function (Faker $faker) {
    return [
        'course_id' => $faker->numberBetween(1, 10),
        'day' => $faker->numberBetween(0, 6),
        'start_time' => $faker->time(),
        'end_time' => $faker->time()
    ];
});
