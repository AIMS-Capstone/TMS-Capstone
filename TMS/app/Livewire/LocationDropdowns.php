<?php
namespace App\Livewire;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class LocationDropdowns extends Component
{
    public $regions = [];
    public $provinces = [];
    public $cities = [];
    
    public $selectedRegion = '';
    public $selectedProvince = '';
    public $selectedCity = '';
    public $zipCode = '';

    public function mount()
    {
        $this->regions = json_decode(file_get_contents(public_path('json/regions.json')), true);
    }

    #[On('selectedRegion')] 
    public function selectedRegion($value)
    {
        $this->selectedRegion = $value;
        
        $this->provinces = [];
        $this->selectedProvince = '';
        $this->cities = [];
        $this->selectedCity = '';
        $this->zipCode = '';
        
        if ($value) {
            $allProvinces = json_decode(file_get_contents(public_path('json/provinces.json')), true);
            $this->provinces = collect($allProvinces)
                ->where('region', $value)
                ->all();

            $this->dispatch('region-selected', $value);
            // Only reinit if we have new data
            if (!empty($this->provinces)) {
                $this->dispatch('reinitSelect2');
            }
        }
    }

    #[On('selectedProvince')] 
    public function selectedProvince($value)
    {
        $this->selectedProvince = $value;
        
        $this->cities = [];
        $this->selectedCity = '';
        $this->zipCode = '';
        
        if ($value) {
            $allMunicipalities = json_decode(file_get_contents(public_path('json/municipalities.json')), true);
            $this->cities = collect($allMunicipalities)
                ->where('province', $value)
                ->all();
                
            $this->dispatch('province-selected', $value);
            // Only reinit if we have new data
            if (!empty($this->cities)) {
                $this->dispatch('reinitSelect2');
            }
        }
    }
    #[On('selectedCity')] 
    public function selectedCity($value)
    {
        $this->selectedCity = $value;
        
        if ($value) {
            $allMunicipalities = json_decode(file_get_contents(public_path('json/municipalities.json')), true);
            $selectedCity = collect($allMunicipalities)
                ->first(function($city) use ($value) {
                    return $city['name'] === $value;
                });
            
            $this->zipCode = $selectedCity['zip_code'] ?? '';
            $this->dispatch('city-selected', $value);
            $this->dispatch('zip-selected', $this->zipCode);
            if (!empty($this->selectedCity)) {
                $this->dispatch('reinitSelect2');
            }
          
        }
    }

    public function updatedZipCode($value)
    {
        // Dispatch an event for the zip code
        $this->dispatch('location-zip-changed', $value);
    }
    public function render()
    {
        return view('livewire.location-dropdowns');
    }
}