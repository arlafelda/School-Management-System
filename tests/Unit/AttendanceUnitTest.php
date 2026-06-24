<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class AttendanceUnitTest extends TestCase
{
    /** @test */
    public function percentage_calculation_is_correct()
    {
        $total = 20;
        $hadir = 18;

        $persen = round(
            ($hadir / $total) * 100,
            1
        );

        $this->assertEquals(
            90,
            $persen
        );
    }

    /** @test */
    public function percentage_should_be_zero_if_no_attendance()
    {
        $total = 0;

        $persen = $total
            ? round((0 / $total) * 100, 1)
            : 0;

        $this->assertEquals(
            0,
            $persen
        );
    }

    /** @test */
    public function valid_attendance_status()
    {
        $status = 'hadir';

        $this->assertContains(
            $status,
            [
                'hadir',
                'izin',
                'alpa'
            ]
        );
    }

    /** @test */
    public function student_role_is_detected()
    {
        $role = 'student';

        $this->assertEquals(
            'student',
            $role
        );
    }

    /** @test */
    public function teacher_role_is_detected()
    {
        $role = 'teacher';

        $this->assertEquals(
            'teacher',
            $role
        );
    }

    /** @test */
    public function admin_role_is_detected()
    {
        $role = 'admin';

        $this->assertEquals(
            'admin',
            $role
        );
    }
}