<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    public function run()
    {
        DB::table('addresses')->insert([
            [
                'address' => '123 Rizal Avenue',
                'zip_code' => '1100',
                'region' => 'Metro Manila',
                'addressable_id' => 1, // Employee ID
                'addressable_type' => 'App\Models\Employee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'address' => '456 EDSA',
                'zip_code' => '1200',
                'region' => 'Metro Manila',
                'addressable_id' => 2, // Employee ID
                'addressable_type' => 'App\Models\Employee',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
