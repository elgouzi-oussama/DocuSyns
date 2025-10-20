<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex min-h-screen select-none ">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
        <div class="p-6 border-b">
            <h1 class="text-2xl font-bold text-blue-600 select-none">Admin</h1>
        </div>
        <nav class="p-4 space-y-2">
            @can('isAdmin')
            <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                🏠 Tableau de bord
            </a>
            <a href="{{ route('admin.invoices.index') }}" class="block px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.invoices.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                📄 Factures
            </a>
            <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                👤 Utilisateurs
            </a>
            @endcan
            @can('isSuperAdmin')
            <a href="{{ route('super_admin.dashboard') }}" class="block px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                🏠 Tableau de bord
            </a>
            <a href="{{ route('super_admin.invoices.index') }}" class="block px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.invoices.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                📄 Factures
            </a>
            <a href="{{ route('super_admin.users.index') }}" class="block px-3 py-2 rounded hover:bg-blue-50 {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                👤 Utilisateurs
            </a>
            <a href="{{ route('super_admin.profile.show') }}" class="block px-3 py-2 text-gray-700 hover:bg-blue-50">⚙️ Paramètres</a>
            @endcan
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <header class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">@yield('page_title')</h2>

            <div>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">Déconnexion</button>
                </form>
            </div>
        </header>

        @yield('content')
    </main>

</body>

</html>