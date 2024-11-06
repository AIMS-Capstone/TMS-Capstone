<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PurchaseBookExport;
use App\Exports\PurchaseBookPostedExport;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    //Purchase-Book page
    public function purchase(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $search = $request->input('search');

        // Query to fetch only draft purchase transactions
        $query = Transactions::where('status', 'draft')
            ->where('transaction_type', 'Purchase')
            ->with('contactDetails');

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

        $transactions = $query->paginate(5);

        return view('purchase-book', compact('transactions'));
    }

    // Purchase posted page
    public function posted(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $search = $request->input('search');

        // Query to fetch only posted purchase transactions
        $query = Transactions::where('status', 'posted')
            ->where('transaction_type', 'Purchase')
            ->with('contactDetails');

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

        $transactions = $query->paginate(5);


        return view('components.purchase-posted', compact('transactions'));
    }

    // Update selected purchase to 'posted' status
    public function updateToPosted(Request $request)
    {
        // Validate the request to ensure 'ids' are provided and exist in the transactions table
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:transactions,id',
        ]);

        // Update the status of the selected transactions to 'posted'
        Transactions::whereIn('id', $request->ids)
            ->where('transaction_type', 'Purchase')
            ->update(['status' => 'posted']);

        // Return a JSON response indicating success
        return response()->json(['message' => 'Selected transactions have been marked as posted.']);
    }

    // Update selected transactions to 'draft' status
    public function updateToDraft(Request $request)
    {
        // Validate the request to ensure 'ids' are provided and exist in the transactions table
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:transactions,id',
        ]);

        // Update the status of the selected transactions to 'draft'
        Transactions::whereIn('id', $request->ids)
            ->where('transaction_type', 'Purchase')
            ->update(['status' => 'draft']);

        // Return a JSON response indicating success
        return response()->json(['message' => 'Selected transactions have been marked as draft.']);
    }
    public function exportPurchaseBook(Request $request)
    {
         $year = $request->query('year');
        $period = $request->query('period', 'annually');
        $month = ($period === 'monthly') ? $request->query('month') : null;
        $quarter = ($period === 'quarterly') ? $request->query('quarter') : null;
        $status = $request->query('status', 'draft');

        // Calculate start and end months if quarterly period is selected
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

        // Pass period, startMonth, and endMonth along with other parameters to the export
        return Excel::download(
            new PurchaseBookExport($year, $month, $startMonth, $endMonth, $status, $period, $quarter),
            'purchase_book.xlsx'
        );
    }

    public function exportPurchaseBookPosted(Request $request)
    {
         $year = $request->query('year');
        $period = $request->query('period', 'annually');
        $month = ($period === 'monthly') ? $request->query('month') : null;
        $quarter = ($period === 'quarterly') ? $request->query('quarter') : null;
        $status = $request->query('status', 'posted');

        // Calculate start and end months if quarterly period is selected
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

        // Pass period, startMonth, and endMonth along with other parameters to the export
        return Excel::download(
            new PurchaseBookPostedExport($year, $month, $startMonth, $endMonth, $status, $period, $quarter),
            'purchase_book_posted.xlsx'
        );
    }

}
