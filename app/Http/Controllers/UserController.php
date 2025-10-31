<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use thiagoalessio\TesseractOCR\TesseractOCR;

class UserController extends Controller
{
    public function index()
    {

        // $text = (new TesseractOCR(storage_path('app/public/page.png')))
        //     ->lang('eng', 'fra')
        //     ->run();
        // $parts = preg_split('/Commande par|Livre a|Commande a/i', $text, -1, PREG_SPLIT_NO_EMPTY);

        // $structured = [
        //     'Commande par' => trim($parts[0] ?? ''),
        //     'Livre à'      => trim($parts[1] ?? ''),
        //     'Commandé à'   => trim($parts[2] ?? ''),
        // ];

        // var_dump($structured);

        return view('user.index');
    }

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
