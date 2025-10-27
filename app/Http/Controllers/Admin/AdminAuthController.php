<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.sign.login');
    }

    public function login(Request $request)
    {
        $formFields = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ], [
            'email.required' => __('admin.auth.validation.email_required'),
            'email.email' => __('admin.auth.validation.email_invalid'),
            'password.required' => __('admin.auth.validation.password_required'),
            'password.min' => __('admin.auth.validation.password_min'),
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($formFields, $remember)) {
            if (Auth::user()->must_change_password == true) {
                return to_route('admin.password.request');
            }

            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return to_route('admin.dashboard');
            } elseif (Auth::user()->role === 'super_admin') {
                return to_route('super_admin.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => __('admin.auth.access_denied')]);
            }
        }

        return back()->withErrors([
            'email' => __('admin.auth.invalid_credentials'),
        ]);
    }

    public function showLinkRequestForm()
    {
        return view('admin.sign.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();

        if (!Gate::allows('isAdmin')) {
            return redirect()->route('super_admin.dashboard')
                ->with('success', __('admin.auth.password_changed'));
        } else {
            return redirect()->route('admin.dashboard')
                ->with('success', __('admin.auth.password_changed'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', __('admin.auth.logout_success'));
    }
}
