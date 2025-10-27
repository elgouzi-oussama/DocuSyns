<?php

namespace App\Http\Controllers;

use App\Models\LicensesType;
use App\Models\User;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('permission:user.show', only: ['show']),
            new Middleware('permission:user.edit', only: ['edit', 'update']),
        ];
    }

    public function index()
    {
        if (Gate::allows('isAdmin')) {
            $countuser = User::where('role', 'user')->count();
            $countinvoice = Invoice::count();
        } else {
            $countuser = User::whereIn('role', ['admin', 'user'])->count();
            $countinvoice = Invoice::count();
        }

        return view('admin.dashboard', compact('countuser', 'countinvoice'));
    }

    /**
     * Show the admin profile.
     */
    public function show()
    {
        if (Gate::allows('isSuperAdmin')) {
            $admin = Auth::user();
            return view('admin.profile.show', compact('admin'));
        }

        return redirect()->route('super_admin.dashboard')
            ->with('error', __('admin.user.access_denied'));
    }

    /**
     * Show the profile edit page.
     */
    public function edit()
    {
        if (!Gate::allows('isSuperAdmin')) {
            return redirect()->route('super_admin.dashboard')
                ->with('error', __('admin.user.access_denied'));
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
                ->with('error', __('admin.user.access_denied'));
        }

        $user = Auth::user();

        $validated = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => "required|email|unique:users,email,{$user->id}",
                'password' => 'nullable|string|min:6|confirmed',
            ],
            [
                'name.required' => __('admin.user.name_required'),
                'email.required' => __('admin.user.email_required'),
                'email.email' => __('admin.user.email_invalid'),
                'email.unique' => __('admin.user.email_unique'),
                'password.min' => __('admin.user.password_min'),
                'password.confirmed' => __('admin.user.password_confirmed'),
            ]
        );

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return back()->with('success', __('admin.user.profile_updated'));
    }
}
