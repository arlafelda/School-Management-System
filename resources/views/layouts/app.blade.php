<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School') }}</title>

    <!-- Tailwind (boleh tetap kalau belum build Tailwind Vite) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- VITE (WAJIB) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-gray-100">

    @include('layouts.navigation')

    <div class="md:ml-64 min-h-screen flex flex-col">

        <button onclick="toggleSidebar()"
            class="md:hidden fixed top-4 left-4 z-50 bg-blue-600 text-white p-2 rounded-lg shadow">
            ☰
        </button>

        <main class="p-4 md:p-8 max-w-7xl w-full mx-auto space-y-6">
            @yield('content')
        </main>

    </div>

    @stack('scripts')

    <script>
        function toggleSidebar() {
            document.querySelector('.sidebar')?.classList.toggle('-translate-x-full');
        }
    </script>

</body>
</html>