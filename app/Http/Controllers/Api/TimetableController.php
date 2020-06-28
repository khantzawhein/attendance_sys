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
use Ramsey\Uuid\Type\Time;

class TimetableController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Timetable::class, 'timetable');
    }

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

    public function teacherTimetable()
    {
        $this->authorize('timetable', Timetable::class);
        return request()->user()->teacher->getTeacherTimetable();
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
            'course_id' => 'required|exists:courses,id'
        ]);
        $timetable = new Timetable([
            'day' => $data['day'],
            'start_time' => Carbon::parse($data['start_time'])->format('H:i'),
            'end_time' => Carbon::parse($data['end_time'])->subMinute(1)->format('H:i'),
            'course_id' => $data['course_id']
        ]);
        $section->timetable()->save($timetable);
        return response('', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     * @param Timetable $timetable
     * @return void
     */
    public function show(Section $section, Timetable $class)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timetable $class)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Section $section
     * @param Timetable $class
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Section $section,Timetable $class)
    {
        $class->delete();
        return response('', 200);
    }

    public function getCode(Timetable $timetable)
    {
        $this->authorize('getCode', $timetable);
        $now = Carbon::now();

        $semester_end = $timetable->section->semester->end_date;
        $end_date = Carbon::parse($semester_end);

        if (Carbon::now()->startOfDay()->greaterThan($end_date))
        {
            return response(['code' => 'This course\'s semester has finished.', 'expire' ], 200);
        }

        if($timetable->code == null) {
            return $timetable->generateCode();
        }
        else if($now->greaterThan($timetable->code->expire_at))
        {
            $timetable->revokeCode();
            return $timetable->generateCode();
        }
        return $timetable->code;
        //return code and expire_at
    }

    public function revokeCode(Timetable $timetable)
    {
        $this->authorize('revokeCode', $timetable);
        //revoke all timetable code
        return $timetable->revokeCode();
    }
}
