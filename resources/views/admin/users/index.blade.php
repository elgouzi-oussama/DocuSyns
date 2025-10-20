@extends('admin.layout')

@section('title', 'Liste des utilisateurs')
@section('page_title', 'Gestion des utilisateurs')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm">

    @if(session('success'))
    <div class="mb-4 {{ session('deleted') ? 'bg-red-100 border border-red-300 text-red-800' : 'bg-green-100 border border-green-300 text-green-800' }} px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-700">Liste des utilisateurs</h2>
        @can('isSuperAdmin')
        <a href="{{ route('super_admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Ajouter un utilisateur
        </a>
        @endcan
    </div>

    <table class="w-full text-left border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border-b">#</th>
                <th class="px-4 py-2 border-b">Nom</th>
                <th class="px-4 py-2 border-b">Email</th>
                <th class="px-4 py-2 border-b">Rôle</th>
                <th class="px-4 py-2 border-b text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-2 border-b">{{ $user->id }}</td>
                <td class="px-4 py-2 border-b">{{ $user->name }}</td>
                <td class="px-4 py-2 border-b">{{ $user->email }}</td>
                <td class="px-4 py-2 border-b">{{ ucfirst($user->role ?? 'user') }}</td>
                <td class="px-4 py-2 border-b text-center space-x-2">
                    @can('isSuperAdmin')
                    <a href="{{ route('super_admin.users.edit', $user) }}" class="text-yellow-600 hover:underline">Modifier</a>
                    <form action="{{ route('super_admin.users.destroy', $user) }}" method="POST" class="inline"
                        onsubmit="if(confirm('Supprimer cet utilisateur ?')){return confirm('Si cet utilisateur a créé des factures, elles seront aussi supprimées.\nÊtes-vous sûr de vouloir continuer ?');}else{return false;}">

                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Supprimer</button>
                    </form>
                    <a href="{{ route('super_admin.users.show', $user) }}" class="text-blue-600 hover:underline">Voir</a>
                    @endcan
                    @cannot('isSuperAdmin')
                    <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:underline">Voir</a>
                    @endcannot
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-3 text-center text-gray-500">Aucun utilisateur trouvé.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection