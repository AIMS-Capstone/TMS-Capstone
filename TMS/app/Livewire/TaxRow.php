<?php

namespace App\Livewire;

use App\Models\ATC;
use App\Models\Coa;
use App\Models\TaxType;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class TaxRow extends Component
{
    public $taxTypes = [];
    public $atcs = [];
    public $coas = [];
    public $amount = [];
    public $index; // Unique row index
    public $taxRow = [
        'description' => '',
        'tax_code' => null,
        'coa' => null,
        'amount' => 0,
        'tax_amount' => 0,
        'net_amount' => 0,
        'tax_type' => '',
    ];
    protected $listeners = [
        'parentComponentErrorBag',
    ];
    protected $rules = [
        'description' => 'required|string|max:255',
        'tax_type' => 'required|exists:tax_types,id',
        'tax_code' => 'nullable|exists:atcs,id',
        'coa' => 'nullable|exists:coas,id',
        'amount' => 'required|numeric|min:0.01',
    ];

    public $type; // Transaction type (purchase/sales)
    public $mode;

    public function mount($index, $taxRow = null, $type = 'purchase', $mode = 'create')
    {
        $this->index = $index;
        $this->type = $type;
        $this->mode = $mode;

        // Load dropdown options
        $this->taxTypes = TaxType::where('transaction_type', $this->type)->get();
        $this->atcs = ATC::where('transaction_type', $this->type)->get();
        $this->coas = Coa::where('status', 'Active')->get();

        // Populate default or passed taxRow data
        if ($taxRow) {
            $this->taxRow = $taxRow;
        }

        $this->calculateTax();
    }
    public function updated($field)
    {
        if (strpos($field, 'taxRow.') === 0) {
            $fieldName = str_replace('taxRow.', '', $field); // Get field name
            $this->resetErrorBag($fieldName . '.' . $this->index);
            $this->calculateTax();
        }

        $this->dispatch('taxRowUpdated', [
            'index' => $this->index,
            'taxRow' => $this->taxRow,
        ]);
    }
    public function calculateTax()
    {
        Log::info("nae nega nae ");
        $taxRate = $this->getTaxRateByType($this->taxRow['tax_type']);

        if ($taxRate > 0) {
            $vatExclusiveAmount = $this->taxRow['amount'] / (1 + ($taxRate / 100));
            $this->taxRow['tax_amount'] = round($this->taxRow['amount'] - $vatExclusiveAmount, 2);
            $this->taxRow['net_amount'] = round($vatExclusiveAmount, 2);
        } else {
            $this->taxRow['tax_amount'] = 0;
            $this->taxRow['net_amount'] = $this->taxRow['amount'];
        }

        // Dispatch event after calculation with specific target
        $this->dispatch('taxRowUpdated', [
            'index' => $this->index,
            'taxRow' => $this->taxRow,
        ]);
    }

    // Determine parent component for event dispatch
    protected function getParentComponentClass()
    {
        $componentClass = null;

        if ($this->mode === 'edit') {
            // If in edit mode, dispatch to the Edit component
            $componentClass = $this->type === 'sales'
            ? 'App\Livewire\EditSalesTransaction'
            : 'App\Livewire\EditPurchaseTransaction';
        } elseif ($this->mode === 'create') {
            // If in create mode, dispatch to the create component
            $componentClass = $this->type === 'sales'
            ? 'App\Livewire\SalesTransaction'
            : 'App\Livewire\PurchaseTransaction';
        } else {
            // Fallback default
            $componentClass = 'App\Livewire\SalesTransaction';
        }

        // Log the returned component class
        Log::info('Component class determined in getParentComponentClass', [
            'mode' => $this->mode,
            'type' => $this->type,
            'component_class' => $componentClass,
        ]);

        return $componentClass;
    }
    public function removeRow()
    {
        $this->dispatch('taxRowRemoved', $this->index);
    }

    public function reinitializeSelect2()
    {
        $this->dispatch('select2:reinitialize');
    }

    public function parentComponentErrorBag($data)
    {
        $index = $data['index'] ?? null;
        $errors = $data['errors'] ?? [];

        Log::warning('Errors received for TaxRow component: ' . json_encode($errors));

        // Only clear errors for this specific index
        foreach ($errors as $field => $messages) {
            $this->resetErrorBag("$field.$index");
        }

        // Add errors with correct indexing
        foreach ($errors as $field => $messages) {
            $this->addError("$field.$index", $messages[0]); // Append the index to the field name
        }
    }

    public function getTaxRateByType($taxTypeId)
    {
        $taxType = TaxType::find($taxTypeId);
        return $taxType ? $taxType->VAT : 0;
    }

    public function render()
    {
        return view('livewire.tax-row');
    }
}
