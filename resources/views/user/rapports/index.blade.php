@extends('user.layout')

@section('title', __('reports.title') . ' | DocuSyns')
@section('page_title', __('reports.page_title'))

@section('content')
<div class="flex-1 flex flex-col items-center justify-center text-center w-full">

    <div class="bg-white p-6 rounded-lg shadow-sm max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">📊 {{ __('reports.heading') }}</h1>
        <p class="text-gray-600 mb-6">{{ __('reports.subtitle') }}</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="p-4 border rounded-lg shadow-sm hover:shadow-md transition">
                <h2 class="text-lg font-semibold mb-2">{{ __('reports.orders.title') }}</h2>
                <p class="text-gray-600 mb-3">{{ __('reports.orders.description') }}</p>
                <a href="#" class="text-blue-600 hover:underline">{{ __('reports.view_report') }} →</a>
            </div>

            <div class="p-4 border rounded-lg shadow-sm hover:shadow-md transition">
                <h2 class="text-lg font-semibold mb-2">{{ __('reports.validations.title') }}</h2>
                <p class="text-gray-600 mb-3">{{ __('reports.validations.description') }}</p>
                <a href="#" class="text-blue-600 hover:underline">{{ __('reports.view_report') }} →</a>
            </div>

        </div>
    </div>
</div>
@endsection