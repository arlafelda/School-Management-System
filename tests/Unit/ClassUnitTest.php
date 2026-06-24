<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Support\Str;

class ClassUnitTest extends TestCase
{
    public function test_slug_generation()
    {
        $slug = Str::slug('X RPL 1');

        $this->assertEquals(
            'x-rpl-1',
            $slug
        );
    }

    public function test_slug_is_string()
    {
        $slug = Str::slug('XI TKJ 1');

        $this->assertIsString($slug);
    }

    public function test_archived_status_active()
    {
        $archived = 0;

        $this->assertEquals(
            0,
            $archived
        );
    }

    public function test_archived_status_archived()
    {
        $archived = 1;

        $this->assertEquals(
            1,
            $archived
        );
    }

    public function test_level_value()
    {
        $level = 'X';

        $this->assertContains(
            $level,
            ['X', 'XI', 'XII']
        );
    }

    public function test_semester_value()
    {
        $semester = 'Ganjil';

        $this->assertContains(
            $semester,
            ['Ganjil', 'Genap']
        );
    }
}