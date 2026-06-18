@extends('layouts.app')

@section('title', 'Tambah Admin')

@section('content')

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4 md:p-8">

    <div class="w-full max-w-4xl">

        <!-- BREADCRUMB -->
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard.super_admin') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <a href="{{ route('admin.index') }}" class="hover:text-indigo-600 transition">Kelola Admin</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <span class="text-gray-700 font-medium">Tambah Admin</span>
        </div>

        <!-- CARD -->
        <div class="rounded-2xl overflow-hidden shadow-lg border border-gray-200">

            <!-- ===== FORM BODY ===== -->
            <div class="bg-white p-8">

                <!-- TITLE -->
                <div class="mb-6">
                    <h1 class="text-xl font-semibold text-gray-800">Informasi Akun</h1>
                    <p class="text-sm text-gray-400 mt-1">Lengkapi data di bawah untuk membuat akun admin baru.</p>
                </div>

                <!-- ALERT -->
                <div id="alertBox" class="hidden mb-5 px-4 py-3 rounded-xl text-sm items-start gap-2"></div>

                <!-- FORM -->
                <form id="addForm" class="space-y-5">
                    @csrf

                    <!-- NAMA LENGKAP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nama Lengkap
                            <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="firstInput"
                            placeholder="Masukkan nama lengkap admin"
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                            required>
                    </div>

                    <!-- EMAIL + PASSWORD -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Email
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="email"
                                name="email"
                                placeholder="contoh@email.com"
                                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">
                                Password
                                <span class="text-red-500">*</span>
                            </label>
                            <input
                                type="password"
                                name="password"
                                placeholder="Minimal 8 karakter"
                                class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                                required>
                            <small class="text-xs text-gray-400 mt-1 block">Minimal 8 karakter</small>
                        </div>

                    </div>

                    <!-- STATUS -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Status
                            <span class="text-red-500">*</span>
                        </label>
                        <select
                            name="archived"
                            required
                            class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            <option value="" disabled>-- Pilih Status --</option>
                            <option value="0" selected>Aktif</option>
                            <option value="1">Nonaktif</option>
                        </select>
                    </div>

                    <!-- DIVIDER -->
                    <hr class="border-gray-100">

                    <!-- BUTTONS -->
                    <div class="flex justify-end items-center gap-3 pt-1">

                        <a href="{{ route('admin.index') }}"
                           class="px-5 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                            Batal
                        </a>

                        <button
                            type="submit"
                            id="submitBtn"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
                            Simpan Admin
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
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('firstInput')?.focus();

    if (typeof window.createData === 'function') {

        window.createData(
            '#addForm',
            "{{ route('admin.store') }}",
            {
                onSuccess: function (data) {
                    const alertBox = document.getElementById('alertBox');
                    alertBox.className = 'mb-5 px-4 py-3 rounded-xl text-sm flex items-start gap-2 bg-green-50 text-green-700 border border-green-200';
                    alertBox.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                        <span>${data.message}</span>`;

                    document.getElementById('addForm').reset();

                    setTimeout(() => {
                        window.location.href = "{{ route('admin.index') }}";
                    }, 800);
                },

                onError: function (err) {
                    const alertBox = document.getElementById('alertBox');
                    alertBox.className = 'mb-5 px-4 py-3 rounded-xl text-sm flex items-start gap-2 bg-red-50 text-red-700 border border-red-200';
                    alertBox.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                        <span>${err.error || 'Terjadi kesalahan'}</span>`;
                }
            }
        );

    } else {
        console.error('ajax.js belum ke-load!');
    }

});
</script>
@endpush