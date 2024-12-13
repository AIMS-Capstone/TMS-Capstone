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
    public $selectedTaxType;
    public $mode;
    protected $listeners = [
        'parentComponentErrorBag', 'taxTypeSelected'  => 'handleTaxTypeSelection', 'atcCodeSelected' => 'handleAtcCodeSelection', 'coaSelected' =>'handleCoaSelection'
    ];
    protected $rules = [
        'description' => 'nullable|string|max:255',
        'tax_type' => 'required|exists:tax_types,id',
        'tax_code' => 'nullable|exists:atcs,id',
        'coa' => 'nullable|exists:coas,id',
        'amount' => 'required|numeric|min:0.01',
    ];

    // Handle initialization for both edit and create
    public function mount($index, $taxRow = null, $type = 'purchase', $mode ='create')
    {
        $this->mode = $mode;
        $this->type = $type;
        $this->index = $index;

        
        // Load initial data based on the type
        $this->taxTypes = TaxType::where('transaction_type', $this->type)->get();
        $this->atcs = ATC::where('transaction_type', $this->type)->get();
        $this->coas = Coa::where('status', 'Active')->get();
    
        
        // If editing, populate fields with taxRow data; else, use default values for creating
        if ($taxRow) {
            $this->tax_code = $taxRow['tax_code'] ?? null;
            $this->coa = $taxRow['coa'] ?? null;
            $this->amount = $taxRow['amount'] ?? 0;
            $this->tax_amount = $taxRow['tax_amount'] ?? 0;
            $this->net_amount = $taxRow['net_amount'] ?? 0;
            $this->description = $taxRow['description'] ?? '';
            $this->tax_type = $taxRow['tax_type'] ?? '';
        } else {
            // Initialize for create
            $this->tax_code = null;
            $this->coa = null;
            $this->amount = 0;
            $this->tax_amount = 0;
            $this->net_amount = 0;
            $this->description = '';
            $this->tax_type = '';
        }

        // Calculate tax (if any initial values are available)
        $this->calculateTax();
    }
    public function handleAtcCodeSelection($data)
    {
        // Ensure you're handling the array input   
        $atcId = is_array($data) ? ($data['value'] ?? null) : $data;
        $index = is_array($data) ? ($data['index'] ?? null) : null;
    
        // Only proceed if we have a valid ATC ID and the index matches this row's index
        if ($atcId !== null && $index == $this->index) {
            $this->tax_code = $atcId;
            
            // Directly call calculateTax method
            $this->calculateTax();
        }
    }
    
    public function handleTaxTypeSelection($data)
    {
        // Ensure you're handling the array input   
        $value = is_array($data) ? ($data['value'] ?? null) : $data;
        $index = is_array($data) ? ($data['index'] ?? null) : null;
    
        // Only proceed if we have a valid value and the index matches this row's index
        if ($value !== null && $index == $this->index) {
            $this->tax_type = $value;
            
            // Directly call calculateTax method
            $this->calculateTax();
        }
    }
    public function handleCoaSelection($data)
    {
        // Ensure you're handling the array input   
        $coaId = is_array($data) ? ($data['value'] ?? null) : $data;
        $index = is_array($data) ? ($data['index'] ?? null) : null;
    
        // Only proceed if we have a valid COA ID and the index matches this row's index
        if ($coaId !== null && $index == $this->index) {
            $this->coa = $coaId;
            
            // Directly call calculateTax method
            $this->calculateTax();
        }
    }
    // Method to remove the row
    public function removeRow()
    {
        $this->dispatch('taxRowRemoved', $this->index);  // Emit the event with the row index
    }

    // Automatically update tax when specific fields are updated
    public function updated($field)
    {
        $this->validateOnly($field);
    
        if (in_array($field, ['tax_code', 'amount', 'tax_type'])) {
            $this->calculateTax();
            
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
    // Determine parent component for event dispatch
    protected function getParentComponentClass()
    {
        if ($this->mode === 'edit') {
            // If in edit mode, dispatch to the Edit component
            return $this->type === 'sales' ? 'App\Livewire\EditSalesTransaction' : 'App\Livewire\EditPurchaseTransaction';
        }
    
        // If in create mode, dispatch to the create component
        if ($this->mode === 'create') {
            return $this->type === 'sales' ? 'App\Livewire\SalesTransaction' : 'App\Livewire\PurchaseTransaction';
        }
    
        // Fallback default
        return 'App\Livewire\SalesTransaction';
    }
    public function parentComponentErrorBag($data)
    {
        $index = $data['index'] ?? null;
        $errors = $data['errors'] ?? [];
    
        // Clear existing errors first
        $this->resetErrorBag();
    
        // Add errors passed from the parent
        foreach ($errors as $field => $messages) {
            // Remove the "taxRows.index." prefix if present
            $cleanField = preg_replace('/^taxRows\.\d+\./', '', $field);
            $this->addError($cleanField, $messages[0]);
        }
    }
    // Calculate tax and net amounts
    public function calculateTax()
    {
        $taxRate = $this->getTaxRateByType($this->tax_type);

        if ($taxRate > 0) {
            $vatExclusiveAmount = $this->amount / (1 + ($taxRate / 100));
            $this->tax_amount = $this->amount - $vatExclusiveAmount; // VAT amount
            $this->net_amount = $vatExclusiveAmount; // VAT-exclusive amount
        } else {
            $this->tax_amount = 0;
            $this->net_amount = $this->amount;
        }

        $this->dispatch('taxRowUpdated', [
            'index' => $this->index,
            'description' => $this->description,
            'tax_type' => $this->tax_type,
            'tax_code' => $this->tax_code,
            'coa' => $this->coa,
            'amount' => $this->amount,
            'tax_amount' => $this->tax_amount,
            'net_amount' => $this->net_amount,
        ]);
    }

    // Fetch the tax rate for a specific tax type
    public function getTaxRateByType($taxTypeId)
    {
        $taxTypeRecord = TaxType::find($taxTypeId);
        return $taxTypeRecord ? $taxTypeRecord->VAT : 0;
    }

    public function render()
    {
        return view('livewire.tax-row');
    }
}
