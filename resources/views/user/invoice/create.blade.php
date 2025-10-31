@extends('user.layout')

@section('title', 'Créer Bon de commande | DocuSyns')
@section('page_title', 'Créer Bon de commande | DocuSyns')

@section('content')
@if (session('error'))
<div
    x-data="{ show: true }"
    x-show="show"
    x-transition
    x-init="setTimeout(() => show = false, 4000)"
    class="m-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3 flex items-center justify-between"
    role="alert">
    <span class="font-medium">{{ session('error') }}</span>
    <button
        type="button"
        class="text-red-600 hover:text-red-800 font-bold text-xl leading-none ml-4"
        @click="show = false">
        ×
    </button>
</div>
@endif

@if (session('success'))
<div
    x-data="{ show: true }"
    x-show="show"
    x-transition
    x-init="setTimeout(() => show = false, 4000)"
    class="m-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 flex items-center justify-between"
    role="alert">
    <span class="font-medium">{{ session('success') }}</span>
    <button
        type="button"
        class="text-green-600 hover:text-green-800 font-bold text-xl leading-none ml-4"
        @click="show = false">
        ×
    </button>
</div>
@endif

<div class="bg-gray-100 flex-1 flex items-center justify-center p-6">
    <div class="w-full max-w-4xl mx-auto p-8">
        <h1 class="text-2xl font-bold mb-4">{{ __('messages.create_order') }}</h1>

        <div class="flex gap-2 mb-4">
            <a href="{{ route('invoice.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded inline-block transition">
                {{ __('messages.back_to_invoices') }}
            </a>
            <button
                type="button"
                onclick="toggleTemplateForm()"
                class=" hover:bg-blue-400 px-4 py-2 rounded border transition bg-blue-600 text-white">
                <span id="toggle-text">{{ __('messages.new_template') }}</span>
            </button>
        </div>

        {{-- Main Upload Form --}}
        <form action="{{ route('invoice.store') }}"
            id="create-form"
            method="POST"
            enctype="multipart/form-data"
            class="bg-white p-6 rounded shadow">
            @csrf

            <div class="mb-4">
                <label for="file" class="block text-sm font-medium mb-2">
                    {{ __('messages.upload_file') }}
                </label>
                <input type="file"
                    name="file"
                    id="file"
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    accept="image/*,application/pdf">
                @error('file')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded transition">
                {{ __('messages.submit') }}
            </button>
        </form>

        {{-- New Template Form --}}
        <form action="{{ route('ai-invoice.store') }}"
            id="new-template" class="hidden bg-white p-6 rounded shadow mt-6"
            method="POST"
            enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="file" class="block text-sm font-medium mb-2">
                    {{ __('messages.upload_file') }}
                </label>
                <input type="file"
                    name="file"
                    id="file"
                    class="w-full p-2 border rounded focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    accept="image/*,application/pdf">
                @error('file')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit"
                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded transition">
                {{ __('messages.submit') }}
            </button>
        </form>


    </div>
</div>

<script>
    function toggleTemplateForm() {
        const newTemplate = document.getElementById("new-template");
        const createForm = document.getElementById("create-form");
        const toggleText = document.getElementById("toggle-text");

        if (newTemplate && createForm) {
            const isHidden = newTemplate.classList.contains("hidden");

            newTemplate.classList.toggle("hidden");
            createForm.classList.toggle("hidden");

            // Update button text
            if (toggleText) {
                toggleText.textContent = isHidden ?
                    "{{ __('messages.back_to_upload') }}" :
                    "{{ __('messages.new_template') }}";
            }
        }
    }
</script>
@endsection