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
        $this->validate();
    
        // Create a transaction with 'Sales' type
        $transaction = Transactions::create([
            'transaction_type' => 'Sales',
            'date' => $this->date,
            'contact' =>  $this->selectedContact,
            'inv_number' => $this->inv_number,
            'reference' => $this->reference,
            'total_amount' => $this->totalAmount,
            'vatable_sales' => $this->vatableSales,
            'vat_amount' => $this->vatAmount,
            'non_vatable_sales' => $this->nonVatableSales // Save non-vatable sales
        ]);
    
        // Save each tax row linked to the transaction
        foreach ($this->taxRows as $row) {
            TaxRow::create([
                'transaction_id' => $transaction->id,
                'description' => $row['description'],
                'amount' => $row['amount'],
                'tax_code' => $row['tax_code'],
                'tax_type' => $row['tax_type'],
                'tax_amount' => $row['tax_amount'],
                'net_amount' => $row['net_amount'],
                'coa' => $row['coa'],
            ]);
        }
    
        // Optionally, redirect or provide feedback to the user
        session()->flash('message', 'Transaction saved successfully!');
        return redirect()->route('transactions.show', ['transaction' => $transaction->id]);
    }
    

    public function render()
    {
        return view('livewire.sales-transaction', [
            'type' => $this->type
        ]);
    }
}
