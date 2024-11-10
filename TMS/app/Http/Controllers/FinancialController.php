<?php

namespace App\Http\Controllers;

use App\Exports\FinancialReportExport;
use App\Models\Transactions; 
use App\Models\OrgSetup;
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
    $organizationId = $request->session()->get('organization_id');
    // Default values for year, month, and quarter
    $year = $request->input('year', now()->year);
    $month = $request->input('month', now()->month);
    $quarter = $request->input('quarter', 'Q' . ceil(now()->month / 3));
    $period = $request->input('period', 'annually');  
    $status = $request->input('status', 'draft');

    $query = Transactions::with(['taxRows.coaAccount'])
            ->where('organization_id', $organizationId)
            ->whereYear('date', $year);

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

    // Aggregate total revenue (including Sales as a subtype of Revenue)
    $totalRevenue = $transactions->flatMap(fn($transaction) =>
        $transaction->taxRows->where('coaAccount.type', 'Revenue')
    )->sum('amount') ?? 0;

    // Aggregate cost of goods sold (COGS), specifically under Expenses with sub-type 'Cost of Goods Sold'
    $totalCostOfSales = $transactions->flatMap(fn($transaction) =>
        $transaction->taxRows->where('coaAccount.type', 'Expenses')
            ->where('coaAccount.sub_type', 'Cost of Goods Sold')
    )->sum('amount') ?? 0;

    // Manually summing specific operating expenses
    $rentalTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Rental')->where('coaAccount.type', 'Expenses'))->sum('amount') ?? 0;
    $depreciationTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Depreciation')->where('coaAccount.type', 'Expenses'))->sum('amount') ?? 0;
    $managementFeeTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Management and Consultancy Fee')->where('coaAccount.type', 'Expenses'))->sum('amount') ?? 0;
    $officeSuppliesTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Office Supplies')->where('coaAccount.type', 'Expenses'))->sum('amount') ?? 0;
    $professionalFeesTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Professional Fees')->where('coaAccount.type', 'Expenses'))->sum('amount') ?? 0;
    $representationTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Representation and Entertainment')->where('coaAccount.type', 'Expenses'))->sum('amount') ?? 0;
    $researchDevelopmentTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Research and Development')->where('coaAccount.type', 'Expenses'))->sum('amount') ?? 0;
    $salariesAllowancesTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'Salaries and Allowances')->where('coaAccount.type', 'Expenses'))->sum('amount') ?? 0;
    $contributionsTotal = $transactions->flatMap(fn($transaction) => $transaction->taxRows->where('coaAccount.sub_type', 'SSS, GSIS, PhilHealth, HDMF and Other Contributions')->where('coaAccount.type', 'Expenses'))->sum('amount') ?? 0;

    // Summing all other expenses as "Others"
    $otherExpensesTotal = $transactions->flatMap(function ($transaction) {
        return $transaction->taxRows->where('coaAccount.type', 'Expenses')
            ->whereNotIn('coaAccount.sub_type', [
                'Rental', 'Depreciation', 'Management and Consultancy Fee', 'Office Supplies', 'Professional Fees',
                'Representation and Entertainment', 'Research and Development', 'Salaries and Allowances',
                'SSS, GSIS, PhilHealth, HDMF and Other Contributions', 'Cost of Goods Sold',
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
        $month = str_pad($request->input('month', now()->month), 2, '0', STR_PAD_LEFT);
        $quarter = $request->input('quarter', 'Q' . ceil(now()->month / 3));
        $organizationId = $request->session()->get('organization_id');

        $organization = OrgSetup::find($organizationId);
        if (!$organization) {
            return response()->json(['error' => 'Organization not found'], 404);
        }

        // Get financial data based on the request
        $financialData = $this->getFinancialData($request);
        
        // Check if financial data was returned as an error response
        if (is_array($financialData) && isset($financialData['error'])) {
            return response()->json(['error' => $financialData['error']], 403);
        }

        $financialData['year'] = $year;
        $financialData['month'] = $month;
        $financialData['quarter'] = $quarter;

        $registrationName = preg_replace('/[^A-Za-z0-9]/', '_', $organization->registration_name); // sanitize filename

        // Generate the Excel file name
        $filename = "IncomeStatement_Of_{$registrationName}_{$year}_{$month}.xlsx";
        return Excel::download(new FinancialReportExport($financialData), $filename);
    }


}
