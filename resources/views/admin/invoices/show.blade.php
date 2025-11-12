@extends('admin.layout')

@section('title', __('invoice.show.title', ['id' => $invoice->id]))
@section('page_title', __('invoice.show.page_title', ['id' => $invoice->id]))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-5xl mx-auto {{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <h2 class="text-xl font-semibold mb-4 text-gray-700">
        {{ __('invoice.show.heading', ['id' => $invoice->id]) }}
    </h2>

    <div class="space-y-3">
        <div class="p-6 bg-white rounded-lg shadow-md space-y-2 text-gray-800">
            <h2 class="text-xl font-semibold mb-4">{{ __('invoice.show.details_title') }}</h2>

            <p><strong>{{ __('invoice.show.fields.user') }}:</strong> {{ $invoice->user->name ?? '—' }}</p>
            <p><strong>{{ __('invoice.show.fields.email') }}:</strong> {{ $invoice->user->email ?? '—' }}</p>
            <p><strong>{{ __('invoice.show.fields.reference') }}:</strong> {{ $invoice->reference_commande ?? '—' }}</p>
            <p><strong>{{ __('invoice.show.fields.date') }}:</strong>
                {{ $invoice->date_commande ? \Carbon\Carbon::parse($invoice->date_commande)->format('d/m/Y') : '—' }}
            </p>
            <p><strong>{{ __('invoice.show.fields.supplier_name') }}:</strong> {{ $invoice->nom_fournisseur ?? '—' }}</p>
            <p><strong>{{ __('invoice.show.fields.supplier_code') }}:</strong> {{ $invoice->code_fournisseur ?? '—' }}</p>
            <p><strong>{{ __('invoice.show.fields.ordered_by') }}:</strong> {{ $invoice->commande_par ?? '—' }}</p>
            <p><strong>{{ __('invoice.show.fields.ordered_to') }}:</strong> {{ $invoice->commande_a ?? '—' }}</p>

            <p><strong>{{ __('invoice.show.fields.amount_ht') }}:</strong>
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
            <p><strong>{{ __('invoice.show.fields.amount_tva') }}:</strong>
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
            <p><strong>{{ __('invoice.show.fields.amount_ttc') }}:</strong>
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

            <p><strong>{{ __('invoice.show.fields.status') }}:</strong>
                <span class="px-2 py-1 rounded text-sm
                    @if($invoice->statut === 'approuvé') bg-green-100 text-green-700
                    @elseif($invoice->statut === 'rejeté') bg-red-100 text-red-700
                    @else bg-yellow-100 text-yellow-700 
                    @endif
                    ">
                    {{ __('invoice.show.status.' . $invoice->statut) }}
                </span>
            </p>

            <p><strong>{{ __('invoice.show.fields.created_at') }}:</strong> {{ $invoice->created_at->format('d/m/Y H:i') }}</p>

            @if ($invoice->file)
            <p><strong>{{ __('invoice.show.fields.file') }}:</strong>
                <a href="{{ asset('storage/' . $invoice->file) }}" target="_blank" class="text-blue-600 hover:underline">
                    📎 {{ __('invoice.show.view_file') }}
                </a>
            </p>
            @endif
            {{-- 🔹 Button to show Articles --}}




        </div>


        @if($invoice->articles)
        <div class="mt-6 text-start  ">
            <!-- <button id="show-articles"
                    class="bg-gray-300 text-black px-3 p-2 hover:bg-gray-700 hover:text-white  rounded-lg">
                    📄 Voir les articles
                </button> -->
        </div>


        <div>
            <h2 class="text-xl font-bold mb-4">Articles</h2>

            <table class="min-w-full border border-gray-300 text-sm">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        @foreach(array_keys($invoice->articles[0] ?? []) as $key)
                        <th class="px-3 py-2 border-b">{{ $key }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->articles as $item)
                    <tr class="hover:bg-gray-50">
                        @foreach($item as $value)
                        <td class="px-3 py-2 border-b">{{ $value }}</td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- <div class="text-right mt-4">
                    <button id="close-modal" class="bg-gray-600 text-white px-4 py-2 rounded-lg">Fermer</button>
                </div> -->
        </div>
        @endif


        {{-- Admin Buttons --}}


        @can('isAdmin')
        <div class="flex justify-between space-x-3 mt-6">
            <div class="flex justify-start">
                @if($invoice->statut !== 'approuvé')
                <form action="{{ route('admin.invoices.approve', $invoice->id) }}" method="POST">
                    @csrf
                    <button class="bg-green-600 text-white px-4 py-2 rounded"> {{ __('invoice.show.actions.approve') }}</button>
                </form>
                @elseif($invoice->statut !== 'rejeté')
                <form action="{{ route('admin.invoices.reject', $invoice->id) }}" method="POST">
                    @csrf
                    <button class="bg-red-600 text-white px-4 py-2 rounded"> {{ __('invoice.show.actions.reject') }}</button>
                </form>
                @endif
            </div>
            <div class="flex justify-end">
                <a href="{{ route('admin.invoices.edit', $invoice->id) }}"
                    class="bg-green-700 text-white px-4 py-2 rounded me-2"> {{ __('invoice.show.actions.edit') }}</a>
                <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('{{ __('invoice.show.actions.confirm_delete') }}')"
                        class="bg-red-700 text-white px-4 py-2 rounded me-2">
                        {{ __('invoice.show.actions.delete') }}
                    </button>
                </form>
                <a href="{{ route('admin.invoices.index') }}" class="bg-gray-300 px-4 py-2 rounded">{{ __('invoice.show.actions.back') }}</a>
            </div>
        </div>
        @endcan
        @cannot('isAdmin')
        <div class="flex justify-between space-x-3 mt-6">
            <div class="flex justify-start">
                @if($invoice->statut !== 'approuvé')
                <form action="{{ route('super_admin.invoices.approve', $invoice->id) }}" method="POST">
                    @csrf
                    <button class="bg-green-600 text-white px-4 py-2 rounded me-2"> {{ __('invoice.show.actions.approve') }}</button>
                </form>
                @endif
                @if($invoice->statut !== 'rejeté')
                <form action="{{ route('super_admin.invoices.reject', $invoice->id) }}" method="POST">
                    @csrf
                    <button class="bg-red-600 text-white px-4 py-2 rounded"> {{ __('invoice.show.actions.reject') }}</button>
                </form>
                @endif
            </div>
            <div class="flex justify-end">
                <a href="{{ route('super_admin.invoices.edit', $invoice->id) }}"
                    class="bg-green-700 text-white px-4 py-2 rounded me-2"> {{ __('invoice.show.actions.edit') }}</a>
                <form action="{{ route('super_admin.invoices.destroy', $invoice->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('{{ __('invoice.show.actions.confirm_delete') }}')"
                        class="bg-red-700 text-white px-4 py-2 rounded me-2">
                        {{ __('invoice.show.actions.delete') }}
                    </button>
                </form>
                <a href="{{ route('super_admin.invoices.index') }}" class="bg-gray-300 px-4 py-2 rounded">{{ __('invoice.show.actions.back') }}</a>
            </div>
        </div>
        @endcannot
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        const showButton = document.getElementById("show-articles");
        const modal = document.getElementById("articles-modal");
        const closeBtn = document.getElementById("close-modal");

        if (showButton && modal && closeBtn) {
            showButton.addEventListener("click", () => {
                modal.classList.remove("hidden");
            });

            closeBtn.addEventListener("click", () => {
                modal.classList.add("hidden");
            });
        }
    });
</script>
@endsection