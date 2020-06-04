<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'module_no' => $this->module_no,
            'module_name' => $this->module_name,
            'teacher_name' => $this->teacher->user->name,
            'term' => $this->term->name,
        ];
    }
}
