<?php

namespace App\Http\Controllers;

use App\Models\Atc;
use App\Models\Form1601EQ;
use App\Models\Form0619E;
use App\Models\Form1601EQAtcDetail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrgSetup;
use App\Models\TaxRow;
use App\Models\Transactions;
use App\Models\WithHolding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\Activitylog\Models\Activity;

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
        // Validate incoming request
        $validated = $request->validate([
            'year' => 'required|numeric|min:1900|max:' . date('Y'),
            'quarter' => 'required|integer|between:1,4',
            'type' => 'required|string|in:1601EQ',
        ]);

        $organizationId = session('organization_id');

        // Check if organization_id exists in session
        if (!$organizationId) {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'Session expired. Please log in again.']);
        }

        try {
            // Prevent Duplicate Form Generation
            $existingForm = Form1601EQ::where([
                'year' => $validated['year'],
                'quarter' => $validated['quarter'],
                'org_setup_id' => $organizationId,
            ])->first();

            if ($existingForm) {
                return redirect()->route('form1601EQ.preview', ['id' => $existingForm->id])
                    ->with('info', '1601EQ Form for this period already exists. Redirecting to preview.');
            }

            // Check if a withholding record already exists
            $withHolding = WithHolding::where([
                'type' => '1601EQ',
                'quarter' => $validated['quarter'],
                'year' => $validated['year'],
                'organization_id' => $organizationId,
            ])->first();

            if ($withHolding) {
                return redirect()->route('with_holding.1601EQ_Qap', ['id' => $withHolding->id])
                    ->with('info', 'Redirecting to create form for existing withholding record.');
            }

            // Sanitize and Create Withholding Record
            $withHolding = WithHolding::create([
                'type' => e($validated['type']),
                'organization_id' => $organizationId,
                'title' => 'Quarterly Remittance Form of Creditable Income Taxes Withheld (Expanded)',
                'quarter' => $validated['quarter'],
                'year' => $validated['year'],
                'created_by' => Auth::id(),
            ]);

            Log::info("Form 1601EQ generated by user: " . Auth::id(), [
                'withholding_id' => $withHolding->id,
            ]);

            return redirect()->route('with_holding.1601EQ_Qap', ['id' => $withHolding->id])
                ->with('success', 'Withholding Tax Return for 1601EQ has been generated.');
            
        } catch (\Exception $e) {
            Log::error('Error generating 1601EQ Form', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->withErrors(['error' => 'Failed to generate form. Please try again later.']);
        }
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

    // Qap page to
    public function showQap1601EQ(Request $request, $withholdingId)
    {
        // Check if the form already exists
        $existingForm = Form1601EQ::where('withholding_id', $withholdingId)->first();

        if ($existingForm) {
            // Redirect to preview if form already exists
            return redirect()->route('form1601EQ.preview', ['id' => $existingForm->id])
                ->with('info', 'QAP cannot be accessed as Form 1601EQ already exists. Redirecting to preview.');
        }

        // Fetch withholding record
        $withHolding = WithHolding::findOrFail($withholdingId);

        $year = $withHolding->year;
        $quarter = $withHolding->quarter;

        switch ($quarter) {
            case 1:
                $startDate = "$year-01-01";
                $endDate = "$year-03-31";
                break;
            case 2:
                $startDate = "$year-04-01";
                $endDate = "$year-06-30";
                break;
            case 3:
                $startDate = "$year-07-01";
                $endDate = "$year-09-30";
                break;
            case 4:
                $startDate = "$year-10-01";
                $endDate = "$year-12-31";
                break;
            default:
                $startDate = null;
                $endDate = null;
        }

        // Get perPage value from the request, default to 5
        $perPage = $request->input('perPage', 5);

        $taxRows = TaxRow::whereHas('transaction', function ($query) use ($withholdingId) {
            $query->where('withholding_id', $withholdingId)
                ->where('transaction_type', 'Purchase')
                ->where('QAP', 'active');
        })
            ->with([
                'transaction.contactDetails',
                'taxType',
                'atc', 
            ])
            ->paginate($perPage);

        $unassignedTransactions = Transactions::whereNull('withholding_id')
            ->where('transaction_type', 'Purchase')
            ->whereBetween('date', [$startDate, $endDate])
            ->with('contactDetails')
            ->get();

        return view('tax_return.with_holding.1601EQ_qap', [
            'withHolding' => $withHolding,
            'taxRows' => $taxRows,
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

            // Assign transactions to the withholding and mark QAP as active
            Transactions::whereIn('id', $transactionIds)->update([
                'withholding_id' => $withholdingId,
                'QAP' => 'active'
            ]);
                
            $this->populate1601EQDetails($withholdingId);

            return redirect()->route('with_holding.1601EQ_Qap', ['id' => $withholdingId])
                ->with('success', 'Transactions successfully assigned and ATC details populated.');
        } catch (\Exception $e) {
            Log::error('Failed to assign transactions', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Failed to assign transactions.']);
        }
    }

    // Populate ATC details for withholding ID
    public function populate1601EQDetails($withholdingId)
    {
        $taxRows = TaxRow::whereHas('transaction', function ($query) use ($withholdingId) {
                $query->where('withholding_id', $withholdingId)
                    ->where('QAP', 'active');
            })
            ->with('atc')
            ->get();

        $groupedData = $taxRows->groupBy('tax_code')->map(function ($rows) {
            $totalBase = $rows->sum('net_amount');
            $totalWithheld = $rows->sum('atc_amount');
            $taxRate = $rows->first()->atc->tax_rate ?? 0;

            return [
                'tax_code' => $rows->first()->tax_code,
                'tax_base' => $totalBase,
                'tax_rate' => $taxRate,
                'tax_withheld' => $totalWithheld,
            ];
        });

        foreach ($groupedData as $data) {
            $atc = Atc::find($data['tax_code']);

            if (!$atc) {
                Log::warning('ATC not found for tax_code: ' . $data['tax_code']);
                continue;
            }

            Form1601EQAtcDetail::updateOrCreate(
                [
                    'withholding_id' => $withholdingId,
                    'atc_id' => $atc->id,
                ],
                [
                    'tax_base' => $data['tax_base'],
                    'tax_rate' => $data['tax_rate'],
                    'tax_withheld' => $data['tax_withheld'],
                ]
            );
        }
    }

    // Soft Delete (Deactivate QAP Transactions)
    public function deactivateQapTransaction(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'No valid transaction IDs provided'], 400);
        }

        // Check if any of the transactions belong to a withholding with an existing 1601EQ form
        $withholdingIds = Transactions::whereIn('id', $ids)->pluck('withholding_id')->unique();

        $restrictedIds = Form1601EQ::whereIn('withholding_id', $withholdingIds)->pluck('withholding_id')->toArray();

        if (!empty($restrictedIds)) {
            return response()->json(['error' => 'Cannot deactivate transactions. Form 1601EQ already exists for some transactions.'], 403);
        }

        Transactions::whereIn('id', $ids)
            ->update(['QAP' => 'inactive']);

        Log::info('Transactions marked as inactive for QAP: ', ['ids' => $ids]);

        return response()->json(['success' => 'Selected transactions have been marked as inactive.']);
    }

    // Archive QAP Transactions
    public function archiveQAP(Request $request, $withholdingId)
    {

        // Check if the form already exists
        $existingForm = Form1601EQ::where('withholding_id', $withholdingId)->first();

        if ($existingForm) {
            // Redirect to preview if form already exists
            return redirect()->route('form1601EQ.preview', ['id' => $existingForm->id])
                ->with('info', 'QAP cannot be accessed as Form 1601EQ already exists. Redirecting to preview.');
        }

        $withHolding = WithHolding::findOrFail($withholdingId);

        // Get perPage value from the request, default to 5
        $perPage = $request->input('perPage', 5);

        $taxRows = TaxRow::whereHas('transaction', function ($query) use ($withholdingId) {
            $query->where('withholding_id', $withholdingId)
                ->where('transaction_type', 'Purchase')
                ->where('QAP', 'inactive');
        })
            ->with(['transaction.contactDetails', 'taxType', 'atc']) 
            ->paginate($perPage);

        $unassignedTransactions = Transactions::whereNull('withholding_id')
            ->where('transaction_type', 'Purchase')
            ->with('contactDetails')
            ->get();

        return view('tax_return.with_holding.1601EQ_qap_archive', [
            'withHolding' => $withHolding,
            'taxRows' => $taxRows,
            'unassignedTransactions' => $unassignedTransactions,
        ]);
    }

    public function activateQapTransaction(Request $request, $withholdingId)
    {
        // Check if form already exists
        $existingForm = Form1601EQ::where('withholding_id', $withholdingId)->first();

        if ($existingForm) {
            return response()->json(['error' => 'Cannot activate transactions. Form 1601EQ already exists.'], 403);
        }

        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'No valid transaction IDs provided'], 400);
        }

        try {
            Transactions::whereIn('id', $ids)
                ->update([
                    'QAP' => 'active',
                    'withholding_id' => $withholdingId
                ]);

            Log::info('Transactions activated for QAP: ', ['ids' => $ids]);

            return response()->json(['success' => 'Selected transactions have been activated.']);
        } catch (\Exception $e) {
            Log::error('Failed to activate QAP transactions: ', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to activate transactions.'], 500);
        }
    }

    public function destroyQapTransaction(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'No valid transaction IDs provided'], 400);
        }

        Transactions::whereIn('id', $ids)
            ->update(['withholding_id' => null]);

        Log::info('Transactions unassigned from QAP: ', ['ids' => $ids]);

        return response()->json(['success' => 'Selected transactions have been unassigned from QAP.']);
    
    }

    //form page
    public function createForm1601EQ($id)
    {
        $withHolding = WithHolding::where('id', $id)->where('type', '1601EQ')->firstOrFail();

        $existingForm = Form1601EQ::where('withholding_id', $id)->first();

        if ($existingForm) {
            return redirect()->route('form1601EQ.preview', ['id' => $existingForm->id])
                ->with('info', '1601EQ Form already exists. Redirecting to preview.');
        }

        $orgSetup = OrgSetup::findOrFail(session('organization_id'));

        // Use the year from the withholding record or allow the user to specify
        $selectedYear = $withHolding->year ?? now()->year;

        // Get the current quarter (or allow user to select)
        $selectedQuarter = ceil(now()->month / 3);

        // Get months for the selected quarter
        $months = $this->getQuarterMonths($selectedQuarter);

        $remittanceData = Form0619E::whereRaw("LEFT(for_month, 4) = ?", [$selectedYear])
            ->whereIn(DB::raw("RIGHT(for_month, 2)"), [
                str_pad($months[0], 2, '0', STR_PAD_LEFT),
                str_pad($months[1], 2, '0', STR_PAD_LEFT),
            ])
            ->selectRaw("
                SUM(CASE WHEN RIGHT(for_month, 2) = ? THEN net_amount_of_remittance ELSE 0 END) as remittance_1st_month
            ", [str_pad($months[0], 2, '0', STR_PAD_LEFT)])
            ->selectRaw("
                SUM(CASE WHEN RIGHT(for_month, 2) = ? THEN net_amount_of_remittance ELSE 0 END) as remittance_2nd_month
            ", [str_pad($months[1], 2, '0', STR_PAD_LEFT)])
            ->first();

        $atcDetails = Form1601EQAtcDetail::where('withholding_id', $id)
            ->with('atc')   
            ->get();

        $sources = $withHolding->sources()->get();

        return view('tax_return.with_holding.1601EQ_form', compact('withHolding', 'orgSetup', 'sources', 'atcDetails', 'remittanceData'));
    }

    /**
     * Get months corresponding to a specific quarter
     */
    private function getQuarterMonths($quarter)
    {
        switch ($quarter) {
            case 1:
                return [1, 2];  // January, February
            case 2:
                return [4, 5];  // April, May
            case 3:
                return [7, 8];  // July, August
            case 4:
                return [10, 11];  // October, November
            default:
                return [];
        }
    }

    public function storeForm1601EQ(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'year' => [
                    'required',
                    'numeric',
                    'min:1900',
                    'max:' . date('Y'),
                    'digits:4',
                ],
                'quarter' => 'required|in:1,2,3,4',
                'amended_return' => 'required|boolean',
                'any_taxes_withheld' => 'required|boolean',
                'category' => 'required|boolean'    ,

                'remittances_1st_month' => 'nullable|numeric|min:0|max:10000000',
                'remittances_2nd_month' => 'nullable|numeric|min:0|max:10000000',
                'remitted_previous' => 'nullable|numeric|min:0|max:10000000',
                'over_remittance' => 'nullable|numeric|min:0|max:10000000',
                'other_payments' => 'nullable|numeric|min:0|max:10000000',

                'surcharge' => 'nullable|numeric|min:0|max:10000000',
                'interest' => 'nullable|numeric|min:0|max:10000000',
                'compromise' => 'nullable|numeric|min:0|max:10000000',
                'penalties' => 'nullable|numeric|min:0|max:10000000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors()); 
        }

        // Retrieve the withholding record
        $withHolding = WithHolding::findOrFail($id);

        $orgSetupId = $withHolding->organization_id ?? session('organization_id');

        // Prevent duplicate form submission for the same period
        $existingForm = Form1601EQ::where('withholding_id', $id)
            ->where('year', $validated['year'])
            ->where('quarter', $validated['quarter'])
            ->first();

        if ($existingForm) {
            return redirect()->route('form1601EQ.preview', ['id' => $existingForm->id])
                ->with('info', 'Form 1601EQ for this period already exists. Redirecting to preview.');
        }

        // Calculate totals for remittances and taxes
        $totalRemittancesMade = 
            ($validated['remittances_1st_month'] ?? 0) + 
            ($validated['remittances_2nd_month'] ?? 0) + 
            ($validated['remitted_previous'] ?? 0) + 
            ($validated['other_payments'] ?? 0);

        // Sum of taxes withheld from related ATC details
        $totalTaxesWithheld = Form1601EQAtcDetail::where('withholding_id', $id)->sum('tax_withheld');

        // Calculate tax still due and total penalties
        $taxStillDue = max(0, $totalTaxesWithheld - $totalRemittancesMade);
        $overRemittance = max(0, $totalRemittancesMade - $totalTaxesWithheld);

        $totalPenalties = ($validated['surcharge'] ?? 0) + 
                        ($validated['interest'] ?? 0) + 
                        ($validated['compromise'] ?? 0);

        $totalAmountDue = $taxStillDue + $totalPenalties;

        // Create or update the 1601EQ form
        $krazy = Form1601EQ::updateOrCreate(
            ['withholding_id' => $id],
            [
                'org_setup_id' => $orgSetupId,
                'year' => $validated['year'],
                'quarter' => $validated['quarter'],
                'amended_return' => $validated['amended_return'],
                'any_taxes_withheld' => $validated['any_taxes_withheld'],
                'category' => $validated['category'],
                'total_taxes_withheld' => $totalTaxesWithheld,
                'remittances_1st_month' => $validated['remittances_1st_month'] ?? 0,
                'remittances_2nd_month' => $validated['remittances_2nd_month'] ?? 0,
                'total_remittances_made' => $totalRemittancesMade,
                'tax_still_due' => $taxStillDue,
                'total_amount_due' => $totalAmountDue,
                'over_remittance' => $overRemittance,
                'remitted_previous' => $validated['remitted_previous'] ?? 0,
                'other_payments' => $validated['other_payments'] ?? 0,
                'surcharge' => $validated['surcharge'] ?? 0,
                'interest' => $validated['interest'] ?? 0,
                'compromise' => $validated['compromise'] ?? 0,
                'penalties' => $validated['penalties'] ?? 0,
            ]
        );

        return redirect()->route('form1601EQ.preview', ['id' => $krazy->id])
            ->with('success', 'Form 1601EQ submitted successfully.');
    }

    public function previewForm1601EQ($id)
    {
        $form = Form1601EQ::with(['organization', 'atcDetails.atc'])->findOrFail($id);

        return view('tax_return.with_holding.1601EQ_preview', compact('form'));
    }

    public function editForm1601EQ($id)
    {
        $form = Form1601EQ::with(['organization', 'atcDetails.atc'])->findOrFail($id);

        $atcs = Atc::where('transaction_type', 'purchase')
            ->where('tax_code', 'LIKE', 'WI%')
            ->orWhere('tax_code', 'LIKE', 'WC%')
            ->orderBy('tax_code')
            ->get();

        return view('tax_return.with_holding.1601EQ_edit', compact('form', 'atcs'));
    }

    public function updateForm1601EQ(Request $request, $id)
    {
        $form = Form1601EQ::findOrFail($id);

        try {
            // Validate incoming request
            $validated = $request->validate([
                'amended_return' => 'required|boolean',
                'any_taxes_withheld' => 'required|boolean',
                'category' => 'required|boolean',
                'remittances_1st_month' => 'nullable|numeric|min:0',
                'remittances_2nd_month' => 'nullable|numeric|min:0',
                'remitted_previous' => 'nullable|numeric|min:0',
                'over_remittance' => 'nullable|numeric|min:0',
                'other_payments' => 'nullable|numeric|min:0',
                'surcharge' => 'nullable|numeric|min:0',
                'interest' => 'nullable|numeric|min:0',
                'compromise' => 'nullable|numeric|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            dd($e->errors());  
        }

        try {
            // Calculate necessary totals
            $totalRemittancesMade = 
                ($validated['remittances_1st_month'] ?? 0) +
                ($validated['remittances_2nd_month'] ?? 0) +
                ($validated['remitted_previous'] ?? 0) +
                ($validated['over_remittance'] ?? 0) +
                ($validated['other_payments'] ?? 0);

            $totalTaxesWithheld = $form->total_taxes_withheld;

            $taxStillDue = $totalTaxesWithheld - $totalRemittancesMade;

            $totalPenalties = ($validated['surcharge'] ?? 0) + ($validated['interest'] ?? 0) + ($validated['compromise'] ?? 0);
            $totalAmountDue = $taxStillDue + $totalPenalties;

            // Update the form with validated data and calculated totals
            $form->update([
                'amended_return' => $validated['amended_return'],
                'any_taxes_withheld' => $validated['any_taxes_withheld'],
                'category' => $validated['category'],
                'remittances_1st_month' => $validated['remittances_1st_month'] ?? 0,
                'remittances_2nd_month' => $validated['remittances_2nd_month'] ?? 0,
                'remitted_previous' => $validated['remitted_previous'] ?? 0,
                'over_remittance' => $validated['over_remittance'] ?? 0,
                'other_payments' => $validated['other_payments'] ?? 0,
                'surcharge' => $validated['surcharge'] ?? 0,
                'interest' => $validated['interest'] ?? 0,
                'compromise' => $validated['compromise'] ?? 0,
                'total_remittances_made' => $totalRemittancesMade,
                'tax_still_due' => $taxStillDue,
                'total_amount_due' => $totalAmountDue,
                'penalties' => $totalPenalties,
            ]);

            return redirect()->route('form1601EQ.preview', ['id' => $form->id])
                ->with('success', '1601EQ Form updated successfully.');
            
        } catch (\Exception $e) {
            Log::error('Error updating Form 1601EQ', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'form_id' => $id
            ]);
            return redirect()->back()->withErrors(['error' => 'Failed to update form. Please try again.']);
        }
    }

    public function downloadForm1601EQ($id)
    {
        // Fetch the form and related data
        $form = Form1601EQ::with(['organization'])->findOrFail($id);

        // Get organization linked to the form
        $organization = $form->organization;

        // Generate PDF from Blade template
        $pdf = Pdf::loadView('tax_return.with_holding.1601EQ_pdf', compact('form', 'organization'));

        // Customize PDF filename
        $filename = '1601EQ_Form_' . $form->year . '_Q' . $form->quarter . '.pdf';

        // Return the PDF for download
        return $pdf->download($filename);
    }

    private function getWithHoldings($organizationId, $type, $perPage = 5)
    {

        return WithHolding::with(['employee', 'employment', 'creator'])
            ->where('type', $type)
            ->where('organization_id', $organizationId)
            ->paginate($perPage);

    }
}
