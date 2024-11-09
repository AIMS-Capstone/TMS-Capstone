<?php

namespace App\Http\Controllers;

use App\Exports\ClientFinancialReportExport;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClientFinancialController extends Controller
{
    public function income_statement(Request $request)
    {
        $organizationId = $request->session()->get('organization_id');
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
        $financialData = $this->calculateFinancialData($transactions);

        return view('client.income_statement', $financialData);
    }

    private function calculateFinancialData($transactions)
    {
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
        $rentalTotal = $transactions->flatMap(fn($transaction) => 
            $transaction->taxRows->where('coaAccount.sub_type', 'Rental')->where('coaAccount.type', 'Expenses')
        )->sum('amount') ?? 0;

        $depreciationTotal = $transactions->flatMap(fn($transaction) => 
            $transaction->taxRows->where('coaAccount.sub_type', 'Depreciation')->where('coaAccount.type', 'Expenses')
        )->sum('amount') ?? 0;

        $managementFeeTotal = $transactions->flatMap(fn($transaction) => 
            $transaction->taxRows->where('coaAccount.sub_type', 'Management and Consultancy Fee')->where('coaAccount.type', 'Expenses')
        )->sum('amount') ?? 0;

        $officeSuppliesTotal = $transactions->flatMap(fn($transaction) => 
            $transaction->taxRows->where('coaAccount.sub_type', 'Office Supplies')->where('coaAccount.type', 'Expenses')
        )->sum('amount') ?? 0;

        $professionalFeesTotal = $transactions->flatMap(fn($transaction) => 
            $transaction->taxRows->where('coaAccount.sub_type', 'Professional Fees')->where('coaAccount.type', 'Expenses')
        )->sum('amount') ?? 0;

        $representationTotal = $transactions->flatMap(fn($transaction) => 
            $transaction->taxRows->where('coaAccount.sub_type', 'Representation and Entertainment')->where('coaAccount.type', 'Expenses')
        )->sum('amount') ?? 0;

        $researchDevelopmentTotal = $transactions->flatMap(fn($transaction) => 
            $transaction->taxRows->where('coaAccount.sub_type', 'Research and Development')->where('coaAccount.type', 'Expenses')
        )->sum('amount') ?? 0;

        $salariesAllowancesTotal = $transactions->flatMap(fn($transaction) => 
            $transaction->taxRows->where('coaAccount.sub_type', 'Salaries and Allowances')->where('coaAccount.type', 'Expenses')
        )->sum('amount') ?? 0;

        $contributionsTotal = $transactions->flatMap(fn($transaction) => 
            $transaction->taxRows->where('coaAccount.sub_type', 'SSS, GSIS, PhilHealth, HDMF and Other Contributions')->where('coaAccount.type', 'Expenses')
        )->sum('amount') ?? 0;

        // Summing all other expenses as "Others"
        $otherExpensesTotal = $transactions->flatMap(fn($transaction) =>
            $transaction->taxRows->where('coaAccount.type', 'Expenses')
                ->whereNotIn('coaAccount.sub_type', [
                    'Rental', 'Depreciation', 'Management and Consultancy Fee', 'Office Supplies', 'Professional Fees',
                    'Representation and Entertainment', 'Research and Development', 'Salaries and Allowances',
                    'SSS, GSIS, PhilHealth, HDMF and Other Contributions', 'Cost of Goods Sold'
                ])
        )->sum('amount') ?? 0;

        $totalOperatingExpenses = $rentalTotal + $depreciationTotal + $managementFeeTotal + $officeSuppliesTotal +
            $professionalFeesTotal + $representationTotal + $researchDevelopmentTotal +
            $salariesAllowancesTotal + $contributionsTotal + $otherExpensesTotal;

        $grossProfit = $totalRevenue - $totalCostOfSales;
        $netIncome = $grossProfit - $totalOperatingExpenses;

        return compact(
            'totalRevenue', 'totalCostOfSales', 'rentalTotal', 'depreciationTotal', 'managementFeeTotal',
            'officeSuppliesTotal', 'professionalFeesTotal', 'representationTotal', 'researchDevelopmentTotal',
            'salariesAllowancesTotal', 'contributionsTotal', 'otherExpensesTotal', 'totalOperatingExpenses',
            'grossProfit', 'netIncome'
        );
    }

    private function getQuarterMonths($quarter)
    {
        switch ($quarter) {
            case 'Q1':return [1, 3];
            case 'Q2':return [4, 6];
            case 'Q3':return [7, 9];
            case 'Q4':return [10, 12];
            default:return [1, 12];
        }
    }

    public function clientExportExcel(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = str_pad($request->input('month', now()->month), 2, '0', STR_PAD_LEFT);
        $quarter = $request->input('quarter', 'Q' . ceil(now()->month / 3));
        $organizationId = $request->session()->get('organization_id');

        $transactions = Transactions::with(['taxRows.coaAccount'])
            ->where('organization_id', $organizationId)
            ->whereYear('date', $year)
            ->get();

        $financialData = $this->calculateFinancialData($transactions);
        $financialData['year'] = $year;
        $financialData['month'] = $month;
        $financialData['quarter'] = $quarter;

        return Excel::download(new ClientFinancialReportExport($financialData), "IncomeStatement_{$year}_{$month}.xlsx");
    }
}
