<?php

use App\Ability;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $abilities = ['view-course', 'create-course', 'manage-course',
            'view-section', 'manage-section', 'create-section',
            'view-semester', 'manage-semester', 'create-semester',
            'view-student', 'manage-student', 'create-student',
            'view-teacher', 'manage-teacher', 'create-teacher',
            'view-timetable', 'manage-timetable', 'create-timetable',
            'view-year', 'manage-year', 'create-year'];

        foreach ($abilities as $ability)
        {
            Ability::create(['name' => $ability]);
        }
    }
}
