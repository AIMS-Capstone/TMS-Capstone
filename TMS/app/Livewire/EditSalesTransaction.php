<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\TaxRow;
use App\Models\ATC;
use App\Models\TaxType;
use App\Models\Coa;
use App\Models\Transactions;
use Illuminate\Support\Facades\Log;

class EditSalesTransaction extends Component
{
    public $transactionId;
    public $type = 'sales';
    public $taxRows = [];
    public $totalAmount = 0;
    public $vatableSales = 0;
    public $vatAmount = 0;
    public $nonVatableSales = 0;
    public $date;
    public $status;
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

    public function mount($transactionId = null)
    {
        $this->transactionId = $transactionId;
        $this->organization_id = session('organization_id');

        if ($this->transactionId) {
            // Load existing transaction and tax rows
            $transaction = Transactions::with('taxRows')->findOrFail($this->transactionId);
            $this->date = $transaction->date;
            $this->inv_number = $transaction->inv_number;
            $this->reference = $transaction->reference;
            $this->totalAmount = $transaction->total_amount;
            $this->vatableSales = $transaction->vatable_sales;
            $this->vatAmount = $transaction->vat_amount;
            $this->nonVatableSales = $transaction->non_vatable_sales;
            $this->selectedContact = $transaction->contact;

      
            foreach ($transaction->taxRows as $taxRow) {
                $this->taxRows[] = [
                    'description' => $taxRow->description,
                    'tax_type' => $taxRow->tax_type,
                    'tax_code' => $taxRow->tax_code,
                    'coa' => $taxRow->coa,
                    'amount' => $taxRow->amount,
                    'tax_amount' => $taxRow->tax_amount,
                    'net_amount' => $taxRow->net_amount,
                ];
            }
        } else {
            // Initialize with three empty rows
            $this->addTaxRow();
            $this->addTaxRow();
            $this->addTaxRow();
        }
    }

    public function addTaxRow()
    {
        $this->taxRows[] = [
            'description' => '',
            'tax_type' => '',
            'tax_code' => null,
            'coa' => '',
            'amount' => 0,
            'tax_amount' => 0,
            'net_amount' => 0,
        ];
    }

    public function removeTaxRow($index)
    {
        unset($this->taxRows[$index]);
        $this->taxRows = array_values($this->taxRows); // Reindex array
        $this->calculateTotals(); // Update totals after row removal
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
    

    private function calculateTaxAmount($amount, $taxType)
    {
        $taxRate = $taxType === 'VAT' ? 0.12 : 0; // 12% for VAT, 0% otherwise
        return $amount * $taxRate;
    }

    public function saveTransaction()
    {
      
      
        try {
            $this->organization_id = session('organization_id');
            $transactionData = [
                'transaction_type' => 'Sales',
                'date' => $this->date,
                'contact' => $this->selectedContact,
                'inv_number' => $this->inv_number,
                'reference' => $this->reference,
                'total_amount' => $this->totalAmount,
                'vatable_sales' => $this->vatableSales,
                'vat_amount' => $this->vatAmount,
                'non_vatable_sales' => $this->nonVatableSales,
                'organization_id' => $this->organization_id,
                'status' => 'Draft',
            ];

            if ($this->transactionId) {
                // Update existing transaction
                $transaction = Transactions::findOrFail($this->transactionId);
                $transaction->update($transactionData);
                $transaction->taxRows()->delete(); // Delete old tax rows
            } else {
                // Create a new transaction
                $transaction = Transactions::create($transactionData);
            }

            foreach ($this->taxRows as $row) {
                TaxRow::create([
                    'transaction_id' => $transaction->id,
                    'description' => $row['description'],
                    'amount' => $row['amount'],
                    'tax_code' => $row['tax_code'] ?? null,
                    'tax_type' => $row['tax_type'],
                    'tax_amount' => $row['tax_amount'],
                    'net_amount' => $row['net_amount'],
                    'coa' => $row['coa'] ?? null,
                ]);
            }

            session()->flash('message', 'Transaction saved successfully!');
            return redirect()->route('transactions.show', ['transaction' => $transaction->id]);

        } catch (\Exception $e) {
            Log::error('Error saving transaction: ' . $e->getMessage());
            session()->flash('error', 'There was an error saving the transaction.');
        }
    }

    public function render()
    {
        return view('livewire.edit-sales-transaction', [
            'type' => $this->type,
        ]);
    }
}
