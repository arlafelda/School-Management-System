<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'tbl_grades';

    protected $fillable = [
        'student_id',
        'subject',
        'assignment_score',
        'mid_exam_score',
        'final_exam_score'
    ];

    // RELASI KE STUDENT
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // RELASI KE CLASS (via student)
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

    // AUTO NILAI AKHIR
    public function getFinalScoreAttribute()
    {
        return round((
            $this->assignment_score +
            $this->mid_exam_score +
            $this->final_exam_score
        ) / 3, 1);
    }
}