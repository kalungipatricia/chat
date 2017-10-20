<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Mail;
use App\Mail\verifyEmail;
use Session;


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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users', // validator
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        Session::flash('status', 'Registered! but verify your email to activate your account');
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'], // create
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'verifyToken'=>Str::random(40),


        ]);
        $thisUser = User::findOrFail($user ->id);
        $this ->sendEmail($thisUser);
        return $user;
    }
//added in functions
    public function verifyEmailFirst()
    {
        return view('email.verifyEmailFirst');
    }

    public function sendEmail($thisUser)
    {
        Mail::to ($thisUser['email'])->send(new verifyEmail($thisUser));
    }



  public function sendEmailDone($email,$verifyToken)
  {
      $user = User::where(['email'=>$email,'$verifyToken'=>$verifyToken])->first();
      if ($user){
         return user::where(['email'=>$email,'$verifyToken'=>$verifyToken])->update(['status'=>'1','verifyToken'=>NULL]);
      }
      else{
         return 'user not found';
      }
  }

}

