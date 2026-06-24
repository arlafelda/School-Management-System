<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Attendance;
use App\Models\Schedule;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AttendanceManagementTest extends TestCase
{
    use RefreshDatabase;

    protected user $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    /** @test */
    public function admin_can_access_attendance_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('attendance.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function student_cannot_access_store_attendance()
    {
        $user = User::factory()->create([
            'role' => 'student'
        ]);

        $response = $this->actingAs(
            User::findOrFail($user->id)
        )->postJson(route('attendance.store'), []);

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_store_attendance()
    {
        $schedule = Schedule::factory()->create();

        $student = Student::factory()->create();

        $response = $this->actingAs($this->admin)
            ->postJson(route('attendance.store'), [
                'schedule_id' => $schedule->id,
                'date' => now()->format('Y-m-d'),
                'student_id' => [$student->id],
                'attendance' => [
                    $student->id => 'hadir'
                ]
            ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('tbl_attendances', [
            'student_id' => $student->id,
            'status' => 'hadir'
        ]);
    }

    /** @test */
    public function attendance_can_be_updated()
    {
        $attendance = Attendance::factory()->create([
            'status' => 'izin'
        ]);

        $response = $this->actingAs($this->admin)
            ->putJson(
                route('attendance.update', $attendance->id),
                [
                    'schedule_id' => $attendance->schedule_id,
                    'date' => $attendance->date,
                    'status' => 'hadir'
                ]
            );

        $response->assertStatus(200);

        $this->assertDatabaseHas('tbl_attendances', [
            'id' => $attendance->id,
            'status' => 'hadir'
        ]);
    }

    /** @test */
    public function attendance_can_be_deleted()
    {
        $attendance = Attendance::factory()->create();

        $response = $this->actingAs($this->admin)
            ->deleteJson(
                route('attendance.destroy', $attendance->id)
            );

        $response->assertStatus(200);

        $this->assertDatabaseMissing('tbl_attendances', [
            'id' => $attendance->id
        ]);
    }

    /** @test */
    public function admin_can_access_recap_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('attendance.recap'));

        $response->assertStatus(200);
    }
}
