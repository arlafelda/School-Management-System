@extends('layouts.app')

@section('title', 'Dashboard Guru')

@section('content')

<!-- TITLE -->
<div>
    <h2 class="text-3xl font-extrabold text-blue-900 font-manrope">
        Dashboard Guru
    </h2>

    <p class="text-gray-500 text-sm">
        Ringkasan aktivitas mengajar hari ini
    </p>
</div>

<!-- STAT -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-4 mt-4">

    <div class="bg-white p-4 rounded-xl shadow text-right">
        <p class="text-xs text-left">Total Mengajar</p>

        <h3 class="font-bold">
            {{ $totalTeaching }} Jam
        </h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-right">
        <p class="text-xs text-left">Total Siswa</p>

        <h3 class="font-bold">
            {{ number_format($totalStudents) }}
        </h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-right">
        <p class="text-xs text-left">Rata-rata Absensi</p>

        <h3 class="font-bold text-green-600">
            {{ $attendancePercent }}%
        </h3>
    </div>

</div>

<!-- GRID -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

    <!-- JADWAL -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h3 class="font-semibold mb-4">
            Jadwal Hari Ini
        </h3>

        <div class="space-y-3 text-sm text-gray-600">

            @forelse($todaySchedules as $schedule)

                <div class="flex justify-between border-b pb-2">

                    <span>
                        {{ $schedule->start_time }}
                    </span>

                    <span>
                        {{ $schedule->subject ?? '-' }}
                    </span>

                </div>

            @empty

                <p class="text-gray-400">
                    Tidak ada jadwal hari ini
                </p>

            @endforelse

        </div>

    </div>

    <!-- INPUT NILAI -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h3 class="font-semibold mb-4">
            Jadwal Mengajar
        </h3>

        <div class="space-y-3 text-sm text-gray-600">

            @forelse($todaySchedules as $schedule)

                <div class="border-b pb-2">

                    <p class="font-medium text-gray-800">
                        {{ $schedule->subject ?? '-' }}
                    </p>

                    <p>
                        Kelas:
                        {{ $schedule->class->name ?? '-' }}
                    </p>

                    <p>
                        Jam:
                        {{ $schedule->start_time ?? '-' }}
                        -
                        {{ $schedule->end_time ?? '-' }}
                    </p>

                </div>

            @empty

                <p class="text-gray-400">
                    Tidak ada aktivitas mengajar
                </p>

            @endforelse

        </div>

    </div>

</div>

@endsection