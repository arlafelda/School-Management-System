@extends('layouts.app')

@section('title', 'Edit Admin')

@section('content')

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4 md:p-8">

    <div class="w-full max-w-2xl">

        {{-- BREADCRUMB --}}
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard.super_admin') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <a href="{{ route('admin.index') }}" class="hover:text-indigo-600 transition">Kelola Admin</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <a href="{{ route('admin.show', $admin->slug) }}" class="hover:text-indigo-600 transition">{{ $admin->name }}</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <span class="text-gray-700 font-medium">Edit</span>
        </div>

        {{-- TITLE --}}
        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-800">Edit Admin</h1>
            <p class="text-sm text-gray-400 mt-0.5">Perbarui data akun admin.</p>
        </div>

        {{-- ALERT --}}
        <div id="alertBox" class="hidden mb-5 px-4 py-3 rounded-xl text-sm items-start gap-2"></div>

        {{-- CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            {{-- ADMIN IDENTITY --}}
            <div class="flex items-center gap-4 mb-7 pb-6 border-b border-gray-100">
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-indigo-600 font-semibold text-lg">
                        {{ strtoupper(substr($admin->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <p class="font-medium text-gray-800">{{ $admin->name }}</p>
                    <p class="text-sm text-gray-400">{{ $admin->email }}</p>
                </div>
            </div>

            <form id="editForm"
                  method="POST"
                  action="{{ route('admin.update', $admin->slug) }}"
                  class="space-y-5">

                @csrf
                @method('PUT')

                {{-- NAMA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           id="firstInput"
                           name="name"
                           value="{{ $admin->name }}"
                           required
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email"
                           name="email"
                           value="{{ $admin->email }}"
                           required
                           class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                </div>

                {{-- PASSWORD ROW --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password Baru
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <input type="password"
                               name="password"
                               placeholder="Kosongkan jika tidak diubah"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Konfirmasi Password
                        </label>
                        <input type="password"
                               name="password_confirmation"
                               placeholder="Ulangi password baru"
                               class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                    </div>

                </div>

                <hr class="border-gray-100">

                {{-- BUTTONS --}}
                <div class="flex justify-end items-center gap-3 pt-1">

                    <a href="{{ route('admin.index') }}"
                       class="px-5 py-2.5 text-sm text-gray-500 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                        Batal
                    </a>

                    <button type="submit"
                            id="submitBtn"
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('firstInput')?.focus();

    if (typeof window.updateData === 'function') {

        window.updateData(
            '#editForm',
            "{{ route('admin.update', $admin->slug) }}",
            {
                onSuccess: function (res) {
                    const alertBox = document.getElementById('alertBox');
                    alertBox.className = 'mb-5 px-4 py-3 rounded-xl text-sm flex items-start gap-2 bg-green-50 text-green-700 border border-green-200';
                    alertBox.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/></svg>
                        <span>${res.message}</span>`;

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
        console.error('updateData belum tersedia');
    }

});
</script>
@endpush