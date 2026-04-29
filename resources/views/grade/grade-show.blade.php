@extends('layouts.app')

@section('content')

<div class="p-6">

    <!-- HEADER -->
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Detail Nilai Siswa</h1>
        <p class="text-sm text-gray-500">Informasi lengkap nilai akademik</p>
    </div>

    <!-- CARD -->
    <div class="bg-white rounded-xl shadow border overflow-hidden max-w-3xl">

        <!-- HEADER CARD -->
        <div class="bg-blue-600 text-white px-6 py-4">
            <h1 class="text-lg font-semibold">Detail Nilai Siswa</h1>
            <p class="text-sm opacity-80">Informasi lengkap nilai akademik</p>
        </div>

        <!-- CONTENT -->
        <div class="p-6 space-y-6">

            <!-- PROFILE -->
            <div class="flex items-center gap-4">

                <div class="w-14 h-14 rounded-full bg-blue-600 text-white flex items-center justify-center text-xl font-bold shadow">
                    {{ strtoupper(substr($grade->student->name, 0, 1)) }}
                </div>

                <div>
                    <h2 class="font-semibold text-lg">
                        {{ $grade->student->name }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        NISN: {{ $grade->student->nisn }}
                    </p>
                </div>

            </div>

            <!-- INFO TABLE -->
            <div class="overflow-hidden border rounded-xl">

                <table class="w-full text-sm">

                    <tbody class="divide-y">

                        <tr class="bg-gray-50">
                            <td class="p-3 font-medium w-1/3">Kelas</td>
                            <td class="p-3">
                                {{ $grade->student->class->name ?? '-' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="p-3 font-medium">Jurusan</td>
                            <td class="p-3">
                                {{ $grade->student->class->major ?? '-' }}
                            </td>
                        </tr>

                        <tr class="bg-gray-50">
                            <td class="p-3 font-medium">Mata Pelajaran</td>
                            <td class="p-3">
                                {{ $grade->subject }}
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

            <!-- NILAI TABLE -->
            <div class="overflow-hidden border rounded-xl">

                <table class="w-full text-sm text-center">

                    <thead class="bg-gray-100 text-gray-600">
                        <tr>
                            <th class="p-3">Tugas</th>
                            <th class="p-3">UTS</th>
                            <th class="p-3">UAS</th>
                            <th class="p-3">Nilai Akhir</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="border-t text-lg font-semibold">

                            <td class="p-3 text-blue-600">
                                {{ $grade->assignment_score }}
                            </td>

                            <td class="p-3 text-yellow-600">
                                {{ $grade->mid_exam_score }}
                            </td>

                            <td class="p-3 text-green-600">
                                {{ $grade->final_exam_score }}
                            </td>

                            <td class="p-3 text-indigo-600">
                                {{ $grade->final_score }}
                            </td>

                        </tr>
                    </tbody>

                </table>

            </div>

            <!-- BUTTON -->
            <div class="flex justify-between items-center pt-4">

                <a href="{{ route('grades.index') }}"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg text-sm">
                    ← Kembali
                </a>

                <a href="{{ route('grades.edit', $grade->id) }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm">
                    Edit Nilai
                </a>

            </div>

        </div>

    </div>

</div>

@endsection