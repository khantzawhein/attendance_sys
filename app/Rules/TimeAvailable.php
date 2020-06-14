<?php

namespace App\Rules;

use App\Timetable;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class TimeAvailable implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($end_time_value, $day)
    {
        $this->day = $day;
        $this->end_time_value = $end_time_value;
        try {
            $this->end_time = Carbon::parse($end_time_value)->format('H:i:s');
        }
        catch (\Exception $exception) {
            $this->end_time = false;
        }
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
        $this->value = $value;
        if ($this->end_time == false) {
            return false;
        }
        try {
            $value = Carbon::parse($value)->format('H:i:s');
        }
        catch (\Exception $exception) {
            return false;
        }

        $timetable = Timetable::where('day', $this->day)
                    ->whereRaw('(start_time BETWEEN ? AND ?',[$value, $this->end_time])
                    ->orWhereRaw('end_time BETWEEN ? AND ?',[$value, $this->end_time])
                    ->orWhereRaw('? BETWEEN start_time AND end_time',[$value])
                    ->orWhereRaw('? BETWEEN start_time AND end_time)',[$this->end_time])
                    ->get();
        if($timetable->isEmpty())
        {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return "Time between {$this->value} and {$this->end_time_value} in not available.";
    }
}
