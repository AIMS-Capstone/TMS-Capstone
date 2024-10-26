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

    public function mount($index, $journalRow = [], $type = 'journal')
    {
        // Load initial data
        $this->coas = Coa::where('status', 'Active')->get();
        $this->index = $index;
        $this->type = $type; // Initialize type

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
        if (in_array($field, ['debit', 'credit', 'coa'])) {
            // Make sure to adjust totals and dispatch updates when debit/credit change
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
        // Define the parent component (if applicable)
        return $this->type === 'sales' ? 'App\Livewire\SalesTransaction' : 'App\Livewire\JournalTransaction';
    }

    public function render()
    {
        return view('livewire.journal-row');
    }
}
