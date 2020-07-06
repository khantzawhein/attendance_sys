<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Section;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(Section::class, function (Faker $faker) {
    $time = Carbon::parse($faker->time());
    return [
        'semester_id' => factory('App\Semester'),
        'name' => $faker->randomLetter,
        'start_time' => $time->format('H:i:s'),
        'end_time' => $time->addMinutes($faker->numberBetween(200, 400))->format('H:i:s'),
    ];
});
