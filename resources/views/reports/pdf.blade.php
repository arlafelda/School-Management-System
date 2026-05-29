<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Rapot {{ $student->name }}
    </title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @media print {

            .no-print {
                display: none;
            }

            body {
                background: white;
            }

            .print-container {
                box-shadow: none !important;
                border-radius: 0 !important;
            }

        }
    </style>
</head>

<body class="bg-gray-100 py-10 px-4 text-gray-800">

    {{-- FILTER RAPOT --}}
    <div class="max-w-5xl mx-auto mb-4 no-print">

        <form
            action=""
            method="GET"
            class="flex flex-wrap gap-4 items-end"
        >

            <div>
                <label class="block text-sm font-semibold mb-1">
                    Semester
                </label>

                <select
                    name="semester"
                    class="border rounded-lg px-4 py-2"
                >
                    <option value="">
                        Pilih Semester
                    </option>

                    <option
                        value="ganjil"
                        {{ request('semester') == 'ganjil' ? 'selected' : '' }}
                    >
                        Ganjil
                    </option>

                    <option
                        value="genap"
                        {{ request('semester') == 'genap' ? 'selected' : '' }}
                    >
                        Genap
                    </option>

                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">
                    Tahun Ajaran
                </label>

                <input
                    type="text"
                    name="academic_year"
                    value="{{ request('academic_year') }}"
                    placeholder="2025/2026"
                    class="border rounded-lg px-4 py-2"
                >
            </div>

            <button
                type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl shadow"
            >
                Filter Rapot
            </button>

        </form>

    </div>


    {{-- ERROR MESSAGE --}}
    @if(isset($error))

    <div class="max-w-5xl mx-auto mb-4">

        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl">

            {{ $error }}

        </div>

    </div>

    @endif


    <div class="max-w-5xl mx-auto mb-6 flex justify-end gap-4 no-print">

    <a
        href="{{ url()->previous() }}"
        class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition duration-300 flex items-center gap-2"
    >
        ← Kembali
    </a>


        <a
            href="{{ route('students.raport.print', [
                'slug' => $student->slug,
                'semester' => request('semester'),
                'academic_year' => request('academic_year')
            ]) }}"
            target="_blank"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition duration-300 flex items-center gap-2"
        >
            🖨️ Print Rapot
        </a>

        <a
            href="{{ route('students.raport.download', [
                'slug' => $student->slug,
                'semester' => request('semester'),
                'academic_year' => request('academic_year')
            ]) }}"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition duration-300 flex items-center gap-2"
        >
            ⬇️ Download PDF
        </a>

    </div>


    <div class="max-w-5xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden print-container">

        <div class="border-b-4 border-blue-700 px-8 py-6 text-center bg-gradient-to-r from-blue-700 to-indigo-700 text-white">

            <h1 class="text-3xl font-bold tracking-wide">
                POLIWANGI
            </h1>

            <p class="text-sm mt-2">
                Jl. Raya Jember No.KM13,
                Banyuwangi,
                Jawa Timur
            </p>

            <p class="text-sm">
                Telp: 08*********
            </p>

        </div>



        <div class="p-8 space-y-8">

            <div>

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-2 h-8 bg-blue-600 rounded-full"></div>

                    <h2 class="text-xl font-bold text-blue-700">
                        Identitas Siswa
                    </h2>

                </div>


                <div class="overflow-hidden border border-gray-200 rounded-xl">

                    <table class="w-full">

                        <tbody class="divide-y divide-gray-200">

                            <tr>

                                <td class="w-1/3 px-5 py-3 font-semibold bg-gray-50">
                                    Nama
                                </td>

                                <td class="px-5 py-3">
                                    {{ $student->name }}
                                </td>

                            </tr>


                            <tr>

                                <td class="px-5 py-3 font-semibold bg-gray-50">
                                    NIS
                                </td>

                                <td class="px-5 py-3">
                                    {{ $student->nis }}
                                </td>

                            </tr>


                            <tr>

                                <td class="px-5 py-3 font-semibold bg-gray-50">
                                    NISN
                                </td>

                                <td class="px-5 py-3">
                                    {{ $student->nisn }}
                                </td>

                            </tr>


                            <tr>

                                <td class="px-5 py-3 font-semibold bg-gray-50">
                                    Kelas
                                </td>

                                <td class="px-5 py-3">
                                    {{ $student->class->name ?? '-' }}
                                </td>

                            </tr>


                            <tr>

                                <td class="px-5 py-3 font-semibold bg-gray-50">
                                    Semester
                                </td>

                                <td class="px-5 py-3">
                                    {{ request('semester') ?? $student->class->semester ?? '-' }}
                                </td>

                            </tr>


                            <tr>

                                <td class="px-5 py-3 font-semibold bg-gray-50">
                                    Tahun Ajaran
                                </td>

                                <td class="px-5 py-3">
                                    {{ request('academic_year') ?? $student->class->academic_year ?? '-' }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>



            <div>

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-2 h-8 bg-green-600 rounded-full"></div>

                    <h2 class="text-xl font-bold text-green-700">
                        Nilai Mata Pelajaran
                    </h2>

                </div>


                <div class="overflow-hidden border border-gray-200 rounded-xl">

                    <table class="w-full">

                        <thead class="bg-green-100 text-green-800">

                            <tr>

                                <th class="px-4 py-3 text-center">
                                    No
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Mata Pelajaran
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Tugas
                                </th>

                                <th class="px-4 py-3 text-center">
                                    UTS
                                </th>

                                <th class="px-4 py-3 text-center">
                                    UAS
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Nilai Akhir
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200">

                            @forelse($student->grades as $index => $grade)

                            <tr>

                                <td class="px-4 py-3 text-center">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $grade->subject->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $grade->assignment_score }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $grade->mid_exam_score }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    {{ $grade->final_exam_score }}
                                </td>

                                <td class="px-4 py-3 text-center font-bold text-green-700">
                                    {{ $grade->final_score }}
                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center px-4 py-5 text-gray-500"
                                >
                                    Belum ada data nilai
                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>



            <div>

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-2 h-8 bg-yellow-500 rounded-full"></div>

                    <h2 class="text-xl font-bold text-yellow-600">
                        Rekap Kehadiran Mata Pelajaran
                    </h2>

                </div>


                <div class="overflow-hidden border border-gray-200 rounded-xl">

                    <table class="w-full">

                        <thead class="bg-yellow-100 text-yellow-800">

                            <tr>

                                <th class="px-4 py-3 text-center">
                                    No
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Mata Pelajaran
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Masuk
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Izin
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Sakit
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Alpa
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200 bg-white">

                            @php

                                $attendanceBySubject = $student->attendances
                                    ->groupBy(function ($attendance) {
                                        return $attendance->schedule->subject->name ?? 'Tidak Ada Subject';
                                    });

                            @endphp


                            @forelse($attendanceBySubject as $subject => $attendances)

                            <tr>

                                <td class="px-4 py-3 text-center">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-4 py-3 font-semibold">
                                    {{ $subject }}
                                </td>

                                <td class="px-4 py-3 text-center text-green-700 font-bold">
                                    {{ $attendances->where('status', 'hadir')->count() }}
                                </td>

                                <td class="px-4 py-3 text-center text-blue-700 font-bold">
                                    {{ $attendances->where('status', 'izin')->count() }}
                                </td>

                                <td class="px-4 py-3 text-center text-yellow-700 font-bold">
                                    {{ $attendances->where('status', 'sakit')->count() }}
                                </td>

                                <td class="px-4 py-3 text-center text-red-700 font-bold">
                                    {{ $attendances->where('status', 'alpa')->count() }}
                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="6"
                                    class="text-center px-4 py-5 text-gray-500"
                                >
                                    Belum ada data kehadiran
                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>



            <div>

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-2 h-8 bg-purple-600 rounded-full"></div>

                    <h2 class="text-xl font-bold text-purple-700">
                        Ekstrakurikuler
                    </h2>

                </div>


                <div class="overflow-hidden border border-gray-200 rounded-xl">

                    <table class="w-full">

                        <thead class="bg-purple-100 text-purple-800">

                            <tr>

                                <th class="px-4 py-3 text-center">
                                    No
                                </th>

                                <th class="px-4 py-3 text-left">
                                    Nama Ekstrakurikuler
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200">

                            @forelse($student->extracurriculars as $i => $item)

                            <tr>

                                <td class="px-4 py-3 text-center">
                                    {{ $i + 1 }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item->name }}
                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="2"
                                    class="text-center px-4 py-5 text-gray-500"
                                >
                                    Belum ada data ekstrakurikuler
                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>



            <div>

                <div class="flex items-center gap-3 mb-4">

                    <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>

                    <h2 class="text-xl font-bold text-indigo-700">
                        Catatan Wali Kelas
                    </h2>

                </div>


                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5">

                    {{ $student->note ?? 'Pertahankan prestasi belajar dan tingkatkan kedisiplinan.' }}

                </div>

            </div>



            <div class="pt-10">

                <div class="grid grid-cols-2 gap-8 text-center">

                    <div>

                        <p class="font-semibold">
                            Wali Kelas
                        </p>

                        <div class="h-24"></div>

                        <p class="border-t border-gray-400 inline-block px-10 pt-2">

                            (
                            {{ $student->class->teacher->name ?? '....................' }}
                            )

                        </p>

                    </div>


                    <div>

                        <p class="font-semibold">
                            Kepala Sekolah
                        </p>

                        <div class="h-24"></div>

                        <p class="border-t border-gray-400 inline-block px-10 pt-2">
                            (__________________)
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>
</html>