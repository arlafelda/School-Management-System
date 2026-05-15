@extends('layouts.app')

@section('content')

@php
$user = auth()->user();

$studentClass = $user->role === 'student'
? optional(optional($user->student)->class)
: null;
@endphp

<div class="p-8 max-w-5xl mx-auto">

    <!-- BREADCRUMB -->
    <div class="mb-4 text-sm text-gray-500">
        <span class="text-gray-700 font-medium">
            Dashboard
        </span>
        /
        <a href="{{ route('attendance.index') }}"
            class="hover:text-blue-600">
            Manajemen Absensi
        </a>
        /
        <span class="text-gray-700 font-medium">
            Edit Absensi
        </span>
    </div>


    <!-- HEADER -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold">
            Edit Absensi
        </h2>

        <p class="text-sm text-gray-500">
            Perbarui data absensi siswa
        </p>
    </div>


    <!-- FILTER -->
    <div class="bg-white p-4 rounded-xl shadow-sm mb-6">

        <div class="grid md:grid-cols-3 gap-4">

            {{-- CLASS --}}
            @if($user->role === 'student')

            <input type="text"
                value="{{ $studentClass->name ?? '-' }}"
                class="border rounded-lg px-3 py-2 bg-gray-100"
                disabled>

            @else

            <select class="border rounded-lg px-3 py-2 bg-gray-100"
                disabled>

                @foreach($classes as $c)
                <option
                    {{ $attendance->student->class_id == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
                @endforeach

            </select>

            @endif


            {{-- SCHEDULE --}}
            @if($user->role === 'teacher')

            <input type="hidden"
                name="schedule_id"
                value="{{ $attendance->schedule_id }}">

            <input type="text"
                value="{{ $attendance->schedule->subject ?? '-' }}"
                class="border rounded-lg px-3 py-2 bg-gray-100"
                disabled>

            @else

            <select name="schedule_id"
                form="formEditAttendance"
                class="border rounded-lg px-3 py-2">

                @foreach($schedules as $s)
                <option value="{{ $s->id }}"
                    {{ $attendance->schedule_id == $s->id ? 'selected' : '' }}>

                    {{ $s->teacher->subject ?? '-' }}
                    -
                    {{ $s->class->name ?? '-' }}

                </option>
                @endforeach

            </select>

            @endif


            {{-- DATE --}}
            <div>
                <input type="date"
                    id="tanggalInput"
                    name="date"
                    form="formEditAttendance"
                    value="{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}"
                    class="border rounded-lg px-3 py-2 w-full">

                <small id="tanggalPreview"
                    class="text-gray-500 text-xs block mt-1">
                </small>
            </div>

        </div>

    </div>


    <!-- ALERT -->
    <div id="alertBox" class="mb-4"></div>


    <!-- FORM -->
    <form id="formEditAttendance"
        method="POST"
        action="{{ route('attendance.update', $attendance->id) }}">

        @csrf
        @method('PUT')

        <input type="hidden"
            name="schedule_id"
            value="{{ $attendance->schedule_id }}">


        <!-- TABLE -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="p-4 border-b">
                <h3 class="font-semibold">
                    Data Siswa
                </h3>
            </div>

            <table class="w-full text-sm">

                <thead class="bg-gray-100 text-left">
                    <tr>
                        <th class="p-3">Nama</th>
                        <th class="p-3 text-center">Status</th>
                    </tr>
                </thead>

                <tbody>

                    <tr class="border-t">

                        <td class="p-3 font-medium">
                            {{ $attendance->student->name }}
                        </td>

                        <td class="p-3 text-center">

                            <div class="flex justify-center gap-4 flex-wrap">

                                @foreach(['hadir','izin','sakit','alpa'] as $status)

                                <label>
                                    <input type="radio"
                                        name="status"
                                        value="{{ $status }}"
                                        {{ $attendance->status == $status ? 'checked' : '' }}>

                                    {{ ucfirst($status) }}
                                </label>

                                @endforeach

                            </div>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        <!-- ACTION -->
        <div class="mt-6 flex justify-end gap-3">

            <a href="{{ route('attendance.index') }}"
                class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-50">
                Batal
            </a>

            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                Update
            </button>

        </div>

    </form>

</div>

@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        /*
        =========================
        AUTO FOCUS
        =========================
        */
        document.getElementById('tanggalInput')?.focus();


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
        AJAX UPDATE
        =========================
        */
        if (typeof window.$ === 'undefined') {
            console.error('jQuery belum load');
            return;
        }

        if (typeof window.updateData === 'function') {

            updateData(
                '#formEditAttendance',
                "{{ route('attendance.update', $attendance->id) }}",
                function(res) {

                    $('#alertBox').html(`
                    <div class="p-3 bg-green-100 text-green-700 rounded-lg">
                        ${res.message ?? 'Absensi berhasil diperbarui'}
                    </div>
                `);

                }
            );

        } else {
            console.error(
                'updateData tidak ditemukan (ajax.js belum ke-load)'
            );
        }

    });
</script>
@endpush