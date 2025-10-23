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
    class="m-4 rounded-lg bg-red-100 border border-red-300 text-green-800 px-4 py-3 flex items-center justify-between"
    role="alert">
    <span class="font-medium">{{ session('error') }}</span>
    <button
        type="button"
        class="text-green-600 hover:text-green-800 font-bold text-xl leading-none ml-4"
        @click="show = false">
        ×
    </button>
</div>
@endif
<div class="bg-gray-100 flex-1  flex items-center justify-center p-6"></div>
<div class=" mx-auto p-8 ">
    <h1 class="text-2xl font-bold mb-4">{{ __('messages.create_order') }}</h1>
    <a href="{{ route('invoice.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded mb-4 inline-block">{{ __('messages.back_to_invoices') }}</a>

    <form action="{{ route('invoice.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
        @csrf


        <div class="mb-4">
            <label for="file" class="block text-sm font-medium">{{ __('messages.upload_file') }}</label>
            <input type="file" name="file" id="file" class="w-full p-2 border rounded" accept="image/*,application/pdf">
            @error('file')
            <div class="text-red-500 text-sm">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">{{ __('messages.submit') }}</button>
    </form>
</div>
</div>
@endsection