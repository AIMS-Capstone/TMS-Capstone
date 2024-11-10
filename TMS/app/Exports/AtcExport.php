<?php

namespace App\Exports;

use App\Models\atc;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AtcExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $type; // To specify 'sale' or 'purchase'

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function collection()
    {
        return Atc::select('id', 'tax_code', 'transaction_type', 'category', 'coverage', 'description', 'tax_rate')
        ->where('transaction_type', $this->type) 
        ->get();

    }
    public function headings(): array
    {
        return [
            'ID',                // Column names as desired
            'Tax Code',       
            'Transaction Type',
            'Category',
            'Coverage',
            'Description',
            'Tax Rate'
        ];
    }
}
