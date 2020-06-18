<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Year;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class YearController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Year::class, 'year');
    }

    public function rules()
    {
        return [
            'academic_year' => ['required','date_format:Y',Rule::unique('years', 'academic_year')
                    ->where(function ($query){
                        return $query->where('name', request('name'));
                    })],
            'name' => 'required|string',

        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Year::all();
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
        Year::create($data);
        return response('', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Year $year)
    {
        return $year;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Year $year
     * @return void
     */
    public function update(Request $request, Year $year)
    {
        $data = $request->validate(['academic_year' => ['required','date_format:Y',Rule::unique('years', 'academic_year')
                    ->where(function ($query){
                        return $query->where('name', request('name'));
                    })->ignore($year->id)],
            'name' => 'required|string']);
        $year->update($data);
        return response('', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Year $year
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Year $year)
    {
        $year->delete();
        return response('', 200);
    }
}
