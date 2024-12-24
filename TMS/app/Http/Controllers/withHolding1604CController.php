<?php

namespace App\Http\Controllers;

use App\Models\Form1601C;
use App\Models\Form1604C;
use App\Models\OrgSetup;
use App\Models\Source;
use App\Models\WithHolding;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class withHolding1604CController extends Controller
{
    /**
     * Display the list of 1604C withholdings for the organization.
     */
    public function index1604C(Request $request)
    {
        $organizationId = session('organization_id');

        if (!$organizationId) {
            return redirect()->back()->withErrors(['error' => 'Organization setup ID not found.']);
        }

        $with_holdings = $this->getWithHoldings($organizationId, '1604C');

        return view('tax_return.with_holding.1604C', compact('with_holdings'));
    }

    /**
     * Generate a new 1604C record for the given year.
     */
    public function generate1604C(Request $request)
    {
        $request->validate([
            'year' => 'required|numeric|min:1900|max:' . date('Y'),
            'type' => 'required|string|in:1604C',
        ]);

        $organizationId = session('organization_id');

        if (!$organizationId) {
            return redirect()->back()->withErrors(['error' => 'Organization setup ID not found.']);
        }

        // Check for existing record
        $existingRecord = WithHolding::where('type', '1604C')
            ->where('year', $request->year)
            ->where('organization_id', $organizationId)
            ->exists();

        if ($existingRecord) {
            return redirect()->back()->withErrors(['error' => 'A record for this year already exists.']);
        }

        // Create new withholding record
        $withHolding = WithHolding::create([
            'type' => $request->type,
            'organization_id' => $organizationId,
            'title' => 'Annual Information Return of Income Taxes Withheld on Compensation',
            'year' => $request->year,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('with_holding.1604C', ['id' => $withHolding->id])
            ->with('success', 'Withholding Tax Return for 1604C has been generated.');
    }

    /**
     * Display the remittance summary for a specific 1604C withholding.
     */
    public function showRemittance1604C(Request $request, $id)
    {
        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1604C')
            ->firstOrFail();

        $year = $withHolding->year;

        // Fetch monthly data for the year
$monthlyData = Form1601C::where('org_setup_id', $withHolding->organization_id)
    ->where('withholding_id', $withHolding->id)
    ->whereYear('filing_period', $year)
    ->get();

Log::debug('Raw Monthly Data:', $monthlyData->toArray());

$monthlyData->each(function ($item) {
    Log::debug('Parsed Month:', [
        'filing_period' => $item->filing_period,
        'month' => Carbon::parse($item->filing_period)->format('F')
    ]);
});

$groupedData = $monthlyData->groupBy(function ($item) {
    return Carbon::parse($item->filing_period)->format('F');
});

Log::debug('Grouped Monthly Data:', $groupedData->toArray());


        // Aggregate totals by month
        $monthlyTotals = [];
        foreach ($monthlyData as $month => $records) {
            $monthlyTotals[$month] = [
                'taxes_withheld' => $records->sum('tax_due'),
                'adjustment' => $records->sum('adjustment'),
                'penalties' => $records->sum('surcharge') + $records->sum('interest') + $records->sum('compromise'),
                'total_amount_remitted' => $records->sum('total_amount_due'),
            ];
        }

        return view('tax_return.with_holding.1604C_remittances', [
            'with_holding' => $withHolding,
            'monthly_totals' => $monthlyTotals,
            'year' => $year,
        ]);
    }

    /**
     * Display schedule 1 (above minimum wage earners) for a specific 1604C withholding.
     */
    public function show1604Cschedule1(Request $request, $id)
    {
        return $this->fetchSchedule($id, 'Above Minimum Wage Earner', 'tax_return.with_holding.1604C_schedule1');
    }

    /**
     * Display schedule 2 (minimum wage earners) for a specific 1604C withholding.
     */
    public function show1604Cschedule2(Request $request, $id)
    {
        return $this->fetchSchedule($id, 'Minimum Wage Earner', 'tax_return.with_holding.1604C_schedule2');
    }

    /**
     * Handle fetching schedules (above or minimum wage earners).
     */
    private function fetchSchedule($id, $wageStatus, $view)
    {
        $organizationId = session('organization_id');

        $withHolding = WithHolding::where('id', $id)
            ->where('organization_id', $organizationId)
            ->where('type', '1604C')
            ->firstOrFail();

        $sourcesQuery = Source::with(['employee', 'employment'])
            ->whereHas('withholding', function ($query) use ($organizationId, $withHolding) {
                $query->where('type', '1601C')
                    ->where('organization_id', $organizationId)
                    ->whereYear('created_at', $withHolding->year);
            })
            ->whereHas('employment', function ($query) use ($wageStatus) {
                $query->where('employee_wage_status', $wageStatus);
            });

        $paginatedSources = $sourcesQuery->paginate(5);

        $aggregatedData = collect($paginatedSources->items())->groupBy('employee_id')->map(function ($sources) {
            $employee = $sources->first()->employee;
            return [
                'employee' => $employee,
                'gross_compensation' => $sources->sum('gross_compensation'),
                'non_taxable_compensation' => $sources->sum(function ($source) {
                    return $source->statutory_minimum_wage +
                    $source->holiday_pay +
                    $source->overtime_pay +
                    $source->night_shift_differential +
                    $source->hazard_pay +
                    $source->month_13_pay +
                    $source->de_minimis_benefits +
                    $source->sss_gsis_phic_hdmf_union_dues +
                    $source->other_non_taxable_compensation;
                }),
                'taxable_compensation' => $sources->sum('taxable_compensation'),
                'tax_due' => $sources->sum('tax_due'),
            ];
        });

        return view($view, [
            'with_holding' => $withHolding,
            'aggregatedData' => $aggregatedData,
            'paginatedSources' => $paginatedSources,
        ]);
    }

    /**
     * Create a 1604C form.
     */
    public function createForm1604C($id)
    {
        $withHolding = WithHolding::where('id', $id)->where('type', '1604C')->firstOrFail();

        $orgSetup = OrgSetup::findOrFail(session('organization_id'));

        $form1604C = Form1604C::where('withholding_id', $id)->first();

        return view('tax_return.with_holding.1604C_form', compact('withHolding', 'orgSetup', 'form1604C'));
    }

    /**
     * Store a 1604C form.
     */
    public function storeForm1604C(Request $request, $id)
    {
        $request->validate([
            'year' => 'required|string|digits:4',
            'amended_return' => 'required|boolean',
            'number_of_sheets' => 'nullable|integer|min:1',
            'agent_category' => 'required|string|in:Government,Private',
            'agent_top' => 'nullable|required_if:agent_category,Private|string',
            'over_remittances' => 'required|boolean',
            'refund_date' => 'nullable|date|required_if:over_remittances,1',
            'total_over_remittances' => 'nullable|numeric|min:0',
            'first_month_remittances' => 'required|date_format:Y-m',
        ]);

        $organizationId = session('organization_id');

        $form1604C = Form1604C::updateOrCreate(
            ['withholding_id' => $id],
            array_merge($request->all(), [
                'org_setup_id' => $organizationId,
                'first_month_remittances' => $request->input('first_month_remittances') . '-01',
            ])
        );

        return redirect()->route('with_holding.1604C', ['id' => $form1604C->id])
            ->with('success', 'Form 1604C submitted successfully.');
    }

    /**
     * Fetch withholdings for a specific type and organization.
     */
    private function getWithHoldings($organizationId, $type)
    {
        return WithHolding::with(['sources.employee', 'sources.employment'])
            ->where('type', $type)
            ->where('organization_id', $organizationId)
            ->paginate(5);
    }
}
