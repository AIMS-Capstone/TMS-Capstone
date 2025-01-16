<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        // Create 10 employees, each with one address and one employment
        Employee::factory()
            ->count(10)
            ->create();
    }
}
