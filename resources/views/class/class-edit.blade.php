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
        <span class="text-gray-600 font-medium">Edit</span>
    </div>

    <!-- PAGE HEADER -->
    <header class="px-6 pt-4 pb-5">
        <h1 class="text-xl font-semibold text-gray-800">Edit kelas</h1>
        <p class="text-sm text-gray-400 mt-0.5">Perbarui data kelas <span class="font-medium text-gray-600">{{ $class->name }}</span>.</p>
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
            <form id="formEditClass"
                  action="{{ route('class.update', $class->slug) }}"
                  method="POST">
                @csrf
                @method('PUT')

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
                            value="{{ $class->name }}"
                            required
                            autofocus
                            placeholder="Contoh: X RPL 1"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
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
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="10" {{ $class->level == 10 ? 'selected' : '' }}>10</option>
                            <option value="11" {{ $class->level == 11 ? 'selected' : '' }}>11</option>
                            <option value="12" {{ $class->level == 12 ? 'selected' : '' }}>12</option>
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
                            value="{{ $class->major }}"
                            required
                            placeholder="Contoh: RPL, TKJ, DKV"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
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
                            value="{{ $class->academic_year }}"
                            required
                            placeholder="Contoh: 2025/2026"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 placeholder-gray-400
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
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
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="Ganjil" {{ $class->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                            <option value="Genap"  {{ $class->semester == 'Genap'  ? 'selected' : '' }}>Genap</option>
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
                                   focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="">-- Pilih guru --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ $class->teacher_id == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->name }} — {{ $teacher->subject }}
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
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 1.1.9 2 2 2h12a2 2 0 002-2V9l-4-4H6a2 2 0 00-2 2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 3v4h4M9 13h6m-3-3v6" />
                            </svg>
                            Simpan perubahan
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

    // AUTO FOCUS + SELECT
    setTimeout(() => {
        const first = document.getElementById('firstInput');
        if (first) {
            first.focus();
            first.select();
        }
    }, 100);

    if (typeof window.$ === 'undefined') {
        console.error('jQuery belum load');
        return;
    }

    if (typeof window.updateData !== 'function') {
        console.error('updateData belum tersedia (ajax.js belum ke-load)');
        return;
    }

    window.updateData(
        '#formEditClass',
        "{{ route('class.update', $class->slug) }}",
        {
            onSuccess: function(res) {

                $('#alertBox').html(`
                    <div class="flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        ${res.message ?? 'Kelas berhasil diperbarui.'}
                    </div>
                `);

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