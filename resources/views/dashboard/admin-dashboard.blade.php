@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    .stat-card { transition: box-shadow .2s, transform .2s; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.08); }
</style>
@endpush

@section('content')

<!-- HEADER -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Dashboard Admin</h2>
        <p class="text-slate-400 text-sm mt-0.5">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
    </div>
    <div class="flex items-center gap-2 text-sm text-slate-500 bg-white border border-slate-200 px-4 py-2 rounded-xl shadow-sm">
        <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Hari ini: <span class="font-semibold text-slate-700">{{ $today }}</span>
    </div>
</div>

<!-- STAT CARDS — border-left accent style, berbeda dari super-admin -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    @php
    $stats = [
        ['label' => 'Siswa',   'value' => $totalStudents,  'color' => 'border-blue-500',   'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'accent' => 'text-blue-500 bg-blue-50'],
        ['label' => 'Guru',    'value' => $totalTeachers,  'color' => 'border-emerald-500', 'icon' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222', 'accent' => 'text-emerald-500 bg-emerald-50'],
        ['label' => 'Kelas',   'value' => $totalClasses,   'color' => 'border-amber-500',   'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'accent' => 'text-amber-500 bg-amber-50'],
        ['label' => 'Jadwal',  'value' => $totalSchedules, 'color' => 'border-violet-500',  'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'accent' => 'text-violet-500 bg-violet-50'],
    ];
    @endphp

    @foreach($stats as $s)
    <div class="stat-card bg-white rounded-2xl shadow-sm border-l-4 {{ $s['color'] }} p-5 flex items-center gap-4">
        <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center {{ $s['accent'] }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/>
            </svg>
        </div>
        <div>
            <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">{{ $s['label'] }}</p>
            <p class="text-2xl font-extrabold text-slate-800">{{ number_format($s['value']) }}</p>
        </div>
    </div>
    @endforeach

</div>

<!-- MAIN GRID -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- KIRI: Radar + distribusi ringkasan -->
    <div class="lg:col-span-2 flex flex-col gap-6">

        <!-- Radar chart — gambaran keseluruhan data sekolah -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-slate-700">Gambaran Data Sekolah</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Distribusi seluruh entitas yang dikelola admin</p>
                </div>
                <span class="text-xs bg-indigo-50 text-indigo-600 font-medium px-2.5 py-1 rounded-full">Radar</span>
            </div>
            <div class="h-64 relative">
                <canvas id="radarChart"></canvas>
            </div>
        </div>

    </div>

    <!-- KANAN: Aktivitas hari ini -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col">

        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-700">Jadwal Hari Ini</h3>
            <span class="text-xs font-semibold bg-indigo-100 text-indigo-700 px-2.5 py-1 rounded-full">
                {{ $today }}
            </span>
        </div>

        <div class="flex items-center gap-2 bg-slate-50 rounded-xl px-3 py-2 mb-4">
            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-xs text-slate-500">
                {{ count($activities) }} sesi dijadwalkan hari ini
            </span>
        </div>

        <ul class="space-y-2 flex-1 overflow-y-auto max-h-[420px] pr-0.5">
            @forelse($activities as $act)
            <li class="flex gap-3 items-start p-3 rounded-xl hover:bg-slate-50 transition-colors border border-transparent hover:border-slate-100">
                <div class="flex-shrink-0 text-center mt-0.5 min-w-[44px]">
                    <span class="block text-xs font-bold text-indigo-600">
                        {{ \Carbon\Carbon::parse($act->start_time)->format('H:i') }}
                    </span>
                    <span class="block text-[10px] text-slate-400">
                        {{ \Carbon\Carbon::parse($act->end_time)->format('H:i') }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-slate-800 truncate">
                        {{ $act->subject->name ?? ($act->class->name ?? '-') }}
                    </p>
                    <p class="text-xs text-slate-500 truncate">
                        {{ $act->class->name ?? '-' }}
                    </p>
                    <p class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ $act->teacher->name ?? '-' }}
                    </p>
                </div>
            </li>
            @empty
            <li class="flex flex-col items-center justify-center py-12 text-slate-400">
                <svg class="w-12 h-12 mb-3 opacity-25" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm font-medium">Tidak ada jadwal</p>
                <p class="text-xs mt-0.5">hari ini libur atau belum diatur</p>
            </li>
            @endforelse
        </ul>

    </div>

</div>

{{-- Data chart disimpan di HTML agar VS Code tidak error parse Blade syntax --}}
<div id="chart-data" class="hidden"
    data-students="{{ $totalStudents }}"
    data-teachers="{{ $totalTeachers }}"
    data-classes="{{ $totalClasses }}"
    data-majors="{{ $totalMajors }}"
    data-subjects="{{ $totalSubjects }}"
    data-schedules="{{ $totalSchedules }}">
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";

    const el = document.getElementById('chart-data');
    const d = {
        students:  parseInt(el.dataset.students),
        teachers:  parseInt(el.dataset.teachers),
        classes:   parseInt(el.dataset.classes),
        majors:    parseInt(el.dataset.majors),
        subjects:  parseInt(el.dataset.subjects),
        schedules: parseInt(el.dataset.schedules),
    };

    // ---- Radar Chart: gambaran data sekolah ----
    new Chart(document.getElementById('radarChart'), {
        type: 'radar',
        data: {
            labels: ['Siswa', 'Guru', 'Kelas', 'Jurusan', 'Mapel', 'Jadwal'],
            datasets: [{
                label: 'Jumlah',
                data: [d.students, d.teachers, d.classes, d.majors, d.subjects, d.schedules],
                backgroundColor: 'rgba(99,102,241,0.15)',
                borderColor: '#6366f1',
                borderWidth: 2,
                pointBackgroundColor: '#6366f1',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                r: {
                    beginAtZero: true,
                    grid: { color: '#e2e8f0' },
                    angleLines: { color: '#e2e8f0' },
                    pointLabels: { font: { size: 11 }, color: '#64748b' },
                    ticks: {
                        display: false,
                        callback: v => v.toLocaleString('id-ID'),
                    },
                }
            }
        }
    });

</script>
@endpush