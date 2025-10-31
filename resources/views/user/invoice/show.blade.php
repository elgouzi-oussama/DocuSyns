@extends('user.layout')

@section('title', __('messages.invoice_details_title'))
@section('page_title', __('messages.invoice_details_title'))

@section('content')

<div>
    @if (session('success'))
    <div
        x-data="{ show: true }"
        x-show="show"
        x-transition
        x-init="setTimeout(() => show = false, 4000)"
        class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3 flex items-center justify-between"
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

    <div class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
        <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-4xl">
            <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
                📄 {{ __('messages.invoice_details') }}
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-gray-700">

                <div>
                    <p class="text-sm text-gray-500">{{ __('messages.reference_commande') }}</p>
                    <p class="font-medium">{{ $invoice->reference_commande }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">{{ __('messages.date_commande') }}</p>
                    <p class="font-medium">{{ \Carbon\Carbon::parse($invoice->date_commande)->format('d/m/Y') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">{{ __('messages.nom_fournisseur') }}</p>
                    <p class="font-medium">{{ $invoice->nom_fournisseur }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">{{ __('messages.code_fournisseur') }}</p>
                    <p class="font-medium">{{ $invoice->code_fournisseur ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">{{ __('messages.commande_par') }}</p>
                    <p class="font-medium whitespace-pre-line">{{ $invoice->commande_par }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">{{ __('messages.commande_a') }}</p>
                    <p class="font-medium whitespace-pre-line">{{ $invoice->commande_a }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">{{ __('messages.montant_ht') }}</p>
                    <p>
                        @if (app()->getLocale() === 'ar')
                        <span dir="ltr">
                            {{ number_format($invoice->montant_ht, 2, ',', ' ') }}&lrm;</span>
                        <span>{{ __('admin.currency') }}</span>
                        @else
                        <span dir="ltr">
                            {{ number_format($invoice->montant_ht, 2, ',', ' ') }}</span>
                        <span>{{ __('admin.currency') }}</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">{{ __('messages.montant_tva') }}</p>
                    <p>
                        @if (app()->getLocale() === 'ar')
                        <span dir="ltr">
                            {{ number_format($invoice->montant_tva, 2, ',', ' ') }}&lrm;</span>
                        <span>{{ __('admin.currency') }}</span>
                        @else
                        <span dir="ltr">
                            {{ number_format($invoice->montant_tva, 2, ',', ' ') }}</span>
                        <span>{{ __('admin.currency') }}</span>
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">{{ __('messages.montant_ttc') }}</p>
                    <p>
                        @if (app()->getLocale() === 'ar')
                        <span dir="ltr">
                            {{ number_format($invoice->montant_ttc, 2, ',', ' ') }}&lrm;</span>
                        <span>{{ __('admin.currency') }}</span>
                        @else
                        <span dir="ltr">
                            {{ number_format($invoice->montant_ttc, 2, ',', ' ') }}</span>
                        <span>{{ __('admin.currency') }}</span>
                        @endif
                    </p>
                </div>

                @if ($invoice->file)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">{{ __('messages.fichier_associe') }}</p>
                    <a href="{{ asset('storage/' . $invoice->file) }}" target="_blank"
                        class="text-blue-600 hover:underline">
                        📎 {{ __('messages.view_file') }}
                    </a>
                </div>
                @endif

            </div>

            <div class="pt-6 flex justify-between">
                <a href="{{ route('invoice.index') }}"
                    class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                    {{ __('messages.back') }}
                </a>
                @can('invoice.edit')
                <a href="{{ route('invoice.edit', $invoice->id) }}"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    {{ __('messages.edit') }}
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>

@endsection