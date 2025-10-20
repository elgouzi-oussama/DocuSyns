@extends('admin.layout')

@section('title', 'Détails utilisateur')
@section('page_title', 'Détails de l’utilisateur')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg mx-auto">
    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif
    <h2 class="text-lg font-semibold text-gray-700 mb-4">{{ $user->name }}</h2>

    <p><strong>Email :</strong> {{ $user->email }}</p>
    <p><strong>Rôle :</strong> {{ ucfirst($user->role ?? 'user') }}</p>
    <p><strong>Créé le :</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
    <p><strong>Dernière mise à jour :</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>

    <div class="mt-4 flex justify-end space-x-3">
        @can('isSuperAdmin')
        <a href="{{ route('super_admin.users.edit', $user) }}" class="bg-yellow-500 text-white px-3 py-2 rounded">Modifier</a>
        <a href="{{ route('super_admin.users.index') }}" class="bg-gray-300 px-3 py-2 rounded">Retour</a>
        @endcan
        @cannot('isSuperAdmin')

        <a href="{{ route('admin.users.index') }}" class="bg-gray-300 px-3 py-2 rounded">Retour</a>
        @endcannot
    </div>
</div>
@endsection