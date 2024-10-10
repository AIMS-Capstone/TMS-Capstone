<?php

namespace App\Livewire;

use Livewire\Component;
class TaxInputField extends Component
{    public $id;
    public $type;
    public $value;
    public $readonly = false;

    public function updatedValue($value)
    {
        // Emit the updated value to the parent component
        $this->dispatch('inputUpdated', $value);
    }

    public function render()
    {
        return view('livewire.tax-input-field');
    }
}