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
        $perPage = $request->input('perPage', 5);

        // Start the query with the organization filter and type
        $query = WithHolding::where('type', '1601EQ')
            ->where('organization_id', $organizationId); // Filter by organization ID

        if ($request->has('search') && $request->search != '') {
            $search = trim($request->search); // Trim whitespace from the search term

            // Add search conditions
            $query->where(function ($q) use ($search) {
                $q->where('year', 'LIKE', '%' . $search . '%')
                    ->orWhere('quarter', 'LIKE', '%' . $search . '%');
                    })->orWhereHas('creator', function ($q) use ($search) {
                $q->where('first_name', 'LIKE', '%' . $search . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $search . '%');
            });
        }

        // Paginate the results
        $with_holdings = $query->paginate($perPage);

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
            activity('Session Error')
                ->causedBy(Auth::user())
                ->withProperties(['ip' => $request->ip(), 'browser' => $request->header('User-Agent')])
                ->log('Session expired while attempting to generate Form 1601EQ.');

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
                activity('Form Access')
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'form_id' => $existingForm->id,
                        'year' => $validated['year'],
                        'quarter' => $validated['quarter'],
                        'organization_id' => $organizationId,
                    ])
                    ->log("Attempted to generate 1601EQ, but form already exists. Redirecting to preview.");

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
                activity('Withholding Access')
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'withholding_id' => $withHolding->id,
                        'year' => $validated['year'],
                        'quarter' => $validated['quarter'],
                        'organization_id' => $organizationId,
                    ])
                    ->log("Redirecting to create form for existing withholding record (ID: {$withHolding->id}).");

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

            activity('Withholding Creation')
                ->performedOn($withHolding)
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $withHolding->id,
                    'year' => $validated['year'],
                    'quarter' => $validated['quarter'],
                    'organization_id' => $organizationId,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log("Created new withholding record for 1601EQ (ID: {$withHolding->id}).");

            return redirect()->route('with_holding.1601EQ_Qap', ['id' => $withHolding->id])
                ->with('success', 'Withholding Tax Return for 1601EQ has been generated.');
        } catch (\Exception $e) {
            activity('Form Generation Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'error_message' => $e->getMessage(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Error occurred while generating 1601EQ form.');

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
            activity('Deletion Request')
                ->causedBy(Auth::user())
                ->withProperties([
                    'requested_ids' => $ids,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Received request to delete Withholding records.');

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
                activity('Deletion Error')
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'requested_ids' => $ids,
                        'error_message' => $e->getMessage(),
                        'ip' => $request->ip(),
                        'browser' => $request->header('User-Agent'),
                    ])
                    ->log('An error occurred during the deletion of WithHolding records.');

                // Return error response
                return response()->json(['error' => 'An error occurred while deleting records'], 500);
            }
        }

    // Qap page to
    public function showQap1601EQ(Request $request, $withholdingId)
    {
        Log::info('Raw Request Data', $request->all());

        
        Log::info('Accessing QAP for 1601EQ', [
            'withholdingId' => $withholdingId,
            'requestParams' => $request->all(),
        ]);

        $existingForm = Form1601EQ::where('withholding_id', $withholdingId)->first();
        if ($existingForm) {
            Log::info('Redirecting to preview as the form already exists', [
                'formId' => $existingForm->id,
            ]);
            return redirect()->route('form1601EQ.preview', ['id' => $existingForm->id])
                ->with('info', 'QAP cannot be accessed as Form 1601EQ already exists. Redirecting to preview.');
        }

        $withHolding = WithHolding::findOrFail($withholdingId);
        $year = $withHolding->year;
        $quarter = $withHolding->quarter;

        switch ($quarter) {
            case 1: $startDate = "$year-01-01"; $endDate = "$year-03-31"; break;
            case 2: $startDate = "$year-04-01"; $endDate = "$year-06-30"; break;
            case 3: $startDate = "$year-07-01"; $endDate = "$year-09-30"; break;
            case 4: $startDate = "$year-10-01"; $endDate = "$year-12-31"; break;
            default: $startDate = null; $endDate = null;
        }

        Log::info('Quarter date range determined', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        $perPage = $request->input('perPage', 5);
        $search = trim($request->input('search', ''));

        Log::info('Search term received', ['search' => $search]);

        $taxRowsQuery = TaxRow::whereHas('transaction', function ($query) use ($withholdingId) {
            $query->where('withholding_id', $withholdingId)
                ->where('transaction_type', 'Purchase')
                ->where('QAP', 'active');
        });

        if (!empty($search)) {
            $taxRowsQuery->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', '%' . $search . '%')
                ->orWhereHas('transaction', function ($q) use ($search) {
                    $q->where('reference', 'LIKE', '%' . $search . '%')
                        ->orWhere('total_amount', 'LIKE', '%' . $search . '%')
                        ->orWhere('date', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('transaction.contactDetails', function ($q) use ($search) {
                    $q->where('bus_name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('atc', function ($q) use ($search) {
                    $q->where('tax_code', 'LIKE', '%' . $search . '%');
                });
            });

            Log::info('Search filters applied to taxRows', [
                'query' => $taxRowsQuery->toSql(),
                'bindings' => $taxRowsQuery->getBindings(),
            ]);
        }

        $taxRows = $taxRowsQuery->paginate($perPage);

        Log::info('Paginated taxRows', [
            'total' => $taxRows->total(),
            'currentPage' => $taxRows->currentPage(),
            'perPage' => $taxRows->perPage(),
        ]);

        $unassignedTransactionsQuery = Transactions::whereNull('withholding_id')
            ->where('transaction_type', 'Purchase')
            ->whereBetween('date', [$startDate, $endDate]);

        if (!empty($search)) {
            $unassignedTransactionsQuery->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', '%' . $search . '%')
                ->orWhere('total_amount', 'LIKE', '%' . $search . '%')
                ->orWhereHas('contactDetails', function ($q) use ($search) {
                    $q->where('bus_name', 'LIKE', '%' . $search . '%');
                });
            });

            Log::info('Search filters applied to unassignedTransactions', [
                'query' => $unassignedTransactionsQuery->toSql(),
                'bindings' => $unassignedTransactionsQuery->getBindings(),
            ]);
        }

        $unassignedTransactions = $unassignedTransactionsQuery->get();

        Log::info('Unassigned transactions retrieved', [
            'count' => $unassignedTransactions->count(),
        ]);

        return view('tax_return.with_holding.1601EQ_Qap', [
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
                'QAP' => 'active',
            ]);

            // Log the transaction assignment
            activity('Transaction Assignment')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $withholdingId,
                    'transaction_ids' => $transactionIds,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log("Transactions assigned to Withholding ID {$withholdingId} and marked QAP as active.");

            // Populate 1601EQ details
            $this->populate1601EQDetails($withholdingId);

            // Log the population of details
            activity('Form Population')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $withholdingId,
                    'transaction_count' => $transactionIds->count(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log("1601EQ details populated for Withholding ID {$withholdingId}.");

            return redirect()->route('with_holding.1601EQ_Qap', ['id' => $withholdingId])
                ->with('success', 'Transactions successfully assigned and ATC details populated.');
        } catch (\Exception $e) {
            // Log the error
            activity('Transaction Assignment Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $withholdingId,
                    'error_message' => $e->getMessage(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Failed to assign transactions to 1601EQ withholding.');

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

        // Validate input IDs
        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'No valid transaction IDs provided'], 400);
        }

        // Fetch the withholding IDs associated with the transactions
        $withholdingIds = Transactions::whereIn('id', $ids)->pluck('withholding_id')->unique();

        // Check if any of the transactions belong to a withholding with an existing 1601EQ form
        $restrictedIds = Form1601EQ::whereIn('withholding_id', $withholdingIds)->pluck('withholding_id')->toArray();

        if (!empty($restrictedIds)) {
            activity('Transaction Deactivation Restricted')
                ->causedBy(Auth::user())
                ->withProperties([
                    'restricted_withholding_ids' => $restrictedIds,
                    'transaction_ids' => $ids,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Deactivation of transactions restricted due to existing 1601EQ forms.');

            return response()->json(['error' => 'Cannot deactivate transactions. Form 1601EQ already exists for some transactions.'], 403);
        }

        // Mark transactions as inactive for QAP
        Transactions::whereIn('id', $ids)->update(['QAP' => 'inactive']);

        // Log the transaction deactivation
        activity('Transaction Deactivation')
            ->causedBy(Auth::user())
            ->withProperties([
                'transaction_ids' => $ids,
                'ip' => $request->ip(),
                'browser' => $request->header('User-Agent'),
            ])
            ->log('Transactions marked as inactive for QAP.');

        return response()->json(['success' => 'Selected transactions have been marked as inactive.']);
    }

    // Archive QAP Transactions
    public function archiveQAP(Request $request, $withholdingId)
    {

         Log::info('Raw Request Data', $request->all());

        
        Log::info('Accessing QAP for 1601EQ', [
            'withholdingId' => $withholdingId,
            'requestParams' => $request->all(),
        ]);

        $existingForm = Form1601EQ::where('withholding_id', $withholdingId)->first();
        if ($existingForm) {
            Log::info('Redirecting to preview as the form already exists', [
                'formId' => $existingForm->id,
            ]);
            return redirect()->route('form1601EQ.preview', ['id' => $existingForm->id])
                ->with('info', 'QAP cannot be accessed as Form 1601EQ already exists. Redirecting to preview.');
        }

        $withHolding = WithHolding::findOrFail($withholdingId);
        $year = $withHolding->year;
        $quarter = $withHolding->quarter;

        switch ($quarter) {
            case 1: $startDate = "$year-01-01"; $endDate = "$year-03-31"; break;
            case 2: $startDate = "$year-04-01"; $endDate = "$year-06-30"; break;
            case 3: $startDate = "$year-07-01"; $endDate = "$year-09-30"; break;
            case 4: $startDate = "$year-10-01"; $endDate = "$year-12-31"; break;
            default: $startDate = null; $endDate = null;
        }

        Log::info('Quarter date range determined', [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);

        $perPage = $request->input('perPage', 5);
        $search = trim($request->input('search', ''));

        Log::info('Search term received', ['search' => $search]);

        $taxRowsQuery = TaxRow::whereHas('transaction', function ($query) use ($withholdingId) {
            $query->where('withholding_id', $withholdingId)
                ->where('transaction_type', 'Purchase')
                ->where('QAP', 'inactive');
        });

        if (!empty($search)) {
            $taxRowsQuery->where(function ($q) use ($search) {
                $q->where('description', 'LIKE', '%' . $search . '%')
                ->orWhereHas('transaction', function ($q) use ($search) {
                    $q->where('reference', 'LIKE', '%' . $search . '%')
                        ->orWhere('total_amount', 'LIKE', '%' . $search . '%')
                        ->orWhere('date', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('transaction.contactDetails', function ($q) use ($search) {
                    $q->where('bus_name', 'LIKE', '%' . $search . '%');
                })
                ->orWhereHas('atc', function ($q) use ($search) {
                    $q->where('tax_code', 'LIKE', '%' . $search . '%');
                });
            });

            Log::info('Search filters applied to taxRows', [
                'query' => $taxRowsQuery->toSql(),
                'bindings' => $taxRowsQuery->getBindings(),
            ]);
        }

        $taxRows = $taxRowsQuery->paginate($perPage);

        Log::info('Paginated taxRows', [
            'total' => $taxRows->total(),
            'currentPage' => $taxRows->currentPage(),
            'perPage' => $taxRows->perPage(),
        ]);

        $unassignedTransactionsQuery = Transactions::whereNull('withholding_id')
            ->where('transaction_type', 'Purchase')
            ->whereBetween('date', [$startDate, $endDate]);

        if (!empty($search)) {
            $unassignedTransactionsQuery->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', '%' . $search . '%')
                ->orWhere('total_amount', 'LIKE', '%' . $search . '%')
                ->orWhereHas('contactDetails', function ($q) use ($search) {
                    $q->where('bus_name', 'LIKE', '%' . $search . '%');
                });
            });

            Log::info('Search filters applied to unassignedTransactions', [
                'query' => $unassignedTransactionsQuery->toSql(),
                'bindings' => $unassignedTransactionsQuery->getBindings(),
            ]);
        }

        $unassignedTransactions = $unassignedTransactionsQuery->get();

        Log::info('Unassigned transactions retrieved', [
            'count' => $unassignedTransactions->count(),
        ]);

        return view('tax_return.with_holding.1601EQ_Qap', [
            'withHolding' => $withHolding,
            'taxRows' => $taxRows,
            'unassignedTransactions' => $unassignedTransactions,
        ]);
    }

    public function destroyQapTransaction(Request $request)
    {
        $ids = $request->input('ids');

        // Validate transaction IDs
        if (!$ids || !is_array($ids)) {
            return response()->json(['error' => 'No valid transaction IDs provided'], 400);
        }

        try {
            // Unassign transactions from QAP
            Transactions::whereIn('id', $ids)
                ->update(['withholding_id' => null]);

            // Log the unassignment activity
            activity('Transaction Unassignment')
                ->causedBy(Auth::user())
                ->withProperties([
                    'transaction_ids' => $ids,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Transactions were unassigned from QAP.');

            return response()->json(['success' => 'Selected transactions have been unassigned from QAP.']);
        } catch (\Exception $e) {
            // Log any errors encountered during the unassignment process
            activity('Transaction Unassignment Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'transaction_ids' => $ids,
                    'error_message' => $e->getMessage(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Failed to unassign transactions from QAP.');

            return response()->json(['error' => 'An error occurred while unassigning transactions.'], 500);
        }
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
                'category' => 'required|boolean',

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

            // Log the validation success
            activity('Form Validation')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $id,
                    'validated_data' => $validated,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Validation succeeded for Form 1601EQ submission.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            activity('Validation Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $id,
                    'errors' => $e->errors(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Validation failed for Form 1601EQ submission.');

            return redirect()->back()->withErrors($e->errors())->withInput();
        }

        $withHolding = WithHolding::findOrFail($id);
        $orgSetupId = $withHolding->organization_id ?? session('organization_id');

        // Prevent duplicate form submission
        $existingForm = Form1601EQ::where('withholding_id', $id)
            ->where('year', $validated['year'])
            ->where('quarter', $validated['quarter'])
            ->first();

        if ($existingForm) {
            activity('Duplicate Form Check')
                ->causedBy(Auth::user())
                ->withProperties([
                    'existing_form_id' => $existingForm->id,
                    'year' => $validated['year'],
                    'quarter' => $validated['quarter'],
                    'withholding_id' => $id,
                ])
                ->log('Duplicate Form 1601EQ detected.');

            return redirect()->route('form1601EQ.preview', ['id' => $existingForm->id])
                ->with('info', 'Form 1601EQ for this period already exists. Redirecting to preview.');
        }

        // Calculate totals
        $totalRemittancesMade =
            ($validated['remittances_1st_month'] ?? 0) +
            ($validated['remittances_2nd_month'] ?? 0) +
            ($validated['remitted_previous'] ?? 0) +
            ($validated['other_payments'] ?? 0);

        $totalTaxesWithheld = Form1601EQAtcDetail::where('withholding_id', $id)->sum('tax_withheld');
        $taxStillDue = max(0, $totalTaxesWithheld - $totalRemittancesMade);
        $overRemittance = max(0, $totalRemittancesMade - $totalTaxesWithheld);
        $totalPenalties = ($validated['surcharge'] ?? 0) + ($validated['interest'] ?? 0) + ($validated['compromise'] ?? 0);
        $totalAmountDue = $taxStillDue + $totalPenalties;

        // Log calculation results
        activity('Form Calculation')
            ->causedBy(Auth::user())
            ->withProperties([
                'total_remittances' => $totalRemittancesMade,
                'total_taxes_withheld' => $totalTaxesWithheld,
                'tax_still_due' => $taxStillDue,
                'over_remittance' => $overRemittance,
                'total_penalties' => $totalPenalties,
                'total_amount_due' => $totalAmountDue,
            ])
            ->log('Calculated totals for Form 1601EQ.');

        // Create or update the form
        $form = Form1601EQ::updateOrCreate(
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

        // Log form creation
        activity('Form Submission')
            ->performedOn($form)
            ->causedBy(Auth::user())
            ->withProperties([
                'form_id' => $form->id,
                'withholding_id' => $id,
                'form_data' => $form->toArray(),
            ])
            ->log('Form 1601EQ created or updated successfully.');

        return redirect()->route('form1601EQ.preview', ['id' => $form->id])
            ->with('success', 'Form 1601EQ submitted successfully.');
    }

    public function previewForm1601EQ($formId)
    {
        // Fetch the form and its related withholding record
        $form = Form1601EQ::with(['organization', 'atcDetails.atc'])->findOrFail($formId);
        $withHolding = $form->withholding;

        // Pass both to the view
        return view('tax_return.with_holding.1601EQ_preview', compact('form', 'withHolding'));
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

            // Log the validation success
            activity('Form Validation')
                ->causedBy(Auth::user())
                ->withProperties([
                    'form_id' => $form->id,
                    'validated_data' => $validated,
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Validation succeeded for updating Form 1601EQ.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            activity('Validation Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'form_id' => $form->id,
                    'errors' => $e->errors(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Validation failed for updating Form 1601EQ.');

            return redirect()->back()->withErrors($e->errors())->withInput();
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

            // Track original form data for logging
            $originalAttributes = $form->getOriginal();

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

            // Log the update success
            activity('Form Update')
                ->performedOn($form)
                ->causedBy(Auth::user())
                ->withProperties([
                    'form_id' => $form->id,
                    'original_data' => $originalAttributes,
                    'updated_data' => $form->getAttributes(),
                    'calculation_results' => [
                        'total_remittances_made' => $totalRemittancesMade,
                        'tax_still_due' => $taxStillDue,
                        'total_penalties' => $totalPenalties,
                        'total_amount_due' => $totalAmountDue,
                    ],
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Form 1601EQ updated successfully.');

            return redirect()->route('form1601EQ.preview', ['id' => $form->id])
                ->with('success', '1601EQ Form updated successfully.');
        } catch (\Exception $e) {
            // Log the update error
            activity('Update Error')
                ->causedBy(Auth::user())
                ->withProperties([
                    'form_id' => $form->id,
                    'error_message' => $e->getMessage(),
                    'ip' => $request->ip(),
                    'browser' => $request->header('User-Agent'),
                ])
                ->log('Error occurred while updating Form 1601EQ.');

            return redirect()->back()->withErrors(['error' => 'Failed to update form. Please try again.']);
        }
    }

    public function downloadForm1601EQ($id)
    {
        // Fetch the form and related data
        $form = Form1601EQ::with(['organization'])->findOrFail($id);

        // Get organization linked to the form
        $organization = $form->organization;
        
        // Log the download activity
        activity('Form Download')
            ->performedOn($form)
            ->causedBy(Auth::user())
            ->withProperties([
                'form_id' => $form->id,
                'year' => $form->year,
                'quarter' => $form->quarter,
                'organization_id' => $organization->id ?? null,
                'ip' => request()->ip(),
                'browser' => request()->header('User-Agent'),
            ])
            ->log("Form 1601EQ (ID: {$form->id}) was downloaded.");

        // Generate PDF from Blade template
        $pdf = Pdf::loadView('tax_return.with_holding.1601EQ_pdf', compact('form', 'organization'));

        // Customize PDF filename
        $filename = '1601EQ_Form_' . $form->year . '_Q' . $form->quarter . '.pdf';

        // Return the PDF for download
        return $pdf->download($filename);
    }

    public function markForm1601EQFiled($formId)
    {
        try {
            $form = Form1601EQ::findOrFail($formId);
            $withHolding = $form->withholding;

            $withHolding->update(['status' => 'Filed']);

            activity('Status Update')
                ->performedOn($withHolding)
                ->causedBy(Auth::user())
                ->withProperties([
                    'withholding_id' => $withHolding->id,
                    'form_id' => $form->id,
                    'previous_status' => $withHolding->getOriginal('status'),
                    'new_status' => 'Filed',
                ])
                ->log("WithHolding ID {$withHolding->id} and Form1601EQ ID {$form->id} marked as Filed.");

            return redirect()->route('form1601EQ.preview', ['withholding_id' => $withHolding->id])
                ->with('success', 'Form successfully marked as Filed.');
        } catch (\Exception $e) {
            activity('Status Update Error')
                ->withProperties([
                    'form_id' => $formId,
                    'error' => $e->getMessage(),
                ])
                ->log('An error occurred while marking Form1601EQ as Filed.');

            return redirect()->back()->withErrors(['error' => 'Failed to mark as Filed.']);
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
