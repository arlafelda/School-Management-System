@extends('layouts.app')

@section('title', 'Detail Jadwal')

@section('content')

<div class="space-y-6">

    <!-- HEADER -->
    <div>
        <h1 class="text-2xl font-bold">Detail Jadwal</h1>
        <p class="text-gray-500 text-sm">Informasi lengkap jadwal pelajaran</p>
    </div>

    <!-- CARD DETAIL -->
    <div class="bg-white rounded-lg shadow p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-gray-500 text-sm">Hari</p>
                <p class="text-lg font-semibold text-blue-600">
                    {{ $schedule->day }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Jam Pelajaran</p>
                <p class="text-lg font-semibold">
                    {{ $schedule->start_time }} - {{ $schedule->end_time }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Kelas</p>
                <p class="text-lg font-semibold">
                    {{ $schedule->class->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Jurusan</p>
                <p class="text-lg font-semibold">
                    {{ $schedule->class->major ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Mata Pelajaran</p>
                <p class="text-lg font-semibold">
                    {{ $schedule->teacher->subject ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Guru Pengajar</p>
                <p class="text-lg font-semibold">
                    {{ $schedule->teacher->name ?? '-' }}
                </p>
            </div>

        </div>

        <!-- BUTTON -->
        <div class="mt-6 flex gap-3">

            <a href="{{ route('schedule.edit', $schedule->id) }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Edit Jadwal
            </a>

            <a href="{{ route('schedule.index') }}"
               class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300">
                Kembali
            </a>

        </div>

    </div>

</div>

@endsection