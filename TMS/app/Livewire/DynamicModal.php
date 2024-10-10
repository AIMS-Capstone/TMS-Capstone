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
    public $newContactPurchase = [
        'contact_role' => 'Vendor',
        'contact_type' => '',
        'contact_tin' => '',
        'bus_name' => '',
        'contact_email' => '',
        'contact_phone' => '',
        'contact_address' => '',
        'contact_city' => '',
        'contact_zip' => '',
        'revenue_tax_type' => '',
        'revenue_atc' => '',
        'revenue_chart_accounts' => '',
        'expense_tax_type' => '',
        'expense_atc' => '',
        'expense_chart_accounts' => '',
    ];
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
    public function savePurchase()
    {
        // Validate all fields related to the purchase contact
        $validatedData = $this->validate([
            'newContactPurchase.contact_role' => 'required|string|max:255',
            'newContactPurchase.contact_type' => 'required|string|max:255',
            'newContactPurchase.contact_tin' => 'nullable|string|max:255',
            'newContactPurchase.bus_name' => 'required|string|max:255',
            'newContactPurchase.contact_email' => 'nullable|email|max:255',
            'newContactPurchase.contact_phone' => 'nullable|string|max:255',
            'newContactPurchase.contact_address' => 'nullable|string|max:255',
            'newContactPurchase.contact_city' => 'nullable|string|max:255',
            'newContactPurchase.contact_zip' => 'nullable|string|max:255',
            'newContactPurchase.revenue_tax_type' => 'nullable|string|max:255',
            'newContactPurchase.revenue_atc' => 'nullable|string|max:255',
            'newContactPurchase.revenue_chart_accounts' => 'nullable|string|max:255',
            'newContactPurchase.expense_tax_type' => 'nullable|string|max:255',
            'newContactPurchase.expense_atc' => 'nullable|string|max:255',
            'newContactPurchase.expense_chart_accounts' => 'nullable|string|max:255',
        ]);

        // Create a new contact in the database using the validated data
        $contact = Contacts::create($validatedData['newContactPurchase']);

        // Reset the form fields
        $this->reset('newContactPurchase');
        $this->closeModal();

        // Fetch updated contacts and map them to a dropdown options structure
        $options = Contacts::all()->map(function ($contact) {
            return [
                'value' => $contact->id,
                'name' => $contact->bus_name,
                'tin' => $contact->contact_tin,
            ];
        })->toArray();

        // Emit an event to update the SelectInput component for purchase-specific dropdown
        $this->dispatch('refreshPurchaseDropdown', [
            'options' => $options
        ]);
    }
    public function render()
    {
        return view('livewire.dynamic-modal');
    }
}
