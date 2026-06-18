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
            Kelas: {{ optional(optional(auth()->user()->student)->class)->name ?? '-' }}
        </p>
    </section>


    <!-- STATS -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <!-- KEHADIRAN -->
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-slate-500">Persentase Kehadiran</p>

            <h2 class="text-3xl font-bold mt-2 text-right">
                {{ number_format($attendancePercent, 1) }}%
            </h2>

            <div class="w-full bg-gray-200 h-2 rounded mt-4">
                <div
                    id="progressBar"
                    data-percent="{{ $attendancePercent }}"
                    class="bg-blue-600 h-2 rounded transition-all duration-700"
                    style="width: 0%">
                </div>
            </div>
        </div>

        <!-- TOTAL KEHADIRAN -->
        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-sm text-slate-500">Total Kehadiran</p>

            <h2 class="text-2xl font-bold mt-2 text-right">
                {{ number_format($totalAttendance) }}
            </h2>
        </div>

        <!-- JADWAL -->
        <div class="bg-blue-50 rounded-2xl shadow p-6">
            <p class="text-sm text-blue-600">Jadwal Hari Ini</p>

            <h2 class="text-2xl font-bold mt-2 text-right text-blue-700">
                {{ $todaySchedules->count() }} Pelajaran
            </h2>
        </div>

    </section>


    <!-- LIST JADWAL -->
    <section class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="p-5 border-b font-semibold">
            Jadwal Hari Ini " {{ $today }} "
        </div>

        <div class="divide-y">

            @forelse($todaySchedules as $schedule)

                <div class="p-5 flex justify-between items-center">
                    <div>
                        {{--
                            Prioritas tampilan nama mata pelajaran:
                            1. $schedule->subject->name  (relasi ke tabel subjects)
                            2. $schedule->subject_name   (kolom langsung di tabel schedules)
                            3. $schedule->teacher->subject (kolom subject di tabel teachers)
                            4. '-' jika semua null
                        --}}
                        <p class="font-semibold">
                            @if(optional($schedule->subject)->name)
                                {{ $schedule->subject->name }}
                            @elseif(!empty($schedule->subject_name))
                                {{ $schedule->subject_name }}
                            @elseif(optional($schedule->teacher)->subject)
                                {{ $schedule->teacher->subject }}
                            @else
                                -
                            @endif
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                            –
                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        </p>

                        <p class="text-xs text-gray-400">
                            {{ optional($schedule->teacher)->name ?? '-' }}
                        </p>
                    </div>

                    <!-- Badge hari -->
                    <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-medium">
                        {{ $schedule->day }}
                    </span>
                </div>

            @empty

                <div class="p-10 text-center text-gray-400">
                    <p class="text-4xl mb-2">📭</p>
                    <p>Tidak ada jadwal hari ini</p>
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
        const percent = parseFloat(bar.dataset.percent) || 0;
        bar.style.width = Math.min(percent, 100) + '%';
    }
});
</script>
@endpush