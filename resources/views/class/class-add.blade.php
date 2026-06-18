@extends('layouts.app')

@section('content')

<div class="min-h-screen bg-gray-50 text-gray-800">

    <!-- BREADCRUMB -->
    <div class="px-6 pt-5 flex items-center gap-1.5 text-sm text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75v-4.5h-4.5V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z" />
        </svg>
        <a href="{{ route('class.index') }}" class="hover:text-blue-600 transition-colors">
            Kelas
        </a>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-600 font-medium">Tambah</span>
    </div>

    <!-- PAGE HEADER -->
    <header class="px-6 pt-4 pb-5">
        <h1 class="text-xl font-semibold text-gray-800">Tambah kelas</h1>
        <p class="text-sm text-gray-400 mt-0.5">Isi data berikut untuk mendaftarkan kelas baru ke sistem.</p>
    </header>

    <!-- CONTENT -->
    <main class="px-6 pb-10">

        <!-- ALERT AJAX -->
        <div id="alertBox" class="max-w-3xl mx-auto mb-4"></div>

        <div class="max-w-3xl mx-auto bg-white border border-gray-200 rounded-xl shadow-sm p-6">

            <!-- SECTION LABEL -->
            <p class="text-xs font-semibold tracking-widest text-gray-400 uppercase mb-5">
                Informasi kelas
            </p>

            <!-- FORM -->
            <form id="formClass"
                  action="{{ route('class.store') }}"
                  method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                    <!-- Nama Kelas -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Nama kelas
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <input
                            id="firstInput"
                            type="text"
                            name="name"
                            required
                            placeholder="Contoh: X RPL 1"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition">
                    </div>

                    <!-- Tingkat -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Tingkat
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <select
                            name="level"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition bg-white">
                            <option value="">-- Pilih tingkat --</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>

                    <!-- Jurusan -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Jurusan
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <input
                            type="text"
                            name="major"
                            required
                            placeholder="Contoh: RPL, TKJ, DKV"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition">
                    </div>

                    <!-- Tahun Ajaran -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Tahun ajaran
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <input
                            type="text"
                            name="academic_year"
                            required
                            placeholder="Contoh: 2025/2026"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition">
                        <span class="text-xs text-gray-400">Format: YYYY/YYYY</span>
                    </div>

                    <!-- Semester -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Semester
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <select
                            name="semester"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition bg-white">
                            <option value="">-- Pilih semester --</option>
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                    </div>

                    <!-- Wali Kelas -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Wali kelas
                            <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <select
                            name="teacher_id"
                            required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                   transition bg-white">
                            <option value="">-- Pilih guru --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">
                                    {{ $teacher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                <!-- DIVIDER -->
                <hr class="my-6 border-gray-100">

                <!-- FOOTER -->
                <div class="flex items-center justify-between">
                    <p class="text-xs text-gray-400">
                        <span class="text-red-500">*</span> Wajib diisi
                    </p>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('class.index') }}"
                           class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                            Kembali
                        </a>
                        <button type="submit"
                                class="flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 3v4H7V3M12 12v6m-3-3h6" />
                            </svg>
                            Simpan
                        </button>
                    </div>
                </div>

            </form>

        </div>

    </main>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // AUTO FOCUS
    setTimeout(() => {
        const first = document.getElementById('firstInput');
        if (first) first.focus();
    }, 100);

    // VALIDASI AJAX
    if (typeof window.createData !== 'function') {
        console.error('ajax.js belum load');
        return;
    }

    // AJAX SUBMIT
    window.createData(
        '#formClass',
        "{{ route('class.store') }}",
        {
            onSuccess: function(res) {

                $('#alertBox').html(`
                    <div class="flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        ${res.message ?? 'Data berhasil disimpan.'}
                    </div>
                `);

                document.getElementById('formClass').reset();

                setTimeout(() => {
                    const first = document.getElementById('firstInput');
                    if (first) first.focus();
                }, 100);
            }
        }
    );

});
</script>
@endpush