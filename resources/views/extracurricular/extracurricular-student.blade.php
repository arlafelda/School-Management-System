<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ekskul Siswa</title>

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- TAILWIND -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- JQUERY (WAJIB) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- AJAX.JS -->
    <script src="{{ asset('js/ajax.js') }}"></script>
</head>

<body class="bg-gray-100 p-6">

@php
    $student = auth()->user()->student ?? null;
@endphp

<!-- HEADER -->
<div class="mb-6">
    <h1 class="text-2xl font-bold">Pilih Ekstrakurikuler</h1>
    <p class="text-gray-500 text-sm">Silakan pilih ekskul yang ingin diikuti</p>
</div>

<!-- ALERT -->
<div id="alertBox"></div>

<!-- ERROR -->
@if(!$student)
<div class="bg-red-100 text-red-700 p-4 rounded mb-6">
    Akun kamu belum terhubung dengan data siswa. Silakan hubungi admin.
</div>
@endif

<!-- LIST EKSKUL -->
<div class="grid md:grid-cols-3 gap-4">

@forelse($extracurriculars as $e)

<div class="bg-white p-5 rounded-lg shadow hover:shadow-md transition">

    <!-- NAMA -->
    <h2 class="font-bold text-lg">
        {{ $e->name }}
    </h2>

    <!-- PEMBINA -->
    <p class="text-sm text-gray-500 mt-1">
        Pembina: {{ $e->teacher->name ?? '-' }}
    </p>

    <!-- JUMLAH -->
    <p class="text-xs text-gray-400 mt-2">
        Total siswa: {{ $e->students->count() }}
    </p>

    <!-- BUTTON -->
    <div class="mt-4">

        @if($student && $student->extracurriculars->contains($e->id))

            <button class="w-full bg-green-500 text-white py-2 rounded text-sm cursor-not-allowed">
                ✔ Sudah Terdaftar
            </button>

        @elseif($student)

            <!-- 🔥 FORM AJAX -->
            <form class="formJoin"
                  action="{{ route('extracurricular.join', $e->id) }}"
                  method="POST">

                @csrf

                <button type="submit"
                    class="btnJoin w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded text-sm">
                    Daftar
                </button>

            </form>

        @else

            <button class="w-full bg-gray-400 text-white py-2 rounded text-sm cursor-not-allowed">
                Tidak tersedia
            </button>

        @endif

    </div>

</div>

@empty

<div class="col-span-3 text-center text-gray-500">
    Tidak ada data ekstrakurikuler
</div>

@endforelse

</div>

<!-- =========================
     AJAX SCRIPT
========================= -->
<script>
$(function () {

    // ⛔ pastikan ajax.js ada
    if (typeof $ === 'undefined') {
        console.error('jQuery belum load');
        return;
    }

    $(document).on('submit', '.formJoin', function (e) {
        e.preventDefault();

        let form = this;
        let button = $(form).find('.btnJoin');

        $.ajax({
            url: form.action,
            type: "POST",
            data: $(form).serialize(),

            beforeSend: function () {
                button.prop('disabled', true).text('Loading...');
            },

            success: function (res) {

                // 🔥 ubah tombol jadi sukses
                $(form).html(`
                    <button class="w-full bg-green-500 text-white py-2 rounded text-sm cursor-not-allowed">
                        ✔ Sudah Terdaftar
                    </button>
                `);

                $('#alertBox').html(`
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        Berhasil daftar ekstrakurikuler
                    </div>
                `);

            },

            error: function () {
                alert('Terjadi kesalahan');
                button.prop('disabled', false).text('Daftar');
            }
        });

    });

});
</script>

</body>
</html>