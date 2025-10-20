<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class AdminUtilisateurController extends Controller
{
    // Display all users
    public function index()
    {
        if (Gate::allows('isSuperAdmin')) {
            $users = User::whereIn('role', ['admin', 'user'])->get(); // Super admin can see all users
        } else {
            $users = User::where('role', 'user')->get(); // Other admins see only normal users
        }

        return view('admin.users.index', compact('users'));
    }
    public function show(User $user)
    {

        return view('admin.users.show', compact('user'));
    }


    // Show form to create a new user
    public function create()
    {
        if (!Gate::allows('isSuperAdmin')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Accès refusé.');
        }

        return view('admin.users.create');
    }

    // Store a new user
    public function store(Request $request)
    {
        if (!Gate::allows('isSuperAdmin')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Accès refusé.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:user,admin',
        ]);

        // ✅ Check if there’s already an admin
        if ($request->role === 'admin' && User::where('role', 'admin')->exists()) {
            return back()->withErrors(['role' => 'Un seul administrateur est autorisé.'])->withInput();
        }

        // ✅ Check if there are already two users
        if ($request->role === 'user' && User::where('role', 'user')->count() >= 2) {
            return back()->withErrors(['role' => 'Seulement deux utilisateurs sont autorisés.'])->withInput();
        }

        // ✅ Create user if limits are respected
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('super_admin.users.index')->with('success', 'Utilisateur créé avec succès.');
    }

    // Show form to edit an existing user
    public function edit(User $user)
    {
        if (!Gate::allows('isSuperAdmin')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Accès refusé.');
        }

        return view('admin.users.edit', compact('user'));
    }

    // Update an existing user
    public function update(Request $request, User $user)
    {
        if (!Gate::allows('isSuperAdmin')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Accès refusé.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'nullable|string|in:user,admin,superadmin',
        ]);
        if ($request->role === 'admin' && User::where('role', 'admin')->exists() && $user->id !== User::where('role', 'admin')->first()->id) {
            return back()->withErrors(['role' => 'Un seul administrateur est autorisé.'])->withInput();
        }






        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('super_admin.users.index')->with('success', 'Utilisateur modifié avec succès.');
    }

    // Delete a user
    public function destroy(User $user)
    {
        if (!Gate::allows('isSuperAdmin')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Accès refusé.');
        }

        $user->delete();
        return redirect()->route('super_admin.users.index')->with('success', 'Utilisateur supprimé avec succès.')->with('deleted', true);
    }
}
