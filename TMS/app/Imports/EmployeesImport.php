<?php

namespace App\Imports;

use App\Exports\import_template;
use App\Models\Address;
use App\Models\Employee;
use App\Models\Employment;
use Illuminate\Support\Carbon;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeesImport implements ToCollection, WithHeadingRow
{
    public $importedData = [];
    protected $errors = [];

    /**
     * Read and store imported data.
     */
    public function collection(Collection $rows)
    {
        try {
            Log::info('Starting Employee collection import with ' . $rows->count() . ' rows');

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

    public function getImportedData(): array
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
            throw new Exception('Organization ID not found in session.');
        }

        foreach ($this->importedData as $index => $row) {
            try {
                // Step 1: Employee Data
                $employeeData = [];
                foreach ($mappedColumns as $dbField => $excelColumn) {
                    if (isset($row[$excelColumn])) {
                        $employeeData[$dbField] = $row[$excelColumn];
                    }
                }
                $employeeData['organization_id'] = $organizationId;

                $employeeData['date_of_birth'] = Carbon::parse($row['date_of_birth'] ?? null)->format('Y-m-d');

                $employee = Employee::updateOrCreate(
                    ['tin' => $employeeData['tin']],
                    $employeeData
                );

                // Step 2: Employee Address
                $addressData = [
                    'address' => $row['address'] ?? null,
                    'zip_code' => $row['zip_code'] ?? null,
                ];
                $employee->address()->updateOrCreate([], $addressData);

                // Step 3: Employment Data
                $employmentData = [
                    'employer_name' => $row['employer_name'] ?? null,
                    'employment_from' => isset($row['employment_from']) ? Carbon::parse($row['employment_from'])->format('Y-m-d') : null,
                    'employment_to' => isset($row['employment_to']) ? Carbon::parse($row['employment_to'])->format('Y-m-d') : null,
                    'rate' => $row['rate'] ?? null,
                    'rate_per_month' => $row['rate_per_month'] ?? null,
                    'employment_status' => $row['employment_status'] ?? null,
                    'reason_for_separation' => $row['reason_for_separation'] ?? null,
                    'employee_wage_status' => $row['employee_wage_status'] ?? null,
                    'substituted_filing' => $row['substituted_filing'] ?? null,
                    'previous_employer_tin' => $row['previous_employer_tin'] ?? null,
                    'prev_employment_from' => isset($row['prev_employment_from']) ? Carbon::parse($row['prev_employment_from'])->format('Y-m-d') : null,
                    'prev_employment_to' => isset($row['prev_employment_to']) ? Carbon::parse($row['prev_employment_to'])->format('Y-m-d') : null,
                    'prev_employment_status' => $row['prev_employment_status'] ?? null,
                    'employee_id' => $employee->id,
                ];
                $employment = Employment::create($employmentData);

                // Step 4: Employer Address
                $employerAddressData = [
                    'address' => $row['prev_address'] ?? null,
                    'zip_code' => $row['prev_zip_code'] ?? null,
                    'region' => $row['region'] ?? null, 
                ];
                $employment->address()->updateOrCreate([], $employerAddressData);

            } catch (Exception $e) {
                Log::error("Error processing row {$index}: " . $e->getMessage());
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
