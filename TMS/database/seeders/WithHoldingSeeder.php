<?php

namespace Database\Seeders;

use App\Models\WithHolding;
use Illuminate\Database\Seeder;

class WithHoldingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WithHolding::factory()
            ->count(10)
            ->hasSources(5) 
            ->create();
    }
}
