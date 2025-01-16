<?php

namespace App\Http\Controllers;

use App\Models\Atc;
use App\Models\Employee;
use App\Models\Employment;
use App\Models\Form1601C;
use App\Models\OrgSetup;
use App\Models\Source;
use App\Models\WithHolding;
use App\Exports\SourcesExport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel as MaatExcel;
use App\Exports\sources_template;

class WithHoldingController extends Controller
{
    public function index1601C(Request $request)
    {
        $organizationId = session('organization_id');
        $perPage = $request->input('perPage', 5);

        // Map month names to their corresponding integers
        $monthMapping = [
            'january' => 1,
            'february' => 2,
            'march' => 3,
            'april' => 4,
            'may' => 5,
            'june' => 6,
            'july' => 7,
            'august' => 8,
            'september' => 9,
            'october' => 10,
            'november' => 11,
            'december' => 12,
        ];

        // Start the query with the organization filter and type
        $query = WithHolding::where('type', '1601C')
            ->where('organization_id', $organizationId);

        if ($request->has('search') && $request->search != '') {
            $search = trim($request->search); // Trim whitespace from the search term

            // Convert search term to lowercase for case-insensitive matching
            $lowerSearch = strtolower($search);

            // Check if the search term is a valid month name
            $monthValue = $monthMapping[$lowerSearch] ?? null;

            // Log the detected month value (if any)
            Log::info('Search term and month mapping', [
                'search' => $search,
                'mapped_month' => $monthValue,
            ]);

            // Add separate search conditions for year, month, and creator
            $query->where(function ($q) use ($search, $monthValue) {
                // Search for year or mapped month value
                $q->where('year', 'LIKE', '%' . $search . '%');

                if ($monthValue) {
                    $q->orWhere('month', $monthValue); // Use direct match for month
                }
            })->orWhereHas('creator', function ($q) use ($search) {
                // Search in creator's first_name or last_name
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%');
            });
        }

        // Paginate the results
        $with_holdings = $query->paginate($perPage);

        // Log the results
        Log::info('Search results', [
            'total_results' => $with_holdings->total(),
            'current_page' => $with_holdings->currentPage(),
            'per_page' => $with_holdings->perPage(),
        ]);

        return view('tax_return.with_holding.1601C', compact('with_holdings'));
    }

    public function generate1601C(Request $request)
    {
        // Validate the input
        $request->validate([
            'year' => 'required|numeric|min:1900|max:' . date('Y'),
            'month' => 'required|numeric|min:1|max:12',
            'type' => 'required|string|in:1601C', // Ensure type is 1601C
        ]);

        $organizationId = session('organization_id');

        // Check if the record for the given month and year already exists
        $existingRecord = WithHolding::where('type', '1601C')
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
            'title' => 'Monthly Remittance Return of Income Taxes Withheld on Compensation',
            'gross_compensation' => 0.00, // Placeholder
            'taxable_compensation' => 0.00, // Placeholder
            'non_taxable_compensation' => 0.00, // Placeholder
            'tax_due' => 0.00, // Placeholder
            'month' => $request->month,
            'year' => $request->year,
            'created_by' => Auth::id(),
        ]);

        // Log activity
        activity('Withholding Tax Generation')
            ->performedOn($withHolding)
            ->causedBy(Auth::user())
            ->withProperties([
                'type' => $request->type,
                'month' => $request->month,
                'year' => $request->year,
                'organization_id' => $organizationId,
                'created_by' => Auth::id(),
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log("Withholding Tax Return for 1601C (Month: {$request->month}, Year: {$request->year}) was generated.");

        return redirect()->route('with_holding.1601C_summary', ['id' => $withHolding->id])
            ->with('success', 'Withholding Tax Return for 1601C has been generated.');
    }

    public function destroy1601C(Request $request)
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
                activity('Withholding Deletion')
                    ->performedOn($withHolding)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'withholding_id' => $withHolding->id,
                        'organization_id' => $withHolding->organization_id,
                        'ip' => $request->ip(),
                        'browser' => $request->header('User-Agent'),
                    ])
                    ->log("WithHolding ID {$withHolding->id} was soft deleted.");
            }

            // Return success response
            return response()->json(['message' => 'WithHolding soft deleted successfully.'], 200);

        } catch (\Exception $e) {
            // Log any errors that occurred during deletion
            Log::error('Error during soft deletion of WithHoldings: ' . $e->getMessage(), [
                'ids' => $ids,
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
            ]);

            // Return error response
            return response()->json(['error' => 'An error occurred while deleting records'], 500);
        }
    }

    //1601C summary
    public function showSummary1601C($id)
    {
        // Fetch the withholding tax record and ensure it is type 1601C
        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1601C')
            ->with(['employee', 'sources', 'organization']) // Include sources for calculations
            ->firstOrFail();

        // Fetch employee's TIN
        $employeeTIN = $withHolding->employee->tin ?? 'N/A';

        // Calculate totals from sources
        $totals = [
            'total_compensation' => $withHolding->sources->sum('gross_compensation'),
            'statutory_minimum_wage' => $withHolding->sources->sum('statutory_minimum_wage'),
            'holiday_overtime_hazard' => $withHolding->sources->sum('holiday_pay') +
            $withHolding->sources->sum('overtime_pay') +
            $withHolding->sources->sum('night_shift_differential') +
            $withHolding->sources->sum('hazard_pay'),
            'month_13_pay' => $withHolding->sources->sum('month_13_pay'),
            'de_minimis_benefits' => $withHolding->sources->sum('de_minimis_benefits'),
            'mandatory_contributions' => $withHolding->sources->sum('sss_gsis_phic_hdmf_union_dues'),
            'other_non_taxable_compensation' => $withHolding->sources->sum('other_non_taxable_compensation'),
        ];

        // Pass data to the Blade file
        return view('tax_return.with_holding.1601C_summary', [
            'with_holding' => $withHolding,
            'employee_tin' => $employeeTIN,
            'totals' => $totals, // Pass totals to the view
        ]);
    }

    //1601C Sources functions
    public function showSources1601C($id, Request $request)
    {
        // Fetch the withholding tax record and ensure it is type 1601C
        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1601C')
            ->firstOrFail();

        $organizationId = session('organization_id');

        // Fetch employees with their latest employment
        $employees = Employee::with('latestEmployment')
            ->where('organization_id', $organizationId)
            ->get();

        // Get the perPage value from the request, default to 5
        $perPage = $request->input('perPage', 5);

        // Fetch sources with related employee and employment data
        $sourcesQuery = $withHolding->sources()
            ->with(['employee.latestEmployment'])
            ->where('status', 'Active'); // Only include active sources

        // Check for search input
        if ($request->has('search') && $request->search != '') {
            $search = trim($request->search);

            // Apply search conditions
            $sourcesQuery->where(function ($q) use ($search) {
                $q->whereHas('employee', function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('employment', function ($q) use ($search) {
                    $q->where('employee_wage_status', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('payment_date', 'LIKE', '%' . $search . '%')
                ->orWhere('gross_compensation', 'LIKE', '%' . $search . '%')
                ->orWhere('tax_due', 'LIKE', '%' . $search . '%')
                ->orWhere('statutory_minimum_wage', 'LIKE', '%' . $search . '%')
                ->orWhere('de_minimis_benefits', 'LIKE', '%' . $search . '%')
                ->orWhere('sss_gsis_phic_hdmf_union_dues', 'LIKE', '%' . $search . '%')
                ->orWhere('other_non_taxable_compensation', 'LIKE', '%' . $search . '%')
                ->orWhere('taxable_compensation', 'LIKE', '%' . $search . '%');
            });
        }

        // Paginate the results
        $sources = $sourcesQuery->paginate($perPage);

        return view('tax_return.with_holding.1601C_sources', [
            'with_holding' => $withHolding,
            'sources' => $sources,
            'employees' => $employees,
        ]);
    }
    
    //store sources
    public function store1601C(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payment_date' => 'required|date',
            'gross_compensation' => 'required|numeric|min:0',
            'statutory_minimum_wage' => 'nullable|numeric|min:0',
            'holiday_pay' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'night_shift_differential' => 'nullable|numeric|min:0',
            'hazard_pay' => 'nullable|numeric|min:0',
            'month_13_pay' => 'nullable|numeric|min:0',
            'de_minimis_benefits' => 'nullable|numeric|min:0',
            'sss_gsis_phic_hdmf_union_dues' => 'nullable|numeric|min:0',
            'other_non_taxable_compensation' => 'nullable|numeric|min:0',
            'tax_due' => 'nullable|numeric|min:0',
        ]);

        // Retrieve withholding record
        $withHolding = WithHolding::findOrFail($id);

        // Fetch the employee's current employment
        $employment = Employment::where('employee_id', $request->employee_id)->first();

        if (!$employment) {
            return redirect()->back()->withErrors([
                'employee_id' => 'Employment record not found for this employee.',
            ]);
        }

        // Prevent duplicate records for the same withholding and payment date
        $existingSource = Source::where('withholding_id', $id)
            ->where('employee_id', $request->employee_id)
            ->where('payment_date', $request->payment_date)
            ->exists();

        if ($existingSource) {
            return redirect()->back()->withErrors([
                'payment_date' => 'A source record for this employee and date already exists.',
            ]);
        }

        $data = $request->all();

        // Null fields for "Above Minimum Wage"
        $statutoryFields = [
            'statutory_minimum_wage',
            'holiday_pay',
            'overtime_pay',
            'night_shift_differential',
            'hazard_pay',
        ];

        if ($employment->employee_wage_status === 'Above Minimum Wage') {
            foreach ($statutoryFields as $field) {
                $data[$field] = null;
            }
        }

        // Compute Non-Taxable Benefits
        $nonTaxableBenefits = $this->computeNonTaxableBenefits((object) $data);

        // Compute Taxable Compensation
        $taxableCompensation = max(0, $data['gross_compensation'] - $nonTaxableBenefits);

        // Determine Taxability and Tax Due
        $isTaxable = $taxableCompensation > 0 && $employment->employee_wage_status === 'Above Minimum Wage';
        $computedTaxDue = $isTaxable ? $this->computeTax($taxableCompensation) : 0;

        // Use tax_due from request or fallback to computed value
        $finalTaxDue = $request->tax_due ?? $computedTaxDue;

        // Create Source Record
        $source = Source::create([
            'withholding_id' => $id,
            'employee_id' => $data['employee_id'],
            'employment_id' => $employment->id,
            'payment_date' => $data['payment_date'],
            'gross_compensation' => $data['gross_compensation'],
            'taxable_compensation' => $taxableCompensation,
            'tax_due' => $finalTaxDue,
            'statutory_minimum_wage' => $data['statutory_minimum_wage'],
            'holiday_pay' => $data['holiday_pay'],
            'overtime_pay' => $data['overtime_pay'],
            'night_shift_differential' => $data['night_shift_differential'],
            'hazard_pay' => $data['hazard_pay'],
            'month_13_pay' => $data['month_13_pay'],
            'de_minimis_benefits' => $data['de_minimis_benefits'],
            'sss_gsis_phic_hdmf_union_dues' => $data['sss_gsis_phic_hdmf_union_dues'],
            'other_non_taxable_compensation' => $data['other_non_taxable_compensation'],
        ]);

        // Log activity
        activity('Withholding Source Management')
            ->performedOn($source)
            ->causedBy(Auth::user())
            ->withProperties([
                'withholding_id' => $id,
                'source_id' => $source->id,
                'employee_id' => $data['employee_id'],
                'payment_date' => $data['payment_date'],
                'tax_due' => $finalTaxDue,
                'organization_id' => $withHolding->organization_id,
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log("Source record ID {$source->id} for Withholding ID {$id} was created.");

        return redirect()->back()->with('success', 'Source record added successfully.');
    }

    //para sa non-taxable field
    private function computeNonTaxableBenefits($request)
    {
        $combinedBenefits = $request->month_13_pay ?? 0;

        // Calculate non-taxable portion (up to ₱90,000)
        $nonTaxableBenefits = min($combinedBenefits, 90000);

        // Add other non-taxable fields
        return $nonTaxableBenefits +
            ($request->statutory_minimum_wage ?? 0) +
            ($request->holiday_pay ?? 0) +
            ($request->overtime_pay ?? 0) +
            ($request->night_shift_differential ?? 0) +
            ($request->hazard_pay ?? 0) +
            ($request->de_minimis_benefits ?? 0) +
            ($request->sss_gsis_phic_hdmf_union_dues ?? 0) +
            ($request->other_non_taxable_compensation ?? 0);
    }

    //hatdog na train law yan
    private function computeTax($taxableCompensation)
    {
        // Monthly tax brackets based on TRAIN Law
        $monthlyBrackets = [
            [20833, 0],       // Up to ₱20,833: 0% tax
            [33333, 0.15],    // ₱20,834 - ₱33,333: 15%
            [66667, 0.20],    // ₱33,334 - ₱66,667: 20%
            [166667, 0.25],   // ₱66,668 - ₱166,667: 25%
            [666667, 0.30],   // ₱166,668 - ₱666,667: 30%
            [PHP_INT_MAX, 0.35], // Over ₱666,667: 35%
        ];

        $taxDue = 0;
        $previousLimit = 0;

        foreach ($monthlyBrackets as [$limit, $rate]) {
            if ($taxableCompensation > $limit) {
                $taxDue += ($limit - $previousLimit) * $rate;
                $previousLimit = $limit;
            } else {
                $taxDue += ($taxableCompensation - $previousLimit) * $rate;
                break;
            }
        }

        return $taxDue;
    }

    public function deactivateSources1601C(Request $request)
    {
        activity('Source Deactivation')
            ->causedBy(Auth::user())
            ->withProperties([
                'request_ids' => $request->ids,
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log('Deactivate request received.');

        // Validate the request
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:sources,id',
        ]);

        // Fetch the sources to be deactivated
        $sources = Source::whereIn('id', $request->ids)->get();

        // Log the number of sources to be updated
        activity('Source Deactivation')
            ->causedBy(Auth::user())
            ->withProperties([
                'source_count' => $sources->count(),
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log('Number of sources to be deactivated.');

        foreach ($sources as $source) {
            $oldStatus = $source->status;

            // Update the status to 'Inactive'
            $source->update(['status' => 'Inactive']);

            // Log the status change for each source
            activity('Source Deactivation')
                ->performedOn($source)
                ->causedBy(Auth::user())
                ->withProperties([
                    'source_id' => $source->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'Inactive',
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log("Source ID {$source->id} status updated to 'Inactive'.");
        }

        return response()->json(['message' => 'Selected Sources have been deactivated.']);
    }

    public function archiveSources1601C(Request $request, $id)
    {
        // Fetch the withholding tax record and ensure it is type 1601C
        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1601C')
            ->firstOrFail();

        // Fetch employees with their latest employment
        $employees = Employee::with('latestEmployment')->get();

        // Get the perPage value from the request, default to 5
        $perPage = $request->input('perPage', 5);

        // Fetch sources with related employee and employment data
        $sourcesQuery = $withHolding->sources()
            ->with(['employee.latestEmployment'])
            ->where('status', 'Inactive'); // Only include inactive sources

        // Check for search input
        if ($request->has('search') && $request->search != '') {
            $search = trim($request->search);
            
            // Apply search conditions
            $sourcesQuery->where(function ($q) use ($search) {
                $q->whereHas('employee', function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('employment', function ($q) use ($search) {
                    $q->where('employee_wage_status', 'LIKE', '%' . $search . '%');
                })
                ->orWhere('payment_date', 'LIKE', '%' . $search . '%')
                ->orWhere('gross_compensation', 'LIKE', '%' . $search . '%')
                ->orWhere('tax_due', 'LIKE', '%' . $search . '%')
                ->orWhere('statutory_minimum_wage', 'LIKE', '%' . $search . '%')
                ->orWhere('de_minimis_benefits', 'LIKE', '%' . $search . '%')
                ->orWhere('sss_gsis_phic_hdmf_union_dues', 'LIKE', '%' . $search . '%')
                ->orWhere('other_non_taxable_compensation', 'LIKE', '%' . $search . '%')
                ->orWhere('taxable_compensation', 'LIKE', '%' . $search . '%');
            });
        }

        // Paginate the results
        $sources = $sourcesQuery->paginate($perPage);

        return view('tax_return.with_holding.1601C_sources_archive', [
            'with_holding' => $withHolding,
            'sources' => $sources,
            'employees' => $employees,
        ]);
    }

    public function destroySources1601C(Request $request)
    {

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:sources,id',
        ]);

        $sources = Source::whereIn('id', $request->ids)
            ->where('status', 'Inactive')
            ->get();

        foreach ($sources as $source) {
            $source->delete();

            activity('Withholding 1601C - Sources')
                ->performedOn($source)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => $source->organization_id,
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("Source {$source->name} was deleted.");
        }

        return response()->json(['message' => 'Selected archived Sources have been deleted successfully.'], 200);
    }

    public function restoreSources1601C(Request $request)
    {

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:sources,id',
        ]);

        $sources = Source::whereIn('id', $request->ids)
            ->get();

        foreach ($sources as $source) {
            $oldStatus = $source->status;

            // Restore Source
            $source->update(['status' => 'Active']);

            activity('Withholding 1601C - Sources')
                ->performedOn($source)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => $source->organization_id,
                    'old_status' => $oldStatus,
                    'new_status' => 'Active',
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("Source {$source->name} was restored.");
        }

        return response()->json(['message' => 'Selected Sources have been restored successfully.'], 200);
    }

    public function downloadSources1601C(Request $request, $id)
    {
        // Fetch the withholding record
        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1601C')
            ->firstOrFail();

        // Fetch the active sources associated with the withholding record
        $sources = Source::where('status', 'Active')
            ->where('withholding_id', $withHolding->id)
            ->with(['employee', 'employment'])
            ->get();

        // Prepare data for the PDF
        $data = [
            'title' => 'Available Sources',
            'date' => date('m/d/Y'),
            'sources' => $sources,
            'withholding' => $withHolding,
        ];

        // Generate the PDF
        $pdf = PDF::loadView('tax_return.with_holding.1601C_sources_download', $data);

        // Log the download activity
        activity('Source Download')
            ->performedOn($withHolding)
            ->causedBy(Auth::user())
            ->withProperties([
                'withholding_id' => $withHolding->id,
                'type' => $withHolding->type,
                'source_count' => $sources->count(),
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log("PDF of active sources for Withholding ID {$withHolding->id} was downloaded.");

        // Return the generated PDF for download
        return $pdf->download('Sources.pdf');
    }

    //form fill up sa 1601C
    public function createForm1601C($id)
    {
        // Check if the 1601C form already exists
        $existingForm = Form1601C::where('withholding_id', $id)->first();

        if ($existingForm) {
            // Redirect to the preview page
            return redirect()->route('form1601C.preview', ['id' => $existingForm->id]);
        }

        // If not existing, proceed with creating the form
        $withHolding = WithHolding::findOrFail($id);
        $orgSetup = OrgSetup::find(session('organization_id'));

        if (!$orgSetup) {
            return redirect()->back()->withErrors(['error' => 'Organization setup not found.']);
        }

        $allowedTaxCodes = [
            'WI010', 'WI011', 'WI090', 'WI091', 'WI100', 'WI120', 'WI152',
            'WI153', 'WI158', 'WI160', 'WC010', 'WC011', 'WC100', 'WC120',
            'WC158', 'WC160'
        ];

        $atcs = Atc::whereIn('tax_code', $allowedTaxCodes)->get();
        $sources = $withHolding->sources()->get();

        return view('tax_return.with_holding.1601C_form', [
            'withHolding' => $withHolding,
            'orgSetup' => $orgSetup,
            'atcs' => $atcs,
            'sources' => $sources,
        ]);
    }

    //Para sa hatdog na 1601C BIR form na napaka haba hatdog na yan
    public function storeForm1601C(Request $request, $id)
    {
        $request->validate([
            'filing_period' => 'required|date_format:Y-m',
            'amended_return' => 'required|boolean',
            'any_taxes_withheld' => 'required|boolean',
            'number_of_sheets' => 'required|integer|min:0',
            'atc_id' => 'required|exists:atcs,id',
            'tax_relief' => 'required|boolean',
            'tax_relief_details' => 'required_if:tax_relief,1|string|nullable',
            'total_taxes_withheld' => 'required|numeric|min:0',
            'adjustment_taxes_withheld' => 'nullable|numeric|min:0',
            'tax_remitted_return' => 'nullable|numeric|min:0',
            'other_remittances' => 'nullable|numeric|min:0',
            'surcharge' => 'nullable|numeric|min:0',
            'interest' => 'nullable|numeric|min:0',
            'compromise' => 'nullable|numeric|min:0',
            'agent_category' => 'required|string',
        ]);

        $withHolding = WithHolding::findOrFail($id);

        $orgSetupId = $withHolding->organization_id ?? session('organization_id');
        if (!$orgSetupId) {
            return redirect()->back()->withErrors(['error' => 'Organization setup ID not found.']);
        }

        $sources = $withHolding->sources()->get();

        $totalTaxesWithheld = $sources->sum('tax_due');
        $adjustment = $request->adjustment_taxes_withheld ?? 0;
        $taxRemitted = $request->tax_remitted_return ?? 0;
        $otherRemittances = $request->other_remittances ?? 0;

        $remittance = $totalTaxesWithheld + $adjustment - $taxRemitted - $otherRemittances;

        $surcharge = $request->surcharge ?? 0;
        $interest = $request->interest ?? 0;
        $compromise = $request->compromise ?? 0;
        $totalPenalties = $surcharge + $interest + $compromise;

        $totalAmountStillDue = $remittance + $totalPenalties;

        try {
            $filingPeriod = Carbon::createFromFormat('Y-m', $request->filing_period)->startOfMonth();

            // Create or update the 1601C form record
            $form = Form1601C::updateOrCreate(
                ['withholding_id' => $id],
                [
                    'filing_period' => $filingPeriod,
                    'org_setup_id' => $withHolding->organization_id,
                    'atc_id' => $request->atc_id,
                    'amended_return' => $request->amended_return,
                    'any_taxes_withheld' => $request->any_taxes_withheld,
                    'number_of_sheets' => $request->number_of_sheets,
                    'tax_relief' => $request->tax_relief,
                    'tax_relief_details' => $request->tax_relief_details,
                    'total_taxes_withheld' => $totalTaxesWithheld,
                    'total_compensation' => $sources->sum('gross_compensation'),
                    'taxable_compensation' => $sources->sum('taxable_compensation'),
                    'tax_due' => $totalTaxesWithheld,
                    'adjustment' => $adjustment,
                    'surcharge' => $surcharge,
                    'interest' => $interest,
                    'compromise' => $compromise,
                    'other_remittances' => $otherRemittances,
                    'total_amount_due' => $totalAmountStillDue,
                    'agent_category' => $request->agent_category,
                ]
            );

            // Log the activity
            activity('Form Submission')
                ->performedOn($form)
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $id,
                    'form_id' => $form->id,
                    'filing_period' => $filingPeriod->format('Y-m'),
                    'total_taxes_withheld' => $totalTaxesWithheld,
                    'total_amount_due' => $totalAmountStillDue,
                    'organization_id' => $withHolding->organization_id,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log("Form 1601C was submitted or updated for Withholding ID {$id}.");

            return redirect()->route('with_holding.1601C_summary', ['id' => $id])
                ->with('success', '1601C Form has been successfully submitted.');
        } catch (\Exception $e) {
            // Log the error
            activity('Form Submission Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $id,
                    'error_message' => $e->getMessage(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('An error occurred during the submission of Form 1601C.');

            return redirect()->back()
                ->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function previewForm1601C($id)
    {
        $form = Form1601C::with([
            'withholding.organization', // Load the organization relationship from withholding
            'atc',
            'sources.employee',
        ])->findOrFail($id);

        $totalGrossCompensation = $form->sources->sum('gross_compensation');
        $totalTaxableCompensation = $form->sources->sum('taxable_compensation');
        $totalTaxDue = $form->sources->sum('tax_due');
        $totalPenalties = ($form->surcharge ?? 0) + ($form->interest ?? 0) + ($form->compromise ?? 0);
        $totalAmountDue = $totalTaxDue + $totalPenalties;

        return view('tax_return.with_holding.1601C_preview', [
            'form' => $form,
            'totalGrossCompensation' => $totalGrossCompensation,
            'totalTaxableCompensation' => $totalTaxableCompensation,
            'totalTaxDue' => $totalTaxDue,
            'totalPenalties' => $totalPenalties,
            'totalAmountDue' => $totalAmountDue,
        ]);
    }

    public function editForm1601C($id)
    {
        $allowedTaxCodes = [
            'WI010', 'WI011', 'WI090', 'WI091', 'WI100', 'WI120', 'WI152',
            'WI153', 'WI158', 'WI160', 'WC010', 'WC011', 'WC100', 'WC120',
            'WC158', 'WC160',
        ];

        $atcs = Atc::whereIn('tax_code', $allowedTaxCodes)->get();

        $form = Form1601C::with(['withholding.organization', 'atc', 'sources'])->findOrFail($id);

        return view('tax_return.with_holding.1601C_edit', [
            'form' => $form,
            'organization' => $form->withholding->organization ?? null,
            'atcs' => $atcs,
        ]);
    }

    public function updateForm1601C(Request $request, $id)
    {
        $form = Form1601C::findOrFail($id);

        try {
            // Validate the request
            $validated = $request->validate([
                'atc_id' => 'required|exists:atcs,id',
                'amended_return' => 'required|boolean',
                'any_taxes_withheld' => 'required|boolean',
                'number_of_sheets' => 'required|integer|min:0',
                'other_remittances' => 'nullable|numeric|min:0',
                'adjustment' => 'nullable|numeric|min:0',
                'surcharge' => 'nullable|numeric|min:0',
                'interest' => 'nullable|numeric|min:0',
                'compromise' => 'nullable|numeric|min:0',
                'agent_category' => 'required|string',
                'tax_relief' => 'required|boolean',
                'adjustment_taxes_withheld' => 'nullable|numeric|min:0',
                'tax_remitted_return' => 'nullable|numeric|min:0',
            ]);

            // Track original attributes before updating
            $originalAttributes = $form->getOriginal();

            // Update the form
            $form->update([
                'atc_id' => $request->atc_id,
                'amended_return' => $request->amended_return,
                'any_taxes_withheld' => $request->any_taxes_withheld,
                'number_of_sheets' => $request->number_of_sheets,
                'other_remittances' => $request->other_remittances,
                'adjustment' => $request->adjustment,
                'surcharge' => $request->surcharge,
                'interest' => $request->interest,
                'compromise' => $request->compromise,
                'agent_category' => $request->agent_category,
                'tax_relief' => $request->tax_relief,
                'adjustment_taxes_withheld' => $request->adjustment_taxes_withheld,
                'tax_remitted_return' => $request->tax_remitted_return,
            ]);

            // Log activity
            activity('Form Update')
                ->performedOn($form)
                ->causedBy(Auth::user())
                ->withProperties([
                    'form_id' => $form->id,
                    'withholding_id' => $form->withholding_id,
                    'changes' => [
                        'before' => $originalAttributes,
                        'after' => $form->getAttributes(),
                    ],
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log("Form 1601C ID {$form->id} was updated.");

            return redirect()->route('form1601C.preview', ['id' => $form->id])
                ->with('success', 'Form 1601C updated successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Log validation error details
            activity('Form Update Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'form_id' => $form->id,
                    'validation_errors' => $e->errors(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log("Validation failed for Form 1601C ID {$form->id}.");

            return redirect()->back()->withErrors($e->errors());
        }
    }

    public function markForm1601CFiled($formId)
    {   
        try {
            // Fetch the Form0619E record
            $form = Form1601C::findOrFail($formId);

            // Fetch the related WithHolding record
            $withHolding = WithHolding::findOrFail($form->withholding_id);

            $withHolding->update(['status' => 'Filed']);

            activity('Status Update')
                ->performedOn($withHolding)
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $withHolding->id,
                    'form_id' => $form->id,
                    'previous_status' => $withHolding->getOriginal('status'),
                    'new_status' => 'Filed',
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("WithHolding ID {$withHolding->id} and Form0619E ID {$form->id} marked as Filed.");

            return redirect()->route('form0619E.preview', ['id' => $form->withholding_id])
                ->with('success', "Form 0619E (ID: {$formId}) and its related WithHolding record have been marked as Filed.");
        } catch (\Exception $e) {
            // Log any exceptions
            activity('Status Update Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'form_id' => $formId,
                    'error_message' => $e->getMessage(),
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log('An error occurred while marking Form0619E as Filed.');

            return redirect()->back()->withErrors(['error' => 'An error occurred while updating the status.']);
        }
    }

    private function getWithHoldings($organizationId, $type, $perPage = 5)
    {

        return WithHolding::with(['employee', 'employment', 'creator'])
            ->where('type', $type)
            ->where('organization_id', $organizationId)
            ->paginate($perPage);

    }

    public function importSources1601C(Request $request, $id)
    {
        // Validate file input
        $request->validate([
            'file' => 'required|mimes:csv,xlsx',
        ]);

        // Find the 1601C withholding record
        $withHolding = WithHolding::findOrFail($id);

        // Save the file temporarily
        $path = $request->file('file')->store('temp');

        // Log the import activity
        activity('Source Import')
            ->performedOn($withHolding)
            ->causedBy(Auth::user())
            ->withProperties([
                'withholding_id' => $id,
                'file_name' => $request->file('file')->getClientOriginalName(),
                'file_type' => $request->file('file')->getMimeType(),
                'file_path' => $path,
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log("Import initiated for Withholding ID {$id} with file {$request->file('file')->getClientOriginalName()}.");

        // Pass withholdingId to the Blade view
        return view('tax_return.with_holding.1601C_sources', [
            'importPath' => $path,
            'withholdingId' => $id, // Pass withholdingId
        ]);
    }

    public function sources_template()
    {
        return MaatExcel::download(new sources_template, 'source_template.xlsx');
    }

    public function downloadForm1601C($id)
    {
        $form = Form1601C::with(['withholding.organization', 'atc', 'sources'])->findOrFail($id);

        $pdf = PDF::loadView('tax_return.with_holding.1601C_pdf', compact('form'));

        return $pdf->download('BIR_Form_1601C_' . $form->filing_period . '.pdf');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $with_holding = WithHolding::findOrFail($id);
        $with_holding->delete();

        return redirect()->route('withholdings.index')->with('success', 'Withholding record deleted successfully!');
    }
}
