<?php

namespace App\Imports;

use App\Models\coa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class CoaImport implements ToCollection, ToModel
{
    private $current = 0;
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {

    }

    public function model(array $row)
    {
        $this->current++;
        if ($this->current > 1) {

            $Coa = new coa;
            $Coa->type = $row[0];
            $Coa->sub_type = $row[1];
            $Coa->code = $row[2];
            $Coa->name = $row[3];
            $Coa->description = $row[4];
            $Coa->save();
        }

    }
}
