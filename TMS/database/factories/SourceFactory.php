<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Source;
use App\Models\WithHolding;
use Illuminate\Database\Eloquent\Factories\Factory;

class SourceFactory extends Factory
{
    protected $model = Source::class;

    public function definition()
    {
        return [
            'withholding_id' => WithHolding::factory(), // Associate with WithHoldingFactory
            'employee_id' => Employee::factory(), // Associate with EmployeeFactory
            'payment_date' => $this->faker->dateTimeThisYear(),
            'gross_compensation' => $this->faker->randomFloat(2, 10000, 50000),
            'tax_due' => $this->faker->randomFloat(2, 1000, 5000),
            'statutory_minimum_wage' => $this->faker->randomFloat(2, 5000, 20000),
            'holiday_pay' => $this->faker->randomFloat(2, 1000, 3000),
            'overtime_pay' => $this->faker->randomFloat(2, 500, 2000),
            'night_shift_differential' => $this->faker->randomFloat(2, 100, 500),
            'hazard_pay' => $this->faker->randomFloat(2, 500, 2000),
            'month_13_pay' => $this->faker->randomFloat(2, 1000, 3000),
            'de_minimis_benefits' => $this->faker->randomFloat(2, 100, 500),
            'sss_gsis_phic_hdmf_union_dues' => $this->faker->randomFloat(2, 500, 2000),
            'other_non_taxable_compensation' => $this->faker->randomFloat(2, 100, 1000),
        ];
    }
}
