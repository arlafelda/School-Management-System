<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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
        'archived',
        'position',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($teacher) {
            $teacher->slug = static::generateUniqueSlug($teacher->name);
        });

        static::updating(function ($teacher) {
            if ($teacher->isDirty('name')) {
                $teacher->slug = static::generateUniqueSlug(
                    $teacher->name,
                    $teacher->id
                );
            }
        });
    }

    public static function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    return $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

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