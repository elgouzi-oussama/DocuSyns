<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        html[dir="rtl"] {
            direction: rtl;
        }

        html[dir="rtl"] .space-x-2>*+*,
        html[dir="rtl"] .space-x-3>*+*,
        html[dir="rtl"] .space-x-4>*+*,
        html[dir="rtl"] .space-x-6>*+* {
            margin-left: 0;
            margin-right: 0.5rem;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col select-none">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
            <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} space-x-2">
                <span class="text-2xl font-bold text-blue-600">DocuSyns</span>
            </div>

            <div class="hidden md:flex {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} space-x-6">
                <a href="{{ route('index') }}" class="text-gray-700 hover:text-blue-600 transition">{{ __('messages.home') }}</a>
                @can('invoice.index')
                <a href="{{ route('invoice.index') }}" class="text-gray-700 hover:text-blue-600 transition">{{ __('messages.orders') }}</a>
                @endcan
                <a href="{{ route('user.rapports.index') }}" class="text-gray-700 hover:text-blue-600 transition">{{ __('messages.reports') }}</a>
                <a href="{{ route('user.contact.index') }}" class="text-gray-700 hover:text-blue-600 transition">{{ __('messages.contact') }}</a>
            </div>

            <div class="flex items-center {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} space-x-4">
                <!-- Language Switcher -->
                <div class="flex {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }} space-x-2">
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

                @auth
                <a href="{{ route('logout') }}" class="bg-red-400 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                    {{ __('messages.logout') }}
                </a>
                @can('profile.show')
                <a href="{{ route('profile.show') }}" class="">
                    <x-css-profile class="w-10 h-10 p-1 rounded-full hover:bg-gray-400 transition" />
                </a>
                @endcan
                @else
                <a href="/signin" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    {{ __('messages.login') }}
                </a>
                @endauth
            </div>
        </div>
    </nav>
    @yield('content')

    <!-- Footer -->
    <footer class="bg-white border-t mt-10 py-4 text-center text-gray-500 text-sm">
        © {{ date('Y') }} DocuSyns. {{ __('messages.all_rights_reserved') }}
    </footer>
</body>

</html>