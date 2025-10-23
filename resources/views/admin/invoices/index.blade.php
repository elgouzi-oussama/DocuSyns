@extends('admin.layout')

@section('title', __('invoice.index.title'))
@section('page_title', __('invoice.index.page_title'))

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">{{ __('invoice.index.heading') }}</h1>

    {{-- ✅ Alert messages --}}
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

    {{-- ✅ Buttons --}}
    <div class="flex space-x-3 mb-6">
        @can('isAdmin')
        <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-5 py-2 rounded-lg shadow">
            {{ __('invoice.index.back') }}
        </a>
        @can('invoice.create')
        <a href="{{ route('admin.invoices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
            {{ __('invoice.index.add_auto') }}
        </a>
        @endcan
        @endcan

        @cannot('isAdmin')
        <a href="{{ route('super_admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-700 text-white px-5 py-2 rounded-lg shadow mx-2">
            {{ __('invoice.index.back') }}
        </a>
        <a href="{{ route('super_admin.invoices.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
            {{ __('invoice.index.add_auto') }}
        </a>
        @endcannot
    </div>

    {{-- ✅ Table --}}
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('invoice.index.reference') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('invoice.index.supplier') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('invoice.index.date') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('invoice.index.amount') }}</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('invoice.index.status') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">{{ __('invoice.index.actions') }}</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-100">
                @forelse ($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->reference_commande }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->nom_fournisseur }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->date_commande }}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">
                        @if (app()->getLocale() === 'ar')
                        <span dir="ltr">
                            {{ number_format($invoice->montant_ttc, 2, ',', ' ') }}&lrm;</span>
                        <span>{{ __('admin.currency') }}</span>
                        @else
                        <span dir="ltr">
                            {{ number_format($invoice->montant_ttc, 2, ',', ' ') }}</span>
                        <span>{{ __('admin.currency') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">{{ $invoice->statut }}</td>

                    <td class="px-6 py-4 text-sm text-right space-x-3">
                        @can('isAdmin')
                        @can('invoice.show')
                        <a href="{{ route('admin.invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">{{ __('invoice.index.view') }}</a>
                        @endcan
                        @can('invoice.edit')
                        <a href="{{ route('admin.invoices.edit', $invoice->id) }}" class="text-yellow-600 hover:text-yellow-800 font-medium">{{ __('invoice.index.edit') }}</a>
                        @endcan
                        @can('invoice.delete')
                        <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('{{ __('invoice.index.confirm_delete') }}')" class="text-red-600 hover:text-red-800 font-medium">
                                {{ __('invoice.index.delete') }}
                            </button>
                        </form>
                        @endcan
                        @endcan

                        @cannot('isAdmin')
                        <a href="{{ route('super_admin.invoices.show', $invoice->id) }}" class="text-blue-600 hover:text-blue-800 m-3 font-medium">{{ __('invoice.index.view') }}</a>
                        <a href="{{ route('super_admin.invoices.edit', $invoice->id) }}" class="text-yellow-600 hover:text-yellow-800 font-medium ">{{ __('invoice.index.edit') }}</a>
                        <form action="{{ route('super_admin.invoices.destroy', $invoice->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('{{ __('invoice.index.confirm_delete') }}')" class="text-red-600 hover:text-red-800 font-medium">
                                {{ __('invoice.index.delete') }}
                            </button>
                        </form>
                        @endcannot
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">{{ __('invoice.index.empty') }}</td>
                </tr>
                @endforelse

                <tr>
                    <td colspan="3" class="p-3 text-right font-semibold">{{ __('invoice.index.total') }}</td>
                    <td dir="auto" colspan="3" class="p-3 text-right font-semibold">
                        @if (app()->getLocale() === 'ar')
                        <span dir="ltr">{{ number_format($invoices->sum('montant_ttc'), 2, ',', ' ') }}&lrm;</span>
                        <span>{{ __('admin.currency') }}</span>
                        @else
                        <span dir="ltr">{{ number_format($invoices->sum('montant_ttc'), 2, ',', ' ') }}</span>
                        <span>{{ __('admin.currency') }}</span>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection