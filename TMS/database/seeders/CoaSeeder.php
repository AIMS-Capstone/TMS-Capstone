<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoaSeeder extends Seeder
{
    public function run()
    {

        $coaData = [
            // Assets
            ['type' => 'Assets', 'sub_type' => 'Current Assets', 'code' => '1000', 'name' => 'Cash', 'description' => 'Cash in hand and at bank'],
            ['type' => 'Assets', 'sub_type' => 'Current Assets', 'code' => '1010', 'name' => 'Accounts Receivable', 'description' => 'Amounts owed by customers'],
            ['type' => 'Assets', 'sub_type' => 'Fixed Assets', 'code' => '1500', 'name' => 'Property, Plant, and Equipment', 'description' => 'Company’s property and equipment'],

            // Liabilities
            ['type' => 'Liabilities', 'sub_type' => 'Current Liabilities', 'code' => '2000', 'name' => 'Accounts Payable', 'description' => 'Amounts owed to suppliers'],
            ['type' => 'Liabilities', 'sub_type' => 'Long-Term Liabilities', 'code' => '2500', 'name' => 'Bank Loan', 'description' => 'Long-term bank loan'],

            // Equity
            ['type' => 'Equity', 'sub_type' => null, 'code' => '3000', 'name' => 'Owner’s Capital', 'description' => 'Initial investment from owners'],
            ['type' => 'Equity', 'sub_type' => null, 'code' => '3100', 'name' => 'Retained Earnings', 'description' => 'Accumulated profits'],

            // Revenue
            ['type' => 'Revenue', 'sub_type' => 'Sales', 'code' => '4000', 'name' => 'Sales Revenue', 'description' => 'Revenue from primary business operations'],
            ['type' => 'Revenue', 'sub_type' => 'Other Income', 'code' => '4100', 'name' => 'Interest Income', 'description' => 'Income from interest'],

            // Cost of Goods Sold (COGS)
            ['type' => 'Expenses', 'sub_type' => 'Cost of Goods Sold', 'code' => '5000', 'name' => 'Raw Materials', 'description' => 'Cost of materials used in production'],
            ['type' => 'Expenses', 'sub_type' => 'Cost of Goods Sold', 'code' => '5010', 'name' => 'Direct Labor', 'description' => 'Labor costs directly associated with production'],
            ['type' => 'Expenses', 'sub_type' => 'Cost of Goods Sold', 'code' => '5020', 'name' => 'Manufacturing Overhead', 'description' => 'Indirect costs associated with production'],
            ['type' => 'Expenses', 'sub_type' => 'Cost of Goods Sold', 'code' => '5030', 'name' => 'Freight-In', 'description' => 'Shipping costs for inbound goods'],

            // Operating Expenses
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '6000', 'name' => 'Rent Expense', 'description' => 'Monthly rent payments'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '6010', 'name' => 'Salaries Expense', 'description' => 'Employee salaries'],
            ['type' => 'Expenses', 'sub_type' => 'Operating Expenses', 'code' => '6020', 'name' => 'Office Supplies', 'description' => 'Cost of office supplies'],
            ['type' => 'Expenses', 'sub_type' => 'Depreciation', 'code' => '6100', 'name' => 'Depreciation Expense', 'description' => 'Depreciation of fixed assets'],
        ];

        DB::table('coas')->insert($coaData);
    }
}
