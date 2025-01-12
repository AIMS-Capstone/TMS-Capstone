<?php

namespace App\Exports;

use App\Models\coa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CoaExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return coa::select( 'code', 'type', 'sub_type', 'name', 'description', )
        ->where('status', 'Active')
        ->get();

    }
    public function headings(): array
    {
        return [
            'Code',       
            'Type',
            'Sub Type',
            'Name',
            'Description',
        ];
    }
}
