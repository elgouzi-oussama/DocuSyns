<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Vérification des Données Commande</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    @if (session('success'))
    <div class="mb-4 p-3 text-green-800 bg-green-100 rounded-lg">
        {{ session('success') }}
    </div>
    @endif
    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-4xl">
        <h1 class="text-2xl font-semibold text-gray-800 mb-6 text-center select-none">
            Vérifiez les données avant d’enregistrer
        </h1>

        <form action="{{ route('invoice.confirm') }}" method="POST" class="space-y-5">
            @csrf


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Référence Commande --}}
                @if (!empty($allData['reference_commande']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 select-none">Référence Commande</label>
                    <input type="text" name="reference_commande"
                        value="{{ $allData['reference_commande'] }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
                @else
                <div class="text-red-600 bg-red-50 p-3 rounded-lg select-none">
                    Référence commande introuvable dans le bon de commande.
                </div>
                @endif

                {{-- Date Commande --}}
                @if (!empty($allData['date_commande']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 select-none">Date Commande</label>
                    <input type="date" name="date_commande"
                        value="{{ \Carbon\Carbon::parse($allData['date_commande'])->format('Y-m-d') }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
                @else
                <div class="text-red-600 bg-red-50 p-3 rounded-lg select-none">
                    Date commande introuvable dans le bon de commande.
                </div>
                @endif

                {{-- Nom Fournisseur --}}
                @if (!empty($allData['nom_fournisseur']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 select-none">Nom Fournisseur</label>
                    <input type="text" name="nom_fournisseur"
                        value="{{ $allData['nom_fournisseur'] }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
                @else
                <div class="text-red-600 bg-red-50 p-3 rounded-lg select-none">
                    Nom fournisseur introuvable dans le bon de commande.
                </div>
                @endif

                {{-- Code Fournisseur --}}
                @if (!empty($allData['code_fournisseur']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 select-none">Code Fournisseur</label>
                    <input type="text" name="code_fournisseur"
                        value="{{ $allData['code_fournisseur'] }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
                @else
                <div class="text-red-600 bg-red-50 p-3 rounded-lg select-none">
                    Code fournisseur introuvable dans le bon de commande.
                </div>
                @endif

                {{-- Commandé Par --}}
                @if (!empty($allData['commande_par']))
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1" select-none>Commandé Par</label>
                    <textarea name="commande_par" rows="2"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">{{ $allData['commande_par'] }}</textarea>
                </div>
                @else
                <div class="text-red-600 bg-red-50 p-3 rounded-lg md:col-span-2 select-none">
                    Champ "Commandé par" introuvable dans le bon de commande.
                </div>
                @endif

                {{-- Commandé À --}}
                @if (!empty($allData['commande_a']))
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1 select-none">Commandé À</label>
                    <textarea name="commande_a" rows="2"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">{{ $allData['commande_a'] }}</textarea>
                </div>
                @else
                <div class="text-red-600 bg-red-50 p-3 rounded-lg md:col-span-2 select-none">
                    Champ "Commandé à" introuvable dans le bon de commande.
                </div>
                @endif

                {{-- Montant HT --}}
                @if (!empty($allData['montant_ht']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 select-none">Montant HT (DH)</label>
                    <input type="text" name="montant_ht"
                        value="{{ number_format((float)$allData['montant_ht'], 2, ',', ' ') }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
                @else
                <div class="text-red-600 bg-red-50 p-3 rounded-lg select-none">
                    Montant HT introuvable dans le bon de commande.
                </div>
                @endif

                {{-- Montant TVA --}}
                @if (!empty($allData['montant_tva']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1" select-none>Montant TVA (DH)</label>
                    <input type="text" name="montant_tva"
                        value="{{ number_format((float)$allData['montant_tva'], 2, ',', ' ') }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
                @else
                <div class="text-red-600 bg-red-50 p-3 rounded-lg select-none">
                    Montant TVA introuvable dans le bon de commande.
                </div>
                @endif

                {{-- Montant TTC --}}
                @if (!empty($allData['montant_ttc']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1 select-none">Montant TTC (DH)</label>
                    <input type="text" name="montant_ttc"
                        value="{{ number_format((float)$allData['montant_ttc'], 2, ',', ' ') }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500">
                </div>
                @else
                <div class="text-red-600 bg-red-50 p-3 rounded-lg select-none">
                    Montant TTC introuvable dans le bon de commande.
                </div>
                @endif

                <input type="hidden" name="file" value="{{ $allData['file'] }}">
            </div>


            <div class="pt-6 flex justify-between">
                <a href="{{ route('invoice.index') }}"
                    class="px-6 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition">
                    Retour
                </a>
                <button type="submit"
                    class="px-6 select-none py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Confirmer et Enregistrer
                </button>
            </div>
        </form>
    </div>
</body>

</html>