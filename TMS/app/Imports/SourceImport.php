<?php

namespace App\Imports;

use App\Models\Employee;
use App\Models\Source;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SourceImport implements ToCollection, WithHeadingRow
{
    public $importedData = [];
    protected $errors = [];

    public function collection(Collection $rows)
    {
        try {
            Log::info('Starting Source collection import with ' . $rows->count() . ' rows');

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

    public function processAndSaveData($mappedColumns, $withholdingId)
    {
        foreach ($this->importedData as $index => $row) {
            try {
                $data = [];
                foreach ($mappedColumns as $dbField => $excelColumn) {
                    $data[$dbField] = $row[$excelColumn] ?? null;
                }

                // Match employee_id using first_name and last_name
                if (isset($data['first_name']) && isset($data['last_name'])) {
                    $employee = Employee::whereRaw('LOWER(TRIM(first_name)) = ?', [strtolower(trim($data['first_name']))])
                        ->whereRaw('LOWER(TRIM(last_name)) = ?', [strtolower(trim($data['last_name']))])
                        ->first();

                    if ($employee) {
                        $data['employee_id'] = $employee->id;

                        // Retrieve employee wage status
                        if ($employee->latestEmployment) {
                            $data['employee_wage_status'] = $employee->latestEmployment->employee_wage_status;
                        } else {
                            throw new Exception('Employment record not found for employee: ' . $data['first_name'] . ' ' . $data['last_name']);
                        }
                    } else {
                        throw new Exception('Employee not found for name: ' . $data['first_name'] . ' ' . $data['last_name']);
                    }
                }

                // Add withholding_id
                $data['withholding_id'] = $withholdingId;

                // Save the source record
                Source::updateOrCreate([
                    'withholding_id' => $withholdingId,
                    'employee_id' => $data['employee_id'],
                ], $data);

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
