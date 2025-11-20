<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">
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

        .blur-overlay {
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .payment-modal {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                @guest
                <a href="{{ route('invoice.index') }}" class="text-gray-700 hover:text-blue-600 transition">{{ __('messages.orders') }}</a>
                @endguest
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

    <!-- Payment Notice Modal - Show if trial expired -->
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

    <!-- Footer -->
    <footer class="flex bg-white border-t mt-10 py-4 text-center text-gray-500 text-sm">
        <p class="font-semibold text-gray-800  text-start  w-[40%] px-3 ">

            @can('isTrial')
            {{ __('messages.trial_message') }}

            @endcan
            @can('isPro')
            {{ __('messages.pro_message') }}

            @endcan
            @can('isBasic')
            {{ __('messages.basic_message') }}

            @endcan
            @can('isEnterprise')

            {{ __('messages.enterprise_message') }}

            @endcan
            @can('noLicense')
            {{ __('messages.trial_expired_message') }}

            @endcan
        </p>
        <p class="w-[60%] text-start"> © {{ date('Y') }} DocuSyns. {{ __('messages.all_rights_reserved') }}</p>
    </footer>

    </div>
</body>

</html>