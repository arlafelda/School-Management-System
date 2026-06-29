<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor &mdash; {{ $student->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white; }
            .print-shadow { box-shadow: none !important; border-radius: 0 !important; }
        }
        .grade-a  { color: #166534; font-weight: 700; }
        .grade-b  { color: #1e40af; font-weight: 700; }
        .grade-c  { color: #92400e; font-weight: 700; }
        .grade-d  { color: #991b1b; font-weight: 700; }
        .tidak-tuntas { color: #dc2626; }
    </style>
</head>

<body class="bg-slate-100 py-6 px-3 sm:py-10 sm:px-4 text-gray-800">

    {{-- ────────────────────────────────────────────────────── --}}
    {{-- FILTER --}}
    {{-- ────────────────────────────────────────────────────── --}}
    <div class="max-w-4xl mx-auto mb-5 no-print">
        <form method="GET" class="bg-white rounded-2xl shadow p-4 flex flex-col sm:flex-row flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-0">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Semester</label>
                <select name="semester" class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                    <option value="">Semua Semester</option>
                    <option value="ganjil" {{ request('semester') == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                    <option value="genap"  {{ request('semester') == 'genap'  ? 'selected' : '' }}>Genap</option>
                </select>
            </div>
            <div class="flex-1 min-w-0">
                <label class="block text-xs font-semibold text-gray-500 mb-1 uppercase tracking-wide">Tahun Ajaran</label>
                <input type="text" name="academic_year" value="{{ request('academic_year') }}"
                    placeholder="2025/2026"
                    class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
            </div>
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow transition">
                🔍 Tampilkan
            </button>
        </form>
    </div>


    {{-- ────────────────────────────────────────────────────── --}}
    {{-- ERROR --}}
    {{-- ────────────────────────────────────────────────────── --}}
    @if(isset($error))
    <div class="max-w-4xl mx-auto mb-4 no-print">
        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-xl text-sm">
            ⚠️ {{ $error }}
        </div>
    </div>
    @endif


    {{-- ────────────────────────────────────────────────────── --}}
    {{-- TOMBOL AKSI --}}
    {{-- ────────────────────────────────────────────────────── --}}
    <div class="max-w-4xl mx-auto mb-6 flex flex-col sm:flex-row justify-between gap-3 no-print">

        <a href="{{ url()->previous() }}"
            class="bg-gray-500 hover:bg-gray-600 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow transition flex items-center gap-2 justify-center">
            ← Kembali
        </a>

        <div class="flex flex-col sm:flex-row gap-3">
            <button onclick="window.print()"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow transition flex items-center gap-2 justify-center">
                🖨️ Cetak Halaman Ini
            </button>

            <a href="{{ route('students.raport.download', [
                    'slug'          => $student->slug,
                    'semester'      => request('semester'),
                    'academic_year' => request('academic_year'),
                ]) }}"
                class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow transition flex items-center gap-2 justify-center">
                ⬇️ Unduh PDF Resmi
            </a>
        </div>

    </div>


    {{-- ────────────────────────────────────────────────────── --}}
    {{-- RAPOR CARD --}}
    {{-- ────────────────────────────────────────────────────── --}}
    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden print-shadow">

        {{-- KOP SURAT --}}
        <div class="border-b-4 border-blue-800 bg-gradient-to-r from-blue-800 to-indigo-700 text-white px-6 py-5 text-center">
            <p class="text-xs uppercase tracking-widest opacity-80 mb-1">Laporan Hasil Belajar Peserta Didik</p>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-wide">Gamelab Indonesia</h1>
            <p class="text-xs sm:text-sm mt-2 opacity-90">Jl. Raya Jember No. KM13, Banyuwangi, Jawa Timur &nbsp;|&nbsp; Telp: 08123456789</p>
            <div class="mt-3 inline-block bg-white/20 rounded-full px-4 py-1 text-xs font-semibold">
                Semester {{ ucfirst(request('semester') ?? $student->class->semester ?? '-') }}
                &nbsp;&mdash;&nbsp;
                T.A. {{ request('academic_year') ?? $student->class->academic_year ?? '-' }}
            </div>
        </div>


        <div class="p-5 sm:p-8 space-y-8">

            {{-- ── IDENTITAS SISWA ────────────────────────────── --}}
            <div>
                <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-1 h-5 bg-blue-600 rounded-full inline-block"></span>
                    A. Identitas Peserta Didik
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-0 border border-gray-200 rounded-xl overflow-hidden text-sm">
                    @php
                        $rows = [
                            ['Nama Lengkap',   $student->name],
                            ['NIS',            $student->nis],
                            ['NISN',           $student->nisn],
                            ['Kelas',          $student->class->name ?? '-'],
                            ['Semester',       ucfirst(request('semester') ?? $student->class->semester ?? '-')],
                            ['Tahun Ajaran',   request('academic_year') ?? $student->class->academic_year ?? '-'],
                            ['Wali Kelas',     $student->class->teacher->name ?? '-'],
                            ['Jenis Kelamin',  $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : ($student->gender ?? '-'))],
                        ];
                    @endphp
                    @foreach($rows as $row)
                    <div class="flex border-b border-gray-100 {{ $loop->last ? 'border-b-0' : '' }}">
                        <div class="w-2/5 px-4 py-2.5 bg-gray-50 font-medium text-gray-600">{{ $row[0] }}</div>
                        <div class="flex-1 px-4 py-2.5">{{ $row[1] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>


            {{-- ── NILAI MATA PELAJARAN ───────────────────────── --}}
            <div>
                <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-1 h-5 bg-emerald-600 rounded-full inline-block"></span>
                    B. Nilai Mata Pelajaran
                </h2>
                @php
                    $grades = $student->grades;
                    $totalNilai = 0;
                    $jumlahMapel = $grades->count();
                    $kkm = 75;
                @endphp
                <div class="overflow-x-auto border border-gray-200 rounded-xl">
                    <table class="w-full text-sm min-w-[560px]">
                        <thead class="bg-emerald-50 text-emerald-800">
                            <tr>
                                <th class="px-4 py-3 text-center w-10">No</th>
                                <th class="px-4 py-3 text-left">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-center w-20">Tugas</th>
                                <th class="px-4 py-3 text-center w-20">UTS</th>
                                <th class="px-4 py-3 text-center w-20">UAS</th>
                                <th class="px-4 py-3 text-center w-24">Nilai Akhir</th>
                                <th class="px-4 py-3 text-center w-20">Predikat</th>
                                <th class="px-4 py-3 text-center w-16">KKM</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($grades as $i => $grade)
                            @php
                                $fs = $grade->final_score;
                                $totalNilai += $fs;
                                $lulus = $fs >= $kkm;
                                $predikat = $grade->grade_letter;
                                $predClass = 'grade-' . strtolower($predikat);
                            @endphp
                            <tr class="{{ $i % 2 == 1 ? 'bg-gray-50/50' : '' }}">
                                <td class="px-4 py-3 text-center text-gray-500">{{ $i + 1 }}</td>
                                <td class="px-4 py-3">{{ $grade->subject->name ?? '-' }}</td>
                                <td class="px-4 py-3 text-center">{{ $grade->assignment_score }}</td>
                                <td class="px-4 py-3 text-center">{{ $grade->mid_exam_score }}</td>
                                <td class="px-4 py-3 text-center">{{ $grade->final_exam_score }}</td>
                                <td class="px-4 py-3 text-center font-bold {{ !$lulus ? 'tidak-tuntas' : 'text-emerald-700' }}">
                                    {{ number_format($fs, 1) }}
                                </td>
                                <td class="px-4 py-3 text-center {{ $predClass }}">{{ $predikat }}</td>
                                <td class="px-4 py-3 text-center text-gray-400 text-xs">{{ $kkm }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-4 py-6 text-center text-gray-400 italic">Belum ada data nilai</td>
                            </tr>
                            @endforelse

                            @if($jumlahMapel > 0)
                            @php $rataRata = round($totalNilai / $jumlahMapel, 1); @endphp
                            <tr class="bg-emerald-50 font-semibold">
                                <td colspan="5" class="px-4 py-3 text-right text-emerald-800">Rata-rata Keseluruhan</td>
                                <td class="px-4 py-3 text-center text-emerald-800">{{ number_format($rataRata, 1) }}</td>
                                <td class="px-4 py-3 text-center {{ 'grade-' . (($rataRata>=90)?'a':(($rataRata>=80)?'b':(($rataRata>=70)?'c':'d'))) }}">
                                    @if($rataRata >= 90) A @elseif($rataRata >= 80) B @elseif($rataRata >= 70) C @else D @endif
                                </td>
                                <td></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Keterangan Predikat --}}
                <p class="text-xs text-gray-400 mt-2">
                    Keterangan: A = 90–100 (Sangat Baik) &nbsp;|&nbsp; B = 80–89 (Baik) &nbsp;|&nbsp;
                    C = 70–79 (Cukup) &nbsp;|&nbsp; D = &lt; 70 (Perlu Perbaikan) &nbsp;|&nbsp;
                    <span class="text-red-500">Merah = di bawah KKM ({{ $kkm }})</span>
                </p>
            </div>


            {{-- ── KEHADIRAN + EKSKUL (2 kolom) ──────────────── --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Kehadiran --}}
                <div>
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-1 h-5 bg-amber-500 rounded-full inline-block"></span>
                        C. Rekap Kehadiran
                    </h2>
                    @php
                        $attendanceBySubject = $student->attendances
                            ->groupBy(fn($a) => $a->schedule->subject->name ?? 'Lainnya');
                        $tH = $student->attendances->where('status','hadir')->count();
                        $tI = $student->attendances->where('status','izin')->count();
                        $tS = $student->attendances->where('status','sakit')->count();
                        $tA = $student->attendances->where('status','alpa')->count();
                    @endphp
                    <div class="overflow-x-auto border border-gray-200 rounded-xl">
                        <table class="w-full text-xs min-w-[300px]">
                            <thead class="bg-amber-50 text-amber-800">
                                <tr>
                                    <th class="px-3 py-2 text-center w-8">No</th>
                                    <th class="px-3 py-2 text-left">Mata Pelajaran</th>
                                    <th class="px-3 py-2 text-center">H</th>
                                    <th class="px-3 py-2 text-center">I</th>
                                    <th class="px-3 py-2 text-center">S</th>
                                    <th class="px-3 py-2 text-center">A</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($attendanceBySubject as $subj => $atts)
                                <tr class="{{ $loop->index % 2 == 1 ? 'bg-gray-50/50' : '' }}">
                                    <td class="px-3 py-2 text-center text-gray-400">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-2">{{ $subj }}</td>
                                    <td class="px-3 py-2 text-center text-green-700 font-semibold">{{ $atts->where('status','hadir')->count() }}</td>
                                    <td class="px-3 py-2 text-center text-blue-700 font-semibold">{{ $atts->where('status','izin')->count() }}</td>
                                    <td class="px-3 py-2 text-center text-yellow-700 font-semibold">{{ $atts->where('status','sakit')->count() }}</td>
                                    <td class="px-3 py-2 text-center text-red-700 font-semibold">{{ $atts->where('status','alpa')->count() }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="px-3 py-4 text-center text-gray-400 italic">Belum ada data</td></tr>
                                @endforelse
                                @if($attendanceBySubject->count() > 0)
                                <tr class="bg-amber-50 font-semibold text-amber-900">
                                    <td colspan="2" class="px-3 py-2 text-right">Total</td>
                                    <td class="px-3 py-2 text-center">{{ $tH }}</td>
                                    <td class="px-3 py-2 text-center">{{ $tI }}</td>
                                    <td class="px-3 py-2 text-center">{{ $tS }}</td>
                                    <td class="px-3 py-2 text-center">{{ $tA }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">H = Hadir &nbsp; I = Izin &nbsp; S = Sakit &nbsp; A = Alpa</p>
                </div>

                {{-- Ekstrakurikuler --}}
                <div>
                    <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-1 h-5 bg-purple-600 rounded-full inline-block"></span>
                        D. Kegiatan Ekstrakurikuler
                    </h2>
                    <div class="overflow-x-auto border border-gray-200 rounded-xl">
                        <table class="w-full text-xs">
                            <thead class="bg-purple-50 text-purple-800">
                                <tr>
                                    <th class="px-3 py-2 text-center w-10">No</th>
                                    <th class="px-3 py-2 text-left">Nama Kegiatan</th>
                                    <th class="px-3 py-2 text-center">Ket.</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($student->extracurriculars as $i => $item)
                                <tr class="{{ $i % 2 == 1 ? 'bg-gray-50/50' : '' }}">
                                    <td class="px-3 py-2 text-center text-gray-400">{{ $i + 1 }}</td>
                                    <td class="px-3 py-2">{{ $item->name }}</td>
                                    <td class="px-3 py-2 text-center text-green-600 font-medium">Aktif</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="px-3 py-4 text-center text-gray-400 italic">Belum ada data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>


            {{-- ── CATATAN WALI KELAS ─────────────────────────── --}}
            <div>
                <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <span class="w-1 h-5 bg-indigo-600 rounded-full inline-block"></span>
                    E. Catatan Wali Kelas
                </h2>
                <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4 text-sm text-gray-700 leading-relaxed">
                    {{ $student->note ?? 'Pertahankan prestasi belajar dan tingkatkan kedisiplinan.' }}
                </div>
            </div>


            {{-- ── TANDA TANGAN ───────────────────────────────── --}}
            <div class="pt-6 grid grid-cols-2 gap-8 text-center text-sm">
                <div>
                    <p class="font-semibold">Wali Kelas</p>
                    <div class="h-16"></div>
                    <div class="border-t border-gray-400 pt-1">
                        <p class="font-semibold">{{ $student->class->teacher->name ?? '( .......................... )' }}</p>
                    </div>
                </div>
                <div>
                    <p class="font-semibold">Kepala Sekolah</p>
                    <div class="h-16"></div>
                    <div class="border-t border-gray-400 pt-1">
                        <p class="font-semibold">( .......................... )</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="max-w-4xl mx-auto mt-4 text-center text-xs text-gray-400 no-print pb-6">
        Dokumen ini digenerate otomatis oleh Sistem Manajemen Sekolah &mdash; {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>