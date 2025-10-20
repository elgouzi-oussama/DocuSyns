@extends('admin.layout')

@section('title', 'Modifier une facture')
@section('page_title', 'Modifier la facture')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-lg mx-auto">

    @can('isAdmin')
    <form method="POST" action="{{ route('admin.invoices.update', $invoice->id) }}">
        @endcan
        @cannot('isAdmin')
        <form method="POST" action="{{ route('super_admin.invoices.update', $invoice->id) }}">
            @endcannot
            @csrf
            @method('PUT')

            {{-- ✅ User --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Utilisateur</label>
                <select name="user_id" class="w-full border-gray-300 rounded px-3 py-2 bg-gray-200" required>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $invoice->user_id == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
                @error('user_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- ✅ Référence commande --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Référence Commande</label>
                    <input type="text" name="reference_commande"
                        value="{{ old('reference_commande', $invoice->reference_commande) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('reference_commande') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ✅ Date commande --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Commande</label>
                    <input type="date" name="date_commande"
                        value="{{ old('date_commande', \Carbon\Carbon::parse($invoice->date_commande)->format('Y-m-d')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('date_commande') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ✅ Nom fournisseur --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom Fournisseur</label>
                    <input type="text" name="nom_fournisseur"
                        value="{{ old('nom_fournisseur', $invoice->nom_fournisseur) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('nom_fournisseur') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ✅ Code fournisseur --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code Fournisseur</label>
                    <input type="text" name="code_fournisseur"
                        value="{{ old('code_fournisseur', $invoice->code_fournisseur) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('code_fournisseur') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ✅ Commandé par --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Commandé Par</label>
                    <textarea name="commande_par" rows="2"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('commande_par', $invoice->commande_par) }}</textarea>
                    @error('commande_par') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ✅ Commandé à --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Commandé À</label>
                    <textarea name="commande_a" rows="2"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none">{{ old('commande_a', $invoice->commande_a) }}</textarea>
                    @error('commande_a') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ✅ Montant HT --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant HT (DH)</label>
                    <input type="text" name="montant_ht"
                        value="{{ old('montant_ht', number_format((float)$invoice->montant_ht, 2, ',', ' ')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('montant_ht') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ✅ Montant TVA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant TVA (DH)</label>
                    <input type="text" name="montant_tva"
                        value="{{ old('montant_tva', number_format((float)$invoice->montant_tva, 2, ',', ' ')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('montant_tva') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- ✅ Montant TTC --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant TTC (DH)</label>
                    <input type="text" name="montant_ttc"
                        value="{{ old('montant_ttc', number_format((float)$invoice->montant_ttc, 2, ',', ' ')) }}"
                        class="bg-gray-200 p-2 w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('montant_ttc') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- ✅ Statut --}}
            <div class="mb-4 mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" class="w-full border-gray-300 rounded px-3 py-2 bg-gray-200">
                    <option value="en_attente" {{ $invoice->status == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="approuvé" {{ $invoice->status == 'approuvé' ? 'selected' : '' }}>Approuvé</option>
                    <option value="rejeté" {{ $invoice->status == 'rejeté' ? 'selected' : '' }}>Rejeté</option>
                </select>
                @error('statut') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- ✅ Buttons --}}
            <div class="flex justify-end space-x-3">
                @can('isAdmin')
                <a href="{{ route('admin.invoices.index') }}" class="px-4 py-2 bg-gray-300 rounded">Annuler</a>
                @endcan
                @cannot('isAdmin')
                <a href="{{ route('super_admin.invoices.index') }}" class="px-4 py-2 bg-gray-300 rounded">Annuler</a>
                @endcannot
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Mettre à jour
                </button>
            </div>
        </form>
</div>
@endsection