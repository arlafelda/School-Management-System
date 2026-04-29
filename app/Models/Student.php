<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Student extends Model
{
    protected $table = 'tbl_students';

    protected $fillable = [
        'user_id',
        'nisn',
        'nis',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'phone',
        'class_id',
        'major',
        'status',
        'father_name',
        'mother_name',
        'parent_phone',
        'parent_address',
    ];

    // RELASI USER LOGIN
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function extracurriculars()
    {
        return $this->belongsToMany(Extracurricular::class, 'tbl_extracurricular_students');
    }
}
