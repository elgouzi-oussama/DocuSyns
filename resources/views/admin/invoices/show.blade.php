@extends('admin.layout')

@section('title', 'Détails de la facture')
@section('page_title', 'Bon de Dommande #'.$invoice->id)

@section('content')
<div class="bg-white p-6 rounded-lg shadow-sm max-w-2xl mx-auto">

    @if(session('success'))
    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
    @endif

    <h2 class="text-xl font-semibold mb-4 text-gray-700">Bon de Dommande #{{ $invoice->id }}</h2>

    <div class="space-y-3">
        <div class="p-6 bg-white rounded-lg shadow-md space-y-2 text-gray-800">
            <h2 class="text-xl font-semibold mb-4">Détails de la Facture</h2>

            <p><strong>Utilisateur :</strong> {{ $invoice->user->name ?? '—' }}</p>
            <p><strong>Email :</strong> {{ $invoice->user->email ?? '—' }}</p>

            <p><strong>Référence Commande :</strong> {{ $invoice->reference_commande ?? '—' }}</p>
            <p><strong>Date Commande :</strong>
                {{ $invoice->date_commande ? \Carbon\Carbon::parse($invoice->date_commande)->format('d/m/Y') : '—' }}
            </p>
            <p><strong>Nom Fournisseur :</strong> {{ $invoice->nom_fournisseur ?? '—' }}</p>
            <p><strong>Code Fournisseur :</strong> {{ $invoice->code_fournisseur ?? '—' }}</p>

            <p><strong>Commandé Par :</strong><br> {{ $invoice->commande_par ?? '—' }}</p>
            <p><strong>Commandé À :</strong><br> {{ $invoice->commande_a ?? '—' }}</p>

            <p><strong>Montant HT :</strong> {{ number_format($invoice->montant_ht, 2, ',', ' ') }} MAD</p>
            <p><strong>Montant TVA :</strong> {{ number_format($invoice->montant_tva, 2, ',', ' ') }} MAD</p>
            <p><strong>Montant TTC :</strong> {{ number_format($invoice->montant_ttc, 2, ',', ' ') }} MAD</p>


            <p><strong>Statut :</strong>
                <span class="px-2 py-1 rounded text-sm
            @if($invoice->statut === 'approuvé') bg-green-100 text-green-700
            @elseif($invoice->statut === 'rejeté') bg-red-100 text-red-700
            @else bg-yellow-100 text-yellow-700 @endif">
                    {{ ucfirst($invoice->statut) }}
                </span>
            </p>

            <p><strong>Date de création :</strong> {{ $invoice->created_at->format('d/m/Y H:i') }}</p>

            @if ($invoice->file)
            <p><strong>Fichier associé :</strong>
                <a href="{{ asset('storage/' . $invoice->file) }}" target="_blank" class="text-blue-600 hover:underline">
                    📎 Voir le fichier
                </a>
            </p>
            @endif
        </div>

        @can('isAdmin')
        <div class="flex justify-between space-x-3 mt-6">
            <div class="flex justify-start">
                @if($invoice->statut !== 'approuvé')
                <form action="{{ route('admin.invoices.approve', $invoice->id) }}" method="POST">
                    @csrf
                    <button class="bg-green-600 text-white px-4 py-2 rounded">Approuver</button>
                </form>
                @elseif($invoice->statut !== 'rejeté')
                <form action="{{ route('admin.invoices.reject', $invoice->id) }}" method="POST">
                    @csrf
                    <button class="bg-red-600 text-white px-4 py-2 rounded">Rejeter</button>
                </form>
                @endif
            </div>
            <div class="flex justify-end">
                <a href="{{ route('admin.invoices.edit', $invoice->id) }}"
                    class="bg-green-700 text-white px-4 py-2 rounded me-2">Modifier</a>
                <form action="{{ route('admin.invoices.destroy', $invoice->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Supprimer cette commande ?')"
                        class="bg-red-700 text-white px-4 py-2 rounded me-2">
                        Supprimer
                    </button>
                </form>
                <a href="{{ route('admin.invoices.index') }}" class="bg-gray-300 px-4 py-2 rounded">Retour</a>
            </div>
        </div>
        @endcan
        @cannot('isAdmin')
        <div class="flex justify-between space-x-3 mt-6">
            <div class="flex justify-start">
                @if($invoice->statut !== 'approuvé')
                <form action="{{ route('super_admin.invoices.approve', $invoice->id) }}" method="POST">
                    @csrf
                    <button class="bg-green-600 text-white px-4 py-2 rounded me-2">Approuver</button>
                </form>
                @endif
                @if($invoice->statut !== 'rejeté')
                <form action="{{ route('super_admin.invoices.reject', $invoice->id) }}" method="POST">
                    @csrf
                    <button class="bg-red-600 text-white px-4 py-2 rounded">Rejeter</button>
                </form>
                @endif
            </div>
            <div class="flex justify-end">
                <a href="{{ route('super_admin.invoices.edit', $invoice->id) }}"
                    class="bg-green-700 text-white px-4 py-2 rounded me-2">Modifier</a>
                <form action="{{ route('super_admin.invoices.destroy', $invoice->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Supprimer cette commande ?')"
                        class="bg-red-700 text-white px-4 py-2 rounded me-2">
                        Supprimer
                    </button>
                </form>
                <a href="{{ route('super_admin.invoices.index') }}" class="bg-gray-300 px-4 py-2 rounded">Retour</a>
            </div>
        </div>
        @endcannot
    </div>
    @endsection