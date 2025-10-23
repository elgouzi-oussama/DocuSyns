@extends('admin.layout')

@section('title', __('admin.profile_edit.title'))
@section('page_title', __('admin.profile_edit.page_title'))

@section('content')
@if (session('success'))
<div class="mb-4 bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3">
    {{ session('success') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg mx-auto">
    <h3 class="text-lg font-semibold mb-4">{{ __('admin.profile_edit.subtitle') }}</h3>

    <form action="{{ route('super_admin.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 text-sm mb-1">{{ __('admin.profile_edit.name') }}</label>
            <input type="text" name="name" value="{{ old('name', $admin->name) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm mb-1">{{ __('admin.profile_edit.email') }}</label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm mb-1">{{ __('admin.profile_edit.new_password') }}</label>
            <input type="password" name="password"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm mb-1">{{ __('admin.profile_edit.confirm_password') }}</label>
            <input type="password" name="password_confirmation"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-between">
            <a href="{{ route('super_admin.dashboard') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                {{ __('admin.profile_edit.back') }}
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ __('admin.profile_edit.save') }}
            </button>
        </div>
    </form>
</div>
@endsection