<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transactions;


class FinancialController extends Controller
{
    public function financial(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Transactions::with(['taxRows.coaAccount']);

        // Apply date filters
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

        // Aggregating revenue and cost of sales
        $totalRevenue = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.type', 'Revenue');
        })->sum('amount');

        $totalCostOfSales = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.type', 'Cost of Sales');
        })->sum('amount');

        // Manually summing specific sub-expenses
        $rentalTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.sub_type', 'Rental');
        })->sum('amount');

        $depreciationTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.sub_type', 'Depreciation');
        })->sum('amount');

        $managementFeeTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.sub_type', 'Management and Consultancy Fee');
        })->sum('amount');

        $officeSuppliesTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.sub_type', 'Office Supplies');
        })->sum('amount');

        $professionalFeesTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.sub_type', 'Professional Fees');
        })->sum('amount');

        $representationTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.sub_type', 'Representation and Entertainment');
        })->sum('amount');

        $researchDevelopmentTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.sub_type', 'Research and Development');
        })->sum('amount');

        $salariesAllowancesTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.sub_type', 'Salaries and Allowances');
        })->sum('amount');

        $contributionsTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->where('coaAccount.sub_type', 'SSS, GSIS, PhilHealth, HDMF and Other Contributions');
        })->sum('amount');

        // Summing all other expenses as "Others"
        $otherExpensesTotal = $transactions->flatMap(function ($transaction) {
            return $transaction->taxRows->whereNotIn('coaAccount.sub_type', [
                'Rental', 
                'Depreciation', 
                'Management and Consultancy Fee', 
                'Office Supplies', 
                'Professional Fees', 
                'Representation and Entertainment', 
                'Research and Development', 
                'Salaries and Allowances', 
                'SSS, GSIS, PhilHealth, HDMF and Other Contributions'
            ]);
        })->sum('amount');

        $totalOperatingExpenses = $rentalTotal + $depreciationTotal + $managementFeeTotal + $officeSuppliesTotal +
                                $professionalFeesTotal + $representationTotal + $researchDevelopmentTotal + 
                                $salariesAllowancesTotal + $contributionsTotal + $otherExpensesTotal;

        // Calculating gross profit and net income
        $grossProfit = $totalRevenue - $totalCostOfSales;
        $netIncome = $grossProfit - $totalOperatingExpenses;

        return view('financial-reports', compact(
            'totalRevenue', 
            'totalCostOfSales', 
            'rentalTotal', 
            'depreciationTotal', 
            'managementFeeTotal', 
            'officeSuppliesTotal', 
            'professionalFeesTotal', 
            'representationTotal', 
            'researchDevelopmentTotal', 
            'salariesAllowancesTotal', 
            'contributionsTotal', 
            'otherExpensesTotal', 
            'totalOperatingExpenses', 
            'grossProfit', 
            'netIncome'
        ));
    }

}
