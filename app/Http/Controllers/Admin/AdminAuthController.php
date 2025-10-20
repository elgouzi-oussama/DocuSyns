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
        ]);
        $remember = $request->filled('remember'); // check if "remember me" is checked

        if (Auth::attempt($formFields, $remember)) {
            if (Auth::user()->must_change_password == true) {
                return to_route('admin.password.request');
            }
            if (Auth::user()->role === 'admin') {
                $request->session()->regenerate();
                return to_route('admin.dashboard');
            } elseif (Auth::user()->role === 'super_admin') {
                $request->session()->regenerate();
                return to_route('super_admin.dashboard');
            } else {
                Auth::logout();
                return back()->withErrors(['email' => 'Access denied. Admin only.']);
            }
        }
        return back()->withErrors([
            'email' => 'Invalid login or password',
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
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->must_change_password = false;
        $user->save();
        if (!Gate::allows('isAdmin')) {
            return redirect()->route('super_admin.dashboard')->with('success', 'Mot de passe changé avec succès.');
        } else {
            return redirect()->route('admin.dashboard')->with('success', 'Mot de passe changé avec succès.');
        }
    }

    // ...existing code...
    public function logout(Request $request)
    {
        // Use the default guard since login uses Auth::attempt()
        Auth::logout();

        // Invalidate session and regenerate token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Déconnexion réussie.');
    }
    // ...existing code...
}
