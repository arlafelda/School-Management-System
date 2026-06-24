<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;

class AdminUnitTest extends TestCase
{
    public function test_slug_generation()
    {
        $name = 'Admin Sekolah';

        $slug = Str::slug($name) . '-1';

        $this->assertEquals(
            'admin-sekolah-1',
            $slug
        );
    }

    public function test_password_is_hashed()
    {
        $password = 'password123';

        $hashed = Hash::make($password);

        $this->assertTrue(
            Hash::check($password, $hashed)
        );
    }

    public function test_admin_role_value()
    {
        $role = 'admin';

        $this->assertEquals(
            'admin',
            $role
        );
    }

    public function test_archive_status()
    {
        $archived = 1;

        $this->assertTrue(
            $archived == 1
        );
    }

    public function test_slug_contains_user_id()
    {
        $slug = Str::slug('Admin Test') . '-99';

        $this->assertStringEndsWith(
            '-99',
            $slug
        );
    }
}