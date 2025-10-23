@extends('user.layout')

@section('title', __('contact.title') . ' | DocuSyns')
@section('page_title', __('contact.page_title'))

@section('content')

<div class="bg-white p-6 mt-5 rounded-lg shadow-sm w-[90%] mx-auto">
    <h1 class="text-2xl font-bold mb-4">📬 {{ __('contact.heading') }}</h1>
    <p class="text-gray-600 mb-6">{{ __('contact.subtitle') }}</p>

    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('user.contact.send') }}" class="space-y-4 ">
        @csrf

        <div>
            <label class="block text-gray-700 mb-1">{{ __('contact.subject') }}</label>
            <input type="text" name="subject" value="{{ old('subject') }}"
                class="w-full bg-gray-300 border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
            @error('subject') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-gray-700 mb-1">{{ __('contact.message') }}</label>
            <textarea name="message" rows="5"
                class="w-full bg-gray-300 border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">{{ old('message') }}</textarea>
            @error('message') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                {{ __('contact.send_button') }}
            </button>
        </div>
    </form>
</div>
@endsection