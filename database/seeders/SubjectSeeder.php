<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Generate semua mata pelajaran unik yang sudah didefinisikan di Factory.
        Subject::factory()
            ->count(12)
            ->create();
    }
}
