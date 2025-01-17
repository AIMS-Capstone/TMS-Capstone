<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Coa;

class JournalRow extends Component
{
    public $coas = []; // Chart of Accounts
    public $coa; // Selected Account
    public $debit = 0; // Debit amount
    public $credit = 0; // Credit amount
    public $index; // Unique row index for each row
    public $description; // Description for the entry
  
    public $type; // Journal entry type (journal, sales, purchase, etc.)
    public $mode; // Mode for the row (create/edit)
    protected $rules = [
        'description' => 'required|string',
        'coa' => 'required|exists:coas,id',
        'debit' => 'required|numeric|min:0',
        'credit' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'description.required' => 'Description is required.',
        'coa.required' => 'Please select an account.',
        'debit.numeric' => 'Debit must be a valid number.',
        'credit.numeric' => 'Credit must be a valid number.',
    ];

    public function mount($index, $journalRow = [], $type = 'journal', $mode = 'create')
    {
        // Load initial data
        $this->coas = Coa::where('status', 'Active')->get();
        $this->index = $index;
        $this->type = $type; // Initialize type
        $this->mode = $mode;

        // Initialize properties from $journalRow (if provided)
        $this->coa = $journalRow['coa'] ?? null;
        $this->debit = $journalRow['debit'] ?? 0;
        $this->credit = $journalRow['credit'] ?? 0;
        $this->description = $journalRow['description'] ?? '';
    }
    public function removeRow()
    {
        $this->dispatch('journalRowRemoved', $this->index);  // Emit the event with the row index
    }

    // Watch for updates on debit or credit fields
    public function updated($field)
    {
        $this->validateOnly($field);

        if (in_array($field, ['debit', 'credit'])) {
            // Ensure only one field can have a value
            if ($this->debit > 0 && $this->credit > 0) {
                $this->addError('balance', 'Cannot have both debit and credit values.');
                return;
            }

            // Your existing update logic
            $this->dispatch('journalRowUpdated', [
                'index' => $this->index,
                'description' => $this->description,
                'coa' => $this->coa,
                'debit' => $this->debit,
                'credit' => $this->credit,
            ])->to($this->getParentComponentClass());
        }
    }
    

    protected function getParentComponentClass()
    {
        // Check if the mode is 'edit' or 'create', and adjust the parent accordingly
        if ($this->mode === 'edit') {
            // If in edit mode, send the event to the EditJournalTransaction parent
            return $this->type === 'journal' ? 'App\Livewire\EditJournalTransaction' : 'App\Livewire\EditTransaction';
        } else {
            // If in create mode, send the event to the JournalTransaction or appropriate component
            return $this->type === 'journal' ? 'App\Livewire\JournalTransaction' : 'App\Livewire\CreateTransaction';
        }
    }


    public function render()
    {
        return view('livewire.journal-row');
    }
}
