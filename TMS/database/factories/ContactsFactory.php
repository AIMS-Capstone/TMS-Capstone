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
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $orgSetupId = OrgSetup::inRandomOrder()->first()->id ?? OrgSetup::first()->id;
        return [
            'organization_id' => $orgSetupId,
            'bus_name' => fake()->company(),
            'contact_type' => 'Non-Individual',
            'contact_tin' => $this->generateTIN(),
            'contact_address' => fake()->address(),
            'contact_city' => fake()->city(),
            'contact_zip'=>fake()->postcode()
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
            rand(100, 999),    // First 3 digits
            rand(100, 999),    // Second 3 digits
            rand(100, 999),    // Third 3 digits
            rand(1000, 9999)   // Last 4 digits
        );
    }
}
