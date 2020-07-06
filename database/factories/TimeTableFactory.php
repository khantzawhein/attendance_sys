<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Timetable;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Timetable::class, function (Faker $faker) {
    $time = Carbon::parse($faker->time());
    return [
        'section_id' => $faker->numberBetween(1, 11),
        'course_id' => $faker->numberBetween(1, 50),
        'day' => $faker->numberBetween(0, 6),
        'start_time' => $time->toTimeString(),
        'end_time' => $time->addMinutes($faker->numberBetween(10, 120))->toTimeString()
    ];
});
