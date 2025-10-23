@extends('admin.layout')

@section('title', __('admin.dashboard_title'))
@section('page_title', __('admin.dashboard_title'))

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4 text-center">{{ __('admin.change_password_title') }}</h2>

    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">{{ __('admin.new_password') }}</label>
            <input type="password" name="password" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">{{ __('admin.confirm_password') }}</label>
            <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
        </div>
        <button class="w-full bg-blue-600 text-white py-2 rounded">
            {{ __('admin.change_button') }}
        </button>
    </form>
</div>
@endsection