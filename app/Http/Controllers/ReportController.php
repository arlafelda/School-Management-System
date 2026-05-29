<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function raport(Request $request, String $slug)
    {
        $semester = $request->semester;
        $academicYear = $request->academic_year;

        $student = Student::with([
            'class.teacher',
            'grades.subject',
            'attendances.schedule.subject',
            'extracurriculars'
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        // VALIDASI RAPOT
        if ($semester && $academicYear) {

            $isValidRaport =
                $student->class &&
                strtolower($student->class->semester) === strtolower($semester) &&
                $student->class->academic_year === $academicYear;

            if (!$isValidRaport) {

                return view('reports.pdf', [
                    'student'       => $student,
                    'semester'      => $semester,
                    'academicYear'  => $academicYear,
                    'error'         => 'Rapot untuk semester dan tahun ajaran yang dipilih tidak tersedia.'
                ]);
            }
        }

        return view(
            'reports.pdf',
            compact(
                'student',
                'semester',
                'academicYear'
            )
        );
    }

    public function myRaport(Request $request)
    {
        $semester = $request->semester;
        $academicYear = $request->academic_year;

        $student = Student::with([
            'class.teacher',
            'grades.subject',
            'attendances.schedule.subject',
            'extracurriculars'
        ])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // VALIDASI RAPOT
        if ($semester && $academicYear) {

            $isValidRaport =
                $student->class &&
                strtolower($student->class->semester) === strtolower($semester) &&
                $student->class->academic_year === $academicYear;

            if (!$isValidRaport) {

                return view('reports.pdf', [
                    'student'       => $student,
                    'semester'      => $semester,
                    'academicYear'  => $academicYear,
                    'error'         => 'Rapot untuk semester dan tahun ajaran yang dipilih tidak tersedia.'
                ]);
            }
        }

        return view(
            'reports.pdf',
            compact(
                'student',
                'semester',
                'academicYear'
            )
        );
    }

    public function studentRaport(Request $request)
    {
        $semester = $request->semester;
        $academicYear = $request->academic_year;

        $student = Student::with([
            'class.teacher',
            'grades.subject',
            'attendances.schedule.subject',
            'extracurriculars'
        ])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // VALIDASI RAPOT
        if ($semester && $academicYear) {

            $isValidRaport =
                $student->class &&
                strtolower($student->class->semester) === strtolower($semester) &&
                $student->class->academic_year === $academicYear;

            if (!$isValidRaport) {

                return view('reports.pdf', [
                    'student'       => $student,
                    'semester'      => $semester,
                    'academicYear'  => $academicYear,
                    'error'         => 'Rapot untuk semester dan tahun ajaran yang dipilih tidak tersedia.'
                ]);
            }
        }

        return view(
            'reports.pdf',
            compact(
                'student',
                'semester',
                'academicYear'
            )
        );
    }

    public function print(String $slug)
    {
        $student = Student::with([
            'class.teacher',
            'grades.subject',
            'attendances.schedule.subject',
            'extracurriculars'
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        return view(
            'reports.print',
            compact('student')
        );
    }

    public function downloadPdf(String $slug)
    {
        $student = Student::with([
            'class.teacher',
            'grades.subject',
            'attendances.schedule.subject',
            'extracurriculars'
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        $pdf = Pdf::loadView(
            'reports.pdf',
            compact('student')
        );

        $pdf->setPaper('A4', 'portrait');

        return $pdf->download(
            'rapot-' . $student->name . '.pdf'
        );
    }
}