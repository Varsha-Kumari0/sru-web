<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SRU Alumni</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .sru-blue {
            background: linear-gradient(135deg, #1e3a8a, #2563eb);
        }
    </style>
</head>
<body class="bg-gray-100">

<!-- 🔷 NAVBAR -->
<nav class="bg-blue-900 text-white px-6 py-4 flex justify-between items-center shadow">

    <div class="flex items-center gap-3">
        <img src="/images/sru-logo.png" class="w-10 h-10">
        <h1 class="text-lg font-semibold">SRU Alumni</h1>
    </div>

    <div class="space-x-4">
        @auth
            <!-- <a href="/dashboard" class="hover:underline">Dashboard</a> -->
            <a href="/profile" class="hover:underline">Profile</a>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="hover:underline bg-transparent border-none p-0 cursor-pointer">Logout</button>
            </form>
        @else
            <a href="/login" class="hover:underline">Login</a>
            <a href="/register" class="bg-white text-blue-900 px-4 py-1 rounded">Register</a>
        @endauth
    </div>

</nav>

<!-- 🔷 HERO SECTION -->
<section class="sru-blue text-white py-20 text-center">

    <h2 class="text-4xl md:text-5xl font-bold mb-4">
        Welcome to SR University Alumni Network
    </h2>

    <p class="text-lg opacity-90 mb-6">
        Connect. Grow. Inspire.
    </p>

    <div class="space-x-4">
        @auth
            <!-- <a href="/dashboard" class="bg-white text-blue-900 px-6 py-2 rounded font-semibold">
                Go to Dashboard
            </a> -->
        @else
            <a href="/login" class="bg-white text-blue-900 px-6 py-2 rounded font-semibold">
                Login
            </a>

            <a href="/register" class="border border-white px-6 py-2 rounded font-semibold">
                Join Now
            </a>
        @endauth
    </div>

</section>

<!-- 🔷 ABOUT SECTION -->
<section class="py-16 bg-white text-center">

    <h3 class="text-2xl font-semibold mb-4">About Alumni Network</h3>

    <p class="max-w-2xl mx-auto text-gray-600">
        The SRU Alumni Network helps graduates stay connected with the university,
        explore career opportunities, and collaborate with fellow alumni.
    </p>

</section>

<!-- 🔷 FEATURES -->
<section class="py-16 bg-gray-50">

    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 text-center">

        <div class="bg-white p-6 rounded shadow">
            <h4 class="font-semibold text-lg mb-2">Build Profile</h4>
            <p class="text-gray-600 text-sm">Showcase your academic and professional journey.</p>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h4 class="font-semibold text-lg mb-2">Connect</h4>
            <p class="text-gray-600 text-sm">Stay in touch with alumni across the globe.</p>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h4 class="font-semibold text-lg mb-2">Opportunities</h4>
            <p class="text-gray-600 text-sm">Discover jobs, internships, and collaborations.</p>
        </div>

    </div>

</section>

<!-- 🔷 FOOTER -->
<footer class="bg-blue-900 text-white text-center py-6 mt-10">

    <p>© {{ date('Y') }} SR University Alumni Portal</p>

</footer>

</body>
</html>