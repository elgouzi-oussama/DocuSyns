@extends('admin.layout')

@section('title', __('user.create_title'))
@section('page_title', __('user.add_user'))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg mx-auto">
    <form method="POST" action="{{ route('super_admin.users.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700">{{ __('user.name') }}</label>
            <input type="text" name="name" class="w-full bg-gray-200 border-gray-300 rounded px-3 py-2"
                value="{{ old('name') }}" required>
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">{{ __('user.email') }}</label>
            <input type="email" name="email" class="w-full bg-gray-200  border-gray-300 rounded px-3 py-2"
                value="{{ old('email') }}" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">{{ __('user.password') }}</label>
            <input type="password" name="password" class="w-full bg-gray-200  border-gray-300 rounded px-3 py-2" required>
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">{{ __('user.confirm_password') }}</label>
            <input type="password" name="password_confirmation" class="w-full  bg-gray-200 border-gray-300 rounded px-3 py-2" required>
        </div>

        <!-- ✅ Role Selection -->
        <div class="mb-6">
            <label class="block text-gray-700 mb-1">{{ __('user.role') }}</label>
            <select name="role"
                class="w-full border-gray-300 rounded bg-gray-200  px-3 py-2  focus:ring-2 focus:ring-blue-500 focus:outline-none"
                required>
                <option value="">{{ __('user.select_role') }}</option>
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>{{ __('user.role_user') }}</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('user.role_admin') }}</option>
            </select>
            @error('role') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end space-x-3">
            <a href="{{ route('super_admin.users.index') }}" class="px-4 mx-4 py-2 bg-gray-300 rounded">
                {{ __('user.cancel') }}
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600  text-white rounded">
                {{ __('user.save') }}
            </button>
        </div>
    </form>
</div>
@endsection