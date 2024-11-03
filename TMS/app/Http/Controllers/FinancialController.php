<?php

namespace App\Http\Controllers;

use App\Exports\FinancialReportExport;
use App\Models\Transactions; 
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class FinancialController extends Controller
{

    private function getQuarterMonths($quarter)
{
    switch ($quarter) {
        case 'Q1':
            return [1, 3];  // January to March
        case 'Q2':
            return [4, 6];  // April to June
        case 'Q3':
            return [7, 9];  // July to September
        case 'Q4':
            return [10, 12];  // October to December
        default:
            return [1, 12];  // Full year as default if quarter is invalid
    }
}
private function getFinancialData(Request $request)
{
    // Default values for year, month, and quarter
    $year = $request->input('year', now()->year);
    $month = $request->input('month', now()->month);
    $quarter = $request->input('quarter', 'Q' . ceil(now()->month / 3));
    $period = $request->input('period', 'annually');  
    $status = $request->input('status', 'draft');


    $query = Transactions::with(['taxRows.coaAccount']);

    $query->whereYear('date', $year);


        if ($period === 'monthly' && $month) {
            $query->whereMonth('date', $month);
        } elseif ($period === 'quarterly' && $quarter) {
            [$startMonth, $endMonth] = $this->getQuarterMonths($quarter);
            $query->whereMonth('date', '>=', $startMonth)
                ->whereMonth('date', '<=', $endMonth);
        }

    if ($status) {
    $query->where('status', $status);
    }

    $transactions = $query->get();

        // Aggregating revenue and cost of sales
        $totalRevenue = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.type', 'Revenue'))->sum('amount') ?? 0;
        $totalCostOfSales = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.type', 'Sales'))->sum('amount') ?? 0;

        // Manually summing specific sub-expenses
        $rentalTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Rental')->where('coaAccount.type', 'Expense'))->sum('amount') ?? 0;
        $depreciationTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Depreciation')->where('coaAccount.type', 'Expense'))->sum('amount') ?? 0;
        $managementFeeTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Management and Consultancy Fee')->where('coaAccount.type', 'Expense'))->sum('amount') ?? 0;
        $officeSuppliesTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Office Supplies')->where('coaAccount.type', 'Expense'))->sum('amount') ?? 0;
        $professionalFeesTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Professional Fees')->where('coaAccount.type', 'Expense'))->sum('amount') ?? 0;
        $representationTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Representation and Entertainment')->where('coaAccount.type', 'Expense'))->sum('amount') ?? 0;
        $researchDevelopmentTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Research and Development')->where('coaAccount.type', 'Expense'))->sum('amount') ?? 0;
        $salariesAllowancesTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Salaries and Allowances')->where('coaAccount.type', 'Expense'))->sum('amount') ?? 0;
        $contributionsTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'SSS, GSIS, PhilHealth, HDMF and Other Contributions')->where('coaAccount.type', 'Expense'))->sum('amount') ?? 0;

        // Summing all other expenses as "Others"
        $otherExpensesTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.type', 'Expense')
                ->whereNotIn('coaAccount.sub_type', [
                    'Rental', 'Depreciation', 'Management and Consultancy Fee', 'Office Supplies', 'Professional Fees',
                    'Representation and Entertainment', 'Research and Development', 'Salaries and Allowances',
                    'SSS, GSIS, PhilHealth, HDMF and Other Contributions',
                ]);
        })->sum('amount') ?? 0;

        $totalOperatingExpenses = $rentalTotal + $depreciationTotal + $managementFeeTotal + $officeSuppliesTotal +
            $professionalFeesTotal + $representationTotal + $researchDevelopmentTotal +
            $salariesAllowancesTotal + $contributionsTotal + $otherExpensesTotal;

        $grossProfit = $totalRevenue - $totalCostOfSales;
        $netIncome = $grossProfit - $totalOperatingExpenses;

        // Return all calculated data
        return compact(
            'totalRevenue', 'totalCostOfSales', 'rentalTotal', 'depreciationTotal', 'managementFeeTotal',
            'officeSuppliesTotal', 'professionalFeesTotal', 'representationTotal', 'researchDevelopmentTotal',
            'salariesAllowancesTotal', 'contributionsTotal', 'otherExpensesTotal', 'totalOperatingExpenses',
            'grossProfit', 'netIncome'
        );
    }
        // Render Financial Report View
    public function financial(Request $request)
    {
        
        $financialData = $this->getFinancialData($request);
        
        return view('financial-reports', $financialData);
    }

    // Export Excel File
    public function exportExcel(Request $request)
    {
        // Set up default values
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);
        $quarter = $request->input('quarter', 'Q' . ceil(now()->month / 3));

        $financialData = $this->getFinancialData($request);
        $financialData['year'] = $year;
        $financialData['month'] = $month;
        $financialData['quarter'] = $quarter;


        return Excel::download(new FinancialReportExport($financialData), "IncomeStatement_{$year}_{$month}.xlsx");
    }

}
