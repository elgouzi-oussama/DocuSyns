<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('admin.title'))</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* RTL adjustments */
        [dir="rtl"] body {
            direction: rtl;
            text-align: right;
        }

        [dir="rtl"] .space-x-2>*+* {
            margin-right: 0.5rem;
            margin-left: 0;
        }

        [dir="rtl"] .space-x-3>*+* {
            margin-right: 0.75rem;
            margin-left: 0;
        }

        [dir="rtl"] .text-left {
            text-align: right !important;
        }

        [dir="rtl"] .text-right {
            text-align: left !important;
        }
    </style>
</head>

<body class="bg-gray-100 flex min-h-screen select-none">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md grid grid-rows-[auto_1fr_auto] min-h-screen">
        <!-- Header -->
        <div class="p-6 border-b flex justify-center items-center">
            <h1 class="text-2xl font-bold text-blue-600 select-none text-center">
                {{ __('admin.sidebar.title') }}
            </h1>
        </div>

        <!-- Navigation -->
        <nav class="p-4 space-y-2 overflow-y-auto">
            @can('isAdmin')
            <a href="{{ route('admin.dashboard') }}"
                class="block px-3 py-2 rounded hover:bg-blue-50 
           {{ request()->routeIs('admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                🏠 {{ __('admin.sidebar.dashboard') }}
            </a>

            @can('invoice.index')
            <a href="{{ route('admin.invoices.index') }}"
                class="block px-3 py-2 rounded hover:bg-blue-50 
           {{ request()->routeIs('admin.invoices.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                📄 {{ __('admin.sidebar.invoices') }}
            </a>
            @endcan

            @can('user.index')
            <a href="{{ route('admin.users.index') }}"
                class="block px-3 py-2 rounded hover:bg-blue-50 
           {{ request()->routeIs('admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                👤 {{ __('admin.sidebar.users') }}
            </a>
            @endcan

            @can('profile.show')
            <a href="{{ route('admin.profile.show') }}"
                class="block px-3 py-2 text-gray-700 hover:bg-blue-50">
                ⚙️ {{ __('admin.sidebar.settings') }}
            </a>
            @endcan
            @endcan

            @can('isSuperAdmin')
            <a href="{{ route('super_admin.dashboard') }}"
                class="block px-3 py-2 rounded hover:bg-blue-50 
           {{ request()->routeIs('super_admin.dashboard') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                🏠 {{ __('admin.sidebar.dashboard') }}
            </a>

            <a href="{{ route('super_admin.invoices.index') }}"
                class="block px-3 py-2 rounded hover:bg-blue-50 
           {{ request()->routeIs('super_admin.invoices.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                📄 {{ __('admin.sidebar.invoices') }}
            </a>

            <a href="{{ route('super_admin.users.index') }}"
                class="block px-3 py-2 rounded hover:bg-blue-50 
           {{ request()->routeIs('super_admin.users.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                👤 {{ __('admin.sidebar.users') }}
            </a>

            <a href="{{ route('super_admin.profile.show') }}"
                class="block px-3 py-2 text-gray-700 hover:bg-blue-50">
                ⚙️ {{ __('admin.sidebar.settings') }}
            </a>
            @endcan
        </nav>

        <!-- Language Switcher -->
        <div class="p-4 border-t bg-gray-50 flex justify-center items-center gap-2 {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
            @foreach(config('app.supported_locales') as $locale)
            <a href="{{ route('set-locale', $locale) }}"
                class="px-3 py-2 rounded-lg font-medium text-sm transition
                      {{ app()->getLocale() === $locale 
                          ? 'bg-blue-600 text-white' 
                          : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                @if($locale === 'en') EN
                @elseif($locale === 'fr') FR
                @elseif($locale === 'ar') AR
                @endif
            </a>
            @endforeach
        </div>
    </aside>


    <!-- Main Content -->
    <main class="flex-1 p-6">
        <header class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700">@yield('page_title')</h2>

            <div class="{{ app()->getLocale() === 'ar' ? 'flex-row-reverse space-x-reverse' : '' }}">
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                        {{ __('admin.logout') }}
                    </button>
                </form>
            </div>
        </header>

        @yield('content')
    </main>

</body>

</html>