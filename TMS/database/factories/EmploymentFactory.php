<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Employment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmploymentFactory extends Factory
{
    protected $model = Employment::class;

    public function definition()
    {
        return [
            'employer_name' => $this->faker->company(),
            'employment_from' => $this->faker->date('Y-m-d', '-5 years'),
            'employment_to' => $this->faker->boolean(80) ? $this->faker->date('Y-m-d', '-1 year') : null,
            'rate' => $this->faker->randomFloat(2, 30000, 70000),
            'rate_per_month' => $this->faker->randomFloat(2, 2500, 6000),
            'employment_status' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract']),
            'reason_for_separation' => $this->faker->boolean(20) ? $this->faker->sentence() : null,
            'employee_wage_status' => $this->faker->randomElement(['Above Minimum Wage Earner', 'Minimum Wage Earner']),
            'substituted_filing' => $this->faker->boolean(),
            'with_previous_employer' => $this->faker->boolean(),
            'previous_employer_tin' => $this->faker->boolean() ? $this->faker->numerify('###-###-###-###') : null,
            'prev_employment_from' => $this->faker->boolean(50) ? $this->faker->date('Y-m-d', '-10 years') : null,
            'prev_employment_to' => $this->faker->boolean(50) ? $this->faker->date('Y-m-d', '-6 years') : null,
            'prev_employment_status' => $this->faker->boolean(50) ? $this->faker->randomElement(['Full-time', 'Part-time', 'Contract']) : null,
            'employee_id' => Employee::factory(), // Associate with EmployeeFactory
        ];
    }
}
