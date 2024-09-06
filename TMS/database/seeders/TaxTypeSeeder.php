<?php

namespace Database\Seeders;

use App\Models\TaxType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxTypes = [
            ['tax_type' => 'Vat on Sales (Goods)', 'description' => 'Value Added Tax when you sell goods that are liable for VAT', 'VAT' => 12.00],
            ['tax_type' => 'Sales to Government (Goods)', 'description' => 'Sales of goods to the government or any of its political subdivisions, instrumentalities, or agencies including government-owned or controlled corporations', 'VAT' => 12.00],
            ['tax_type' => 'Zero Rated Sales (Goods)', 'description' => 'The seller does not impose VAT in the Philippines to the buyer who is within the Philippines or abroad.', 'VAT' => 0.00],
            ['tax_type' => 'Tax-Exempt Sales (Goods)', 'description' => 'Forms of services that are not taxable due to the provisions made by the BIR', 'VAT' => 0.00],
            ['tax_type' => 'Vat on Sales (Services)', 'description' => 'Value Added Tax when you sell services that are liable for VAT', 'VAT' => 12.00],
            ['tax_type' => 'Sales to Government (Services)', 'description' => 'Sales of services to the government or any of its political subdivisions, instrumentalities, or agencies including government-owned or controlled corporations', 'VAT' => 12.00],
            ['tax_type' => 'Zero Rated Sales (Services)', 'description' => 'The seller does not impose VAT in the Philippines to the buyer who is within the Philippines or abroad.', 'VAT' => 0.00],
            ['tax_type' => 'Tax-Exempt Sales (Services)', 'description' => 'Forms of services that are not taxable due to the provisions made by the BIR', 'VAT' => 0.00],
            ['tax_type' => 'Non-Tax', 'description' => 'These transactions are not subject to VAT but may fall under other tax types.', 'VAT' => 0.00],
        ];

        // Insert the tax types into the tax_types table
        foreach ($taxTypes as $taxType) {
            TaxType::create($taxType);
        }
    }
}
