<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">

        <!-- Header -->
        <div class="bg-pink-600 text-white text-center py-5">
            <h2 class="text-xl font-bold">Reset Password</h2>
            <p class="text-sm opacity-90">Secure your account</p>
        </div>

        <!-- Body -->
        <div class="p-6">

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700">Email</label>

                    <input 
                        type="email" 
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required autofocus
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500 focus:outline-none"
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
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500 focus:outline-none"
                    >

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700">Confirm Password</label>

                    <input 
                        type="password" 
                        name="password_confirmation"
                        required
                        class="w-full mt-1 px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-pink-500 focus:outline-none"
                    >

                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Button -->
                <button 
                    type="submit"
                    class="w-full bg-pink-600 hover:bg-pink-700 text-white py-2 rounded-md font-semibold transition duration-200"
                >
                    Reset Password
                </button>

            </form>

        </div>
    </div>

</body>
</html>