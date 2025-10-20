@extends('admin.layout')

@section('title', 'Admin Dashboard')
@section('page_title', 'Tableau de bord')

@section('content')
<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded-lg shadow">
    <h2 class="text-xl font-bold mb-4 text-center">Changer le mot de passe</h2>
    <form method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        <div class="mb-4">
            <label class="block mb-1">Nouveau mot de passe</label>
            <input type="password" name="password" class="w-full border rounded p-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" class="w-full border rounded p-2" required>
        </div>
        <button class="w-full bg-blue-600 text-white py-2 rounded">Changer</button>
    </form>
</div>
@endsection