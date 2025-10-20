@extends('admin.layout')

@section('title', 'Créer une facture')
@section('page_title', 'Nouvelle facture')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg mx-auto">

    @if (session('error'))
    <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3">
        {{ session('error') }}
    </div>
    @endif
    @can('isAdmin')
    <form method="POST" action="{{ route('admin.invoices.store') }}" enctype="multipart/form-data">
        @endcan
        @cannot('isAdmin')
        <form method="POST" action="{{ route('super_admin.invoices.store') }}" enctype="multipart/form-data">
            @endcannot
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700">Utilisateur</label>
                <select name="user_id" class="w-full border-gray-300 rounded px-3 py-2" required>
                    <option value="">-- Choisir un utilisateur --</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>




            <div class="mb-4">
                <label class="block text-gray-700">Statut</label>
                <select name="statut" class="w-full border-gray-300 rounded px-3 py-2">
                    <option value="en_attente">En attente</option>
                    <option value="approuvé">Approuvé</option>
                    <option value="rejeté">Rejeté</option>
                </select>
                @error('statut') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium">Upload File (Image or PDF)</label>
                <input type="file" name="file" id="file" class="w-full p-2 border rounded" accept="image/*,application/pdf">
                @error('file')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>
            @can('isAdmin')
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 bg-gray-300 rounded">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enregistrer</button>
            </div>
            @endcan
            @cannot('isAdmin')
            <div class="flex justify-end space-x-3">
                <a href="{{ route('super_admin.invoices.index') }}" class="px-4 py-2 bg-gray-300 rounded">Annuler</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Enregistrer</button>
            </div>
            @endcannot
        </form>
</div>
@endsection