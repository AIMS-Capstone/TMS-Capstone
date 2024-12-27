<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class employee_template implements FromCollection, WithHeadings
{
    /**
     * Return a collection of data for the export.
     * @return \Illuminate\Support\Collection
     */
     public function collection()
    {
        // Example employee data for the export
        return collect([
            [
                'John', 'Doe', 'Michael', '', '06-15-1985', '123-456-789', 'Filipino', 
                '09171234567', '123 Main St', '1101', 'ABC Corporation', 
                '2020-01-15', '2023-12-31', '50000', '4166.67', 'Full-time', 
                'Regular', 'Yes', '321-654-987', 'Yes', 
                '2018-05-01', '2019-12-15', 'Resigned', '456 2nd St', '1200', 'NCR'
            ],
            [
                'Jane', 'Smith', 'Ann', 'Jr', '09-22-1990', '234-567-891', 'American', 
                '09183456789', '789 Oak St', '1300', 'XYZ Ltd.', 
                '2019-03-10', '2022-07-20', '60000', '5000.00', 'Part-time', 
                'Casual', 'No', '', 'No', 
                '', '', '', '', '', 'Region 4-A'
            ],
            [
                'Carlos', 'Gonzalez', '', 'Sr', '11-05-1978', '345-678-912', 'Mexican', 
                '09185678901', '456 Elm St', '1400', 'DEF Inc.', 
                '2017-07-01', '2021-03-30', '70000', '5833.33', 'Contractual', 
                'Project-based', 'Yes', '123-321-555', 'Yes', 
                '2015-01-01', '2016-12-20', 'Retired', '789 Pine St', '1500', 'Region 7'
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
            'first name',
            'last name',
            'middle name',
            'suffix',
            'date of birth',
            'tin',
            'nationality',
            'contact number',
            'address',
            'zip code',
            'employer name',
            'employment from',
            'employment to',
            'rate',
            'rate per month',
            'employment status',
            'employee wage status',
            'reason for separation',
            'substituted filling',
            'previous employer tin',
            'with previous employer',
            'previous employment from',
            'previous employment to',
            'previous employment status',
            'prev address', 
            'prev zip code',  
            'region',
        ];
    }
}
