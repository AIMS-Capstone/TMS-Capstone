<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class sources_template implements FromCollection, WithHeadings
{
    /**
     * Return a collection of data for the export.
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Example sources data for the export
        return collect([
            [
                'kim', 'amayon', '2023-12-31', '50000', '4166.67', '5000', '2000',
                '1500', '3000', '500', '500', '300', '150', '200', '300',
            ],
            [
                'ruen', 'gonzalez', '2022-07-20', '60000', '5000', '6000', '2500',
                '2000', '3500', '600', '600', '350', '200', '250', '400',
            ],
            [
                'sean', 'forlanda', '2021-03-30', '70000', '5833.33', '7000', '3000',
                '2500', '4000', '700', '700', '400', '250', '300', '500',
            ],
        ]);
    }

    /**
     * Define the headings for the export.
     * @return array
     */
    public function headings(): array
    {
        return [
            'first_name',
            'last_name',
            'payment_date',
            'gross_compensation',
            'statutory_minimum_wage',
            'holiday_pay',
            'overtime_pay',
            'night_shift_differential',
            'hazard_pay',
            'month_13_pay',
            'de_minimis_benefits',
            'sss_gsis_phic_hdmf_union_dues',
            'other_non_taxable_compensation',
            'taxable_compensation',
            'tax_due',
        ];
    }
}
