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
        if (empty($this->financialData)) {
            return [['No data available for the selected period']];
        }

        return [
            ['Revenue', ''],
            ['Sales/Revenues/Receipts/Fees', $this->financialData['totalRevenue'] ?? 0],
            ['Total Revenue', $this->financialData['totalRevenue'] ?? 0],

            // Cost of Sales Section
            ['Cost of Sales', ''],
            ['Cost of Goods Sold', $this->financialData['totalCostOfSales'] ?? 0],
            ['Total Cost of Sales', $this->financialData['totalCostOfSales'] ?? 0],

            // Gross Profit
            ['Gross Profit', $this->financialData['grossProfit'] ?? 0],

            // Expenses Section Header
            ['Expenses', ''],
            ['Rental', $this->financialData['rentalTotal'] ?? 0],
            ['Depreciation', $this->financialData['depreciationTotal'] ?? 0],
            ['Management and Consultancy Fee', $this->financialData['managementFeeTotal'] ?? 0],
            ['Office Supplies', $this->financialData['officeSuppliesTotal'] ?? 0],
            ['Professional Fees', $this->financialData['professionalFeesTotal'] ?? 0],
            ['Representation and Entertainment', $this->financialData['representationTotal'] ?? 0],
            ['Research and Development', $this->financialData['researchDevelopmentTotal'] ?? 0],
            ['Salaries and Allowances', $this->financialData['salariesAllowancesTotal'] ?? 0],
            ['SSS, GSIS, PhilHealth, HDMF and Other Contributions', $this->financialData['contributionsTotal'] ?? 0],
            ['Others', $this->financialData['otherExpensesTotal'] ?? 0],

            // Total Operating Expenses
            ['Total Operating Expenses', $this->financialData['totalOperatingExpenses'] ?? 0],

            // Net Income
            ['Net Income (Loss) From Operations', $this->financialData['netIncome'] ?? 0],
        ];
    }

    public function headings(): array
    {
        return ['Category', 'Amount'];
    }
}
