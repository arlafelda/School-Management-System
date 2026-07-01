@extends('layouts.app')

@section('title', 'Tambah Nilai Kolektif')

@section('content')

<div class="p-6 space-y-6 bg-gray-100 min-h-screen">

@php
    $selectedSchedule = $schedules->firstWhere(
        'id',
        request('schedule_id')
    );

    $gradeExists = false;

    if (request('schedule_id')) {
        $gradeExists = \App\Models\Grade::where(
            'schedule_id',
            request('schedule_id')
        )->exists();
    }
@endphp


<!-- BREADCRUMB -->
<div class="flex items-center text-sm text-gray-500 mb-2">

    <span class="text-gray-700 font-medium">
        Dashboard
    </span>

    <span class="mx-2">/</span>

    <a href="{{ route('grades.index') }}"
       class="hover:text-blue-600">
        Nilai
    </a>

    <span class="mx-2">/</span>

    <span class="text-blue-600 font-medium">
        Tambah Nilai
    </span>

</div>


<!-- HEADER -->
<div class="bg-white border rounded-lg p-4 flex justify-between items-center">

    <div>
        <h1 class="text-xl font-bold text-blue-700">
            Tambah Nilai Kolektif
        </h1>

        <p class="text-sm text-gray-500">
            Input nilai siswa secara massal
        </p>
    </div>

    <input
        type="text"
        id="searchInput"
        placeholder="Cari siswa..."
        class="w-64 px-3 py-2 border rounded-lg text-sm"
    >

</div>


<div id="alertBox"></div>


@if($gradeExists)
<div class="mb-4 p-3 bg-yellow-100 text-yellow-700 rounded-lg">
    Nilai untuk jadwal ini sudah pernah diinput dan tidak dapat ditambahkan kembali.
</div>
@endif


<!-- FILTER -->
<form method="GET"
      id="filterForm"
      class="bg-white p-4 border rounded-lg">

    <select name="schedule_id"
            onchange="filterData()"
            class="w-full border rounded px-3 py-2 text-sm"
            required>

        <option value="">
            Pilih Jadwal
        </option>

        @foreach($schedules as $schedule)

            <option
                value="{{ $schedule->id }}"
                {{ request('schedule_id') == $schedule->id ? 'selected' : '' }}
            >
                {{ $schedule->subject->name ?? '-' }}
                -
                {{ $schedule->classModel->name ?? '-' }}
            </option>

        @endforeach

    </select>

</form>


<!-- FORM -->
<form id="formGrade"
      method="POST"
      action="{{ route('grades.store') }}">

    @csrf

    <input type="hidden"
           name="schedule_id"
           value="{{ request('schedule_id') }}">

    <input type="hidden"
           name="subject_id"
           value="{{ $selectedSchedule?->subject_id ?? '' }}">


    <div class="bg-white border rounded-lg overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3">No</th>
                    <th class="p-3">NISN</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Formatif</th>
                    <th class="p-3">STS</th>
                    <th class="p-3">SAS</th>
                </tr>
            </thead>

            <tbody id="studentTable">

            @forelse($students as $i => $student)

                <tr class="border-t">

                    <td class="p-3 text-center">
                        {{ $i + 1 }}

                        <input type="hidden"
                               name="student_id[]"
                               value="{{ $student->id }}">
                    </td>

                    <td class="p-3 text-center">
                        {{ $student->nisn }}
                    </td>

                    <td class="p-3">
                        {{ $student->name }}
                    </td>

                    <td class="p-3">
                        <input
                            type="number"
                            name="assignment_score[]"
                            min="0"
                            max="100"
                            required
                            class="w-full border rounded px-2 py-1 text-center"
                            {{ $gradeExists ? 'disabled' : '' }}
                            {{ $i === 0 ? 'id=firstInput' : '' }}>
                    </td>

                    <td class="p-3">
                        <input
                            type="number"
                            name="mid_exam_score[]"
                            min="0"
                            max="100"
                            required
                            class="w-full border rounded px-2 py-1 text-center"
                            {{ $gradeExists ? 'disabled' : '' }}>
                    </td>

                    <td class="p-3">
                        <input
                            type="number"
                            name="final_exam_score[]"
                            min="0"
                            max="100"
                            required
                            class="w-full border rounded px-2 py-1 text-center"
                            {{ $gradeExists ? 'disabled' : '' }}>
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="6"
                        class="text-center p-6 text-gray-400">
                        Tidak ada siswa
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>


    <div class="flex justify-end mt-6 gap-3">

        <a href="{{ route('grades.index') }}"
           class="px-4 py-2 border rounded-lg text-sm hover:bg-gray-100">
            Batal
        </a>

        <button
            type="submit"
            {{ (!$selectedSchedule || $gradeExists) ? 'disabled' : '' }}
            class="px-6 py-2 bg-blue-600 text-white rounded-lg text-sm
                   font-semibold hover:bg-blue-700 disabled:bg-gray-400">

            {{ $gradeExists ? 'Nilai Sudah Diinput' : 'Simpan Nilai' }}

        </button>

    </div>

</form>

</div>


<script>
function filterData() {
    document.getElementById('filterForm').submit();
}
</script>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const firstInput = document.getElementById('firstInput');
    const searchInput = document.getElementById('searchInput');

    if (firstInput) {
        firstInput.focus();
    } else {
        searchInput.focus();
    }

    $('#searchInput').on('keyup', function () {

        let value = $(this).val().toLowerCase();

        $('#studentTable tr').filter(function () {
            $(this).toggle(
                $(this).text().toLowerCase().includes(value)
            );
        });

    });

    if (typeof window.createData === 'function') {
        createData(
            '#formGrade',
            "{{ route('grades.store') }}",
            function(res) {
                $('#alertBox').html(`
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
                        ${res.message ?? 'Berhasil disimpan'}
                    </div>
                `);
            }
        );
    }

});
</script>
@endpush