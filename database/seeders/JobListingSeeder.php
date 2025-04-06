<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\JobListing;

class JobListingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $job = JobListing::create([
            'title' => 'software engineer',
            'description' => 'experienced software engineer',
            'company_name' => 'Astudio',
            'salary_min' => 1000,
            'salary_max' => 5000,
            'is_remote' => true,
            'job_type' => 'full-time',
            'status' => 'published',
        ]);
          
        $job->attributes()->attach([
            1 => ['value' => 5],           
            2 => ['value' => true],        
            3 => ['value' => 'evening'],
            4 => ['value' => 'Cairo, Egypt'],
            5 => ['value' => '2025-05-01'],  
        ]);

        $job2 = JobListing::create([
            'title' => 'Backend Developer',
            'description' => 'Laravel and API expert',
            'company_name' => 'CodeCraft',
            'salary_min' => 3000,
            'salary_max' => 7000,
            'is_remote' => false,
            'job_type' => 'contract',
            'status' => 'published',
        ]);

        $job2->attributes()->attach([
            1 => ['value' => 3],
            2 => ['value' => false],
            3 => ['value' => 'morning'],
            4 => ['value' => 'Alexandria, Egypt'],
            5 => ['value' => '2025-06-15'],
        ]);
    }
}
