<?php

namespace App\Http\Controllers;

use App\Exports\CashDisbursementExport;
use App\Exports\CashDisbursementPostedExport;
use App\Models\OrgSetup;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class CashDisbursementController extends Controller
{
    // Helper function to retrieve organization ID from session
    protected function getOrganizationId(Request $request)
    {
        $organizationId = $request->session()->get('organization_id');
        if (!$organizationId) {
            abort(403, 'Organization ID not set in session');
        }

        return $organizationId;
    }

    // Cash disbursement-Book page
    public function cashDisbursement(Request $request)
    {
        $organizationId = $this->getOrganizationId($request); // Ensure organization ID is in session

        $year = $request->input('year');
        $month = $request->input('month');
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $search = $request->input('search');
        $perPage = $request->input('perPage', 5);

        $query = Transactions::where('status', 'draft')
            ->where('Paidstatus', 'Paid')
            ->where('transaction_type', 'Purchase')
            ->where('organization_id', $organizationId) // Filter by organization
            ->with('contactDetails', 'taxRows.coaAccount', 'taxRows.atc', 'taxRows.taxType');

        if ($year) {
            $query->whereYear('date', $year);
        }

        if ($month && $year) {
            $query->whereMonth('date', $month);
        }

        if ($startMonth && $endMonth && $year) {
            $query->whereMonth('date', '>=', $startMonth)
                ->whereMonth('date', '<=', $endMonth);
        }

        // Apply search filter
        if ($search) {
            $query->whereHas('contactDetails', function ($q) use ($search) {
                $q->where('bus_name', 'LIKE', "%{$search}%")
                    ->orWhere('contact_address', 'LIKE', "%{$search}%");
            })
                ->orWhere('inv_number', 'LIKE', "%{$search}%")
                ->orWhere('reference', 'LIKE', "%{$search}%");
        }

        $transactions = $query->paginate($perPage);

        return view('cash-disbursement', compact('transactions'));
    }

    // Cash disbursement posted page
    public function posted(Request $request)
    {
        $organizationId = $this->getOrganizationId($request); // Ensure organization ID is in session

        $year = $request->input('year');
        $month = $request->input('month');
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $search = $request->input('search');
        $perPage = $request->input('perPage', 5);

        // Query to fetch only posted Cash disbursement transactions for the organization
        $query = Transactions::where('status', 'posted')
            ->where('Paidstatus', 'Paid')
            ->where('transaction_type', 'Purchase')
            ->where('organization_id', $organizationId) // Filter by organization
            ->with('contactDetails', 'taxRows.coaAccount', 'taxRows.atc', 'taxRows.taxType');

        if ($year) {
            $query->whereYear('date', $year);
        }

        if ($month && $year) {
            $query->whereMonth('date', $month);
        }

        if ($startMonth && $endMonth && $year) {
            $query->whereMonth('date', '>=', $startMonth)
                ->whereMonth('date', '<=', $endMonth);
        }

        if ($search) {
            $query->whereHas('contactDetails', function ($q) use ($search) {
                $q->where('bus_name', 'LIKE', "%{$search}%")
                    ->orWhere('contact_address', 'LIKE', "%{$search}%");
            })
                ->orWhere('inv_number', 'LIKE', "%{$search}%")
                ->orWhere('reference', 'LIKE', "%{$search}%");
        }

        $transactions = $query->paginate($perPage);

        return view('components.cash-disbursement-posted', compact('transactions'));
    }

    // Update selected Cash disbursement to 'posted' status
    public function updateToPosted(Request $request)
    {
        $organizationId = $this->getOrganizationId($request); // Ensure organization ID is in session

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:transactions,id',
        ]);

        $transactions = Transactions::whereIn('id', $request->ids)
            ->where('Paidstatus', 'Paid')
            ->where('transaction_type', 'Purchase')
            ->where('organization_id', $organizationId)
            ->get();

        foreach ($transactions as $transaction) {
            $oldStatus = $transaction->status;

            // Update to posted
            $transaction->update(['status' => 'posted']);

            activity('Cash Disbursement')
                ->performedOn($transaction)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => $transaction->organization_id,
                    'old_status' => $oldStatus,
                    'new_status' => 'posted',
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("Cash Disbursement #{$transaction->inv_number} was updated to posted.");
        }

        return response()->json(['message' => 'Selected transactions have been marked as posted.']);
    }

    // Update selected transactions to 'draft' status
    public function updateToDraft(Request $request)
    {
        $organizationId = $this->getOrganizationId($request); // Ensure organization ID is in session

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:transactions,id',
        ]);

        $transactions = Transactions::whereIn('id', $request->ids)
            ->where('Paidstatus', 'Paid')
            ->where('transaction_type', 'Purchase')
            ->where('organization_id', $organizationId)
            ->get();

        foreach ($transactions as $transaction) {
            $oldStatus = $transaction->status;

            // Update to draft
            $transaction->update(['status' => 'draft']);
            
            activity('Cash Disbursement')
                ->performedOn($transaction)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => $transaction->organization_id,
                    'old_status' => $oldStatus,
                    'new_status' => 'draft',
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("Cash Disbursement #{$transaction->inv_number} was updated to draft.");
        }

        return response()->json(['message' => 'Selected transactions have been marked as draft.']);
    }

    public function exportCashDisbursement(Request $request)
    {
        $organizationId = $this->getOrganizationId($request); // Retrieve organization ID
        $organization = OrgSetup::find($organizationId); // Get organization details

        if (!$organization) {
            abort(404, 'Organization not found.');
        }

        $transactions = Transactions::where('organization_id', $organizationId)->get();

        // Assuming each organization has a contact associated
        $contact = $transactions->first()->contactDetails ?? null;

        $year = $request->query('year');
        $period = $request->query('period', 'annually');
        $month = ($period === 'monthly') ? $request->query('month') : null;
        $quarter = ($period === 'quarterly') ? $request->query('quarter') : null;
        $status = $request->query('status', 'draft');

        $startMonth = null;
        $endMonth = null;

        if ($period === 'quarterly' && $quarter) {
            switch ($quarter) {
                case 'Q1':
                    $startMonth = '01';
                    $endMonth = '03';
                    break;
                case 'Q2':
                    $startMonth = '04';
                    $endMonth = '06';
                    break;
                case 'Q3':
                    $startMonth = '07';
                    $endMonth = '09';
                    break;
                case 'Q4':
                    $startMonth = '10';
                    $endMonth = '12';
                    break;
            }
        }

        $filename = "CashDisbursement_{$organization->registration_name}_{$year}_{$month}.xlsx";
        return Excel::download(
            new CashDisbursementExport(                
                $year,
                $month,
                $startMonth,
                $endMonth,
                $status,
                $period,
                $quarter,
                $contact,
                $organization),
            $filename
        );
    }

    public function exportCashDisbursementPosted(Request $request)
    {
        $organizationId = $this->getOrganizationId($request); // Retrieve organization ID
        $organization = OrgSetup::find($organizationId); // Get organization details

        if (!$organization) {
            abort(404, 'Organization not found.');
        }

        $transactions = Transactions::where('organization_id', $organizationId)->get();

        // Assuming each organization has a contact associated
        $contact = $transactions->first()->contactDetails ?? null;

        $year = $request->query('year');
        $period = $request->query('period', 'annually');
        $month = ($period === 'monthly') ? $request->query('month') : null;
        $quarter = ($period === 'quarterly') ? $request->query('quarter') : null;
        $status = $request->query('status', 'posted');

        $startMonth = null;
        $endMonth = null;

        if ($period === 'quarterly' && $quarter) {
            switch ($quarter) {
                case 'Q1':
                    $startMonth = '01';
                    $endMonth = '03';
                    break;
                case 'Q2':
                    $startMonth = '04';
                    $endMonth = '06';
                    break;
                case 'Q3':
                    $startMonth = '07';
                    $endMonth = '09';
                    break;
                case 'Q4':
                    $startMonth = '10';
                    $endMonth = '12';
                    break;
            }
        }

        $filename = "CashDisbursementPosted_{$organization->registration_name}_{$year}_{$month}.xlsx";
        return Excel::download(
            new CashDisbursementPostedExport(                
                $year,
                $month,
                $startMonth,
                $endMonth,
                $status,
                $period,
                $quarter,
                $contact,
                $organization),
            $filename
        );
    }
}
