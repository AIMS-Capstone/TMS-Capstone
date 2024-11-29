<?php
namespace App\Http\Controllers;

use App\Models\IndividualBackgroundInformation;
use App\Models\SpouseInformation;
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
    
        // Fetch Spouse Information if the individual is married
        $spouseInfo = null;
        if ($backgroundInfo->civil_status === 'married') {
            $spouseInfo = SpouseInformation::firstOrNew(['individual_background_information_id' => $backgroundInfo->id]);
        }
    
        return view('background_information.edit', compact('taxReturn', 'backgroundInfo', 'spouseInfo'));
    }
    
    
    /**
     * Handle the update request for Background Information.
     */
    public function update(Request $request, $id)
{
    // Validate the input fields for both individual and spouse information
    $validated = $request->validate([
        'date_of_birth' => 'nullable|date',
        'filer_type' => 'nullable|in:single_proprietor,professional,estate,trust',
        'alphanumeric_tax_code' => 'nullable|string',
        'civil_status' => 'nullable|in:single,married',
        'citizenship' => 'nullable|string',
        'foreign_tax' => 'nullable|string',
        'foreign_tax_credits_claimed' => 'nullable|in:yes,no',
        // Spouse information
        'spouse_employment_status' => 'nullable|in:employed,unemployed',
        'spouse_name' => [
        'nullable',
        'string',
        function ($attribute, $value, $fail) {
            $parts = explode(',', $value);
            if (count($parts) !== 3) {
                $fail('The ' . $attribute . ' must be in the format: Last Name, First Name, Middle Name.');
            }
        },
    ],
        'spouse_tin' => 'nullable|string',
        'spouse_rdo' => 'nullable|string',
        
        'spouse_type' => 'nullable|in:single_proprietor,professional,compensation_owner',
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

    // If civil status is married, update spouse information
    if ($validated['civil_status'] === 'married') {
        // Split the spouse_name into parts
        $spouseNameParts = array_map('trim', explode(',', $validated['spouse_name'] ?? ''));

        // Ensure we have all three parts or handle missing parts gracefully
        $spouseLastName = $spouseNameParts[0] ?? null;
        $spouseFirstName = $spouseNameParts[1] ?? null;
        $spouseMiddleName = $spouseNameParts[2] ?? null;

        // Update or create Spouse Information using `individual_background_information_id`
        SpouseInformation::updateOrCreate(
            ['individual_background_information_id' => $individualBackground->id],
            [
                'employment_status' => $validated['spouse_employment_status'] ?? null,
                'tin' => $validated['spouse_tin'] ?? null,
                'rdo' => $validated['spouse_rdo'] ?? null,
                'last_name' => $spouseLastName,
                'first_name' => $spouseFirstName,
                'middle_name' => $spouseMiddleName,
                'filer_type' => $validated['spouse_type'] ?? null,
            ]
        );
    } else {
        // If not married, ensure no spouse information is stored (delete if exists)
        SpouseInformation::where('individual_background_information_id', $individualBackground->id)->delete();
    }

    // Return a response (e.g., redirect or a success message)
    return redirect()->route('income_return.show', ['id' => $id, 'type' => $taxReturn->title])
    ->with('success', 'Background Information updated successfully.');
}

    
    
}
