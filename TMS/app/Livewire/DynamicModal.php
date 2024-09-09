<?php
namespace App\Livewire;

use Livewire\Component;
use App\Models\Contacts;

class DynamicModal extends Component
{
    public $title;
    public $body;
    public $id;
    public $showModal = false;
    public $newContact = [
        'contact_type' => '',
        'contact_tin' => '',
        'bus_name' => '',
        'contact_address' => '',
        'contact_city' => '',
        'contact_zip' => '',
    ];

    protected $listeners = ['triggerModal' => 'openModal'];

    public function openModal($title, $body)
    {
        $this->title = $title;
        $this->body = $body;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    public function save()
    {
        // Validate and create the new contact
        $validatedData = $this->validate([
            'newContact.bus_name' => 'required|string|max:255',
            'newContact.contact_tin' => 'nullable|string|max:255',
            'newContact.contact_type' => 'nullable|string|max:255',
            'newContact.contact_address' => 'nullable|string|max:255',
            'newContact.contact_city' => 'nullable|string|max:255',
            'newContact.contact_zip' => 'nullable|string|max:255',
        ]);

        $contact = Contacts::create($validatedData['newContact']);

        // Reset the form
        $this->reset('newContact');
        $this->closeModal();

        // Fetch updated contacts
        $options = Contacts::all()->map(function ($contact) {
            return [
                'value' => $contact->id,
                'name' => $contact->bus_name,
                'tin' => $contact->contact_tin,
            ];
        })->toArray();

        // Emit an event to update the SelectInput component
        $this->dispatch('refreshDropdown', [
            'options' => $options
        ]);
    }

    public function render()
    {
        return view('livewire.dynamic-modal');
    }
}
