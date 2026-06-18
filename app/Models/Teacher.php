<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use App\Models\Subject;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Extracurricular;

class Teacher extends Model
{
    use HasFactory;
    protected $table = 'tbl_teachers';

    protected $fillable = [
        'user_id',
        'name',
        'nip',
        'phone',
        'address',
        'archived',
        'position',
        'slug',
    ];

    protected $casts = [
        'archived' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($teacher) {
            $teacher->slug = self::generateUniqueSlug($teacher->name);
        });

        static::updating(function ($teacher) {
            if ($teacher->isDirty('name')) {
                $teacher->slug = self::generateUniqueSlug(
                    $teacher->name,
                    $teacher->id
                );
            }
        });
    }

    public static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $slug = Str::slug($name);
        $original = $slug;
        $count = 1;

        while (
            self::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $original . '-' . $count++;
        }

        return $slug;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ======================
    // RELASI
    // ======================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * MANY TO MANY: teachers <-> subjects
     */
    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'teacher_subject',
            'teacher_id',
            'subject_id'
        );
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }

    public function extracurriculars()
    {
        return $this->hasMany(Extracurricular::class, 'teacher_id');
    }
}