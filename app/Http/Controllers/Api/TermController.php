<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TermResource;
use App\Term;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function rules()
    {
        return [
           'name' => 'required|unique:terms',
           'start_date' => 'required|date_format:yy/m/d',
           'end_date' => 'required|date_format:yy/m/d|after:start_date'
        ];
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TermResource::collection(Term::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Term $term
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show(Term $term)
    {
        return $term;
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
        Term::create($data);
        return response('', 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Term $term)
    {
        $data = $request->validate($this->rules());
        $term->update($data);
        return response('', 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Term $term
     * @return void
     * @throws \Exception
     */
    public function destroy(Term $term)
    {
        $term->delete();
        return response('', 201);
    }
}
