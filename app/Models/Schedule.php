<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Subject;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_schedules';

    protected $fillable = [
        'class_id',
        'teacher_id',
        'subject_id',
        'day',
        'start_time',
        'end_time',
    ];

    protected $dates = ['deleted_at'];

    public function teacher()
    {
        return $this->belongsTo(
            Teacher::class,
            'teacher_id'
        );
    }

    public function classModel()
    {
        return $this->belongsTo(
            ClassModel::class,
            'class_id'
        );
    }

    public function class()
    {
        return $this->belongsTo(
            ClassModel::class,
            'class_id'
        );
    }

    public function subject()
    {
        return $this->belongsTo(
            Subject::class,
            'subject_id'
        );
    }
}
