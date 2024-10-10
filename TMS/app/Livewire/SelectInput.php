<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Contacts;

class SelectInput extends Component
{
    public $name;
    public $options = [];
    public $labelKey;
    public $valueKey;
    public $class;
    public $id;
    public $isGrouped;
    public $type;
    public $selectedValue; 

    protected $listeners = ['contactAddedToSelectContact' => 'updateOptions'];

    public function mount($name, $options = [], $labelKey = 'name', $valueKey = 'value', $class = '', $id = '', $isGrouped = false, $type = '')
    {
        $this->name = $name;
        $this->options = $options;
        $this->labelKey = $labelKey;
        $this->valueKey = $valueKey;
        $this->class = $class;
        $this->id = $id;
        $this->isGrouped = $isGrouped;
        $this->type = $type; 

        $this->updateOptions();
    }
    public function updatedSelectedValue($value)
    {
        $this->dispatch('contactSelected', $value); // Emit event with selected value
    }

    public function updateOptions($data = null)
    {
        // Update options based on the event data
        if ($data && isset($data['id']) && $data['id'] === $this->id) {
            $this->options = $data['options'];
        } else {
            // Otherwise, refresh options from the database
            $this->options = $this->fetchOptions();
        }

        // Dispatch an event to refresh the Select2 dropdown
        $this->dispatch('refreshDropdown', [
            'id' => $this->id,
            'options' => $this->options
        ]);
    }

    protected function fetchOptions()
    {
        // Modify the query based on the type
        if ($this->type === 'purchase') {
            return Contacts::where('contact_role', 'Vendor')->get()->map(function ($contact) {
                return [
                    'value' => $contact->id,
                    'name' => $contact->bus_name,
                    'tax_identification_number' => $contact->contact_tin
                ];
            })->toArray();
        } elseif ($this->type === 'sales') {
            return Contacts::where('contact_role', 'Customer')->get()->map(function ($contact) {
                return [
                    'value' => $contact->id,
                    'name' => $contact->bus_name,
                    'tax_identification_number' => $contact->contact_tin
                ];
            })->toArray();
        }

        return [];
    }

    public function render()
    {
        return view('livewire.select-input');
    }
}
