<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Employment;
use App\Models\User;
use App\Models\WithHolding;
use Illuminate\Database\Eloquent\Factories\Factory;

class WithHoldingFactory extends Factory
{
    protected $model = WithHolding::class;

    public function definition()
    {
        return [
            'title' => 'Monthly Remittance Return of Income Taxes Withheld on Compensation',
            'organization_id' => 1,
            'type' => '1601C', // Set a default type for now
            'month' => $this->faker->numberBetween(1, 12),
            'year' => $this->faker->numberBetween(1900, now()->year),
            'created_by' => User::factory(), // Assumes UserFactory exists
            'updated_by' => $this->faker->boolean(50) ? User::factory() : null,
        ];
    }
}
