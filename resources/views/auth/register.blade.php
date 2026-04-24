<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRU Alumni Register</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-blue-50 to-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">

        <!-- Header -->
        <div class="bg-pink-600 text-white text-center py-5">
            <h2 class="text-xl font-bold">Create Account</h2>
            <p class="text-sm opacity-90">SRU Alumni Portal</p>
        </div>

        <!-- Body -->
        <div class="p-6">

            <!-- Title -->
            <h1 class="text-6xl font-extrabold text-center text-blue-900 mb-6">
                SRU
            </h1>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700">
                        Email Address
                    </label>

                    <input 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    >

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Button -->
                <button 
                    type="submit"
                    class="w-full bg-pink-600 hover:bg-pink-800 text-white py-2 rounded-md font-semibold transition duration-200"
                >
                    Generate Password
                </button>

            </form>

            <!-- Footer -->
            <p class="text-center text-sm text-gray-600 mt-4">
                Already registered?
                <a href="{{ route('login') }}" class="text-blue-700 font-semibold hover:underline">
                    Login
                </a>
            </p>

        </div>
    </div>

</body>
</html>