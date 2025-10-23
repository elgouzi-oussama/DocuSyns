<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{

    // ********************** index login
    public function index()
    {
        return view('user.login.signin');
    }



    // ***********      auth login
    public function auth(Request $request)
    {
        // 🧠 Validation avec messages multilingues automatiques
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ], [
            'email.required' => __('auth.errors.email_required'),
            'email.email' => __('auth.errors.email_invalid'),
            'password.required' => __('auth.errors.password_required'),
            'password.min' => __('auth.errors.password_min'),
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($formFields, $remember)) {

            if (Auth::user()->role === 'user') {

                if ($request->has('remember')) {
                    Cookie::queue('email', $request->email, 60 * 24 * 30);
                    Cookie::queue('password', $request->password, 60 * 24 * 30);
                } else {
                    Cookie::queue(Cookie::forget('email'));
                    Cookie::queue(Cookie::forget('password'));
                }

                $request->session()->regenerate();
                return to_route('index');
            }

            Auth::logout();
            return back()->withErrors(['email' => __('auth.errors.access_denied')]);
        }

        return back()->withErrors([
            'email' => __('auth.errors.invalid_login'),
        ]);
    }
    // ****************** logout
    public function logout()
    {
        Auth::logout();
        return to_route('index');
    }
}
