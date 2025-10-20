<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Facture</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">

    {{-- Success message --}}
    @if (session('success'))
    <div class="absolute top-6 bg-green-100 text-green-800 border border-green-300 rounded-lg px-4 py-2 shadow">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-4xl">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center">
            Modifier les données de la facture
        </h1>

        <form action="{{ route('invoice.update', $invoice->id) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Référence Commande</label>
                    <input type="text" name="reference_commande"
                        value="{{ old('reference_commande', $invoice->reference_commande) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Commande</label>
                    <input type="date" name="date_commande"
                        value="{{ old('date_commande', \Carbon\Carbon::parse($invoice->date_commande)->format('Y-m-d')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom Fournisseur</label>
                    <input type="text" name="nom_fournisseur"
                        value="{{ old('nom_fournisseur', $invoice->nom_fournisseur) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code Fournisseur</label>
                    <input type="text" name="code_fournisseur"
                        value="{{ old('code_fournisseur', $invoice->code_fournisseur) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Commandé Par</label>
                    <textarea name="commande_par" rows="2"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('commande_par', $invoice->commande_par) }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Commandé À</label>
                    <textarea name="commande_a" rows="2"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('commande_a', $invoice->commande_a) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant HT (DH)</label>
                    <input type="text" name="montant_ht"
                        value="{{ old('montant_ht', number_format((float)$invoice->montant_ht, 2, ',', ' ')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant TVA (DH)</label>
                    <input type="text" name="montant_tva"
                        value="{{ old('montant_tva', number_format((float)$invoice->montant_tva, 2, ',', ' ')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant TTC (DH)</label>
                    <input type="text" name="montant_ttc"
                        value="{{ old('montant_ttc', number_format((float)$invoice->montant_ttc, 2, ',', ' ')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="pt-6 flex justify-between">
                <a href="{{ route('invoice.index') }}"
                    class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                    Retour
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>

</body>

</html>