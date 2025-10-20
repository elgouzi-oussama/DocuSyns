<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    // ********************** index login
    public function indexin()
    {
        return view('login.signin');
    }



    // ***********      auth login
    public function auth(Request $request)
    {
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ]);
        $remember = $request->filled('remember'); // check if "remember me" is checked

        if (Auth::attempt($formFields, $remember)) {
            if (Auth::user()->role === 'user') {
                $request->session()->regenerate();
                return to_route('index');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Users only.']);
            }
        }
        return back()->withErrors([
            'email' => 'Invalid login or password',
        ]);
    }




    // *************     show sign up 
    public function create()
    {
        return view('login.signup');
    }


    // ********** create account
    public function store(Request $request)
    {
        $name = $request->firstName . " " . $request->lastName;
        User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return view('login.signin');
    }







    // ****************** logout
    public function logout()
    {
        Auth::logout();
        return to_route('index');
    }
}
