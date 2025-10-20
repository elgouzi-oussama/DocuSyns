@extends('admin.layout')

@section('title', 'Admin Dashboard')
@section('page_title', 'Tableau de bord')

@section('content')
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
<div class="bg-white p-6 rounded-lg shadow-sm">
    <h3 class="text-xl font-medium text-gray-700 mb-4">Bienvenue dans l’espace administrateur 👋</h3>
    <p class="text-gray-600">Ici vous pouvez gérer vos demandes, utilisateurs et plus encore.</p>


    <div class="grid grid-cols-3 gap-4 mt-6">
        @can('isAdmin')
        <a href="{{ route('admin.invoices.index') }}" class="bg-blue-50 p-4 rounded-lg text-center">
            <h4 class="text-blue-700 text-lg font-semibold">{{ $countinvoice }}</h4>
            <p class="text-gray-600 text-sm">bon de commande</p>
        </a>
        <a href="{{ route('admin.invoices.index') }}" class="bg-green-50 p-4 rounded-lg text-center">
            <h4 class="text-green-700 text-lg font-semibold">{{ $countuser }}</h4>
            <p class="text-gray-600 text-sm">Utilisateurs</p>
        </a>
        @endcan
        @cannot('isAdmin')
        <a href="{{ route('super_admin.invoices.index') }}" class="bg-blue-50 p-4 rounded-lg text-center">
            <h4 class="text-blue-700 text-lg font-semibold">{{ $countinvoice }}</h4>
            <p class="text-gray-600 text-sm">bon de commande</p>
        </a>
        <a href="{{ route('super_admin.invoices.index') }}" class="bg-green-50 p-4 rounded-lg text-center">
            <h4 class="text-green-700 text-lg font-semibold">{{ $countuser }}</h4>
            <p class="text-gray-600 text-sm">Utilisateurs</p>
        </a>
        @endcannot
    </div>
</div>
@endsection