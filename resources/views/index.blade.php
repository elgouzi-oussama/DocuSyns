<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | DocuSyns</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-2xl font-bold text-blue-600">DocuSyns</span>
            </div>

            <div class="hidden md:flex space-x-6">
                <a href="{{ route('index') }}" class="text-gray-700 hover:text-blue-600 transition">Accueil</a>
                <a href="{{ route('invoice.index') }}" class="text-gray-700 hover:text-blue-600 transition">Factures</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 transition">Rapports</a>
                <a href="#" class="text-gray-700 hover:text-blue-600 transition">Contact</a>
            </div>

            <div>
                @auth
                <a href="{{ route('logout') }}" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">Déconnexion</a>
                @else
                <a href="/signin" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">Connexion</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="flex-1 flex flex-col items-center justify-center text-center px-6">
        <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">
            Bienvenue sur <span class="text-blue-600">DocuSyns</span>
        </h1>
        <p class="text-gray-600 max-w-lg mb-8">
            Gérez vos factures, vos rapports et bien plus encore depuis une seule plateforme.
        </p>

        <div class="flex space-x-4">
            <a href="{{ route('invoice.create') }}" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                ➕ Ajouter une facture
            </a>
            <a href="{{ route('invoice.index') }}" class="border border-blue-600 text-blue-600 px-6 py-3 rounded-lg hover:bg-blue-50 transition">
                📄 Voir les factures
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t mt-10 py-4 text-center text-gray-500 text-sm">
        © {{ date('Y') }} DocuSyns. Tous droits réservés.
    </footer>

</body>

</html>