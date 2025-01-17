<?php

namespace App\Livewire;

use App\Imports\JournalImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class JournalMultiStepImport extends Component
{
    use WithFileUploads;

    public $file;
    public $modalOpen = false;
    public $step = 1;
    public $columns = [];
    public $mappedColumns = [];
    public $importedData = [];
    protected $import;
    public $databaseColumns = [
        'date' => 'Date',
        'reference_no' => 'Reference No.',
        'description' => 'Description',
        'coa_code' => 'COA Code',
        'debit' => 'Debit',
        'credit'=> 'Credit',
    ];

    protected $rules = [
        'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        'mappedColumns.*' => 'required'
    ];

    public function mount()
    {
        $this->import = new JournalImport();
    }

    public function openModal()
    {
        $this->reset(['file', 'step', 'columns', 'mappedColumns', 'importedData']);
        $this->modalOpen = true;
        $this->import = new JournalImport();
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->reset(['file', 'step', 'columns', 'mappedColumns', 'importedData']);
        session()->forget(['importedData', 'mappedColumns']);
        
        // Cleanup any temporary files
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
            $this->import = new JournalImport();
            $path = $this->file->store('temp');
            
            Excel::import($this->import, storage_path('app/' . $path));
            
            $this->importedData = $this->import->getImportedData();
            
            if (!empty($this->importedData) && is_array($this->importedData) && count($this->importedData) > 0) {
                $this->columns = array_keys($this->importedData[0]);
                $this->mappedColumns = [];
                $this->step = 2;
            } else {
                session()->flash('error', 'No data found in the uploaded file.');
                $this->step = 1;
            }

            // Cleanup the temporary file
            Storage::delete($path);
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error processing file: ' . $e->getMessage());
            $this->step = 1;
        }
    }

    public function mapColumns()
    {
        $this->validate([
            'mappedColumns.*' => 'required',
        ], [
            'mappedColumns.*.required' => 'All fields must be mapped to a column.'
        ]);

        try {
            if (empty($this->importedData)) {
                throw new \Exception('No data to process. Please upload a file first.');
            }

            // Store mapped columns in session
            session()->put('mappedColumns', $this->mappedColumns);
            
            // Store imported data in session
            $cleanData = array_map(function ($row) {
                $mappedRow = [];
                foreach ($this->mappedColumns as $dbField => $excelColumn) {
                    $mappedRow[$dbField] = $row[$excelColumn] ?? null;
                }
                return $mappedRow;
            }, $this->importedData);
            
            session()->put('importedData', $cleanData);
            
            $this->step = 3;
        } catch (\Exception $e) {
            session()->flash('error', 'Error mapping columns: ' . $e->getMessage());
        }
    }

    public function saveImport()
    {
        try {
            if (!$this->import) {
                $this->import = new JournalImport();
            }

            $mappedColumns = session()->get('mappedColumns');
            if (!$mappedColumns) {
                throw new \Exception('Column mapping not found.');
            }
        
            $this->import->processAndSaveData($mappedColumns);
            
            session()->flash('message', 'Import completed successfully!');
            $this->step = 4;
            
            // Clean up session
            session()->forget(['importedData', 'mappedColumns']);
        } catch (\Exception $e) {
            session()->flash('error', 'Error saving import: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.journal-multi-step-import', [
            'importedData' => session()->get('importedData'),
            'previewData' => array_slice(session()->get('importedData', []), 0, 5) // Preview first 5 rows
        ]);
    }
}