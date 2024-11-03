<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FinancialReportExport implements FromArray, WithHeadings
{
    protected $financialData;

    public function __construct($financialData)
    {
        $this->financialData = $financialData;
    }

public function array(): array
{
    return [

        // ['Report Period', "{$this->financialData['year']} - {$this->financialData['month']} - {$this->financialData['quarter']}"],
        // Revenue Section
        ['Revenue', ''],
        ['Sales/Revenues/Receipts/Fees', $this->financialData['totalRevenue']], // Main sales revenue
        ['Total Revenue', $this->financialData['totalRevenue']],
        
        // Cost of Sales Section
        ['Cost of Sales', ''],
        ['Cost of Goods Sold', $this->financialData['totalCostOfSales']],
        ['Total Cost of Sales', $this->financialData['totalCostOfSales']],
        
        // Gross Profit
        ['Gross Profit', $this->financialData['grossProfit']],
        
        // Expenses Section Header
        ['Expenses', ''],

        // Individual Expenses
        ['Rental', $this->financialData['rentalTotal']],
        ['Depreciation', $this->financialData['depreciationTotal']],
        ['Management and Consultancy Fee', $this->financialData['managementFeeTotal']],
        ['Office Supplies', $this->financialData['officeSuppliesTotal']],
        ['Professional Fees', $this->financialData['professionalFeesTotal']],
        ['Representation and Entertainment', $this->financialData['representationTotal']],
        ['Research and Development', $this->financialData['researchDevelopmentTotal']],
        ['Salaries and Allowances', $this->financialData['salariesAllowancesTotal']],
        ['SSS, GSIS, PhilHealth, HDMF and Other Contributions', $this->financialData['contributionsTotal']],
        ['Others', $this->financialData['otherExpensesTotal']],
        
        // Total Operating Expenses
        ['Total Operating Expenses', $this->financialData['totalOperatingExpenses']],
        
        // Net Income
        ['Net Income (Loss) From Operations', $this->financialData['netIncome']],
        
        // Placeholder for Income Tax Expense if needed
        ['Income Tax Expense', ''],
        
        // Final Net Income
        ['Net Income (Loss) From Operations', $this->financialData['netIncome']]
    ];
}



    public function headings(): array
    {
        return ['Category', 'Amount'];
    }
}
