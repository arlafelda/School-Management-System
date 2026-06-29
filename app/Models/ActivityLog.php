<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'tbl_activity_logs';

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'target_label',
        'description',
        'old_data',
        'new_data',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_data'   => 'array',
        'new_data'   => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // =====================
    // RELASI
    // =====================
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    // =====================
    // SCOPES
    // =====================
    public function scopeByModule(Builder $query, string $module): Builder
    {
        return $query->where('module', $module);
    }

    public function scopeByAction(Builder $query, string $action): Builder
    {
        return $query->where('action', $action);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // =====================
    // HELPERS
    // =====================
    public function getActionBadgeColorAttribute(): string
    {
        return match ($this->action) {
            'create'       => 'bg-green-100 text-green-700',
            'update'       => 'bg-blue-100 text-blue-700',
            'delete'       => 'bg-red-100 text-red-700',
            'restore'      => 'bg-yellow-100 text-yellow-700',
            'force_delete' => 'bg-red-200 text-red-800',
            'login'        => 'bg-indigo-100 text-indigo-700',
            'logout'       => 'bg-gray-100 text-gray-700',
            default        => 'bg-gray-100 text-gray-600',
        };
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'create'       => 'Tambah',
            'update'       => 'Ubah',
            'delete'       => 'Hapus',
            'restore'      => 'Pulihkan',
            'force_delete' => 'Hapus Permanen',
            'login'        => 'Login',
            'logout'       => 'Logout',
            default        => ucfirst($this->action),
        };
    }
}