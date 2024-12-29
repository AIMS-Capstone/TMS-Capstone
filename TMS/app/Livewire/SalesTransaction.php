<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\TaxRow;
use App\Models\ATC;
use App\Models\TaxType;
use App\Models\Coa;
use App\Models\Transactions;
use Illuminate\Support\Facades\Log;

class SalesTransaction extends Component
{
    public $type = 'sales';
    public $taxRows = [];
    public $appliedATCsTotalAmount = 0;
    public $appliedATCs = [];
    public $totalAmount = 0;
    public $vatableSales = 0;
    public $vatAmount = 0;
    public $nonVatableSales = 0; // New property to hold non-vatable sales
    public $date;
    public $status;
    public $contact;
    public $inv_number;
    public $organization_id;
    public $reference;
    public $errors = [];

    public $selectedContact;

    protected $listeners = ['taxRowUpdated' => 'updateTaxRow', 'contactSelected', 'taxRowRemoved' => 'removeTaxRow'];
 
    public function rules()
    {
        $rules = [];
        
        // Base rules
        $rules['date'] = 'required|date';
        $rules['inv_number'] = 'required|string';
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
 
        $this->organization_id = session('organization_id');
       
    }
    public function getValidationMessages()
    {
        $messages = $this->messages;
        
        foreach ($this->taxRows as $index => $row) {
            foreach ($messages as $key => $message) {
                if (strpos($key, 'taxRows.*') === 0) {
                    $newKey = str_replace('*', $index, $key);
                    // Add 1 and convert to ordinal
                    $rowNumber = $this->numberToOrdinal($index + 1);
                    // Replace both the key and the index in the message
                    $messages[$newKey] = str_replace(':index', $rowNumber, $message);
                }
            }
        }
        
        return $messages;
    }
private function numberToOrdinal($number)
{
    $suffix = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
    
    if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
        return $number . 'th';
    } else {
        return $number . $suffix[$number % 10];
    }
}
    public function addTaxRow()
    {
        // Use count of existing rows or generate a unique identifier
        $newIndex = count($this->taxRows);
        
        $this->taxRows[] = [
            'id' => uniqid(), // Add a unique identifier
            'description' => '',
            'tax_type' => '',
            'tax_code' => '',
            'coa' => '',
            'amount' => 0,
            'tax_amount' => 0,
            'net_amount' => 0
        ];
    
        // Dispatch event to reinitialize Select2
        $this->dispatch('select2:reinitialize');
    }
    

    public function contactSelected($contactId)
    {
        $this->selectedContact = $contactId;
    }

    public function removeTaxRow($index)
    {
        // Find the key of the row with the matching index
        $key = array_search($index, array_column($this->taxRows, 'id'));
        
        if ($key !== false) {
            unset($this->taxRows[$key]);
            $this->taxRows = array_values($this->taxRows); // Re-index array
            $this->calculateTotals(); // Recalculate after removing a row
        }
    }

    public function updateTaxRow($data)
    {
        // Find the key of the row with the matching ID
        $key = array_search($data['index'], array_column($this->taxRows, 'id'));
        
        if ($key !== false) {
            $this->taxRows[$key] = $data['taxRow'];
            $this->calculateTotals();
        }
    }
    public function selectReinit()
    {
       Log::info("naabot sa sales");
        $this->dispatch('select2:reinitialize');
    }
    public function calculateTotals()
    {
    
        // Initialize totals
        $this->vatableSales = 0;
        $this->vatAmount = 0;  // This will store the VAT amount (not including ATC)
        $this->totalAmount = 0;
        $this->nonVatableSales = 0;
        $this->appliedATCs = [];  // To store ATC details
        $this->appliedATCsTotalAmount = 0;  // Total ATC tax amount
    
        foreach ($this->taxRows as &$row) {
            $amount = $row['amount']; // Total Amount Due (Gross Sales)
            $taxTypeId = $row['tax_type'];
            $taxCode = $row['tax_code'];
    
            // Find the tax type and rate
            $taxType = TaxType::find($taxTypeId);
            $taxRate = $taxType ? $taxType->VAT : 0; // Get VAT or PT rate
    
            // Find ATC and its rate
            $atc = ATC::find($taxCode);
            $atcRate = $atc ? $atc->tax_rate : 0;  // ATC rate if applicable
    
            // If it's Percentage Tax (PT)
            if ($taxType && $taxType->short_code == 'PT') {
                // If there's an ATC, first calculate the net sales (without ATC)
                if ($atcRate > 0) {
                    // Step 1: Calculate VATable Sales (Net) before ATC (i.e., remove the ATC portion)
                    $netSales = $amount / (1 + ($atcRate / 100)); // Net Sales without ATC
    
                    // Step 2: Calculate the ATC tax
                    $atcAmount = $netSales * ($atcRate / 100);
    
                    // Step 3: Add to VATable Sales and VAT Amount
                    $this->vatableSales += $netSales; // Only net amount (VATable Sales)
                    $this->vatAmount += $netSales * ($taxRate / 100); // VAT is calculated only on net sales, excluding ATC
    
                    // Store ATC details
                    $this->appliedATCs[$taxCode] = [
                        'code' => $atc->tax_code,
                        'rate' => $atcRate,
                        'amount' => $amount,
                        'tax_amount' => $atcAmount
                    ];
                    $row['atc_amount'] = $atcAmount; // Store ATC amount for this row
                } else {
                    // No ATC, full amount is VATable
                    $this->vatableSales += $amount;
                    $this->vatAmount += $amount * ($taxRate / 100); // Apply VAT on the full amount
                    $row['atc_amount'] = 0; // No ATC applied
                }
            } else {
                // Non-Percentage Tax sales (e.g., VAT or Non-VATable)
                if ($taxRate > 0) {
                    // For VAT, calculate net amount and VAT
                    $netAmount = $amount / (1 + ($taxRate / 100));
                    $this->vatableSales += $netAmount;
                    $this->vatAmount += $amount - $netAmount; // Add VAT amount
                } else {
                    // Non-VATable sales
                    $this->nonVatableSales += $amount;
                    $row['atc_amount'] = 0; // No ATC for non-vatable sales
                }
            }
    
            // Add the gross amount (Total Amount Due) to total
            $this->totalAmount += $amount;
        }
    
        // Sum up all applied ATC tax amounts
        $this->appliedATCsTotalAmount = collect($this->appliedATCs)->sum('tax_amount');
    
        // Final adjustment to the total amount: VATable Sales + Non-VATable Sales + ATC tax
        $this->totalAmount = $this->vatableSales + $this->vatAmount + $this->nonVatableSales + $this->appliedATCsTotalAmount; // Total includes ATC tax

        $this->rendered();
    }
    
    

    public function saveTransaction()
    {
        $this->validate(
            $this->rules(),
            $this->messages()
        );
        // Retrieve organization ID from the session
        $this->organization_id = session('organization_id');
        
        // Create a transaction with 'Sales' type
        try {
            $transaction = Transactions::create([
                'transaction_type' => 'Sales',
                'date' => $this->date,
                'contact' => $this->selectedContact,
                'inv_number' => $this->inv_number,
                'reference' => $this->reference,
                'total_amount' => $this->totalAmount,
                'vatable_sales' => $this->vatableSales,
                'vat_amount' => $this->vatAmount,
                'non_vatable_sales' => $this->nonVatableSales,
                'organization_id' => $this->organization_id, // Ensure this is included
                'status'=> 'Draft'
            ]);
    
            // Log the transaction ID after saving
            Log::info('Transaction saved successfully with ID: ' . $transaction->id);
    
            // Save each tax row linked to the transaction
            foreach ($this->taxRows as $row) {
                // If the tax type is 'Percentage Tax' and there is an ATC code
                if ($row['tax_type'] && $row['tax_type'] == 'PT' && !empty($row['tax_code'])) {
                    // Calculate and set ATC Amount in the row
                    $atc = ATC::find($row['tax_code']); // Find the ATC by its code
                    $atcRate = $atc ? $atc->tax_rate : 0;
                    if ($atcRate > 0) {
                        $netSales = $row['amount'] / (1 + ($atcRate / 100)); // Net amount excluding ATC
                        $row['atc_amount'] = $netSales * ($atcRate / 100); // ATC amount
                    }
                }
    
                // Insert the tax row, including the atc_amount if applicable
                TaxRow::create([
                    'transaction_id' => $transaction->id,
                    'description' => $row['description'],
                    'amount' => $row['amount'],
                    'tax_code' => !empty($row['tax_code']) ? $row['tax_code'] : null,
                    'tax_type' => $row['tax_type'],
                    'tax_amount' => $row['tax_amount'],
                    'net_amount' => $row['net_amount'],
                    'coa' => !empty($row['coa']) ? $row['coa'] : null,
                    'atc_amount' => isset($row['atc_amount']) ? $row['atc_amount'] : 0,  // Insert the atc_amount
                ]);
            }
    
            // Provide feedback to the user
            session()->flash('message', 'Transaction saved successfully!');
            return redirect()->route('transactions.show', ['transaction' => $transaction->id]);
    
        } catch (\Exception $e) {
            // Log the error for further investigation
            Log::error('Error saving transaction: ' . $e->getMessage());
            session()->flash('error', 'There was an error saving the transaction.');
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
        return view('livewire.sales-transaction', [
            'type' => $this->type
        ]);
    }
}