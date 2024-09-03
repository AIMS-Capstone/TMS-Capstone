<?php
namespace Database\Factories;

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
        return [
            'name' => fake()->company(),
            'type' => 'Non-Individual',
            'tax_identification_number' => $this->generateTIN(),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'zip'=>fake()->postcode()
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
