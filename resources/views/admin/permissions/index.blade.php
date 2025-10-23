@extends('admin.layout')

@section('title', 'Gérer les permissions')
@section('page_title', 'Gérer les permissions')


@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-xl mx-auto">
    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4  bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
    @endif
    <h1 class="text-xl font-bold mb-4">Modifier les permissions</h1>


    @foreach($users as $user)
    <div class="border border-gray-200 rounded-lg shadow-sm overflow-hidden">
        <!-- Header -->
        <button type="button"
            onclick="document.getElementById('user-{{ $user->id }}').classList.toggle('hidden')"
            class="w-full text-left px-4 py-3 bg-gray-100 hover:bg-gray-200 flex justify-between items-center">
            <span class="font-semibold text-gray-800">
                👤 {{ $user->name }} — <span class="text-sm text-gray-600">{{ $user->email }}</span>
            </span>
            <span class="text-sm text-blue-600 uppercase">{{ $user->role }}</span>
        </button>
        <div id="user-{{ $user->id }}" class="hidden">
            @can('isSuperAdmin')
            <form method="POST" action="{{ route('super_admin.users.permissions', $user) }}">
                @endcan
                @cannot('isSuperAdmin')
                <form method="POST" action="{{ route('admin.users.permissions', $user) }}">
                    @endcannot
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 p-4 bg-white rounded-lg shadow-sm text-lg">
                        <!--  Permissions -->
                        @can('isSuperAdmin')
                        @if ($user->role !== "user")
                        <h3 class="col-span-full text-lg font-semibold text-gray-700 mt-4 mb-2">Permissions - edit</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">

                            <label>
                                <input type="checkbox" name="permissions[]" value="permission.show"
                                    class="mr-2" {{ $user->hasPermission('permission.show') ? 'checked' : '' }}>
                                Voir le permission
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="permission.edit"
                                    class="mr-2" {{ $user->hasPermission('permission.edit') ? 'checked' : '' }}>
                                Modifier le permission
                            </label>
                        </div>
                        @endif
                        @endcan
                        <!-- Profile Permissions -->
                        <h3 class="col-span-full text-lg font-semibold text-gray-700 mt-4 mb-2">Permissions - Profil</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">

                            <label>
                                <input type="checkbox" name="permissions[]" value="profile.show"
                                    class="mr-2" {{ $user->hasPermission('profile.show') ? 'checked' : '' }}>
                                Voir le profil
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="profile.edit"
                                    class="mr-2" {{ $user->hasPermission('profile.edit') ? 'checked' : '' }}>
                                Modifier le profil
                            </label>
                        </div>
                        <!-- Invoices Permissions -->
                        <h3 class="col-span-full text-lg font-semibold text-gray-700 mb-2">Permissions - Bons de commande</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">

                            <label>
                                <input type="checkbox" name="permissions[]" value="invoice.index"
                                    class="mr-2" {{ $user->hasPermission('invoice.index') ? 'checked' : '' }}>
                                Voir la liste des bons de commande
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="invoice.show"
                                    class="mr-2" {{ $user->hasPermission('invoice.show') ? 'checked' : '' }}>
                                Afficher un bon de commande
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="invoice.create"
                                    class="mr-2" {{ $user->hasPermission('invoice.create') ? 'checked' : '' }}>
                                Créer un bon de commande
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="invoice.edit"
                                    class="mr-2" {{ $user->hasPermission('invoice.edit') ? 'checked' : '' }}>
                                Modifier un bon de commande
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="invoice.delete"
                                    class="mr-2" {{ $user->hasPermission('invoice.delete') ? 'checked' : '' }}>
                                Supprimer un bon de commande
                            </label>
                            @if ($user->role !== "user")
                            <label>
                                <input type="checkbox" name="permissions[]" value="invoice.approve"
                                    class="mr-2" {{ $user->hasPermission('invoice.approve') ? 'checked' : '' }}>
                                Approuver un bon de commande
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="invoice.reject"
                                    class="mr-2" {{ $user->hasPermission('invoice.reject') ? 'checked' : '' }}>
                                Rejeter un bon de commande
                            </label>
                            @endif
                        </div>
                        @if ($user->role !== "user")


                        <!-- Users Permissions -->
                        <h3 class="col-span-full text-lg font-semibold text-gray-700 mt-4 mb-2">Permissions - Utilisateurs</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">

                            <label>
                                <input type="checkbox" name="permissions[]" value="user.index"
                                    class="mr-2" {{ $user->hasPermission('user.index') ? 'checked' : '' }}>
                                Voir la liste des utilisateurs
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="user.show"
                                    class="mr-2" {{ $user->hasPermission('user.show') ? 'checked' : '' }}>
                                Afficher un utilisateur
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="user.create"
                                    class="mr-2" {{ $user->hasPermission('user.create') ? 'checked' : '' }}>
                                Créer un utilisateur
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="user.edit"
                                    class="mr-2" {{ $user->hasPermission('user.edit') ? 'checked' : '' }}>
                                Modifier un utilisateur
                            </label>

                            <label>
                                <input type="checkbox" name="permissions[]" value="user.delete"
                                    class="mr-2" {{ $user->hasPermission('user.delete') ? 'checked' : '' }}>
                                Supprimer un utilisateur
                            </label>
                        </div>

                    </div>
                    @endif

                    <div class="flex justify-end space-x-3  mb-4 me-2">
                        @can('permission.edit')
                        <button type="submit" class="px-2 py-1 bg-blue-600 text-white rounded">Mettre à jour</button>
                        @endcan
                        @can('isSuperAdmin')
                        <button type="submit" class="px-2 py-1 bg-blue-600 text-white rounded">Mettre à jour</button>
                        @endcan
                    </div>
                </form>
        </div>
        @endforeach
    </div>

    <div class="flex justify-end mt-6 me-2">
        @can('isSuperAdmin')
        <a href="{{ route(name: 'super_admin.users.index') }}" class="px-4 py-2 bg-gray-300 rounded ">Annuler</a>
        @endcan
        @cannot('isSuperAdmin')
        <a href="{{ route(name: 'admin.users.index') }}" class="px-4 py-2 bg-gray-300 rounded ">Annuler</a>
        @endcannot
    </div>
    </form>
</div>
@endsection