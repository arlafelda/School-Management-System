@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')

<div class="space-y-6">

    <!-- TITLE -->
    <div>
        <h1 class="text-2xl font-bold">Tambah Jadwal</h1>
        <p class="text-gray-500 text-sm">Isi data jadwal pelajaran</p>
    </div>

    <!-- ALERT AJAX -->
    <div id="alertBox"></div>

    <!-- ERROR VALIDATION -->
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p>• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <!-- FORM -->
    <div class="bg-white p-6 rounded-lg shadow max-w-xl">

        <form id="formSchedule" action="{{ route('schedule.store') }}" method="POST">

            @csrf

            <!-- KELAS -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Kelas</label>
                <select name="class_id" required class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}"
                            {{ old('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }} - {{ $class->major }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- HARI -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Hari</label>
                <select name="day" required class="w-full border rounded px-3 py-2 text-sm">
                    <option value="">-- Pilih Hari --</option>
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                        <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>
                            {{ $day }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- TANGGAL -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Tanggal</label>
                <input type="date" name="date"
                       value="{{ old('date') }}"
                       class="w-full border rounded px-3 py-2 text-sm"
                       required>
            </div>

            <!-- JAM -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Jam Mulai</label>
                    <input type="time" name="start_time"
                           value="{{ old('start_time') }}"
                           class="w-full border rounded px-3 py-2 text-sm"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Jam Selesai</label>
                    <input type="time" name="end_time"
                           value="{{ old('end_time') }}"
                           class="w-full border rounded px-3 py-2 text-sm"
                           required>
                </div>
            </div>

            <!-- GURU -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Guru</label>

                <select name="teacher_id" id="teacherSelect" required
                        class="w-full border rounded px-3 py-2 text-sm">

                    <option value="">-- Pilih Guru --</option>

                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}"
                                data-subject="{{ $teacher->subject }}"
                                {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }} - {{ $teacher->subject }}
                        </option>
                    @endforeach

                </select>
            </div>

            <!-- MAPEL -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Mata Pelajaran</label>
                <input type="text" id="subjectField" readonly
                       class="w-full border rounded px-3 py-2 text-sm bg-gray-100">
            </div>

            <!-- BUTTON -->
            <div class="flex justify-between">

                <a href="{{ route('schedule.index') }}"
                   class="px-4 py-2 border rounded-lg text-sm">
                    Kembali
                </a>

                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

@endsection


@push('scripts')

<script>
/* =========================
   CREATE AJAX (ajax.js)
========================= */
window.addEventListener('load', function () {
    createData('#formSchedule', "{{ route('schedule.store') }}");
});


/* =========================
   AUTO SUBJECT (MAPEL)
========================= */
document.addEventListener("DOMContentLoaded", function () {

    const teacherSelect = document.getElementById('teacherSelect');
    const subjectField = document.getElementById('subjectField');

    if (!teacherSelect) return;

    function updateSubject() {
        let subject = teacherSelect.options[teacherSelect.selectedIndex]
            ?.getAttribute('data-subject');

        subjectField.value = subject ?? '';
    }

    teacherSelect.addEventListener('change', updateSubject);

    updateSubject(); // init value
});
</script>

@endpush