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
        Route::get('user/name', function () {
            return auth()->user()->name;
        });

        //Student Management
        Route::post('students/{student}/approve', 'StudentController@approve');
        Route::post('students/{student}/disapprove', 'StudentController@disapprove');

        //Class Management
        Route::post('sections/{section}/reset-code', 'SectionController@resetAccessCode');
        Route::get('sections/{section}/get-code', 'SectionController@getAccessCode');
        Route::get('sections/{section}/get-students', 'SectionController@getStudents');
        Route::get('sections/{section}/get-nStudents', 'SectionController@getNotEnrolledStudents');
        Route::post('sections/{section}/unregister', 'SectionStudentController@unbind');
        Route::post('sections/{section}/enroll', 'SectionStudentController@enroll');

        //Teacher Management
        Route::put('teachers/{teacher}/change-password', 'TeacherController@changePassword');
        Route::put('students/{student}/change-password', 'StudentController@changePassword');
        Route::get('semesters/options', 'SemesterController@getOptions');
        //Get Current Role
        Route::get('user/role', function () {
            return Auth::user()->role_name();
        });
        //Class Management For Students
        Route::get('my_classes', "SectionStudentController@index");
        Route::post('my_classes', "SectionStudentController@bind");

        //Individual Teacher's Timetable
        Route::get('teacher-timetable', 'TimetableController@teacherTimetable');

        //Attendance Code Management
        Route::get('teacher-timetable/{timetable}/code', 'TimetableController@getCode');
        Route::delete('teacher-timetable/{timetable}/code', 'TimetableController@revokeCode');
        Route::post('teacher-timetable/{timetable}/code', 'TimetableController@extendCode');
        Route::post('attendance', 'StudentController@getAttendance')->middleware('throttle:30,1');

        //Dashboard Data
        Route::get('dashboard-data', 'DashboardController@dashboard');

        //Teacher's Attendance Management
        Route::get('/courses/{course}/attendances', 'AttendanceController@index');
        Route::post('courses/{course}/attendances/{attendance}/update-status', 'AttendanceController@updateStatus');
        Route::get('/my-attendances', 'AttendanceController@studentAttendance');

        //Edit own personal data.
        Route::post('user/change-password', 'UserController@changePassword');
        Route::get('teacher/get-info', 'UserController@teacherInfoGet');
        Route::post('teacher/edit-info', 'UserController@teacherInfoEdit');

        //Student request edit personal data
        Route::get('student/get-info', 'UserController@studentInfoGet');
        Route::post('student/edit-info', 'UserController@studentInfoEdit');
        Route::post('student_info_review/{student_info_review}/approve', 'StudentInfoReviewController@approveRequest');
        Route::get('student_info_review/count', 'StudentInfoReviewController@count');

        //Role Management
        Route::get('roles', 'RoleController@index');
        Route::post('roles/super-admin', 'RoleController@setAsSuperAdmin');
        Route::delete('roles/super-admin', 'RoleController@removeAsSuperAdmin');

        Route::apiResources(
            [
                'courses' => 'CourseController',
                'students' => 'StudentController',
                'teachers' => 'TeacherController',
                'semesters' => 'SemesterController',
                'sections' => 'SectionController',
                'sections/{section}/classes' => 'TimetableController',
                'years' => 'YearController',
                'student_info_review' => 'StudentInfoReviewController'
            ]
        );

    });

});
