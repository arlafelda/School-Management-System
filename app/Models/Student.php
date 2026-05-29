<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\ClassModel;

class Student extends Model
{
    protected $table = 'tbl_students';

    protected $fillable = [
        'user_id',
        'slug',
        'nisn',
        'nis',
        'name',
        'archived',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'class_id',
        'major',
        'status',
        'semester',
        'father_name',
        'mother_name',
        'parent_phone',
        'parent_address',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {

            $student->slug =
                Str::slug($student->name). '-' .rand(1000, 9999);

        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(
            ClassModel::class,
            'class_id'
        );
    }

    public function grades()
    {
        return $this->hasMany(
            Grade::class,
            'student_id'
        );
    }

    public function attendances()
    {
        return $this->hasMany(
            Attendance::class,
            'student_id'
        );
    }

    public function extracurriculars()
    {
        return $this->belongsToMany(
            Extracurricular::class,
            'tbl_extracurricular_students',
            'student_id',
            'extracurricular_id'
        );
    }
}