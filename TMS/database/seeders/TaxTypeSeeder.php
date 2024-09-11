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
            // Sales Tax Types
            ['tax_type' => 'Vat on Sales (Goods)', 'description' => 'Value Added Tax when you sell goods that are liable for VAT', 'VAT' => 12.00, 'transaction_type' => 'sales'],
            ['tax_type' => 'Sales to Government (Goods)', 'description' => 'Sales of goods to the government or any of its political subdivisions, instrumentalities, or agencies including government-owned or controlled corporations', 'VAT' => 12.00, 'transaction_type' => 'sales'],
            ['tax_type' => 'Zero Rated Sales (Goods)', 'description' => 'The seller does not impose VAT in the Philippines to the buyer who is within the Philippines or abroad.', 'VAT' => 0.00, 'transaction_type' => 'sales'],
            ['tax_type' => 'Tax-Exempt Sales (Goods)', 'description' => 'Forms of services that are not taxable due to the provisions made by the BIR', 'VAT' => 0.00, 'transaction_type' => 'sales'],
            ['tax_type' => 'Vat on Sales (Services)', 'description' => 'Value Added Tax when you sell services that are liable for VAT', 'VAT' => 12.00, 'transaction_type' => 'sales'],
            ['tax_type' => 'Sales to Government (Services)', 'description' => 'Sales of services to the government or any of its political subdivisions, instrumentalities, or agencies including government-owned or controlled corporations', 'VAT' => 12.00, 'transaction_type' => 'sales'],
            ['tax_type' => 'Zero Rated Sales (Services)', 'description' => 'The seller does not impose VAT in the Philippines to the buyer who is within the Philippines or abroad.', 'VAT' => 0.00, 'transaction_type' => 'sales'],
            ['tax_type' => 'Tax-Exempt Sales (Services)', 'description' => 'Forms of services that are not taxable due to the provisions made by the BIR', 'VAT' => 0.00, 'transaction_type' => 'sales'],
            ['tax_type' => 'Non-Tax', 'description' => 'These transactions are not subject to VAT but may fall under other tax types.', 'VAT' => 0.00, 'transaction_type' => 'sales'],
            
            // Purchase Tax Types
            ['tax_type' => 'Vat on Purchases (Goods)', 'description' => 'Value Added Tax when you purchase goods that are liable for VAT', 'VAT' => 12.00, 'transaction_type' => 'purchase'],
            ['tax_type' => 'Capital Goods', 'description' => 'Goods that are used in producing other goods', 'VAT' => 12.00, 'transaction_type' => 'purchase'],
            ['tax_type' => 'Goods Not Qualified for Input Tax', 'description' => 'Goods that can’t be taxed under provisions from the BIR', 'VAT' => 0.00, 'transaction_type' => 'purchase'],
            ['tax_type' => 'Importation of Goods', 'description' => 'Process of bringing goods from another country for the purpose of reselling', 'VAT' => 12.00, 'transaction_type' => 'purchase'],
            ['tax_type' => 'Tax-Exempt Purchases (Importation of Goods)', 'description' => 'Goods brought from another country that is exempted from tax', 'VAT' => 0.00, 'transaction_type' => 'purchase'],
            ['tax_type' => 'Vat on Purchases (Services)', 'description' => 'Value Added Tax when you purchase services that are liable for VAT', 'VAT' => 12.00, 'transaction_type' => 'purchase'],
            ['tax_type' => 'Services Not Qualified for Input Tax', 'description' => 'Services that can’t be taxed under provisions from the BIR', 'VAT' => 0.00, 'transaction_type' => 'purchase'],
            ['tax_type' => 'Services by Non-Residents', 'description' => 'Service provided by an alien that may apply for tax relief', 'VAT' => 0.00, 'transaction_type' => 'purchase'],
            ['tax_type' => 'Non-Tax', 'description' => 'These transactions are not subject to VAT but may fall under other tax types.', 'VAT' => 0.00, 'transaction_type' => 'purchase'],
        ];

        // Insert the tax types into the tax_types table
        foreach ($taxTypes as $taxType) {
            TaxType::create($taxType);
        }
    }
}
