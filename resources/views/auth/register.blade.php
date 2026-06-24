<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - GameLab Indonesia</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">

        <a href="{{ url('/') }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-[#62788A] hover:text-[#10243A] transition-colors mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            Kembali ke Beranda
        </a>

        <div class="text-center mb-10">
            <img src="{{ asset('images/logo-gamelab.png') }}" alt="GameLab Indonesia" class="h-14 w-14 mx-auto object-contain mb-4">

            <h1 class="text-2xl font-extrabold tracking-tight">
                <span class="text-[#29ABE2]">GAMELAB</span> <span class="text-[#10243A]">INDONESIA</span>
            </h1>

            <h2 class="text-lg font-bold mt-2 text-[#10243A]">
                Buat Akun Baru
            </h2>

            <p class="text-sm text-[#62788A] mt-2">
                Lengkapi data di bawah untuk mendaftar
            </p>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-[#E7EEF3] shadow-sm">

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-semibold mb-1.5 text-[#10243A]">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Masukkan nama lengkap"
                        class="w-full border border-[#E7EEF3] rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#29ABE2] focus:border-transparent"
                    >

                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-semibold mb-1.5 text-[#10243A]">
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        placeholder="Masukkan email"
                        class="w-full border border-[#E7EEF3] rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#29ABE2] focus:border-transparent"
                    >

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold mb-1.5 text-[#10243A]">
                        Password <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Masukkan password"
                        class="w-full border border-[#E7EEF3] rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#29ABE2] focus:border-transparent"
                    >

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold mb-1.5 text-[#10243A]">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi password"
                        class="w-full border border-[#E7EEF3] rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#29ABE2] focus:border-transparent"
                    >

                    @error('password_confirmation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full bg-[#29ABE2] text-white py-3 rounded-lg font-semibold hover:bg-[#1C7FAE] transition-colors">
                    Daftar
                </button>
            </form>

        </div>

        <p class="text-center text-sm text-[#62788A] mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}"
               class="text-[#29ABE2] font-semibold hover:underline">
                Masuk di sini
            </a>
        </p>

    </div>

</body>
</html>