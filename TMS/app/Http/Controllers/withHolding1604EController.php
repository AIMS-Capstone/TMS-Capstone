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
use App\Models\Form1604E;
use App\Models\Contacts;
use App\Models\Payees;
use App\Models\Form1601EQAtcDetail;
use App\Models\atc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class withHolding1604EController extends Controller
{
    public function index1604E(Request $request)
    {
        $organizationId = session('organization_id');

        // Get the perPage value from the request, default to 5
        $perPage = $request->input('perPage', 5);
        $with_holdings = $this->getWithHoldings($organizationId, '1604E', $perPage);
        return view('tax_return.with_holding.1604E', compact('with_holdings'));
    }

    public function generate1604E(Request $request)
    {
        $request->validate([
            'year' => 'required|numeric|min:1900|max:' . date('Y'),
            'type' => 'required|string|in:1604E',
        ]);

        $organizationId = session('organization_id');

        if (!$organizationId) {
            return redirect()->back()->withErrors(['error' => 'Organization setup ID not found.']);
        }

        // Check for existing record
        $existingRecord = WithHolding::where('type', '1604E')
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
            'title' => 'Annual Information Return of Creditable Income Taxes Withheld (Expanded)',
            'year' => $request->year,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('with_holding.1604E', ['id' => $withHolding->id])
            ->with('success', 'Withholding Tax Return for 1604E has been generated.');
    }

    //Summary
    public function showSummary1604E($id)
    {
        // Fetch the withholding record for type 1604E
        $withHolding = WithHolding::where('id', $id)
            ->where('type', '1604E')
            ->with('creator', 'organization') // Fetch creator and organization for display
            ->firstOrFail();

        // Fetch related 1601EQ records for the same year
        $year = $withHolding->year;
        $orgSetupId = $withHolding->organization_id;

        // Fetch all Form1601EQ records
        $form1601EQRecords = Form1601EQ::where('org_setup_id', $orgSetupId)
            ->where('year', $year)
            ->get();

        // Map quarters with months
        $quarterNames = [
            1 => ['name' => '1st Quarter (January - March)'],
            2 => ['name' => '2nd Quarter (April - June)'],
            3 => ['name' => '3rd Quarter (July - September)'],
            4 => ['name' => '4th Quarter (October - December)'],
        ];

        // Ensure all quarters are present, even if no data exists
        $quarters = collect($quarterNames)->map(function ($quarterInfo, $quarter) use ($form1601EQRecords) {
            // Find all records for this quarter
            $records = $form1601EQRecords->where('quarter', $quarter);

            // Return aggregated data if records exist, otherwise default to 0
            return [
                'name' => $quarterInfo['name'],
                'taxes_withheld' => $records->sum('total_taxes_withheld'),
                'penalties' => $records->sum(function ($record) {
                    return $record->calculateTotalPenalties();
                }),
                'total_remitted' => $records->sum(function ($record) {
                    return $record->calculateTotalRemittances();
                }),
            ];
        });

        // Fetch creator's TIN (if available)
        $employeeTIN = $withHolding->creator->tin ?? 'N/A';

        // Pass data to the Blade view
        return view('tax_return.with_holding.1604E_summary', [
            'with_holding' => $withHolding,
            'quarters' => $quarters, // All quarters with default or aggregated values
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

    // 1604E Schedule 4 Show
    public function showSchedule41604E($withholdingId)
    {
        // Fetch the withholding record for 1604E
        $withHolding = WithHolding::findOrFail($withholdingId);

        // Get the withholding year
        $withholdingYear = $withHolding->year;

        // Fetch all payees associated with this withholding record
        $payees = Payees::with(['atc', 'contact'])
            ->where('withholding_id', $withholdingId)
            ->paginate(5);

        // Fetch all ATCs
        $atcs = Atc::all();

        // Fetch all contacts with their associated ATCs, filtered by the transaction year
        $contacts = Contacts::with(['transactions' => function ($query) use ($withholdingYear) {
            $query->whereYear('date', $withholdingYear); // Filter transactions by the year
        }, 'transactions.taxRows.atc'])
            ->get()
            ->map(function ($contact) {
                // Map each contact to their unique ATCs based on the filtered transactions
                $contact->atcs = $contact->transactions->flatMap(function ($transaction) {
                    return $transaction->taxRows->map->atc;
                })->unique('id');

                return $contact;
            });

        // Return the view with data
        return view('tax_return.with_holding.1604E_schedule4', [
            'withHolding' => $withHolding,
            'payees' => $payees,
            'atcs' => $atcs,
            'contacts' => $contacts,
        ]);
    }

    //1604E schedule 4 store
    public function storeSchedule41604E(Request $request, $withholdingId)
    {
        // Validate incoming data
        $request->validate([
            'payees' => 'required|array|min:1', // Ensure payees is an array and contains at least one entry
            'payees.*.contact_id' => 'required|exists:contacts,id', // Validate each contact_id
            'payees.*.atc_id' => 'required|exists:atcs,id', // Validate each atc_id
            'payees.*.amount' => 'required|numeric|min:0', // Validate each amount
        ]);

        try {
            // Loop through each payee in the request and create an entry
            foreach ($request->payees as $payeeData) {
                Payees::create([
                    'organization_id' => session('organization_id'), // Assuming organization ID is stored in session
                    'withholding_id' => $withholdingId,
                    'contact_id' => $payeeData['contact_id'],
                    'atc_id' => $payeeData['atc_id'],
                    'amount' => $payeeData['amount'], // Store the amount field
                ]);
            }

            // Log success
            Log::info('Payees added successfully', [
                'withholding_id' => $withholdingId,
                'payees' => $request->payees
            ]);

            return redirect()->back()->with('success', 'Payees have been successfully added.');
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Error adding payees', [
                'withholding_id' => $withholdingId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->withErrors(['error' => 'An error occurred while adding payees.']);
        }
    }

    public function createForm1604E($id)
    {
        $withHolding = WithHolding::where('id', $id)->where('type', '1604E')->firstOrFail();

        $orgSetup = OrgSetup::findOrFail(session('organization_id'));

        $form1604E = Form1604E::where('withholding_id', $id)->first();

        return view('tax_return.with_holding.1604E_form', compact('withHolding', 'orgSetup', 'form1604E'));
    }

    /**
     * Store a 1604C form.
     */
    public function storeForm1604E(Request $request, $id)
    {
        $request->validate([
            'year' => 'required|string|digits:4',
            'amended_return' => 'required|boolean',
            'number_of_sheets' => 'nullable|integer|min:1',
            'agent_category' => 'required|string|in:Government,Private',
            'agent_top' => 'nullable|required_if:agent_category,Private|string',
        ]);

        $organizationId = session('organization_id');

        $form1604E = Form1604E::updateOrCreate(
            ['withholding_id' => $id],
            array_merge($request->all(), [
                'org_setup_id' => $organizationId,
            ])
        );

        return redirect()->route('with_holding.1604E', ['id' => $form1604E->id])
            ->with('success', 'Form 1604E submitted successfully.');
    }

    private function getWithHoldings($organizationId, $type, $perPage = 5)
    {

        return WithHolding::with(['employee', 'employment', 'creator'])
            ->where('type', $type)
            ->where('organization_id', $organizationId)
            ->paginate($perPage);
            
    }

}
