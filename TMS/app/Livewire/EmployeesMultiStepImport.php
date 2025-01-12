<?php

namespace App\Livewire;

use App\Imports\EmployeesImport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class EmployeesMultiStepImport extends Component
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
        // Employee Fields
        'first_name' => 'First Name',
        'middle_name' => 'Middle Name',
        'last_name' => 'Last Name',
        'suffix' => 'Suffix',
        'date_of_birth' => 'Date of Birth',
        'tin' => 'Tax Identification Number',
        'nationality' => 'Nationality',
        'contact_number' => 'Contact Number',
        // Employee Address Fields
        'address' => 'Address',
        'zip_code' => 'Zip Code',
        // Employment Fields
        'employer_name' => 'Employer Name',
        'employment_from' => 'Employment From',
        'employment_to' => 'Employment To',
        'rate' => 'Rate',
        'rate_per_month' => 'Rate Per Month',
        'employment_status' => 'Employment Status', 
        'reason_for_separation' => 'Reason for Separation',
        'employee_wage_status' => 'Employee Wage Status',
        'substituted_filing' => 'Substituted Filing',
        'with_previous_employer' => 'With Previous Employer',
        'previous_employer_tin' => 'Previous Employer Tin',
        'prev_employment_from' => 'Previous Employment From',
        'prev_employment_to' => 'Previous Employment To',
        'prev_employment_status' => 'Previous Employment Status',
        // Employer Address Fields
        'prev_address' => 'Employer Address',
        'prev_zip_code' => 'Employer Zip Code',
        'region' => 'Region',
    ];


    protected $rules = [
        'file' => 'required|mimes:csv,xlsx|max:10240',
        'mappedColumns.*' => 'required',
    ];

    public function mount()
    {
        $this->import = new EmployeesImport();
    }

    public function openModalEmployeesImport()
    {
        $this->reset(['file', 'step', 'columns', 'mappedColumns', 'importedData']);
        $this->modalOpen = true;
        $this->import = new EmployeesImport();

        if (!Session::has('organization_id')) {
            session()->flash('error', 'Organization ID is missing. Please ensure you are logged in.');
        }
    }

    public function closeModal()
    {
        $this->modalOpen = false;
        $this->reset(['file', 'step', 'columns', 'mappedColumns', 'importedData']);
        session()->forget(['importedData', 'mappedColumns']);

        if ($this->file) {
            Storage::delete('temp/' . $this->file);
        }
    }

    public function processFileUpload()
    {
        $this->validate([
            'file' => 'required|mimes:csv,xlsx|max:10240',
        ]);

        $this->import = new EmployeesImport(); 

        try {
            $path = $this->file->store('temp');
            Excel::import($this->import, storage_path('app/' . $path));

            $this->importedData = $this->import->getImportedData(); 

            session()->put('importedData', $this->importedData);

            if (!empty($this->importedData) && is_array($this->importedData) && count($this->importedData) > 0) {
                $this->columns = array_keys($this->importedData[0]);
                $this->mappedColumns = [];
                $this->step = 2;
            } else {
                session()->flash('error', 'No data found in the uploaded file.');
                $this->step = 1;
            }

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
            'mappedColumns.*.required' => 'All fields must be mapped to a column.',
        ]);

        try {
            if (empty($this->importedData)) {
                throw new \Exception('No data to process. Please upload a file first.');
            }

            session()->put('mappedColumns', $this->mappedColumns);

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
                $this->import = new EmployeesImport();
            }

            $mappedColumns = session()->get('mappedColumns');
            
            if (!$mappedColumns) {
                throw new \Exception('Column mapping not found.');
            }

            $this->import->processAndSaveData($mappedColumns);

            session()->flash('message', 'Import completed successfully!');
            $this->step = 4;

            session()->forget(['importedData', 'mappedColumns']);

            $this->dispatchBrowserEvent('closeModalAndReload');

        } catch (\Exception $e) {
            session()->flash('error', 'Error saving import: ' . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.employees-multi-step-import', [
            'importedData' => session()->get('importedData'),
            'previewData' => array_slice(session()->get('importedData', []), 0, 5)
        ]);
    }
}
