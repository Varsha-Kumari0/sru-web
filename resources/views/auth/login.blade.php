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

                    <input 
                        type="password" 
                        name="password"
                        required
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >

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

            </form>

        </div>
    </div>

</body>
</html>