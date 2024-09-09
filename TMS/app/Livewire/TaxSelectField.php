<?php
namespace App\Livewire;

use Livewire\Attributes\Modelable;
use Livewire\Component;

class TaxSelectField extends Component
{    #[Modelable]
    public $id;
    public $name;
 
    public $options = [];
    public $value = null;
    public $labelKey;  // New property for label key

    public function updatedSelected($value)
    {
        // Emit the updated value to the parent component
        $this->dispatch('selectUpdated', $value);
    }

    public function render()
    {
        return view('livewire.tax-select-field');
    }
}