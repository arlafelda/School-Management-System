@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    .stat-card {
        position: relative;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    }
    .stat-card .icon-bg {
        position: absolute;
        right: -10px;
        bottom: -10px;
        opacity: 0.15;
        width: 70px;
        height: 70px;
    }
    .activity-item:last-child {
        border-bottom: none !important;
    }
</style>
@endpush

@section('content')

<!-- HEADER -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-gray-800 tracking-tight">Dashboard</h2>
        <p class="text-gray-400 text-sm mt-0.5">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
    </div>
    <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-green-50 text-green-700 border border-green-200 px-3 py-1.5 rounded-full">
        <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
        Sistem Aktif
    </span>
</div>

<!-- STAT CARDS -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">

    <div class="stat-card bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-2xl text-white shadow-md">
        <p class="text-blue-200 text-xs font-medium uppercase tracking-wider">Siswa</p>
        <h3 class="text-2xl font-extrabold mt-1">{{ number_format($totalStudents) }}</h3>
        <svg class="icon-bg" fill="white" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
    </div>

    <div class="stat-card bg-gradient-to-br from-emerald-500 to-emerald-600 p-4 rounded-2xl text-white shadow-md">
        <p class="text-emerald-100 text-xs font-medium uppercase tracking-wider">Guru</p>
        <h3 class="text-2xl font-extrabold mt-1">{{ number_format($totalTeachers) }}</h3>
        <svg class="icon-bg" fill="white" viewBox="0 0 24 24"><path d="M5 3h14a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2zm7 3a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm0 5c-2.33 0-4 1.17-4 2v1h8v-1c0-.83-1.67-2-4-2zM3 19h18v2H3v-2z"/></svg>
    </div>

    <div class="stat-card bg-gradient-to-br from-violet-500 to-violet-600 p-4 rounded-2xl text-white shadow-md">
        <p class="text-violet-200 text-xs font-medium uppercase tracking-wider">Admin</p>
        <h3 class="text-2xl font-extrabold mt-1">{{ number_format($totalAdmins) }}</h3>
        <svg class="icon-bg" fill="white" viewBox="0 0 24 24"><path d="M12 1l3.09 6.26L22 8.27l-5 4.87L18.18 20 12 16.77 5.82 20 7 13.14 2 8.27l6.91-1.01L12 1z"/></svg>
    </div>

    <div class="stat-card bg-gradient-to-br from-amber-500 to-orange-500 p-4 rounded-2xl text-white shadow-md">
        <p class="text-amber-100 text-xs font-medium uppercase tracking-wider">Kelas</p>
        <h3 class="text-2xl font-extrabold mt-1">{{ number_format($totalClasses) }}</h3>
        <svg class="icon-bg" fill="white" viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-1 9H9V9h10v2zm-4 4H9v-2h6v2zm4-8H9V5h10v2z"/></svg>
    </div>

    <div class="stat-card bg-gradient-to-br from-pink-500 to-rose-500 p-4 rounded-2xl text-white shadow-md">
        <p class="text-pink-100 text-xs font-medium uppercase tracking-wider">Jurusan</p>
        <h3 class="text-2xl font-extrabold mt-1">{{ number_format($totalMajors) }}</h3>
        <svg class="icon-bg" fill="white" viewBox="0 0 24 24"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
    </div>

    <div class="stat-card bg-gradient-to-br from-cyan-500 to-teal-500 p-4 rounded-2xl text-white shadow-md">
        <p class="text-cyan-100 text-xs font-medium uppercase tracking-wider">Mapel</p>
        <h3 class="text-2xl font-extrabold mt-1">{{ number_format($totalSubjects) }}</h3>
        <svg class="icon-bg" fill="white" viewBox="0 0 24 24"><path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1z"/></svg>
    </div>

</div>

<!-- CHART + ACTIVITY ROW -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

    <!-- CHARTS PANEL -->
    <div class="lg:col-span-2 flex flex-col gap-6">

        <!-- Doughnut + Bar -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- Doughnut Chart: Distribusi Pengguna -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-700 text-sm mb-4">Distribusi Pengguna</h3>
                <div class="relative h-44">
                    <canvas id="doughnutChart"></canvas>
                </div>
                <div class="flex justify-center gap-4 mt-4 text-xs text-gray-500">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 inline-block"></span>Siswa</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>Guru</span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-violet-500 inline-block"></span>Admin</span>
                </div>
            </div>

            <!-- Bar Chart: Ringkasan Data -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-semibold text-gray-700 text-sm mb-4">Ringkasan Data</h3>
                <div class="relative h-44">
                    <canvas id="barChart"></canvas>
                </div>
            </div>

        </div>

        <!-- Horizontal bar breakdown -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-semibold text-gray-700 text-sm mb-5">Perbandingan Pengguna</h3>
            @php
                $userLabels = ['Siswa', 'Guru', 'Admin'];
                $userColors = ['bg-blue-500', 'bg-emerald-500', 'bg-violet-500'];
                $userMax = max($chartData) ?: 1;
            @endphp
            <div class="space-y-4">
                @foreach($chartData as $i => $value)
                @php $pct = round(($value / $userMax) * 100); @endphp
                <div>
                    <div class="flex justify-between items-center text-sm mb-1.5">
                        <span class="text-gray-600 font-medium">{{ $userLabels[$i] }}</span>
                        <span class="text-gray-800 font-semibold">{{ number_format($value) }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="{{ $userColors[$i] }} h-2.5 rounded-full transition-all duration-700"
                             x-data x-init="setTimeout(() => $el.style.width = '{{ $pct }}%', 100)"
                             style="width: 0%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

    <!-- ACTIVITY PANEL -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-semibold text-gray-700 text-sm">Aktivitas Hari Ini</h3>
            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">{{ count($activities) }} jadwal</span>
        </div>

        <ul class="space-y-3 flex-1 overflow-y-auto max-h-96 pr-1">
            @forelse($activities as $act)
            <li class="activity-item flex gap-3 border-b border-gray-50 pb-3">
                <!-- Dot indicator -->
                <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-blue-500 ring-4 ring-blue-50"></div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">
                        {{ $act->class->name ?? '-' }}
                    </p>
                    <p class="text-xs text-blue-500 font-medium mt-0.5">
                        {{ $act->subject->name ?? '' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ $act->day }} &middot;
                        {{ \Carbon\Carbon::parse($act->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($act->end_time)->format('H:i') }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                        {{ $act->teacher->name ?? '-' }}
                    </p>
                </div>
            </li>
            @empty
            <li class="flex flex-col items-center justify-center py-10 text-center text-gray-400">
                <svg class="w-10 h-10 mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm">Tidak ada aktivitas</p>
            </li>
            @endforelse
        </ul>
    </div>

</div>

{{-- Data chart disimpan di HTML, bukan JS, agar VS Code tidak salah parse Blade syntax --}}
<div id="chart-data" class="hidden"
    data-students="{{ $chartData[0] }}"
    data-teachers="{{ $chartData[1] }}"
    data-admins="{{ $chartData[2] }}"
    data-classes="{{ $totalClasses }}"
    data-majors="{{ $totalMajors }}"
    data-subjects="{{ $totalSubjects }}">
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

{{-- Data disimpan di atribut HTML, bukan di dalam script, agar VS Code tidak error --}}

<script>
    Chart.defaults.font.family = "'Inter', sans-serif";

    // Baca data dari atribut HTML — tidak ada Blade syntax di dalam script
    const el = document.getElementById('chart-data');
    const d = {
        students: parseInt(el.dataset.students),
        teachers: parseInt(el.dataset.teachers),
        admins:   parseInt(el.dataset.admins),
        classes:  parseInt(el.dataset.classes),
        majors:   parseInt(el.dataset.majors),
        subjects: parseInt(el.dataset.subjects),
    };

    // --- Doughnut Chart ---
    const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Siswa', 'Guru', 'Admin'],
            datasets: [{
                data: [d.students, d.teachers, d.admins],
                backgroundColor: ['#3b82f6', '#10b981', '#8b5cf6'],
                borderColor: '#fff',
                borderWidth: 3,
                hoverOffset: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + ctx.label + ': ' + ctx.parsed.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    // --- Bar Chart ---
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Kelas', 'Jurusan', 'Mapel'],
            datasets: [{
                label: 'Jumlah',
                data: [d.classes, d.majors, d.subjects],
                backgroundColor: ['#f59e0b', '#ec4899', '#06b6d4'],
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ' ' + ctx.parsed.y.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 }, color: '#9ca3af' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        font: { size: 11 },
                        color: '#9ca3af',
                        callback: val => val.toLocaleString('id-ID')
                    }
                }
            }
        }
    });
</script>
@endpush