<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudent;
use App\Providers\RouteServiceProvider;
use App\Student;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
Use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(StoreStudent $request)
    {
        $request->validated();
        event(new Registered($user = $this->create($request->all())));

        //disable auto login
//        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new Response('', 201)
                    : redirect()->route('login')->with('registered', "You have been registered and is pending approval from administrator.");
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $nrc = "{$data['nrc_region']}/{$data['nrc_township']}({$data['nrc_type']}){$data['nrc_number']}";
        $student = new Student([
            'urn' => isset($data['urn']) ? $data['urn'] : null,
            'nrc' => $nrc,
            'batch' => $data['batch'],
            'father_name' => $data['father_name'],
            'phone' => $data['phone']
        ]);
        $user->student()->save($student);
        $user->assignRole('student');
        return $user;
    }
}
