<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Coa;
use App\Models\Transactions;
use App\Models\TaxRow; // Assuming this model exists for individual journal rows
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class JournalTransaction extends Component
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
    
    public  $organizationId;

    // Listen for child component updates
    protected $listeners = ['journalRowUpdated' => 'updateJournalRow', 'journalRowRemoved' => 'removeJournalRow'];
    protected $rules = [
        'date' => 'required|date',
        'reference' => 'required|string',
        'journalRows.*.description' => 'required|string',
        'journalRows.*.coa' => 'required|exists:coas,id',
        'journalRows.*.debit' => 'required|numeric|min:0',
        'journalRows.*.credit' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'date.required' => 'The date field is required.',
        'reference.required' => 'The reference number is required.',
        'journalRows.*.description.required' => 'Description is required for all entries.',
        'journalRows.*.coa.required' => 'Account must be selected for all entries.',
        'journalRows.*.debit.required' => 'Debit amount is required.',
        'journalRows.*.credit.required' => 'Credit amount is required.',
        'journalRows.*.debit.numeric' => 'Debit must be a valid number.',
        'journalRows.*.credit.numeric' => 'Credit must be a valid number.',
    ];

    public function mount()
    {
        $this->addJournalRow(); // Add an initial journal row on mount
        $this->addJournalRow(); 
    }
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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

    // Handles saving the transaction
    public function saveTransaction()
    {
        $this->validate();
        // Ensure debits and credits are balanced
        if ($this->totalAmountDebit !== $this->totalAmountCredit) {
            $this->addError('balance', 'Debits and credits must be equal.');
            return;
        }
        try {

        $organizationId = Session::get('organization_id');

        // Create a new transaction
        $transaction = Transactions::create([
            'transaction_type' => 'Journal',
            'date' => $this->date,
            'reference' => $this->reference,
            'total_amount' => $this->totalAmount,
            'total_amount_debit' => $this->totalAmountDebit,
            'total_amount_credit' => $this->totalAmountCredit,
            'status' => 'Draft',
            'contact' => $this->selectedContact, // Optionally link a contact
            'organization_id' => $organizationId,
        ]);

        // Log the transaction creation activity
        activity('Transaction Management')
            ->performedOn($transaction)
            ->causedBy(Auth::user())
            ->withProperties([
                'transaction_id' => $transaction->id,
                'transaction_type' => 'Journal',
                'organization_id' => $organizationId,
                'ip' => request()->ip(),
                'browser' => request()->header('User-Agent'),
            ])
            ->log("Journal transaction {$transaction->id} was created.");

        // Save each journal row linked to the transaction
        foreach ($this->journalRows as $row) {
            $amount = $row['debit'] > 0 ? $row['debit'] : ($row['credit'] > 0 ? $row['credit'] : 0);
            $journalRow = TaxRow::create([
                'transaction_id' => $transaction->id,
                'debit' => $row['debit'],
                'amount' => $amount,
                'credit' => $row['credit'],
                'description' => $row['description'],
                'coa' => $row['coa'], // Assuming 'coa' is the ID of the account
            ]);

            // Log each journal row creation
            activity('Transaction Management')
                ->performedOn($journalRow)
                ->causedBy(Auth::user())
                ->withProperties([
                    'transaction_id' => $transaction->id,
                    'journal_row_id' => $journalRow->id,
                    'description' => $row['description'],
                    'debit' => $row['debit'],
                    'credit' => $row['credit'],
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("Journal row {$journalRow->id} was added to Transaction {$transaction->id}.");
        }

        // Provide feedback and redirect
        session()->flash('message', 'Transaction saved successfully!');
        return redirect()->route('transactions.show', ['transaction' => $transaction->id])
    ->with('successTransaction', 'Transaction completed successfully!');
    } catch (\Exception $e) {
        $this->addError('general', 'Failed to save transaction. ' . $e->getMessage());
        return;
    }
    }

    public function render()
    {
        return view('livewire.journal-transaction', [
            'coas' => Coa::all(), // Pass the list of accounts (chart of accounts)
        ]);
    }
}
