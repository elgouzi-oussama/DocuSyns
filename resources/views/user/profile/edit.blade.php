@extends('user.layout')

@section('title', __('messages.profile_edit_title'))
@section('page_title', __('messages.profile_edit_page'))

@section('content')
@if (session('success'))
<div class="mb-4 bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3">
    {{ session('success') }}
</div>
@endif

<div class="flex-1 flex flex-col items-center justify-center px-6 py-6"></div>
<div class="bg-white text-lg p-6 px-9 rounded-lg shadow-sm max-w-xl w-full m-auto">
    <h3 class="text-xl font-semibold mb-4 text-center">{{ __('messages.edit_info') }}</h3>

    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 mb-1">{{ __('messages.name') }}</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-1">{{ __('messages.email') }}</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200" required>
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-1">{{ __('messages.new_password_optional') }}</label>
            <input type="password" name="password"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 mb-1">{{ __('messages.confirm_password') }}</label>
            <input type="password" name="password_confirmation"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
        </div>

        <div class="flex justify-between">
            <a href="{{ route('profile.show') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                {{ __('messages.back') }}
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ __('messages.save') }}
            </button>
        </div>
    </form>
</div>
</div>
@endsection