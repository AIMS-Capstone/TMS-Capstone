<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\ATC;

class ATCSelect extends Component
{
    public $name;
    public $options = [];
    public $labelKey;
    public $valueKey;
    public $index;
    
    public $class;
    public $id;
    public $selectedATCCode;
    public $transactionType;

    public function mount(
        $name = 'tax_code', 
        $options = [], 
        $labelKey = 'tax_code', 
        $valueKey = 'id', 
        $class = 'block w-full h-full border-none select2 py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer', 
        $id = 'atc_code_select', 
        $selectedATCCode = null,
        $index = null,
        $transactionType = 'sales'
    ) {
        $this->name = $name;
        $this->selectedATCCode = $selectedATCCode;
        $this->labelKey = $labelKey;
        $this->valueKey = $valueKey;
        $this->class = $class;
        $this->index = $index;
        $this->id = $id;
        $this->transactionType = $transactionType;

        $this->updateOptions();
    }

    public function updatedSelectedATCCode($value)
    {
        $this->dispatch('atcCodeSelected', [
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
        return ATC::where('transaction_type', $this->transactionType)
            ->get()
            ->map(function ($atc) {
                return [
                    'id' => $atc->id,
                    'tax_code' => "{$atc->tax_code} ({$atc->tax_rate}%)",
                    'description' => $atc->description, 
                    'tax_rate' => $atc->tax_rate,
                ];
            })
            ->toArray();
    }
    

    public function render()
    {
        return view('livewire.atc-select', [
            'atcs' => $this->options,
               'selectId' => "atc_select_{$this->index}"
        ]);
    }
}