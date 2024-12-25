<?php

namespace App\Livewire;

use App\Models\ATC;
use App\Models\TaxRow;
use App\Models\TaxType;
use App\Models\Transactions;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class PurchaseTransaction extends Component
{
    public $taxRows = [];
    public $totalAmount = 0;
    public $vatablePurchase = 0;
    public $nonVatablePurchase = 0;
    public $vatAmount = 0;
    public $appliedATCsTotalAmount = 0; // Add this line to define the property
    public $date;
    public $contact;
    public $inv_number;
    public $type = 'purchase';
    public $reference;
    public $selectedContact;
    public $appliedATCs = []; // Array to hold applied ATC details
    public $organizationId;

    protected $listeners = ['taxRowUpdated' => 'updateTaxRow', 'contactSelected', 'taxRowRemoved' => 'removeTaxRow'];

    protected $rules = [
        'date' => 'required|date',
        'inv_number' => 'required|string',
        'reference' => 'nullable|string',
        'taxRows.*.amount' => 'required|numeric|min:0.01',
        'taxRows.*.tax_code' => 'nullable|exists:atcs,id',
        'taxRows.*.coa' => 'required|string',
        'taxRows.*.description' => 'required|string',
        'taxRows.*.tax_type' => 'required|exists:tax_types,id', // Add validation for tax type
    ];
    public function mount()
    {
        $this->addTaxRow();

    }

    public function addTaxRow()
    {
        $newIndex = count($this->taxRows);

        $this->taxRows[] = [
            'id' => uniqid(),
            'description' => '',
            'tax_type' => '',
            'tax_code' => '',
            'coa' => '',
            'amount' => 0,
            'tax_amount' => 0,
            'net_amount' => 0,
        ];
        $this->dispatch('select2:reinitialize');
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
        Log::info('updateTaxRow data received', [
            'full_data' => $data,
            'index' => $data['index'] ?? 'No index',
            'taxRow' => $data['taxRow'] ?? 'No taxRow',
        ]);

        // Find the key of the row with the matching ID
        $key = array_search($data['index'], array_column($this->taxRows, 'id'));

        if ($key !== false) {
            Log::info('naabot mo to idol sa updateTaxrow to');
            $this->taxRows[$key] = $data['taxRow'];
            $this->calculateTotals();
        } else {
            Log::info("ina mo mali ka");
        };
    }

    public function calculateTotals()
    {
        $this->vatablePurchase = 0;
        $this->nonVatablePurchase = 0;
        $this->vatAmount = 0;
        $this->totalAmount = 0;
        $this->appliedATCs = [];
        $this->appliedATCsTotalAmount = 0;

        Log::info('Calculating totals', [
            'tax_rows_count' => count($this->taxRows),
        ]);

        foreach ($this->taxRows as &$row) { // Use reference (&) to modify array elements
            $amount = $row['amount'];
            $taxTypeId = $row['tax_type'];

            $taxType = TaxType::find($taxTypeId);
            $vatRate = $taxType ? $taxType->VAT : 0;

            $atc = ATC::find($row['tax_code']);
            $atcRate = $atc ? $atc->tax_rate : 0;

            // Log each row's details
            Log::info('Processing tax row', [
                'row_details' => $row,
                'tax_type' => $row['tax_type'],
                'amount' => $row['amount'],
            ]);

            if ($vatRate > 0) {
                // Handle vatable purchase
                $netAmount = $amount / (1 + ($vatRate / 100));
                $this->vatablePurchase += $netAmount;
                $this->vatAmount += $amount - $netAmount;

                // Calculate ATC if applicable
                if ($atcRate > 0) {
                    $atcAmount = $netAmount * ($atcRate / 100);
                    $row['atc_amount'] = $atcAmount; // Ensure atc_amount is set
                    $this->appliedATCs[$row['tax_code']] = [
                        'code' => $atc->tax_code,
                        'rate' => $atcRate,
                        'amount' => $netAmount,
                        'tax_amount' => $atcAmount,
                    ];
                } else {
                    $row['atc_amount'] = 0; // Ensure atc_amount is set to 0 if no ATC
                }
            } else {
                // Handle non-vatable purchase
                $this->nonVatablePurchase += $amount;
                $row['atc_amount'] = 0; // No ATC for non-vatable purchases
            }
        }

        // Calculate the total amount
        $this->appliedATCsTotalAmount = collect($this->appliedATCs)->sum('tax_amount');
        $vatInclusiveAmount = $this->vatablePurchase + $this->vatAmount;

        // Add both vatable and non-vatable purchases to the total amount
        $this->totalAmount = $vatInclusiveAmount + $this->nonVatablePurchase - $this->appliedATCsTotalAmount;
    }

    public function saveTransaction()
    {
        $this->validate(); // Add validation if not already present

        $organizationId = session('organization_id');

        try {
            $transaction = Transactions::create([
                'transaction_type' => 'Purchase',
                'date' => $this->date,
                'contact' => $this->selectedContact,
                'inv_number' => $this->inv_number,
                'reference' => $this->reference,
                'total_amount' => $this->totalAmount,
                'vatable_purchase' => $this->vatablePurchase,
                'non_vatable_purchase' => $this->nonVatablePurchase,
                'vat_amount' => $this->vatAmount,
                'status' => 'Draft',
                'organization_id' => $organizationId,
            ]);

            foreach ($this->taxRows as $row) {
                TaxRow::create([
                    'transaction_id' => $transaction->id,
                    'description' => $row['description'],
                    'amount' => $row['amount'],
                    'tax_code' => !empty($row['tax_code']) ? $row['tax_code'] : null,
                    'tax_type' => $row['tax_type'],
                    'tax_amount' => $row['tax_amount'],
                    'atc_amount' => isset($row['atc_amount']) ? $row['atc_amount'] : 0,
                    'net_amount' => $row['net_amount'],
                    'coa' => !empty($row['coa']) ? $row['coa'] : null,
                ]);
            }

            session()->flash('message', 'Purchase transaction saved successfully!');
            return redirect()->route('transactions.show', ['transaction' => $transaction->id]);

        } catch (\Exception $e) {
            Log::error('Error saving purchase transaction: ' . $e->getMessage());
            session()->flash('error', 'There was an error saving the purchase transaction.');
        }
    }
    public function render()
    {
        return view('livewire.purchase-transaction', [
            'taxRows' => $this->taxRows,
            'totalAmount' => $this->totalAmount,
            'vatablePurchase' => $this->vatablePurchase,
            'nonVatablePurchase' => $this->nonVatablePurchase,
            'vatAmount' => $this->vatAmount,
            'appliedATCs' => $this->appliedATCs,
            'appliedATCsTotalAmount' => $this->appliedATCsTotalAmount, // Pass this to the view
        ]);
    }
}
