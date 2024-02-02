<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //show register form
    public function create()
    {
        return view('users.register');
    }

    //store User
    public function store(Request $request)
    {
        $form_fields = $request->validate([
            'name' => 'required|min:3',
            'email' => ['required', Rule::unique('users', 'email')],
            'password' =>'required|confirmed|min:8'
        ]);

        //hash password
        $form_fields['password'] = bcrypt($form_fields['password']);

        //create User
        $user = User::create($form_fields);
        
        //authenticate user login
        auth()->login($user);

        return redirect('/')->with('message', 'User logged in');
    }


    //log User out
    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been logged out!');
    }


    //show login form
    public function login()
    {
        return view('users.login');
    }

    //authenticate User
    public function authenticate(Request $request)
    {
        $form_fields = $request->validate([
            'email' => ['required', 'email'],
            'password' =>'required'
        ]);

        if(auth()->attempt($form_fields))
        {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are logged in!');
        }

        return back()->withErrors(['email'=>'Invalid Credentials'])->onlyInput('email');
    }
}
