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

    public function collection(\Illuminate\Support\Collection $rows)
    {
        try {
            Log::info('Starting collection import with ' . $rows->count() . ' rows');
            
            if ($rows->isEmpty()) {
                throw new Exception('No data found in the uploaded file.');
            }

            $this->importedData = $rows->toArray();
            Log::info('Imported data count: ' . count($this->importedData));
            
            // Debug log the first row - using Arr::first() instead of array_first()
            if (!empty($this->importedData)) {
                Log::info('First row sample: ' . json_encode(Arr::first($this->importedData)));
            }
            
            // Validate basic structure of imported data
            if (empty($this->importedData)) {
                throw new Exception('Failed to process the uploaded file data.');
            }
        } catch (Exception $e) {
            Log::error('Import collection error: ' . $e->getMessage());
            throw $e;
        }
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

    public function processAndSaveData($mappedColumns)
    {
        Log::info('Starting processAndSaveData');
        Log::info('Imported data count at start of processing: ' . count($this->importedData));
        Log::info('Mapped columns: ' . json_encode($mappedColumns));
        if (empty($this->importedData)) {
            $this->importedData = session()->get('importedData', []);
        }
        $processedData = [];
        $organizationId = Session::get('organization_id');

        if (!$organizationId) {
            Log::error('Organization ID missing from session');
            throw new Exception('Organization ID not found in session.');
        }

        Log::info('Organization ID found: ' . $organizationId);

        foreach ($this->importedData as $index => $row) {
            Log::info('Processing row ' . $index);
            
            try {
           

                $contactData = $this->processContactData($row);
             
                Log::info('Contact data processed for row ' . $index);
                
                // Fetch and validate related models
                $taxType = $this->getTaxType($row);
             
                $atcModel = $this->getAtc($row);
                $coaModel = $this->getCoa($row);
               


                if (!$taxType || !$atcModel || !$coaModel) {
                    Log::warning("Row {$index} missing required related models");
                    continue;
                }

               
                // Calculate amounts
                $amount = floatval($row['amount'] ?? 0);
            
                $taxAmount = $this->calculateTaxAmount($amount, $taxType);
                $netAmount = $amount - $taxAmount;

                // Group by reference and invoice
                $key = $this->generateKey($row, $mappedColumns);
                Log::info("Generated key for row {$index}: {$key}");
                
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
        
        // Instead of dd(), let's log the data and then save it
        Log::info('Processed data structure: ' . json_encode(array_keys($processedData)));
        
        return $this->saveProcessedData($processedData);
    }
    protected function processContactData($row)
    {
        $contactData = [
            'contact_tin' => $row['contact_tin'] ?? null,
            'contact_address' => $row['address_line'] ?? null,
            'contact_city' => $row['city'] ?? null,
            'contact_zip' => $row['zip_code'] ?? null,
        ];
    
        $contactData['contact_type'] = !empty($row['contact_name']) ? 'Non-Individual' : 'Individual';
        
        $contactData['bus_name'] = !empty($row['contact_name']) 
            ? trim($row['contact_name']) 
            : trim(sprintf(
                '%s %s %s',
                $row['first_name'] ?? '',
                $row['last_name'] ?? '',
                $row['middle_name'] ?? ''
            ));
    
        return $contactData;
    }
    

    protected function getTaxType($row)
    {
        try {
            $taxType = TaxType::where('short_code', $row['tax_type'] ?? null)
                ->where('category', $row['category'] ?? null)
                ->first();
    
            if (!$taxType) {
                throw new Exception("Tax Type not found for: {$row['tax_type']} - {$row['category']}");
            }
    
            return $taxType;
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            Log::error($e->getMessage());
            return null;
        }
    }
    

    protected function getAtc($row)
    {
        try {
            // Directly accessing 'atc' from the row
            $atc = Atc::where('tax_code', $row['atc'] ?? null)->first();
    
            if (!$atc) {
                throw new Exception("ATC not found for: {$row['atc']}");
            }
    
            return $atc;
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            Log::error($e->getMessage());
            return null;
        }
    }
    
    protected function getCoa($row)
    {
        try {
            // Directly accessing 'coa' from the row
            $coa = Coa::where('code', $row['coa_code'] ?? null)->first();
    
            if (!$coa) {
                throw new Exception("COA not found for: {$row['coa']}");
            }
    
            return $coa;
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            Log::error($e->getMessage());
            return null;
        }
    }
    

    protected function calculateTaxAmount($amount, $taxType)
    {
        return $amount > 0 ? ($amount / (1 + $taxType->VAT / 100) * ($taxType->VAT / 100)) : 0;
    }

    protected function generateKey($row)
    {
        return sprintf(
            '%s_%s',
            $row['reference_no'] ?? '',
            $row['invoice_no'] ?? ''
        );
    }
    
    protected function prepareProcessedData($row, $contactData, $taxType, $atcModel, $coaModel, $amount, $taxAmount, $netAmount, $organizationId)
    {
       
        return [
            'Date' => Carbon::parse($row['date'] ?? '')->format('m/d/Y'),
            'Amount' => $amount,
            'Tax Type' => $taxType->tax_type,
            'Category' => $row['category'] ?? '',
            'ATC' => $row['atc'] ?? '',
            'COA' => $row['coa'] ?? '',
            'Invoice No.' => $row['invoice_no'] ?? '',
            'Reference No.' => $row['reference_no'] ?? '',
            'Description' => $row['description'] ?? '',
            'Contact Name' => $contactData['bus_name'],
            'Contact TIN' => $row['contact_tin'] ?? '',
            'Tax Amount' => $taxAmount,
            'Net Amount' => $netAmount,
            'contactData' => $contactData,
            'taxTypeId' => $taxType->id,
            'atcId' => $atcModel->id,
            'coaId' => $coaModel->id,
            'organizationId' => $organizationId,
        ];
    }
    

    private function hasValidMappings($mappedColumns, $row)
    {
        $requiredColumns = [
            'contact_tin', 'address_line', 'city', 'zip_code',
            'contact_name', 'first_name', 'last_name', 'middle_name',
            'tax_type', 'category', 'atc', 'coa', 'amount', 'reference_no',
            'invoice_no', 'description', 'date'
        ];

        $missingColumns = [];
        foreach ($requiredColumns as $column) {
            if (empty($mappedColumns[$column]) || !isset($row[$mappedColumns[$column]])) {
                $missingColumns[] = $column;
            }
        }

        if (!empty($missingColumns)) {
            $this->errors[] = "Missing required columns: " . implode(', ', $missingColumns);
            return false;
        }

        return true;
    }

    public function saveProcessedData($processedData)

    {
   
        Log::info('Starting saveProcessedData with ' . count($processedData) . ' groups');
        
        try {
            foreach ($processedData as $key => $entries) {
        
                Log::info("Processing group: {$key} with " . count($entries) . " entries");
                
                $totalAmount = array_sum(array_column($entries, 'Amount'));
                $totalTaxAmount = array_sum(array_column($entries, 'Tax Amount'));
                $totalNetAmount = array_sum(array_column($entries, 'Net Amount'));
                $data = $entries[0];

                // Create or update contact
                $contact = Contacts::firstOrCreate(
                    ['contact_tin' => $data['Contact TIN']],
                    $data['contactData']
                );
                Log::info("Created/Updated contact ID: {$contact->id}");

                // Create or update transaction
                $transaction = Transactions::firstOrCreate(
                    [
                        'inv_number' => $data['Invoice No.'],
                        'reference' => $data['Reference No.'],
                    ],
                    [
                        'date' => Carbon::parse($data['Date']),
                        'total_amount' => $totalAmount,
                        'vat_amount' => $totalTaxAmount,
                        'vatable_sales'=> $totalNetAmount,
                        'contact' => $contact->id,
                        'transaction_type' => 'Sales',
                        'organization_id' => $data['organizationId'],
                    ]
                );
                Log::info("Created/Updated transaction ID: {$transaction->id}");

                // Create tax rows
                foreach ($entries as $entry) {
                    $taxRow = TaxRow::create([
                        'transaction_id' => $transaction->id,
                        'amount' => $entry['Amount'],
                        'tax_type' => $entry['taxTypeId'],
                        'tax_code' => $entry['atcId'],
                        'coa' => $entry['coaId'],
                        'tax_amount' => $entry['Tax Amount'],
                        'net_amount' => $entry['Net Amount'],
                    ]);
                    Log::info("Created tax row ID: {$taxRow->id}");
                }
            }

            Log::info('Successfully completed saveProcessedData');
            return true;
        } catch (Exception $e) {
            Log::error('Error saving processed data: ' . $e->getMessage());
            throw new Exception('Failed to save imported data: ' . $e->getMessage());
        }
    }
}