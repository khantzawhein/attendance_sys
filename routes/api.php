<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::middleware("auth:sanctum")->group(function () {
    Route::namespace('Api')->group(function() {
        Route::apiResources(
            [
                'courses' => 'CourseController',
                'students' => 'StudentController',
                'teachers' => 'TeacherController',
                'terms' => 'TermController'
            ]
        );
        Route::post('students/{student}/approve', 'StudentController@approve');
        Route::post('students/{student}/disapprove', 'StudentController@disapprove');
        Route::post('courses/{course}/reset-code', 'CourseController@resetAccessCode');
        Route::get('courses/{course}/get-code', 'CourseController@getAccessCode');
    });

});
