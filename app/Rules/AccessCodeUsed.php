<?php

namespace App\Rules;

use App\Course;
use App\Student;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class AccessCodeUsed implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Student $student)
    {
        $this->student = $student;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $result = Course::where('access_code', $value)->first()->student->contains($this->student);
         return !$result;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The access code is already used.';
    }
}
