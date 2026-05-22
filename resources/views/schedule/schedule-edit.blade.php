@extends('layouts.app')

@section('title', 'Edit Jadwal')

@section('content')

<div class="min-h-screen bg-gray-100 p-6">

    {{-- BREADCRUMB --}}
    <nav class="flex items-center text-sm text-gray-500 mb-6">

        <a href="{{ route('dashboard') }}"
           class="hover:text-blue-600 transition">

            Dashboard

        </a>

        <span class="mx-2">/</span>

        <a href="{{ route('schedule.index') }}"
           class="hover:text-blue-600 transition">

            Jadwal

        </a>

        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            Edit Jadwal
        </span>

    </nav>

    {{-- ALERT --}}
    <div id="alertBox"></div>

    {{-- HEADER --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Edit Jadwal
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Perbarui data jadwal pelajaran
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 max-w-2xl overflow-hidden">

        {{-- CARD HEADER --}}
        <div class="px-6 py-4 border-b bg-gray-50">

            <h2 class="font-semibold text-gray-700">
                Form Edit Jadwal
            </h2>

        </div>

        {{-- FORM --}}
        <form id="formEditSchedule"
              class="p-6 space-y-5">

            @csrf
            @method('PUT')

            {{-- CLASS --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kelas
                </label>

                <select name="class_id"
                        required
                        class="
                            w-full
                            border border-gray-300
                            rounded-lg
                            px-4 py-2.5
                            text-sm
                            focus:ring-2 focus:ring-blue-500
                            focus:border-blue-500
                        ">

                    <option value="">
                        -- Pilih Kelas --
                    </option>

                    @foreach($classes as $class)

                        <option value="{{ $class->id }}"
                            {{ old('class_id', $schedule->class_id) == $class->id ? 'selected' : '' }}>

                            {{ $class->name }} - {{ $class->major }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- DAY --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Hari
                </label>

                <select name="day"
                        required
                        class="
                            w-full
                            border border-gray-300
                            rounded-lg
                            px-4 py-2.5
                            text-sm
                            focus:ring-2 focus:ring-blue-500
                            focus:border-blue-500
                        ">

                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)

                        <option value="{{ $day }}"
                            {{ old('day', $schedule->day) == $day ? 'selected' : '' }}>

                            {{ $day }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- TIME --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- START TIME --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Mulai
                    </label>

                    <input type="time"
                           name="start_time"
                           value="{{ old('start_time', $schedule->start_time) }}"
                           required
                           class="
                                w-full
                                border border-gray-300
                                rounded-lg
                                px-4 py-2.5
                                text-sm
                                focus:ring-2 focus:ring-blue-500
                                focus:border-blue-500
                           ">

                </div>

                {{-- END TIME --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Selesai
                    </label>

                    <input type="time"
                           name="end_time"
                           value="{{ old('end_time', $schedule->end_time) }}"
                           required
                           class="
                                w-full
                                border border-gray-300
                                rounded-lg
                                px-4 py-2.5
                                text-sm
                                focus:ring-2 focus:ring-blue-500
                                focus:border-blue-500
                           ">

                </div>

            </div>

            {{-- TEACHER --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Guru
                </label>

                <select id="teacherSelect"
                        class="
                            w-full
                            border border-gray-300
                            rounded-lg
                            px-4 py-2.5
                            text-sm
                            focus:ring-2 focus:ring-blue-500
                            focus:border-blue-500
                        "
                        required>

                    <option value="">
                        -- Pilih Guru --
                    </option>

                    @foreach($teachers as $teacher)

                        <option value="{{ $teacher->id }}"
                            {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>

                            {{ $teacher->name }}

                        </option>

                    @endforeach

                </select>

            </div>

            {{-- SUBJECT --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Mata Pelajaran
                </label>

                <select name="subject_id"
                        id="subjectSelect"
                        required
                        class="
                            w-full
                            border border-gray-300
                            rounded-lg
                            px-4 py-2.5
                            text-sm
                            focus:ring-2 focus:ring-blue-500
                            focus:border-blue-500
                        ">

                    <option value="">
                        -- Pilih Mata Pelajaran --
                    </option>

                </select>

            </div>

            {{-- HIDDEN TEACHER --}}
            <input type="hidden"
                   name="teacher_id"
                   id="teacher_id"
                   value="{{ old('teacher_id', $schedule->teacher_id) }}">

            {{-- BUTTON --}}
            <div class="flex items-center justify-end gap-3 pt-4">

                {{-- BACK --}}
                <a href="{{ route('schedule.index') }}"
                   class="
                        px-4 py-2
                        border border-gray-300
                        rounded-lg
                        text-sm
                        text-gray-700
                        hover:bg-gray-100
                        transition
                   ">

                    Kembali

                </a>

                {{-- SUBMIT --}}
                <button type="submit"
                        class="
                            px-5 py-2
                            bg-blue-600 hover:bg-blue-700
                            text-white
                            rounded-lg
                            text-sm
                            transition
                        ">

                    Update

                </button>

            </div>

        </form>

    </div>

</div>

{{-- SAFE JSON --}}
<script id="teachers-data" type="application/json">
@json($teachers)
</script>

@endsection

@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =====================
       AUTO FOCUS
    ===================== */
    const firstField = document.querySelector(
        '#formEditSchedule select, #formEditSchedule input'
    );

    if (firstField) {

        firstField.focus();

    }

    /* =====================
       JSON DATA
    ===================== */
    const teachers = JSON.parse(
        document.getElementById('teachers-data').textContent
    );

    const teacherSelect = document.getElementById('teacherSelect');
    const subjectSelect = document.getElementById('subjectSelect');
    const teacherIdInput = document.getElementById('teacher_id');

    const selectedTeacherId =
        "{{ old('teacher_id', $schedule->teacher_id) }}";

    const selectedSubjectId =
        "{{ old('subject_id', $schedule->subject_id) }}";

    /* =====================
       RESET SUBJECT
    ===================== */
    function resetSubject() {

        subjectSelect.innerHTML =
            '<option value="">-- Pilih Mata Pelajaran --</option>';

    }

    /* =====================
       LOAD SUBJECT
    ===================== */
    function loadSubjects(teacherId) {

        resetSubject();

        const teacher = teachers.find(function (t) {

            return String(t.id) === String(teacherId);

        });

        if (!teacher || !teacher.subjects) {
            return;
        }

        teacher.subjects.forEach(function (subject) {

            const option = document.createElement('option');

            option.value = subject.id;
            option.textContent = subject.name;

            if (String(subject.id) === String(selectedSubjectId)) {

                option.selected = true;

            }

            subjectSelect.appendChild(option);

        });

    }

    /* =====================
       CHANGE TEACHER
    ===================== */
    teacherSelect.addEventListener('change', function () {

        const teacherId = this.value;

        teacherIdInput.value = teacherId;

        loadSubjects(teacherId);

    });

    /* =====================
       INITIAL LOAD
    ===================== */
    teacherSelect.value = selectedTeacherId;

    loadSubjects(selectedTeacherId);

    /* =====================
       AJAX UPDATE
    ===================== */
    if (typeof updateData !== 'undefined') {

        updateData(
            '#formEditSchedule',
            "{{ route('schedule.update', $schedule->id) }}",
            {

                onSuccess: function (res) {

                    document.getElementById('alertBox').innerHTML = `
                        <div class="mb-4 p-4 rounded-lg bg-green-100 text-green-700 border border-green-200">
                            ${res.message ?? 'Data berhasil diupdate'}
                        </div>
                    `;

                }

            }
        );

    } else {

        console.warn('updateData belum tersedia');

    }

});

</script>

@endpush