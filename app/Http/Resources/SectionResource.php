<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
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
            'name' => $this->name,
            'semester' => $this->semester->semester_name,
            'semester_id' => $this->semester->id,
            'academic_year' => $this->semester->year->academic_year,
            'year' => $this->semester->year->name,
            'start_time' => Carbon::parse($this->start_time)->format('g:ia'),
            'end_time' => Carbon::parse($this->end_time)->format('g:ia')
        ];
    }
}
