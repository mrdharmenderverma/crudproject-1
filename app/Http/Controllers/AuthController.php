<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function RegisterView(){
        return view('register');
    }
    public function loginView(){
        return view('login');
    }

    // Admin Login Function
    public function loginStore(Request $request){

        // Validate Data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/employees');
        }
        // dd($request->all());
        // return back()->withErrors(['email' => 'Invalid email or password.']);
        return redirect('/')->with('error', 'Email or Password Incorrect');

        // return view('authentication-login');
    }

    //Login Function
    public function RegisterStore(Request $request){
        // Validate Data
        // dd($request->all());
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // save data
        User::create([
            'email'=> $request->email,
            'password'=> Hash::make($request->password),
        ]);
        
        //login user 
        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect('/login')->with('success', 'Register Successfully');
        }
        return redirect('/')->with('error', 'Email or Password Incorrect');
    }
}
