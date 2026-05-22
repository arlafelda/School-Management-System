<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Subject;

class Schedule extends Model
{
    protected $table = 'tbl_schedules';

    protected $fillable = [
        'class_id',
        'teacher_id',
        'subject_id',
        'day',
        'start_time',
        'end_time',
        'archived',
    ];

    // TEACHER
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    // CLASS
    public function classModel()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    // SUBJECT (langsung dari schedule)
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}