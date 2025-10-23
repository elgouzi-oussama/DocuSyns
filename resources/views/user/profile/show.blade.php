@extends('user.layout')

@section('title', __('messages.account_info'))
@section('page_title', __('messages.profile_page_title'))

@section('content')
@if (session('success'))
<div class="m-4 bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3 text-lg">
    {{ session('success') }}
</div>
@endif

<div class="flex-1 flex flex-col items-center text-lg justify-center px-6 py-6">
    <h2 class="text-xl font-semibold mb-2">{{ __('messages.account_info') }}</h2>

    <div class="d-block bg-white max-w-xl p-8 rounded-lg shadow-lg mx-auto">
        <div class="mb-6">
            <p><strong>{{ __('messages.name') }} :</strong> {{ $user->name }}</p>
            <p><strong>{{ __('messages.email') }} :</strong> {{ $user->email }}</p>
            <p><strong>{{ __('messages.role') }} :</strong> {{ ucfirst($user->role) }}</p>
            <p><strong>{{ __('messages.created_at') }} :</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>{{ __('messages.updated_at') }} :</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('index') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                {{ __('messages.back') }}
            </a>

            @can('profile.edit')
            <a href="{{ route('profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ __('messages.edit_profile') }}
            </a>
            @endcan
        </div>
    </div>
</div>
@endsection