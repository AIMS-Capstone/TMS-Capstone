<table>
    <thead>
        <tr>
            <th>Description</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <!-- Revenue Section -->
        <tr><td>Total Revenue</td><td>{{ number_format($financialData['totalRevenue'], 2) }}</td></tr>
        
        <!-- Cost of Sales Section -->
        <tr><td>Total Cost of Sales</td><td>{{ number_format($financialData['totalCostOfSales'], 2) }}</td></tr>
        
        <!-- Gross Profit -->
        <tr><td>Gross Profit</td><td>{{ number_format($financialData['grossProfit'], 2) }}</td></tr>
        
        <!-- Operating Expenses Header -->
        <tr><td>Operating Expenses</td><td></td></tr>
        
        <!-- Individual Expenses -->
        <tr><td>Rental</td><td>{{ number_format($financialData['rentalTotal'], 2) }}</td></tr>
        <tr><td>Depreciation</td><td>{{ number_format($financialData['depreciationTotal'], 2) }}</td></tr>
        <tr><td>Management and Consultancy Fee</td><td>{{ number_format($financialData['managementFeeTotal'], 2) }}</td></tr>
        <tr><td>Office Supplies</td><td>{{ number_format($financialData['officeSuppliesTotal'], 2) }}</td></tr>
        <tr><td>Professional Fees</td><td>{{ number_format($financialData['professionalFeesTotal'], 2) }}</td></tr>
        <tr><td>Representation and Entertainment</td><td>{{ number_format($financialData['representationTotal'], 2) }}</td></tr>
        <tr><td>Research and Development</td><td>{{ number_format($financialData['researchDevelopmentTotal'], 2) }}</td></tr>
        <tr><td>Salaries and Allowances</td><td>{{ number_format($financialData['salariesAllowancesTotal'], 2) }}</td></tr>
        <tr><td>SSS, GSIS, PhilHealth, HDMF and Other Contributions</td><td>{{ number_format($financialData['contributionsTotal'], 2) }}</td></tr>
        <tr><td>Others</td><td>{{ number_format($financialData['otherExpensesTotal'], 2) }}</td></tr>
        
        <!-- Total Operating Expenses -->
        <tr><td>Total Operating Expenses</td><td>{{ number_format($financialData['totalOperatingExpenses'], 2) }}</td></tr>
        
        <!-- Net Income -->
        <tr><td>Net Income (Loss) From Operations</td><td>{{ number_format($financialData['netIncome'], 2) }}</td></tr>
        
        <!-- Income Tax Expense (if applicable) -->
        <tr><td>Income Tax Expense</td><td></td></tr>
        
        <!-- Final Net Income -->
        <tr><td>Net Income (Loss) From Operations</td><td>{{ number_format($financialData['netIncome'], 2) }}</td></tr>
    </tbody>
</table>
