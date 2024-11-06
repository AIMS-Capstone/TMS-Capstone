<?php

namespace App\Http\Controllers;

use App\Models\Transactions;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GeneralJournalExport;
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

        $query = Transactions::with('taxRows.coaAccount')
        ->where('transaction_type', 'Journal');


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

    public function exportJournalExcel(Request $request)
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
            new GeneralJournalExport($year, $month, $startMonth, $endMonth, $status, $period, $quarter),
            'general_journal.xlsx'
        );
    }

}
