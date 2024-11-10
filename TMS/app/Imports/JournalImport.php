<?php
namespace App\Imports;

use App\Models\Coa;
use App\Models\Transactions;
use App\Models\JournalEntry;
use App\Models\TaxRow;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Arr;

class JournalImport implements ToCollection, WithHeadingRow
{
    public $importedData = [];
    protected $errors = [];

    public function collection(\Illuminate\Support\Collection $rows)
    {
        try {
            Log::info('Starting journal entry import with ' . $rows->count() . ' rows');
            
            if ($rows->isEmpty()) {
                throw new Exception('No data found in the uploaded file.');
            }

            $this->importedData = $rows->toArray();
            Log::info('Imported data count: ' . count($this->importedData));
            
            if (!empty($this->importedData)) {
                Log::info('First row sample: ' . json_encode(Arr::first($this->importedData)));
            }
            
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
        return $this->importedData;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function processAndSaveData($mappedColumns)
    {
        Log::info('Starting processAndSaveData for journal entries');
        
        if (empty($this->importedData)) {
            $this->importedData = session()->get('importedData', []);
        }
        
        $processedData = [];
        $organizationId = Session::get('organization_id');

        if (!$organizationId) {
            Log::error('Organization ID missing from session');
            throw new Exception('Organization ID not found in session.');
        }

        // Group entries by reference number
        foreach ($this->importedData as $index => $row) {
            try {
                // Validate COA
                $coaModel = $this->getCoa($row);
                if (!$coaModel) {
                    Log::warning("Row {$index} has invalid COA code");
                    continue;
                }

                // Group by reference number
                $refNo = $row['reference_no'] ?? '';
                if (!isset($processedData[$refNo])) {
                    $processedData[$refNo] = [
                        'entries' => [],
                        'total_debit' => 0,
                        'total_credit' => 0,
                        'date' => Carbon::parse($row['date'] ?? '')->format('Y-m-d'),
                        'description' => $row['description'] ?? '',
                        'organization_id' => $organizationId
                    ];
                }

                // Add entry to the group
                $debit = floatval($row['debit'] ?? 0);
                $credit = floatval($row['credit'] ?? 0);
                
                $processedData[$refNo]['entries'][] = [
                    'coa_id' => $coaModel->id,
                    'debit' => $debit,
                    'credit' => $credit,
                ];

                $processedData[$refNo]['total_debit'] += $debit;
                $processedData[$refNo]['total_credit'] += $credit;

            } catch (Exception $e) {
                $this->errors[] = "Row {$index}: " . $e->getMessage();
                Log::error("Error processing row {$index}: " . $e->getMessage());
                continue;
            }
        }

        // Validate balanced entries
        foreach ($processedData as $refNo => $data) {
            if (abs($data['total_debit'] - $data['total_credit']) > 0.01) {
                $this->errors[] = "Reference {$refNo}: Debits and credits are not equal. " .
                                "Debit total: {$data['total_debit']}, Credit total: {$data['total_credit']}";
                unset($processedData[$refNo]);
            }
        }

        return $this->saveJournalEntries($processedData);
    }

    protected function getCoa($row)
    {
        try {
            $coa = Coa::where('code', $row['coa_code'] ?? null)->first();
    
            if (!$coa) {
                throw new Exception("COA not found for: {$row['coa_code']}");
            }
    
            return $coa;
        } catch (Exception $e) {
            $this->errors[] = $e->getMessage();
            Log::error($e->getMessage());
            return null;
        }
    }

    protected function saveJournalEntries($processedData)
    {
        Log::info('Starting saveJournalEntries with ' . count($processedData) . ' entries');
        
        try {
            foreach ($processedData as $refNo => $data) {
                // Create the main transaction record
                $transaction = Transactions::create([
                    'date' => $data['date'],
                    'reference' => $refNo,
                    'total_amount' => $data['total_debit'], // Use either debit or credit total as they're equal
                    'total_amount_debit'=>  $data['total_debit'],
                    'total_amount_credit'=>  $data['total_credit'],
                    'transaction_type' => 'Journal',
                    'organization_id' => $data['organization_id'],
                ]);

                // Create the journal entries
                foreach ($data['entries'] as $entry) {
                    TaxRow::create([
                        'transaction_id' => $transaction->id,
                        'coa' => $entry['coa_id'],
                        'debit' => $entry['debit'],
                        'credit' => $entry['credit'],
                        'description' => $data['description'],
                    ]);
                }
            }

            Log::info('Successfully completed saveJournalEntries');
            return true;
        } catch (Exception $e) {
            Log::error('Error saving journal entries: ' . $e->getMessage());
            throw new Exception('Failed to save journal entries: ' . $e->getMessage());
        }
    }
}