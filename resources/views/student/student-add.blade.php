@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')

<div class="min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="w-full max-w-4xl mx-auto">

        {{-- BREADCRUMB --}}
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" />
            </svg>
            <a href="{{ route('students.index') }}" class="hover:text-indigo-600 transition">Kelola Siswa</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M9 18l6-6-6-6" />
            </svg>
            <span class="text-gray-700 font-medium">Tambah Siswa</span>
        </div>

        {{-- ALERT --}}
        <div id="alertBox" class="hidden mb-5 px-4 py-3 rounded-xl text-sm items-start gap-2"></div>

        {{-- CARD --}}
        <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-200">
            <div class="bg-white p-8">

                {{-- TITLE --}}
                <div class="mb-8">
                    <h1 class="text-xl font-semibold text-gray-800">Tambah Siswa Baru</h1>
                    <p class="text-sm text-gray-400 mt-1">Lengkapi seluruh data di bawah untuk mendaftarkan siswa baru.</p>
                </div>

                <form id="formSiswa" class="space-y-8">
                    @csrf

                    {{-- ================================ --}}
                    {{-- SECTION: AKUN LOGIN --}}
                    {{-- ================================ --}}
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                            </div>
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Akun Login</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Email Login <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    name="email"
                                    id="firstInput"
                                    placeholder="Masukkan email login"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="password"
                                    name="password"
                                    placeholder="Masukkan password"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Konfirmasi Password <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    placeholder="Ulangi password"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    required>
                            </div>

                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- ================================ --}}
                    {{-- SECTION: DATA SISWA --}}
                    {{-- ================================ --}}
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Data Siswa</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Siswa <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="name"
                                    placeholder="Masukkan nama siswa"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    NISN <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="nisn"
                                    placeholder="Masukkan NISN"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    NIS <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="nis"
                                    placeholder="Masukkan NIS"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kelas <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="class_id"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }} - {{ $class->major }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Jurusan
                                </label>
                                <input
                                    type="text"
                                    name="major"
                                    placeholder="Masukkan jurusan"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Jenis Kelamin <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="gender"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    required>
                                    <option value="">-- Pilih Jenis Kelamin --</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select
                                    name="status"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                    required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="aktif">Aktif</option>
                                    <option value="lulus">Lulus</option>
                                    <option value="pindah">Pindah</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    No. HP
                                </label>
                                <input
                                    type="text"
                                    name="phone"
                                    placeholder="Contoh: 081234567890"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tempat Lahir
                                </label>
                                <input
                                    type="text"
                                    name="birth_place"
                                    placeholder="Masukkan tempat lahir"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal Lahir
                                </label>
                                <input
                                    type="date"
                                    name="birth_date"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Alamat
                                </label>
                                <textarea
                                    name="address"
                                    placeholder="Masukkan alamat lengkap"
                                    rows="3"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"></textarea>
                            </div>

                        </div>
                    </div>

                    <hr class="border-gray-100">

                    {{-- ================================ --}}
                    {{-- SECTION: DATA ORANG TUA --}}
                    {{-- ================================ --}}
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>
                            <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider">Data Orang Tua</h2>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Ayah
                                </label>
                                <input
                                    type="text"
                                    name="father_name"
                                    placeholder="Masukkan nama ayah"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nama Ibu
                                </label>
                                <input
                                    type="text"
                                    name="mother_name"
                                    placeholder="Masukkan nama ibu"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    No. HP Orang Tua
                                </label>
                                <input
                                    type="text"
                                    name="parent_phone"
                                    placeholder="Masukkan nomor HP orang tua"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Alamat Orang Tua
                                </label>
                                <textarea
                                    name="parent_address"
                                    placeholder="Masukkan alamat orang tua"
                                    rows="3"
                                    class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none"></textarea>
                            </div>

                        </div>
                    </div>

                    {{-- DIVIDER --}}
                    <hr class="border-gray-100">

                    {{-- BUTTONS --}}
                    <div class="flex justify-end items-center gap-3 pt-1">

                        <a href="{{ route('students.index') }}"
                            class="px-5 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Siswa
                        </button>

                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts') 
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const input = document.getElementById('firstInput');
        if (input) input.focus();
        if (typeof createData !== 'undefined') {
            createData('#formSiswa', "{{ route('students.store') }}", {
                onSuccess: function() {
                    document.getElementById('formSiswa').reset();
                    if (input) input.focus();
                }
            });
        } else {
            console.error('createData belum tersedia');
        }
    });
</script> 
@endpush