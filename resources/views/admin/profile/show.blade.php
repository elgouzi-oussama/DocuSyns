@extends('admin.layout')

@section('title', __('admin.profile.title'))
@section('page_title', __('admin.profile.page_title'))

@section('content')
@if (session('success'))
<div class="mb-4 bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-3">
    {{ session('success') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg mx-auto">
    <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">{{ __('admin.profile.account_info') }}</h2>
        <p><strong>{{ __('admin.profile.name') }} :</strong> {{ $admin->name }}</p>
        <p><strong>{{ __('admin.profile.email') }} :</strong> {{ $admin->email }}</p>
        <p><strong>{{ __('admin.profile.role') }} :</strong> {{ ucfirst($admin->role) }}</p>
        <p><strong>{{ __('admin.profile.created_at') }} :</strong> {{ $admin->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>{{ __('admin.profile.updated_at') }} :</strong> {{ $admin->updated_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="flex justify-between">
        <a href="{{ route('super_admin.dashboard') }}" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
            {{ __('admin.profile.back') }}
        </a>

        @can('isSuperAdmin')
        <a href="{{ route('super_admin.profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            {{ __('admin.profile.edit') }}
        </a>
        @endcan

        @can('profile.edit')
        <a href="{{ route('super_admin.profile.edit') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            {{ __('admin.profile.edit') }}
        </a>
        @endcan
    </div>
</div>
@endsection