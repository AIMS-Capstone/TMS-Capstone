<!-- resources/views/tax_return/income_report_pdf.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>BIR Form 1701Q</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
        }
        .container {
            width: 100%;
            max-width: 8.5in;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .bir-logo {
            width: 80px;
            height: auto;
        }
        .form-title {
            font-size: 16px;
            font-weight: bold;
            margin: 10px 0;
        }
        .form-subtitle {
            font-size: 14px;
            margin: 5px 0;
        }
        .box {
            border: 1px solid #000;
            padding: 5px;
            margin: 2px;
        }
        .grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .row {
            display: table-row;
        }
        .cell {
            display: table-cell;
            border: 1px solid #000;
            padding: 3px;
            vertical-align: middle;
        }
        .label {
            font-weight: bold;
            background-color: #f0f0f0;
        }
        .value {
            text-align: right;
        }
        .section-title {
            background-color: #d3d3d3;
            font-weight: bold;
            padding: 5px;
            margin: 10px 0;
        }
        .checkbox {
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            display: inline-block;
            margin-right: 5px;
        }
        .checked {
            background-color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="form-title">Quarterly Income Tax Return</div>
            <div class="form-subtitle">BIR Form No. 1701Q {{ $tax1701q->amended_return == 'yes' ? '(Amended)' : '' }}</div>
        </div>

        <!-- Basic Information Section -->
        <div class="grid">
            <div class="row">
                <div class="cell">1 For the Year: {{ $tax1701q->for_the_year }}</div>
                <div class="cell">2 Quarter: {{ $tax1701q->quarter }}</div>
                <div class="cell">3 Amended Return: {{ $tax1701q->amended_return }}</div>
                <div class="cell">4 No. of Sheets Attached: {{ $tax1701q->sheets }}</div>
            </div>
        </div>

     <!-- Taxpayer Information -->
<div class="section-title">Part I - Taxpayer Information</div>
<div class="grid">
    <div class="row">
        <div class="cell label">5 TIN</div>
        <div class="cell">{{ $tax1701q->tin }}</div>
        <div class="cell label">6 RDO Code</div>
        <div class="cell">{{ $tax1701q->rdo_code }}</div>
    </div>
    <div class="row">
        <div class="cell label">7 Taxpayer/Filer Type</div>
        <div class="cell">{{ $tax1701q->filer_type }}</div>
        <div class="cell label">8 Alphanumeric Tax Code</div>
        <div class="cell">{{ $tax1701q->alphanumeric_tax_code }}</div>
    </div>
    <div class="row">
        <div class="cell label">9 Taxpayer's Name</div>
        <div class="cell" colspan="3">{{ $tax1701q->taxpayer_name }}</div>
    </div>
    <div class="row">
        <div class="cell label">10 Registered Address</div>
        <div class="cell" colspan="3">{{ $tax1701q->registered_address }}</div>
    </div>
    <div class="row">
        <div class="cell label">10A Zip Code</div>
        <div class="cell">{{ $tax1701q->zip_code }}</div>
        <div class="cell label">11 Date of Birth</div>
        <div class="cell">{{ $tax1701q->date_of_birth }}</div>
    </div>
    <div class="row">
        <div class="cell label">12 Email Address</div>
        <div class="cell" colspan="3">{{ $tax1701q->email_address }}</div>
    </div>
    <div class="row">
        <div class="cell label">13 Citizenship</div>
        <div class="cell">{{ $tax1701q->citizenship }}</div>
        <div class="cell label">14 Foreign Tax Number</div>
        <div class="cell">{{ $tax1701q->foreign_tax }}</div>
    </div>
    <div class="row">
        <div class="cell label">15 Claiming Foreign Tax Credits?</div>
        <div class="cell" colspan="3">   {{ $tax1701q->claiming_foreign_credits == 1 ? 'Yes' : 'No' }}</div>
    </div>
    <div class="row">
        <div class="cell label">16 Tax Rate</div>
        <div class="cell">{{ $tax1701q->individual_rate_type == '8_percent'? '8% on gross sales/receipts & other non-operating income in lieu of Graduated Rates under Sec. 24(A)(2)(a) & Percentage Tax' : 'Graduated Rates' }}</div>
        <div class="cell label">16A Method of Deduction</div>
        <div class="cell">{{ $tax1701q->individual_deduction_method }}</div>
    </div>
    
</div>


        <!-- Income Computation -->
        <div class="section-title">Part III Total Tax Payable</div>
        <div class="grid">
            <div class="row">
                <div class="cell label">Particulars</div>
                <div class="cell label">A.)Taxpayer/Filer</div>
            </div>
            <div class="row">
                <div class="cell label">26 Tax Due (From Part V, Schedule I-Item 46 OR Schedule II-Item 54)</div>
                <div class="cell value">{{ number_format($tax1701q->tax_due, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">27 Less: Tax Credits/Payments (From Part V, Schedule III-Item 62)</div>
                <div class="cell value">{{ number_format($tax1701q->tax_credits_payments, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">28 Tax Payable/(Overpayment) (Item 26 Less Item 27) (From Part V, Item 63)</div>
                <div class="cell value">{{ number_format($tax1701q->tax_payable_overpayment, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">29 Add: Total Penalties (From Part V, Schedule IV-Item 67)</div>
                <div class="cell value">{{ number_format($tax1701q->total_penalties, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">30 Total Amount Payable/(Overpayment) (Sum of Items 28 and 29) (From Part V, Item 68)</div>
                <div class="cell value">{{ number_format($tax1701q->total_amount_payable, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">31 Aggregate Amount Payable/(Overpayment) (Sum of Items 30A and 30B)</div>
                <div class="cell value">{{ number_format($tax1701q->aggregate_amount_payable, 2) }}</div>
            </div>
        </div>

        <!-- Tax Due Computation -->
      <!-- Part V – Computation of Tax Due -->
<div class="section-title">Part V – Computation of Tax Due</div>

<div class="grid">
    @if($tax1701q->individual_rate_type == '8_percent')
        <!-- Schedule II – For 8% IT Rate -->
        <div class="section-title">Schedule II – For 8% IT Rate</div>
        <div class="row">
            <div class="cell label">47 Sales/Revenues/Receipts/Fees (net of sales returns, allowances and discounts)</div>
            <div class="cell value">{{ number_format($tax1701q->sales_revenues, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">48 Add: Non-Operating Income (specify)</div>
            <div class="cell value">{{ number_format($tax1701q->non_operating_income, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">49 Total Income for the quarter (Sum of Items 47 and 48)</div>
            <div class="cell value">{{ number_format($tax1701q->total_income_quarter, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">50 Add: Total Taxable Income/(Loss) Previous Quarter (Item 51 of previous quarter)</div>
            <div class="cell value">{{ number_format($tax1701q->total_income_previous_quarter, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">51 Cumulative Taxable Income/(Loss) as of This Quarter (Sum of Items 49 and 50)</div>
            <div class="cell value">{{ number_format($tax1701q->cumulative_income, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">52 Less: Allowable reduction from gross sales/receipts and other non-operating income of purely self-employed individuals and/or professionals in the amount of P 250,000</div>
            <div class="cell value">{{ number_format($tax1701q->allowable_reduction, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">53 Taxable Income/(Loss) To Date (Item 51 Less Item 52)</div>
            <div class="cell value">{{ number_format($tax1701q->taxable_income_to_date, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">54 TAX DUE (Item 53 x 8% Tax Rate) (To Part III, Item 26)</div>
            <div class="cell value">{{ number_format($tax1701q->tax_due_8_percent, 2) }}</div>
        </div>
    @else
        <!-- Schedule I – For Graduated IT Rate -->
        <div class="section-title">Schedule I – For Graduated IT Rate</div>
        <div class="row">
            <div class="cell label">36 Sales/Revenues/Receipts/Fees (net of sales returns, allowances and discounts)</div>
            <div class="cell value">{{ number_format($tax1701q->sales_revenues, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">37 Less: Cost of Sales/Services (applicable only if availing Itemized Deductions)</div>
            <div class="cell value">{{ number_format($tax1701q->cost_of_sales, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">38 Gross Income/(Loss) from Operation (Item 36 Less Item 37)</div>
            <div class="cell value">{{ number_format($tax1701q->gross_income, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">39 Total Allowable Itemized Deductions</div>
            <div class="cell value">{{ number_format($tax1701q->itemized_deductions, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">40 Optional Standard Deduction (OSD) (40% of Item 36)</div>
            <div class="cell value">{{ number_format($tax1701q->osd, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">41 Net Income/(Loss) This Quarter (If Itemized: Item 38 Less Item 39; If OSD: Item 38 Less Item 40)</div>
            <div class="cell value">{{ number_format($tax1701q->net_income, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">42 Taxable Income/(Loss) Previous Quarter/s</div>
            <div class="cell value">{{ number_format($tax1701q->taxable_income_previous, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">43 Non-Operating Income (specify)</div>
            <div class="cell value">{{ number_format($tax1701q->non_operating_income_specified, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">44 Amount Received/Share in Income by a Partner from General Professional Partnership (GPP)</div>
            <div class="cell value">{{ number_format($tax1701q->income_from_gpp, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">45 Total Taxable Income/(Loss) To Date (Sum of Items 41 to 44)</div>
            <div class="cell value">{{ number_format($tax1701q->total_taxable_income, 2) }}</div>
        </div>
        <div class="row">
            <div class="cell label">46 TAX DUE (Item 45 x Applicable Tax Rate based on Tax Table below) (To Part III, Item 26)</div>
            <div class="cell value">{{ number_format($tax1701q->tax_due_graduated, 2) }}</div>
        </div>
    @endif
</div>


        <!-- Penalties -->
        <div class="section-title">Part IV - Penalties</div>
        <div class="grid">
            <div class="row">
                <div class="cell label">21 Surcharge</div>
                <div class="cell value">{{ number_format($tax1701q->surcharge, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">22 Interest</div>
                <div class="cell value">{{ number_format($tax1701q->interest, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">23 Compromise</div>
                <div class="cell value">{{ number_format($tax1701q->compromise, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">24 Total Penalties (Sum of Items 21 to 23)</div>
                <div class="cell value">{{ number_format($tax1701q->total_penalties, 2) }}</div>
            </div>
        </div>

        <!-- Total Amount Payable -->
        <div class="section-title">Total Amount Payable</div>
        <div class="grid">
            <div class="row">
                <div class="cell label">25 Total Amount Payable (Sum of Items 20 and 24)</div>
                <div class="cell value">{{ number_format($tax1701q->show_total_amount_payable, 2) }}</div>
            </div>
        </div>

   

   
        </div>
    </div>
</body>
</html>