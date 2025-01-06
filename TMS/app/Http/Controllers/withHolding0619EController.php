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
use Barryvdh\DomPDF\Facade\Pdf;

class withHolding0619EController extends Controller
{
    //0619E function
    public function index0619E(Request $request)
    {
        $organizationId = session('organization_id');
        $perPage = $request->input('perPage', 5); // Get the perPage value from the request, default to 5
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
            ->first();

            if ($existingRecord) {
                // Check if 0619E form already exists for this withholding
                $existingForm = Form0619E::where('withholding_id', $existingRecord->id)->first();

                if ($existingForm) {
                    return redirect()->route('form0619E.preview', ['id' => $existingForm->id]);
                }

                return redirect()->route('form0619E.create', ['id' => $existingRecord->id])
                    ->with('success', 'Withholding Tax Return for 0619E has been generated.');
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
        $existingForm = Form0619E::where('withholding_id', $id)->first();

        if ($existingForm) {
            // Redirect to preview, but pass withholding ID, not form ID
            return redirect()->route('form0619E.preview', ['id' => $existingForm->withholding_id]);
        }

        $withHolding = WithHolding::findOrFail($id);
        $orgSetup = OrgSetup::find(session('organization_id'));

        if (!$orgSetup) {
            return redirect()->back()->withErrors(['error' => 'Organization setup not found.']);
        }

        $atcs = Atc::where('category', 'Withholding')
            ->orWhere(function ($query) {
                $query->where('tax_code', 'like', 'WI%')
                    ->orWhere('tax_code', 'like', 'WC%');
            })->get();

        $taxTypeCodes = ['WE' => 'Withholding Tax - Expanded'];

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
                'atc_id' => 'required|exists:atcs,id',
                'category' => 'required|boolean',
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
            'atc_id' => $request->atc_id,
            'category' => $request->category,
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

    public function showFormOrPreview($id)
    {
        $withHolding = WithHolding::findOrFail($id);
        $form = Form0619E::where('withholding_id', $id)->first();

        if ($form) {
            // If form exists, redirect to preview
            return view('tax_return.with_holding.0619E_preview', compact('form'));
        }

        // Otherwise, proceed to create form
        return redirect()->route('form0619E.create', ['id' => $withHolding->id]);
    }

    //Edit 0619E form
    public function editForm0619E($id)
    {
        $form = Form0619E::with(['organization', 'withholding'])->findOrFail($id);
        $atcs = Atc::where('tax_code', 'like', 'WI%')
            ->orWhere('tax_code', 'like', 'WC%')
            ->get();
        $taxTypeCodes = ['WE' => 'Withholding Tax - Expanded'];

        return view('tax_return.with_holding.0619E_edit', [
            'form' => $form,
            'atcs' => $atcs,
            'taxTypeCodes' => $taxTypeCodes,
        ]);
    }

    public function updateForm0619E(Request $request, $id)
    {
        $form = Form0619E::findOrFail($id);

        $validated = $request->validate([
            'for_month' => 'required|date_format:Y-m',
            'due_date' => 'required|date',
            'amended_return' => 'required|boolean',
            'tax_code' => 'required|string',
            'atc_id' => 'required|exists:atcs,id',
            'category' => 'required|boolean',
            'any_taxes_withheld' => 'required|boolean',
            'amount_of_remittance' => 'required|numeric|min:0',
            'remitted_previous' => 'nullable|numeric|min:0',
            'surcharge' => 'nullable|numeric|min:0',
            'interest' => 'nullable|numeric|min:0',
            'compromise' => 'nullable|numeric|min:0',
        ]);

        // Calculate penalties and total amount due
        $totalPenalties = ($validated['surcharge'] ?? 0) + ($validated['interest'] ?? 0) + ($validated['compromise'] ?? 0);
        $totalAmountDue = ($validated['amount_of_remittance'] ?? 0) - ($validated['remitted_previous'] ?? 0) + $totalPenalties;

        // Update form details
        $form->update(array_merge($validated, [
            'net_amount_of_remittance' => ($validated['amount_of_remittance'] ?? 0) - ($validated['remitted_previous'] ?? 0),
            'total_penalties' => $totalPenalties,
            'total_amount_due' => $totalAmountDue,
        ]));

        // Redirect to preview using the withholding ID
        return redirect()->route('form0619E.preview', ['id' => $form->withholding_id])
            ->with('success', 'Form 0619E has been successfully updated.');
    }

    public function downloadForm0619E($id)
    {
        // Fetch the form details with related organization and ATC
        $form = Form0619E::with(['atc', 'organization'])->findOrFail($id);

        // Get the related organization from the form
        $organization = $form->organization;

        // Load the PDF view and pass the form and organization data
        $pdf = Pdf::loadView('tax_return.with_holding.0619E_pdf', compact('form', 'organization'));

        // Customize the filename
        $filename = '0619E_Form_' . $form->for_month . '.pdf';

        // Return PDF download response
        return $pdf->download($filename);
    }

    private function getWithHoldings($organizationId, $type)
    {

        return WithHolding::with(['employee', 'employment', 'creator'])
            ->where('type', $type)
            ->where('organization_id', $organizationId)
            ->paginate(5);
            
    }
}
