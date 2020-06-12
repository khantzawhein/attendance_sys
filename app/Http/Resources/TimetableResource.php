<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TimetableResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $day = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return [
            'day' => $day[$this->day],
            'start_time' => Carbon::parse($this->start_time)->format('g:ia'),
            'end_time' => Carbon::parse($this->end_time)->format('g:ia')
        ];
    }
}
