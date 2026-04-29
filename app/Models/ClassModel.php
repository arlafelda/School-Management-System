<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    protected $table = 'tbl_classes';

    protected $fillable = [
        'name',
        'level',
        'major',
        'academic_year',
        'semester',
        'teacher_id'
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id')->with('teacher');
    }
}
