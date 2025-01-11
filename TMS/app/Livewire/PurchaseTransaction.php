<?php

namespace App\Livewire;

use App\Models\Atc;
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

    public function rules()
    {
        $rules = [];

        // Base rules
        $rules['date'] = 'required|date';
        $rules['reference'] = 'nullable|string';
        $rules['selectedContact'] = 'required|exists:contacts,id';

        // Dynamic rules for tax rows
        foreach ($this->taxRows as $index => $row) {
            $rules["taxRows.{$index}.amount"] = 'required|numeric|min:0.01';
            $rules["taxRows.{$index}.tax_code"] = 'nullable|exists:atcs,id';
            $rules["taxRows.{$index}.coa"] = 'required|string';
            $rules["taxRows.{$index}.description"] = 'required|string';
            $rules["taxRows.{$index}.tax_type"] = 'required|exists:tax_types,id';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        // Base messages
        $messages['selectedContact.required'] = 'Please select a contact.';
        $messages['selectedContact.exists'] = 'The selected contact is invalid.';
        $messages['date.required'] = 'The date field is required.';
        $messages['inv_number.required'] = 'The invoice number is required.';

        // Dynamic messages for tax rows
        foreach ($this->taxRows as $index => $row) {
            $rowNum = $index + 1;
            $messages["taxRows.{$index}.amount.required"] = "The amount field in Row #{$rowNum} is required.";
            $messages["taxRows.{$index}.amount.min"] = "The amount field in Row #{$rowNum} must be at least :min.";
            $messages["taxRows.{$index}.coa.required"] = "The chart of accounts field in Row #{$rowNum} is required.";
            $messages["taxRows.{$index}.description.required"] = "The description field in Row #{$rowNum} is required.";
            $messages["taxRows.{$index}.tax_type.required"] = "The tax type field in Row #{$rowNum} is required.";
        }

        return $messages;
    }
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
        $this->rendered();
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
    public function rendered(): void
    {
        Log::info('rendered reached');

        // First, check for contact selection errors

        $this->resetValidation();
        if ($this->getErrorBag()->has('selectedContact')) {
            $this->dispatch('contactError', [
                'index' => 'select_contact', // Use a unique identifier for the contact field
                'errors' => [
                    'contact' => $this->getErrorBag()->get('selectedContact')
                ]
            ]);
            Log::info('Contact Error: ' . json_encode($this->getErrorBag()->get('selectedContact')));
        }

        // Then handle tax row errors as before
        foreach ($this->taxRows as $index => $row) {
            $rowErrors = [];

            $errorFields = [
                'description' => "taxRows.{$index}.description",
                'tax_type' => "taxRows.{$index}.tax_type", 
                'tax_code' => "taxRows.{$index}.tax_code",
                'coa' => "taxRows.{$index}.coa", 
                'amount' => "taxRows.{$index}.amount"
            ];

            foreach ($errorFields as $field => $errorKey) {
                if ($this->getErrorBag()->has($errorKey)) {
                    $rowErrors[$field] = $this->getErrorBag()->get($errorKey);
                    Log::info($rowErrors[$field] = $this->getErrorBag()->get($errorKey));
                }
            }

            if (!empty($rowErrors)) {
                Log::info('Row Errors: ' . json_encode($rowErrors));
                $this->dispatch('parentComponentErrorBag', [
                    'index' => $row['id'], 
                    'errors' => $rowErrors
                ]);
            }
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
