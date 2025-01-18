<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>BIR Form 1702Q</title>
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
    </style>
</head>
<body>
    <div class="container">
        <!-- Header Section -->
        <div class="header">
            <div class="form-title">Quarterly Income Tax Return for Corporations</div>
            <div class="form-subtitle">BIR Form No. 1702Q {{ $tax1702q->amended_return == 'yes' ? '(Amended)' : '' }}</div>
        </div>

        <!-- Basic Information Section -->
        <div class="grid">
            <div class="row">
                <div class="cell">1 For the: {{ $tax1702q->period }}</div>
                <div class="cell">2 Year Ended: {{ $tax1702q->year_ended }}</div>
                <div class="cell">3 Quarter: {{ $tax1702q->quarter }}</div>
                <div class="cell">4 Amended Return?: {{ $tax1702q->amended_return }}</div>
            </div>
            <div class="row">
                <div class="cell" colspan="4">5 Alphanumeric Tax Code: {{ $tax1702q->alphanumeric_tax_code }}</div>
            </div>
        </div>

        <!-- Background Information -->
        <div class="section-title">Background Information</div>
        <div class="grid">
            <div class="row">
                <div class="cell label">6 TIN</div>
                <div class="cell">{{ $tax1702q->tin }}</div>
                <div class="cell label">7 RDO Code</div>
                <div class="cell">{{ $tax1702q->rdo_code }}</div>
            </div>
            <div class="row">
                <div class="cell label">8 Registered Name</div>
                <div class="cell" colspan="3">{{ $tax1702q->taxpayer_name }}</div>
            </div>
            <div class="row">
                <div class="cell label">9 Registered Address</div>
                <div class="cell" colspan="3">{{ $tax1702q->registered_address }}</div>
            </div>
            <div class="row">
                <div class="cell label">9A Zip Code</div>
                <div class="cell">{{ $tax1702q->zip_code }}</div>
                <div class="cell label">10 Contact Number</div>
                <div class="cell">{{ $tax1702q->contact_number }}</div>
            </div>
            <div class="row">
                <div class="cell label">11 Email Address</div>
                <div class="cell" colspan="3">{{ $tax1702q->email_address }}</div>
            </div>
            <div class="row">
                <div class="cell label">12 Method of Deduction</div>
                <div class="cell" colspan="3">{{ $tax1702q->method_of_deduction }}</div>
            </div>
            <div class="row">
                <div class="cell label">13 Are you availing of tax relief under Special Law or International Tax Treaty?</div>
                <div class="cell">{{ $tax1702q->tax_relief }}</div>
                <div class="cell label">13A If yes, specify</div>
                <div class="cell">{{ $tax1702q->yes_specify }}</div>
            </div>
        </div>

        <!-- Part II - Total Tax Payables -->
        <div class="section-title">Part II - Total Tax Payables</div>
        <div class="grid">
            <div class="row">
                <div class="cell label">14 Income Tax Due - Regular/Normal Rate</div>
                <div class="cell value">{{ number_format($tax1702q->show_income_tax_due_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">15 Less: Unexpired Excess of Prior Year's MCIT</div>
                <div class="cell value">{{ number_format($tax1702q->unexpired_excess_mcit, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">16 Balance/Income Tax Still Due – Regular/Normal Rate</div>
                <div class="cell value">{{ number_format($tax1702q->balance_tax_due_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">17 Add: Income Tax Due – Special Rate</div>
                <div class="cell value">{{ number_format($tax1702q->show_income_tax_due_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">18 Aggregate Income Tax Due</div>
                <div class="cell value">{{ number_format($tax1702q->aggregate_tax_due, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">19 Less: Total Tax Credits/Payments</div>
                <div class="cell value">{{ number_format($tax1702q->show_total_tax_credits, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">20 Net Tax Payable / (Overpayment)</div>
                <div class="cell value">{{ number_format($tax1702q->net_tax_payable, 2) }}</div>
            </div>
            <!-- Penalties Section -->
            <div class="row">
                <div class="cell label">21 Surcharge</div>
                <div class="cell value">{{ number_format($tax1702q->surcharge, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">22 Interest</div>
                <div class="cell value">{{ number_format($tax1702q->interest, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">23 Compromise</div>
                <div class="cell value">{{ number_format($tax1702q->compromise, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">24 Total Penalties</div>
                <div class="cell value">{{ number_format($tax1702q->total_penalties, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">25 TOTAL AMOUNT PAYABLE / (Overpayment)</div>
                <div class="cell value">{{ number_format($tax1702q->total_amount_payable, 2) }}</div>
            </div>
        </div>

        <!-- Part IV Schedules -->
        <div class="section-title">Part IV - Schedules</div>
        
        <!-- Schedule 1 - Declaration this quarter -->
        <div class="section-title">Schedule 1 - Declaration this Quarter - Special Rate</div>
        <div class="grid">
            <div class="row">
                <div class="cell label">1 Sales/Receipts/Revenues/Fees</div>
                <div class="cell value">{{ number_format($tax1702q->sales_receipts_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">2 Less: Cost of Sales/Services</div>
                <div class="cell value">{{ number_format($tax1702q->cost_of_sales_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">3 Gross Income from Operation</div>
                <div class="cell value">{{ number_format($tax1702q->gross_income_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">4 Add: Non-Operating and Other Taxable Income</div>
                <div class="cell value">{{ number_format($tax1702q->other_taxable_income_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">5 Total Gross Income</div>
                <div class="cell value">{{ number_format($tax1702q->total_gross_income_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">6 Less: Deductions</div>
                <div class="cell value">{{ number_format($tax1702q->deductions_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">7 Taxable Income this Quarter</div>
                <div class="cell value">{{ number_format($tax1702q->taxable_income_quarter_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">8 Add: Taxable Income Previous Quarter/s</div>
                <div class="cell value">{{ number_format($tax1702q->prev_quarter_income_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">9 Total Taxable Income to Date</div>
                <div class="cell value">{{ number_format($tax1702q->total_taxable_income_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">10 Applicable Income Tax Rate</div>
                <div class="cell value">{{ $tax1702q->tax_rate_special }}%</div>
            </div>
            <div class="row">
                <div class="cell label">11 Income Tax Due Other than MCIT</div>
                <div class="cell value">{{ number_format($tax1702q->income_tax_due_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">12 Less: Share of Other Agencies</div>
                <div class="cell value">{{ number_format($tax1702q->other_agencies_share_special, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">13 Net Income Tax Due to National Government</div>
                <div class="cell value">{{ number_format($tax1702q->net_tax_due_special, 2) }}</div>
            </div>
        </div>

        <!-- Schedule 2 - Declaration this Quarter - Regular Rate -->
        <div class="section-title">Schedule 2 - Declaration this Quarter - Regular/Normal Rate</div>
        <div class="grid">
            <div class="row">
                <div class="cell label">1 Sales/Receipts/Revenues/Fees</div>
                <div class="cell value">{{ number_format($tax1702q->sales_receipts_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">2 Less: Cost of Sales/Services</div>
                <div class="cell value">{{ number_format($tax1702q->cost_of_sales_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">3 Gross Income from Operation</div>
                <div class="cell value">{{ number_format($tax1702q->gross_income_operation_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">4 Add: Non-Operating and Other Taxable Income</div>
                <div class="cell value">{{ number_format($tax1702q->non_operating_income_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">5 Total Gross Income</div>
                <div class="cell value">{{ number_format($tax1702q->total_gross_income_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">6 Less: Deductions</div>
                <div class="cell value">{{ number_format($tax1702q->deductions_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">7 Taxable Income this Quarter</div>
                <div class="cell value">{{ number_format($tax1702q->taxable_income_quarter_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">8 Add: Taxable Income Previous Quarter/s</div>
                <div class="cell value">{{ number_format($tax1702q->taxable_income_previous_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">9 Total Taxable Income to Date</div>
                <div class="cell value">{{ number_format($tax1702q->total_taxable_income_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">10 Applicable Income Tax Rate</div>
                <div class="cell value">{{ $tax1702q->income_tax_rate_regular }}%</div>
            </div>
            <div class="row">
                <div class="cell label">11 Income Tax Due Other than MCIT</div>
                <div class="cell value">{{ number_format($tax1702q->income_tax_due_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">12 Minimum Corporate Income Tax (MCIT)</div>
                <div class="cell value">{{ number_format($tax1702q->mcit_regular, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">13 Income Tax Due</div>
                <div class="cell value">{{ number_format($tax1702q->final_income_tax_due_regular, 2) }}</div>
            </div>
        </div>

        <!-- Schedule 3 - Computation of Minimum Corporate Income Tax -->
        <div class="section-title">Schedule 3 - Computation of Minimum Corporate Income Tax (MCIT)</div>
        <div class="grid">
            <div class="row">
                <div class="cell label">1 Gross Income Regular/Normal Rate - 1st Quarter</div>
                <div class="cell value">{{ number_format($tax1702q->gross_income_first_quarter_mcit, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">2 Gross Income Regular/Normal Rate - 2nd Quarter</div>
                <div class="cell value">{{ number_format($tax1702q->gross_income_second_quarter_mcit, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">3 Gross Income Regular/Normal Rate - 3rd Quarter</div>
                <div class="cell value">{{ number_format($tax1702q->gross_income_third_quarter_mcit, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">4 Total Gross Income</div>
                <div class="cell value">{{ number_format($tax1702q->total_gross_income_mcit, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">5 MCIT Rate</div>
                <div class="cell value">{{ $tax1702q->mcit_rate }}%</div>
            </div>
            <div class="row">
                <div class="cell label">6 Minimum Corporate Income Tax</div>
                <div class="cell value">{{ number_format($tax1702q->minimum_corporate_income_tax_mcit, 2) }}</div>
            </div>
        </div>

        <!-- Schedule 4 - Tax Credits/Payments -->
        <div class="section-title">Schedule 4 - Tax Credits/Payments</div>
        <div class="grid">
            <div class="row">
                <div class="cell label">1 Prior Year's Excess Credits</div>
                <div class="cell value">{{ number_format($tax1702q->prior_year_excess_credits, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">2 Tax payment/s for the previous quarter/s</div>
                <div class="cell value">{{ number_format($tax1702q->previous_quarters_tax_payments, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">3 MCIT payment/s for the previous quarter/s</div>
                <div class="cell value">{{ number_format($tax1702q->previous_quarters_mcit_payments, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">4 Creditable Tax Withheld for the previous quarter/s</div>
                <div class="cell value">{{ number_format($tax1702q->previous_quarters_creditable_tax, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">5 Creditable Tax Withheld per BIR Form No. 2307 for this quarter</div>
                <div class="cell value">{{ number_format($tax1702q->current_quarter_creditable_tax, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">6 Tax paid in return previously filed if this is an amended return</div>
                <div class="cell value">{{ number_format($tax1702q->previously_filed_tax_payment, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">Other Tax Credits/Payments</div>
                <div class="cell value"></div>
            </div>
            <div class="row">
                <div class="cell label">A. {{ $tax1702q->other_tax_specify }}</div>
                <div class="cell value">{{ number_format($tax1702q->other_tax_amount, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">B. {{ $tax1702q->other_tax_specify2 }}</div>
                <div class="cell value">{{ number_format($tax1702q->other_tax_amount2, 2) }}</div>
            </div>
            <div class="row">
                <div class="cell label">7 Total Tax Credits/Payments</div>
                <div class="cell value">{{ number_format($tax1702q->total_tax_credits, 2) }}</div>
            </div>
        </div>
    </div>
</body>
</html>