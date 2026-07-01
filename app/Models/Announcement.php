<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    use SoftDeletes;

    protected $table = 'tbl_announcements';

    protected $fillable = [
        'title',
        'content',
        'priority',
        'target_role',
        'expired_at',
        'is_active',
        'created_by',
        'published_at',
    ];

    protected $casts = [
        'expired_at'   => 'datetime',
        'published_at' => 'datetime',
        'is_active'    => 'boolean',
    ];

    // =============================================
    // RELATIONSHIPS
    // =============================================
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // =============================================
    // SCOPES
    // =============================================

    /**
     * Hanya pengumuman yang aktif dan belum kadaluarsa.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function (Builder $q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>=', now());
            });
    }

    /**
     * Filter berdasarkan target role (all / student / teacher / admin).
     */
    public function scopeForRole(Builder $query, string $role): Builder
    {
        return $query->where(function (Builder $q) use ($role) {
            $q->where('target_role', 'all')
              ->orWhere('target_role', $role);
        });
    }
}