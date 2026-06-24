<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ClassModel;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_dashboard()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role' => 'super_admin',
            'archived' => 0,
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_admin_can_access_dashboard()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role' => 'admin',
            'archived' => 0,
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_teacher_can_access_dashboard()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role' => 'teacher',
            'archived' => 0,
        ]);

        Teacher::factory()->create([
            'user_id' => $user->id,
            'archived' => 0,
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_student_can_access_dashboard()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role' => 'student',
            'archived' => 0,
        ]);

        $class = ClassModel::factory()->create();

        Student::factory()->create([
            'user_id' => $user->id,
            'class_id' => $class->id,
            'archived' => 0,
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_invalid_role_gets_403()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role' => 'guest',
            'archived' => 0,
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertStatus(403);
    }

    public function test_teacher_without_teacher_data_gets_404()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role' => 'teacher',
            'archived' => 0,
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertStatus(404);
    }

    public function test_student_without_student_data_gets_404()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create([
            'role' => 'student',
            'archived' => 0,
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard'));

        $response->assertStatus(404);
    }
}