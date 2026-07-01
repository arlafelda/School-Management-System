<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Student;
use App\Models\Schedule;
use App\Models\ClassModel;
use App\Models\Subject;

class Grade extends Model
{
    protected $table = 'tbl_grades';

    protected $fillable = [
        'student_id',
        'schedule_id',
        'subject_id',
        'archived',
        'assignment_score',
        'mid_exam_score',
        'final_exam_score'
    ];

    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    public function schedule()
    {
        return $this->belongsTo(
            Schedule::class,
            'schedule_id'
        );
    }

    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }

    public function class()
    {
        return $this->hasOneThrough(
            ClassModel::class,
            Student::class,
            'id',
            'id',
            'student_id',
            'class_id'
        );
    }

    public function getFinalScoreAttribute()
    {
        return round(
            (
                $this->assignment_score +
                $this->mid_exam_score +
                $this->final_exam_score
            ) / 3,
            1
        );
    }

    /**
     * Kode singkat nilai akhir (dipakai untuk kelas CSS: grade-a, grade-b, dst).
     * Ambang batas tetap sama, hanya digunakan sebagai kunci internal.
     */
    public function getGradeLetterAttribute()
    {
        $score = $this->final_score;

        if ($score >= 90) {
            return 'A';
        }

        if ($score >= 80) {
            return 'B';
        }

        if ($score >= 70) {
            return 'C';
        }

        return 'D';
    }

    /**
     * Predikat deskriptif sesuai Kurikulum Merdeka.
     * Kurikulum Merdeka menampilkan capaian kompetensi dengan predikat
     * kata, bukan huruf tunggal seperti pada K-13.
     */
    public function getPredikatAttribute(): string
    {
        return match ($this->grade_letter) {
            'A'     => 'Sangat Baik',
            'B'     => 'Baik',
            'C'     => 'Cukup',
            default => 'Perlu Bimbingan',
        };
    }

    /**
     * Deskripsi capaian kompetensi otomatis untuk rapor,
     * dibangun dari nama mapel & predikat (format naratif Kurikulum Merdeka).
     */
    public function getCapaianKompetensiAttribute(): string
    {
        $mapel = $this->subject->name ?? 'mata pelajaran ini';

        return match ($this->grade_letter) {
            'A'     => "Menunjukkan penguasaan yang sangat baik pada capaian pembelajaran {$mapel}.",
            'B'     => "Menunjukkan penguasaan yang baik pada capaian pembelajaran {$mapel}.",
            'C'     => "Menunjukkan penguasaan yang cukup pada capaian pembelajaran {$mapel}, perlu latihan lebih lanjut.",
            default => "Masih memerlukan bimbingan untuk mencapai tujuan pembelajaran {$mapel}.",
        };
    }
}