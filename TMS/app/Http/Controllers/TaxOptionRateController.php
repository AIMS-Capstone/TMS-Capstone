<?php

namespace App\Http\Controllers;

use App\Models\TaxOptionRate;
use App\Models\TaxReturn;
use App\Models\IndividualBackgroundInformation;
use App\Models\SpouseTaxOptionRate; // Assuming SpouseTaxOptionRate model is created
use Illuminate\Http\Request;

class TaxOptionRateController extends Controller
{
    /**
     * Show the edit form for Tax Option Rate.
     */
    public function edit($id)
    {
        // Fetch the TaxReturn
        $taxReturn = TaxReturn::findOrFail($id);
    
        // Attempt to retrieve IndividualBackgroundInformation
        $individualBackground = $taxReturn->individualBackgroundInformation;
    
        if (!$individualBackground) {
            // Stay on the same page and show an error message
            return back()->with('error', 'Individual Background Information not found for this Tax Return. Please ensure it is set up before editing Tax Option Rate.');
        }
    
        // Retrieve or create the TaxOptionRate for the IndividualBackgroundInformation
        $taxOptionRate = TaxOptionRate::firstOrNew(
            ['individual_background_information_id' => $individualBackground->id],
            ['rate_type' => null, 'deduction_method' => null] // Default values for new record
        );
 
        // Check if the filer is married, and fetch spouse’s TaxOptionRate if needed
        $spouseTaxOptionRate = null;

        if ($individualBackground->civil_status === 'married') {
            // Retrieve the SpouseInformation related to the individual background
            $spouseInformation = $individualBackground->spouseInformation;
        
            // If there is SpouseInformation, retrieve or create the SpouseTaxOptionRate
            if ($spouseInformation) {
                $spouseTaxOptionRate = SpouseTaxOptionRate::firstOrNew(
                    ['spouse_information_id' => $spouseInformation->id], // Use spouse_information_id instead of individual_background_information_id
                    ['rate_type' => null, 'deduction_method' => null] // Default values for spouse if new record
                );
            }
        }
        
    
        return view('tax_option_rate.edit', compact('taxReturn', 'individualBackground', 'taxOptionRate', 'spouseTaxOptionRate'));
    }
    

    /**
     * Handle the update request for Tax Option Rate.
     */
    public function update(Request $request, $id)
    {
        // Fetch the TaxReturn and related IndividualBackgroundInformation
        $taxReturn = TaxReturn::findOrFail($id);
        $individualBackground = $taxReturn->individualBackgroundInformation;

        if (!$individualBackground) {
            abort(404, 'Individual Background Information not found for this Tax Return.');
        }

        // Validate the input
        $validated = $request->validate([
            'rate_type' => 'required|in:8_percent,graduated_rates',
            'deduction_method' => 'nullable|in:itemized,osd', // Validate deduction_method if provided
            'spouse_rate_type' => 'nullable|in:8_percent,graduated_rates', // Spouse rate type validation
            'spouse_deduction_method' => 'nullable|in:itemized,osd', // Spouse deduction method validation
        ]);
  
        // Update or create the TaxOptionRate for the IndividualBackgroundInformation
        TaxOptionRate::updateOrCreate(
            ['individual_background_information_id' => $individualBackground->id],
            [
                'rate_type' => $validated['rate_type'],
                'deduction_method' => $validated['deduction_method'] ?? null,
            ]
        );
        $spouseInformation = $individualBackground->spouseInformation;
        // If the filer is married, update or create the spouse's TaxOptionRate
        if ($individualBackground->civil_status === 'married' && $spouseInformation) {
            // Update or create the SpouseTaxOptionRate for the spouse's information
            SpouseTaxOptionRate::updateOrCreate(
                ['spouse_information_id' => $spouseInformation->id], // Foreign key referencing SpouseInformation
                [
                    'rate_type' => $validated['spouse_rate_type'] ?? null, // Rate type for the spouse
                    'deduction_method' => $validated['spouse_deduction_method'] ?? null, // Deduction method for the spouse
                ]
            );
        }

        // Redirect back with a success message
        return redirect()->route('income_return.show', ['id' => $id, 'type' => $taxReturn->title])
            ->with('success', 'Tax Option Rate updated successfully.');
    }
}
