@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

<div class="space-y-8">

    <!-- HERO -->
    <section class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 text-white rounded-3xl p-8 shadow-lg">
        <h1 class="text-2xl md:text-3xl font-bold">
            Halo, {{ auth()->user()->name }} 👋
        </h1>

        <p class="text-sm mt-2 text-white/80">
            Selamat datang kembali di dashboard siswa
        </p>

        <p class="text-sm mt-1 text-white/70">
            Kelas:
            {{ auth()->user()->student->class->name ?? '-' }}
        </p>
    </section>


    <!-- STATS -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <!-- KEHADIRAN -->
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-slate-500">
                Persentase Kehadiran
            </p>

            <h2 class="text-3xl font-bold mt-2 text-right">
                {{ number_format($attendancePercent ?? 0, 1) }}%
            </h2>

            <div class="w-full bg-gray-200 h-2 rounded mt-4">
                <div
                    id="progressBar"
                    data-percent="{{ $attendancePercent ?? 0 }}"
                    class="bg-blue-600 h-2 rounded transition-all duration-700"
                    style="width: 0%">
                </div>
            </div>
        </div>


        <!-- TOTAL -->
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-slate-500">
                Total Kehadiran
            </p>

            <h2 class="text-2xl font-bold mt-2 text-right">
                {{ number_format($totalAttendance ?? 0) }}
            </h2>
        </div>


        <!-- JADWAL -->
        <div class="bg-blue-50 rounded-2xl shadow p-6">
            <p class="text-sm text-blue-600">
                Jadwal Hari Ini
            </p>

            <h2 class="text-2xl font-bold mt-2 text-right text-blue-700">
                {{ ($todaySchedules ?? collect())->count() }} Pelajaran
            </h2>
        </div>

    </section>


    <!-- LIST JADWAL -->
    <section class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="p-5 border-b font-semibold">
            Jadwal Hari Ini
        </div>

        <div class="divide-y">

            @forelse($todaySchedules ?? [] as $schedule)

                <div class="p-5 flex justify-between items-center">

                    <div>
                        <p class="font-semibold">
                            {{ $schedule->teacher->subject ?? '-' }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ $schedule->start_time }}
                            -
                            {{ $schedule->end_time }}
                        </p>

                        <p class="text-xs text-gray-400">
                            {{ $schedule->teacher->name ?? '-' }}
                        </p>
                    </div>

                </div>

            @empty

                <div class="p-6 text-center text-gray-500">
                    Tidak ada jadwal hari ini
                </div>

            @endforelse

        </div>

    </section>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const bar = document.getElementById('progressBar');

    if (bar) {
        const percent = bar.dataset.percent || 0;
        bar.style.width = percent + '%';
    }

});
</script>
@endpush