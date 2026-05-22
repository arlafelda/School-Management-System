@extends('layouts.app')

@section('content')

@php
$user = auth()->user();

$studentClass = $user->role === 'student'
    ? optional(optional($user->student)->class)
    : null;

$dateValue = $date ?? date('Y-m-d');

$hariMap = [
    'Monday' => 'Senin',
    'Tuesday' => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday' => 'Kamis',
    'Friday' => 'Jumat',
    'Saturday' => 'Sabtu',
    'Sunday' => 'Minggu'
];

$hariAktif = $hariMap[\Carbon\Carbon::parse($dateValue)->format('l')] ?? '-';
@endphp

<div class="p-6">

    {{-- BREADCRUMB --}}
    <div class="mb-4 text-sm text-gray-500">
        <span class="text-gray-700 font-medium">Dashboard</span>
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

    {{-- HEADER --}}
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
            {{ \Carbon\Carbon::parse($dateValue)->locale('id')->translatedFormat('d F Y') }}
        </span>
    </div>

    {{-- FILTER --}}
    <form id="filterForm"
          method="GET"
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
                    id="classFilter"
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
                id="scheduleFilter"
                class="border rounded-lg px-3 py-2"
                required>

            <option value="">
                {{ $schedules->count() ? 'Pilih Jadwal' : 'Tidak ada jadwal hari ini' }}
            </option>

            @foreach($schedules as $s)

                <option value="{{ $s->id }}"
                        data-class="{{ $s->class_id }}"
                        {{ request('schedule_id') == $s->id ? 'selected' : '' }}>

                    {{ optional($s->subject)->name ?? 'Mapel tidak ada' }}
                    -
                    {{ optional($s->classModel)->name ?? '-' }}

                    (
                    {{ substr($s->start_time,0,5) }}
                    -
                    {{ substr($s->end_time,0,5) }}
                    )

                </option>

            @endforeach

        </select>


        {{-- DATE --}}
        <div>
            <input type="date"
                   id="tanggalInput"
                   name="date"
                   value="{{ $dateValue }}"
                   class="border rounded-lg px-3 py-2 w-full">

            <small id="tanggalPreview"
                   class="text-gray-500 text-xs block mt-1">
            </small>
        </div>


        {{-- BUTTON --}}
        <button type="submit"
                class="bg-blue-600 text-white rounded-lg px-3 py-2">
            Filter
        </button>

    </form>


    <div id="alertBox" class="mb-4"></div>


    {{-- ALERT --}}
    @if($schedules->count() == 0)

        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded text-red-700">
            Tidak ada jadwal pada hari <b>{{ $hariAktif }}</b>
        </div>

    @elseif($attendanceExists)

        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded text-yellow-800">
            Absensi sudah ada. Gunakan edit untuk perubahan data.
        </div>

    @elseif(!$showForm)

        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded text-blue-800">
            Pilih jadwal terlebih dahulu.
        </div>

    @endif


    {{-- FORM ABSENSI --}}
    <form id="formAttendance"
          @if(!$showForm) style="display:none;" @endif>

        @csrf

        <input type="hidden"
               name="schedule_id"
               value="{{ request('schedule_id') }}">

        <input type="hidden"
               name="date"
               value="{{ $dateValue }}">

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
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg">
                    Simpan
                </button>
            </div>
        @endif

    </form>


    <div class="mt-4">
        <a href="{{ route('attendance.index') }}"
           class="inline-block px-6 py-2 border rounded-lg hover:bg-gray-50">
            Kembali
        </a>
    </div>

</div>


<script id="show-form-data" type="application/json">
{!! json_encode($showForm) !!}
</script>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const tanggalInput = document.getElementById('tanggalInput');
    const preview = document.getElementById('tanggalPreview');
    const filterForm = document.getElementById('filterForm');
    const classFilter = document.getElementById('classFilter');
    const scheduleFilter = document.getElementById('scheduleFilter');

    function formatTanggal(value) {
        if (!value) return '';

        return new Date(value).toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    if (tanggalInput && preview) {
        preview.innerText = formatTanggal(tanggalInput.value);

        tanggalInput.addEventListener('change', function () {
            preview.innerText = formatTanggal(this.value);
            filterForm.submit();
        });
    }

    function filterSchedule() {

        const selectedClass =
            classFilter ? classFilter.value : '';

        Array.from(scheduleFilter.options).forEach((option, index) => {

            if (index === 0) return;

            option.hidden =
                selectedClass !== '' &&
                option.dataset.class !== selectedClass;

        });

        scheduleFilter.value = '';
    }

    if (classFilter) {
        classFilter.addEventListener('change', filterSchedule);
    }

    if (scheduleFilter) {
        scheduleFilter.addEventListener('change', function () {
            if (this.value) {
                filterForm.submit();
            }
        });
    }

    if (typeof createData !== 'undefined') {

        const formEnabled = JSON.parse(
            document.getElementById('show-form-data').textContent
        );

        if (formEnabled) {

            createData('#formAttendance',
                "{{ route('attendance.store') }}",
                {
                    onSuccess: function(res) {

                        document.getElementById('alertBox').innerHTML =
                        `
                            <div class="p-3 bg-green-100 text-green-700 rounded">
                                ${res.message}
                            </div>
                        `;

                        document.querySelectorAll(
                            'input[type="radio"]'
                        ).forEach(function(radio){
                            radio.checked = false;
                        });

                    }
                }
            );

        }
    }

});
</script>
@endpush