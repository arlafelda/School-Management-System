<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Academy Ledger</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- HAPUS CDN TAILWIND --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        h1, h2 {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

    <div class="w-full max-w-md">

        <div class="text-center mb-10">
            <div class="w-12 h-12 mx-auto bg-blue-600 rounded-xl flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-white text-2xl">
                    account_balance
                </span>
            </div>

            <h1 class="text-2xl font-extrabold">
                Academy Ledger
            </h1>

            <h2 class="text-lg font-bold mt-1">
                Selamat Datang Kembali
            </h2>

            <p class="text-sm text-gray-500 mt-2">
                Silakan masuk ke akun Anda
            </p>
        </div>

        <div class="bg-white p-8 rounded-xl shadow border">

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
                    <label class="block text-xs font-semibold mb-1">
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
                        class="w-full border rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        placeholder="Masukkan password"
                        class="w-full border rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600"
                    >

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="remember"
                            class="accent-blue-600"
                        >
                        Ingat saya
                    </label>

                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-blue-600 font-semibold hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button id="loginBtn"
                        type="submit"
                        class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    Masuk
                </button>
            </form>

        </div>

        @if(Route::has('register'))
            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}"
                   class="text-blue-600 font-semibold hover:underline">
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