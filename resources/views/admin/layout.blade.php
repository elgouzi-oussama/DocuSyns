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

        /* Fixed sidebar positioning for RTL */
        [dir="rtl"] aside {
            left: auto !important;
            right: 0 !important;
        }

        [dir="rtl"] main {
            margin-left: 0 !important;
            margin-right: 15rem !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen select-none">


    <aside class="fixed top-0 left-0 bg-white shadow-md grid grid-rows-[auto_1fr_auto_auto] h-screen w-60">
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
            <a href="{{ route('super_admin.licenses.index') }}"
                class="block px-3 py-2 rounded hover:bg-blue-50 
           {{ request()->routeIs('super_admin.licenses.*') ? 'bg-blue-100 text-blue-700' : 'text-gray-700' }}">
                🔑 {{ __('admin.sidebar.licenses') }}
            </a>

            <a href="{{ route('super_admin.profile.show') }}"
                class="block px-3 py-2 text-gray-700 hover:bg-blue-50">
                ⚙️ {{ __('admin.sidebar.settings') }}
            </a>
            @endcan
        </nav>

        <div class="bg-gray-100 text-gray-800 px-4 py-2 border-gray-300">
            @can('isTrial')
            <h3 class="font-semibold mb-2">
                {{ __('messages.trial_message') }}
            </h3>

            @endcan
            @can('isPro')
            <h3 class="font-semibold mb-2">
                {{ __('messages.pro_message') }}
            </h3>

            @endcan
            @can('isBasic')
            <h3 class="font-semibold mb-2">
                {{ __('messages.basic_message') }}
            </h3>
            @endcan
            @can('isEnterprise')
            <h3 class="font-semibold mb-2">
                {{ __('messages.enterprise_message') }}
            </h3>
            @endcan
            @can('noLicense')
            <h3 class="font-semibold mb-2">
                {{ __('messages.trial_expired_message') }}
            </h3>
            @endcan
        </div>


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
    <main class="ml-60 p-6">
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
    @can('isTrialEnded')
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 backdrop-blur-sm bg-black/30">
        <div class="bg-white/90 backdrop-blur-xl rounded-3xl p-8 max-w-md w-full shadow-2xl border border-white/20 payment-modal">
            <div class="text-center">
                <!-- Icon -->
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <!-- Title -->
                <h2 class="text-3xl font-bold text-gray-800 mb-4">
                    {{ __('messages.trial_ended_title', ['default' => 'Free Trial Ended']) }}
                </h2>

                <!-- Message -->
                <p class="text-gray-600 mb-6 leading-relaxed">
                    {{ __('messages.trial_ended_message', ['default' => 'Your 30-day free trial has come to an end. To continue using our amazing features and services, please upgrade to a paid plan.']) }}
                </p>


            </div>
        </div>
    </div>
    @endcan


</body>

</html>