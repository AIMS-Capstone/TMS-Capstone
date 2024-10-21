<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\OrgSetup;
use App\Models\rdo;

class CreateOrg extends Component
{

public $title;
    public $body;
    public $id;
    public $showModal = false;
    public $type;
    public $rdos;
    
    
    public function render()
    {
        $rdos = rdo::all();
        return view('livewire.create-org', compact('rdos'));
    }

    public function save()
    {
            //  Validate the request data
        $validatedData = $this->validate([
            'type' => 'required|in:Non-individual,Individual',
            'registration_name' => 'required|string|max:255',
            'line_of_business' => 'required|string|max:255',
            'address_line' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:10',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'tin' => 'required|string|max:20',
            'rdo' => 'required|string|max:20',
            'tax_type' => 'required|string|max:255',
            'registration_date' => 'required|date',
            'start_date' => 'required|date',
            'financial_year_end' => 'required|string|max:255',
        ]);
        
        $createOrg = OrgSetup::create($validatedData);
    }
}
