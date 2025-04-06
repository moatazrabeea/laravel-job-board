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
            1 => ['value' => 5],           // years_experience
            2 => ['value' => true],        // has_equity
            3 => ['value' => 'evening'],   // work_shift
        ]);
    }
}
