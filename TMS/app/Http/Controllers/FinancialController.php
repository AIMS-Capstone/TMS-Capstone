<?php

namespace App\Http\Controllers;

use App\Exports\FinancialReportExport;
use App\Models\OrgSetup;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FinancialController extends Controller
{
    private function getQuarterMonths($quarter)
    {
        switch ($quarter) {
            case 'Q1':
                return [1, 3]; // January to March
            case 'Q2':
                return [4, 6]; // April to June
            case 'Q3':
                return [7, 9]; // July to September
            case 'Q4':
                return [10, 12]; // October to December
            default:
                return [1, 12]; // Full year as default if quarter is invalid
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
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = Transactions::with(['taxRows.coaAccount'])
            ->where('organization_id', $organizationId)
            ->whereYear('date', $year);

        if ($period === 'select-date' && $startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        } elseif ($period === 'monthly' && $month) {
            $fromDate = Carbon::create($year, $month, 1);
            $toDate = $fromDate->copy()->endOfMonth();
            $query->whereBetween('date', [$fromDate, $toDate]);
        } elseif ($period === 'quarterly' && $quarter) {
            [$startMonth, $endMonth] = $this->getQuarterMonths($quarter);
            $fromDate = Carbon::create($year, $startMonth, 1);
            $toDate = Carbon::create($year, $endMonth, 1)->endOfMonth();
            $query->whereBetween('date', [$fromDate, $toDate]);
        } else { // Annually
            $fromDate = Carbon::create($year, 1, 1);
            $toDate = Carbon::create($year, 12, 31);
            $query->whereBetween('date', [$fromDate, $toDate]);
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
        return [
            'totalRevenue' => $totalRevenue ?? 0,
            'totalCostOfSales' => $totalCostOfSales ?? 0,
            'rentalTotal' => $rentalTotal ?? 0,
            'depreciationTotal' => $depreciationTotal ?? 0,
            'managementFeeTotal' => $managementFeeTotal ?? 0,
            'officeSuppliesTotal' => $officeSuppliesTotal ?? 0,
            'professionalFeesTotal' => $professionalFeesTotal ?? 0,
            'representationTotal' => $representationTotal ?? 0,
            'researchDevelopmentTotal' => $researchDevelopmentTotal ?? 0,
            'salariesAllowancesTotal' => $salariesAllowancesTotal ?? 0,
            'contributionsTotal' => $contributionsTotal ?? 0,
            'otherExpensesTotal' => $otherExpensesTotal ?? 0,
            'totalOperatingExpenses' => $totalOperatingExpenses ?? 0,
            'grossProfit' => $grossProfit ?? 0,
            'netIncome' => $netIncome ?? 0,
        ];

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

        $year = $request->input('year');
        $month = $request->input('month');
        $quarter = $request->input('quarter');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $period = $request->input('period', 'annually');

        // Default date range
        $fromDate = null;
        $toDate = null;

        $organizationId = $request->session()->get('organization_id');

        $organization = OrgSetup::find($organizationId);
        if (!$organization) {
            return response()->json(['error' => 'Organization not found'], 404);
        }

        if ($period === 'select-date' && $startDate && $endDate) {
            $fromDate = Carbon::parse($startDate);
            $toDate = Carbon::parse($endDate);
        } elseif ($period === 'monthly' && $month) {
            $fromDate = Carbon::createFromFormat('Y-m-d', "$year-$month-01");
            $toDate = Carbon::createFromFormat('Y-m-d', "$year-$month-01")->endOfMonth();
        } elseif ($period === 'quarterly' && $quarter) {
            $quarters = [
                'Q1' => ['start' => '01', 'end' => '03'],
                'Q2' => ['start' => '04', 'end' => '06'],
                'Q3' => ['start' => '07', 'end' => '09'],
                'Q4' => ['start' => '10', 'end' => '12'],
            ];
            if (isset($quarters[$quarter])) {
                $fromDate = Carbon::createFromFormat('Y-m-d', "$year-{$quarters[$quarter]['start']}-01");
                $toDate = Carbon::createFromFormat('Y-m-d', "$year-{$quarters[$quarter]['end']}-01")->endOfMonth();
            }
        } else {
            // Default to the entire year if no specific filters
            $fromDate = Carbon::createFromFormat('Y-m-d', "$year-01-01");
            $toDate = Carbon::createFromFormat('Y-m-d', "$year-12-31");
        }

        // Ensure fromDate and toDate are used in the query
        $financialData = Transactions::whereBetween('date', [$fromDate, $toDate])->get();

        $financialData = $this->getFinancialData($request);

        // Ensure all keys are present with default values
        $financialData = array_merge([
            'totalRevenue' => 0,
            'totalCostOfSales' => 0,
            'rentalTotal' => 0,
            'depreciationTotal' => 0,
            'managementFeeTotal' => 0,
            'officeSuppliesTotal' => 0,
            'professionalFeesTotal' => 0,
            'representationTotal' => 0,
            'researchDevelopmentTotal' => 0,
            'salariesAllowancesTotal' => 0,
            'contributionsTotal' => 0,
            'otherExpensesTotal' => 0,
            'totalOperatingExpenses' => 0,
            'grossProfit' => 0,
            'netIncome' => 0,
        ], $financialData);

        $registrationName = preg_replace('/[^A-Za-z0-9]/', '_', $organization->registration_name); // sanitize filename

        // Naming ng file
        switch ($period) {
            case 'select-date':
                $filename = "IncomeStatement_Of_{$registrationName}_From_{$startDate}_To_{$endDate}.xlsx";
                break;
            case 'monthly':
                $monthName = Carbon::createFromFormat('m', $month)->format('F');
                $filename = "IncomeStatement_Of_{$registrationName}_For_{$monthName}_{$year}.xlsx";
                break;
            case 'quarterly':
                $filename = "IncomeStatement_Of_{$registrationName}_For_{$quarter}_{$year}.xlsx";
                break;
            case 'annually':
            default:
                $filename = "IncomeStatement_Of_{$registrationName}_For_{$year}.xlsx";
                break;
        }

        return Excel::download(new FinancialReportExport($financialData), $filename);

    }

}
