<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attribute;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attribute::insert([
            [
                'name' => 'years_experience',
                'label' => 'Years of Experience',
                'type' => 'number',
                'options' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'has_equity',
                'label' => 'Has Equity',
                'type' => 'boolean',
                'options' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'work_shift',
                'label' => 'Work Shift',
                'type' => 'select',
                'options' => json_encode(['morning', 'evening', 'night']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'job_location',
                'label' => 'Job Location',
                'type' => 'text',
                'options' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'start_date',
                'label' => 'Start Date',
                'type' => 'date',
                'options' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
