<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'tbl_teachers';

    protected $fillable = [
        'user_id',
        'name',
        'nip',
        'subject',
        'phone',
        'address',
        'position',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }

    public function extracurriculars()
    {
        return $this->hasMany(Extracurricular::class);
    }
}
