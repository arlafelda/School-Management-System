@extends('layouts.app')

@section('title', 'Tambah Jadwal')

@section('content')

<div class="min-h-screen bg-gray-50 text-gray-800">

    {{-- BREADCRUMB --}}
    <div class="px-6 pt-5 flex items-center gap-1.5 text-sm text-gray-400">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.75L12 3l9 6.75V21a.75.75 0 01-.75.75H15a.75.75 0 01-.75-.75v-4.5h-4.5V21a.75.75 0 01-.75.75H3.75A.75.75 0 013 21V9.75z" />
        </svg>
        <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <a href="{{ route('schedule.index') }}" class="hover:text-blue-600 transition-colors">Jadwal</a>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-gray-600 font-medium">Tambah</span>
    </div>

    {{-- PAGE HEADER --}}
    <header class="px-6 pt-4 pb-5">
        <h1 class="text-xl font-semibold text-gray-800">Tambah jadwal</h1>
        <p class="text-sm text-gray-400 mt-0.5">Tambahkan data jadwal pelajaran baru ke sistem.</p>
    </header>

    {{-- CONTENT --}}
    <main class="px-6 pb-10">

        {{-- ALERT AJAX --}}
        <div id="alertBox" class="max-w-2xl mx-auto mb-4"></div>

        <div class="max-w-2xl mx-auto bg-white border border-gray-200 rounded-xl shadow-sm p-6">

            {{-- SECTION LABEL --}}
            <p class="text-xs font-semibold tracking-widest text-gray-400 uppercase mb-5">
                Informasi jadwal
            </p>

            {{-- FORM --}}
            <form id="formSchedule" class="space-y-5">
                @csrf

                {{-- KELAS & HARI --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                    {{-- Kelas --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Kelas <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <select name="class_id" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="">-- Pilih kelas --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">
                                    {{ $class->name }} — {{ $class->major }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Hari --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Hari <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <select name="day" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="">-- Pilih hari --</option>
                            @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- JAM MULAI & SELESAI --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                    {{-- Jam Mulai --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Jam mulai <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <input type="time" name="start_time" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>

                    {{-- Jam Selesai --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Jam selesai <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <input type="time" name="end_time" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                      focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                    </div>

                </div>

                {{-- GURU & MATA PELAJARAN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                    {{-- Guru --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Guru <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <select id="teacherSelect" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="">-- Pilih guru --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Mata Pelajaran --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-sm font-medium text-gray-700">
                            Mata pelajaran <span class="text-red-500 ml-0.5">*</span>
                        </label>
                        <select name="subject_id" id="subjectSelect" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800
                                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition bg-white">
                            <option value="">-- Pilih guru dulu --</option>
                        </select>
                        <span class="text-xs text-gray-400" id="subjectHint">
                            Pilih guru terlebih dahulu untuk memuat mata pelajaran.
                        </span>
                    </div>

                </div>

                {{-- HIDDEN TEACHER --}}
                <input type="hidden" name="teacher_id" id="teacher_id">

                {{-- DIVIDER --}}
                <hr class="border-gray-100">

                {{-- FOOTER --}}
                <div class="flex items-center justify-between pt-1">
                    <p class="text-xs text-gray-400">
                        <span class="text-red-500">*</span> Wajib diisi
                    </p>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('schedule.index') }}"
                           class="px-4 py-2 text-sm border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition">
                            Kembali
                        </a>
                        <button type="submit"
                                class="flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V7l-4-4z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 3v4H7V3M12 12v6m-3-3h6" />
                            </svg>
                            Simpan
                        </button>
                    </div>
                </div>

            </form>

        </div>

    </main>

</div>

{{-- SAFE JSON --}}
<script id="teachers-data" type="application/json">
@json($teachers)
</script>

{{-- SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const teachers      = JSON.parse(document.getElementById('teachers-data').textContent);
    const teacherSelect = document.getElementById('teacherSelect');
    const subjectSelect = document.getElementById('subjectSelect');
    const teacherInput  = document.getElementById('teacher_id');
    const subjectHint   = document.getElementById('subjectHint');

    function resetSubject(placeholder = '-- Pilih guru dulu --') {
        subjectSelect.innerHTML = `<option value="">${placeholder}</option>`;
        subjectHint.textContent = 'Pilih guru terlebih dahulu untuk memuat mata pelajaran.';
    }

    teacherSelect.addEventListener('change', function () {
        const teacherId = this.value;
        teacherInput.value = teacherId;
        resetSubject('-- Pilih mata pelajaran --');

        const teacher = teachers.find(t => String(t.id) === String(teacherId));

        if (!teacher || !teacher.subjects || teacher.subjects.length === 0) {
            subjectHint.textContent = 'Guru ini belum memiliki mata pelajaran.';
            return;
        }

        teacher.subjects.forEach(function (subject) {
            const opt = document.createElement('option');
            opt.value = subject.id;
            opt.textContent = subject.name;
            subjectSelect.appendChild(opt);
        });

        subjectHint.textContent = `${teacher.subjects.length} mata pelajaran tersedia.`;
    });

    resetSubject();

    if (typeof createData !== 'undefined') {
        createData(
            '#formSchedule',
            "{{ route('schedule.store') }}",
            {
                onSuccess: function () {
                    document.getElementById('alertBox').innerHTML = `
                        <div class="flex items-center gap-2 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Jadwal berhasil disimpan.
                        </div>
                    `;
                    document.getElementById('formSchedule').reset();
                    resetSubject();
                    teacherInput.value = '';
                }
            }
        );
    } else {
        console.error('createData belum tersedia');
    }

});
</script>

@endsection