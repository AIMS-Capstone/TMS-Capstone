<?php
namespace App\Livewire;

use App\Models\Contacts;
use Livewire\Component;

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

    protected $rules = [
        'selectedValue' => 'required',
    ];
    public function mount($name, $options = [], $labelKey = 'name', $valueKey = 'value', $class = '', $id = '', $isGrouped = false, $type = '', $selectedValue = '')
    {
        $this->name = $name;
        $this->options = $options;
        $this->labelKey = $labelKey;
        $this->valueKey = $valueKey;
        $this->class = $class;
        $this->id = $id;
        $this->isGrouped = $isGrouped;
        $this->type = $type;
        $this->selectedValue = $selectedValue ?: 'default'; // Default to "default" if not set

        $this->updateOptions();
    }
    public function updatedSelectedValue($value)
    {
        $this->dispatch('contactSelected', $value); // Emit event with selected value
    }
    public function updated($field)
    {
        $this->validateOnly($field);
        $this->dispatch('contactSelected', $this->selectedValue);
    }

    protected $messages = [
        'selectedValue.required' => 'Please select a contact.',
    ];

  public function updateOptions($data = null)
{
    $organizationId = session()->get('organization_id'); // Retrieve organization_id from session

    if ($data && isset($data['id']) && $data['id'] === $this->id) {
        $this->options = $data['options'];
    } else {
        $this->options = $this->fetchOptions($organizationId); // Pass organization_id to fetchOptions
    }

    // Ensure the selected value is still valid
    if (!collect($this->options)->pluck($this->valueKey)->contains($this->selectedValue)) {
        $this->selectedValue = 'default'; // Reset to default if invalid
    }

    // Dispatch event to refresh Select2 dropdown
    $this->dispatch('refreshDropdown', [
        'id' => $this->id,
        'options' => $this->options,
    ]);
}


    protected function fetchOptions($organizationId)
    {
        return Contacts::where('organization_id', $organizationId)->get()->map(function ($contact) {
            return [
                'value' => $contact->id,
                'name' => $contact->bus_name,
                'tax_identification_number' => $contact->contact_tin,
            ];
        })->toArray();
    }
    

    public function render()
    {
        return view('livewire.select-input');
    }
}
