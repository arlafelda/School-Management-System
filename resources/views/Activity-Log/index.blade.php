@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')

@php
    $user = auth()->user();
@endphp

<div class="min-h-screen bg-gray-100 p-4 md:p-8">
    <div class="max-w-7xl mx-auto">

        {{-- BREADCRUMB --}}
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <span class="text-gray-700 font-medium">Log Aktivitas</span>
        </div>

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-800">Log Aktivitas (Audit Trail)</h1>
                <p class="text-sm text-gray-400 mt-0.5">Rekam jejak seluruh perubahan data dalam sistem</p>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Total Log</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($stats['total']) }}</h3>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Hari Ini</p>
                <h3 class="text-3xl font-bold text-indigo-600 mt-2">{{ number_format($stats['today']) }}</h3>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Minggu Ini</p>
                <h3 class="text-3xl font-bold text-blue-600 mt-2">{{ number_format($stats['this_week']) }}</h3>
            </div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5">
                <p class="text-xs text-gray-500 uppercase tracking-wide">Bulan Ini</p>
                <h3 class="text-3xl font-bold text-green-600 mt-2">{{ number_format($stats['this_month']) }}</h3>
            </div>
        </div>

        {{-- FILTER PANEL --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 mb-6">
            <form method="GET" action="{{ route('activity-log.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">

                {{-- Search --}}
                <div class="col-span-1 sm:col-span-2 md:col-span-2 lg:col-span-1">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Nama user, deskripsi, target..."
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

                {{-- Modul --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Modul</label>
                    <select name="module" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Semua Modul</option>
                        @foreach($modules as $mod)
                            <option value="{{ $mod }}" {{ request('module') == $mod ? 'selected' : '' }}>{{ $mod }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Aksi --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Aksi</label>
                    <select name="action" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Semua Aksi</option>
                        @foreach([
                            'create'       => 'Tambah',
                            'update'       => 'Ubah',
                            'delete'       => 'Hapus',
                            'restore'      => 'Pulihkan',
                            'force_delete' => 'Hapus Permanen',
                            'login'        => 'Login',
                            'logout'       => 'Logout',
                        ] as $val => $label)
                            <option value="{{ $val }}" {{ request('action') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Role</label>
                    <select name="role" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Semua Role</option>
                        @foreach(['super_admin','admin','teacher','student'] as $r)
                            <option value="{{ $r }}" {{ request('role') == $r ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$r)) }}</option>
                        @endforeach
                    </select>
                </div>

                @if($user->role === 'super_admin')
                {{-- User --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">User</label>
                    <select name="user_id" class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <option value="">Semua User</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->role }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Tanggal Dari --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

                {{-- Tanggal Sampai --}}
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full text-sm border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                </div>

                {{-- Buttons --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        Filter
                    </button>
                    <a href="{{ route('activity-log.index') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-xl hover:bg-gray-200 transition">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Menampilkan <span class="font-semibold text-gray-700">{{ $logs->firstItem() }}–{{ $logs->lastItem() }}</span>
                    dari <span class="font-semibold text-gray-700">{{ $logs->total() }}</span> log
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                        <tr>
                            <th class="px-4 py-3 text-left">Waktu</th>
                            <th class="px-4 py-3 text-left">User</th>
                            <th class="px-4 py-3 text-left">Aksi</th>
                            <th class="px-4 py-3 text-left">Modul</th>
                            <th class="px-4 py-3 text-left">Deskripsi</th>
                            <th class="px-4 py-3 text-center">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 transition">

                            {{-- Waktu (sudah disesuaikan ke WIB / Asia Jakarta) --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-xs text-gray-800 font-medium">
                                    {{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $log->created_at->timezone('Asia/Jakarta')->format('H:i:s') }} WIB
                                </div>
                            </td>

                            {{-- User --}}
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-800 text-xs">{{ $log->user_name ?? '-' }}</div>
                                <div class="text-xs text-gray-400 capitalize">{{ str_replace('_', ' ', $log->user_role ?? '-') }}</div>
                            </td>

                            {{-- Aksi Badge --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $log->action_badge_color }}">
                                    {{ $log->action_label }}
                                </span>
                            </td>

                            {{-- Modul --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="text-xs text-gray-600 bg-gray-100 px-2 py-0.5 rounded-md">{{ $log->module }}</span>
                            </td>

                            {{-- Deskripsi --}}
                            <td class="px-4 py-3 max-w-xs">
                                <p class="text-xs text-gray-700 line-clamp-2">{{ $log->description }}</p>
                                @if($log->target_label)
                                    <p class="text-xs text-gray-400 mt-0.5">Target: <span class="text-gray-600">{{ $log->target_label }}</span></p>
                                @endif
                            </td>

                            {{-- Detail Link --}}
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('activity-log.show', $log->id) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition"
                                    title="Lihat Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </a>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3 text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Tidak ada log aktivitas ditemukan</p>
                                    <p class="text-xs text-gray-400">Coba ubah filter pencarian</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $logs->links() }}
            </div>
            @endif

        </div>

    </div>
</div>

@endsection