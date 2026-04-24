<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Password</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-blue-50 to-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">

        <!-- Header -->
        <div class="bg-pink-600 text-white text-center py-5">
            <h2 class="text-xl font-bold">Confirm Password</h2>
            <p class="text-sm opacity-90">Secure Area Access</p>
        </div>

        <!-- Body -->
        <div class="p-6">

            <!-- Info Text -->
            <p class="text-sm text-gray-600 mb-4 text-center">
                This is a secure area. Please confirm your password to continue.
            </p>

            <!-- Form -->
            <form method="POST" action="{{ route('password.confirm') }}">
                @csrf

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

                <!-- Button -->
                <button 
                    type="submit"
                    class="w-full bg-pink-600 hover:bg-pink-800 text-white py-2 rounded-md font-semibold transition duration-200"
                >
                    Confirm
                </button>

            </form>

        </div>
    </div>

</body>
</html>