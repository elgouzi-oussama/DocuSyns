    @extends('user.layout')

    @section('title', __('invoice.page_title') . ' | DocuSyns')
    @section('page_title', __('invoice.page_title') . ' | DocuSyns')

    @section('content')
    <div class="p-6 flex justify-content-between align-items-center">
        <h1 class="text-3xl m-4 font-bold mb-6 text-gray-800">📄 {{ __('invoice.heading') }}</h1>

        {{-- ✅ Alert messages --}}
        @if (session('success'))
        <div class="w-50 h-12 m-4 rounded-lg bg-green-100 border border-green-300 text-green-800 px-4 py-3">
            {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-800 px-4 py-3">
            {{ session('error') }}
        </div>
        @endif
    </div>

    <div class="flex-1 flex flex-col items-center justify-center text-center w-full">

        {{-- ✅ Buttons --}}
        <div class="flex space-x-3 mb-6">
            <a href="{{ route('index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white px-5 py-2 rounded-lg shadow">
                {{ __('invoice.back') }}
            </a>

            @can('invoice.create')
            <a href="{{ route('invoice.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
                {{ __('invoice.add_auto') }}
            </a>
            @endcan
        </div>

        {{-- ✅ Table --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden w-full max-w-5xl">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            {{ __('invoice.reference') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            {{ __('invoice.supplier') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            {{ __('invoice.date') }}
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            {{ __('invoice.amount_ttc') }}
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            {{ __('invoice.actions') }}
                        </th>
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
                        <td class="px-6 py-4 text-sm text-right space-x-3 ">
                            @can('invoice.show')
                            <a href="{{ route('invoice.show', $invoice->id) }}"
                                class="text-blue-600 hover:text-blue-800  font-semibold">{{ __('invoice.view') }}</a>
                            @endcan

                            @can('invoice.edit')
                            <a href="{{ route('invoice.edit', $invoice->id) }}"
                                class="text-yellow-600 hover:text-yellow-800  font-semibold">{{ __('invoice.edit') }}</a>
                            @endcan

                            @can('invoice.delete')
                            <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('{{ __('invoice.delete_confirm') }}')"
                                    class="text-red-600 hover:text-red-800 font-semibold">
                                    {{ __('invoice.delete') }}
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            {{ __('invoice.no_invoice') }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endsection