<?php

namespace App\Rules;

use App\Attendance;
use App\Code;
use App\Student;
use App\Timetable;
use Illuminate\Contracts\Validation\Rule;

class AttendanceCodeUsed implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @param Student $student
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
        $code =  Code::where('code', $value)->first();
        if ($code == null) {
            return false;
        }
        $timetable_id = $code->timetable->id;
        return $student = $this->student->attendances()->where('timetable_id', $timetable_id)->get()->isEmpty();

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The code was either used or invalid.';
    }
}
