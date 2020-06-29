<?php

use App\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            [
                'name' => 'superadmin',
                'label' => 'SuperAdmin'
            ],
            [
                'name' => 'teacher',
                'label' => 'Teacher'
            ],
            [
                'name' => 'student',
                'label' => 'Student'
            ]
        ]);
    }
}
