@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

<!-- TITLE -->
<div>
    <h2 class="text-3xl font-extrabold text-blue-900 font-manrope">
        Dashboard Guru
    </h2>
    <p class="text-gray-500 text-sm">
        Ringkasan aktivitas mengajar — {{ $today }}
    </p>
</div>

<!-- STAT (4 kartu: hapus Absensi & Jam Mengajar) -->
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-4">

    <!-- Siswa Wali Kelas -->
    <div class="bg-white p-4 rounded-xl shadow">
        <p class="text-xs text-gray-500">Siswa Wali Kelas</p>
        <h3 class="text-2xl font-bold mt-1">{{ number_format($totalStudents) }}</h3>
        @if($homeroomClass)
            <p class="text-xs text-blue-500 mt-1">{{ $homeroomClass->name }}</p>
        @else
            <p class="text-xs text-gray-400 mt-1">Belum ada kelas wali</p>
        @endif
    </div>

    <!-- Kelas Diajar -->
    <div class="bg-white p-4 rounded-xl shadow">
        <p class="text-xs text-gray-500">Kelas Diajar</p>
        <h3 class="text-2xl font-bold mt-1">{{ number_format($totalClasses) }}</h3>
    </div>

    <!-- Mata Pelajaran -->
    <div class="bg-white p-4 rounded-xl shadow">
        <p class="text-xs text-gray-500">Mata Pelajaran</p>
        <h3 class="text-2xl font-bold mt-1">{{ number_format($totalSubjects) }}</h3>
    </div>

    <!-- Jadwal Hari Ini -->
    <div class="bg-blue-50 p-4 rounded-xl shadow">
        <p class="text-xs text-blue-600">Jadwal Hari Ini</p>
        <h3 class="text-2xl font-bold mt-1 text-blue-700">{{ $todayCount }}</h3>
    </div>

</div>

<!-- GRID -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

    <!-- DAFTAR MATA PELAJARAN -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h3 class="font-semibold mb-4">Mata Pelajaran Diampu</h3>

        @if($subjects->isEmpty())
            <p class="text-sm text-gray-400 text-center py-4">Belum ada mata pelajaran</p>
        @else
            <ul class="space-y-2">
                @foreach($subjects as $subject)
                    <li class="flex items-center gap-2 text-sm">
                        <span class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0"></span>
                        <span class="text-gray-700">{{ $subject->name }}</span>
                    </li>
                @endforeach
            </ul>
        @endif

    </div>

    <!-- JADWAL HARI INI -->
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow">

        <h3 class="font-semibold mb-4">Jadwal Hari Ini</h3>

        @forelse($todaySchedules as $schedule)

            <div class="flex items-start gap-4 border-b pb-3 mb-3 last:border-0 last:mb-0 last:pb-0">

                <!-- Waktu -->
                <div class="text-center min-w-[60px]">
                    <p class="text-sm font-semibold text-blue-700">
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                    </p>
                </div>

                <!-- Info -->
                <div>
                    <p class="font-medium text-gray-800">
                        {{ optional($schedule->subject)->name ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Kelas: {{ optional($schedule->class)->name ?? '-' }}
                    </p>
                </div>

            </div>

        @empty

            <div class="text-center py-8 text-gray-400">
                <p class="text-3xl mb-2">📭</p>
                <p class="text-sm">Tidak ada jadwal hari ini</p>
            </div>

        @endforelse

    </div>

</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.chart-bar').forEach(function (el) {
        const w = el.dataset.width || 0;
        el.style.width = Math.min(w, 100) + '%';
    });
});
</script>
@endpush