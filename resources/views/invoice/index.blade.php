<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">📄 Invoices</h1>

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
            <a href="{{ route('index') }}"
                class="bg-gray-500 hover:bg-gray-700 text-white px-5 py-2 rounded-lg shadow">
                back
            </a>
            <a href="{{ route('invoice.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow">
                Ajouter automatiquement (par image ou fichier)
            </a>

        </div>

        {{-- ✅ Table --}}
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Référence
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Fournisseur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Montant TTC
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse ($invoices as $invoice)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $invoice->reference_commande }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $invoice->nom_fournisseur }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $invoice->date_commande }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ number_format($invoice->montant_ttc, 2, ',', ' ')  }}
                        </td>
                        <td class="px-6 py-4 text-sm text-right space-x-3">
                            <a href="{{ route('invoice.show', $invoice->id) }}"
                                class="text-blue-600 hover:text-blue-800 font-medium">Voir</a>

                            <a href="{{ route('invoice.edit', $invoice->id) }}"
                                class="text-yellow-600 hover:text-yellow-800 font-medium">Modifier</a>

                            <form action="{{ route('invoice.destroy', $invoice->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Supprimer cette commande ?')"
                                    class="text-red-600 hover:text-red-800 font-medium">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Aucune facture trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>