<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SemesterResource extends JsonResource
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
            'academic_year' => $this->year->academic_year,
            'year' => $this->year->name,
            'semester_name' => $this->semester_name,
            'start_date' => Carbon::parse($this->start_date)->format('d-M-yy'),
            'end_date' => Carbon::parse($this->end_date)->format('d-M-yy')
        ];
    }
}
