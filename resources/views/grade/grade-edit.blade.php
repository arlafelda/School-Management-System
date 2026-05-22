@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- BREADCRUMB -->
    <div class="flex items-center text-sm text-gray-500 mb-4">

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
            Edit Nilai
        </span>

    </div>


    <!-- HEADER -->
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Edit Nilai Siswa
        </h1>

        <p class="text-sm text-gray-500">
            Perbarui nilai akademik siswa sesuai mata pelajaran
        </p>

    </div>


    <!-- ALERT -->
    <div id="alertBox" class="mb-4"></div>


    <!-- CARD -->
    <div class="bg-white rounded-xl shadow border max-w-3xl">

        <!-- FORM HEADER -->
        <div class="px-6 py-4 border-b bg-gray-50">

            <h2 class="text-lg font-semibold text-gray-800">
                Form Edit Nilai
            </h2>

        </div>


        <!-- FORM -->
        <form
            id="formEditGrade"
            method="POST"
            action="{{ route('grades.update', $grade->id) }}"
            class="p-6 space-y-5">

            @csrf
            @method('PUT')

            <input
                type="hidden"
                name="schedule_id"
                value="{{ $grade->schedule_id }}">

            <input
                type="hidden"
                name="subject_id"
                value="{{ $grade->subject_id }}">


            <!-- DATA SISWA -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Nama Siswa
                    </label>

                    <input
                        type="text"
                        value="{{ $grade->student->name ?? '-' }}"
                        disabled
                        class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100 text-gray-600">
                </div>


                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Kelas
                    </label>

                    <input
                        type="text"
                        value="{{ $grade->student->class->name ?? '-' }}"
                        disabled
                        class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100 text-gray-600">
                </div>

            </div>


            <!-- SUBJECT -->
            <div>

                <label class="text-sm font-medium text-gray-600">
                    Mata Pelajaran
                </label>

                <input
                    type="text"
                    value="{{ $grade->schedule->subject->name ?? '-' }}"
                    disabled
                    class="w-full mt-1 px-3 py-2 border rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">

            </div>


            <!-- NILAI -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                <div>
                    <label class="text-sm font-medium text-gray-600">
                        Tugas
                    </label>

                    <input
                        type="number"
                        id="firstInput"
                        name="assignment_score"
                        min="0"
                        max="100"
                        value="{{ $grade->assignment_score }}"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>


                <div>
                    <label class="text-sm font-medium text-gray-600">
                        UTS
                    </label>

                    <input
                        type="number"
                        name="mid_exam_score"
                        min="0"
                        max="100"
                        value="{{ $grade->mid_exam_score }}"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-yellow-400">
                </div>


                <div>
                    <label class="text-sm font-medium text-gray-600">
                        UAS
                    </label>

                    <input
                        type="number"
                        name="final_exam_score"
                        min="0"
                        max="100"
                        value="{{ $grade->final_exam_score }}"
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-400">
                </div>

            </div>


            <!-- RATA-RATA -->
            @php
                $final =
                    (($grade->assignment_score ?? 0) +
                    ($grade->mid_exam_score ?? 0) +
                    ($grade->final_exam_score ?? 0)) / 3;
            @endphp

            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">

                <p class="text-sm text-gray-500">
                    Nilai Rata-rata
                </p>

                <h3 class="text-2xl font-bold text-blue-700">
                    {{ number_format($final, 1) }}
                </h3>

            </div>


            <!-- BUTTON -->
            <div class="flex justify-between items-center pt-4 border-t">

                <a href="{{ route('grades.index') }}"
                    class="text-sm text-gray-600 hover:text-gray-800">
                    ← Kembali
                </a>

                <button
                    type="submit"
                    class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm shadow">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const firstInput =
        document.getElementById('firstInput');

    if (firstInput) {
        firstInput.focus();
    }

    document
        .querySelectorAll('input[type="number"]')
        .forEach(function(input) {

            input.addEventListener('input', function() {

                if (this.value > 100) {
                    this.value = 100;
                }

                if (this.value < 0) {
                    this.value = 0;
                }

            });

        });

    if (typeof window.$ === 'undefined') {
        console.error('jQuery belum load');
        return;
    }

    if (typeof window.updateData === 'function') {

        updateData(
            '#formEditGrade',
            "{{ route('grades.update', $grade->id) }}",
            function(res) {

                $('#alertBox').html(`
                    <div class="p-3 bg-green-100 text-green-700 rounded-lg mb-4">
                        ${res.message ?? 'Nilai berhasil diperbarui'}
                    </div>
                `);

            }
        );

    } else {
        console.error(
            'updateData tidak ditemukan (cek ajax.js)'
        );
    }

});
</script>
@endpush