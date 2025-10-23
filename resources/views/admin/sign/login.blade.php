<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.title') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f9fafb 0%, #e5e7eb 100%);
            font-family: 'Poppins', sans-serif;

            direction: {
                    {
                    app()->getLocale()==='ar' ? 'rtl': 'ltr'
                }
            }

            ;

            text-align: {
                    {
                    app()->getLocale()==='ar' ? 'right': 'left'
                }
            }

            ;
        }

        .form-shadow {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center select-none">

    <div class="max-w-md w-full bg-white rounded-2xl overflow-hidden form-shadow p-8">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">{{ __('auth.panel_title') }}</h1>
            <p class="text-gray-500 mt-1">{{ __('auth.subtitle') }}</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.email_label') }}</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border border-gray-300 rounded-lg p-3 bg-gray-50 focus:ring-2 focus:ring-gray-400 focus:outline-none"
                    placeholder="{{ __('auth.email_placeholder') }}" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('auth.password_label') }}</label>
                <input type="password" name="password"
                    class="w-full border border-gray-300 rounded-lg p-3 bg-gray-50 focus:ring-2 focus:ring-gray-400 focus:outline-none"
                    placeholder="{{ __('auth.password_placeholder') }}" required>
            </div>

            <div class="flex items-center justify-between {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
                <label class="flex items-center text-sm text-gray-600">
                    <input type="checkbox" name="remember" class="h-4 w-4 text-gray-600 border-gray-300 rounded">
                    <span class="ml-2">{{ __('auth.remember_me') }}</span>
                </label>
                <a href="#" class="text-sm text-gray-500 hover:text-gray-700">{{ __('auth.forgot_password') }}</a>
            </div>

            <button type="submit"
                class="w-full bg-gray-800 hover:bg-gray-700 text-white font-semibold py-3 rounded-lg transition">
                {{ __('auth.login_button') }}
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-500">
            <p>© {{ date('Y') }} {{ __('auth.footer') }}</p>
        </div>
    </div>

</body>

</html>