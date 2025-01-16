<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'address' => $this->faker->streetAddress(),
            'zip_code' => $this->faker->numerify('####'), // Ensures 4 digits
            'region' => $this->faker->state(),
        ];
    }
}
