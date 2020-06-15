<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SectionResource;
use App\Section;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SectionResource::collection(Section::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
           'semester_id' => ['required', 'exists:semesters,id'],
            'name' => ['required', 'unique:sections'],
            'start_time' => 'required|date_format:"g:ia"',
            'end_time' => 'required|after:start_time|date_format:"g:ia"'
        ]);

        $data['start_time'] = Carbon::parse($data['start_time'])->format('H:i');
        $data['end_time'] = Carbon::parse($data['end_time'])->format('H:i');

        Section::create($data);
        response('', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Section $section
     * @return Section
     */
    public function show(Section $section)
    {
        return new SectionResource($section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Section $section
     * @return void
     */
    public function update(Request $request, Section $section)
    {
        $data = $request->validate([
           'semester_id' => ['required', 'exists:semesters,id'],
            'name' => ['required', Rule::unique('sections')->ignore($section->id)],
            'start_time' => 'required|date_format:"g:ia"',
            'end_time' => 'required|after:start_time|date_format:"g:ia"'
        ]);
        $data['start_time'] = Carbon::parse($data['start_time'])->format('H:i');
        $data['end_time'] = Carbon::parse($data['end_time'])->format('H:i');

        $section->update($data);
        response('', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Section $section
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Section $section)
    {
        $section->delete();
        return response('', 201);
    }
}
