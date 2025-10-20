<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Application</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            /* soft gray background */
        }

        .form-card {
            background-color: #ffffff;
            /* white card */
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        }

        .input-field {
            background-color: #f9fafb;
            /* slightly off-white input background */
        }

        .input-field:focus {
            background-color: #fff;
            border-color: #d1d5db;
            box-shadow: 0 0 0 3px rgba(209, 213, 219, 0.4);
            /* soft grey focus ring */
        }

        .btn-primary {
            background-color: #4b5563;
            /* neutral dark grey */
        }

        .btn-primary:hover {
            background-color: #374151;
            /* darker hover */
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

    <div class="max-w-md w-full rounded-2xl overflow-hidden form-card">
        <!-- Header -->
        <div class="bg-gray-100 p-6 text-center">
            <h1 class="text-2xl font-semibold text-gray-800">Bienvenue</h1>
            <p class="text-gray-500 mt-1">Connectez-vous à votre compte</p>
        </div>

        <!-- Form -->
        <div class="p-8">
            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Adresse e-mail
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </span>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') . Cookie::get('email') }}"
                            placeholder="vous@example.com"
                            required
                            class="input-field block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-200">
                    </div>
                    @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <a href="#" class="text-sm text-gray-500 hover:text-gray-700">Mot de passe oublié ?</a>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="fas fa-lock text-gray-400"></i>
                        </span>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••"
                            value="{{ Cookie::get('password') }}"
                            required
                            class="input-field block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none transition duration-200">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember & Submit -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-gray-600 border-gray-300 rounded">
                        <span class="ml-2 text-sm text-gray-700">Se souvenir de moi</span>
                    </label>

                    <button
                        type="submit"
                        class="btn-primary text-white font-medium py-3 px-6 rounded-lg shadow-md transition duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-1">
                        Connexion
                    </button>
                </div>
            </form>

            <!-- Divider -->
            <div class="my-8 relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
        togglePassword.addEventListener('click', () => {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            togglePassword.querySelector('i').classList.toggle('fa-eye');
            togglePassword.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>