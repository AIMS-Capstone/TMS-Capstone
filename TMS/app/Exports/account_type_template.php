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
            ['Cost of Sales']
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
