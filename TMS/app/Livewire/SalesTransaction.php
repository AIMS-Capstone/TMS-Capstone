<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Atc;
use App\Models\Coa;
use App\Models\Transactions;
use App\Models\Contacts;
use App\Models\TaxType;

class SalesTransaction extends Component
{
    public $contacts;
    public $taxTypes;
    public $atcs;
    public $coas;
    public $contact_id;
    public $tax_code;
    public $amount = 0;
    public $tax_amount = 0;
    public $net_amount = 0;
    public $description;
    public $coa;
    public $reference;
    public $date;

    public function mount()
    {
        $this->contacts = Contacts::all();
        $this->taxTypes = TaxType::all();
        $this->atcs = Atc::all();
        $this->coas = Coa::all();
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'amount' || $propertyName === 'tax_code') {
            $this->calculateAmounts();
        }
    }

    public function calculateAmounts()
    {
        $atc = $this->atcs->find($this->tax_code);

        // Ensure amount is a number
        $amount = is_numeric($this->amount) ? (float) $this->amount : 0;

        if ($atc) {
            // Ensure tax rate is a number
            $tax_rate = is_numeric($atc->tax_rate) ? (float) $atc->tax_rate : 0;

            // Calculate tax amount and net amount based on the tax rate
            $this->tax_amount = $amount * ($tax_rate / 100);
            $this->net_amount = $amount + $this->tax_amount;
        } else {
            // Clear tax amount and net amount if ATC is not selected
            $this->tax_amount = 0;
            $this->net_amount = $amount;
        }
    }

    public function saveTransaction()
    {
        $this->validate([
            'contact_id' => 'required',
            'tax_code' => 'required',
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'coa' => 'required|string',
            'reference' => 'required|string',
            'date' => 'required|date',
        ]);

        // Store the transaction
        $transaction = new Transactions();
        $transaction->contact_id = $this->contact_id;
        $transaction->tax_code = $this->tax_code;
        $transaction->amount = $this->amount;
        $transaction->tax_amount = $this->tax_amount;
        $transaction->net_amount = $this->net_amount;
        $transaction->description = $this->description;
        $transaction->coa = $this->coa;
        $transaction->reference = $this->reference;
        $transaction->date = $this->date;
        $transaction->save();

        // Reset form fields
        $this->reset();

        session()->flash('message', 'Transaction saved successfully!');
    }

    public function render()
    {
        return view('livewire.sales-transaction');
    }
}
