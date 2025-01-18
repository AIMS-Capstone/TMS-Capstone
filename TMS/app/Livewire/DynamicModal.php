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
        'contact_classification' => 'Customer',
        'organization_id' => '',
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
        $this->newContact['organization_id'] = session('organization_id'); 
        $this->newContact['contact_type'] = 'Customer';
        // Validate and create the new contact
        $validatedData = $this->validate([
            'newContact.bus_name' => 'required|string|max:255',
            'newContact.contact_tin' => 'nullable|string|max:255',
            'newContact.contact_type' => 'nullable|string|max:255',
            'newContact.contact_address' => 'nullable|string|max:255',
            'newContact.contact_city' => 'nullable|string|max:255',
            'newContact.contact_zip' => 'nullable|string|max:255',
            'newContact.organization_id' => 'required|integer',
            'newContact.classification' => 'required|string',
        ]);


        $contact = Contacts::create($validatedData['newContact']);

        // Reset the form
        $this->reset('newContact');
        $this->closeModal();
        
        // Fetch updated contacts
        $options = Contacts::select('id', 'bus_name', 'contact_tin')
        ->where('contact_type', 'Customer')
        ->where('organization_id', session('organization_id'))
        ->orderBy('bus_name')
        ->get()
        ->map(function ($contact) {
            return [
                'value' => $contact->id,
                'name' => $contact->bus_name,
                'tin' => $contact->contact_tin ?? 'N/A',
            ];
        })
        ->toArray();

        // Emit an event to update the SelectInput component
        $this->dispatch('refreshDropdown', [
            'options' => $options
        ]);
        $this->dispatch('show-toast', [
            'message' => 'Contact added successfully',
            'type' => 'success'
        ]);
    }
    public function savePurchase()
    {
        try {
            // Validate the purchase contact data
            $validatedData = $this->validate([
                'newContactPurchase.classification' => 'required|string|max:255',
                'newContactPurchase.contact_tin' => [
                    'nullable',
                    'string',
                    'max:255',
                    'regex:/^\d{3}-\d{3}-\d{3}-\d{3}$|^\d{12}$/' // TIN format validation
                ],
                'newContactPurchase.bus_name' => 'required|string|max:255|unique:contacts,bus_name',
                'newContactPurchase.contact_address' => 'nullable|string|max:255',
                'newContactPurchase.contact_city' => 'nullable|string|max:255',
                'newContactPurchase.contact_zip' => [
                    'nullable',
                    'string',
                    'max:10',
                    'regex:/^\d{4}$/' // ZIP code format validation
                ],
            ], [
                'newContactPurchase.bus_name.unique' => 'This business name is already registered.',
                'newContactPurchase.contact_tin.regex' => 'The TIN must be in XXX-XXX-XXX-XXX format or 12 consecutive digits.',
                'newContactPurchase.contact_zip.regex' => 'The ZIP code must be 4 digits.',
            ]);
    
            // Prepare contact data with organization ID and contact type
            $contactData = array_merge($validatedData['newContactPurchase'], [
                'organization_id' => session('organization_id'),
                'contact_type' => 'Vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    
            // Create new contact
            $contact = Contacts::create($contactData);
    
            if (!$contact) {
                throw new \Exception('Failed to create contact');
            }
    
            // Reset form and close modal
            $this->reset('newContactPurchase');
            $this->closeModal();
    
            // Fetch updated contacts with proper eager loading if needed
            $options = Contacts::select('id', 'bus_name', 'contact_tin')
                ->where('contact_type', 'Vendor')
                ->where('organization_id', session('organization_id'))
                ->orderBy('bus_name')
                ->get()
                ->map(function ($contact) {
                    return [
                        'value' => $contact->id,
                        'name' => $contact->bus_name,
                        'tin' => $contact->contact_tin ?? 'N/A',
                    ];
                })
                ->toArray();
    
            // Dispatch event to update dropdown
            $this->dispatch('refreshPurchaseDropdown', [
                'options' => $options
            ]);
            $this->dispatch('show-toast', [
                'message' => 'Contact added successfully',
                'type' => 'success'
            ]);
    
            // Show success message
            session()->flash('message', 'Vendor successfully added.');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'Please check the form for errors.'
            ]);
            throw $e;
        } catch (\Exception $e) {
            // Handle other exceptions
            logger()->error('Error saving purchase contact: ' . $e->getMessage());
            $this->dispatch('showAlert', [
                'type' => 'error',
                'message' => 'An error occurred while saving the vendor.'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.dynamic-modal');
    }
}
