@extends('layouts.app')

@section('title', 'Papan Pengumuman')

@push('styles')
<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up   { animation: fadeUp .4s ease both; }
    .fade-up-1 { animation-delay: .05s; }
    .fade-up-2 { animation-delay: .10s; }
    .fade-up-3 { animation-delay: .15s; }

    .announcement-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .announcement-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px -5px rgba(0,0,0,0.08), 0 4px 10px -3px rgba(0,0,0,0.04);
    }

    .priority-mendesak { border-left: 4px solid #ef4444; }
    .priority-penting  { border-left: 4px solid #f59e0b; }
    .priority-normal   { border-left: 4px solid #64748b; }
</style>
@endpush

@section('content')

@php
    $mendesak = $announcements->where('priority', 'mendesak');
    $penting  = $announcements->where('priority', 'penting');
    $normal   = $announcements->where('priority', 'normal');
@endphp

<!-- HEADER -->
<div class="fade-up fade-up-1 relative overflow-hidden bg-gradient-to-br from-violet-500 via-purple-500 to-indigo-500 rounded-3xl p-7 mb-6 shadow-lg text-white">
    <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute top-10 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>

    <div class="flex items-start justify-between relative">
        <div>
            <p class="text-sm font-medium text-white/70 mb-1">Informasi Sekolah</p>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Papan Pengumuman</h1>
            <p class="text-sm text-white/70 mt-2">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div class="hidden md:flex flex-col items-end gap-2">
            <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                📢 {{ $announcements->count() }} Pengumuman Aktif
            </span>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 mt-4">
        @if($mendesak->count() > 0)
        <span class="inline-flex items-center gap-1.5 bg-red-500/30 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-full">
            🚨 {{ $mendesak->count() }} Mendesak
        </span>
        @endif
        @if($penting->count() > 0)
        <span class="inline-flex items-center gap-1.5 bg-amber-500/30 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-full">
            ⚠️ {{ $penting->count() }} Penting
        </span>
        @endif
        <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-full">
            📣 {{ $normal->count() }} Normal
        </span>
    </div>
</div>

<!-- KOSONG -->
@if($announcements->isEmpty())
<div class="fade-up fade-up-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-16 flex flex-col items-center justify-center text-slate-400">
    <div class="text-6xl mb-4">📭</div>
    <p class="font-semibold text-lg text-slate-500">Belum ada pengumuman</p>
    <p class="text-sm mt-1 text-slate-400">Pantau terus halaman ini untuk informasi terbaru dari sekolah.</p>
</div>

@else

<!-- MENDESAK -->
@if($mendesak->count() > 0)
<div class="fade-up fade-up-2 mb-6">
    <div class="flex items-center gap-2 mb-3">
        <span class="text-lg">🚨</span>
        <h2 class="text-sm font-bold text-red-600 uppercase tracking-wider">Mendesak</h2>
        <div class="flex-1 h-px bg-red-100"></div>
    </div>
    <div class="space-y-3">
        @foreach($mendesak as $ann)
        <div class="announcement-card priority-mendesak bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-red-100 text-red-700 border border-red-200">
                                🚨 Mendesak
                            </span>
                            @if($ann->expired_at)
                            <span class="text-xs text-slate-400">
                                Berakhir: {{ $ann->expired_at->format('d M Y') }}
                            </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-slate-800 text-base leading-snug">{{ $ann->title }}</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed line-clamp-3">
                            {{ Str::limit(strip_tags($ann->content), 200) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ optional($ann->author)->name ?? 'Admin' }}
                        <span class="text-slate-300">·</span>
                        {{ optional($ann->published_at ?? $ann->created_at)?->diffForHumans() ?? '-' }}
                    </div>
                    <button onclick="openModal('modal-{{ $ann->id }}')"
                        class="text-xs font-semibold text-red-600 hover:text-red-800 transition-colors">
                        Baca Selengkapnya →
                    </button>
                </div>
            </div>
        </div>
        @include('announcement._modal', ['ann' => $ann])
        @endforeach
    </div>
</div>
@endif

<!-- PENTING -->
@if($penting->count() > 0)
<div class="fade-up fade-up-2 mb-6">
    <div class="flex items-center gap-2 mb-3">
        <span class="text-lg">⚠️</span>
        <h2 class="text-sm font-bold text-amber-600 uppercase tracking-wider">Penting</h2>
        <div class="flex-1 h-px bg-amber-100"></div>
    </div>
    <div class="space-y-3">
        @foreach($penting as $ann)
        <div class="announcement-card priority-penting bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                                ⚠️ Penting
                            </span>
                            @if($ann->expired_at)
                            <span class="text-xs text-slate-400">
                                Berakhir: {{ $ann->expired_at->format('d M Y') }}
                            </span>
                            @endif
                        </div>
                        <h3 class="font-bold text-slate-800 text-base leading-snug">{{ $ann->title }}</h3>
                        <p class="text-sm text-slate-500 mt-2 leading-relaxed line-clamp-3">
                            {{ Str::limit(strip_tags($ann->content), 200) }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100">
                    <div class="flex items-center gap-1.5 text-xs text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ optional($ann->author)->name ?? 'Admin' }}
                        <span class="text-slate-300">·</span>
                        {{ optional($ann->published_at ?? $ann->created_at)?->diffForHumans() ?? '-' }}
                    </div>
                    <button onclick="openModal('modal-{{ $ann->id }}')"
                        class="text-xs font-semibold text-amber-600 hover:text-amber-800 transition-colors">
                        Baca Selengkapnya →
                    </button>
                </div>
            </div>
        </div>
        @include('announcement._modal', ['ann' => $ann])
        @endforeach
    </div>
</div>
@endif

<!-- NORMAL -->
@if($normal->count() > 0)
<div class="fade-up fade-up-3 mb-6">
    <div class="flex items-center gap-2 mb-3">
        <span class="text-lg">📢</span>
        <h2 class="text-sm font-bold text-slate-500 uppercase tracking-wider">Pengumuman</h2>
        <div class="flex-1 h-px bg-slate-100"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        @foreach($normal as $ann)
        <div class="announcement-card priority-normal bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200">
                        📢 Normal
                    </span>
                    @if($ann->expired_at)
                    <span class="text-xs text-slate-400">
                        Berakhir: {{ $ann->expired_at->format('d M Y') }}
                    </span>
                    @endif
                </div>
                <h3 class="font-bold text-slate-800 text-sm leading-snug">{{ $ann->title }}</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed line-clamp-2">
                    {{ Str::limit(strip_tags($ann->content), 150) }}
                </p>

                <div class="flex items-center justify-between mt-4 pt-3 border-t border-slate-100">
                    <span class="text-xs text-slate-400">
                        {{ optional($ann->published_at ?? $ann->created_at)?->diffForHumans() ?? '-' }}
                    </span>
                    <button onclick="openModal('modal-{{ $ann->id }}')"
                        class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                        Selengkapnya →
                    </button>
                </div>
            </div>
        </div>
        @include('announcement._modal', ['ann' => $ann])
        @endforeach
    </div>
</div>
@endif

@endif

@endsection

@push('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    // Tutup saat klik backdrop (bukan konten modal)
    document.addEventListener('click', function (e) {
        const backdrop = e.target.closest('[data-modal-backdrop]');
        if (backdrop && e.target === backdrop) {
            closeModal(backdrop.dataset.modalBackdrop);
        }
    });

    // Tutup saat tekan Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('[data-modal-backdrop]').forEach(el => {
                if (!el.classList.contains('hidden')) {
                    closeModal(el.dataset.modalBackdrop);
                }
            });
        }
    });
</script>
@endpush