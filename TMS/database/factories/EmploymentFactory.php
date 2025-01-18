<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmploymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'employer_name' => $this->faker->company(),
            'employment_from' => $this->faker->date('Y-m-d', '-5 years'),
            'employment_to' => $this->faker->date('Y-m-d', 'now'),
            'rate' => $this->faker->randomFloat(2, 15000, 50000),
            'rate_per_month' => $this->faker->boolean(),
            'employment_status' => $this->faker->randomElement(['Regular', 'Contractual', 'Probationary']),
            'reason_for_separation' => $this->faker->sentence(),
            'employee_wage_status' => $this->faker->randomElement(['Minimum Wage Earner', 'Above Minimum Wage Earner']),
            'substituted_filing' => $this->faker->boolean(),
            'with_previous_employer' => $this->faker->boolean(),
            'previous_employer_tin' => $this->faker->unique()->numerify('###-###-###-###'),
            'prev_employment_from' => $this->faker->date('Y-m-d', '-10 years'),
            'prev_employment_to' => $this->faker->date('Y-m-d', '-6 years'),
            'prev_employment_status' => $this->faker->randomElement(['Resigned', 'Terminated', 'End of Contract']),
            'employee_id' => null, // This will be assigned by the seeder
        ];
    }
}
