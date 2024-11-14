<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TaxRow;
use App\Models\ATC;
use App\Models\TaxType;
use App\Models\Coa;
use App\Models\Transactions;
use Illuminate\Support\Facades\Session;

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
            'tax_type' => '',
            'tax_code' => '',
            'coa' => '',
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
        $this->taxRows[$data['index']] = $data;
        $this->calculateTotals(); // Recalculate after updating a row
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
            $amount = $row['amount'];
            $taxTypeId = $row['tax_type'];

            $taxType = TaxType::find($taxTypeId);
            $vatRate = $taxType ? $taxType->VAT : 0;

            $atc = ATC::find($row['tax_code']);
            $atcRate = $atc ? $atc->tax_rate : 0;

            if ($vatRate > 0) {
                $netAmount = $amount / (1 + ($vatRate / 100));
                $this->vatablePurchase += $netAmount;
                $this->vatAmount += $amount - $netAmount;

                if ($atcRate > 0) {
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
        $this->transaction->update([
            'date' => $this->date,
            'contact' => $this->selectedContact,
            'inv_number' => $this->inv_number,
            'reference' => $this->reference,
            'total_amount' => $this->totalAmount,
            'vatable_purchase' => $this->vatablePurchase,
            'non_vatable_purchase' => $this->nonVatablePurchase,
            'vat_amount' => $this->vatAmount,
            'status' => 'Draft',
            'organization_id' => $this->organizationId
        ]);

        // Remove old tax rows and save the updated ones
        $this->transaction->taxRows()->delete(); // Delete old tax rows

        foreach ($this->taxRows as $row) {
            TaxRow::create([
                'transaction_id' => $this->transaction->id,
                'description' => $row['description'],
                'amount' => $row['amount'],
                'tax_code' => $row['tax_code'],
                'tax_type' => $row['tax_type'],
                'tax_amount' => $row['tax_amount'],
                'atc_amount' => $row['atc_amount'],
                'net_amount' => $row['net_amount'],
                'coa' => !empty($row['coa']) ? $row['coa'] : null,
            ]);
        }

        session()->flash('message', 'Transaction updated successfully!');
        return redirect()->route('transactions.show', ['transaction' => $this->transaction->id]);
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
