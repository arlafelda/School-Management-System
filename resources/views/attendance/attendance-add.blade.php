@extends('layouts.app')

@section('content')

@php
$user = auth()->user();

$studentClass = $user->role === 'student'
? optional(optional($user->student)->class)
: null;

$hariMap = [
'Monday' => 'Senin',
'Tuesday' => 'Selasa',
'Wednesday' => 'Rabu',
'Thursday' => 'Kamis',
'Friday' => 'Jumat',
'Saturday' => 'Sabtu',
'Sunday' => 'Minggu'
];

$hariAktif =
$hariMap[\Carbon\Carbon::parse(
$date ?? date('Y-m-d')
)->format('l')];
@endphp


<div class="p-6">

    <!-- BREADCRUMB -->
    <div class="mb-4 text-sm text-gray-500">
        <span class="text-gray-700 font-medium">
            Dashboard
        </span>

        <span class="mx-2">/</span>

        <a href="{{ route('attendance.index') }}"
            class="hover:text-blue-600">
            Attendance
        </a>

        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            Input Attendance
        </span>
    </div>


    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-semibold">
                Input Absensi
            </h2>

            <p class="text-sm text-blue-600 font-medium">
                Hari Aktif: {{ $hariAktif }}
            </p>
        </div>

        <span class="text-sm text-gray-500">
            Tanggal:
            {{ \Carbon\Carbon::parse($date ?? date('Y-m-d'))->locale('id')->translatedFormat('d F Y') }}
        </span>
    </div>


    <!-- FILTER -->
    <form method="GET"
        action="{{ route('attendance.create') }}"
        class="bg-white p-4 rounded-xl shadow-sm mb-6 grid md:grid-cols-4 gap-4">

        {{-- CLASS --}}
        @if($user->role === 'student')

        <input type="text"
            value="{{ $studentClass->name ?? '-' }}"
            class="border rounded-lg px-3 py-2 bg-gray-100"
            disabled>

        <input type="hidden"
            name="class_id"
            value="{{ $studentClass->id ?? '' }}">

        @else

        <select name="class_id"
            id="firstInput"
            class="border rounded-lg px-3 py-2">

            <option value="">
                Semua Kelas
            </option>

            @foreach($classes as $c)
            <option value="{{ $c->id }}"
                {{ request('class_id') == $c->id ? 'selected' : '' }}>
                {{ $c->name }}
            </option>
            @endforeach

        </select>

        @endif


        {{-- SCHEDULE --}}
        <select name="schedule_id"
            class="border rounded-lg px-3 py-2"
            required>

            <option value="">
                {{ $schedules->count()
                    ? 'Pilih Jadwal'
                    : 'Tidak ada jadwal hari ini' }}
            </option>

            @foreach($schedules as $s)
            <option value="{{ $s->id }}"
                {{ request('schedule_id') == $s->id ? 'selected' : '' }}>

                @if($user->role === 'teacher')
                {{ $s->subject ?? '-' }}
                - {{ $s->class->name ?? '-' }}
                @else
                {{ $s->teacher->subject ?? '-' }}
                - {{ $s->class->name ?? '-' }}
                @endif

            </option>
            @endforeach

        </select>


        {{-- DATE --}}
        <div>
            <input type="date"
                id="tanggalInput"
                name="date"
                value="{{ $date ?? date('Y-m-d') }}"
                class="border rounded-lg px-3 py-2 w-full">

            <small id="tanggalPreview"
                class="text-gray-500 text-xs block mt-1">
            </small>
        </div>


        <button
            class="bg-blue-600 text-white rounded-lg px-3 py-2">
            Filter
        </button>

    </form>


    <!-- ALERT -->
    <div id="alertBox" class="mb-4"></div>


    @if($schedules->count() == 0)
    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded text-red-700">
        Tidak ada jadwal pada hari
        <b>{{ $hariAktif }}</b>
    </div>

    @elseif($attendanceExists)
    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded text-yellow-800">
        Absensi untuk jadwal dan tanggal ini sudah pernah disimpan.
        Gunakan menu edit untuk mengubah data.
    </div>

    @elseif(!$showForm)
    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded text-blue-800">
        Pilih jadwal lalu tekan tombol Filter.
    </div>
    @endif


    <!-- FORM -->
    <form id="formAttendance"
        @if(!$showForm) style="display:none;" @endif>

        @csrf

        <input type="hidden"
            name="schedule_id"
            value="{{ request('schedule_id') }}">

        <input type="hidden"
            name="date"
            value="{{ $date ?? date('Y-m-d') }}">

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-3">Nama</th>
                        <th class="p-3 text-center">Absensi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($students as $student)
                    <tr class="border-t">

                        <td class="p-3">
                            {{ $student->name }}
                        </td>

                        <td class="p-3 text-center">

                            <input type="hidden"
                                name="student_id[]"
                                value="{{ $student->id }}">

                            @foreach(['hadir','izin','sakit','alpa'] as $status)
                            <label class="mx-2">
                                <input type="radio"
                                    name="attendance[{{ $student->id }}]"
                                    value="{{ $status }}"
                                    {{ $attendanceExists ? 'disabled' : '' }}>
                                {{ ucfirst($status) }}
                            </label>
                            @endforeach

                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="2"
                            class="text-center p-6 text-gray-400">
                            Tidak ada siswa
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>

        </div>


        @if($showForm)
        <div class="mt-4">
            <button type="submit"
                class="px-6 py-2 bg-blue-500 text-white rounded-lg"
                {{ $attendanceExists ? 'disabled' : '' }}>
                Simpan
            </button>
        </div>
        @endif

    </form>


    <!-- BACK -->
    <div class="mt-4">
        <a href="{{ route('attendance.index') }}"
            class="inline-block px-6 py-2 border rounded-lg hover:bg-gray-50">
            Kembali
        </a>
    </div>

</div>

@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        /*
        =========================
        FORMAT TANGGAL INDONESIA
        =========================
        */
        const tanggalInput =
            document.getElementById('tanggalInput');

        const preview =
            document.getElementById('tanggalPreview');

        function formatTanggalIndonesia(value) {
            if (!value) return '';

            return new Date(value)
                .toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });
        }

        if (tanggalInput && preview) {
            preview.innerText =
                formatTanggalIndonesia(
                    tanggalInput.value
                );

            tanggalInput.addEventListener(
                'change',
                function() {
                    preview.innerText =
                        formatTanggalIndonesia(
                            this.value
                        );
                }
            );
        }


        /*
        =========================
        AJAX STORE
        =========================
        */
        if (typeof $ === 'undefined' ||
            typeof createData === 'undefined') {
            return;
        }

        const formEnabled =
            "{{ !empty($showForm) ? 'true' : 'false' }}" === 'true';

        if (!formEnabled) return;

        createData(
            '#formAttendance',
            "{{ route('attendance.store') }}",
            function(res) {

                $('#alertBox').html(`
                <div class="p-3 bg-green-100 text-green-700 rounded">
                    ${res.message}
                </div>
            `);

                $('input[type="radio"]').prop(
                    'checked',
                    false
                );
            }
        );

    });
</script>
@endpush