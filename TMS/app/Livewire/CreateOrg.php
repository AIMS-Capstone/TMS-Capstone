<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\OrgSetup;
use App\Models\Rdo;

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
        // Retrieve the start date
        $startDate = $this->input('start_date');
    
        // If the start date is in YYYY-MM format (e.g., '2024-06'), append '-01' to make it a full date (YYYY-MM-DD)
        if (strlen($startDate) == 7) {  // Format like '2024-06'
            $startDate .= '-01';  // Append the first day of the month to make it a valid date
        }
    
        // Validate the request data
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
            'start_date' => 'required|date',  // Now it's guaranteed to be in full date format (YYYY-MM-DD)
            'financial_year_end' => 'required|string|max:255',
        ]);
    
        // Ensure the start date is formatted as 'YYYY-MM-DD' (in case it's 'YYYY-MM')
        $validatedData['start_date'] = $startDate; // Update the start_date to the correct format
    
        // Create the organization setup
        $createOrg = OrgSetup::create($validatedData);
    }
    
}
