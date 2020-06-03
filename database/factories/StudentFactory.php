<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'user_id' => factory("App\User"),
        'urn' => $faker->numberBetween(100000, 500000)
    ];
});
