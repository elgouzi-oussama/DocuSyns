@extends('user.layout')
@section('title', __('messages.welcome_page') )
@section('page_title', __('messages.welcome_page') )

@section('content')

<!-- Hero Section -->
<section class="flex-1 flex flex-col items-center justify-center text-center px-6">
    <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
        {{ __('messages.welcome') }} <span class="text-blue-600">DocuSyns</span>
    </h1>
    <p class="text-gray-600 max-w-lg mb-8">
        {{ __('messages.inside_welcome') }}
    </p>

    <div class="flex space-x-4">
        @can('invoice.create')
        <a href="{{ route('invoice.create') }}" class="bg-blue-600 text-white mx-3 px-6 py-3 rounded-lg hover:bg-blue-700 transition">
            ➕ {{ __('messages.add_invoice') }}
        </a>
        @endcan
        @can('invoice.index')
        <a href="{{ route('invoice.index') }}" class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition">
            📄 {{ __('messages.view_invoices') }}
        </a>
        @endcan
    </div>
</section>
@endsection