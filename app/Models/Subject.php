<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Teacher;

class Subject extends Model
{
    use HasFactory;
    protected $table = 'tbl_subjects';

    protected $fillable = [
        'name',
        'code',
        'kkm',
        'description',
        'slug',
        'archived'
    ];

    protected $casts = [
        'archived' => 'boolean',
    ];

    // MANY TO MANY TEACHER
    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class,
            'teacher_subject',
            'subject_id',
            'teacher_id'
        );
    }
}