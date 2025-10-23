@extends('admin.layout')

@section('title', __('user.list.title'))
@section('page_title', __('user.list.page_title'))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm">

    @if(session('success'))
    <div class="mb-4 {{ session('deleted') ? 'bg-red-100 border border-red-300 text-red-800' : 'bg-green-100 border border-green-300 text-green-800' }} px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    @can('isSuperAdmin')
    <div class="mb-4">
        <a href="{{ route('super_admin.users.permissions.index') }}" class="text-blue-600 hover:underline rounded bg-blue-50 p-2 text-lg">
            {{ __('user.list.manage_permissions') }}
        </a>
    </div>
    @endcan

    @can('permission.show')
    <div class="mb-4">
        <a href="{{ route('admin.users.permissions.index') }}" class="text-blue-600 hover:underline rounded bg-blue-50 p-2 text-lg">
            {{ __('user.list.manage_permissions') }}
        </a>
    </div>
    @endcan

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-700">{{ __('user.list.title_table') }}</h2>

        @can('isSuperAdmin')
        <a href="{{ route('super_admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + {{ __('user.list.add_user') }}
        </a>
        @endcan

        @can('user.create')
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + {{ __('user.list.add_user') }}
        </a>
        @endcan
    </div>

    <table class="w-full text-left border border-gray-200">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border-b">#</th>
                <th class="px-4 py-2 border-b">{{ __('user.list.name') }}</th>
                <th class="px-4 py-2 border-b">{{ __('user.list.email') }}</th>
                <th class="px-4 py-2 border-b">{{ __('user.list.role') }}</th>
                <th class="px-4 py-2 border-b text-center">{{ __('user.list.actions') }}</th>
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
                    <a href="{{ route('super_admin.users.edit', $user) }}" class="text-yellow-600 mx-2 hover:underline">{{ __('user.list.edit') }}</a>

                    <form action="{{ route('super_admin.users.destroy', $user) }}" method="POST" class=" inline"
                        onsubmit="return confirm('{{ __('user.list.delete_confirm') }}');">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">{{ __('user.list.delete') }}</button>
                    </form>

                    <a href="{{ route('super_admin.users.show', $user) }}" class="text-blue-600 hover:underline">{{ __('user.list.show') }}</a>
                    @endcan

                    @can('user.edit')
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:underline">{{ __('user.list.edit') }}</a>
                    @endcan

                    @can('user.delete')
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                        onsubmit="return confirm('{{ __('user.list.delete_confirm') }}');">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">{{ __('user.list.delete') }}</button>
                    </form>
                    @endcan

                    @can('user.show' , $user)
                    <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:underline">{{ __('user.list.show') }}</a>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-3 text-center text-gray-500">{{ __('user.list.no_users') }}</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection