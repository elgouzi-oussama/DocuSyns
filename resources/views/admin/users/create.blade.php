@extends('admin.layout')

@section('title', 'Créer un utilisateur')
@section('page_title', 'Ajouter un utilisateur')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg mx-auto">
    <form method="POST" action="{{ route('super_admin.users.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700">Nom</label>
            <input type="text" name="name" class="w-full border-gray-300 rounded px-3 py-2"
                value="{{ old('name') }}" required>
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" name="email" class="w-full border-gray-300 rounded px-3 py-2"
                value="{{ old('email') }}" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Mot de passe</label>
            <input type="password" name="password" class="w-full border-gray-300 rounded px-3 py-2" required>
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Confirmer mot de passe</label>
            <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded px-3 py-2" required>
        </div>
        <!-- ✅ Role Selection -->
        <div class="mb-6">
            <label class="block text-gray-700 mb-1">Rôle</label>
            <select name="role" class="w-full border-gray-300 rounded px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                <option value="">-- Sélectionner un rôle --</option>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Utilisateur</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
            </select>
            @error('role') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('super_admin.users.index') }}" class="px-4 py-2 bg-gray-300 rounded">Annuler</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enregistrer</button>
        </div>
    </form>
</div>
@endsection