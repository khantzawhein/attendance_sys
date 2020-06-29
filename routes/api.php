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
        Route::post('students/{student}/approve', 'StudentController@approve');
        Route::post('students/{student}/disapprove', 'StudentController@disapprove');
        Route::post('sections/{section}/reset-code', 'SectionController@resetAccessCode');
        Route::get('sections/{section}/get-code', 'SectionController@getAccessCode');
        Route::put('teachers/{teacher}/change-password', 'TeacherController@changePassword');
        Route::put('students/{student}/change-password', 'StudentController@changePassword');
        Route::get('semesters/options', 'SemesterController@getOptions');
        Route::get('user/role', function () {
            return Auth::user()->role_name();
        });
        Route::get('my_classes', "SectionStudentController@index");
        Route::post('my_classes', "SectionStudentController@bind");
        Route::delete('my_classes/{class}', "SectionStudentController@unbind");

        Route::get('teacher-timetable', 'TimetableController@teacherTimetable');

        //Attendance Code
        Route::get('teacher-timetable/{timetable}/code', 'TimetableController@getCode');
        Route::delete('teacher-timetable/{timetable}/code', 'TimetableController@revokeCode');
        Route::post('teacher-timetable/{timetable}/code', 'TimetableController@extendCode');
        Route::post('attendance', 'StudentController@getAttendance')->middleware('throttle:30,1');

        Route::get('dashboard-data', 'DashboardController@dashboard');

        Route::get('/courses/{course}/attendances', 'AttendanceController@index');
        Route::post('courses/{course}/attendances/{attendance}/update-status', 'AttendanceController@updateStatus');
        Route::get('/my-attendances', 'AttendanceController@studentAttendance');

        Route::post('user/change-password', 'UserController@changePassword');
        Route::get('teacher/get-info', 'UserController@teacherInfoGet');
        Route::post('teacher/edit-info', 'UserController@teacherInfoEdit');
        Route::apiResources(
            [
                'courses' => 'CourseController',
                'students' => 'StudentController',
                'teachers' => 'TeacherController',
                'semesters' => 'SemesterController',
                'sections' => 'SectionController',
                'sections/{section}/classes' => 'TimetableController',
                'years' => 'YearController'
            ]
        );

    });

});
