<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapot Siswa</title>

    {{-- Tailwind CSS --}}
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

    {{-- DATA DUMMY --}}
    @php
        $student = [
            'name' => 'Arlafelda Meindra Widayat',
            'nis' => '362358302169',
            'nisn' => '1234567890',
            'class' => 'XII RPL 1',
            'semester' => 'Genap',
            'tahun_ajaran' => '2025/2026'
        ];

        $grades = [
            ['subject' => 'Matematika', 'score' => 90, 'grade' => 'A'],
            ['subject' => 'Bahasa Indonesia', 'score' => 88, 'grade' => 'A'],
            ['subject' => 'Bahasa Inggris', 'score' => 85, 'grade' => 'B'],
            ['subject' => 'Pemrograman Web', 'score' => 95, 'grade' => 'A'],
            ['subject' => 'Basis Data', 'score' => 92, 'grade' => 'A'],
        ];

        $attendance = [
            'hadir' => 20,
            'izin' => 2,
            'sakit' => 1,
            'alpha' => 0
        ];

        $extracurricular = [
            'Basket',
            'Pramuka'
        ];
    @endphp


    {{-- TOMBOL CETAK --}}
    <div class="max-w-5xl mx-auto mb-6 flex justify-end no-print">

        <button
            onclick="window.print()"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl shadow-lg transition duration-300 flex items-center gap-2"
        >
            🖨️ Cetak PDF
        </button>

    </div>


    <div class="max-w-5xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden print-container">

        {{-- HEADER SEKOLAH --}}
        <div class="border-b-4 border-blue-700 px-8 py-6 text-center bg-gradient-to-r from-blue-700 to-indigo-700 text-white">
            <h1 class="text-3xl font-bold tracking-wide">
                POLIWANGI
            </h1>

            <p class="text-sm mt-2">
                Jl. Raya Jember No.KM13, Kawang, Labanasem, Kec. Kabat, Kabupaten Banyuwangi, Jawa Timur 68461
            </p>

            <p class="text-sm">
                Telp: 08*********
            </p>
        </div>


        <div class="p-8 space-y-8">

            {{-- IDENTITAS SISWA --}}
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
                                    {{ $student['name'] }}
                                </td>
                            </tr>

                            <tr>
                                <td class="px-5 py-3 font-semibold bg-gray-50">
                                    NIS
                                </td>

                                <td class="px-5 py-3">
                                    {{ $student['nis'] }}
                                </td>
                            </tr>

                            <tr>
                                <td class="px-5 py-3 font-semibold bg-gray-50">
                                    NISN
                                </td>

                                <td class="px-5 py-3">
                                    {{ $student['nisn'] }}
                                </td>
                            </tr>

                            <tr>
                                <td class="px-5 py-3 font-semibold bg-gray-50">
                                    Kelas
                                </td>

                                <td class="px-5 py-3">
                                    {{ $student['class'] }}
                                </td>
                            </tr>

                            <tr>
                                <td class="px-5 py-3 font-semibold bg-gray-50">
                                    Semester
                                </td>

                                <td class="px-5 py-3">
                                    {{ $student['semester'] }}
                                </td>
                            </tr>

                            <tr>
                                <td class="px-5 py-3 font-semibold bg-gray-50">
                                    Tahun Ajaran
                                </td>

                                <td class="px-5 py-3">
                                    {{ $student['tahun_ajaran'] }}
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>


            {{-- NILAI --}}
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
                                    Nilai
                                </th>

                                <th class="px-4 py-3 text-center">
                                    Predikat
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">

                            @foreach($grades as $index => $grade)
                            <tr>

                                <td class="px-4 py-3 text-center">
                                    {{ $index + 1 }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $grade['subject'] }}
                                </td>

                                <td class="px-4 py-3 text-center font-semibold">
                                    {{ $grade['score'] }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <span class="px-3 py-1 text-sm font-bold rounded-full
                                        {{ $grade['grade'] == 'A'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $grade['grade'] }}
                                    </span>
                                </td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>


            {{-- ABSENSI --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-8 bg-yellow-500 rounded-full"></div>

                    <h2 class="text-xl font-bold text-yellow-600">
                        Rekap Kehadiran
                    </h2>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    <div class="bg-green-100 rounded-xl p-5 text-center">
                        <h3 class="text-sm font-semibold text-green-700">
                            Hadir
                        </h3>

                        <p class="text-3xl font-bold text-green-800 mt-2">
                            {{ $attendance['hadir'] }}
                        </p>
                    </div>

                    <div class="bg-blue-100 rounded-xl p-5 text-center">
                        <h3 class="text-sm font-semibold text-blue-700">
                            Izin
                        </h3>

                        <p class="text-3xl font-bold text-blue-800 mt-2">
                            {{ $attendance['izin'] }}
                        </p>
                    </div>

                    <div class="bg-yellow-100 rounded-xl p-5 text-center">
                        <h3 class="text-sm font-semibold text-yellow-700">
                            Sakit
                        </h3>

                        <p class="text-3xl font-bold text-yellow-800 mt-2">
                            {{ $attendance['sakit'] }}
                        </p>
                    </div>

                    <div class="bg-red-100 rounded-xl p-5 text-center">
                        <h3 class="text-sm font-semibold text-red-700">
                            Alpha
                        </h3>

                        <p class="text-3xl font-bold text-red-800 mt-2">
                            {{ $attendance['alpha'] }}
                        </p>
                    </div>

                </div>
            </div>


            {{-- EKSTRAKURIKULER --}}
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

                            @foreach($extracurricular as $i => $item)
                            <tr>

                                <td class="px-4 py-3 text-center">
                                    {{ $i + 1 }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $item }}
                                </td>

                            </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>


            {{-- CATATAN --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>

                    <h2 class="text-xl font-bold text-indigo-700">
                        Catatan Wali Kelas
                    </h2>
                </div>

                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-5">
                    Pertahankan prestasi belajar dan tingkatkan kedisiplinan.
                </div>
            </div>


            {{-- TANDA TANGAN --}}
            <div class="pt-10">
                <div class="grid grid-cols-2 gap-8 text-center">

                    <div>
                        <p class="font-semibold">
                            Wali Kelas
                        </p>

                        <div class="h-24"></div>

                        <p class="border-t border-gray-400 inline-block px-10 pt-2">
                            (__________________)
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