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
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mt-4">

    <div class="bg-white p-4 rounded-xl shadow text-right">
        <p class="text-xs text-left">Jam Mengajar</p>
        <h3 class="font-bold">
            {{ $totalTeaching ?? 0 }} Jam
        </h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-right">
        <p class="text-xs text-left">Total Siswa</p>
        <h3 class="font-bold">
            {{ number_format($totalStudents ?? 0) }}
        </h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-right">
        <p class="text-xs text-left">Kelas</p>
        <h3 class="font-bold">
            {{ number_format($totalClasses ?? 0) }}
        </h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-right">
        <p class="text-xs text-left">Mapel</p>
        <h3 class="font-bold">
            {{ number_format($totalSubjects ?? 0) }}
        </h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-right">
        <p class="text-xs text-left">Jadwal Hari Ini</p>
        <h3 class="font-bold">
            {{ $todayCount ?? 0 }}
        </h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-right">
        <p class="text-xs text-left">Absensi</p>
        <h3 class="font-bold text-green-600">
            {{ $attendancePercent ?? 0 }}%
        </h3>
    </div>

</div>

<!-- GRID -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

    <!-- CHART -->
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow">

        <h3 class="font-semibold mb-4">
            Distribusi Aktivitas
        </h3>

        @php
            $labels = ['Jam Mengajar', 'Siswa', 'Kelas', 'Jadwal'];

            $colors = [
                'bg-blue-500',
                'bg-green-500',
                'bg-yellow-500',
                'bg-purple-500'
            ];

            $chartData = [
                $totalTeaching ?? 0,
                $totalStudents ?? 0,
                $totalClasses ?? 0,
                $todayCount ?? 0
            ];

            $max = max($chartData) ?: 1;
        @endphp

        <div class="flex flex-col gap-4">

            @foreach($chartData as $index => $value)

                @php
                    $percent = ($value / $max) * 100;

                    if ($percent >= 90) $width = 'w-full';
                    elseif ($percent >= 75) $width = 'w-3/4';
                    elseif ($percent >= 50) $width = 'w-1/2';
                    elseif ($percent >= 25) $width = 'w-1/4';
                    else $width = 'w-1/6';
                @endphp

                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span>{{ $labels[$index] }}</span>
                        <span>{{ number_format($value) }}</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded h-3">
                        <div class="{{ $colors[$index] }} h-3 rounded {{ $width }}"></div>
                    </div>
                </div>

            @endforeach

        </div>

    </div>

    <!-- JADWAL HARI INI -->
    <div class="bg-white p-6 rounded-xl shadow">

        <h3 class="font-semibold mb-4">
            Jadwal Hari Ini
        </h3>

        <ul class="space-y-3 text-sm text-gray-600">

            @forelse(($todaySchedules ?? []) as $schedule)

                <li class="border-b pb-2">

                    <p class="font-medium text-gray-800">
                        {{ $schedule->teacher->subject ?? '-' }}
                    </p>

                    <p>
                        {{ $schedule->start_time ?? '-' }}
                        -
                        {{ $schedule->end_time ?? '-' }}
                    </p>

                    <p>
                        Kelas:
                        {{ $schedule->class->name ?? '-' }}
                    </p>

                </li>

            @empty

                <li class="text-gray-400">
                    Tidak ada jadwal hari ini
                </li>

            @endforelse

        </ul>

    </div>

</div>

@endsection