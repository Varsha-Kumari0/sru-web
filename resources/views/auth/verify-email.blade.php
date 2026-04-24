<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-blue-50 to-gray-100 min-h-screen flex items-center justify-center font-sans">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">

        <!-- Header -->
        <div class="bg-pink-600 text-white text-center py-5">
            <h2 class="text-xl font-bold">Verify Email</h2>
            <p class="text-sm opacity-90">Complete your registration</p>
        </div>

        <!-- Body -->
        <div class="p-6 text-center">

            <!-- Message -->
            <p class="text-sm text-gray-600 mb-4">
                Thanks for signing up! Please verify your email by clicking the link we sent.
                If you didn’t receive it, you can request another below.
            </p>

            <!-- Success Message -->
            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 text-green-600 text-sm font-medium">
                    A new verification link has been sent to your email.
                </div>
            @endif

            <!-- Buttons -->
            <div class="flex flex-col gap-3 mt-4">

                <!-- Resend -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <button 
                        type="submit"
                        class="w-full bg-pink-600 hover:bg-pink-800 text-white py-2 rounded-md font-semibold transition duration-200"
                    >
                        Resend Verification Email
                    </button>
                </form>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button 
                        type="submit"
                        class="text-sm text-gray-600 hover:text-blue-700 underline"
                    >
                        Log Out
                    </button>
                </form>

            </div>

        </div>
    </div>

</body>
</html>