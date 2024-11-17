<?php

namespace App\Imports;

use App\Models\Coa;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CoaImport implements ToCollection, WithHeadingRow
{
    public $importedData = [];
    protected $errors = [];

    public function collection(Collection $rows)
    {
        try {
            Log::info('Starting COA collection import with ' . $rows->count() . ' rows');

            if ($rows->isEmpty()) {
                throw new Exception('No data found in the uploaded file.');
            }

            $organizationId = Session::get('organization_id');
            if (!$organizationId) {
                throw new Exception('Organization ID is missing in the session. Please try re-uploading.');
            }

            $this->importedData = $rows->toArray();
            Log::info('Imported data count: ' . count($this->importedData));

            if (!empty($this->importedData)) {
                Log::info('First row sample: ' . json_encode($this->importedData[0]));
            }

        } catch (Exception $e) {
            Log::error('Import collection error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getImportedData()
    {
        return $this->importedData;
    }

    public function processAndSaveData($mappedColumns)
    {
        if (empty($this->importedData)) {
            $this->importedData = session()->get('importedData', []);
        }

        $organizationId = Session::get('organization_id');
        if (!$organizationId) {
            Log::error('Organization ID missing from session');
            throw new Exception('Organization ID not found in session.');
        }

        foreach ($this->importedData as $index => $row) {
            try {
                $data = [];
                    foreach ($mappedColumns as $dbField => $excelColumn) {
                        if ($dbField === 'sub_type') {
                            $data['sub_type'] = $row[$excelColumn] ?? $row[strtolower($excelColumn)] ?? null;
                        } else {
                            $data[$dbField] = $row[$excelColumn] ?? null;
                        }
                    }


                $data['organization_id'] = $organizationId;
                $data['status'] = 'Active';


                // Save or update the COA entry without specifying organization_id or status
                Coa::updateOrCreate(
                    ['code' => $data['code']], // Assumed unique identifier for COA
                    $data
                );

                Log::info("Successfully processed row {$index}");

            } catch (Exception $e) {
                $this->errors[] = "Row {$index}: " . $e->getMessage();
                Log::error("Error processing row {$index}: " . $e->getMessage());
                continue;
            }
        }


        if (!empty($this->errors)) {
            Log::warning('Import completed with errors: ' . json_encode($this->errors));
        }

        return true;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
