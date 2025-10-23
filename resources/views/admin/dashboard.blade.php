@extends('admin.layout')

@section('title', __('admin.dashboard.page_title'))
@section('page_title', __('admin.dashboard.page_title'))

@section('content')
@if (session('success'))
<div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3">
    {{ session('error') }}
</div>
@endif

<div class="bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-xl font-medium text-gray-700 mb-4">{{ __('admin.dashboard.welcome_title') }}</h3>
    <p class="text-gray-600">{{ __('admin.dashboard.welcome_text') }}</p>

    <div class="grid grid-cols-3 gap-4 mt-6">
        @can('isAdmin')
        @can('invoice.index')
        <a href="{{ route('admin.invoices.index') }}" class="bg-blue-50 p-4 rounded-lg text-center">
            <h4 class="text-blue-700 text-lg font-semibold">{{ $countinvoice }}</h4>
            <p class="text-gray-600 text-sm">{{ __('admin.dashboard.purchase_orders') }}</p>
        </a>
        @endcan
        @cannot('invoice.index')
        <div class="bg-blue-50 p-4 rounded-lg text-center">
            <h4 class="text-blue-700 text-lg font-semibold">{{ $countinvoice }}</h4>
            <p class="text-gray-600 text-sm">{{ __('admin.dashboard.purchase_orders') }}</p>
        </div>
        @endcannot

        @can('user.index')
        <a href="{{ route('admin.users.index') }}" class="bg-green-50 p-4 rounded-lg text-center">
            <h4 class="text-green-700 text-lg font-semibold">{{ $countuser }}</h4>
            <p class="text-gray-600 text-sm">{{ __('admin.dashboard.users') }}</p>
        </a>
        @endcan
        @cannot('user.index')
        <div class="bg-green-50 p-4 rounded-lg text-center">
            <h4 class="text-green-700 text-lg font-semibold">{{ $countuser }}</h4>
            <p class="text-gray-600 text-sm">{{ __('admin.dashboard.users') }}</p>
        </div>
        @endcannot
        @endcan

        @cannot('isAdmin')
        <a href="{{ route('super_admin.invoices.index') }}" class="bg-blue-50 p-4 rounded-lg text-center">
            <h4 class="text-blue-700 text-lg font-semibold">{{ $countinvoice }}</h4>
            <p class="text-gray-600 text-sm">{{ __('admin.dashboard.purchase_orders') }}</p>
        </a>
        <a href="{{ route('super_admin.invoices.index') }}" class="bg-green-50 p-4 rounded-lg text-center">
            <h4 class="text-green-700 text-lg font-semibold">{{ $countuser }}</h4>
            <p class="text-gray-600 text-sm">{{ __('admin.dashboard.users') }}</p>
        </a>
        @endcannot
    </div>
</div>
@endsection