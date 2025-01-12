<?php

namespace App\Http\Controllers;

use App\Models\IndividualBackgroundInformation;
use App\Models\TaxReturn;
use Illuminate\Http\Request;

class BackgroundInformationController extends Controller
{
    /**
     * Show the edit form for the Background Information.
     */
    public function edit($id)
    {
        // Fetch the TaxReturn and either create a new instance or fetch the existing one.
        $taxReturn = TaxReturn::findOrFail($id);
    
        // Fetch or create Individual Background Information
        $backgroundInfo = IndividualBackgroundInformation::firstOrNew(['tax_return_id' => $id]);
    
        return view('background_information.edit', compact('taxReturn', 'backgroundInfo'));
    }
    
    /**
     * Handle the update request for Background Information.
     */
    public function update(Request $request, $id)
    {
        // Validate the input fields
        $validated = $request->validate([
            'date_of_birth' => 'nullable|date',
            'filer_type' => 'nullable|in:single_proprietor,professional,estate,trust',
            'alphanumeric_tax_code' => 'nullable|string',
            'civil_status' => 'nullable|in:single,married',
            'citizenship' => 'nullable|string',
            'foreign_tax' => 'nullable|string',
            'foreign_tax_credits_claimed' => 'nullable|in:yes,no',
        ]);

        // Fetch the TaxReturn to ensure it exists
        $taxReturn = TaxReturn::findOrFail($id);

        // Update or create Individual Background Information
        $individualBackground = IndividualBackgroundInformation::updateOrCreate(
            ['tax_return_id' => $id],
            [
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'filer_type' => $validated['filer_type'] ?? null,
                'alphanumeric_tax_code' => $validated['alphanumeric_tax_code'] ?? null,
                'civil_status' => $validated['civil_status'] ?? null,
                'citizenship' => $validated['citizenship'] ?? null,
                'foreign_tax' => $validated['foreign_tax'] ?? null,
                'foreign_tax_credits_claimed' => $validated['foreign_tax_credits_claimed'] ?? null,
            ]
        );

        // Return a response
        return redirect()->route('income_return.show', ['id' => $id, 'type' => $taxReturn->title])
            ->with('success', 'Background Information updated successfully.');
    }
}