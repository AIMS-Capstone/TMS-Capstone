<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\TaxRow;
use App\Models\ATC;
use App\Models\TaxType;
use App\Models\Coa;
use App\Models\Transactions;

class SalesTransaction extends Component
{
    public $type = 'sales';
    public $taxRows = [];
    public $totalAmount = 0;
    public $vatableSales = 0;
    public $vatAmount = 0;
    public $nonVatableSales = 0; // New property to hold non-vatable sales
    public $date;
    public $contact;
    public $inv_number;
    public $organization_id;
    public $reference;
    public $selectedContact;

    protected $listeners = ['taxRowUpdated' => 'updateTaxRow', 'contactSelected', 'taxRowRemoved' => 'removeTaxRow'];

    protected $rules = [
        'date' => 'required|date',
        'inv_number' => 'required|string',
        'reference' => 'nullable|string',
        'taxRows.*.amount' => 'required|numeric|min:0',
        'taxRows.*.tax_code' => 'nullable|exists:atcs,id',
        'taxRows.*.coa' => 'nullable|string',
    ];

    public function mount()
    {
        $this->addTaxRow();
        $this->addTaxRow();
        $this->addTaxRow();
        $this->organization_id = session('organization_id');
       
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
            'net_amount' => 0
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
        // Initialize totals to zero
        $this->vatableSales = 0;
        $this->vatAmount = 0;
        $this->totalAmount = 0;
        $this->nonVatableSales = 0; // Reset non-vatable sales
    
        // Loop through each tax row to calculate the totals
        foreach ($this->taxRows as $row) {
            $taxType = TaxType::find($row['tax_type']);
            $vatRate = $taxType ? $taxType->VAT : 0; // Get VAT rate (0 or 12)
    
            if ($vatRate > 0) {
                // VATable sales
                $this->vatableSales += $row['amount'] - $row['tax_amount'];
                $this->vatAmount += $row['tax_amount'];
            } else {
                // Non-VATable sales
                $this->nonVatableSales += $row['amount'];
            }
            
            $this->totalAmount += $row['amount'];
        }
    }
    

    public function saveTransaction()
    {
        // Validate the required fields
        $this->validate();
    
        // Retrieve organization ID from the session
       
    
    
    
        // Create a transaction with 'Sales' type
        try {
         
            $this->organization_id = session('organization_id');
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
            ]);
    
            // Log the transaction ID after saving
            \Log::info('Transaction saved successfully with ID: ' . $transaction->id);
    
            // Save each tax row linked to the transaction
            foreach ($this->taxRows as $row) {
                TaxRow::create([
                    'transaction_id' => $transaction->id,
                    'description' => $row['description'],
                    'amount' => $row['amount'],
                    'tax_code' => !empty($row['tax_code']) ? $row['tax_code'] : null,
                    'tax_type' => $row['tax_type'],
                    'tax_amount' => $row['tax_amount'],
                    'net_amount' => $row['net_amount'],
                    'coa' => !empty($row['coa']) ? $row['coa'] : null,
                ]);
            }
    
            // Provide feedback to the user
            session()->flash('message', 'Transaction saved successfully!');
            return redirect()->route('transactions.show', ['transaction' => $transaction->id]);
    
        } catch (\Exception $e) {
            // Log the error for further investigation
            \Log::error('Error saving transaction: ' . $e->getMessage());
            session()->flash('error', 'There was an error saving the transaction.');
        }
    }
    
    

    public function render()
    {
        return view('livewire.sales-transaction', [
            'type' => $this->type
        ]);
    }
}
