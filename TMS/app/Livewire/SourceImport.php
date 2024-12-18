<?php

namespace App\Livewire;

use App\Models\Employee;
use App\Models\Source;
use App\Models\Employment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SourceImport extends Component
    {
        use WithFileUploads;

        public $file;
        public $modalOpen = false;
        public $step = 1;
        public $columns = [];
        public $mappedColumns = [];
        public $importedData = [];
        public $withholdingId;
        public $employees = [];
        public $databaseColumns = [
            'first_name' => 'Employee First Name',
            'last_name' => 'Employee Last Name',
            'payment_date' => 'Payment Date',
            'gross_compensation' => 'Gross Compensation',
            'taxable_compensation' => 'Taxable Compensation',
            'tax_due' => 'Tax Due',
            'statutory_minimum_wage' => 'Statutory Minimum Wage',
            'holiday_pay' => 'Holiday Pay',
            'overtime_pay' => 'Overtime Pay',
            'night_shift_differential' => 'Night Shift Differential',
            'hazard_pay' => 'Hazard Pay',
            'month_13_pay' => '13th Month Pay',
            'de_minimis_benefits' => 'De Minimis Benefits',
            'sss_gsis_phic_hdmf_union_dues' => 'SSS/GSIS/PHIC/HDMF/Union Dues',
            'other_non_taxable_compensation' => 'Other Non-Taxable Compensation',
        ];

        protected $rules = [
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
            'mappedColumns.*' => 'required',
        ];

        public function mount($withholdingId = null)
        {
            $this->withholdingId = $withholdingId;

            // Map employees by "first_name|last_name" keys
            $this->employees = Employee::all()
                ->mapWithKeys(fn($employee) => [strtolower(trim($employee->first_name) . '|' . trim($employee->last_name)) => $employee->id])
                ->toArray();

            Log::info('Employee mapping keys: ' . json_encode(array_keys($this->employees)));
            Log::info("Withholding ID set: {$this->withholdingId}");
        }

        public function openModalImport()
        {
            $this->reset(['file', 'step', 'columns', 'mappedColumns', 'importedData']);
            $this->modalOpen = true;

            if (!Session::has('organization_id')) {
                session()->flash('error', 'Organization ID is missing. Please ensure you are logged in.');
            }
        }

        public function closeModal()
        {
            $this->reset(['file', 'step', 'columns', 'mappedColumns', 'importedData']);
            $this->modalOpen = false;
            session()->forget(['importedData', 'mappedColumns']);

            if ($this->file) {
                Storage::delete($this->file->getRealPath());
            }
        }

        public function processFileUpload()
        {
            $this->validate(['file' => 'required|mimes:xlsx,xls,csv|max:10240']);

            try {
                $path = $this->file->store('temp');
                $filePath = storage_path('app/' . $path);

                $file = fopen($filePath, 'r');
                $headers = fgetcsv($file);
                $rows = [];

                while (($row = fgetcsv($file)) !== false) {
                    // Skip rows with no meaningful data
                    if (!empty(array_filter($row))) {
                        $rows[] = array_combine($headers, $row);
                    }
                }

                fclose($file);
                Storage::delete($path);

                if (!empty($rows)) {
                    $this->columns = $headers;
                    $this->importedData = $rows;

                    session()->put('columns', $this->columns);
                    session()->put('importedData', $this->importedData);

                    Log::info('Uploaded file data: ' . json_encode(array_slice($this->importedData, 0, 5)));
                    $this->step = 2;
                } else {
                    session()->flash('error', 'No valid data found in the uploaded file.');
                }
            } catch (\Exception $e) {
                session()->flash('error', 'Error processing file: ' . $e->getMessage());
            }
        }

        public function mapColumns()
        {
            $this->validate(['mappedColumns.*' => 'required']);

            try {
                $data = session()->get('importedData', []);
                if (empty($data)) {
                    throw new \Exception('No data to process. Please upload a file first.');
                }

                $normalizedData = collect($data)->map(function ($row) {
                    $normalizedRow = [];
                    foreach ($row as $key => $value) {
                        $normalizedKey = strtolower(trim($key));
                        $normalizedRow[$normalizedKey] = trim($value);
                    }
                    return $normalizedRow;
                });

                $cleanData = $normalizedData->map(function ($row) {
                    $mappedRow = [];
                    foreach ($this->mappedColumns as $dbField => $excelColumn) {
                        $normalizedColumn = strtolower(trim($excelColumn));
                        $value = $row[$normalizedColumn] ?? null;

                        if ($dbField === 'payment_date' && $value) {
                            // Clean and parse the date
                            $cleanedDate = $this->cleanString($value);
                            $mappedRow[$dbField] = $this->parseDate($cleanedDate);
                            if (!$mappedRow[$dbField]) {
                                Log::error("Failed to parse payment_date: {$cleanedDate}");
                            }
                        } else {
                            $mappedRow[$dbField] = $value;
                        }
                    }

                    // Map employee_id based on the employee key
                    $employeeKey = strtolower(trim($mappedRow['first_name'])) . '|' . strtolower(trim($mappedRow['last_name']));
                    $mappedRow['employee_id'] = $this->employees[$employeeKey] ?? null;

                    if (empty($mappedRow['employee_id'])) {
                        Log::warning("Employee not found for: {$mappedRow['first_name']} {$mappedRow['last_name']}");
                    }

                    return $mappedRow;
                });

                session()->put('importedData', $cleanData->toArray());
                $this->step = 3;
            } catch (\Exception $e) {
                session()->flash('error', 'Error mapping columns: ' . $e->getMessage());
            }
        }

        public function saveImport()
        {
            try {
                $data = session()->get('importedData', []);
                if (empty($data)) {
                    throw new \Exception('No data to import.');
                }

                foreach ($data as $row) {
                    Log::info("Before saving row: " . json_encode($row));

                    if (!empty($row['payment_date']) && !empty($row['employee_id'])) {
                        // Fetch employment details
                        $employment = Employment::where('employee_id', $row['employee_id'])->first();
                        if (!$employment) {
                            Log::warning("No employment record found for employee ID: {$row['employee_id']}");
                            $row['employment_id'] = null;
                        } else {
                            $row['employment_id'] = $employment->id;
                            Log::info("Found employment ID: {$employment->id} for employee ID: {$row['employee_id']}");
                        }

                        try {
                            // Save the data to the database
                            Source::updateOrCreate([
                                'withholding_id' => $this->withholdingId,
                                'employee_id' => $row['employee_id'],
                                'payment_date' => $row['payment_date'],
                            ], $row);

                            Log::info("Successfully saved row: " . json_encode($row));
                        } catch (\Exception $e) {
                            Log::error("Error saving row: " . json_encode($row) . " - Exception: " . $e->getMessage());
                        }
                    } else {
                        Log::error("Skipping row due to missing required fields: " . json_encode($row));
                    }
                }

                session()->flash('message', 'Import completed successfully!');
                $this->reset(['importedData', 'mappedColumns']);
                $this->step = 4;

                $this->dispatchBrowserEvent('reload-page');
            } catch (\Exception $e) {
                session()->flash('error', 'Error saving import: ' . $e->getMessage());
            }
        }

        public function render()
        {
            return view('livewire.source-import', [
                'previewData' => array_slice(session()->get('importedData', []), 0, 5),
            ]);
        }

        private function cleanString($value)
        {
            return preg_replace('/[^\x20-\x7E]/', '', $value);
        }

        private function parseDate($value)
        {
            $formats = ['m/d/Y', 'Y-m-d']; 
            foreach ($formats as $format) {
                try {
                    return \Carbon\Carbon::createFromFormat($format, $value)->format('Y-m-d');
                } catch (\Exception $e) {
                    continue; 
                }
            }
            return null;
        }

    }
