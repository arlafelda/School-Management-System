<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Extracurricular;
use Illuminate\Support\Str;

class ExtracurricularSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Pramuka',
            'Paskibra',
            'Futsal',
            'PMR',
            'KIR',
        ];

        foreach ($data as $name) {

            Extracurricular::updateOrCreate(
                ['name' => $name],
                [
                    'slug' => Str::slug($name) . '-' . rand(1000, 9999),
                ]
            );
        }
    }
}