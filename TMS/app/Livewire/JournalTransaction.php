<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Coa;
use App\Models\Transactions;
use App\Models\TaxRow; // Assuming this model exists for individual journal rows

class JournalTransaction extends Component
{
    public $journalRows = []; // Rows for the journal entry
    public $totalAmountDebit = 0; // Total Debit
    public $totalAmountCredit = 0; // Total Credit
    public $date; // Transaction date
    public $contact; // Contact involved in the transaction
    public $inv_number; // Invoice number
    public $credit;
    public $coa;
    public $debit;
    public $description; 
    public $reference; // Reference number
    public $selectedContact; // Selected contact for the transaction
    public $type = 'journal'; // Default to 'journal' transaction type

    // Listen for child component updates
    protected $listeners = ['journalRowUpdated' => 'updateJournalRow', 'journalRowRemoved' => 'removeJournalRow'];

    public function mount()
    {
        $this->addJournalRow(); // Add an initial journal row on mount
    }

    // Adds a new journal row to the entry
    public function addJournalRow()
    {
        $this->journalRows[] = [
            'description' => '',
            'coa' => '',
            'debit' => 0,
            'credit' => 0,
        ];
    }

    // Removes a journal row at the given index
    public function removeJournalRow($index)
    {
        unset($this->journalRows[$index]);  // Remove the row by index
    $this->journalRows = array_values($this->journalRows);  // Reindex the array

    // Optionally recalculate totals if necessary
    $this->calculateTotals();
    }

    // Updates a journal row based on the data provided by the child component
    public function updateJournalRow($data)
    {
        $this->journalRows[$data['index']] = $data; // Update the specific journal row
        $this->calculateTotals(); // Recalculate totals
    }

    // Calculate the total debit and credit amounts
    public function calculateTotals()
    {
        $this->totalAmountDebit = collect($this->journalRows)->sum('debit'); // Sum debits
        $this->totalAmountCredit = collect($this->journalRows)->sum('credit'); // Sum credits
    }

    // Handles saving the transaction
    public function saveTransaction()
    {
        // Ensure debits and credits are balanced
        if ($this->totalAmountDebit !== $this->totalAmountCredit) {
            session()->flash('error', 'Debits and credits must be balanced.');
            return;
        }

        // Create a new transaction
        $transaction = Transactions::create([
            'transaction_type' => 'Journal',
            'date' => $this->date,
            'reference' => $this->reference,
            'total_amount_debit' => $this->totalAmountDebit,
            'total_amount_credit' => $this->totalAmountCredit,
            'contact' => $this->selectedContact, // Optionally link a contact
        ]);

        // Save each journal row linked to the transaction
        foreach ($this->journalRows as $row) {
            TaxRow::create([
                'transaction_id' => $transaction->id,
                'debit' => $row['debit'],
                'credit' => $row['credit'],
                'description' => $row['description'],
                'coa' => $row['coa'], // Assuming 'coa' is the ID of the account
            ]);
        }

        // Optionally provide feedback and redirect
        session()->flash('message', 'Transaction saved successfully!');
        return redirect()->route('transactions.show', ['transaction' => $transaction->id]);
    }

    public function render()
    {
        return view('livewire.journal-transaction', [
            'coas' => Coa::all(), // Pass the list of accounts (chart of accounts)
        ]);
    }
}
