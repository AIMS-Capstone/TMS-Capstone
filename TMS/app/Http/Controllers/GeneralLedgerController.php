<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller
{
    public function ledger(Request $request)
{
    $year = $request->input('year');
    $month = $request->input('month');
    $startMonth = $request->input('start_month');
    $endMonth = $request->input('end_month');
    $search = $request->input('search');
    $status = $request->input('status');

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
        $query->where('status', $status);
    }

    if ($search) {
        $query->whereHas('taxRows.coaAccount', function ($q) use ($search) {
            $q->where('code', 'LIKE', "%{$search}%")
              ->orWhere('name', 'LIKE', "%{$search}%")
              ->orWhere('type', 'LIKE', "%{$search}%");
        })
        ->orWhere('inv_number', 'LIKE', "%{$search}%")
        ->orWhere('reference', 'LIKE', "%{$search}%");
    }

    $transactions = $query->get();

    // Summing debit and credit from taxRows
    $totalDebit = $transactions->pluck('taxRows')->flatten()->sum('debit');
    $totalCredit = $transactions->pluck('taxRows')->flatten()->sum('credit');

    return view('general-ledger', compact('transactions', 'totalDebit', 'totalCredit'));
}

}
