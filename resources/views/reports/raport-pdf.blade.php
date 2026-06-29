<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor {{ $student->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            color: #000;
            background: #fff;
        }

        /* ─── HALAMAN ─────────────────────────────────────── */
        @page {
            size: A4 portrait;
            margin: 15mm 20mm 20mm 25mm;
        }

        .page {
            width: 100%;
        }

        /* ─── ATURAN PAGE BREAK ───────────────────────────── */

        /* Jangan potong di tengah tabel */
        table          { page-break-inside: auto; }
        tr             { page-break-inside: avoid; page-break-after: auto; }
        thead          { display: table-header-group; } /* ulangi thead di halaman baru */
        tfoot          { display: table-footer-group; }

        /* Setiap section utama tidak boleh dipotong di awal */
        .section       { page-break-inside: avoid; }

        /* Section yang cukup besar boleh break, tapi judul tetap ikut isinya */
        .section-title { page-break-after: avoid; }

        /* Tanda tangan selalu satu halaman dengan catatan wali kelas */
        .ttd-block     { page-break-inside: avoid; }

        /* Paksa 2-kolom kehadiran+ekskul tidak terputah */
        .two-col-wrap  { page-break-inside: avoid; }

        /* ─── KOP SURAT ───────────────────────────────────── */
        .kop {
            border-top: 4px solid #000;
            border-bottom: 2px solid #000;
            padding: 6pt 0 8pt 0;
            margin-bottom: 10pt;
            text-align: center;
            page-break-inside: avoid;
            page-break-after: avoid;
        }

        .kop-nama-sekolah {
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1pt;
        }

        .kop-alamat { font-size: 9pt; margin-top: 3pt; }
        .kop-npsn   { font-size: 9pt; }

        /* ─── JUDUL RAPOR ─────────────────────────────────── */
        .judul-rapor {
            text-align: center;
            margin: 8pt 0 12pt 0;
            page-break-inside: avoid;
            page-break-after: avoid;
        }

        .judul-rapor h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1pt;
            text-decoration: underline;
        }

        .judul-rapor p { font-size: 10pt; margin-top: 3pt; }

        /* ─── SECTION TITLE ───────────────────────────────── */
        .section-title {
            font-size: 10.5pt;
            font-weight: bold;
            margin-bottom: 4pt;
            padding-bottom: 2pt;
            border-bottom: 1px solid #000;
            page-break-after: avoid;   /* judul tidak boleh sendirian di bawah halaman */
        }

        /* ─── IDENTITAS SISWA ─────────────────────────────── */
        .identitas-wrap {
            margin-bottom: 12pt;
            page-break-inside: avoid;
        }

        .identitas-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }

        .identitas-table td {
            padding: 2.5pt 4pt;
            vertical-align: top;
        }

        .identitas-table td:first-child { width: 38%; }
        .identitas-table td:nth-child(2) { width: 3%; text-align: center; }

        /* ─── TABEL NILAI ─────────────────────────────────── */
        .nilai-wrap {
            margin-bottom: 12pt;
        }

        .nilai-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }

        .nilai-table th,
        .nilai-table td {
            border: 1px solid #000;
            padding: 3.5pt 4pt;
            text-align: center;
            vertical-align: middle;
        }

        .nilai-table th {
            background-color: #d9d9d9;
            font-weight: bold;
            page-break-after: avoid;
        }

        .nilai-table td.mapel { text-align: left; padding-left: 5pt; }

        .nilai-table tr:nth-child(even) td { background-color: #f5f5f5; }

        .nilai-table td.nilai-akhir { font-weight: bold; }

        .nilai-tidak-tuntas { color: #c00000; }

        .rata-rata-row td {
            background-color: #e0e0e0 !important;
            font-weight: bold;
        }

        /* ─── 2-KOLOM KEHADIRAN + EKSKUL ──────────────────── */
        .two-col-wrap {
            width: 100%;
            margin-bottom: 12pt;
            /* DomPDF tidak support display:flex, pakai table layout */
        }

        .two-col-outer {
            width: 100%;
            border-collapse: collapse;
        }

        .two-col-outer > tbody > tr > td {
            vertical-align: top;
            padding: 0;
        }

        .two-col-outer > tbody > tr > td:first-child {
            width: 52%;
            padding-right: 5pt;
        }

        .two-col-outer > tbody > tr > td:last-child {
            width: 48%;
            padding-left: 5pt;
        }

        .kehadiran-table,
        .ekskul-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .kehadiran-table th,
        .kehadiran-table td,
        .ekskul-table th,
        .ekskul-table td {
            border: 1px solid #000;
            padding: 3pt 4pt;
            text-align: center;
            vertical-align: middle;
        }

        .kehadiran-table th,
        .ekskul-table th {
            background-color: #d9d9d9;
            font-weight: bold;
        }

        .kehadiran-table td.label,
        .ekskul-table td.label { text-align: left; }

        /* ─── CATATAN WALI KELAS ──────────────────────────── */
        .catatan-wrap {
            margin-bottom: 12pt;
            page-break-inside: avoid;
        }

        .catatan-box {
            border: 1px solid #000;
            padding: 5pt 7pt;
            min-height: 36pt;
            font-size: 10pt;
        }

        /* ─── TANDA TANGAN ────────────────────────────────── */
        .ttd-block {
            page-break-inside: avoid;
            margin-top: 6pt;
        }

        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }

        .ttd-table td {
            text-align: center;
            vertical-align: bottom;
            padding: 0 10pt;
            width: 50%;
        }

        .ttd-space { height: 48pt; }

        .ttd-nama {
            border-top: 1px solid #000;
            display: inline-block;
            min-width: 110pt;
            padding-top: 2pt;
            font-weight: bold;
        }

        /* ─── KETERANGAN PREDIKAT ─────────────────────────── */
        .keterangan {
            font-size: 8pt;
            margin-top: 3pt;
            color: #333;
        }
    </style>
</head>

<body>
<div class="page">

    {{-- KOP SURAT --}}
    <div class="kop">
        <div class="kop-nama-sekolah">Gamelab Indonesia</div>
        <div class="kop-alamat">Jl. Raya Jember No. KM13, Banyuwangi, Jawa Timur</div>
        <div class="kop-npsn">Telp: 08123456789 &nbsp;|&nbsp; Email: info@gamelab.id</div>
    </div>

    {{-- JUDUL --}}
    <div class="judul-rapor">
        <h2>Laporan Hasil Belajar Peserta Didik</h2>
        <p>
            Semester
            @if($semester)
                {{ ucfirst($semester) }}
            @else
                {{ ucfirst($student->class->semester ?? '-') }}
            @endif
            &nbsp;&mdash;&nbsp; Tahun Ajaran
            @if($academicYear)
                {{ $academicYear }}
            @else
                {{ $student->class->academic_year ?? '-' }}
            @endif
        </p>
    </div>

    {{-- A. IDENTITAS SISWA --}}
    <div class="identitas-wrap">
        <div class="section-title">A. Identitas Peserta Didik</div>
        @php
            $semesterDisplay    = $semester    ?? ($student->class->semester    ?? '-');
            $tahunAjaranDisplay = $academicYear ?? ($student->class->academic_year ?? '-');
        @endphp
        <table class="identitas-table">
            <tbody>
                <tr>
                    <td>Nama Peserta Didik</td>
                    <td>:</td>
                    <td><b>{{ $student->name }}</b></td>
                    <td style="width:38%">Kelas</td>
                    <td style="width:3%;text-align:center">:</td>
                    <td>{{ $student->class->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>NIS</td>
                    <td>:</td>
                    <td>{{ $student->nis }}</td>
                    <td>Semester</td>
                    <td style="text-align:center">:</td>
                    <td>{{ ucfirst($semesterDisplay) }}</td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td>{{ $student->nisn }}</td>
                    <td>Tahun Ajaran</td>
                    <td style="text-align:center">:</td>
                    <td>{{ $tahunAjaranDisplay }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td>{{ $student->gender == 'L' ? 'Laki-laki' : ($student->gender == 'P' ? 'Perempuan' : ($student->gender ?? '-')) }}</td>
                    <td>Wali Kelas</td>
                    <td style="text-align:center">:</td>
                    <td>{{ $student->class->teacher->name ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- B. NILAI MATA PELAJARAN --}}
    <div class="nilai-wrap">
        <div class="section-title">B. Nilai Mata Pelajaran</div>
        @php
            $grades      = $student->grades;
            $totalNilai  = 0;
            $jumlahMapel = $grades->count();
            $kkm         = 75;
        @endphp
        <table class="nilai-table">
            <thead>
                <tr>
                    <th style="width:5%">No</th>
                    <th style="width:30%;text-align:left;padding-left:5pt">Mata Pelajaran</th>
                    <th style="width:11%">Tugas</th>
                    <th style="width:11%">UTS</th>
                    <th style="width:11%">UAS</th>
                    <th style="width:13%">Nilai Akhir</th>
                    <th style="width:10%">Predikat</th>
                    <th style="width:9%;font-size:8.5pt">KKTP</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $index => $grade)
                @php
                    $finalScore = $grade->final_score;
                    $totalNilai += $finalScore;
                    $predikat = $grade->grade_letter;
                    $lulus    = $finalScore >= $kkm;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="mapel">{{ $grade->subject->name ?? '-' }}</td>
                    <td>{{ $grade->assignment_score }}</td>
                    <td>{{ $grade->mid_exam_score }}</td>
                    <td>{{ $grade->final_exam_score }}</td>
                    <td class="nilai-akhir {{ !$lulus ? 'nilai-tidak-tuntas' : '' }}">
                        {{ number_format($finalScore, 1) }}
                    </td>
                    <td style="font-weight:bold;">{{ $predikat }}</td>
                    <td style="font-size:8.5pt">{{ $kkm }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;font-style:italic;color:#666">
                        Belum ada data nilai
                    </td>
                </tr>
                @endforelse

                @if($jumlahMapel > 0)
                @php $rataRata = round($totalNilai / $jumlahMapel, 1); @endphp
                <tr class="rata-rata-row">
                    <td colspan="5" style="text-align:right;padding-right:5pt">Rata-rata Keseluruhan</td>
                    <td class="nilai-akhir">{{ number_format($rataRata, 1) }}</td>
                    <td>
                        @if($rataRata >= 90) A
                        @elseif($rataRata >= 80) B
                        @elseif($rataRata >= 70) C
                        @else D
                        @endif
                    </td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>
        <p class="keterangan">
            Keterangan: A = 90&ndash;100 (Sangat Baik) &nbsp;|&nbsp;
            B = 80&ndash;89 (Baik) &nbsp;|&nbsp;
            C = 70&ndash;79 (Cukup) &nbsp;|&nbsp;
            D = &lt;70 (Perlu Bimbingan) &nbsp;|&nbsp;
            <span style="color:#c00000">Merah = di bawah KKTP ({{ $kkm }})</span>
        </p>
    </div>

    {{-- C. KEHADIRAN + D. EKSKUL (2 kolom, tidak boleh terpotong) --}}
    <div class="two-col-wrap">
        <table class="two-col-outer">
            <tbody>
                <tr>
                    {{-- REKAP KEHADIRAN --}}
                    <td>
                        <div class="section-title">C. Rekap Kehadiran</div>
                        @php
                            $attendanceBySubject = $student->attendances
                                ->groupBy(function ($att) {
                                    return $att->schedule->subject->name ?? 'Lainnya';
                                });
                            $tH = $student->attendances->where('status', 'hadir')->count();
                            $tI = $student->attendances->where('status', 'izin')->count();
                            $tS = $student->attendances->where('status', 'sakit')->count();
                            $tA = $student->attendances->where('status', 'alpa')->count();
                        @endphp
                        <table class="kehadiran-table">
                            <thead>
                                <tr>
                                    <th style="width:7%">No</th>
                                    <th class="label" style="text-align:left">Mata Pelajaran</th>
                                    <th style="width:12%">H</th>
                                    <th style="width:12%">I</th>
                                    <th style="width:12%">S</th>
                                    <th style="width:12%">A</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendanceBySubject as $subj => $atts)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="label">{{ $subj }}</td>
                                    <td>{{ $atts->where('status', 'hadir')->count() }}</td>
                                    <td>{{ $atts->where('status', 'izin')->count() }}</td>
                                    <td>{{ $atts->where('status', 'sakit')->count() }}</td>
                                    <td>{{ $atts->where('status', 'alpa')->count() }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" style="font-style:italic;color:#666">Belum ada data</td>
                                </tr>
                                @endforelse
                                @if($attendanceBySubject->count() > 0)
                                <tr style="background:#e0e0e0;font-weight:bold;">
                                    <td colspan="2" style="text-align:right;padding-right:4pt">Total</td>
                                    <td>{{ $tH }}</td>
                                    <td>{{ $tI }}</td>
                                    <td>{{ $tS }}</td>
                                    <td>{{ $tA }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        <p class="keterangan" style="margin-top:2pt">
                            H = Hadir &nbsp; I = Izin &nbsp; S = Sakit &nbsp; A = Alpa
                        </p>
                    </td>

                    {{-- EKSKUL --}}
                    <td>
                        <div class="section-title">D. Kegiatan Ekstrakurikuler</div>
                        <table class="ekskul-table">
                            <thead>
                                <tr>
                                    <th style="width:10%">No</th>
                                    <th class="label" style="text-align:left">Nama Kegiatan</th>
                                    <th style="width:22%">Ket.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($student->extracurriculars as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="label">{{ $item->name }}</td>
                                    <td>Aktif</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" style="font-style:italic;color:#666">Belum ada data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- E. CATATAN WALI KELAS --}}
    <div class="catatan-wrap">
        <div class="section-title">E. Catatan Wali Kelas</div>
        <div class="catatan-box">
            {{ $student->note ?? 'Pertahankan prestasi belajar dan tingkatkan kedisiplinan.' }}
        </div>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="ttd-block">
        @php
            $tanggal = \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y');
        @endphp
        <p style="text-align:right;font-size:10pt;margin-bottom:4pt">
            Banyuwangi, {{ $tanggal }}
        </p>
        <table class="ttd-table">
            <tbody>
                <tr>
                    <td>
                        <p>Wali Kelas,</p>
                        <div class="ttd-space"></div>
                        <div class="ttd-nama">
                            {{ $student->class->teacher->name ?? '( ................................ )' }}
                        </div>
                    </td>
                    <td>
                        <p>Mengetahui,<br>Kepala Sekolah</p>
                        <div class="ttd-space"></div>
                        <div class="ttd-nama">( ................................ )</div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>