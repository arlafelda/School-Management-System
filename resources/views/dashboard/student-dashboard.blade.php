@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@push('styles')
<style>
    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp .4s ease both; }
    .fade-up-1 { animation-delay: .05s; }
    .fade-up-2 { animation-delay: .12s; }
    .fade-up-3 { animation-delay: .19s; }
    .fade-up-4 { animation-delay: .26s; }

    .schedule-item:last-child .timeline-line { display: none; }
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

<!-- HERO — personal greeting, teal accent -->
<div class="fade-up fade-up-1 relative overflow-hidden bg-gradient-to-br from-teal-500 via-cyan-500 to-sky-500 rounded-3xl p-7 mb-6 shadow-lg text-white">

    {{-- decorative circles --}}
    <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/10 rounded-full"></div>
    <div class="absolute top-10 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>

    <p class="text-sm font-medium text-white/70 mb-1">{{ $greet }},</p>
    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">{{ auth()->user()->name }}</h1>
    <div class="flex flex-wrap items-center gap-3 mt-3">
        <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Kelas {{ $className }}
        </span>
        <span class="inline-flex items-center gap-1.5 bg-white/20 backdrop-blur text-white text-xs font-semibold px-3 py-1.5 rounded-full">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            {{ $today }}
        </span>
    </div>
</div>

<!-- STAT ROW -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">

    <!-- Gauge kehadiran — signature element -->
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
            $color = $pct >= 80 ? 'text-emerald-600 bg-emerald-50' : ($pct >= 60 ? 'text-amber-600 bg-amber-50' : 'text-red-500 bg-red-50');
            $label = $pct >= 80 ? 'Baik' : ($pct >= 60 ? 'Cukup' : 'Perlu Perhatian');
        @endphp
        <span class="mt-4 text-xs font-semibold px-3 py-1 rounded-full {{ $color }}">{{ $label }}</span>
    </div>

    <!-- Total kehadiran -->
    <div class="fade-up fade-up-2 bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col justify-between">
        <div class="flex items-center justify-between">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Kehadiran</p>
            <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div>
            <p class="text-4xl font-extrabold text-slate-800 mt-4">{{ $todaySchedules->count() }}</p>
            <p class="text-xs text-slate-400 mt-1">pelajaran hari ini</p>
        </div>
    </div>

</div>

<!-- JADWAL TIMELINE -->
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
        $dotColors = ['bg-teal-400','bg-sky-400','bg-violet-400','bg-amber-400','bg-pink-400','bg-emerald-400','bg-rose-400','bg-cyan-400'];
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
            <!-- Timeline vertical line + dot -->
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs text-slate-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ optional($schedule->teacher)->name ?? '-' }}
                    </span>
                </div>
            </div>
        </div>
        @empty

        <div class="flex flex-col items-center justify-center py-14 text-slate-400">
            <svg class="w-14 h-14 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="font-medium text-sm">Tidak ada jadwal hari ini</p>
            <p class="text-xs mt-1 text-slate-300">Nikmati harimu! 🎉</p>
        </div>

        @endforelse
    </div>

</div>

{{-- Data gauge disimpan di HTML agar tidak konflik VS Code --}}
<div id="gauge-data" class="hidden" data-percent="{{ $pct }}"></div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const gaugePct = parseFloat(document.getElementById('gauge-data').dataset.percent) || 0;

    // Warna berdasarkan persentase
    const gaugeColor = gaugePct >= 80 ? '#10b981' : gaugePct >= 60 ? '#f59e0b' : '#ef4444';

    const gaugeChart = new Chart(document.getElementById('attendanceGauge'), {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [0, 100],           // animasi dari 0
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

    // Animasi gauge & label counter
    let start = null;
    const duration = 900;
    const label = document.getElementById('gaugeLabel');

    function animateGauge(ts) {
        if (!start) start = ts;
        const progress = Math.min((ts - start) / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = gaugePct * eased;

        gaugeChart.data.datasets[0].data = [current, 100 - current];
        gaugeChart.update('none');
        label.textContent = current.toFixed(1) + '%';

        if (progress < 1) requestAnimationFrame(animateGauge);
    }

    requestAnimationFrame(animateGauge);
</script>
@endpush