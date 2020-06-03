<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'module_no' => "CS-{$faker->randomNumber(4)}",
        'module_name' => "TestCourse_{$faker->randomNumber(3)}",
        'teacher_id' => $faker->numberBetween(1, 5)
    ];
});
