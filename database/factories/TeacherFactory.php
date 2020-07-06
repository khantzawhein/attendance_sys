<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Teacher;
use Faker\Generator as Faker;

$factory->define(Teacher::class, function (Faker $faker) {
    return [
        'user_id' => factory("App\User"),
        'role' => 'AP',
        'department' => "Computer science"
    ];
});
$factory->afterCreating(Teacher::class, function ($teacher, $faker) {
    $teacher->user->assignRole('teacher');
});
