<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class sub_type_template implements FromCollection, WithHeadings
{
    /**
     * Return a collection of account types with subcategories.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([
            ['Assets', 'Cash'],
            ['Assets', 'Accounts Receivable'],
            ['Assets', 'Inventory'],
            ['Liabilities', 'Accounts Payable'],
            ['Liabilities', 'Loans Payable'],
            ['Liabilities', 'Accrued Expenses'],
            ['Equity', 'Common Stock'],
            ['Equity', 'Retained Earnings'],
            ['Revenue', 'Sales Revenue'],
            ['Revenue', 'Service Revenue'],
            ['Cost of Sales', 'Direct Materials'],
            ['Cost of Sales', 'Direct Labor'],
            ['Cost of Sales', 'Manufacturing Overhead'],
            ['Expenses', 'Salaries Expense'],
            ['Expenses', 'Rent Expense'],
            ['Expenses', 'Utilities Expense'],
        ]);
    }

    /**
     * Define the heading for the export.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Account Type',
            'Sub Category',
        ];
    }
}
