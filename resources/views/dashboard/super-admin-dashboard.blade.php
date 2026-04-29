@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- TITLE -->
<div>
    <h2 class="text-3xl font-extrabold text-blue-900 font-manrope">Dashboard</h2>
    <p class="text-gray-500 text-sm">Ringkasan data hari ini</p>
</div>

<!-- STAT -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mt-4">

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-xs">Siswa</p>
        <h3 class="font-bold">{{ $totalStudents }}</h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-xs">Guru</p>
        <h3 class="font-bold">{{ $totalTeachers }}</h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-xs">Admin</p>
        <h3 class="font-bold">{{ $totalAdmins }}</h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-xs">Kelas</p>
        <h3 class="font-bold">{{ $totalClasses }}</h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-xs">Jurusan</p>
        <h3 class="font-bold">{{ $totalMajors }}</h3>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-xs">Mapel</p>
        <h3 class="font-bold">{{ $totalSubjects }}</h3>
    </div>

</div>

<!-- GRID -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">

    <!-- CHART -->
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow">
        <h3 class="font-semibold mb-4">Distribusi Pengguna</h3>

        @php
            $labels = ['Siswa', 'Guru', 'Admin'];
            $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500'];
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
                        <span>{{ $value }}</span>
                    </div>

                    <div class="w-full bg-gray-200 rounded h-3">
                        <div class="{{ $colors[$index] }} h-3 rounded {{ $width }}"></div>
                    </div>
                </div>

            @endforeach

        </div>
    </div>

    <!-- AKTIVITAS -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="font-semibold mb-4">Aktivitas</h3>

        <ul class="space-y-2 text-sm text-gray-600">
            @forelse($activities as $act)
                <li>
                    {{ $act->day }} |
                    {{ $act->start_time }} -
                    {{ $act->end_time }} |
                    {{ $act->class->name ?? '-' }}
                </li>
            @empty
                <li>Tidak ada aktivitas</li>
            @endforelse
        </ul>

    </div>

</div>

@endsection