<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'School') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3 {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100">

@include('layouts.navigation')

<!-- MOBILE SIDEBAR BUTTON -->
<button onclick="toggleSidebar()"
    class="md:hidden fixed top-4 left-4 z-50 bg-blue-600 text-white p-2 rounded-lg shadow">
    ☰
</button>

<!-- OVERLAY MOBILE -->
<div id="overlay"
     onclick="toggleSidebar()"
     class="hidden fixed inset-0 bg-black/50 z-40 md:hidden"></div>

<!-- BREADCRUMB AREA -->
<div class="w-full max-w-7xl mx-auto px-4 md:px-8 pt-4">
    @yield('breadcrumb')
</div>

<!-- PAGE HEADER AREA -->
<div class="w-full max-w-7xl mx-auto px-4 md:px-8">
    @yield('page-header')
</div>

<!-- MAIN CONTENT -->
<div class="md:ml-64 min-h-screen flex flex-col">

    <main class="p-4 md:p-8 w-full max-w-7xl mx-auto space-y-6">
        @yield('content')
    </main>

</div>

<!-- TOAST NOTIFICATION -->
<div id="toast"
     class="fixed top-5 right-5 hidden bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50">
</div>

@stack('scripts')

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('-translate-x-full');
    document.getElementById('overlay').classList.toggle('hidden');
}
</script>

</body>
</html>