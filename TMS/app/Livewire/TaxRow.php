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
        // Initialize type
        $this->type = $type;

        // Load initial data
        $this->taxTypes = TaxType::where('transaction_type', $this->type)->get();
        $this->atcs = ATC::where('transaction_type', $this->type)->get();
        $this->coas = Coa::all();
        $this->index = $index;

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

    public function removeRow()
    {
        $this->dispatch('taxRowRemoved', $this->index);  // Emit the event with the row index
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
        if ($this->type === 'sales') {
            // For sales, calculate based on tax_type only
            $taxRate = $this->getTaxRateByType($this->tax_type);
        } else {
            // For purchases, calculate based on tax_code
            $vatRate = $this->getTaxRateByType($this->tax_type);

            // Get tax rate from tax_code (like ATC)
            $taxRateFromCode = $this->getTaxRate($this->tax_code);
    
            // Combine both rates
            $taxRate = $vatRate + $taxRateFromCode;
        }

        // Ensure tax_rate is properly handled even if not found
        $taxRate = $taxRate ?: 0;

        $this->tax_amount = $this->amount * ($taxRate / 100);
        $this->net_amount = $this->amount - $this->tax_amount;
    }

    public function getTaxRate($taxCode)
    {
        $tax = ATC::find($taxCode);
        return $tax ? $tax->tax_rate : 0;
    }

    public function getTaxRateByType($taxTypeId)
    {
        // Fetch the tax type record using its ID
        $taxTypeRecord = TaxType::find($taxTypeId);
        
        // Return the VAT percentage, or 0 if no record is found
        return $taxTypeRecord ? $taxTypeRecord->VAT : 0;
    }

    public function render()
    {
        return view('livewire.tax-row');
    }
}
