<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // User profile management

    public function show()
    {
        $user = Auth::user();
        return view('user.profile.show', compact('user'));
    }

    // Show the edit form --- IGNORE ---
    public function edit()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }
    public function update(Request $request)
    {
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

        return to_route('profile.show')->with('success', 'Profil mis à jour avec succès.');
    }
}
