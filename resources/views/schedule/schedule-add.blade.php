@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')

<div class="min-h-screen bg-gray-100 p-6">

    {{-- BREADCRUMB --}}
    <div class="flex items-center text-sm text-gray-500 mb-4">

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
            Tambah Jadwal
        </span>

    </div>

    {{-- TITLE --}}
    <div class="mb-6">

        <h1 class="text-2xl font-bold text-gray-800">
            Tambah Jadwal
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Tambahkan data jadwal pelajaran baru
        </p>

    </div>

    {{-- CARD --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 max-w-2xl overflow-hidden">

        {{-- HEADER --}}
        <div class="px-6 py-4 border-b bg-gray-50">

            <h2 class="font-semibold text-gray-700">
                Form Tambah Jadwal
            </h2>

        </div>

        {{-- FORM --}}
        <form id="formSchedule"
              class="p-6 space-y-5">

            @csrf

            {{-- CLASS --}}
            <div>

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Kelas
                </label>

                <select name="class_id"
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
                        -- pilih kelas --
                    </option>

                    @foreach($classes as $class)

                        <option value="{{ $class->id }}">
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
                        -- pilih hari --
                    </option>

                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)

                        <option value="{{ $day }}">
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

                </div>

                {{-- END TIME --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jam Selesai
                    </label>

                    <input type="time"
                           name="end_time"
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
                        -- pilih guru --
                    </option>

                    @foreach($teachers as $teacher)

                        <option value="{{ $teacher->id }}">
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
                        -- pilih mapel --
                    </option>

                </select>

            </div>

            {{-- HIDDEN TEACHER --}}
            <input type="hidden"
                   name="teacher_id"
                   id="teacher_id">

            {{-- BUTTON --}}
            <div class="flex items-center justify-end gap-3 pt-4">

                {{-- BACK --}}
                <a href="{{ route('schedule.index') }}"
                   class="
                        px-4 py-2
                        border border-gray-300
                        rounded-lg
                        text-sm
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

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

{{-- SAFE JSON --}}
<script id="teachers-data" type="application/json">
@json($teachers)
</script>

{{-- SCRIPT --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    /* =====================
       GET JSON DATA
    ===================== */
    const teachers = JSON.parse(
        document.getElementById('teachers-data').textContent
    );

    const teacherSelect = document.getElementById('teacherSelect');
    const subjectSelect = document.getElementById('subjectSelect');
    const teacherIdInput = document.getElementById('teacher_id');

    /* =====================
       RESET SUBJECT
    ===================== */
    function resetSubject() {

        subjectSelect.innerHTML =
            '<option value="">-- pilih mapel --</option>';

    }

    /* =====================
       CHANGE TEACHER
    ===================== */
    teacherSelect.addEventListener('change', function () {

        const teacherId = this.value;

        teacherIdInput.value = teacherId;

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

            subjectSelect.appendChild(option);

        });

    });

    /* =====================
       INITIAL RESET
    ===================== */
    resetSubject();

    /* =====================
       AJAX CREATE
    ===================== */
    if (typeof createData !== 'undefined') {

        createData(
            '#formSchedule',
            "{{ route('schedule.store') }}",
            {

                onSuccess: function () {

                    document.getElementById('formSchedule').reset();

                    resetSubject();

                    teacherIdInput.value = '';

                }

            }
        );

    } else {

        console.error('createData belum tersedia');

    }

});

</script>

@endsection