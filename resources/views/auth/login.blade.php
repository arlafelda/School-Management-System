<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GameLab Indonesia</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- HAPUS CDN TAILWIND --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <style>
        body {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">

        <div class="text-center mb-10">
            <img src="{{ asset('images/logo-gamelab.png') }}" alt="GameLab Indonesia" class="h-14 w-14 mx-auto object-contain mb-4">

            <h1 class="text-2xl font-extrabold tracking-tight">
                <span class="text-[#29ABE2]">GAMELAB</span> <span class="text-[#10243A]">INDONESIA</span>
            </h1>

            <h2 class="text-lg font-bold mt-2 text-[#10243A]">
                Selamat Datang Kembali
            </h2>

            <p class="text-sm text-[#62788A] mt-2">
                Silakan masuk ke akun Anda
            </p>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-[#E7EEF3] shadow-sm">

            <div id="alertBox"></div>

            @if(session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form id="loginForm"
                  method="POST"
                  action="{{ route('login') }}"
                  class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold mb-1.5 text-[#10243A]">
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input
                        id="emailField"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="Masukkan email"
                        class="w-full border border-[#E7EEF3] rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#29ABE2] focus:border-transparent"
                    >

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1.5 text-[#10243A]">
                        Password <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="Masukkan password"
                        class="w-full border border-[#E7EEF3] rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#29ABE2] focus:border-transparent"
                    >

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2 text-[#10243A]">
                        <input
                            type="checkbox"
                            name="remember"
                            class="accent-[#29ABE2]"
                        >
                        Ingat saya
                    </label>

                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-[#29ABE2] font-semibold hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button id="loginBtn"
                        type="submit"
                        class="w-full bg-[#29ABE2] text-white py-3 rounded-lg font-semibold hover:bg-[#1C7FAE] transition-colors">
                    Masuk
                </button>
            </form>

        </div>

        @if(Route::has('register'))
            <p class="text-center text-sm text-[#62788A] mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}"
                   class="text-[#29ABE2] font-semibold hover:underline">
                    Daftar
                </a>
            </p>
        @endif

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {

            setTimeout(() => {
                $('#emailField').focus();
            }, 100);

            $('#loginForm').submit(function(e) {
                e.preventDefault();

                let btn = $('#loginBtn');
                btn.text('Loading...').prop('disabled', true);

                $('#alertBox').html('');

                $.ajax({
                    url: "{{ route('login') }}",
                    type: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    data: {
                        email: $('input[name=email]').val(),
                        password: $('input[name=password]').val(),
                        remember: $('input[name=remember]').is(':checked') ? 1 : 0
                    },

                    success: function(res) {
                        $('#alertBox').html(`
                            <div class="mb-4 text-sm text-green-600">
                                Login berhasil, mengalihkan...
                            </div>
                        `);

                        setTimeout(() => {
                            window.location.href = res.redirect;
                        }, 1000);
                    },

                    error: function(xhr) {
                        let message = "Terjadi kesalahan server!";

                        if (xhr.responseJSON) {
                            message = xhr.responseJSON.message;
                        }

                        $('#alertBox').html(`
                            <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                                ${message}
                            </div>
                        `);

                        btn.text('Masuk').prop('disabled', false);
                    }
                });
            });

        });
    </script>

</body>
</html>