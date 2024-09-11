<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\ATC;
use App\Models\TaxType;
use App\Models\Coa;

class TaxRow extends Component
{
    public $taxTypes = [];
    public $atcs = [];
    public $coas = [];
    public $tax_code;
    public $coa;
    public $amount = 0;
    public $tax_amount = 0;
    public $net_amount = 0;
    public $index; // Unique row index
    public $description; // Added if needed
    public $tax_type; // Added if needed
    public $type; // Added to store transaction type

    public function mount($index, $taxRow = [], $type = 'purchase')
    {
        // Load initial data
        $this->taxTypes = TaxType::all();
        $this->atcs = ATC::all();
        $this->coas = Coa::all();
        $this->index = $index;
        $this->type = $type; // Initialize type

        // Initialize properties from $taxRow
        $this->tax_code = $taxRow['tax_code'] ?? null;
        $this->coa = $taxRow['coa'] ?? null;
        $this->amount = $taxRow['amount'] ?? 0;
        $this->tax_amount = $taxRow['tax_amount'] ?? 0;
        $this->net_amount = $taxRow['net_amount'] ?? 0;
        $this->description = $taxRow['description'] ?? '';
        $this->tax_type = $taxRow['tax_type'] ?? '';

        // Calculate tax on mount
        $this->calculateTax();
    }

    public function updated($field)
    {
        if (in_array($field, ['tax_code', 'amount', 'tax_type'])) {
            $this->calculateTax();
            // Dispatch event to the appropriate parent component based on type
            $this->dispatch('taxRowUpdated', [
                'index' => $this->index,
                'description' => $this->description,
                'tax_type' => $this->tax_type,
                'tax_code' => $this->tax_code,
                'coa' => $this->coa,
                'amount' => $this->amount,
                'tax_amount' => $this->tax_amount,
                'net_amount' => $this->net_amount,
            ])->to($this->getParentComponentClass());
        }
    }

    protected function getParentComponentClass()
    {
        return $this->type === 'sales' ? 'App\Livewire\SalesTransaction' : 'App\Livewire\PurchaseTransaction';
    }

    public function calculateTax()
    {
        $taxRate = $this->getTaxRate($this->tax_code);
        $this->tax_amount = $this->amount * ($taxRate / 100);
        $this->net_amount = $this->amount - $this->tax_amount;
    }

    public function getTaxRate($taxCode)
    {
        $tax = ATC::find($taxCode);
        return $tax ? $tax->tax_rate : 0;
    }

    public function render()
    {
        return view('livewire.tax-row');
    }
}
