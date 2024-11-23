<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentSeeder extends Seeder
{
    public function run()
    {
        DB::table('employments')->insert([
            [
                'employer_name' => 'ABC Corporation',
                'employment_from' => '2015-01-01',
                'employment_to' => '2020-12-31',
                'rate' => 50000,
                'rate_per_month' => 5000,
                'employment_status' => 'Full-Time',
                'reason_for_separation' => 'Resigned',
                'employee_wage_status' => 'Above Minimum Wage',
                'substituted_filing' => true,
                'with_previous_employer' => false,
                'employee_id' => 1, // Matches Employee ID from EmployeeSeeder
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'employer_name' => 'XYZ Inc.',
                'employment_from' => '2018-06-01',
                'employment_to' => '2022-05-31',
                'rate' => 60000,
                'rate_per_month' => 6000,
                'employment_status' => 'Part-Time',
                'reason_for_separation' => 'Company Closure',
                'employee_wage_status' => 'Above Minimum Wage',
                'substituted_filing' => false,
                'with_previous_employer' => true,
                'employee_id' => 2, // Matches Employee ID from EmployeeSeeder
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
