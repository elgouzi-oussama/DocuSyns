@extends('user.layout')

@section('title', __('messages.modify_purchase_order') . ' | DocuSyns')
@section('page_title', __('messages.modify_purchase_order') . ' | DocuSyns')

@section('content')

<div class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    {{-- ✅ Success Message --}}
    @if (session('success'))
    <div class="absolute top-6 bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-2 shadow">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-4xl 
        {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }}">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            {{ __('messages.modify_purchase_order') }}
        </h1>

        <form action="{{ route('invoice.update', $invoice->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 
                {{ app()->getLocale() === 'ar' ? 'direction-rtl' : '' }}">

                {{-- Référence Commande --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.reference_number') }}
                    </label>
                    <input type="text" name="reference_commande"
                        value="{{ old('reference_commande', $invoice->reference_commande) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Date Commande --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.order_date') }}
                    </label>
                    <input type="date" name="date_commande"
                        value="{{ old('date_commande', \Carbon\Carbon::parse($invoice->date_commande)->format('Y-m-d')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Nom Fournisseur --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.supplier_name') }}
                    </label>
                    <input type="text" name="nom_fournisseur"
                        value="{{ old('nom_fournisseur', $invoice->nom_fournisseur) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Code Fournisseur --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.supplier_code') }}
                    </label>
                    <input type="text" name="code_fournisseur"
                        value="{{ old('code_fournisseur', $invoice->code_fournisseur) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Commandé Par --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.ordered_by') }}
                    </label>
                    <textarea name="commande_par" rows="2"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('commande_par', $invoice->commande_par) }}</textarea>
                </div>

                {{-- Commandé À --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.ordered_to') }}
                    </label>
                    <textarea name="commande_a" rows="2"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('commande_a', $invoice->commande_a) }}</textarea>
                </div>

                {{-- Montant HT --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.amount_ht') }}
                    </label>
                    <input type="text" name="montant_ht"
                        value="{{ old('montant_ht', number_format((float)$invoice->montant_ht, 2, ',', ' ')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Montant TVA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.amount_tva') }}
                    </label>
                    <input type="text" name="montant_tva"
                        value="{{ old('montant_tva', number_format((float)$invoice->montant_tva, 2, ',', ' ')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                {{-- Montant TTC --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ __('messages.amount_ttc') }}
                    </label>
                    <input type="text" name="montant_ttc"
                        value="{{ old('montant_ttc', number_format((float)$invoice->montant_ttc, 2, ',', ' ')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            {{-- Buttons --}}
            <div class="pt-6 flex justify-between {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
                <a href="{{ route('invoice.index') }}"
                    class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                    {{ __('messages.back') }}
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    {{ __('messages.update') }}
                </button>
            </div>
        </form>
    </div>

</div>
@endsection