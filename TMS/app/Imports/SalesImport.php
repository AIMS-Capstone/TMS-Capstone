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
use Illuminate\Support\Facades\DB;

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
    
    public function processAndSaveData(array $importedData)
{
    Log::info('Starting processAndSaveData');
    Log::info('Imported data count at start of processing: ' . count($importedData));

    $processedData = [];
    $organizationId = Session::get('organization_id');
    
    if (!$organizationId) {
        Log::error('Organization ID missing from session');
        throw new Exception('Organization ID not found in session.');
    }
    
    Log::info('Organization ID found: ' . $organizationId);

    foreach ($importedData as $index => $row) {
        Log::info('Processing row ' . $index);
        
        try {
            // Process contact information
            $contactData = $this->processContactData($row);
            Log::info('Contact data processed for row ' . $index);
            
            // Fetch and validate related models
            $taxType = $this->getTaxType($row);
            $atcModel = $this->getAtc($row);
            $coaModel = $this->getCoa($row);
            
            if (!$taxType || !$atcModel || !$coaModel) {
                Log::warning("Row {$index} missing required related models");
                $this->errors[] = "Row {$index}: Missing required related models";
                continue;
            }
            
            // Calculate amounts
            $amount = floatval($row['amount'] ?? 0);
            $taxAmount = $this->calculateTaxAmount($amount, $taxType, $atcModel);
            $netAmount = $this->calculateNetAmount($amount, $taxType, $atcModel);

            // Generate unique key for grouping transactions
            $key = $this->generateKey($row);
            Log::info("Generated key for row {$index}: {$key}");
            
            // Prepare and store processed data
            $processedData[$key][] = $this->prepareProcessedData(
                $row,
                $contactData,
                $taxType,
                $atcModel,
                $coaModel,
                $amount,
                $taxAmount,
                $netAmount,
                $organizationId
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

    Log::info('Processed data count: ' . count($processedData));
    Log::info('Processed data structure: ' . json_encode(array_keys($processedData)));
    
    // Save the processed data and return the result
    return $this->saveProcessedData($processedData);
}

protected function getTaxType(array $row)
{
    return TaxType::where('short_code', $row['tax_type'])
        ->where('category', $row['category'])
        ->first();
}

protected function getAtc(array $row)
{
    if (empty($row['atc'])) {
        return null;
    }
    return Atc::where('tax_code', $row['atc'])->first();
}

protected function prepareProcessedData(
    array $row,
    Contacts $contact,
    TaxType $taxType,
    ?Atc $atc,
    ?Coa $coa,
    float $amount,
    float $taxAmount,
    float $netAmount,
    int $organizationId
) {
    // Validate contact relationship
    if (!$contact || !$contact->id) {
        Log::error('Invalid contact data in prepareProcessedData', [
            'contact' => $contact ?? 'null',
            'row' => $row
        ]);
        throw new Exception('Invalid contact data - contact ID is missing');
    }

    // Ensure we're using the correct contact for this organization
    if ($contact->organization_id !== $organizationId) {
        Log::error('Unexpected organization mismatch', [
            'contact_org_id' => $contact->organization_id,
            'current_org_id' => $organizationId
        ]);
        throw new Exception('Contact organization mismatch - this should not happen after contact processing');
    }

    $processedData = [
        'organization_id' => $organizationId,
        'contact_id' => $contact->id,
        'date' => $row['date'],
        'invoice_no' => $row['invoice_no'] ?? null,
        'reference_no' => $row['reference_no'] ?? null,
        'description' => $row['description'] ?? null,
        'tax_type_id' => $taxType->id,
        'atc_id' => $atc ? $atc->id : null,
        'coa_id' => $coa ? $coa->id : null,
        'amount' => $amount,
        'tax_amount' => $taxAmount,
        'net_amount' => $netAmount
    ];

    Log::info('Prepared processed data', [
        'contact_id' => $contact->id,
        'invoice_no' => $row['invoice_no'] ?? null,
        'amount' => $amount
    ]);

    return $processedData;
}
protected function getCoa(array $row)
{
    if (empty($row['coa_code'])) {
        return null;
    }
    return Coa::where('code', $row['coa_code'])->first();
}

protected function calculateTaxAmount($amount, TaxType $taxType, ?Atc $atc = null)
{
    Log::info('Starting tax amount calculation', [
        'input_amount' => $amount,
        'tax_type_code' => $taxType->short_code ?? 'null',
        'tax_rate' => $taxType->VAT ?? 'null',
        'atc_rate' => $atc ? $atc->tax_rate : 'no ATC'
    ]);

    // Validate inputs
    if (!is_numeric($amount) || $amount <= 0) {
        Log::error('Invalid amount provided', ['amount' => $amount]);
        return 0;
    }

    // If no tax type or rate, return 0
    if (!$taxType || !$taxType->VAT) {
        Log::info('Returning 0 tax amount - no tax type or rate');
        return 0;
    }

    $taxRate = $taxType->VAT;
    $atcRate = $atc ? $atc->tax_rate : 0;
    $finalTaxAmount = 0;

    try {
        switch ($taxType->short_code) {
            case 'PT': // Percentage Tax
                if ($atcRate > 0) {
                    // For PT with ATC, we need to:
                    // 1. First calculate the net amount before ATC (reverse the gross-up)
                    // 2. Then apply the tax rate to the net amount
                    $netAmount = $amount / (1 + ($atcRate / 100));
                    $finalTaxAmount = $netAmount * ($atcRate / 100);
                } else {
                    // For PT without ATC, simply apply the tax rate
                    $finalTaxAmount = $amount * ($taxRate / 100);
                }
                break;

            case 'VAT': // Value Added Tax
                // VAT calculation:
                // 1. Net amount = Gross amount / (1 + VAT rate)
                // 2. VAT amount = Gross amount - Net amount
                $netAmount = $amount / (1 + ($taxRate / 100));
                $finalTaxAmount = $amount - $netAmount;
                break;

            default: // Other tax types
                // Simple percentage calculation
                $finalTaxAmount = $amount * ($taxRate / 100);
                break;
        }

        // Round to 2 decimal places for currency
        $finalTaxAmount = round($finalTaxAmount, 2);

        Log::info('Tax calculation completed', [
            'tax_type' => $taxType->short_code,
            'gross_amount' => $amount,
            'tax_rate' => $taxRate,
            'atc_rate' => $atcRate,
            'final_tax_amount' => $finalTaxAmount
        ]);

        return $finalTaxAmount;

    } catch (\Exception $e) {
        Log::error('Error calculating tax amount', [
            'error' => $e->getMessage(),
            'tax_type' => $taxType->short_code,
            'amount' => $amount
        ]);
        throw new \Exception('Failed to calculate tax amount: ' . $e->getMessage());
    }
}

protected function calculateNetAmount($amount, TaxType $taxType, ?Atc $atc = null)
{
    if (!$taxType || !$taxType->VAT) {
        return $amount;
    }

    $taxRate = $taxType->VAT;
    $atcRate = $atc ? $atc->tax_rate : 0;

    // For Percentage Tax with ATC
    if ($taxType->short_code == 'PT' && $atcRate > 0) {
        return $amount / (1 + ($atcRate / 100));
    }
    
    // For VAT
    if ($taxType->short_code == 'VAT') {
        return $amount / (1 + ($taxRate / 100));
    }
    
    // For other tax types
    return $amount - ($amount * ($taxRate / 100));
}

protected function processContactData(array $row)
{
    Log::info('Starting contact data processing', [
        'tin' => $row['contact_tin'] ?? 'Not provided',
        'business_name' => $row['contact_name'] ?? 'Not provided'
    ]);

    // Prepare business name with validation
    $businessName = $this->prepareBusinessName($row);
    if (!$businessName) {
        throw new Exception('Unable to process contact - no valid business name or individual name provided');
    }

    // Try to find existing contact
    $contact = $this->findExistingContact($row, $businessName);
    
    // Create new contact if none found
    if (!$contact) {
        Log::info('No existing contact found, creating new contact', [
            'business_name' => $businessName
        ]);
        
        $contact = $this->createNewContact($row, $businessName);
    } else {
        Log::info('Found existing contact', [
            'contact_id' => $contact->id,
            'business_name' => $contact->bus_name
        ]);
        
        // Update contact if necessary
        $contact = $this->updateExistingContact($contact, $row, $businessName);
    }

    // Validate final contact state
    $this->validateContactState($contact);

    return $contact;
}

protected function prepareBusinessName(array $row): ?string
{
    // First try to get business name directly
    $businessName = $row['contact_name'] ?? null;

    // If no business name, try to construct from individual names
    if (!$businessName) {
        $nameParts = array_filter([
            $row['first_name'] ?? null,
            $row['middle_name'] ?? null,
            $row['last_name'] ?? null
        ]);
        
        if (!empty($nameParts)) {
            $businessName = implode(' ', $nameParts);
            Log::info('Constructed business name from individual names', [
                'parts' => $nameParts,
                'result' => $businessName
            ]);
        }
    }

    return $businessName ? trim($businessName) : null;
}

protected function findExistingContact(array $row, string $businessName): ?Contacts
{
    $organizationId = Session::get('organization_id');
    
    // Try to find by TIN within the same organization first
    if (!empty($row['contact_tin'])) {
        $contact = Contacts::where('contact_tin', $row['contact_tin'])
                         ->where('organization_id', $organizationId)
                         ->first();
                         
        if ($contact) {
            Log::info('Found contact by TIN in current organization', [
                'contact_id' => $contact->id,
                'tin' => $row['contact_tin']
            ]);
            return $contact;
        }

        // If not found in current organization, check if contact exists in other organizations
        $existingContact = Contacts::where('contact_tin', $row['contact_tin'])
                                 ->where('organization_id', '!=', $organizationId)
                                 ->first();
                                 
        if ($existingContact) {
            Log::info('Found contact in different organization, creating new contact for current organization', [
                'existing_contact_id' => $existingContact->id,
                'existing_org_id' => $existingContact->organization_id,
                'current_org_id' => $organizationId
            ]);
            
            // Create a new contact for current organization with the same details
            return $this->cloneContactForOrganization($existingContact, $organizationId, $row);
        }
    }

    // Try to find by business name within current organization
    $contact = Contacts::where('bus_name', $businessName)
                     ->where('organization_id', $organizationId)
                     ->first();
                     
    if ($contact) {
        Log::info('Found contact by business name in current organization', [
            'contact_id' => $contact->id,
            'business_name' => $businessName
        ]);
        return $contact;
    }

    // Check if contact exists in other organizations by business name
    $existingContact = Contacts::where('bus_name', $businessName)
                             ->where('organization_id', '!=', $organizationId)
                             ->first();
                             
    if ($existingContact) {
        Log::info('Found contact by business name in different organization, creating new contact', [
            'existing_contact_id' => $existingContact->id,
            'existing_org_id' => $existingContact->organization_id,
            'current_org_id' => $organizationId
        ]);
        
        // Create a new contact for current organization with the same details
        return $this->cloneContactForOrganization($existingContact, $organizationId, $row);
    }

    return null;
}

protected function cloneContactForOrganization(Contacts $existingContact, int $newOrganizationId, array $rowData): Contacts
{
    try {
        // Create new contact with data from existing contact but new organization ID
        $newContact = new Contacts([
            'organization_id' => $newOrganizationId,
            'contact_type' => $existingContact->contact_type,
            'bus_name' => $existingContact->bus_name,
            'contact_email' => $rowData['contact_email'] ?? $existingContact->contact_email,
            'contact_phone' => $rowData['contact_phone'] ?? $existingContact->contact_phone,
            'contact_tin' => $rowData['contact_tin'] ?? $existingContact->contact_tin,
            'contact_address' => $rowData['address_line'] ?? $existingContact->contact_address,
            'contact_city' => $rowData['city'] ?? $existingContact->contact_city,
            'contact_zip' => $rowData['zip_code'] ?? $existingContact->contact_zip,
        ]);
        
        $newContact->save();
        
        Log::info('Successfully cloned contact for new organization', [
            'original_contact_id' => $existingContact->id,
            'new_contact_id' => $newContact->id,
            'organization_id' => $newOrganizationId
        ]);
        
        return $newContact;
        
    } catch (\Exception $e) {
        Log::error('Failed to clone contact for new organization', [
            'error' => $e->getMessage(),
            'original_contact_id' => $existingContact->id,
            'organization_id' => $newOrganizationId
        ]);
        throw new Exception('Failed to clone contact: ' . $e->getMessage());
    }
}

protected function createNewContact(array $row, string $businessName): Contacts
{
    try {
        $contact = new Contacts([
            'organization_id' => Session::get('organization_id'),
            'contact_type' => 'business',
            'bus_name' => $businessName,
            'contact_email' => $row['contact_email'] ?? null,
            'contact_phone' => $row['contact_phone'] ?? null,
            'contact_tin' => $row['contact_tin'] ?? null,
            'contact_address' => $row['address_line'] ?? null,
            'contact_city' => $row['city'] ?? null,
            'contact_zip' => $row['zip_code'] ?? null,
        ]);
        
        $contact->save();
        
        Log::info('Successfully created new contact', [
            'contact_id' => $contact->id,
            'business_name' => $businessName,
            'tin' => $row['contact_tin'] ?? 'Not provided'
        ]);
        
        return $contact;
        
    } catch (\Exception $e) {
        Log::error('Failed to create new contact', [
            'error' => $e->getMessage(),
            'business_name' => $businessName
        ]);
        throw new Exception('Failed to create new contact: ' . $e->getMessage());
    }
}

protected function updateExistingContact(Contacts $contact, array $row, string $businessName): Contacts
{
    $updated = false;
    $updates = [];

    // Check and update each field if new data is available
    $fieldMappings = [
        'contact_email' => 'contact_email',
        'contact_phone' => 'contact_phone',
        'contact_address' => 'address_line',
        'contact_city' => 'city',
        'contact_zip' => 'zip_code'
    ];

    foreach ($fieldMappings as $contactField => $rowField) {
        if (!empty($row[$rowField]) && $contact->$contactField !== $row[$rowField]) {
            $contact->$contactField = $row[$rowField];
            $updates[$contactField] = $row[$rowField];
            $updated = true;
        }
    }

    // Update business name if it's different and new one is provided
    if ($contact->bus_name !== $businessName) {
        $contact->bus_name = $businessName;
        $updates['bus_name'] = $businessName;
        $updated = true;
    }

    if ($updated) {
        try {
            $contact->save();
            Log::info('Updated existing contact', [
                'contact_id' => $contact->id,
                'updates' => $updates
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update contact', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage()
            ]);
            throw new Exception('Failed to update contact: ' . $e->getMessage());
        }
    }

    return $contact;
}

protected function validateContactState(Contacts $contact): void
{
    $requiredFields = [
        'id' => 'Contact ID',
        'organization_id' => 'Organization ID',
        'bus_name' => 'Business Name'
    ];

    $missingFields = [];
    foreach ($requiredFields as $field => $label) {
        if (empty($contact->$field)) {
            $missingFields[] = $label;
        }
    }

    if (!empty($missingFields)) {
        $errorMessage = 'Invalid contact state - missing required fields: ' . implode(', ', $missingFields);
        Log::error($errorMessage, ['contact_id' => $contact->id ?? null]);
        throw new Exception($errorMessage);
    }
}

protected function saveProcessedData(array $processedData)
{
    Log::info('Starting saveProcessedData');
    Log::info('Number of groups to process: ' . count($processedData));

    try {
        $savedTransactions = [];

        DB::beginTransaction();

        foreach ($processedData as $groupKey => $group) {
            Log::info("Processing group: {$groupKey}");
            
            if (empty($group)) {
                Log::warning("Empty group found for key: {$groupKey}");
                continue;
            }

            // Calculate totals for the entire group
            $totalAmount = 0;
            $totalVatAmount = 0;
            $totalVatableSales = 0;

            // Use the first row of the group for common transaction data
            $firstRow = $group[0];
            
            // Create the main transaction first
            $transaction = new Transactions([
                'organization_id' => $firstRow['organization_id'],
                'contact' => $firstRow['contact_id'],
                'transaction_type' => 'Sales',
                'date' => Carbon::parse($firstRow['date']),
                'inv_number' => $firstRow['invoice_no'],
                'reference' => $firstRow['reference_no'],
                'status' => 'Draft'
            ]);

            // Create tax rows and calculate totals
            $taxRows = [];
            foreach ($group as $row) {
                // Get required models for tax calculation
                $taxType = TaxType::findOrFail($row['tax_type_id']);
                $atc = $row['atc_id'] ? Atc::findOrFail($row['atc_id']) : null;
                
                // Calculate tax amounts
                $amount = floatval($row['amount']);
                $taxAmount = $this->calculateTaxAmount($amount, $taxType, $atc);
                $netAmount = $amount - $taxAmount;

                // Add to totals
                $totalAmount += $amount;
                if ($taxType->short_code === 'VAT') {
                    $totalVatAmount += $taxAmount;
                    $totalVatableSales += $netAmount;
                }

                // Prepare tax row
                $taxRows[] = new TaxRow([
                    'tax_type' => $row['tax_type_id'],
                    'tax_code' => $row['atc_id'],
                    'coa' => $row['coa_id'],
                    'amount' => $amount,
                    'description' => $row['description'],
                    'tax_amount' => $taxAmount,
                    'net_amount' => $netAmount,
                    'status' => 'active'
                ]);
            }

            // Update transaction with calculated totals
            $transaction->total_amount = $totalAmount;
            $transaction->vat_amount = $totalVatAmount;
            $transaction->vatable_sales = $totalVatableSales;
            
            // Save transaction
            $transaction->save();
            Log::info("Created transaction ID: {$transaction->id}");

            // Save tax rows
            foreach ($taxRows as $taxRow) {
                $taxRow->transaction_id = $transaction->id;
                $taxRow->save();
                Log::info("Created tax row ID: {$taxRow->id} for transaction ID: {$transaction->id}");
            }

            $savedTransactions[] = $transaction;
        }

        DB::commit();
        Log::info('Successfully committed all transactions');

        return [
            'success' => true,
            'message' => 'Successfully imported ' . count($savedTransactions) . ' transactions',
            'transactions' => $savedTransactions
        ];

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error in saveProcessedData: ' . $e->getMessage());
        Log::error($e->getTraceAsString());

        throw new \Exception('Failed to save import data: ' . $e->getMessage());
    }
}

// Helper method to generate a unique key for grouping related transactions
protected function generateKey(array $row): string
{
    $invoiceNo = $row['invoice_no'] ?? '';
    $referenceNo = $row['reference_no'] ?? '';
    $date = $row['date'] ?? '';
    
    return md5($invoiceNo . $referenceNo . $date);
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
    
        $taxTypeModel = TaxType::forSales()
            ->where('short_code', $taxType)
            ->where('category', $category)
            ->first();
    
        if (!$taxTypeModel) {
            $this->rowErrors[$rowNumber][] = "Invalid tax type ({$taxType}) or category ({$category}) for Sales transaction";
            return false;
        }
    
        return true;
    }
    

    protected function validateAtc($data, $rowNumber)
{
    $atc = $data['atc'] ?? null;
    if (!empty($atc)) {
        $atcModel = Atc::forSales()
            ->where('tax_code', $atc)
            ->first();
            
        if (!$atcModel) {
            $this->rowErrors[$rowNumber][] = "Invalid ATC code: {$atc} for Sales transaction";
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