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
    public $taxRows = [];
    public $totalAmount = 0;
    public $date;
    public $inv_number;
    public $reference;

    protected $listeners = ['taxRowUpdated' => 'updateTaxRow'];

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

    public function removeTaxRow($index)
    {
        unset($this->taxRows[$index]);
        $this->taxRows = array_values($this->taxRows); // Re-index array
        $this->totalAmount = $this->calculateTotalAmount();
    }

    public function updateTaxRow($data)
    {
        $this->taxRows[$data['index']] = $data;
        $this->totalAmount = $this->calculateTotalAmount();
    }

    public function calculateTotalAmount()
    {
        return collect($this->taxRows)->sum('amount');
    }

    public function saveTransaction()
    {
        $this->validate();

        // Create a transaction with 'Sales' type
        $transaction = Transactions::create([
            'transaction_type' => 'Sales',
            'date' => $this->date,
            'inv_number' => $this->inv_number,
            'reference' => $this->reference,
            'total_amount' => $this->totalAmount,
        ]);

        // Save each tax row linked to the transaction
        foreach ($this->taxRows as $row) {
            TaxRow::create([
                'transaction_id' => $transaction->id,
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
            'taxTypes' => TaxType::all(),
            'atcs' => ATC::all(),
            'coas' => Coa::all()
        ]);
    }
}
