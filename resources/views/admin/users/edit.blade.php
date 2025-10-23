@extends('admin.layout')

@section('title', __('user.edit.title'))
@section('page_title', __('user.edit.page_title'))

@section('content')
<div class="bg-white p-4 rounded-lg shadow-sm max-w-xl text-lg mx-auto">
    <form method="POST" action="{{ route('super_admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 mb-1">{{ __('user.edit.role') }}</label>
            <select name="role"
                class="w-full border-gray-400 rounded px-3 py-2 bg-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                required>
                <option value="">{{ __('user.edit.select_role') }}</option>
                <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>
                    {{ __('user.edit.role_user') }}
                </option>
                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>
                    {{ __('user.edit.role_admin') }}
                </option>
            </select>
            @error('role') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">{{ __('user.edit.name') }}</label>
            <input type="text" name="name" class="w-full border-gray-400 bg-gray-300 rounded px-3 py-2"
                value="{{ old('name', $user->name) }}" required>
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">{{ __('user.edit.email') }}</label>
            <input type="email" name="email" class="w-full border-gray-400 bg-gray-300 rounded px-3 py-2"
                value="{{ old('email', $user->email) }}" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">{{ __('user.edit.new_password') }}</label>
            <input type="password" name="password" class="w-full border-gray-400 bg-gray-300 rounded px-3 py-2">
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">{{ __('user.edit.confirm_password') }}</label>
            <input type="password" name="password_confirmation" class="w-full bg-gray-300 border-gray-400 rounded px-3 py-2">
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('super_admin.users.index') }}" class="px-4 py-2 bg-gray-300 rounded">
                {{ __('user.edit.cancel') }}
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                {{ __('user.edit.update') }}
            </button>
        </div>
    </form>
</div>
@endsection

@section('content2')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-xl mx-auto">
    <h1 class="text-xl font-bold mb-4">{{ __('user.edit.manage_permissions') }}</h1>

    <form method="POST" action="{{ route('super_admin.users.permissions', $user) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4 p-4 bg-white rounded-lg shadow-sm text-lg">

            <h3 class="col-span-full text-lg font-semibold text-gray-700 mb-2">
                {{ __('user.edit.invoice_permissions') }}
            </h3>

            <label><input type="checkbox" name="permissions[]" value="invoice.index" class="mr-2"
                    {{ $user->hasPermission('invoice.index') ? 'checked' : '' }}>
                {{ __('user.edit.perms.view_invoices') }}
            </label>

            <label><input type="checkbox" name="permissions[]" value="invoice.show" class="mr-2"
                    {{ $user->hasPermission('invoice.show') ? 'checked' : '' }}>
                {{ __('user.edit.perms.show_invoice') }}
            </label>

            <label><input type="checkbox" name="permissions[]" value="invoice.create" class="mr-2"
                    {{ $user->hasPermission('invoice.create') ? 'checked' : '' }}>
                {{ __('user.edit.perms.create_invoice') }}
            </label>

            <label><input type="checkbox" name="permissions[]" value="invoice.edit" class="mr-2"
                    {{ $user->hasPermission('invoice.edit') ? 'checked' : '' }}>
                {{ __('user.edit.perms.edit_invoice') }}
            </label>

            <label><input type="checkbox" name="permissions[]" value="invoice.delete" class="mr-2"
                    {{ $user->hasPermission('invoice.delete') ? 'checked' : '' }}>
                {{ __('user.edit.perms.delete_invoice') }}
            </label>

            <label><input type="checkbox" name="permissions[]" value="invoice.approve" class="mr-2"
                    {{ $user->hasPermission('invoice.approve') ? 'checked' : '' }}>
                {{ __('user.edit.perms.approve_invoice') }}
            </label>

            <label><input type="checkbox" name="permissions[]" value="invoice.reject" class="mr-2"
                    {{ $user->hasPermission('invoice.reject') ? 'checked' : '' }}>
                {{ __('user.edit.perms.reject_invoice') }}
            </label>

            @if ($user->role !== "user")
            <h3 class="col-span-full text-lg font-semibold text-gray-700 mt-4 mb-2">
                {{ __('user.edit.user_permissions') }}
            </h3>

            <label><input type="checkbox" name="permissions[]" value="user.index" class="mr-2"
                    {{ $user->hasPermission('user.index') ? 'checked' : '' }}>
                {{ __('user.edit.perms.view_users') }}
            </label>

            <label><input type="checkbox" name="permissions[]" value="user.show" class="mr-2"
                    {{ $user->hasPermission('user.show') ? 'checked' : '' }}>
                {{ __('user.edit.perms.show_user') }}
            </label>

            <label><input type="checkbox" name="permissions[]" value="user.create" class="mr-2"
                    {{ $user->hasPermission('user.create') ? 'checked' : '' }}>
                {{ __('user.edit.perms.create_user') }}
            </label>

            <label><input type="checkbox" name="permissions[]" value="user.edit" class="mr-2"
                    {{ $user->hasPermission('user.edit') ? 'checked' : '' }}>
                {{ __('user.edit.perms.edit_user') }}
            </label>

            <label><input type="checkbox" name="permissions[]" value="user.delete" class="mr-2"
                    {{ $user->hasPermission('user.delete') ? 'checked' : '' }}>
                {{ __('user.edit.perms.delete_user') }}
            </label>
            @endif
        </div>

        <div class="flex justify-end space-x-3 mt-4">
            <a href="{{ route('super_admin.users.index') }}" class="px-4 py-2 bg-gray-300 mx-3 rounded">
                {{ __('user.edit.cancel') }}
            </a>
            <button type="submit" class="px-4   py-2 bg-blue-600 text-white rounded">
                {{ __('user.edit.update') }}
            </button>
        </div>
    </form>
</div>
@endsection