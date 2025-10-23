<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminUtilisateurController extends Controller implements HasMiddleware
{

    /**
     * Get the middleware that should be assigned to the controller.
     */
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
        return view('admin.users.create');
    }

    // Store a new user
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'role' => 'required|in:user,admin',
        ]);

        // ✅ Check if there’s already an admin
        if ($request->role === 'admin' && User::where('role', 'admin')->exists()) {
            return back()->withErrors(['role' => 'Un seul administrateur est autorisé.'])->withInput();
        } elseif ($request->role === 'admin' && auth()->user()->role !== 'super_admin') {
            return back()->withErrors(['role' => 'Vous n\'êtes pas autorisé.'])->withInput();
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

        return view('admin.users.edit', compact('user'));
    }

    // Update an existing user
    public function update(Request $request, User $user)
    {


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


        $user->delete();
        return redirect()->route('super_admin.users.index')->with('success', 'Utilisateur supprimé avec succès.')->with('deleted', true);
    }


    // Show permissions management page
    public function permissions(Request $request, User $user)
    {
        $permissions = $request->input('permissions', []);
        $user->permissions = $permissions;
        $user->save();
        if (Gate::allows('isSuperAdmin')) {
            return redirect()->route('super_admin.users.permissions.index')->with('success', 'Permissions mises à jour avec succès.');
        } else {
            return redirect()->route('admin.users.permissions.index')->with('success', 'Permissions mises à jour avec succès.');
        }
    }
}
