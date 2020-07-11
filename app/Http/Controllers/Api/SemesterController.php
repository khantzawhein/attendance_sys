<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SemesterCollection;
use App\Http\Resources\SemesterResource;
use App\Semester;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SemesterController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Semester::class, 'semester');
    }

    public function rules()
    {
        return [
            'year_id' => 'required|exists:years,id',
           'semester_name' => ['required', Rule::unique('semesters')->where(
               function($query){
                   return $query->where('year_id', request('year_id'));
               }
           )],
           'start_date' => 'required|date_format:Y-m-d',
           'end_date' => 'required|date_format:Y-m-d|after:start_date'
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SemesterResource::collection(Semester::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Semester $semester
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show(Semester $semester)
    {
        return $semester;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        Semester::create($data);
        return response('', 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Semester $semester)
    {
        $data = $request->validate([
            'year_id' => 'required|exists:years,id',
            'semester_name' => ['required', Rule::unique('semesters')->ignore($semester->id)->where(
               function($query){
                   return $query->where('year_id', request('year_id'));
               }
           )],
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after:start_date'
        ]);
        $semester->update($data);
        return response('', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Semester $semester
     * @return void
     * @throws \Exception
     */
    public function destroy(Semester $semester)
    {
        $semester->delete();
        return response('', 201);
    }

    public function getOptions()
    {
        $semesters = Semester::with('year')->get();
        $data = collect([]);
        foreach ($semesters as $semester)
        {
            $data->push([
                'id' => $semester->id,
                'text' => "{$semester->year->academic_year}/{$semester->year->name}/{$semester->semester_name}"
            ]);
        }
        return $data;
    }
}
