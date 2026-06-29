@extends('layouts.app')

@section('title', 'Detail Log Aktivitas')

@section('content')

<div class="min-h-screen bg-gray-100 p-4 md:p-8">
    <div class="max-w-4xl mx-auto">

        {{-- BREADCRUMB --}}
        <div class="mb-4 text-sm text-gray-500 flex items-center gap-1">
            <a href="{{ route('dashboard') }}" class="hover:text-indigo-600 transition">Dashboard</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <a href="{{ route('activity-log.index') }}" class="hover:text-indigo-600 transition">Log Aktivitas</a>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
            <span class="text-gray-700 font-medium">Detail #{{ $activityLog->id }}</span>
        </div>

        {{-- BACK BUTTON --}}
        <div class="mb-6">
            <a href="{{ route('activity-log.index') }}"
                class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-indigo-600 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Kembali ke Daftar Log
            </a>
        </div>

        {{-- MAIN CARD --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-100 flex items-start justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $activityLog->action_badge_color }}">
                            {{ $activityLog->action_label }}
                        </span>
                        <span class="text-sm text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md">{{ $activityLog->module }}</span>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800 mt-2">{{ $activityLog->description }}</h2>
                    @if($activityLog->target_label)
                        <p class="text-sm text-gray-500 mt-0.5">Target: <span class="font-medium text-gray-700">{{ $activityLog->target_label }}</span></p>
                    @endif
                </div>
                <div class="text-right shrink-0">
                    <div class="text-sm font-medium text-gray-700">{{ $activityLog->created_at->format('d M Y') }}</div>
                    <div class="text-sm text-gray-400">{{ $activityLog->created_at->format('H:i:s') }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ $activityLog->created_at->diffForHumans() }}</div>
                </div>
            </div>

            {{-- Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-0 divide-y md:divide-y-0 md:divide-x divide-gray-100">

                {{-- Pelaku --}}
                <div class="px-6 py-5">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Informasi Pelaku</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Nama User</dt>
                            <dd class="font-medium text-gray-800">{{ $activityLog->user_name ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Role</dt>
                            <dd class="font-medium text-gray-800 capitalize">{{ str_replace('_', ' ', $activityLog->user_role ?? '-') }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">User ID</dt>
                            <dd class="font-medium text-gray-800">{{ $activityLog->user_id ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Teknis --}}
                <div class="px-6 py-5">
                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Informasi Teknis</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">IP Address</dt>
                            <dd class="font-mono text-xs text-gray-800">{{ $activityLog->ip_address ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Log ID</dt>
                            <dd class="font-mono text-xs text-gray-800">#{{ $activityLog->id }}</dd>
                        </div>
                        <div class="flex justify-between text-sm">
                            <dt class="text-gray-500">Browser</dt>
                            <dd class="text-xs text-gray-600 text-right max-w-[200px] truncate" title="{{ $activityLog->user_agent }}">
                                {{ $activityLog->user_agent ? Str::limit($activityLog->user_agent, 40) : '-' }}
                            </dd>
                        </div>
                    </dl>
                </div>

            </div>
        </div>

        {{-- DATA PERUBAHAN --}}
        @if($activityLog->old_data || $activityLog->new_data)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Data Lama --}}
            @if($activityLog->old_data)
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-red-100 bg-red-50">
                    <h3 class="text-sm font-semibold text-red-700 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5"/><path d="M12 5l-7 7 7 7"/>
                        </svg>
                        Data Sebelum
                    </h3>
                </div>
                <div class="p-5">
                    <dl class="space-y-2">
                        @foreach($activityLog->old_data as $key => $value)
                        <div class="flex justify-between text-sm py-1.5 border-b border-gray-50 last:border-0">
                            <dt class="text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}</dt>
                            <dd class="font-medium text-red-700 text-right max-w-[200px] break-words">
                                {{ is_null($value) ? '<null>' : (is_array($value) ? json_encode($value) : $value) }}
                            </dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
            </div>
            @endif

            {{-- Data Baru --}}
            @if($activityLog->new_data)
            <div class="bg-white rounded-2xl shadow-sm border border-green-100 overflow-hidden">
                <div class="px-5 py-4 border-b border-green-100 bg-green-50">
                    <h3 class="text-sm font-semibold text-green-700 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14"/><path d="M12 5l7 7-7 7"/>
                        </svg>
                        Data Sesudah
                    </h3>
                </div>
                <div class="p-5">
                    <dl class="space-y-2">
                        @foreach($activityLog->new_data as $key => $value)
                        @php
                            $changed = $activityLog->old_data && array_key_exists($key, $activityLog->old_data)
                                && $activityLog->old_data[$key] != $value;
                        @endphp
                        <div class="flex justify-between text-sm py-1.5 border-b border-gray-50 last:border-0">
                            <dt class="text-gray-500 capitalize">{{ str_replace('_', ' ', $key) }}</dt>
                            <dd class="font-medium text-right max-w-[200px] break-words {{ $changed ? 'text-green-700 font-semibold' : 'text-gray-800' }}">
                                {{ is_null($value) ? '<null>' : (is_array($value) ? json_encode($value) : $value) }}
                                @if($changed)
                                    <span class="ml-1 text-[10px] text-green-500 font-normal">(diubah)</span>
                                @endif
                            </dd>
                        </div>
                        @endforeach
                    </dl>
                </div>
            </div>
            @endif

        </div>
        @endif

    </div>
</div>

@endsection
