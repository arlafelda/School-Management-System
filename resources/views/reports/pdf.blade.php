<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapot {{ $student->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .print-container { box-shadow: none !important; border-radius: 0 !important; }
        }

        /* Tabel scroll horizontal di mobile */
        .table-wrapper {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Kolom tabel tidak terlalu sempit */
        .table-wrapper table {
            min-width: 480px;
        }

        /* Tabel identitas siswa tidak perlu scroll */
        .table-identity {
            min-width: unset;
        }
    </style>
</head>

<body class="bg-gray-100 py-6 px-3 sm:py-10 sm:px-4 text-gray-800">

    {{-- FILTER RAPOT --}}
    <div class="max-w-5xl mx-auto mb-4 no-print">
        <form action="" method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4 items-stretch sm:items-end">

            <div class="flex-1 min-w-0">
                <label class="block text-sm font-semibold mb-1">Semester</label>
                <select name="semester" class="border rounded-lg px-3 py-2 w-full">
                    <option value="">Pilih Semester</option>
                    <option value="ganjil" {{ request('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="genap" {{ request('semester') == 'genap' ? 'selected' : '' }}>Genap</option>
                </select>
            </div>

            <div class="flex-1 min-w-0">
                <label class="block text-sm font-semibold mb-1">Tahun Ajaran</label>
                <input
                    type="text"
                    name="academic_year"
                    value="{{ request('academic_year') }}"
                    placeholder="2025/2026"
                    class="border rounded-lg px-3 py-2 w-full"
                >
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl shadow w-full sm:w-auto">
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


    {{-- TOMBOL AKSI --}}
    <div class="max-w-5xl mx-auto mb-6 flex flex-col sm:flex-row justify-end gap-3 no-print">

        <a
            href="{{ url()->previous() }}"
            class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-5 py-3 rounded-xl shadow-lg transition duration-300 flex items-center justify-center gap-2 text-sm sm:text-base"
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
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-3 rounded-xl shadow-lg transition duration-300 flex items-center justify-center gap-2 text-sm sm:text-base"
        >
            🖨️ Print Rapot
        </a>

        <a
            href="{{ route('students.raport.download', [
                'slug' => $student->slug,
                'semester' => request('semester'),
                'academic_year' => request('academic_year')
            ]) }}"
            class="bg-green-600 hover:bg-green-700 text-white font-semibold px-5 py-3 rounded-xl shadow-lg transition duration-300 flex items-center justify-center gap-2 text-sm sm:text-base"
        >
            ⬇️ Download PDF
        </a>

    </div>


    <div class="max-w-5xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden print-container">

        {{-- HEADER --}}
        <div class="border-b-4 border-blue-700 px-4 sm:px-8 py-5 sm:py-6 text-center bg-gradient-to-r from-blue-700 to-indigo-700 text-white">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-wide">POLIWANGI</h1>
            <p class="text-xs sm:text-sm mt-2">Jl. Raya Jember No.KM13, Banyuwangi, Jawa Timur</p>
            <p class="text-xs sm:text-sm">Telp: 08*********</p>
        </div>


        <div class="p-4 sm:p-8 space-y-6 sm:space-y-8">

            {{-- IDENTITAS SISWA --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                    <h2 class="text-lg sm:text-xl font-bold text-blue-700">Identitas Siswa</h2>
                </div>

                <div class="overflow-hidden border border-gray-200 rounded-xl">
                    <table class="w-full table-identity">
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="w-2/5 sm:w-1/3 px-3 sm:px-5 py-3 font-semibold bg-gray-50 text-sm sm:text-base">Nama</td>
                                <td class="px-3 sm:px-5 py-3 text-sm sm:text-base">{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 sm:px-5 py-3 font-semibold bg-gray-50 text-sm sm:text-base">NIS</td>
                                <td class="px-3 sm:px-5 py-3 text-sm sm:text-base">{{ $student->nis }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 sm:px-5 py-3 font-semibold bg-gray-50 text-sm sm:text-base">NISN</td>
                                <td class="px-3 sm:px-5 py-3 text-sm sm:text-base">{{ $student->nisn }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 sm:px-5 py-3 font-semibold bg-gray-50 text-sm sm:text-base">Kelas</td>
                                <td class="px-3 sm:px-5 py-3 text-sm sm:text-base">{{ $student->class->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 sm:px-5 py-3 font-semibold bg-gray-50 text-sm sm:text-base">Semester</td>
                                <td class="px-3 sm:px-5 py-3 text-sm sm:text-base">{{ request('semester') ?? $student->class->semester ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 sm:px-5 py-3 font-semibold bg-gray-50 text-sm sm:text-base">Tahun Ajaran</td>
                                <td class="px-3 sm:px-5 py-3 text-sm sm:text-base">{{ request('academic_year') ?? $student->class->academic_year ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            {{-- NILAI MATA PELAJARAN --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-8 bg-green-600 rounded-full"></div>
                    <h2 class="text-lg sm:text-xl font-bold text-green-700">Nilai Mata Pelajaran</h2>
                </div>

                <div class="table-wrapper border border-gray-200 rounded-xl overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-green-100 text-green-800">
                            <tr>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">No</th>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm">Mata Pelajaran</th>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">Tugas</th>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">UTS</th>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">UAS</th>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($student->grades as $index => $grade)
                            <tr>
                                <td class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">{{ $index + 1 }}</td>
                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm">{{ $grade->subject->name ?? '-' }}</td>
                                <td class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">{{ $grade->assignment_score }}</td>
                                <td class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">{{ $grade->mid_exam_score }}</td>
                                <td class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">{{ $grade->final_exam_score }}</td>
                                <td class="px-3 sm:px-4 py-3 text-center font-bold text-green-700 text-xs sm:text-sm">{{ $grade->final_score }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center px-4 py-5 text-gray-500 text-sm">Belum ada data nilai</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            {{-- REKAP KEHADIRAN --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-8 bg-yellow-500 rounded-full"></div>
                    <h2 class="text-lg sm:text-xl font-bold text-yellow-600">Rekap Kehadiran Mata Pelajaran</h2>
                </div>

                <div class="table-wrapper border border-gray-200 rounded-xl overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-yellow-100 text-yellow-800">
                            <tr>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">No</th>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm">Mata Pelajaran</th>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">Masuk</th>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">Izin</th>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">Sakit</th>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">Alpa</th>
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
                                <td class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">{{ $loop->iteration }}</td>
                                <td class="px-3 sm:px-4 py-3 font-semibold text-xs sm:text-sm">{{ $subject }}</td>
                                <td class="px-3 sm:px-4 py-3 text-center text-green-700 font-bold text-xs sm:text-sm">{{ $attendances->where('status', 'hadir')->count() }}</td>
                                <td class="px-3 sm:px-4 py-3 text-center text-blue-700 font-bold text-xs sm:text-sm">{{ $attendances->where('status', 'izin')->count() }}</td>
                                <td class="px-3 sm:px-4 py-3 text-center text-yellow-700 font-bold text-xs sm:text-sm">{{ $attendances->where('status', 'sakit')->count() }}</td>
                                <td class="px-3 sm:px-4 py-3 text-center text-red-700 font-bold text-xs sm:text-sm">{{ $attendances->where('status', 'alpa')->count() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center px-4 py-5 text-gray-500 text-sm">Belum ada data kehadiran</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            {{-- EKSTRAKURIKULER --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-8 bg-purple-600 rounded-full"></div>
                    <h2 class="text-lg sm:text-xl font-bold text-purple-700">Ekstrakurikuler</h2>
                </div>

                <div class="table-wrapper border border-gray-200 rounded-xl overflow-hidden">
                    <table class="w-full" style="min-width: 240px;">
                        <thead class="bg-purple-100 text-purple-800">
                            <tr>
                                <th class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm w-16">No</th>
                                <th class="px-3 sm:px-4 py-3 text-left text-xs sm:text-sm">Nama Ekstrakurikuler</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($student->extracurriculars as $i => $item)
                            <tr>
                                <td class="px-3 sm:px-4 py-3 text-center text-xs sm:text-sm">{{ $i + 1 }}</td>
                                <td class="px-3 sm:px-4 py-3 text-xs sm:text-sm">{{ $item->name }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center px-4 py-5 text-gray-500 text-sm">Belum ada data ekstrakurikuler</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            {{-- CATATAN WALI KELAS --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                    <h2 class="text-lg sm:text-xl font-bold text-indigo-700">Catatan Wali Kelas</h2>
                </div>
                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 sm:p-5 text-sm sm:text-base">
                    {{ $student->note ?? 'Pertahankan prestasi belajar dan tingkatkan kedisiplinan.' }}
                </div>
            </div>


            {{-- TANDA TANGAN --}}
            <div class="pt-8 sm:pt-10">
                <div class="grid grid-cols-2 gap-4 sm:gap-8 text-center">

                    <div>
                        <p class="font-semibold text-sm sm:text-base">Wali Kelas</p>
                        <div class="h-16 sm:h-24"></div>
                        <p class="border-t border-gray-400 inline-block px-4 sm:px-10 pt-2 text-xs sm:text-sm">
                            ( {{ $student->class->teacher->name ?? '....................' }} )
                        </p>
                    </div>

                    <div>
                        <p class="font-semibold text-sm sm:text-base">Kepala Sekolah</p>
                        <div class="h-16 sm:h-24"></div>
                        <p class="border-t border-gray-400 inline-block px-4 sm:px-10 pt-2 text-xs sm:text-sm">
                            (__________________)
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </div>

</body>
</html>