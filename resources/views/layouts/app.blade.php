<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'SRU Alumni')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-blue-700 to-blue-500 min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-white shadow-md p-4 flex justify-between">
        <h1 class="text-blue-700 font-semibold text-lg">SRU Alumni</h1>

        <div>
            <a href="/dashboard" class="mr-4 text-gray-700">Dashboard</a>
            <a href="/profile/create" class="mr-4 text-gray-700">Profile</a>

            <form method="POST" action="/logout" class="inline">
                @csrf
                <button class="text-red-500">Logout</button>
            </form>
        </div>
    </nav>

    <!-- CONTENT -->
    <div class="p-6">
        @yield('content')
    </div>

</body>
</html>