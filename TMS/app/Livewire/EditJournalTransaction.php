<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Coa;
use App\Models\Transactions;
use App\Models\TaxRow; // Assuming this model exists for individual journal rows
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EditJournalTransaction extends Component
{
    public $journalRows = []; // Rows for the journal entry
    public $totalAmountDebit = 0; // Total Debit
    public $totalAmountCredit = 0; // Total Credit
    public $totalAmount = 0; // Total Amount (should reflect balance if debits and credits match)
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
    public $organizationId;
    public $transactionId; // Added for the existing transaction ID
    
    // Listen for child component updates
    protected $listeners = ['journalRowUpdated' => 'updateJournalRow', 'journalRowRemoved' => 'removeJournalRow'];

    // Define rules for validation (if any)
    protected $rules = [
        'date' => 'required|date',
        'reference' => 'nullable|string',
        'journalRows.*.debit' => 'required|numeric|min:0',
        'journalRows.*.credit' => 'required|numeric|min:0',
        'journalRows.*.coa' => 'required|exists:coas,id', // Assuming COA is the id of the account
    ];

    // Mount method to load the existing transaction
    public function mount($transactionId)
    {
        $this->transactionId = $transactionId;
        
        // Fetch the transaction and its journal rows from the database
        $transaction = Transactions::with('TaxRows')->findOrFail($transactionId);
        
        // Pre-fill the fields with the existing transaction data
        $this->date = $transaction->date;
        $this->reference = $transaction->reference;
        $this->selectedContact = $transaction->contact;
        $this->organizationId = $transaction->organization_id;

        // Populate the journal rows
        $this->journalRows = $transaction->TaxRows->map(function ($row) {
            return [
                'description' => $row->description,
                'coa' => $row->coa,
                'debit' => $row->debit,
                'credit' => $row->credit,
            ];
        })->toArray();

        // Calculate the totals based on the existing rows
        $this->calculateTotals();
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

        // Recalculate totals
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

        // Set the total amount as the balanced amount if debits and credits match
        $this->totalAmount = $this->totalAmountDebit; // Total amount should reflect the balance
    }

    // Handles updating the transaction (for editing)
    public function saveTransaction()
    {
        // Ensure debits and credits are balanced
        if ($this->totalAmountDebit !== $this->totalAmountCredit) {
            session()->flash('error', 'Debits and credits must be balanced.');
            return;
        }

        try {
            $this->organizationId = session('organization_id');

            $transactionData = [
                'date' => $this->date,
                'reference' => $this->reference,
                'total_amount' => $this->totalAmount,
                'total_amount_debit' => $this->totalAmountDebit,
                'total_amount_credit' => $this->totalAmountCredit,
                'contact' => $this->selectedContact,
            ];

            Transactions::$disableLogging = true;

            // Retrieve existing transaction
            $transaction = Transactions::findOrFail($this->transactionId);
            $oldAttributes = $transaction->getOriginal();
            $transaction->update($transactionData);
            $changedAttributes = $transaction->getChanges();

            Transactions::$disableLogging = false;

            // Delete old journal rows
            TaxRow::where('transaction_id', $this->transactionId)->delete();

            // Save new journal rows
            foreach ($this->journalRows as $row) {
                TaxRow::create([
                    'transaction_id' => $transaction->id,
                    'debit' => $row['debit'],
                    'credit' => $row['credit'],
                    'description' => $row['description'],
                    'coa' => $row['coa'],
                ]);
            }

            // Format changes in a structured way
            $changes = [];
            foreach ($changedAttributes as $key => $newValue) {
                $oldValue = $oldAttributes[$key] ?? 'N/A';
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }

            // Log activity with structured change details
            activity('transactions')
                ->performedOn($transaction)
                ->causedBy(Auth::user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                    'organization_id' => $transaction->organization_id,
                    'changes' => $changes,
                ])
                ->log("Updated Journal Transaction Reference No.:{$transaction->reference}");

            // Provide success feedback
            session()->flash('message', 'Transaction updated successfully!');
            return redirect()->route('transactions.show', ['transaction' => $transaction->id]);

        } catch (\Exception $e) {
            Log::error('Error updating journal transaction: ' . $e->getMessage());
            session()->flash('error', 'There was an error updating the transaction.');
        }
    }

    public function render()
    {
        return view('livewire.edit-journal-transaction', [
            'coas' => Coa::all(), // Pass the list of accounts (chart of accounts)
        ]);
    }
}
