<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminUtilisateurController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
            new Middleware('permission:user.index', only: ['index']),
            new Middleware('permission:user.show', only: ['show']),
            new Middleware('permission:user.create', only: ['create', 'store']),
            new Middleware('permission:user.edit', only: ['edit', 'update']),
            new Middleware('permission:user.delete', only: ['destroy']),
        ];
    }

    public function index()
    {
        if (Gate::allows('isSuperAdmin')) {
            $users = User::whereIn('role', ['admin', 'user'])->get();
        } else {
            $users = User::where('role', 'user')->get();
        }

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role'     => 'required|in:user,admin',
        ], [
            'name.required'     => __('admin.user.validation.name_required'),
            'email.required'    => __('admin.user.validation.email_required'),
            'email.email'       => __('admin.user.validation.email_email'),
            'email.unique'      => __('admin.user.validation.email_unique'),
            'password.required' => __('admin.user.validation.password_required'),
            'password.confirmed' => __('admin.user.validation.password_confirmed'),
            'password.min'      => __('admin.user.validation.password_min'),
            'role.required'     => __('admin.user.validation.role_required'),
            'role.in'           => __('admin.user.validation.role_in'),
        ]);


        if (Gate::allows('isTrial')) {
            $allowedUsers = 3;
            $allowedAdmins = 1;
            $totalAllowed = 4;
        } else {
            $license = \App\Models\License::first();
            $allowedUsers = $license->getFeature('users');
            $allowedAdmins = $license->getFeature('admins');
            $totalAllowed = $license->totalAccountsAllowed();
        }

        $currentUsers = User::where('role', 'user')->count();
        $currentAdmins = User::where('role', 'admin')->count();
        $currentTotal = $currentUsers + $currentAdmins;
        // 🚫 Check role restrictions based on license
        if ($request->role === 'admin' && $currentAdmins >= $allowedAdmins) {
            return back()->withErrors([
                'role' => __('admin.user.errors.admin_limit_reached', ['max' => $allowedAdmins]),
            ])->withInput();
        }

        if ($request->role === 'user' && $currentUsers >= $allowedUsers) {
            return back()->withErrors([
                'role' => __('admin.user.errors.user_limit_reached', ['max' => $allowedUsers]),
            ])->withInput();
        }

        if ($currentTotal >= $totalAllowed) {
            return back()->withErrors([
                'role' => __('admin.user.errors.total_limit_reached', ['max' => $totalAllowed]),
            ])->withInput();
        }


        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role'     => $request->role,
        ]);
        return redirect()->route(userRoute('users.index'))
            ->with('success', __('admin.user.success.created'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6|confirmed',
            'role'     => 'nullable|string|in:user,admin,superadmin',
        ], [
            'name.required'     => __('admin.user.validation.name_required'),
            'email.required'    => __('admin.user.validation.email_required'),
            'email.email'       => __('admin.user.validation.email_email'),
            'email.unique'      => __('admin.user.validation.email_unique'),
            'password.confirmed' => __('admin.user.validation.password_confirmed'),
            'password.min'      => __('admin.user.validation.password_min'),
            'role.in'           => __('admin.user.validation.role_in'),
        ]);

        if ($request->role === 'admin' && User::where('role', 'admin')->exists() && $user->id !== User::where('role', 'admin')) {
            return back()->withErrors(['role' => __('admin.user.errors.one_admin_only')])->withInput();
        }

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route(userRoute('users.index'))
            ->with('success', __('admin.user.success.updated'));
    }

    public function destroy(User $user)
    {

        $user->delete();

        return redirect()->route(userRoute('users.index'))
            ->with('success', __('admin.user.success.deleted'))
            ->with('deleted', true);
    }
}
