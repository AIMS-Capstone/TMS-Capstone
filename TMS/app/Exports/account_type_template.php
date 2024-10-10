<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class account_type_template implements FromCollection, WithHeadings
{
    /**
     * Return a collection of account types.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Example account types
        return collect([
            ['Assets'],
            ['Liabilities'],
            ['Equity'],
            ['Revenue'],
            ['Expenses'],
            ['Gains'],
            ['Losses'],
            ['Other Taxable Income from Operations not Subject to Final Tax'],
            ['Non Operating Income'],
            ['Gross Sales Revenues Receipts Fees not subject to Withholding Tax'],
            ['Sales Revenues Receipts Fees'],
            ['Sales Returns  Allowances and Discounts']
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
        ];
    }
}
