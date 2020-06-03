<?php

namespace App\Http\Controllers;

use App\Code;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CodeGenerateController extends Controller
{
    public function index()
    {
        $otp = $this->generate();
        return view('code_generate', compact(['otp']));
    }

    public function generate()
    {
        $code = str_pad(rand(10, 999999), 6, 0, STR_PAD_LEFT);
        $expire_at = Carbon::now()->addSecond(180)->toDateTimeString();
        $db = Code::updateOrCreate([
            'timetable_id' => 1
        ], [
            'timetable_id' => 1,
            'code' => $code,
            'expire_at' => $expire_at
        ]);
        return ['code' => $code, 'expire_at' => $expire_at];
    }
}
