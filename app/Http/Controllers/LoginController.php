<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('pages.login');
    }
    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'bail|required|email',
            'password' => 'bail|required',

        ],[
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
        ]);

        if(Auth::attempt(['email' => $request->email,'password' => $request->password]))
        {
            return redirect('/');
        }
        else
        {
            return redirect()->back()->with('error','error');
        }

    }

    public function logout()
    {
        if(Auth::check())
        {
            Auth::logout();
            return redirect('/login');
        }
    }
}
