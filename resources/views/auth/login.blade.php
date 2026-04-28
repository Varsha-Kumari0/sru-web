<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SRU Alumni</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-blue-50 to-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">

        <!-- Header -->
        <div class="bg-pink-600 text-white text-center py-5">
            <h2 class="text-xl font-bold">Login</h2>
            <p class="text-sm opacity-90">SRU Alumni Portal</p>
        </div>

        <!-- Body -->
        <div class="p-6">

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-green-600 text-sm font-medium text-center">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('auth_required'))
                <div class="mb-4 text-red-600 text-sm font-medium text-center">
                    {{ session('auth_required') }}
                </div>
            @endif

            <!-- Logo -->
            <h1 class="text-6xl font-extrabold text-center text-blue-900 mb-6">
                SRU
            </h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700">Email</label>

                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required autofocus
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700">Password</label>

                    <div class="relative">
                        <input 
                            id="password"
                            type="password" 
                            name="password"
                            required
                            class="w-full mt-1 px-3 py-2 pr-10 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        >
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                            aria-label="Show password"
                        >
                            <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2.062 12.348a1 1 0 0 1 0-.696C3.423 8.185 7.36 5 12 5s8.577 3.185 9.938 6.652a1 1 0 0 1 0 .696C20.577 15.815 16.64 19 12 19s-8.577-3.185-9.938-6.652"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.733 5.076A10.744 10.744 0 0 1 12 5c4.64 0 8.577 3.185 9.938 6.652a1 1 0 0 1 0 .696 10.69 10.69 0 0 1-1.673 2.888"/>
                                <path d="M6.228 6.228A10.691 10.691 0 0 0 2.062 11.652a1 1 0 0 0 0 .696C3.423 15.815 7.36 19 12 19c1.708 0 3.33-.432 4.77-1.19"/>
                                <path d="M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="mr-2 rounded border-gray-300">
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-blue-700 hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <button 
                    type="submit"
                    class="w-full bg-pink-600 hover:bg-pink-800 text-white py-2 rounded-md font-semibold transition duration-200"
                >
                    Log in
                </button>

                <p class="mt-4 text-sm text-center text-gray-600">
                    Not registered? 
                    <a href="{{ route('register') }}" class="text-blue-700 hover:underline font-semibold">Create an account</a>
                </p>

            </form>

        </div>
    </div>

</body>

<script>
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('togglePassword');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (passwordInput && toggleButton) {
        toggleButton.addEventListener('click', () => {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isPassword);
            eyeClosed.classList.toggle('hidden', !isPassword);
            toggleButton.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
        });
    }
</script>
</html>