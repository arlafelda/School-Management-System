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

    // =========================
    // RELASI KE STUDENT
    // =========================
    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    // =========================
    // RELASI KE SCHEDULE
    // =========================
    public function schedule()
    {
        return $this->belongsTo(
            Schedule::class,
            'schedule_id'
        );
    }

    // =========================
    // RELASI KE SUBJECT
    // =========================
    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }

    // =========================
    // RELASI KE CLASS (lewat student)
    // =========================
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

    // =========================
    // AUTO HITUNG NILAI AKHIR
    // =========================
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
}