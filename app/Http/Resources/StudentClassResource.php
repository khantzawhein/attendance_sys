<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StudentClassResource extends JsonResource
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
            'id' => $this->id,
            'section_name' => $this->name,
            'academic_year' => $this->semester->year->academic_year,
            'year' => $this->semester->year->name,
            'semester_name' => $this->semester->semester_name,
        ];
    }
}
