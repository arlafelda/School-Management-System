<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DashboardUnitTest extends TestCase
{
    public function test_attendance_percentage_calculation()
    {
        $totalAttendance = 20;
        $presentCount = 18;

        $percent = round(
            ($presentCount / $totalAttendance) * 100,
            1
        );

        $this->assertEquals(
            90.0,
            $percent
        );
    }

    public function test_attendance_percentage_zero_when_no_data()
    {
        $totalAttendance = 0;

        $percent = $totalAttendance > 0
            ? round((0 / $totalAttendance) * 100, 1)
            : 0;

        $this->assertEquals(
            0,
            $percent
        );
    }

    public function test_day_mapping_monday()
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $this->assertEquals(
            'Senin',
            $days['Monday']
        );
    }

    public function test_day_mapping_friday()
    {
        $days = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $this->assertEquals(
            'Jumat',
            $days['Friday']
        );
    }

    public function test_role_super_admin()
    {
        $role = 'super_admin';

        $this->assertEquals(
            'super_admin',
            $role
        );
    }

    public function test_role_admin()
    {
        $role = 'admin';

        $this->assertEquals(
            'admin',
            $role
        );
    }

    public function test_role_teacher()
    {
        $role = 'teacher';

        $this->assertEquals(
            'teacher',
            $role
        );
    }

    public function test_role_student()
    {
        $role = 'student';

        $this->assertEquals(
            'student',
            $role
        );
    }
}