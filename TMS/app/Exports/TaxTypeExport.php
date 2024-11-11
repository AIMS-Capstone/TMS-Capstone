<?php

namespace App\Exports;

use App\Models\TaxType;
use Maatwebsite\Excel\Concerns\FromCollection;

class TaxTypeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection

    */
    protected $type;
    public function __construct($type)
    {
        $this->type = $type;
    }

    public function collection()
    {
        return TaxType::select( 'tax_type', 'short_code', 'category', 'VAT', 'description',)
        ->where('transaction_type', $this->type) 
        ->get();

    }
    public function headings(): array
    {
        return [
                         // Column names as desired
            'Tax Type',       
            'Short Code',
            'Category',
            'Tax Rate',
            'Description',
          
        ];
    }
}
