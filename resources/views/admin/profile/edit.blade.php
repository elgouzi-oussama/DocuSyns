@extends('admin.layout')

@section('title', 'Mon Profil')
@section('page_title', 'Profil Administrateur')

@section('content')
@if (session('success'))
<div class="mb-4 bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3">
    {{ session('success') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg mx-auto">
    <h3 class="text-lg font-semibold mb-4">Modifier mes informations</h3>

    <form action="{{ route('super_admin.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 text-sm mb-1">Nom</label>
            <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm mb-1">Nouveau mot de passe (optionnel)</label>
            <input type="password" name="password"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm mb-1">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-between">
            <a href="{{ route('super_admin.dashboard') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                Retour
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Sauvegarder
            </button>
        </div>
    </form>
</div>
@endsection