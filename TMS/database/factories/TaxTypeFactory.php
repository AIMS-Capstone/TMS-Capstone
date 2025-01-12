<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaxType>
 */
class TaxTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
     
            return [
                // Default values for one tax type (can be overridden by specific states)
                'tax_type' => 'Vat on Sales (Goods)',
                'description' => 'Value Added Tax when you sell goods that are liable for VAT',
                'VAT' => 12.00,
            ];
        }
    
        // Static state methods for each specific tax type
        public function vatOnSalesGoods()
        {
            return $this->state([
                'tax_type' => 'Vat on Sales (Goods)',
                'description' => 'Value Added Tax when you sell goods that are liable for VAT',
                'VAT' => 12.00,
            ]);
        }
    
        public function salesToGovernmentGoods()
        {
            return $this->state([
                'tax_type' => 'Sales to Government (Goods)',
                'description' => 'Sales of goods to the government or any of its political subdivisions, instrumentalities, or agencies including government-owned or controlled corporations',
                'VAT' => 12.00,
            ]);
        }
    
        public function zeroRatedSalesGoods()
        {
            return $this->state([
                'tax_type' => 'Zero Rated Sales (Goods)',
                'description' => 'The seller does not impose VAT in the Philippines to the buyer who is within the Philippines or abroad.',
                'VAT' => 0.00,
            ]);
        }
    
        public function taxExemptSalesGoods()
        {
            return $this->state([
                'tax_type' => 'Tax-Exempt Sales (Goods)',
                'description' => 'Forms of services that are not taxable due to the provisions made by the BIR',
                'VAT' => 0.00,
            ]);
        }
    
        public function vatOnSalesServices()
        {
            return $this->state([
                'tax_type' => 'Vat on Sales (Services)',
                'description' => 'Value Added Tax when you sell services that are liable for VAT',
                'VAT' => 12.00,
            ]);
        }
    
        public function salesToGovernmentServices()
        {
            return $this->state([
                'tax_type' => 'Sales to Government (Services)',
                'description' => 'Sales of services to the government or any of its political subdivisions, instrumentalities, or agencies including government-owned or controlled corporations',
                'VAT' => 12.00,
            ]);
        }
    
        public function zeroRatedSalesServices()
        {
            return $this->state([
                'tax_type' => 'Zero Rated Sales (Services)',
                'description' => 'The seller does not impose VAT in the Philippines to the buyer who is within the Philippines or abroad.',
                'VAT' => 0.00,
            ]);
        }
    
        public function taxExemptSalesServices()
        {
            return $this->state([
                'tax_type' => 'Tax-Exempt Sales (Services)',
                'description' => 'Forms of services that are not taxable due to the provisions made by the BIR',
                'VAT' => 0.00,
            ]);
        }
    
        public function nonTax()
        {
            return $this->state([
                'tax_type' => 'Non-Tax',
                'description' => 'These transactions are not subject to VAT but may fall under other tax types.',
                'VAT' => 0.00,
            ]);
        }
    }

