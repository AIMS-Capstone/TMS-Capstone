<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TaxRow;
use App\Models\Atc;
use App\Models\TaxType;
use App\Models\Coa;
use App\Models\Transactions;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EditPurchaseTransaction extends Component
{
    public $transaction;
    public $taxRows = [];
    public $totalAmount = 0;
    public $vatablePurchase = 0;
    public $nonVatablePurchase = 0;
    public $vatAmount = 0;
    public $appliedATCsTotalAmount = 0;
    public $date;
    public $contact;
    public $inv_number;
    public $reference;
    public $selectedContact;
    public $appliedATCs = [];
    public $organizationId;

    protected $listeners = ['taxRowUpdated' => 'updateTaxRow', 'contactSelected', 'taxRowRemoved' => 'removeTaxRow'];

    protected $rules = [
        'date' => 'required|date',
        'inv_number' => 'required|string',
        'reference' => 'nullable|string',
        'taxRows.*.amount' => 'required|numeric|min:0',
        'taxRows.*.tax_code' => 'nullable|exists:atcs,id',
        'taxRows.*.coa' => 'nullable|string',
    ];

    public function mount($transactionId)
    {
        // Fetch the existing transaction by ID
        $this->transaction = Transactions::with('taxRows')->findOrFail($transactionId);

        // Populate fields with existing transaction data
        $this->date = $this->transaction->date;
        $this->inv_number = $this->transaction->inv_number;
        $this->reference = $this->transaction->reference;
        $this->selectedContact = $this->transaction->contact;
        $this->organizationId = $this->transaction->organization_id;

        // Load the tax rows associated with the transaction
        $this->taxRows = $this->transaction->taxRows->toArray();

        // Recalculate totals based on existing tax rows
        $this->calculateTotals();
    }

    public function addTaxRow()
    {
        $this->taxRows[] = [
            'description' => '',
            'tax_type' => null, 
            'tax_code' => null,  
            'coa' => null,      
            'amount' => 0,
            'tax_amount' => 0,
            'net_amount' => 0,
            'atc_amount' => 0
        ];
    }

    public function contactSelected($contactId)
    {
        $this->selectedContact = $contactId;
    }

    public function removeTaxRow($index)
    {
        unset($this->taxRows[$index]);
        $this->taxRows = array_values($this->taxRows); // Re-index array
        $this->calculateTotals(); // Recalculate after removing a row
    }

    public function updateTaxRow($data)
    {
        if (isset($data['index']) && isset($data['taxRow'])) {
            $this->taxRows[$data['index']] = $data['taxRow'];
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $this->vatablePurchase = 0;
        $this->nonVatablePurchase = 0;
        $this->vatAmount = 0;
        $this->totalAmount = 0;
        $this->appliedATCs = [];
        $this->appliedATCsTotalAmount = 0;
    
        foreach ($this->taxRows as &$row) {
            // Add safety checks
            if (!isset($row['tax_type']) || !isset($row['amount']) || !isset($row['tax_code'])) {
                continue;
            }
    
            $amount = $row['amount'];
            $taxTypeId = $row['tax_type'];
    
            $taxType = !empty($taxTypeId) ? TaxType::find($taxTypeId) : null;
            $vatRate = $taxType ? $taxType->VAT : 0;
    
            $atc = !empty($row['tax_code']) ? Atc::find($row['tax_code']) : null;
            $atcRate = $atc ? $atc->tax_rate : 0;
    
            if ($vatRate > 0) {
                $netAmount = $amount / (1 + ($vatRate / 100));
                $this->vatablePurchase += $netAmount;
                $this->vatAmount += $amount - $netAmount;
    
                if ($atcRate > 0 && $atc) {
                    $atcAmount = $netAmount * ($atcRate / 100);
                    $row['atc_amount'] = $atcAmount;
                    $this->appliedATCs[$row['tax_code']] = [
                        'code' => $atc->tax_code,
                        'rate' => $atcRate,
                        'amount' => $netAmount,
                        'tax_amount' => $atcAmount
                    ];
                } else {
                    $row['atc_amount'] = 0;
                }
            } else {
                $this->nonVatablePurchase += $amount;
                $row['atc_amount'] = 0;
            }
        }
    
        $this->appliedATCsTotalAmount = collect($this->appliedATCs)->sum('tax_amount');
        $vatInclusiveAmount = $this->vatablePurchase + $this->vatAmount;
        $this->totalAmount = $vatInclusiveAmount + $this->nonVatablePurchase - $this->appliedATCsTotalAmount;
    }

    public function saveTransaction()
    {
        try {
            $this->organizationId = session('organization_id');

            $transactionData = [
                'transaction_type' => 'Purchase',
                'date' => $this->date,
                'contact' => $this->selectedContact,
                'inv_number' => $this->inv_number,
                'reference' => $this->reference,
                'total_amount' => $this->totalAmount,
                'vatable_purchase' => $this->vatablePurchase,
                'vat_amount' => $this->vatAmount,
                'non_vatable_purchase' => $this->nonVatablePurchase,
                'organization_id' => $this->organizationId,
                'status' => 'Draft',
            ];

            if ($this->transaction) {
                // Update existing transaction
                Transactions::$disableLogging = true;
                $oldAttributes = $this->transaction->getOriginal();
                $this->transaction->update($transactionData);
                $changedAttributes = $this->transaction->getChanges();
                Transactions::$disableLogging = false;

                // Delete old tax rows
                $this->transaction->taxRows()->delete();
            } else {
                // Create new transaction
                $this->transaction = Transactions::create($transactionData);
                $oldAttributes = [];
                $changedAttributes = $this->transaction->getAttributes();
            }

            // Insert updated tax rows
            foreach ($this->taxRows as $row) {
                TaxRow::create([
                    'transaction_id' => $this->transaction->id,
                    'description' => $row['description'],
                    'amount' => $row['amount'],
                    'tax_code' => $row['tax_code'] ?? null,
                    'tax_type' => $row['tax_type'],
                    'tax_amount' => $row['tax_amount'],
                    'net_amount' => $row['net_amount'],
                    'coa' => $row['coa'] ?? null,
                ]);
            }

            // Format changes in a readable way
            $changes = [];
            foreach ($changedAttributes as $key => $newValue) {
                $oldValue = $oldAttributes[$key] ?? 'N/A';
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }

            // Log the update with IP, browser, and detailed changes
            activity('transactions')
                ->performedOn($this->transaction)
                ->causedBy(Auth::user())
                ->withProperties([
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                    'organization_id' => $this->transaction->organization_id,
                    'changes' => $changes,
                ])
                ->log("Updated Purchase Transaction Reference No.: {$this->transaction->reference}");

            session()->flash('message', 'Purchase Transaction updated successfully!');
            return redirect()->route('transactions.show', ['transaction' => $this->transaction->id]);

        } catch (\Exception $e) {
            // Log error and flash message if something goes wrong
            Log::error('Error saving purchase transaction: ' . $e->getMessage());
            session()->flash('error', 'There was an error saving the purchase transaction.');
        }
    }

    public function render()
    {
        return view('livewire.edit-purchase-transaction', [
            'taxRows' => $this->taxRows,
            'totalAmount' => $this->totalAmount,
            'vatablePurchase' => $this->vatablePurchase,
            'nonVatablePurchase' => $this->nonVatablePurchase,
            'vatAmount' => $this->vatAmount,
            'appliedATCs' => $this->appliedATCs,
            'appliedATCsTotalAmount' => $this->appliedATCsTotalAmount,
        ]);
    }
}
