@extends('admin.layout')

@section('title', 'Profil Administrateur')
@section('page_title', 'Mon Profil')

@section('content')
@if (session('success'))
<div class="mb-4 bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3">
    {{ session('success') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Informations du compte</h2>
        <p><strong>Nom :</strong> {{ $admin->name }}</p>
        <p><strong>Email :</strong> {{ $admin->email }}</p>
        <p><strong>Rôle :</strong> {{ ucfirst($admin->role) }}</p>
        <p><strong>Date de création :</strong> {{ $admin->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Dernière mise à jour :</strong> {{ $admin->updated_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="flex justify-between">
        <a href="{{ route('super_admin.dashboard') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
            Retour
        </a>
        <a href="{{ route('super_admin.profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Modifier le profil
        </a>
    </div>
</div>
@endsection