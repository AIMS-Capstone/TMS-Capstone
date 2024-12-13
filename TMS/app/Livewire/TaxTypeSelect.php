<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\TaxType;

class TaxTypeSelect extends Component
{
    public $name;
    public $options = [];
    public $labelKey;
    public $valueKey;
    
    public $class;
    public $id;
    public $selectedTaxType = [];
    public $index;

    public function mount(
        $name = 'tax_type', 
        $options = [], 
        $labelKey = 'name', 
        $valueKey = 'value', 
        $class = 'form-control w-full select2', 
        $id = 'tax_type_select', 
        $selectedTaxType = null,
        $index = null
    ) {
        $this->name = $name;
        $this->selectedTaxType = $selectedTaxType ?? [];
        $this->labelKey = $labelKey;
        $this->valueKey = $valueKey;
        $this->class = $class;
        $this->index = $index;
        $this->id = $id ? $id . '_' . $index : 'tax_type_select_' . $index;

        $this->updateOptions();
    }
    public function updatedSelectedTaxType($value)
    {
        // Dispatch event with both value and specific index
        $this->dispatch('taxTypeSelected', [
            'value' => $value, 
            'index' => $this->index
        ]);
    }

    public function updateOptions()
    {
        $this->options = $this->fetchOptions();

        // Dispatch an event to refresh the Select2 dropdown
        $this->dispatch('refreshDropdown', [
            'id' => $this->id,
            'options' => $this->options
        ]);
    }

    protected function fetchOptions()
    {
        return TaxType::all()->map(function ($taxType) {
            return [
                'value' => $taxType->id,
                'name' => "{$taxType->tax_type} ({$taxType->VAT}%)",
                'vat' => $taxType->VAT
            ];
        })->toArray();
    }

    public function render()
    {
        return view('livewire.tax-type-select', [
            'selectId' => $this->id
        ]);
    }
}