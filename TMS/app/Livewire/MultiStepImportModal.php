<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Imports\SalesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MultiStepImportModal extends Component
{
    use WithFileUploads;

    public $file;
    public $modalOpen = false;
    public $step = 1;
    public $columns = [];
    public $mappedColumns = [];
    public $importedData = [];
    public $validationErrors = [];
    public $rowErrors = [];
    public $hasErrors = false;
    public $processedRowCount = 0;
    protected $import;

    public $databaseColumns = [
        'date' => 'Date',
        'contact_tin' => 'Contact TIN',
        'contact_name' => 'Contact Name',
        'last_name' => 'Last Name',
        'first_name' => 'First Name',
        'middle_name' => 'Middle Name',
        'address_line' => 'Address Line',
        'city' => 'City',
        'zip_code' => 'Zip Code',
        'invoice_no' => 'Invoice No.',
        'reference_no' => 'Reference No.',
        'description' => 'Description',
        'category' => 'Category',
        'tax_type' => 'Tax Type',
        'atc' => 'ATC',
        'coa_code' => 'COA Code',
        'amount' => 'Amount',
    ];

    protected $rules = [
        'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        'mappedColumns.*' => 'required'
    ];

    public function mount()
    {
        $this->import = new SalesImport();
    }

    public function openModal()
    {
        $this->resetState();
        $this->modalOpen = true;
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->resetState();
        $this->cleanupTempFiles();
    }

    protected function resetState()
    {
        $this->reset([
            'file',
            'step',
            'columns',
            'mappedColumns',
            'importedData',
            'validationErrors',
            'rowErrors',
            'hasErrors',
            'processedRowCount'
        ]);
        $this->import = new SalesImport();
        session()->forget(['importedData', 'mappedColumns']);
    }

    protected function cleanupTempFiles()
    {
        if ($this->file) {
            Storage::delete('temp/' . $this->file);
        }
    }

    public function updatedFile()
    {
        $this->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        try {
            $this->import = new SalesImport();
            $path = $this->file->store('temp');
            
            // Log file details for debugging
            Log::info('File uploaded: ' . $path);
            Log::info('File size: ' . Storage::size($path));
            
            // Import the file
            Excel::import($this->import, storage_path('app/' . $path));
            
            $this->importedData = $this->import->getImportedData();
            Log::info('Imported data count: ' . count($this->importedData));
            
            if (!empty($this->importedData)) {
                $this->columns = array_keys($this->importedData[0]);
                $this->mappedColumns = [];
                
                // Auto-map columns with improved matching
                foreach ($this->databaseColumns as $dbColumn => $displayName) {
                    foreach ($this->columns as $fileColumn) {
                        // Normalize both strings for comparison
                        $normalizedDbColumn = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $dbColumn));
                        $normalizedFileColumn = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $fileColumn));
                        
                        if ($normalizedFileColumn === $normalizedDbColumn) {
                            $this->mappedColumns[$dbColumn] = $fileColumn;
                            break;
                        }
                    }
                }
                
                $this->step = 2;
            } else {
                throw new \Exception('No data found in the uploaded file. Please check if the file contains data and has headers.');
            }

            // Clean up the temporary file
            Storage::delete($path);
            
        } catch (\Exception $e) {
            Log::error('File upload error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            session()->flash('error', 'Error processing file: ' . $e->getMessage());
            $this->step = 1;
        }
    }

    public function mapColumns()
    {
        $this->validationErrors = [];
        $this->rowErrors = [];
        $this->hasErrors = false;
        Log::info($this->importedData);
        $this->validate([
            'mappedColumns.*' => 'required',
        ], [
            'mappedColumns.*.required' => 'All fields must be mapped to a column.'
        ]);

        try {
            Log::info('Initial importedData count:', [count($this->importedData)]);
            if (empty($this->importedData)) {
                throw new \Exception('No data to process. Please upload a file first.');
            }
        
            $cleanData = [];
            foreach ($this->importedData as $row) {
                Log::info('Processing row:', $row);
                $mappedRow = [];
                foreach ($this->mappedColumns as $dbField => $excelColumn) {
                    $mappedRow[$dbField] = $row[$excelColumn] ?? null;
                }
                Log::info('Mapped row:', $mappedRow);
                $cleanData[] = $mappedRow;
            }
        
            Log::info('Clean Data Count:', [count($cleanData)]);
            if (!$this->import) {
                $this->import = new SalesImport();
                Log::info('reached number 2');
            }
            if (!$this->import->validateData($cleanData)) {
                $errors = $this->import->getValidationErrors();
                Log::info('Validation Errors:', $errors);
                $this->validationErrors = $errors['validationErrors'];
                $this->rowErrors = $errors['rowErrors'];
                $this->hasErrors = true;
                $this->step = 4;
                return;
            }
            
        
            session(['mappedColumns' => $this->mappedColumns]);
            session(['importedData' => $cleanData]);
            $this->processedRowCount = count($cleanData);
            $this->step = 3;
        
        } catch (\Exception $e) {
            Log::error('Error in mapColumns:', ['message' => $e->getMessage()]);
            $this->validationErrors[] = $e->getMessage();
            $this->hasErrors = true;
            $this->step = 4;
        }
        
    }

    public function saveImport()
    {
        try {
            $mappedColumns = session('mappedColumns');
            $importedData = session('importedData');
    
            if (!$mappedColumns || !$importedData) {
                throw new \Exception('Import data not found. Please try again.');
            }
    
            if (!$this->import) {
                $this->import = new SalesImport();
            }
    
            // Process and save the data
            if (method_exists($this->import, 'processAndSaveData')) {
                $this->import->processAndSaveData($importedData);
            } else {
                throw new \Exception('Unable to process import data. Method not found.');
            }
            
            // Clear any previous errors
            $this->validationErrors = [];
            $this->rowErrors = [];
            $this->hasErrors = false;
            
            // Set success state
            session()->flash('message', 'Import completed successfully!');
            $this->processedRowCount = count($importedData);
            
            // Move to completion step
            $this->step = 4;
            
            // Clean up session data
            session()->forget(['importedData', 'mappedColumns']);
                
        } catch (\Exception $e) {
            Log::error('Save import error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            // Set error state
            $this->validationErrors = [$e->getMessage()];
            $this->hasErrors = true;
            $this->step = 4;
            
            // Clean up session in case of error
            session()->forget(['importedData', 'mappedColumns']);
        }
    }

    public function render()
    {
        return view('livewire.multi-step-import-modal', [
            'importedData' => session('importedData'),
            'previewData' => array_slice(session('importedData', []), 0, 5),
            'validationErrors' => $this->validationErrors,
            'rowErrors' => $this->rowErrors,
            'hasErrors' => $this->hasErrors,
            'processedRowCount' => $this->processedRowCount
        ]);
    }
}