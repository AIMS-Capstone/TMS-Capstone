<?php

namespace App\Http\Controllers;

use App\Models\atc;
use App\Models\Form1601EQ;
use App\Models\OrgSetup;
use App\Models\Transactions;
use App\Models\WithHolding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class withHolding1601EQController extends Controller
{
    // 1601EQ function
    public function index1601EQ(Request $request)
    {
        $organizationId = session('organization_id');

        // Get the perPage value from the request, default to 5
        $perPage = $request->input('perPage', 5);
        $with_holdings = $this->getWithHoldings($organizationId, '1601EQ', $perPage);
        return view('tax_return.with_holding.1601EQ', compact('with_holdings'));
    }

    public function generate1601EQ(Request $request)
    {
        // Validate the input
        $request->validate([
            'year' => 'required|numeric|min:1900|max:' . date('Y'),
            'quarter' => 'required|numeric|min:1|max:4',
            'type' => 'required|string|in:1601EQ',
        ]);

        $organizationId = session('organization_id');

        // Calculate the months covered by the quarter
        $monthsInQuarter = [
            1 => [1, 2, 3], // Q1
            2 => [4, 5, 6], // Q2
            3 => [7, 8, 9], // Q3
            4 => [10, 11, 12], // Q4
        ];

        // Get the months for the selected quarter
        $selectedMonths = $monthsInQuarter[$request->quarter];

        // Check if records for the quarter already exist
        $existingRecord = WithHolding::where('type', '1601EQ')
            ->whereIn('month', $selectedMonths)
            ->where('year', $request->year)
            ->where('organization_id', $organizationId)
            ->exists();

        if ($existingRecord) {
            return redirect()->back()->withErrors(['error' => 'A record for this quarter and year already exists.']);
        }

        // Create the record
        $withHolding = WithHolding::create([
            'type' => $request->type,
            'organization_id' => $organizationId,
            'title' => 'Quarterly Remittance Form of Creditable Income Taxes Withheld (Expanded)',
            'quarter' => $request->quarter,
            'year' => $request->year,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('with_holding.1601EQ', ['id' => $withHolding->id])
            ->with('success', 'Withholding Tax Return for 1601EQ has been generated.');
    }

    public function destroy1601EQ(Request $request)
    {
        $ids = $request->input('ids'); // This will be an array of IDs sent from JavaScript

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

    //Qap page
    public function showQap1601EQ($withholdingId)
    {
        $withHolding = WithHolding::findOrFail($withholdingId);

        $transactions = Transactions::where('withholding_id', $withholdingId)
            ->where('transaction_type', 'Purchase')
            ->with(['contactDetails', 'withholding'])
            ->paginate(5);

        $unassignedTransactions = Transactions::whereNull('withholding_id')
            ->where('transaction_type', 'Purchase')
            ->get();

        return view('tax_return.with_holding.1601EQ_qap', [
            'withHolding' => $withHolding,
            'transactions' => $transactions,
            'unassignedTransactions' => $unassignedTransactions,
        ]);
    }

    // Set the QAP transactions
    public function setQap1601EQ(Request $request, $withholdingId)
    {
        $request->validate([
            'transactions' => 'required|array|min:1',
            'transactions.*.transaction_id' => 'exists:transactions,id',
        ]);

        try {
            $transactionIds = collect($request->transactions)->pluck('transaction_id');

            Transactions::whereIn('id', $transactionIds)->update(['withholding_id' => $withholdingId]);

            return redirect()->route('with_holding.1601EQ_Qap', ['id' => $withholdingId])
                ->with('success', 'Transactions successfully assigned.');
        } catch (\Exception $e) {
            Log::error('Failed to assign transactions', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to assign transactions.']);
        }
    }

    //form page
    public function createForm1601EQ($id)
    {
        // Fetch the withholding tax record and ensure it is type 1601EQ
        $withHolding = WithHolding::where('id', $id)->where('type', '1601EQ')->firstOrFail();

        // Retrieve organization setup and related data
        $orgSetup = OrgSetup::findOrFail(session('organization_id'));

        // Retrieve necessary data for the form
        $atcs = Atc::all(); // Fetch all Alphanumeric Tax Codes
        $sources = $withHolding->sources()->get(); // Sources related to the withholding record

        // Pass data to the blade template
        return view('tax_return.with_holding.1601EQ_form', compact('withHolding', 'orgSetup', 'atcs', 'sources'));
    }

    public function storeForm1601EQ(Request $request, $id)
    {

        try {

            $request->validate([
                'year' => 'required|numeric|min:1900|max:' . date('Y'),
                'quarter' => 'required|in:1,2,3,4',
                'amended_return' => 'required|boolean',
                'any_taxes_withheld' => 'required|boolean',
                'atc.*' => 'nullable|exists:atcs,id', // Validate ATC IDs
                'tax_base.*' => 'nullable|numeric|min:0', // Validate Tax Base
                'tax_rate.*' => 'nullable|numeric|min:0|max:100', // Validate Tax Rate
                'tax_withheld.*' => 'nullable|numeric|min:0', // Validate Tax Withheld
                'remittances_1st_month' => 'nullable|numeric|min:0',
                'remittances_2nd_month' => 'nullable|numeric|min:0',
                'remitted_previous' => 'nullable|numeric|min:0',
                'over_remittance' => 'nullable|numeric|min:0',
                'surcharge' => 'nullable|numeric|min:0',
                'interest' => 'nullable|numeric|min:0',
                'compromise' => 'nullable|numeric|min:0',
                'penalties' => 'nullable|numeric|min:0',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation errors:', $e->errors());
            return redirect()->back()->withErrors($e->errors());
        }

        $withHolding = WithHolding::findOrFail($id);

        $orgSetupId = $withHolding->organization_id ?? session('organization_id');
        if (!$orgSetupId) {
            return redirect()->back()->withErrors(['error' => 'Organization setup ID not found.']);
        }

        $totalTaxesWithheld = $request->total_taxes_withheld ?? array_sum($request->tax_withheld ?? []);

        $remittances1stMonth = $request->remittances_1st_month ?? 0;
        $remittances2ndMonth = $request->remittances_2nd_month ?? 0;
        $remittedPrevious = $request->remitted_previous ?? 0;
        $overRemittance = $request->over_remittance ?? 0;
        $otherPayments = $request->other_payments ?? 0;

        $totalRemittancesMade = $remittances1stMonth + $remittances2ndMonth + $remittedPrevious + $overRemittance + $otherPayments;
        $taxStillDue = $totalTaxesWithheld - $totalRemittancesMade;

        $surcharge = $request->surcharge ?? 0;
        $interest = $request->interest ?? 0;
        $compromise = $request->compromise ?? 0;
        $totalPenalties = $surcharge + $interest + $compromise;

        $totalAmountDue = $taxStillDue + $totalPenalties;
        // Save the form

        try {
            $form1601EQ = Form1601EQ::updateOrCreate(
                ['withholding_id' => $id],
                [
                    'org_setup_id' => $withHolding->organization_id,
                    'year' => $request->year,
                    'quarter' => $request->quarter,
                    'amended_return' => $request->amended_return,
                    'any_taxes_withheld' => $request->any_taxes_withheld,
                    'total_taxes_withheld' => $totalTaxesWithheld,
                    'remittances_1st_month' => $remittances1stMonth,
                    'remittances_2nd_month' => $remittances2ndMonth,
                    'remitted_previous' => $remittedPrevious,
                    'over_remittance' => $overRemittance,
                    'other_payments' => $otherPayments,
                    'total_remittances_made' => $totalRemittancesMade,
                    'tax_still_due' => $taxStillDue,
                    'surcharge' => $surcharge,
                    'interest' => $interest,
                    'compromise' => $compromise,
                    'penalties' => $totalPenalties,
                    'total_amount_due' => $totalAmountDue,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Exception occurred:', ['message' => $e->getMessage()]);
            dd('Exception:', $e->getMessage());
        }

        // Save dynamic ATC rows
        foreach ($request->atc as $index => $atcId) {
            if ($atcId) {
                $form1601EQ->atcDetails()->updateOrCreate(
                    ['atc_id' => $atcId],
                    [
                        'tax_base' => $request->tax_base[$index] ?? 0,
                        'tax_rate' => $request->tax_rate[$index] ?? 0,
                        'tax_withheld' => $request->tax_withheld[$index] ?? 0,
                    ]
                );
            }
        }

        return redirect()->route('with_holding.1601EQ')->with('success', 'Form 1601EQ submitted successfully.');
    }

    private function getWithHoldings($organizationId, $type, $perPage = 5)
    {

        return WithHolding::with(['employee', 'employment', 'creator'])
            ->where('type', $type)
            ->where('organization_id', $organizationId)
            ->paginate($perPage);

    }
}
