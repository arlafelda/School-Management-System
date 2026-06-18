@extends('layouts.app')

@section('title', 'Profile Admin')

@section('content')

<div class="min-h-screen bg-gray-100 p-4 md:p-8">

    <div class="max-w-3xl mx-auto">

        {{-- BREADCRUMB --}}
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard.super_admin') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <a href="{{ route('admin.index') }}" class="hover:text-indigo-600 transition">Kelola Admin</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <span class="text-gray-700 font-medium">{{ $admin->name }}</span>
        </div>

        {{-- CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">

            {{-- HEADER ROW: avatar + info + buttons --}}
            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">

                <div class="flex items-center gap-4">
                    {{-- AVATAR --}}
                    <div class="w-16 h-16 rounded-2xl bg-indigo-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-2xl">
                            {{ strtoupper(substr($admin->name, 0, 1)) }}
                        </span>
                    </div>

                    {{-- NAME + ROLE + EMAIL --}}
                    <div>
                        <div class="flex items-center gap-2 mb-0.5">
                            <h1 class="text-lg font-semibold text-gray-800">{{ $admin->name }}</h1>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ ucfirst($admin->role) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-400">{{ $admin->email }}</p>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('admin.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm text-gray-600 border border-gray-200 rounded-xl bg-white hover:bg-gray-50 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        Kembali
                    </a>

                    <a href="{{ route('admin.edit', $admin->slug) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        Edit Profile
                    </a>
                </div>

            </div>

            {{-- DIVIDER --}}
            <hr class="border-gray-100 mb-6">

            {{-- DETAIL GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">ID Admin</p>
                        <p class="text-sm font-semibold text-gray-700 font-mono">
                            #{{ str_pad($admin->id, 3, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Status</p>
                        @if($admin->archived == 0)
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-green-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-400">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 inline-block"></span>
                                Nonaktif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Tanggal Dibuat</p>
                        <p class="text-sm font-semibold text-gray-700">
                            {{ \Carbon\Carbon::parse($admin->created_at)->locale('id')->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50 border border-gray-100">
                    <div class="w-8 h-8 rounded-lg bg-white border border-gray-200 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Terakhir Update</p>
                        <p class="text-sm font-semibold text-gray-700">
                            {{ \Carbon\Carbon::parse($admin->updated_at)->locale('id')->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection