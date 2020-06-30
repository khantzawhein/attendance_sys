<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Student;
use App\StudentInfoChange;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentInfoReviewController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', StudentInfoChange::class);

        $requesters = StudentInfoChange::with('student.user:id,name')->whereNull('approved_at')->get();

        return $requesters;
    }
    public function show(StudentInfoChange $student_info_review)
    {
        $this->authorize('view', $student_info_review);
        $student_request = $student_info_review->toArray();
        $student_arr =  $student_info_review->student->toArray();
        $diff = collect(array_diff($student_arr, $student_request))->except(['user_id', 'created_at', 'updated_at']);
        $diffKeys = array_keys($diff->toArray());
        $student_db = Student::where('students.id', $student_info_review->student_id)->join('users', 'users.id', 'students.user_id')->select('students.*', 'users.name', 'users.email')->first();
        $data = collect([]);

        foreach ($diffKeys as $key)
        {
            $data->put($key, [
                'old' => $student_db[$key],
                'new' => $student_request[$key],
            ]);
        }
        return $data;
    }
    public function approveRequest(StudentInfoChange $student_info_review)
    {
        $sir = $student_info_review;
        $this->authorize('approveRequest', $sir);
        $sir->approved_at = Carbon::now();
        $sir->save();

        $sir->student->update([
           'batch' => $sir->batch,
            'father_name' => $sir->father_name,
            'nrc' => $sir->nrc,
            'phone' => $sir->phone,
            'urn' => $sir->urn
        ]);

        $sir->student->user([
            'name' => $sir->name,
            'email' => $sir->email
        ]);
        return response('' ,201);
    }

    public function destroy(StudentInfoChange $student_info_review)
    {
        $this->authorize('delete', $student_info_review);
        $student_info_review->delete();
        return response('' ,201);
    }

    public function count()
    {
        $this->authorize('viewAny', StudentInfoChange::class);
        $count = StudentInfoChange::whereNull('approved_at')->count();
        return $count;
    }
}
