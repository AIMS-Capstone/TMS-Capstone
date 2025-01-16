<?php
namespace Database\Factories;

use App\Models\OrgSetup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contacts>
 */
class ContactsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $orgSetupId = OrgSetup::inRandomOrder()->first()->id ?? OrgSetup::first()->id;
        
        // Alternate between 'Vendor' and 'Customer'
        $contactType = fake()->randomElement(['Vendor', 'Customer']);

        // Alternate between 'Individual' and 'Non-Individual'
        $classification = fake()->randomElement(['Individual', 'Non-Individual']);

        return [
            'organization_id' => 1, // Hardcoded to OrgSetup with ID 1
            'bus_name' => fake()->company(),
            'contact_type' => $contactType,
            'classification' => $classification,
            'contact_tin' => $this->generateTIN(),
            'contact_address' => fake()->address(),
            'contact_city' => fake()->city(),   
            'contact_zip' =>  fake()->numberBetween(1000, 9999),
            'classification' => 'Individual',
        ];
    }   

    /**
     * Generate a TIN (Tax Identification Number) in the format 3-3-3-4.
     *
     * @return string
     */
    private function generateTIN(): string
    {
        return sprintf('%03d-%03d-%03d-%04d',
            rand(100, 999), // First 3 digits
            rand(100, 999), // Second 3 digits
            rand(100, 999), // Third 3 digits
            rand(1000, 9999) // Last 4 digits
        );
    }
}
