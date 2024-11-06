<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GeneralLedgerExport;
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

    public function exportExcel(Request $request)
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
            new GeneralLedgerExport($year, $month, $startMonth, $endMonth, $status, $period, $quarter),
            'general_ledger.xlsx'
        );
    }

}
