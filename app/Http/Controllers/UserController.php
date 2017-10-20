<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;


class UserController extends Controller
{
    public function profile()
    {
        return view('profile', array('user' => Auth::user()));
    }
    public function update_avatar(Request $request){
        //handle update user info

    }
}
