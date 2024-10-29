<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;

class GeneralJournalController extends Controller
{
    public function journal(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $search = $request->input('search');
        $status = $request->input('status'); // New status filter

        $query = Transactions::with('taxRows.coaAccount');

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

        if ($status) {
            $query->where('status', $status); // Apply status filter
        }

        // Apply search filter
        if ($search) {
            $query->whereHas('taxRows.coaAccount', function ($q) use ($search) {
                $q->where('bus_name', 'LIKE', "%{$search}%")
                    ->orWhere('contact_address', 'LIKE', "%{$search}%");
            })
                ->orWhere('inv_number', 'LIKE', "%{$search}%")
                ->orWhere('reference', 'LIKE', "%{$search}%");
        }

        $transactions = $query->paginate(5);


        return view('general-journal', compact('transactions'));
    }

}
