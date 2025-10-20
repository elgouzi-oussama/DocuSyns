<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{

    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $countuser = User::where('role', 'user')->count();

            $countinvoice = Invoice::count();
            return view('admin.dashboard', compact('countuser', 'countinvoice'));
        } else {
            $countuser =  User::whereIn('role', ['admin', 'user'])->count();
            $countinvoice = Invoice::count();
        }

        return view('admin.dashboard', compact('countuser', 'countinvoice'));

        // $invoices = Invoice::with('user')->latest()->paginate(10);
    }
    /**
     * Show the admin profile.
     */
    public function show()
    {
        if (Gate::allows('isSuperAdmin')) {
            $admin = Auth::user();
            return view('admin.profile.show', compact('admin'));
        } else {

            return redirect()->route('super_admin.dashboard')
                ->with('error', 'Accès refusé.');
        }
    }
    /**
     * Show the profile edit page.
     */
    public function edit()
    {
        if (!Gate::allows('isSuperAdmin')) {
            return redirect()->route('super_admin.dashboard')
                ->with('error', 'Accès refusé.');
        }
        $admin = Auth::user();
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update admin profile info.
     */
    public function update(Request $request)
    {
        if (!Gate::allows('isSuperAdmin')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Accès refusé.');
        }
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        /** @var \App\Models\User $user */

        $user->update($validated);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }
}
