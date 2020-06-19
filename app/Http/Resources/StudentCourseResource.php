<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentCourseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'course_id' => $this->id,
            'module_no' => $this->module_no,
            'module_name' => $this->module_name,
            'teacher_name' => $this->teacher->user->name,
            'academic_year' => $this->semester->year->academic_year,
            'year' => $this->semester->year->name,
            'semester' => $this->semester->semester_name
        ];
    }
}
