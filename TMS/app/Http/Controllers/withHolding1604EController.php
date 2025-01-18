<?php

namespace App\Http\Controllers;

use App\Models\Atc;
use App\Models\Contacts;
use App\Models\Form1601EQ;
use App\Models\Form1604E;
use App\Models\OrgSetup;
use App\Models\Transactions;    
use App\Models\WithHolding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class withHolding1604EController extends Controller
{
    public function index1604E(Request $request)
    {
        $organizationId = session('organization_id');
        $perPage = $request->input('perPage', 5);

        // Start the query with the organization filter and type
        $query = WithHolding::where('type', '1604E')
            ->where('organization_id', $organizationId); // Filter by organization ID

        if ($request->has('search') && $request->search != '') {
            $search = trim($request->search); // Trim whitespace from the search term

            // Add search conditions
            $query->where(function ($q) use ($search) {
                $q->where('year', 'LIKE', '%' . $search . '%');
            })->orWhereHas('creator', function ($q) use ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%');
            });
        }

        // Paginate the results
        $with_holdings = $query->paginate($perPage);

        return view('tax_return.with_holding.1604E', compact('with_holdings'));
    }

    public function generate1604E(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|numeric|min:1900|max:' . date('Y'),
            'type' => 'required|string|in:1604E',
        ]);

        $organizationId = session('organization_id');

        if (!$organizationId) {
            activity('Generate 1604E Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'error' => 'Organization setup ID not found',
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Failed to generate 1604E due to missing organization ID.');

            return redirect()->back()->withErrors(['error' => 'Organization setup ID not found.']);
        }

        // Check for existing WithHolding record
        $existingWithHolding = WithHolding::where('type', '1604E')
            ->where('year', $validated['year'])
            ->where('organization_id', $organizationId)
            ->first();

        // Check for existing Form1604E record
        $existingForm1604E = Form1604E::where('year', $validated['year'])
            ->where('org_setup_id', $organizationId)
            ->first();

        if ($existingWithHolding) {
            activity('Duplicate Withholding Record')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $existingWithHolding->id,
                    'year' => $validated['year'],
                    'organization_id' => $organizationId,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Attempted to generate 1604E but withholding record already exists.');

            return redirect()->route('form1604E.preview', ['id' => $existingWithHolding->id])
                ->with('warning', '1604E Withholding form already exists for this year.');
        }

        if ($existingForm1604E) {
            activity('Duplicate Form Record')
                ->causedBy(Auth::user())
                ->withProperties([
                    'form_id' => $existingForm1604E->id,
                    'year' => $validated['year'],
                    'organization_id' => $organizationId,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Attempted to generate 1604E but form record already exists.');

            return redirect()->route('form1604E.preview', ['id' => $existingForm1604E->id])
                ->with('warning', '1604E form already exists for this year.');
        }

        // Create new withholding record
        $withHolding = WithHolding::create([
            'type' => $validated['type'],
            'organization_id' => $organizationId,
            'title' => 'Annual Information Return of Creditable Income Taxes Withheld (Expanded)',
            'year' => $validated['year'],
            'created_by' => Auth::id(),
        ]);

        activity('Withholding Record Creation')
            ->performedOn($withHolding)
            ->causedBy(Auth::user())
            ->withProperties([
                'withholding_id' => $withHolding->id,
                'year' => $validated['year'],
                'organization_id' => $organizationId,
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log('Withholding record created for 1604E.');

        return redirect()->route('with_holding.1604E', ['id' => $withHolding->id])
            ->with('success', 'Withholding Tax Return for 1604E has been generated.');
    }

    //Summary
    public function showSummary1604E($id)
    {
        // Fetch the withholding record for type 1604E
        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1604E')
            ->with('creator', 'organization')
            ->firstOrFail();

        $year = $withHolding->year;
        $orgSetupId = $withHolding->organization_id;

        // Fetch all Form1601EQ records for the year
        $form1601EQRecords = Form1601EQ::where('org_setup_id', $orgSetupId)
            ->where('year', $year)
            ->get();

        // Calculate totals from Form 1601-EQ (no need to fetch 0619-E separately)
        $totalTaxesWithheld = $form1601EQRecords->sum('total_taxes_withheld');
        $totalRemitted = $form1601EQRecords->sum('total_remittances_made');

        // Calculate over-remittance (if any)
        $overRemittance = max(0, $totalRemitted - $totalTaxesWithheld);

        // Map quarters
        $quarterNames = [
            1 => ['name' => '1st Quarter (January - March)'],
            2 => ['name' => '2nd Quarter (April - June)'],
            3 => ['name' => '3rd Quarter (July - September)'],
            4 => ['name' => '4th Quarter (October - December)'],
        ];

        // Aggregate data per quarter
        $quarters = collect($quarterNames)->map(function ($quarterInfo, $quarter) use ($form1601EQRecords) {
            $records = $form1601EQRecords->where('quarter', $quarter);

            return [
                'name' => $quarterInfo['name'],
                'taxes_withheld' => $records->sum('total_taxes_withheld'),
                'penalties' => $records->sum(fn($record) => $record->calculateTotalPenalties()),
                'total_remitted' => $records->sum(fn($record) => $record->calculateTotalRemittances()),
            ];
        });

        $employeeTIN = $withHolding->creator->tin ?? 'N/A';

        return view('tax_return.with_holding.1604E_summary', [
            'with_holding' => $withHolding,
            'quarters' => $quarters,
            'totalRemitted' => $totalRemitted,
            'totalTaxesWithheld' => $totalTaxesWithheld,
            'overRemittance' => $overRemittance,
            'employee_tin' => $employeeTIN,
        ]);
    }

    // 1604E schedule 1
    public function showRemittance1604E($id)
    {
        // Fetch the withholding record for type 1604E
        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1604E')
            ->with('creator') // Fetch creator for display
            ->firstOrFail();

        // Fetch related 1601EQ records for the same year
        $year = $withHolding->year;
        $orgSetupId = $withHolding->organization_id;

        $form1601EQRecords = Form1601EQ::where('org_setup_id', $orgSetupId)
            ->where('year', $year)
            ->get(); // Fetch all Form1601EQ rows

        // Calculate totals
        $totalTaxesWithheld = $form1601EQRecords->sum('total_taxes_withheld');
        $totalPenalties = $form1601EQRecords->sum(function ($record) {
            return $record->calculateTotalPenalties();
        });
        $totalRemittances = $form1601EQRecords->sum(function ($record) {
            return $record->calculateTotalRemittances();
        });

        // Fetch creator's TIN (if available)
        $employeeTIN = $withHolding->creator->tin ?? 'N/A';

        // Pass data to the view
        return view('tax_return.with_holding.1604E_remittances', [
            'with_holding' => $withHolding,
            'form1601EQRecords' => $form1601EQRecords,
            'totalTaxesWithheld' => $totalTaxesWithheld,
            'totalPenalties' => $totalPenalties,
            'totalRemittances' => $totalRemittances,
            'employee_tin' => $employeeTIN,
        ]);
    }

    public function showSources1604E($withholdingId)
    {
        // Fetch withholding record for 1604-E
        $withHolding = WithHolding::findOrFail($withholdingId);
        $year = $withHolding->year;
        $organizationId = $withHolding->organization_id;

        // Fetch all transactions marked as QAP (active) for the given year
        $qapTransactions = Transactions::where('organization_id', $organizationId)
            ->whereYear('date', $year)
            ->where('QAP', 'active')
            ->whereNotNull('withholding_id')
            ->with(['contactDetails', 'taxRows.atc']) 
            ->get();    

        // Group transactions by contact and ATC
        $payeeSummary = $qapTransactions->map(function ($transaction) {
            return $transaction->taxRows->map(function ($taxRow) use ($transaction) {
                $contact = $transaction->contactDetails;
                $atc = $taxRow->atc;
                return [
                    'vendor' => $contact->bus_name,
                    'tin' => $contact->contact_tin ?? 'N/A',
                    'address' => $contact->contact_address ?? 'N/A',
                    'amount' => $taxRow->net_amount,
                    'tax_withheld' => $taxRow->atc_amount,
                    'atc' => $atc->tax_code ?? 'N/A',
                    'tax_rate' => $atc->tax_rate ?? 0,
                ];
            });
        })->collapse();  // Flatten the collection

        // Paginate manually if necessary
        $currentPage = request()->get('page', 1);
        $perPage = 5;
        $paginatedSummary = $payeeSummary->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedSummary,
            $payeeSummary->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Return the view with aggregated data
        return view('tax_return.with_holding.1604E_sources', [
            'withHolding' => $withHolding,
            'payeeSummary' => $paginatedSummary,
            'year' => $year,
            'qapTransactions' => $paginator,
        ]);
    }

    public function createForm1604E($id)
    {
        $existingForm1604E = Form1604E::where('withholding_id', $id)->first();

        // If the form already exists, redirect to the preview page
        if ($existingForm1604E) {
            return redirect()->route('form1604E.preview', ['id' => $id])
                ->with('warning', 'Form 1604E for this year has already been submitted. You cannot submit another.');
        }

        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1604E')
            ->firstOrFail();

        $orgSetup = OrgSetup::findOrFail(session('organization_id'));

        // Pass form1604E as null (or other logic if needed)
        $form1604E = null;

        return view('tax_return.with_holding.1604E_form', compact('withHolding', 'orgSetup', 'form1604E'));
    }

    /**
     * Store a 1604E form.
     */
    public function storeForm1604E(Request $request, $id)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'year' => 'required|numeric|digits:4|min:1900|max:' . date('Y'),
                'amended_return' => 'required|boolean',
                'number_of_sheets' => 'nullable|integer|min:1',
                'agent_category' => 'required|string|in:Government,Private',
                'agent_top' => 'nullable|required_if:agent_category,Private|in:Yes,No',
            ]);

            // Log successful validation
            activity('Form Validation')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $id,
                    'validated_data' => $validated,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Validation succeeded for Form 1604E submission.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            activity('Validation Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $id,
                    'errors' => $e->errors(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Validation failed for Form 1604E submission.');

            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $organizationId = session('organization_id');

        try {
            // Create or update the Form1604E record
            $form1604E = Form1604E::updateOrCreate(
                ['withholding_id' => $id],
                $validated + [
                    'org_setup_id' => $organizationId,
                ]
            );

            // Log the creation or update of the form
            activity('Form Submission')
                ->performedOn($form1604E)
                ->causedBy(Auth::user())
                ->withProperties([
                    'form_id' => $form1604E->id,
                    'withholding_id' => $id,
                    'form_data' => $form1604E->toArray(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Form 1604E created or updated successfully.');

            return redirect()->route('form1604E.preview', ['id' => $id])
                ->with('success', 'Form 1604E submitted successfully.');

        } catch (\Exception $e) {
            // Log errors during creation or update
            activity('Form Submission Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $id,
                    'error_message' => $e->getMessage(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Error occurred during Form 1604E submission.');

            return redirect()->back()->withErrors(['error' => 'Failed to submit Form 1604E. Please try again.']);
        }
    }

    public function previewForm1604E($id)
    {
        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1604E')
            ->with('creator', 'organization')
            ->firstOrFail();

        $year = $withHolding->year;
        $orgSetupId = $withHolding->organization_id;

        $form1604E = Form1604E::where('withholding_id', $id)->first();

        $form1601EQRecords = Form1601EQ::where('org_setup_id', $orgSetupId)
            ->where('year', $year)
            ->get();

        $totalTaxesWithheld = $form1601EQRecords->sum('total_taxes_withheld');
        $totalRemitted = $form1601EQRecords->sum('total_remittances_made');
        $totalPenalties = $form1601EQRecords->sum('penalties');
        $overRemittance = max(0, $totalRemitted - $totalTaxesWithheld);

        $quarterNames = [
            1 => ['name' => '1st Quarter (January - March)'],
            2 => ['name' => '2nd Quarter (April - June)'],
            3 => ['name' => '3rd Quarter (July - September)'],
            4 => ['name' => '4th Quarter (October - December)'],
        ];

        $quarters = collect($quarterNames)->map(function ($quarterInfo, $quarter) use ($form1601EQRecords) {
            $records = $form1601EQRecords->where('quarter', $quarter);

            return [
                'name' => $quarterInfo['name'],
                'taxes_withheld' => $records->sum('total_taxes_withheld'),
                'penalties' => $records->sum(fn($record) => $record->calculateTotalPenalties()),
                'total_remitted' => $records->sum(fn($record) => $record->calculateTotalRemittances()),
                'remittance_date' => $records->max('date_of_remittance') 
                                    ? Carbon::parse($records->max('date_of_remittance'))->format('m/d/Y') 
                                    : Carbon::parse($records->max('created_at'))->format('m/d/Y'),
            ];
        });

        return view('tax_return.with_holding.1604E_preview', [
            'with_holding' => $withHolding,
            'quarters' => $quarters,
            'totalRemitted' => $totalRemitted,
            'totalTaxesWithheld' => $totalTaxesWithheld,
            'totalPenalties' => $totalPenalties,
            'overRemittance' => $overRemittance,
            'form1604E' => $form1604E,
            'form1601EQRecords' => $form1601EQRecords,
        ]);
    }

    public function editForm1604E($id)
    {
        // Fetch the Form1604E record with the associated withholding record
        $form1604E = Form1604E::where('withholding_id', $id)->firstOrFail();

        // Pass the form data to the edit view
        return view('tax_return.with_holding.1604E_edit', compact('form1604E'));
    }

    public function updateForm1604E(Request $request, $id)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'amended_return' => 'required|boolean',
                'agent_category' => 'required|string|in:Government,Private',
            ]);

            // Log the validation success
            activity('Form Validation')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $id,
                    'validated_data' => $validated,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Validation succeeded for updating Form 1604E.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation errors
            activity('Validation Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $id,
                    'errors' => $e->errors(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Validation failed for updating Form 1604E.');

            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        try {
            // Find the form record
            $form1604E = Form1604E::where('withholding_id', $id)->firstOrFail();

            // Track original data for logging
            $originalAttributes = $form1604E->getOriginal();

            // Update the fields
            $form1604E->update([
                'amended_return' => $validated['amended_return'],
                'agent_category' => $validated['agent_category'],
            ]);

            // Log the update success
            activity('Form Update')
                ->performedOn($form1604E)
                ->causedBy(Auth::user())
                ->withProperties([
                    'form_id' => $form1604E->id,
                    'withholding_id' => $id,
                    'original_data' => $originalAttributes,
                    'updated_data' => $form1604E->getAttributes(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Form 1604E updated successfully.');

            return redirect()->route('form1604E.preview', ['id' => $id])
                ->with('success', 'Form 1604E updated successfully.');
        } catch (\Exception $e) {
            // Log update errors
            activity('Update Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $id,
                    'error_message' => $e->getMessage(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Error occurred while updating Form 1604E.');

            return redirect()->back()->withErrors(['error' => 'Failed to update Form 1604E. Please try again.']);
        }
    }

    public function downloadForm1604E($id)
    {
        // Fetch the form and related data
        $form = Form1604E::with(['organization', 'withholding'])->findOrFail($id);
        $withHolding = $form->withholding;

        $year = $withHolding->year;
        $orgSetupId = $withHolding->organization_id;

        // Fetch related Form1601EQ records
        $form1601EQRecords = Form1601EQ::where('org_setup_id', $orgSetupId)
            ->where('year', $year)
            ->get();

        // Calculate totals
        $totalTaxesWithheld = $form1601EQRecords->sum('total_taxes_withheld');
        $totalRemitted = $form1601EQRecords->sum('total_remittances_made');
        $totalPenalties = $form1601EQRecords->sum('penalties');
        $overRemittance = max(0, $totalRemitted - $totalTaxesWithheld);

        // Quarter names and calculations
        $quarterNames = [
            1 => ['name' => '1st Quarter (January - March)'],
            2 => ['name' => '2nd Quarter (April - June)'],
            3 => ['name' => '3rd Quarter (July - September)'],
            4 => ['name' => '4th Quarter (October - December)'],
        ];

        $quarters = collect($quarterNames)->map(function ($quarterInfo, $quarter) use ($form1601EQRecords) {
            $records = $form1601EQRecords->where('quarter', $quarter);

            return [
                'name' => $quarterInfo['name'],
                'taxes_withheld' => $records->sum('total_taxes_withheld'),
                'penalties' => $records->sum(fn($record) => $record->calculateTotalPenalties()),
                'total_remitted' => $records->sum(fn($record) => $record->calculateTotalRemittances()),
                'remittance_date' => $records->max('date_of_remittance')
                    ? Carbon::parse($records->max('date_of_remittance'))->format('m/d/Y')
                    : Carbon::parse($records->max('created_at'))->format('m/d/Y'),
            ];
        });

        // Log the download action
        activity('Form Download')
            ->performedOn($form)
            ->causedBy(Auth::user())
            ->withProperties([
                'form_id' => $form->id,
                'year' => $form->year,
                'organization_id' => $form->organization->id ?? null,
                'total_taxes_withheld' => $totalTaxesWithheld,
                'total_remitted' => $totalRemitted,
                'total_penalties' => $totalPenalties,
                'over_remittance' => $overRemittance,
                'ip' => request()->ip(),
                'browser' => request()->header('User-Agent'),
            ])
            ->log("Form 1604E (ID: {$form->id}) was downloaded.");

        // Generate PDF
        $pdf = Pdf::loadView('tax_return.with_holding.1604E_pdf', [
            'form' => $form,
            'organization' => $form->organization,
            'quarters' => $quarters,
            'totalTaxesWithheld' => $totalTaxesWithheld,
            'totalRemitted' => $totalRemitted,
            'totalPenalties' => $totalPenalties,
            'overRemittance' => $overRemittance,
        ]);

        // Customize the filename
        $filename = '1604E_Form_' . $form->year . '.pdf';

        // Return the PDF for download
        return $pdf->download($filename);
    }

    public function markForm1604EFiled($id)
{
    try {
        Log::info('Marking WithHolding as Filed - Start', [
            'withholding_id' => $id,
            'user_id' => Auth::id(),
            'timestamp' => now(),
        ]);

        // Fetch the withholding record
        $withHolding = WithHolding::findOrFail($id);

        Log::info('WithHolding record retrieved successfully', [
            'withholding_id' => $withHolding->id,
            'current_status' => $withHolding->status,
        ]);

        // Update the status to "Filed"
        $withHolding->update(['status' => 'Filed']);

        Log::info('WithHolding status updated to Filed', [
            'withholding_id' => $withHolding->id,
            'updated_status' => $withHolding->status,
        ]);

        // Record activity
        activity('Status Update')
            ->performedOn($withHolding)
            ->causedBy(Auth::user())
            ->withProperties([
                'withholding_id' => $withHolding->id,
                'previous_status' => $withHolding->getOriginal('status'),
                'new_status' => 'Filed',
                'user_id' => Auth::id(),
                'ip' => request()->ip(),
            ])
            ->log("WithHolding ID {$withHolding->id} marked as Filed.");

        Log::info('WithHolding marked as Filed - Success', [
            'withholding_id' => $withHolding->id,
            'user_id' => Auth::id(),
        ]);

        return response()->json(['success' => true, 'message' => 'WithHolding marked as Filed.']);
    } catch (\Exception $e) {
        Log::error('Error marking WithHolding as Filed', [
            'withholding_id' => $id,
            'error_message' => $e->getMessage(),
            'user_id' => Auth::id(),
            'timestamp' => now(),
        ]);

        activity('Status Update Error')
            ->withProperties([
                'withholding_id' => $id,
                'error_message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'ip' => request()->ip(),
            ])
            ->log('An error occurred while marking WithHolding as Filed.');

        return response()->json(['success' => false, 'message' => 'Failed to mark as Filed.'], 500);
    }
}


    private function getWithHoldings($organizationId, $type, $perPage = 5)
    {

        return WithHolding::with(['employee', 'employment', 'creator'])
            ->where('type', $type)
            ->where('organization_id', $organizationId)
            ->paginate($perPage);

    }

}
