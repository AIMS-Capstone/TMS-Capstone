<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrgSetup;
use App\Models\WithHolding;
use App\Models\Source;
use App\Models\Employee;
use App\Models\Employment;
use App\Models\Form1601C;
use App\Models\Form1601EQ;
use App\Models\Form0619E;
use App\Models\atc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class withHolding0619EController extends Controller
{
    //0619E function
    public function index0619E(Request $request)
    {
        $organizationId = session('organization_id');

        // Get the perPage value from the request, default to 5
        $perPage = $request->input('perPage', 5);

        $with_holdings = $this->getWithHoldings($organizationId, '0619E', $perPage);
        return view('tax_return.with_holding.0619E', compact('with_holdings'));
    }

    public function generate0619E(Request $request)
    {
        // Validate the input
        $request->validate([
            'year' => 'required|numeric|min:1900|max:' . date('Y'),
            'month' => 'required|numeric|min:1|max:12',
            'type' => 'required|string|in:0619E', 
        ]);

        $organizationId = session('organization_id');

        // Check if the record for the given month and year already exists
        $existingRecord = WithHolding::where('type', '0619E')
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->where('organization_id', $organizationId)
            ->exists();

        if ($existingRecord) {
            return redirect()->back()->withErrors(['error' => 'A record for this month and year already exists.']);
        }

        // Create the record
        $withHolding = WithHolding::create([
            'type' => $request->type,
            'organization_id' => $organizationId,
            'title' => 'Monthly Remittance Form of Creditable Income Taxes Withheld (Expanded)',
            'month' => $request->month,
            'year' => $request->year,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('form0619E.create', ['id' => $withHolding->id])
            ->with('success', 'Withholding Tax Return for 0619E has been generated.');
    }

    public function destroy0619E(Request $request)
    {
        $ids = $request->input('ids');  // This will be an array of IDs sent from JavaScript

        // Validate that IDs are provided and valid
        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'No valid IDs provided'], 400);
        }

        // Fetch the WithHolding records
        $withHoldings = WithHolding::whereIn('id', $ids)->get();

        // Log the incoming IDs for debugging
        Log::info('Received WithHolding IDs for soft deletion: ', $ids);

        try {

            foreach ($withHoldings as $withHolding) {
                // Set the 'deleted_by' field to the currently authenticated user
                $withHolding->deleted_by = Auth::id();
                $withHolding->save(); // Save the updated model
            }

            // Perform the soft delete on WithHoldings
            WithHolding::whereIn('id', $ids)->delete();

            // Log each deletion activity
            foreach ($withHoldings as $withHolding) {
                activity('withholdings')
                    ->performedOn($withHolding)
                    ->causedBy(Auth::user())
                    ->log("WithHolding ID {$withHolding->id} was soft deleted");
            }

            // Return success response
            return response()->json(['message' => 'WithHolding soft deleted successfully.'], 200);

        } catch (\Exception $e) {
            // Log any errors that occurred during deletion
            Log::error('Error during soft deletion of WithHoldings: ' . $e->getMessage());

            // Return error response
            return response()->json(['error' => 'An error occurred while deleting records'], 500);
        }
    }

    public function createForm0619E($id)
    {
        // Fetch the withholding tax record and ensure it is type 0619E
        $withHolding = WithHolding::findOrFail($id);

        // Retrieve organization setup and related data
        $orgSetup = OrgSetup::find(session('organization_id'));

        if (!$orgSetup) {
            return redirect()->back()->withErrors(['error' => 'Organization setup not found.']);
        }

        $atcs = Atc::all(); 
        $taxTypeCodes = ['WC' => 'Withholding Tax - Creditable/Expanded']; 

        // Pass data to the Blade template
        return view('tax_return.with_holding.0619E_form', [
            'withHolding' => $withHolding,
            'orgSetup' => $orgSetup,
            'atcs' => $atcs,
            'taxTypeCodes' => $taxTypeCodes,
        ]);
    }

    public function storeForm0619E(Request $request, $id)
{
    // Log the beginning of the method
    Log::info("Initiating 0619E form submission", ['withholding_id' => $id]);

    // Log incoming data
    Log::info("Incoming Request Data", $request->all());

    try {
        $request->validate([
            'for_month' => 'required|date_format:Y-m',
            'due_date' => 'required|date',
            'amended_return' => 'required|boolean',
            'tax_code' => 'required|string',
            'any_taxes_withheld' => 'required|boolean',
            'amount_of_remittance' => 'required|numeric|min:0',
            'remitted_previous' => 'nullable|numeric|min:0',
            'surcharge' => 'nullable|numeric|min:0',
            'interest' => 'nullable|numeric|min:0',
            'compromise' => 'nullable|numeric|min:0',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error("Validation failed", [
            'errors' => $e->errors()
        ]);
        return redirect()->back()->withErrors($e->errors())->withInput();
    }

    // Fetch the withholding record
    $withHolding = WithHolding::findOrFail($id);
    Log::info("Fetched withholding record", ['organization_id' => $withHolding->organization_id]);

    // Calculate penalties and total amount due
    $totalPenalties = ($request->surcharge ?? 0) + ($request->interest ?? 0) + ($request->compromise ?? 0);
    $totalAmountDue = ($request->amount_of_remittance ?? 0) - ($request->remitted_previous ?? 0) + $totalPenalties;

    Log::info("Calculated totals", [
        'totalPenalties' => $totalPenalties,
        'totalAmountDue' => $totalAmountDue
    ]);

    // Create the form record
    $form = Form0619E::create([
        'org_setup_id' => $withHolding->organization_id,
        'withholding_id' => $id,
        'for_month' => $request->for_month,
        'due_date' => $request->due_date,
        'amended_return' => $request->amended_return,
        'tax_code' => $request->tax_code,
        'any_taxes_withheld' => $request->any_taxes_withheld,
        'amount_of_remittance' => $request->amount_of_remittance,
        'remitted_previous' => $request->remitted_previous,
        'net_amount_of_remittance' => ($request->amount_of_remittance ?? 0) - ($request->remitted_previous ?? 0),
        'surcharge' => $request->surcharge,
        'interest' => $request->interest,
        'compromise' => $request->compromise,
        'total_penalties' => $totalPenalties,
        'total_amount_due' => $totalAmountDue,
    ]);

    Log::info("0619E Form created successfully", [
        'form_id' => $form->id,
        'form_data' => $form->toArray()
    ]);

    // Redirect back with a success message
    return redirect()->route('with_holding.0619E')
        ->with('success', '0619E Form has been successfully submitted.');
}



    private function getWithHoldings($organizationId, $type, $perPage = 5)
    {

        return WithHolding::with(['employee', 'employment', 'creator'])
            ->where('type', $type)
            ->where('organization_id', $organizationId)
            ->paginate($perPage);
            
    }
}
