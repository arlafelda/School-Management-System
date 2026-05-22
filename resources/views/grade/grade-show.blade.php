@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- BREADCRUMB -->
    <div class="mb-4 text-sm text-gray-500">
        <span class="text-gray-700 font-medium">
            Dashboard
        </span>

        <span class="mx-2">/</span>

        <a href="{{ route('grades.index') }}"
            class="hover:text-blue-600">
            Nilai
        </a>

        <span class="mx-2">/</span>

        <span class="text-gray-700 font-medium">
            Detail Nilai
        </span>
    </div>


    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Detail Nilai Siswa
        </h1>

        <p class="text-sm text-gray-500">
            Informasi lengkap nilai akademik siswa
        </p>
    </div>


    @php
        $tugas = $grade->assignment_score ?? 0;
        $uts   = $grade->mid_exam_score ?? 0;
        $uas   = $grade->final_exam_score ?? 0;

        $final = ($tugas + $uts + $uas) / 3;

        if ($final >= 90) {
            $gradeLabel = 'Sangat Baik';
            $gradeColor = 'text-green-600';
        } elseif ($final >= 75) {
            $gradeLabel = 'Baik';
            $gradeColor = 'text-blue-600';
        } elseif ($final >= 60) {
            $gradeLabel = 'Cukup';
            $gradeColor = 'text-yellow-600';
        } else {
            $gradeLabel = 'Perlu Perbaikan';
            $gradeColor = 'text-red-600';
        }
    @endphp


    <!-- CARD -->
    <div class="bg-white rounded-xl shadow border overflow-hidden max-w-4xl">

        <!-- HEADER CARD -->
        <div class="bg-blue-600 text-white px-6 py-5">
            <h1 class="text-xl font-semibold">
                Detail Nilai Siswa
            </h1>

            <p class="text-sm opacity-80">
                Informasi lengkap hasil akademik siswa
            </p>
        </div>


        <!-- CONTENT -->
        <div class="p-6 space-y-6">

            <!-- PROFILE -->
            <div class="flex items-center gap-4">

                <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold shadow">
                    {{ strtoupper(substr($grade->student->name ?? 'S', 0, 1)) }}
                </div>

                <div>
                    <h2 class="font-semibold text-xl text-gray-800">
                        {{ $grade->student->name ?? '-' }}
                    </h2>

                    <p class="text-sm text-gray-500">
                        NISN: {{ $grade->student->nisn ?? '-' }}
                    </p>

                    <p class="text-sm text-gray-500">
                        Kelas:
                        {{ $grade->student->class->name ?? '-' }}
                    </p>
                </div>

            </div>


            <!-- INFO TABLE -->
            <div class="overflow-hidden border rounded-xl">

                <table class="w-full text-sm">
                    <tbody class="divide-y">

                        <tr class="bg-gray-50">
                            <td class="p-3 font-medium w-1/3">
                                Jurusan
                            </td>

                            <td class="p-3">
                                {{ $grade->student->class->major ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3 font-medium">
                                Mata Pelajaran
                            </td>

                            <td class="p-3">
                                {{ $grade->schedule->subject->name ?? '-' }}
                            </td>
                        </tr>

                        <tr class="bg-gray-50">
                            <td class="p-3 font-medium">
                                Guru
                            </td>

                            <td class="p-3">
                                {{ $grade->schedule->teacher->name ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3 font-medium">
                                Tahun Ajaran
                            </td>

                            <td class="p-3">
                                {{ $grade->student->class->academic_year ?? '-' }}
                            </td>
                        </tr>

                        <tr class="bg-gray-50">
                            <td class="p-3 font-medium">
                                Semester
                            </td>

                            <td class="p-3">
                                {{ $grade->student->class->semester ?? '-' }}
                            </td>
                        </tr>

                    </tbody>
                </table>

            </div>


            <!-- NILAI TABLE -->
            <div class="overflow-hidden border rounded-xl">

                <table class="w-full text-sm">

                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="p-3 text-right">Tugas</th>
                            <th class="p-3 text-right">UTS</th>
                            <th class="p-3 text-right">UAS</th>
                            <th class="p-3 text-right">Nilai Akhir</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="border-t text-lg font-semibold">

                            <td class="p-4 text-blue-600 text-right">
                                {{ number_format($tugas) }}
                            </td>

                            <td class="p-4 text-yellow-600 text-right">
                                {{ number_format($uts) }}
                            </td>

                            <td class="p-4 text-green-600 text-right">
                                {{ number_format($uas) }}
                            </td>

                            <td class="p-4 text-indigo-600 text-right">
                                {{ number_format($final, 1) }}
                            </td>

                        </tr>
                    </tbody>

                </table>

            </div>


            <!-- STATUS -->
            <div class="bg-gray-50 border rounded-xl p-5">

                <div class="flex justify-between items-center">

                    <div>
                        <p class="text-sm text-gray-500">
                            Status Akademik
                        </p>

                        <h3 class="text-lg font-bold {{ $gradeColor }}">
                            {{ $gradeLabel }}
                        </h3>
                    </div>

                    <div class="text-right">
                        <p class="text-sm text-gray-500">
                            Rata-rata Nilai
                        </p>

                        <h2 class="text-3xl font-bold text-blue-700">
                            {{ number_format($final, 1) }}
                        </h2>
                    </div>

                </div>

            </div>


            <!-- BUTTON -->
            <div class="flex justify-between items-center pt-4 border-t">

                <a href="{{ route('grades.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">
                    ← Kembali
                </a>

                @if(in_array(auth()->user()->role, ['super_admin','admin','teacher']))
                    <a href="{{ route('grades.edit', $grade->id) }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm shadow">
                        Edit Nilai
                    </a>
                @endif

            </div>

        </div>
    </div>
</div>

@endsection