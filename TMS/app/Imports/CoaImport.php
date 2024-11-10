<?php

namespace App\Imports;

use App\Models\Coa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class CoaImport implements ToCollection, ToModel
{
    private $current = 0;
    private $organizationId; // Store the organization_id

    // Accept organization_id as a parameter in the constructor
    public function __construct($organizationId)
    {
        $this->organizationId = $organizationId;
    }

    public function collection(Collection $collection)
    {
        // No changes needed here unless processing the entire collection
    }

    public function model(array $row)
    {
        $this->current++;
        if ($this->current > 1) {
            return new Coa([
                'type' => $row[0],
                'sub_type' => $row[1],
                'code' => $row[2],
                'name' => $row[3],
                'description' => $row[4],
                'organization_id' => $this->organizationId, // Set organization_id here
            ]);
        }
    }
}
