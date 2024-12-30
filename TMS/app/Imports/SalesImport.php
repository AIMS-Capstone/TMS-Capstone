<?php
namespace App\Imports;

use App\Models\Atc;
use App\Models\Coa;
use App\Models\Contacts;
use App\Models\TaxRow;
use App\Models\TaxType;
use App\Models\Transactions;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Arr;

class SalesImport implements ToCollection, WithHeadingRow
{
    public $importedData = [];
    protected $errors = [];
    protected $validationErrors = [];
    protected $rowErrors = [];
    protected $mappedData = [];

    public function collection(\Illuminate\Support\Collection $rows)
    {
        try {
            // Remove empty rows and trim values
            $cleanRows = $rows->filter(function ($row) {
                return !empty(array_filter($row->toArray()));
            })->map(function ($row) {
                return collect($row)->map(function ($value) {
                    return is_string($value) ? trim($value) : $value;
                });
            });

            if ($cleanRows->isEmpty()) {
                throw new Exception('No valid data found in the uploaded file.');
            }

            // Convert to array and store
            $this->importedData = $cleanRows->toArray();
            
            // Log the data for debugging
            Log::info('Imported data count: ' . count($this->importedData));
            Log::info('First row sample: ' . json_encode(reset($this->importedData)));

        } catch (Exception $e) {
            Log::error('Import collection error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function validateData(array $cleanData)
{
    $this->validationErrors = [];
    $this->rowErrors = [];
    $this->mappedData = [];

    if (empty($cleanData)) {
        $this->validationErrors[] = "No data available for validation";
        return false;
    }

    // Reindex the array numerically to avoid potential issues with non-integer keys
    $cleanData = array_values($cleanData);

    $isValid = true;
    foreach ($cleanData as $index => $row) {
        $rowNumber = $index + 2; // Assuming headers are on the first row
        $rowValid = $this->validateRow($row, $rowNumber);
        if (!$rowValid) {
            $isValid = false;
        }
        $this->mappedData[] = $row;
    }

    return $isValid;
}

    public function setImportedData(array $data)
    {
        $this->importedData = $data;
    }

    protected function validateRow($row, $rowNumber)
    {
        // Log the row for debugging
        Log::debug("Starting validation for row {$rowNumber}: " . json_encode($row));
    
        $validationFunctions = [
            'date' => [$this, 'validateDate'],
            'amount' => [$this, 'validateAmount'],
            'tax_type' => [$this, 'validateTaxTypeAndCategory'],
            'category' => [$this, 'validateTaxTypeAndCategory'],
            'atc' => [$this, 'validateAtc'],
            'coa_code' => [$this, 'validateCoa'],
            'contact_tin' => [$this, 'validateContactInfo'],
            'contact_name' => [$this, 'validateContactInfo'],
            'first_name' => [$this, 'validateContactInfo'],
            'last_name' => [$this, 'validateContactInfo'],
            'address_line' => [$this, 'validateContactInfo'],
            'city' => [$this, 'validateContactInfo'],
            'zip_code' => [$this, 'validateContactInfo'],
            'invoice_no' => [$this, 'validateDocumentNumbers'],
            'reference_no' => [$this, 'validateDocumentNumbers']
        ];
    
        $isValid = true;
        
        // Always validate required fields, regardless of whether they exist in the row
        $requiredFields = ['date', 'amount', 'tax_type', 'category'];
        foreach ($requiredFields as $field) {
            if (!isset($row[$field]) || $row[$field] === null) {
                $this->rowErrors[$rowNumber][] = ucfirst($field) . " is required";
                $isValid = false;
            }
        }
    
        foreach ($validationFunctions as $field => $validator) {
            // Create a subset of the row data needed for this validation
            $validationData = $this->getValidationDataForField($field, $row);
            
            // Call the validator and track the result
            $fieldResult = call_user_func($validator, $validationData, $rowNumber);
            Log::debug("Field {$field} validation result: " . ($fieldResult ? 'true' : 'false'));
            
            if (!$fieldResult) {
                $isValid = false;
            }
        }
    
        Log::debug("Final validation result for row {$rowNumber}: " . ($isValid ? 'true' : 'false'));
        return $isValid;
    }

    protected function getValidationDataForField($field, $row)
    {
        // Return relevant data based on the field being validated
        switch ($field) {
            case 'tax_type':
            case 'category':
                return [
                    'tax_type' => $row['tax_type'] ?? null,
                    'category' => $row['category'] ?? null
                ];
            case 'contact_name':
            case 'first_name':
            case 'last_name':
            case 'contact_tin':
            case 'address_line':
            case 'city':
            case 'zip_code':
                return [
                    'contact_name' => $row['contact_name'] ?? null,
                    'first_name' => $row['first_name'] ?? null,
                    'last_name' => $row['last_name'] ?? null,
                    'contact_tin' => $row['contact_tin'] ?? null,
                    'address_line' => $row['address_line'] ?? null,
                    'city' => $row['city'] ?? null,
                    'zip_code' => $row['zip_code'] ?? null
                ];
            default:
                return [$field => $row[$field]];
        }
    }

    protected function validateDate($data, $rowNumber)
    {
        $date = $data['date'] ?? null;
    
        // Log the date value for debugging
        Log::debug("Validating date for row {$rowNumber}: " . ($date ?? 'null'));
    
        // Check if date is null or empty
        if ($date === null || $date === '') {
            if (!in_array("Date is required", $this->rowErrors[$rowNumber] ?? [])) {
                $this->rowErrors[$rowNumber][] = "Date is required";
            }
            return false;
        }
    
        try {
            $parsedDate = Carbon::parse($date);
            if ($parsedDate > Carbon::now()) {
                if (!in_array("Date cannot be in the future", $this->rowErrors[$rowNumber] ?? [])) {
                    $this->rowErrors[$rowNumber][] = "Date cannot be in the future";
                }
                return false;
            }
            return true;
        } catch (Exception $e) {
            if (!in_array("Invalid date format. Unable to parse: {$date}", $this->rowErrors[$rowNumber] ?? [])) {
                $this->rowErrors[$rowNumber][] = "Invalid date format. Unable to parse: {$date}";
            }
            return false;
        }
    }
    

    protected function validateAmount($data, $rowNumber)
    {
        $amount = $data['amount'] ?? null;
        if (!isset($amount)) {
            $this->rowErrors[$rowNumber][] = "Amount is required";
            return false;
        }

        if (!is_numeric($amount)) {
            $this->rowErrors[$rowNumber][] = "Amount must be a number";
            return false;
        }

        if ($amount <= 0) {
            $this->rowErrors[$rowNumber][] = "Amount must be greater than zero";
            return false;
        }

        return true;
    }

    protected function validateTaxTypeAndCategory($data, $rowNumber)
    {
        $taxType = $data['tax_type'] ?? null;
        $category = $data['category'] ?? null;

        if (empty($taxType) || empty($category)) {
            $this->rowErrors[$rowNumber][] = "Tax type and category are required";
            return false;
        }

        $taxTypeModel = TaxType::where('short_code', $taxType)
            ->where('category', $category)
            ->first();

        if (!$taxTypeModel) {
            $this->rowErrors[$rowNumber][] = "Invalid tax type ({$taxType}) or category ({$category})";
            return false;
        }

        return true;
    }

    protected function validateAtc($data, $rowNumber)
    {
        $atc = $data['atc'] ?? null;
        if (!empty($atc)) {
            $atcModel = Atc::where('tax_code', $atc)->first();
            if (!$atcModel) {
                $this->rowErrors[$rowNumber][] = "Invalid ATC code: {$atc}";
                return false;
            }
        }
        return true;
    }

    protected function validateCoa($data, $rowNumber)
    {
        $coaCode = $data['coa_code'] ?? null;
        if (!empty($coaCode)) {
            $coa = Coa::where('code', $coaCode)->first();
            if (!$coa) {
                $this->rowErrors[$rowNumber][] = "Invalid COA code: {$coaCode}";
                return false;
            }
        }
        return true;
    }

    protected function validateContactInfo($data, $rowNumber)
    {
        // Validate TIN format if provided
        if (!empty($data['contact_tin'])) {
            if (!preg_match('/^\d{3}-\d{3}-\d{3}$|^\d{3}-\d{3}-\d{3}-\d{3}$|^\d{3}-\d{3}-\d{3}-\d{5}$/', $data['contact_tin'])) {
                $this->rowErrors[$rowNumber][] = "Invalid TIN format. Accepted formats: XXX-XXX-XXX, XXX-XXX-XXX-XXX, or XXX-XXX-XXX-XXXXX";
                return false;
            }
        }

        // Validate that either contact_name or individual name fields are provided
        if (empty($data['contact_name']) && 
            (empty($data['first_name']) || empty($data['last_name']))) {
            $this->rowErrors[$rowNumber][] = "Either contact name or first and last name must be provided";
            return false;
        }

        // Validate required address fields
        $requiredFields = [
            'address_line' => 'Address',
            'city' => 'City',
            'zip_code' => 'ZIP code'
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                $this->rowErrors[$rowNumber][] = "{$label} is required";
                return false;
            }
        }

        return true;
    }

    protected function validateDocumentNumbers($data, $rowNumber)
    {
        if (isset($data['invoice_no'])) {
            if (empty($data['invoice_no'])) {
                $this->rowErrors[$rowNumber][] = "Invoice number is required";
                return false;
            }

            if (!preg_match('/^[A-Za-z0-9-]+$/', $data['invoice_no'])) {
                $this->rowErrors[$rowNumber][] = "Invoice number contains invalid characters";
                return false;
            }
        }

        if (isset($data['reference_no'])) {
            if (empty($data['reference_no'])) {
                $this->rowErrors[$rowNumber][] = "Reference number is required";
                return false;
            }

            if (!preg_match('/^[A-Za-z0-9-]+$/', $data['reference_no'])) {
                $this->rowErrors[$rowNumber][] = "Reference number contains invalid characters";
                return false;
            }
        }

        return true;
    }

    public function getValidationErrors()
    {
        return [
            'rowErrors' => $this->rowErrors,
            'validationErrors' => $this->validationErrors
        ];
    }

    public function getImportedData()
    {
        Log::info('getImportedData called. Current count: ' . count($this->importedData));
        return $this->importedData;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getMappedData()
    {
        return $this->mappedData;
    }
}