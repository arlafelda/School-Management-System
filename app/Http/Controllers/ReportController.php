<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\ActivityLogService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Muat data siswa beserta relasi yang dibutuhkan.
     */
    private function loadStudent(string $slug): Student
    {
        return Student::with([
            'class.teacher',
            'grades.subject',
            'attendances.schedule.subject',
            'extracurriculars',
        ])->where('slug', $slug)->firstOrFail();
    }

    /**
     * Validasi apakah semester & tahun ajaran cocok dengan kelas siswa.
     * Mengembalikan pesan error atau null jika valid.
     */
    private function validateRaport(Student $student, ?string $semester, ?string $academicYear): ?string
    {
        if (! $semester || ! $academicYear) {
            return null; // tidak ada filter → tampilkan apa adanya
        }

        $isValid = $student->class &&
            strtolower($student->class->semester) === strtolower($semester) &&
            $student->class->academic_year === $academicYear;

        return $isValid
            ? null
            : 'Rapor untuk semester dan tahun ajaran yang dipilih tidak tersedia.';
    }

    // ─────────────────────────────────────────────────────────────
    // HALAMAN PREVIEW (admin / teacher melihat rapor siswa)
    // ─────────────────────────────────────────────────────────────

    public function raport(Request $request, string $slug)
    {
        $semester     = $request->semester;
        $academicYear = $request->academic_year;
        $student      = $this->loadStudent($slug);

        $error = $this->validateRaport($student, $semester, $academicYear);

        // 📝 Catat aktivitas preview rapor
        ActivityLogService::log(
            'view',
            'Report',
            "Melihat preview rapor siswa: {$student->name}" .
            ($semester    ? " — Semester: {$semester}"        : '') .
            ($academicYear ? ", T.A: {$academicYear}"         : ''),
            $student->name
        );

        return view('reports.pdf', compact('student', 'semester', 'academicYear', 'error'));
    }

    // ─────────────────────────────────────────────────────────────
    // HALAMAN RAPOR MILIK SISWA YANG LOGIN
    // ─────────────────────────────────────────────────────────────

    public function myRaport(Request $request)
    {
        $semester     = $request->semester;
        $academicYear = $request->academic_year;

        $student = Student::with([
            'class.teacher',
            'grades.subject',
            'attendances.schedule.subject',
            'extracurriculars',
        ])->where('user_id', Auth::id())->firstOrFail();

        $error = $this->validateRaport($student, $semester, $academicYear);

        // 📝 Catat aktivitas siswa melihat rapor sendiri
        ActivityLogService::log(
            'view',
            'Report',
            "Siswa melihat rapor sendiri: {$student->name}" .
            ($semester     ? " — Semester: {$semester}"   : '') .
            ($academicYear ? ", T.A: {$academicYear}"     : ''),
            $student->name
        );

        return view('reports.pdf', compact('student', 'semester', 'academicYear', 'error'));
    }

    public function studentRaport(Request $request)
    {
        return $this->myRaport($request);
    }

    // ─────────────────────────────────────────────────────────────
    // CETAK (window.print via browser)
    // ─────────────────────────────────────────────────────────────

    public function print(string $slug)
    {
        $student = $this->loadStudent($slug);

        // 📝 Catat aktivitas cetak rapor
        ActivityLogService::log(
            'view',
            'Report',
            "Mencetak rapor siswa: {$student->name}",
            $student->name
        );

        return view('reports.print', compact('student'));
    }

    // ─────────────────────────────────────────────────────────────
    // DOWNLOAD PDF (DomPDF — format rapor resmi A4)
    // ─────────────────────────────────────────────────────────────

    public function downloadPdf(Request $request, string $slug)
    {
        $semester     = $request->semester;
        $academicYear = $request->academic_year;
        $student      = $this->loadStudent($slug);

        // Validasi filter jika keduanya diisi
        if ($semester && $academicYear) {
            $error = $this->validateRaport($student, $semester, $academicYear);

            if ($error) {
                return redirect()
                    ->route('students.raport', [
                        'slug'          => $slug,
                        'semester'      => $semester,
                        'academic_year' => $academicYear,
                    ])
                    ->with('error', $error);
            }
        }

        $pdf = Pdf::loadView(
            'reports.raport-pdf',
            compact('student', 'semester', 'academicYear')
        );

        $pdf->setPaper('A4', 'portrait');

        $safeName = Str::slug($student->name);
        $safeSem  = $semester     ? '-semester-' . $semester                    : '';
        $safeYear = $academicYear ? '-' . str_replace('/', '-', $academicYear)  : '';
        $filename = "rapor-{$safeName}{$safeSem}{$safeYear}.pdf";

        // 📝 Catat aktivitas download PDF
        ActivityLogService::log(
            'view',
            'Report',
            "Mengunduh PDF rapor siswa: {$student->name}" .
            ($semester     ? " — Semester: {$semester}"   : '') .
            ($academicYear ? ", T.A: {$academicYear}"     : '') .
            " — File: {$filename}",
            $student->name
        );

        return $pdf->download($filename);
    }
}