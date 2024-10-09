<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class import_template implements FromCollection, WithHeadings
{
    /**
     * Return a collection of data for the export.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Example data for the COA export
        return collect([
            ['Liabilities', '(Optional)', '123', 'Sed', '(Optional)'],
            ['Assets', 'Current Assets', '456', 'Cash', 'Bank Account'],
            ['Equity', 'Owner\'s Equity', '789', 'Owner\'s Capital', 'Initial Investment'],
        ]);
    }

    /**
     * Define the headings for the export.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Account Type',
            'Sub Category',
            'Code',
            'Name',
            'Description',
        ];
    }
}
