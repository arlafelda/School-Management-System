<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'tbl_schedules';

    protected $fillable = [
        'class_id',      // 🔥 TAMBAHKAN INI (INI PENYEBAB ERROR)
        'day',
        'start_time',
        'end_time',
        'teacher_id'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id'); // lebih aman explicit
    }
}
