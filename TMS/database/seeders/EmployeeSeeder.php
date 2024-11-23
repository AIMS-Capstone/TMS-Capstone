<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        DB::table('employees')->insert([
            [
                'first_name' => 'John',
                'middle_name' => 'Doe',
                'last_name' => 'Smith',
                'suffix' => 'Jr',
                'date_of_birth' => Carbon::parse('1990-01-01'),
                'tin' => '123-456-789-000',
                'nationality' => 'Filipino',
                'contact_number' => '09171234567',
                'organization_id' => 1,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Jane',
                'middle_name' => 'Marie',
                'last_name' => 'Doe',
                'suffix' => '',
                'date_of_birth' => Carbon::parse('1995-05-20'),
                'tin' => '234-567-890-001',
                'nationality' => 'Filipino',
                'contact_number' => '09181234567',
                'organization_id' => 2,
                'deleted_by' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
