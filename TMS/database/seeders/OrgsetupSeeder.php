<?php

namespace Database\Seeders;

use App\Models\OrgSetup as Orgsetups;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrgsetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Original data
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

        // Additional data sets
        Orgsetups::create([
            'type' => 'Individual',
            'registration_name' => 'Juan Dela Cruz',
            'line_of_business' => 'Freelance Graphic Design',
            'address_line' => 'Brgy. Poblacion',
            'region' => 'NCR',
            'province' => 'Metro Manila',
            'city' => 'Quezon City',
            'zip_code' => '1101',
            'contact_number' => '09291234567',
            'email' => 'juan.delacruz@gmail.com',
            'tin' => '234-567-890-000',
            'rdo' => 045,
            'tax_type' => 'Percentage Tax',
            'registration_date' => Carbon::parse('2021-02-20'),
            'start_date' => Carbon::parse('2021-02-01'),
            'financial_year_end' => Carbon::parse('2024-12-31'),
        ]);

        Orgsetups::create([
            'type' => 'Non-individual',
            'registration_name' => 'Tech Innovations Corp.',
            'line_of_business' => 'Electronics Manufacturing',
            'address_line' => 'Science Park',
            'region' => 'Region 3',
            'province' => 'Pampanga',
            'city' => 'San Fernando',
            'zip_code' => '2000',
            'contact_number' => '09331234567',
            'email' => 'info@techinnovations.com',
            'tin' => '345-678-901-000',
            'rdo' => 040,
            'tax_type' => 'Value-Added Tax',
            'registration_date' => Carbon::parse('2019-05-10'),
            'start_date' => Carbon::parse('2019-05-01'),
            'financial_year_end' => Carbon::parse('2024-12-31'),
        ]);

        Orgsetups::create([
            'type' => 'Individual',
            'registration_name' => 'Maria Santos',
            'line_of_business' => 'Online Retail',
            'address_line' => 'Brgy. Binondo',
            'region' => 'NCR',
            'province' => 'Metro Manila',
            'city' => 'Manila',
            'zip_code' => '1006',
            'contact_number' => '09181234567',
            'email' => 'maria.santos@gmail.com',
            'tin' => '456-789-012-000',
            'rdo' => 041,
            'tax_type' => 'Percentage Tax',
            'registration_date' => Carbon::parse('2021-06-15'),
            'start_date' => Carbon::parse('2021-06-01'),
            'financial_year_end' => Carbon::parse('2024-12-31'),
        ]);

        Orgsetups::create([
            'type' => 'Non-individual',
            'registration_name' => 'Green Earth Inc.',
            'line_of_business' => 'Agricultural Products',
            'address_line' => 'Eco Park',
            'region' => 'Region 1',
            'province' => 'Ilocos Norte',
            'city' => 'Laoag City',
            'zip_code' => '2900',
            'contact_number' => '09451234567',
            'email' => 'contact@greenearth.com',
            'tin' => '567-890-123-000',
            'rdo' => 030,
            'tax_type' => 'Value-Added Tax',
            'registration_date' => Carbon::parse('2018-09-20'),
            'start_date' => Carbon::parse('2018-09-01'),
            'financial_year_end' => Carbon::parse('2024-12-31'),
        ]);
    }
}
