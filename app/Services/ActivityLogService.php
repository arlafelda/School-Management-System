<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Catat aktivitas ke database.
     *
     * @param string      $action        Aksi: create | update | delete | restore | force_delete | login | logout
     * @param string      $module        Nama modul: Teacher | Student | Grade | Class | dll
     * @param string      $description   Deskripsi lengkap aktivitas
     * @param string|null $targetLabel   Label target data (nama siswa, guru, dll)
     * @param array|null  $oldData       Data sebelum diubah
     * @param array|null  $newData       Data sesudah diubah
     */
    public static function log(
        string  $action,
        string  $module,
        string  $description,
        ?string $targetLabel = null,
        ?array  $oldData     = null,
        ?array  $newData     = null
    ): void {
        $user = Auth::user();

        ActivityLog::create([
            'user_id'      => $user?->id,
            'user_name'    => $user?->name ?? 'System',
            'user_role'    => $user?->role ?? '-',
            'action'       => $action,
            'module'       => $module,
            'target_label' => $targetLabel,
            'description'  => $description,
            'old_data'     => $oldData,
            'new_data'     => $newData,
            'ip_address'   => Request::ip(),
            'user_agent'   => Request::userAgent(),
        ]);
    }

    // ─── Shorthand helpers ───────────────────────────────────────────────────

    public static function create(string $module, string $description, ?string $label = null, ?array $newData = null): void
    {
        self::log('create', $module, $description, $label, null, $newData);
    }

    public static function update(string $module, string $description, ?string $label = null, ?array $old = null, ?array $new = null): void
    {
        self::log('update', $module, $description, $label, $old, $new);
    }

    public static function delete(string $module, string $description, ?string $label = null, ?array $oldData = null): void
    {
        self::log('delete', $module, $description, $label, $oldData);
    }

    public static function restore(string $module, string $description, ?string $label = null): void
    {
        self::log('restore', $module, $description, $label);
    }

    public static function forceDelete(string $module, string $description, ?string $label = null, ?array $oldData = null): void
    {
        self::log('force_delete', $module, $description, $label, $oldData);
    }

    public static function login(string $description): void
    {
        self::log('login', 'Auth', $description);
    }

    public static function logout(string $description): void
    {
        self::log('logout', 'Auth', $description);
    }
}
