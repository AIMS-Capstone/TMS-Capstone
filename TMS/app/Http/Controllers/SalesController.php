<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    // Sales-Book page
    public function sales(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $search = $request->input('search');

        // Query to fetch only draft sales transactions
        $query = Transactions::where('status', 'draft')
            ->where('transaction_type', 'Sales')
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

        return view('sales-book', compact('transactions'));
    }

    // Sales posted page
    public function posted(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $search = $request->input('search');

        // Query to fetch only posted sales transactions
        $query = Transactions::where('status', 'posted')
            ->where('transaction_type', 'Sales')
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

        return view('components.sales-posted', compact('transactions'));
    }

    // Update selected transactions to 'posted' status
    public function updateToPosted(Request $request)
    {
        // Validate the request to ensure 'ids' are provided and exist in the transactions table
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:transactions,id',
        ]);

        // Update the status of the selected transactions to 'posted'
        Transactions::whereIn('id', $request->ids)
            ->where('transaction_type', 'Sales')
            ->update(['status' => 'posted']);

        // Return a JSON response indicating success
        return response()->json(['message' => 'Selected transactions have been marked as posted.']);
    }

    // Update selected Sales to 'draft' status
    public function updateToDraft(Request $request)
    {
        // Validate the request to ensure 'ids' are provided and exist in the transactions table
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:transactions,id',
        ]);

        // Update the status of the selected transactions to 'draft'
        Transactions::whereIn('id', $request->ids)
            ->where('transaction_type', 'Sales')
            ->update(['status' => 'draft']);

        // Return a JSON response indicating success
        return response()->json(['message' => 'Selected transactions have been marked as draft.']);
    }
}
