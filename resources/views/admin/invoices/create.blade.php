@extends('admin.layout')

@section('title', __('invoice.invoice_create.title'))
@section('page_title', __('invoice.invoice_create.page_title'))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg mx-auto">

    @if (session('error'))
    <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3">
        {{ session('error') }}
    </div>
    @endif

    @can('isAdmin')
    <form method="POST" action="{{ route('admin.invoices.store') }}" enctype="multipart/form-data">
        @endcan

        @cannot('isAdmin')
        <form method="POST" action="{{ route('super_admin.invoices.store') }}" enctype="multipart/form-data">
            @endcannot

            @csrf

            {{-- User field --}}
            <div class="mb-4">
                <label class="block text-gray-700">{{ __('invoice.invoice_create.user_label') }}</label>
                <select name="user_id" class="w-full border-gray-300 rounded px-3 py-2" required>
                    <option value="">{{ __('invoice.invoice_create.choose_user') }}</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- Status field --}}
            <div class="mb-4">
                <label class="block text-gray-700">{{ __('invoice.invoice_create.status_label') }}</label>
                <select name="statut" class="w-full border-gray-300 rounded px-3 py-2">
                    <option value="en_attente">{{ __('invoice.invoice_create.status.pending') }}</option>
                    <option value="approuvé">{{ __('invoice.invoice_create.status.approved') }}</option>
                    <option value="rejeté">{{ __('invoice.invoice_create.status.rejected') }}</option>
                </select>
                @error('statut') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- File upload --}}
            <div class="mb-4">
                <label for="file" class="block text-sm font-medium">{{ __('invoice.invoice_create.upload_label') }}</label>
                <input type="file" name="file" id="file" class="w-full p-2 border rounded" accept="image/*,application/pdf">
                @error('file')
                <div class="text-red-500 text-sm">{{ $message }}</div>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end space-x-3">
                @can('isAdmin')
                <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 bg-gray-300 rounded mx-2">
                    {{ __('invoice.invoice_create.cancel') }}
                </a>
                @endcan
                @cannot('isAdmin')
                <a href="{{ route('super_admin.invoices.index') }}" class="px-4 mx-2 py-2 bg-gray-300 rounded">
                    {{ __('invoice.invoice_create.cancel') }}
                </a>
                @endcannot

                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                    {{ __('invoice.invoice_create.save') }}
                </button>
            </div>
        </form>
</div>
@endsection