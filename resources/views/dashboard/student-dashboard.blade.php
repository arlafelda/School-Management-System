@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@push('styles')
<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up   { animation: fadeUp .4s ease both; }
    .fade-up-1 { animation-delay: .05s; }
    .fade-up-2 { animation-delay: .12s; }
    .fade-up-3 { animation-delay: .19s; }
    .fade-up-4 { animation-delay: .26s; }
    .fade-up-5 { animation-delay: .33s; }

    .schedule-item:last-child .timeline-line { display: none; }

    .announcement-item { transition: background .15s ease; }
    .announcement-item:hover { background: #f8fafc; }
</style>
@endpush

@section('content')

@php
    $student   = auth()->user()->student;
    $className = optional(optional($student)->class)->name ?? '-';
    $greet     = match(true) {
        now()->hour < 11 => 'Selamat pagi',
        now()->hour < 15 => 'Selamat siang',
        now()->hour < 18 => 'Selamat sore',
        default          => 'Selamat malam',
    };
    $pct = min((float) $attendancePercent, 100);
@endphp

<!-- ============================================================
     HERO — personal greeting
     ============================================================ -->
<div class="fade-up fade-up-1 relative overflow-hidden bg-gradient-to-br from-teal-500 via-cyan-500 to-sky-500 rounded-3xl p-7 mb-6 shadow-lg text-white">

    <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute top-10 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>

    <p class="text-sm font-medium text-white/70 mb-1">{{ $greet }},</p>
    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">{{ auth()->user()->name }}</h1>

    <div class="flex flex-wrap items-center gap-3 mt-3">
        <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Kelas {{ $className }}
        </span>
        <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ $today }}
        </span>

        {{-- Tampilkan badge jika ada pengumuman mendesak --}}
        @if(isset($announcements) && $announcements->where('priority', 'mendesak')->count() > 0)
        <span class="inline-flex items-center gap-1.5 bg-red-500/40 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-full animate-pulse">
            🚨 {{ $announcements->where('priority', 'mendesak')->count() }} Pengumuman Mendesak
        </span>
        @endif
    </div>
</div>

<!-- ============================================================
     STAT ROW
     ============================================================ -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

    <!-- Gauge kehadiran -->
    <div class="fade-up fade-up-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col items-center">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Kehadiran</p>

        <div class="relative w-32 h-32">
            <canvas id="attendanceGauge"></canvas>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-2xl font-extrabold text-slate-800" id="gaugeLabel">0%</span>
                <span class="text-[10px] text-slate-400 mt-0.5">dari total</span>
            </div>
        </div>

        @php
            $color = $pct >= 80
                ? 'text-emerald-600 bg-emerald-50'
                : ($pct >= 60 ? 'text-amber-600 bg-amber-50' : 'text-red-500 bg-red-50');
            $label = $pct >= 80
                ? 'Baik'
                : ($pct >= 60 ? 'Cukup' : 'Perlu Perhatian');
        @endphp
        <span class="mt-4 text-xs font-semibold px-3 py-1 rounded-full {{ $color }}">{{ $label }}</span>
    </div>

    <!-- Total kehadiran -->
    <div class="fade-up fade-up-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Kehadiran</p>
            <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div>
            <p class="text-4xl font-extrabold text-slate-800 mt-4">{{ number_format($totalAttendance) }}</p>
            <p class="text-xs text-slate-400 mt-1">pertemuan tercatat</p>
        </div>
    </div>

    <!-- Jadwal hari ini -->
    <div class="fade-up fade-up-3 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jadwal Hari Ini</p>
            <div class="w-9 h-9 rounded-xl bg-sky-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div>
            <p class="text-4xl font-extrabold text-slate-800 mt-4">{{ $todaySchedules->count() }}</p>
            <p class="text-xs text-slate-400 mt-1">pelajaran hari ini</p>
        </div>
    </div>

</div>

<!-- ============================================================
     SECTION BAWAH — 2 kolom: Jadwal kiri | Pengumuman kanan
     (mobile: stack vertikal)
     ============================================================ -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    <!-- ===== JADWAL TIMELINE ===== -->
    <div class="fade-up fade-up-4 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-slate-700">Jadwal Hari Ini</h3>
                <p class="text-xs text-slate-400 mt-0.5">{{ $today }}</p>
            </div>
            <span class="text-xs font-semibold bg-teal-100 text-teal-700 px-3 py-1 rounded-full">
                {{ $todaySchedules->count() }} sesi
            </span>
        </div>

        @php
            $dotColors = [
                'bg-teal-400','bg-sky-400','bg-violet-400','bg-amber-400',
                'bg-pink-400','bg-emerald-400','bg-rose-400','bg-cyan-400',
            ];
        @endphp

        <div class="px-6 py-4">
            @forelse($todaySchedules as $i => $schedule)
            @php
                $dot = $dotColors[$i % count($dotColors)];
                $subjectName = optional($schedule->subject)->name
                    ?? $schedule->subject_name
                    ?? optional($schedule->teacher)->subject
                    ?? '-';
            @endphp

            <div class="schedule-item relative flex gap-4 pb-5">
                <!-- Timeline dot + line -->
                <div class="flex flex-col items-center flex-shrink-0 w-8">
                    <div class="w-3 h-3 rounded-full {{ $dot }} ring-4 ring-white shadow-sm mt-0.5 z-10"></div>
                    <div class="timeline-line flex-1 w-px bg-slate-100 mt-1"></div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0 pb-1">
                    <div class="flex items-start justify-between gap-2">
                        <p class="font-semibold text-slate-800 leading-snug">{{ $subjectName }}</p>
                        <span class="flex-shrink-0 text-xs font-semibold bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full">
                            {{ $schedule->day }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                        <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                            – {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ optional($schedule->teacher)->name ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>

            @empty
            <div class="flex flex-col items-center justify-center py-14 text-slate-400">
                <svg class="w-14 h-14 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="font-medium text-sm">Tidak ada jadwal hari ini</p>
                <p class="text-xs mt-1 text-slate-300">Nikmati harimu! 🎉</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- ===== WIDGET PENGUMUMAN ===== -->
    <div class="fade-up fade-up-5 bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">

        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-slate-700">Pengumuman</h3>
                <p class="text-xs text-slate-400 mt-0.5">Informasi terbaru dari sekolah</p>
            </div>
            <a href="{{ route('announcement.board') }}"
               class="text-xs font-semibold text-violet-600 hover:text-violet-800 transition-colors whitespace-nowrap">
                Lihat Semua →
            </a>
        </div>

        <!-- List -->
        <div class="flex-1 divide-y divide-slate-50">
            @forelse(isset($announcements) ? $announcements : [] as $ann)

            {{-- Konfigurasi warna per prioritas --}}
            @php
                $priorityConfig = match($ann->priority) {
                    'mendesak' => [
                        'icon'       => '🚨',
                        'badgeBg'    => 'bg-red-100',
                        'badgeText'  => 'text-red-700',
                        'borderLeft' => 'border-l-4 border-red-400',
                        'label'      => 'Mendesak',
                        'btnText'    => 'text-red-600 hover:text-red-800',
                    ],
                    'penting' => [
                        'icon'       => '⚠️',
                        'badgeBg'    => 'bg-amber-100',
                        'badgeText'  => 'text-amber-700',
                        'borderLeft' => 'border-l-4 border-amber-400',
                        'label'      => 'Penting',
                        'btnText'    => 'text-amber-600 hover:text-amber-800',
                    ],
                    default => [
                        'icon'       => '📣',
                        'badgeBg'    => 'bg-slate-100',
                        'badgeText'  => 'text-slate-600',
                        'borderLeft' => 'border-l-4 border-slate-300',
                        'label'      => 'Normal',
                        'btnText'    => 'text-slate-500 hover:text-slate-800',
                    ],
                };
            @endphp

            <div class="announcement-item {{ $priorityConfig['borderLeft'] }} px-5 py-4 rounded-r-lg">
                <div class="flex items-start gap-3">

                    <!-- Icon -->
                    <span class="text-base flex-shrink-0 mt-0.5">{{ $priorityConfig['icon'] }}</span>

                    <!-- Body -->
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $priorityConfig['badgeBg'] }} {{ $priorityConfig['badgeText'] }}">
                                {{ $priorityConfig['label'] }}
                            </span>
                            @if($ann->expired_at)
                            <span class="text-xs text-slate-400">
                                Berakhir {{ $ann->expired_at->format('d M Y') }}
                            </span>
                            @endif
                        </div>

                        <p class="font-semibold text-slate-800 text-sm leading-snug line-clamp-1">
                            {{ $ann->title }}
                        </p>
                        <p class="text-xs text-slate-400 mt-1 line-clamp-2 leading-relaxed">
                            {{ Str::limit(strip_tags($ann->content), 120) }}
                        </p>

                        <div class="flex items-center justify-between mt-2">
                            <span class="text-xs text-slate-400">
                                {{ optional($ann->published_at ?? $ann->created_at)->diffForHumans() }}
                            </span>
                            <button onclick="openModal('modal-ann-{{ $ann->id }}')"
                                class="text-xs font-semibold {{ $priorityConfig['btnText'] }} transition-colors">
                                Selengkapnya →
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal untuk item ini --}}
            <div id="modal-ann-{{ $ann->id }}"
                 class="hidden fixed inset-0 z-50 items-center justify-center bg-black/40 backdrop-blur-sm p-4"
                 data-modal-backdrop="modal-ann-{{ $ann->id }}">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[80vh] flex flex-col overflow-hidden">

                    {{-- Modal header --}}
                    <div class="flex items-start justify-between gap-4 px-6 py-5 border-b border-slate-100">
                        <div class="flex items-center gap-2">
                            <span>{{ $priorityConfig['icon'] }}</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full
                                {{ $priorityConfig['badgeBg'] }} {{ $priorityConfig['badgeText'] }}">
                                {{ $priorityConfig['label'] }}
                            </span>
                        </div>
                        <button onclick="closeModal('modal-ann-{{ $ann->id }}')"
                            class="text-slate-400 hover:text-slate-600 transition-colors flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Modal body --}}
                    <div class="px-6 py-5 overflow-y-auto">
                        <h2 class="text-lg font-bold text-slate-800 leading-snug mb-3">{{ $ann->title }}</h2>
                        <div class="flex items-center gap-2 text-xs text-slate-400 mb-4">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ optional($ann->author)->name ?? 'Admin' }}
                            <span class="text-slate-300">·</span>
                            {{ optional($ann->published_at ?? $ann->created_at)->diffForHumans() }}
                            @if($ann->expired_at)
                            <span class="text-slate-300">·</span>
                            Berakhir {{ $ann->expired_at->format('d M Y') }}
                            @endif
                        </div>
                        <div class="prose prose-sm max-w-none text-slate-600 leading-relaxed">
                            {!! nl2br(e(strip_tags($ann->content))) !!}
                        </div>
                    </div>
                </div>
            </div>

            @empty
            <div class="flex flex-col items-center justify-center py-14 text-slate-400">
                <span class="text-4xl mb-3">📭</span>
                <p class="font-medium text-sm text-slate-500">Belum ada pengumuman</p>
                <p class="text-xs mt-1 text-slate-400">Pantau terus halaman ini ya!</p>
            </div>
            @endforelse
        </div>

    </div>{{-- end widget pengumuman --}}

</div>{{-- end grid 2 kolom --}}

{{-- Data gauge --}}
<div id="gauge-data" class="hidden" data-percent="{{ $pct }}"></div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // ── Gauge kehadiran ──────────────────────────────────────────
    const gaugePct   = parseFloat(document.getElementById('gauge-data').dataset.percent) || 0;
    const gaugeColor = gaugePct >= 80 ? '#10b981' : gaugePct >= 60 ? '#f59e0b' : '#ef4444';

    const gaugeChart = new Chart(document.getElementById('attendanceGauge'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [0, 100],
                backgroundColor: [gaugeColor, '#f1f5f9'],
                borderWidth: 0,
                borderRadius: 6,
            }]
        },
        options: {
            cutout: '78%',
            rotation: -90,
            circumference: 180,
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            animation: { duration: 0 }
        }
    });

    let start = null;
    const duration = 900;
    const label = document.getElementById('gaugeLabel');

    function animateGauge(ts) {
        if (!start) start = ts;
        const progress = Math.min((ts - start) / duration, 1);
        const eased    = 1 - Math.pow(1 - progress, 3);
        const current  = gaugePct * eased;

        gaugeChart.data.datasets[0].data = [current, 100 - current];
        gaugeChart.update('none');
        label.textContent = current.toFixed(1) + '%';

        if (progress < 1) requestAnimationFrame(animateGauge);
    }

    requestAnimationFrame(animateGauge);

    // ── Modal helpers ────────────────────────────────────────────
    function openModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Tutup saat klik backdrop
    document.addEventListener('click', function (e) {
        const backdrop = e.target.closest('[data-modal-backdrop]');
        if (backdrop && e.target === backdrop) {
            closeModal(backdrop.dataset.modalBackdrop);
        }
    });

    // Tutup saat tekan Escape
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        document.querySelectorAll('[data-modal-backdrop]').forEach(el => {
            if (!el.classList.contains('hidden')) closeModal(el.dataset.modalBackdrop);
        });
    });
</script>
@endpush