<?php

namespace App\Exports;

use App\Models\Source;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class SourcesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Source::select( 'employee_id', 'employment_id', 'payment_date', 'gross_compensation', 'taxable_compensation', 'tax_due', 'statutory_minimum_wage', 'holiday_pay', 'overtime_pay', 'night_shift_differential', 'hazard_pay', 'month_13_pay', 'de_minimis_benefit', 'sss_gsis_phic_hdmf_union_dues', 'other_non_taxable_compensation',) 
        ->where('status', 'Active')     
        ->get();

    }

    public function headings(): array
    {
        return [
            'Employee ID',  
            'Employment ID',     
            'Payment Date',             
            'Gross Compensation',           
            'Taxable Compensation', 
            'Tax Due',   
            'Statutory Minimum Wage',           
            'Holiday Pay',                                                          
            'Overtime Pay',
            'Night Shift Differential',
            'Hazard Pay',
            '13th Month Pay',
            'De Minimis Benefits',
            'SSS, GSIS, PHIC, HDMF, UNION Dues',
            'Other Non-Taxable Compensation',
        ];
    }         
}
