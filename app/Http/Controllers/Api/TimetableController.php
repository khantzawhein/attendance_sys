<?php

namespace App\Http\Controllers\Api;

use App\Course;
use App\Http\Controllers\Controller;
use App\Http\Resources\TimetableResource;
use App\Rules\TimeAvailable;
use App\Section;
use App\Timetable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Course $course
     * @return \Illuminate\Http\Response
     */
    public function index(Section $section)
    {
        return TimetableResource::collection($section->timetable)->collection->groupBy('day');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Section $section
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function store(Section $section, Request $request)
    {
        $data = $request->validate([
            'day' => 'required|numeric|between:0,6',
            'start_time' => ['required','date_format:"g:ia"', new TimeAvailable($request->end_time, $request->day)],
            'end_time' => 'required|after:start_time|date_format:"g:ia"',
            'course_id' => 'required'
        ]);
        $timetable = new Timetable([
            'day' => $data['day'],
            'start_time' => Carbon::parse($data['start_time'])->format('H:i'),
            'end_time' => Carbon::parse($data['end_time'])->subMinute(1)->format('H:i')
        ]);
        $course = Course::findOrFail($data['course_id']);
        $section->timetable()->save($timetable);
        $course->bindToTimetable($timetable);

        return response('', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @param Timetable $timetable
     * @return void
     */
    public function show(Section $section, Timetable $timetable)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
