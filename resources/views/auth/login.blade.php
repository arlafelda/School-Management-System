<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Academy Ledger</title>

    <!-- CSRF TOKEN (WAJIB UNTUK AJAX) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6 font-[Inter]">

    <div class="w-full max-w-md">

        <!-- Logo -->
        <div class="text-center mb-10">
            <div class="w-12 h-12 mx-auto bg-blue-600 rounded-xl flex items-center justify-center mb-4">
                <span class="material-symbols-outlined text-white text-2xl">account_balance</span>
            </div>

            <h1 class="text-2xl font-extrabold font-[Manrope]">Academy Ledger</h1>
            <h2 class="text-lg font-bold mt-1">Selamat Datang Kembali</h2>
            <p class="text-sm text-gray-500 mt-2">Silakan masuk ke akun Anda</p>
        </div>

        <!-- Card -->
        <div class="bg-white p-8 rounded-xl shadow border">

            <!-- ALERT AJAX -->
            <div id="alertBox"></div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="nama@email.com"
                        class="w-full border rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-semibold mb-1">
                        Password <span class="text-red-500">*</span>
                    </label>

                    <input type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full border rounded-lg px-3 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember -->
                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="remember" class="accent-blue-600">
                        Ingat saya
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-blue-600 font-semibold hover:underline">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <button id="loginBtn" type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:opacity-90 transition">
                    Masuk
                </button>
            </form>
        </div>

        <!-- Register -->
        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 font-semibold hover:underline">
                    Daftar
                </a>
            </p>
        @endif

    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- AJAX LOGIN -->
    <script>
    $(document).ready(function() {

        $('#loginForm').submit(function(e) {
            e.preventDefault();

            let btn = $('#loginBtn');
            btn.text('Loading...').prop('disabled', true);

            $('#alertBox').html(''); // reset alert

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

                    if (res.success) {
                        $('#alertBox').html(
                            '<div class="mb-4 text-sm text-green-600">Login berhasil, mengalihkan...</div>'
                        );

                        setTimeout(() => {
                            window.location.href = res.redirect;
                        }, 1000);
                    }
                },

                error: function(xhr) {

                    let message = "Terjadi kesalahan server!";

                    if (xhr.responseJSON) {
                        message = xhr.responseJSON.message;
                    }

                    $('#alertBox').html(
                        `<div class="mb-4 bg-red-100 text-red-700 p-3 rounded">${message}</div>`
                    );

                    btn.text('Masuk').prop('disabled', false);
                }
            });
        });

    });
    </script>

</body>
</html>