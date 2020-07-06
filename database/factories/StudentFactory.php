<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Student;
use Faker\Generator as Faker;

$factory->define(Student::class, function (Faker $faker) {
    return [
        'user_id' => factory("App\User"),
        'urn' => $faker->numberBetween(100000, 500000),
        'nrc' => $faker->uuid,
        'phone' => $faker->phoneNumber,
        'father_name' => $faker->name('male'),
        'batch' => $faker->year()
    ];
});
$factory->afterCreating(Student::class, function ($student, $faker) {
    $student->user->assignRole('student');
});
