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
use App\Models\atc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WithHoldingController extends Controller
{
    // etong parent withHoldingController na gamit ko sa 1601C
    public function index1601C(Request $request)
    {
        $organizationId = session('organization_id');

        // Get the perPage value from the request, default to 5
        $perPage = $request->input('perPage', 5);

        $with_holdings = $this->getWithHoldings($organizationId, '1601C', $perPage);
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

        return redirect()->route('with_holding.1601C_summary', ['id' => $withHolding->id])
            ->with('success', 'Withholding Tax Return for 1601C has been generated.');
    }

    public function destroy1601C(Request $request)
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

    //1601C summary
    public function showSummary1601C($id)
    {
        // Fetch the withholding tax record and ensure it is type 1601C
        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1601C')
            ->with(['employee', 'sources']) // Include sources for calculations
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
    public function showSources1601C($id)
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

        // Fetch sources with related employee and employment data
        $sources = $withHolding->sources()
            ->with(['employee.latestEmployment'])
            ->paginate(5);

        return view('tax_return.with_holding.1601C_sources', [
            'with_holding' => $withHolding,
            'sources' => $sources,
            'employees' => $employees,
        ]);
    }

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
            'tax_due' => 'nullable|numeric|min:0', // Validate tax_due field
        ]);

        $withHolding = WithHolding::findOrFail($id);

        // Fetch the employee's current employment
        $employment = Employment::where('employee_id', $request->employee_id)->first();

        if (!$employment) {
            return redirect()->back()->withErrors([
                'employee_id' => 'Employment record not found for this employee.',
            ]);
        }

        // Prevent duplicate records for the same withholding and payment date
        $existingSource = Source::where('withholding_id', $request->withholding_id)
            ->where('employee_id', $request->employee_id)
            ->where('payment_date', $request->payment_date)
            ->exists();

        if ($existingSource) {
            return redirect()->back()->withErrors([
                'payment_date' => 'A source record for this employee and date already exists.',
            ]);
        }

        // Compute Non-Taxable Benefits
        $nonTaxableBenefits = $this->computeNonTaxableBenefits($request);

        // Compute Taxable Compensation
        $taxableCompensation = $request->gross_compensation - $nonTaxableBenefits;

        // Determine Taxability and Tax Due
        $isTaxable = $taxableCompensation > 0 && $employment->employee_wage_status === 'Above Minimum Wage';
        $computedTaxDue = $isTaxable ? $this->computeTax($taxableCompensation) : 0;

        // Use tax_due from request or fallback to computed value
        $finalTaxDue = $request->tax_due ?? $computedTaxDue;

        // Create Source Record
        Source::create([
            'withholding_id' => $id,
            'employee_id' => $request->employee_id,
            'employment_id' => $employment->id,
            'payment_date' => $request->payment_date,
            'gross_compensation' => $request->gross_compensation,
            'taxable_compensation' => $taxableCompensation, // Store taxable compensation
            'tax_due' => $finalTaxDue,
            'statutory_minimum_wage' => $request->statutory_minimum_wage,
            'holiday_pay' => $request->holiday_pay,
            'overtime_pay' => $request->overtime_pay,
            'night_shift_differential' => $request->night_shift_differential,
            'hazard_pay' => $request->hazard_pay,
            'month_13_pay' => $request->month_13_pay,
            'de_minimis_benefits' => $request->de_minimis_benefits,
            'sss_gsis_phic_hdmf_union_dues' => $request->sss_gsis_phic_hdmf_union_dues,
            'other_non_taxable_compensation' => $request->other_non_taxable_compensation,
        ]);

        return redirect()->back()->with('success', 'Source record added successfully.');
    }

    //para sa non-taxable field
    private function computeNonTaxableBenefits($request)
    {
        return 
            ($request->statutory_minimum_wage ?? 0) +
            ($request->holiday_pay ?? 0) +
            ($request->overtime_pay ?? 0) +
            ($request->night_shift_differential ?? 0) +
            ($request->hazard_pay ?? 0) +
            ($request->month_13_pay ?? 0) +
            ($request->de_minimis_benefits ?? 0) +
            ($request->sss_gsis_phic_hdmf_union_dues ?? 0) +
            ($request->other_non_taxable_compensation ?? 0);
    }

    //hatdog na train law yan
    private function computeTax($taxableCompensation)
    {
        $brackets = [
            [250000, 0],         // Up to ₱250,000: 0% tax
            [400000, 0.15],      // ₱250,001 - ₱400,000: 15%
            [800000, 0.20],      // ₱400,001 - ₱800,000: 20%
            [2000000, 0.25],     // ₱800,001 - ₱2,000,000: 25%
            [8000000, 0.30],     // ₱2,000,001 - ₱8,000,000: 30%
            [PHP_INT_MAX, 0.35], // Over ₱8,000,000: 35%
        ];

        $taxDue = 0;
        $previousLimit = 0;

        foreach ($brackets as [$limit, $rate]) {
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

    //form fill up sa 1601C
    public function createForm1601C($id)
    {
        // Fetch the withholding tax record and ensure it is type 1601C
        $withHolding = WithHolding::findOrFail($id);

        // Retrieve organization setup and related data
        $orgSetup = OrgSetup::find(session('organization_id'));

        if (!$orgSetup) {
            return redirect()->back()->withErrors(['error' => 'Organization setup not found.']);
        }

        // Retrieve necessary data for the form
        $atcs = Atc::all(); // Fetch all Alphanumeric Tax Codes
        $sources = $withHolding->sources()->get(); // Sources related to the withholding record

        // Pass data to the blade template
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
        ]);

        // Fetch the withholding tax record
        $withHolding = WithHolding::findOrFail($id);

        $orgSetupId = $withHolding->organization_id ?? session('organization_id');
        if (!$orgSetupId) {
            return redirect()->back()->withErrors(['error' => 'Organization setup ID not found.']);
        }

        // Retrieve sources for calculations
        $sources = $withHolding->sources()->get();

        // Calculate remittance and penalties
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

        $filingPeriod = Carbon::createFromFormat('Y-m', $request->filing_period)->startOfMonth();

        // Create or update the 1601C form record
        Form1601C::updateOrCreate(
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
                'total_taxes_withheld' => $request->total_taxes_withheld,
                'total_compensation' => $sources->sum('gross_compensation'),
                'taxable_compensation' => $sources->sum('taxable_compensation'),
                'tax_due' => $totalTaxesWithheld,
                'adjustment' => $adjustment,
                'surcharge' => $surcharge,
                'interest' => $interest,
                'compromise' => $compromise,
                'other_remittances' => $otherRemittances,
                'total_amount_due' => $totalAmountStillDue,
            ]
        );

        return redirect()->route('with_holding.1601C_summary', ['id' => $id])
            ->with('success', '1601C Form has been successfully submitted.');
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
            'file' => 'required|mimes:csv,xlsx'
        ]);

        // Find the 1601C withholding record
        $withHolding = WithHolding::findOrFail($id);

        // Save the file temporarily
        $path = $request->file('file')->store('temp');

        // Pass withholdingId to the Blade view
        return view('tax_return.with_holding.1601C_sources', [
            'importPath' => $path,
            'withholdingId' => $id, // Pass withholdingId
        ]);
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
