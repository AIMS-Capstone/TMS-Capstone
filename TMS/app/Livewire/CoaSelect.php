<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Coa;

class CoaSelect extends Component
{
    public $name;
    public $options = [];
    public $labelKey;
    public $valueKey;
    
    public $index;
    public $class;
    public $id;
    public $selectedCoa;
    public $status;

    public function mount(
        $name = 'coa', 
        $options = [], 
        $labelKey = 'display', 
        $valueKey = 'id', 
        $class = 'block w-full h-full border-none select2 py-2.5 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer', 
        $id = 'coa_select', 
        $selectedCoa = null,
        $status = 'Active',
        $index = null
    ) {
        $this->name = $name;
        $this->selectedCoa = $selectedCoa;
        $this->labelKey = $labelKey;
        $this->valueKey = $valueKey;
        $this->class = $class;
        $this->index = $index;
        $this->id = $id;
        $this->status = $status;

        $this->updateOptions();
    }

    public function updatedSelectedCoa($value)
    {
        $this->dispatch('coaSelected', [
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
        return Coa::where('status', $this->status)
            ->get()
            ->map(function ($coa) {
                return [
                    'id' => $coa->id,
                    'display' => "{$coa->code} {$coa->name}",
                    'code' => $coa->code,
                    'name' => $coa->name
                ];
            })
            ->toArray();
    }

    public function render()
    {
        return view('livewire.coa-select', [
            'coas' => $this->options,
              'selectId' => "coa_select_{$this->index}"
        ]);
    }
}