<?php

namespace Database\Seeders;

use App\Models\OrgSetup as Orgsetups;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Orgsetup extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Orgsetups::create([
            'type' => 'Non-individual', 
            'registration_name' => 'PUPSRC ET AL NIG',
            'line_of_business' => 'Software Development',
            'address_line' => 'Brgy, Tagapo',
            'region' => 'Region 4-A',
            'province' => 'Laguna',
            'city' => 'Santa Rosa',
            'zip_code' => '4026',
            'contact_number' => '09171234567',
            'email' => 'pupsrc@gmail.com',
            'tin' => '123-456-789-000',
            'rdo' => 050, 
            'tax_type' => 'Value-Added Tax', 
            'registration_date' => Carbon::parse('2020-01-15'),
            'start_date' => Carbon::parse('2020-01-01'),
            'financial_year_end' => Carbon::parse('2024-12-31'), 
        ]);
    }
}
