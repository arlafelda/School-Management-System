@extends('layouts.app')

@section('title', 'Arsip Admin')

@section('content')

<div class="min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="max-w-6xl mx-auto">

        {{-- BREADCRUMB --}}
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard.super_admin') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <a href="{{ route('admin.index') }}" class="hover:text-indigo-600 transition">Kelola Admin</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <span class="text-gray-700 font-medium">Arsip</span>
        </div>

        {{-- ALERT --}}
        <div id="alertBox" class="hidden mb-4 px-4 py-3 rounded-xl text-sm items-start gap-2"></div>

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Arsip Admin</h1>
                <p class="text-sm text-gray-400 mt-0.5">Admin yang telah dinonaktifkan dan dipindahkan ke arsip.</p>
            </div>

            <a href="{{ route('admin.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>

        {{-- TABLE CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full text-sm min-w-[680px]">

                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/70">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50">

                        @forelse($admins as $user)

                        <tr id="row-{{ $user->id }}" class="hover:bg-gray-50/50 transition group">

                            {{-- USER --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center flex-shrink-0">
                                        <span class="text-gray-400 font-semibold text-sm">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-500">{{ $user->name }}</p>
                                        <p class="text-gray-400 text-xs mt-0.5">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- ROLE --}}
                            <td class="px-6 py-4 text-gray-500">
                                {{ ucfirst($user->role) }}
                            </td>

                            {{-- ID --}}
                            <td class="px-6 py-4">
                                <span class="text-gray-400 font-mono text-xs">#{{ $user->id }}</span>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 bg-red-50 text-red-600 border border-red-100 px-2.5 py-1 rounded-full text-xs font-medium">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400 inline-block"></span>
                                    Diarsipkan
                                </span>
                            </td>

                            {{-- AKSI --}}
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    <button
                                        class="btn-restore inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-green-700 border border-green-200 rounded-lg bg-green-50 hover:bg-green-100 transition"
                                        data-id="{{ $user->id }}"
                                        data-url="{{ route('admin.restore', $user->slug) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                                        Restore
                                    </button>

                                    <button
                                        class="btn-delete inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg bg-red-50 hover:bg-red-100 transition"
                                        data-id="{{ $user->id }}"
                                        data-url="{{ route('admin.forceDelete', $user->slug) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                        Hapus Permanen
                                    </button>

                                </div>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="5" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 8v13H3V8"/><path d="M1 3h22v5H1z"/><path d="M10 12h4"/></svg>
                                    <p class="text-sm font-medium text-gray-500">Tidak ada data di arsip</p>
                                    <a href="{{ route('admin.index') }}" class="text-xs text-indigo-600 hover:underline">← Kembali ke daftar admin</a>
                                </div>
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // =====================
    // RESTORE ADMIN
    // =====================
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-restore');
        if (!btn) return;

        const id  = btn.dataset.id;
        const url = btn.dataset.url;

        if (typeof restoreData === 'undefined') {
            console.error('restoreData belum tersedia');
            return;
        }

        restoreData(
            url,
            'Restore data ini?',
            {
                onSuccess: function () {
                    document.getElementById('row-' + id)?.remove();
                }
            }
        );
    });

    // =====================
    // DELETE PERMANEN
    // =====================
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.btn-delete');
        if (!btn) return;

        const id  = btn.dataset.id;
        const url = btn.dataset.url;

        if (typeof deleteData === 'undefined') {
            console.error('deleteData belum tersedia');
            return;
        }

        deleteData(
            url,
            'Hapus permanen data ini? Data tidak bisa dikembalikan!',
            {
                onSuccess: function () {
                    document.getElementById('row-' + id)?.remove();
                }
            }
        );
    });

});
</script>
@endpush