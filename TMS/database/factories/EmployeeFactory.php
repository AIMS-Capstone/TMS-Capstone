<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Employment;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'middle_name' => $this->faker->lastName(),
            'last_name' => $this->faker->lastName(),
            'suffix' => null,
            'date_of_birth' => $this->faker->date('Y-m-d', '2000-01-01'),
            'tin' => $this->faker->unique()->numerify('###-###-###-###'),
            'nationality' => $this->faker->country(),
            'contact_number' => $this->faker->phoneNumber(),
            'organization_id' => 1,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($employee) {
            // Create one address for each employee
            $employee->address()->create(
                Address::factory()->make()->toArray()
            );

            // Create one employment for each employee
            Employment::factory()->create([
                'employee_id' => $employee->id,
            ]);
        });
    }
}
