<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tbl_users';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'password',
        'role',
        'archived',
        'creation_time',
        'create_id',
        'update_time',
        'update_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // 🔥 AUTO SLUG ANTI NULL (FIX UTAMA)
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = Str::slug($user->name) . '-' . time();
            }
        });

        static::updating(function ($user) {
            if (empty($user->slug)) {
                $user->slug = Str::slug($user->name) . '-' . $user->id;
            }
        });
    }

    // RELASI
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // ROLE CHECK
    public function isSuperAdmin() { return $this->role === 'super_admin'; }
    public function isAdmin() { return $this->role === 'admin'; }
    public function isTeacher() { return $this->role === 'teacher'; }
    public function isStudent() { return $this->role === 'student'; }
}