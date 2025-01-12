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

class PurchaseImport implements ToCollection, WithHeadingRow
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
            
            // Debug log the first row
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

                // Get amount from row
                $amount = floatval($row['amount'] ?? 0);

                // Group by reference
                $key = $this->generateKey($row);
                Log::info("Generated key for row {$index}: {$key}");
                
                $processedData[$key][] = $this->prepareProcessedData(
                    $row, 
                    $contactData, 
                    $taxType, 
                    $atcModel, 
                    $coaModel, 
                    $amount,
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
    
        if (!empty($row['contact_name'])) {
            // Use Contact Name if provided
            $contactData['contact_type'] = 'Non-Individual';
            $contactData['bus_name'] = trim($row['contact_name']);
        } else {
            // Construct Full Name for Individual
            $contactData['contact_type'] = 'Individual';
            $contactData['bus_name'] = trim(sprintf(
                '%s %s %s',
                $row['first_name'] ?? '',
                $row['last_name'] ?? '',
                $row['middle_name'] ?? ''
            ));
        }
    
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

    protected function calculateAmounts($amount, $taxType, $atcModel)
    {
        $vatRate = $taxType->VAT ?? 0;
        $atcRate = $atcModel->tax_rate ?? 0;
        
        $calculations = [
            'amount' => $amount,
            'vatable_purchase' => 0,
            'non_vatable_purchase' => 0,
            'vat_amount' => 0,
            'atc_amount' => 0,
            'net_amount' => $amount
        ];

        if ($vatRate > 0) {
            // Handle vatable purchase
            $netAmount = $amount / (1 + ($vatRate / 100));
            $vatAmount = $amount - $netAmount;
            
            $calculations['vatable_purchase'] = $netAmount;
            $calculations['vat_amount'] = $vatAmount;
            $calculations['net_amount'] = $netAmount;

            // Calculate ATC if applicable
            if ($atcRate > 0) {
                $calculations['atc_amount'] = $netAmount * ($atcRate / 100);
            }
        } else {
            // Handle non-vatable purchase
            $calculations['non_vatable_purchase'] = $amount;
        }

        return $calculations;
    }

    protected function generateKey($row)
    {
        return $row['reference_no'] ?? '';
    }

    protected function prepareProcessedData($row, $contactData, $taxType, $atcModel, $coaModel, $amount, $organizationId)
    {
        $calculations = $this->calculateAmounts($amount, $taxType, $atcModel);
        
        return [
            'Date' => Carbon::parse($row['date'] ?? '')->format('m/d/Y'),
            'Amount' => $calculations['amount'],
            'Tax Type' => $taxType->tax_type,
            'Category' => $row['category'] ?? '',
            'ATC' => $row['atc'] ?? '',
            'COA' => $row['coa'] ?? '',
            'Reference No.' => $row['reference_no'] ?? '',
            'Description' => $row['description'] ?? '',
            'Contact Name' => $contactData['bus_name'],
            'Contact TIN' => $row['contact_tin'] ?? '',
            'Tax Amount' => $calculations['vat_amount'],
            'Net Amount' => $calculations['net_amount'],
            'ATC Amount' => $calculations['atc_amount'],
            'Vatable Purchase' => $calculations['vatable_purchase'],
            'Non-Vatable Purchase' => $calculations['non_vatable_purchase'],
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
           'description', 'date'
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
                $totalVatAmount = array_sum(array_column($entries, 'Tax Amount'));
                $totalVatablePurchase = array_sum(array_column($entries, 'Vatable Purchase'));
                $totalNonVatablePurchase = array_sum(array_column($entries, 'Non-Vatable Purchase'));
                $totalAtcAmount = array_sum(array_column($entries, 'ATC Amount'));
                
                $data = $entries[0];

                // Create or update contact
                $contact = Contacts::firstOrCreate(
                    ['contact_tin' => $data['Contact TIN'], 'bus_name' => $data['contactData']['bus_name']],
                    $data['contactData']
                );
                Log::info("Created/Updated contact ID: {$contact->id}");

                // Calculate final total amount (including VAT and deducting ATC)
                $finalTotalAmount = ($totalVatablePurchase + $totalVatAmount + $totalNonVatablePurchase) - $totalAtcAmount;

                // Create or update transaction
                $transaction = Transactions::firstOrCreate(
                    [
                        'reference' => $data['Reference No.'],
                    ],
                    [
                        'date' => Carbon::parse($data['Date']),
                        'total_amount' => $finalTotalAmount,
                        'vat_amount' => $totalVatAmount,
                        'vatable_purchase' => $totalVatablePurchase,
                        'contact' => $contact->id,
                        'transaction_type' => 'Purchase',
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
                        'atc_amount' => $entry['ATC Amount']
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