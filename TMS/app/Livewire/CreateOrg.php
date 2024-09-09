<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\OrgSetup;

class CreateOrg extends Component
{

public $title;
    public $body;
    public $id;
    public $showModal = false;
    public $type;
    
    
    public function render()
    {
        return view('livewire.create-org');
    }

    public function save()
    {
            //  Validate the request data
        $validatedData = $this->validate([
            'type' => 'required|in:non-individual,individual',
            'registration_name' => 'required|string|max:255',
            'line_of_business' => 'required|string|max:255',
            'address_line' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tin' => 'required|string|max:20',
            'rdo' => 'required|string|max:20',
            'tax_type' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'start_date' => 'required|date',
            'financial_year_end' => 'required|date',
        ]);

        $createOrg = OrgSetup::create($validatedData);
    }
}
