<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Extracurricular extends Model
{
    protected $table = 'tbl_extracurriculars';

    protected $fillable = [
        'name',
        'slug',
        'teacher_id',
        'archived',
    ];

    // 🔥 Route model binding pakai slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // 🔥 RELASI TEACHER
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    // 🔥 RELASI STUDENTS (FIX PIVOT TABLE)
    public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'tbl_extracurricular_students',
            'extracurricular_id', // ✅ FIX INI
            'student_id'
        );
    }
}