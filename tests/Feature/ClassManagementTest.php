<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\ClassModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClassManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    public function test_admin_can_view_class_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('class.index'));

        $response->assertStatus(200);
    }

    public function test_admin_can_create_class()
    {
        $response = $this->actingAs($this->admin)
            ->postJson(route('class.store'), [
                'name' => 'X RPL 1',
                'level' => 'X',
                'major' => 'RPL',
                'academic_year' => '2025/2026',
                'semester' => 'Ganjil'
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tbl_classes', [
            'name' => 'X RPL 1'
        ]);
    }

    public function test_admin_can_view_class_detail()
    {
        $class = ClassModel::factory()->create([
            'archived' => 0
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('class.show', $class));

        $response->assertStatus(200);
    }

    public function test_admin_can_update_class()
    {
        $class = ClassModel::factory()->create([
            'archived' => 0
        ]);

        $response = $this->actingAs($this->admin)
            ->put(route('class.update', $class), [
                'name' => 'XI RPL 1',
                'level' => 'XI',
                'major' => 'RPL',
                'academic_year' => '2025/2026',
                'semester' => 'Genap'
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('tbl_classes', [
            'id' => $class->id,
            'name' => 'XI RPL 1'
        ]);
    }

    public function test_admin_can_archive_class()
    {
        $class = ClassModel::factory()->create([
            'archived' => 0
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('class.destroy', $class));

        $response->assertStatus(200);

        $this->assertDatabaseHas('tbl_classes', [
            'id' => $class->id,
            'archived' => 1
        ]);
    }

    public function test_admin_can_restore_class()
    {
        $class = ClassModel::factory()->create([
            'archived' => 1,
            'slug' => 'x-rpl-1'
        ]);

        $response = $this->actingAs($this->admin)
            ->postJson(route('class.restore', $class->slug));

        $response->assertStatus(200);

        $this->assertDatabaseHas('tbl_classes', [
            'id' => $class->id,
            'archived' => 0
        ]);
    }

    public function test_admin_can_delete_class_permanently()
    {
        $class = ClassModel::factory()->create([
            'archived' => 1,
            'slug' => 'x-rpl-delete'
        ]);

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('class.delete', $class->slug));

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tbl_classes', [
            'id' => $class->id
        ]);
    }

    public function test_teacher_can_view_own_class()
    {
        /** @var \App\Models\User $teacherUser */
        $teacherUser = User::factory()->create([
            'role' => 'teacher',
        ]);

        $teacher = Teacher::factory()->create([
            'user_id' => $teacherUser->id,
        ]);

        ClassModel::factory()->create([
            'teacher_id' => $teacher->id,
        ]);

        $response = $this->actingAs($teacherUser)
            ->get(route('class.index'));

        $response->assertStatus(200);
    }
}
