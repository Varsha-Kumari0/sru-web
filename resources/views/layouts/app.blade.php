<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'SRU Alumni')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-[#2f4f8f] text-white px-6 py-3 flex justify-between items-center">

    <div class="font-semibold text-lg">
        <a href="{{ url('/') }}" class="font-semibold text-lg">
            SRU Alumni
        </a>
    </div>

    <div class="hidden lg:flex items-center space-x-5 text-sm">
        <a href="{{ route('about') }}" class="hover:underline">About</a>
        <a href="{{ route('testimonials.index') }}" class="hover:underline">Testimonials</a>
        <a href="{{ route('gallery') }}" class="hover:underline">Gallery</a>
        <a href="{{ route('engage') }}" class="hover:underline">Engage</a>
        <a href="{{ route('newsroom') }}" class="hover:underline">Newsroom</a>
        <a href="{{ route('events.index') }}" class="hover:underline">Events</a>
        <a href="{{ route('contact') }}" class="hover:underline">Contact</a>
        <a href="/profile" class="hover:underline">Profile</a>

        <form method="POST" action="/logout" class="inline">
            @csrf
            <button class="hover:underline">Logout</button>
        </form>
    </div>

</nav>

    <!-- CONTENT -->
    <div class="p-6">
        @yield('content')
    </div>

</body>
</html>
