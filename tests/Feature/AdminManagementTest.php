<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'role' => 'admin',
            'archived' => 0,
        ]);
    }

    public function test_admin_can_view_admin_page()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_new_admin()
    {
        $response = $this->actingAs($this->adminUser)
            ->postJson(route('admin.store'), [
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => 'password123',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tbl_users', [
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_update_admin()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'slug' => 'admin-lama',
        ]);

        $response = $this->actingAs($this->adminUser)
            ->putJson(route('admin.update', $admin->slug), [
                'name' => 'Admin Baru',
                'email' => 'baru@test.com',
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tbl_users', [
            'id' => $admin->id,
            'name' => 'Admin Baru',
        ]);
    }

    public function test_admin_can_archive_admin()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'slug' => 'admin-archive',
            'archived' => 0,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->deleteJson(route('admin.destroy', $admin->slug));

        $response->assertStatus(200);

        $this->assertDatabaseHas('tbl_users', [
            'id' => $admin->id,
            'archived' => 1,
        ]);
    }

    public function test_admin_can_restore_admin()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'slug' => 'admin-restore',
            'archived' => 1,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.restore', $admin->slug));

        $response->assertRedirect();

        $this->assertDatabaseHas('tbl_users', [
            'id' => $admin->id,
            'archived' => 0,
        ]);
    }

    public function test_admin_can_force_delete_admin()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'slug' => 'admin-delete',
            'archived' => 1,
        ]);

        $response = $this->actingAs($this->adminUser)
            ->deleteJson(route('admin.forceDelete', $admin->slug));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tbl_users', [
            'id' => $admin->id,
        ]);
    }
}