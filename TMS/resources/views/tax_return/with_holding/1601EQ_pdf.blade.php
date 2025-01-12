<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>1601EQ Form PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 15px;
            padding: 10px 20px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .field span {
            font-weight: bold;
            display: inline-block;
            width: 240px;
        }
        .line {
            border-bottom: 1px solid black;
            display: inline-block;
            width: 240px;
        }
        .checkbox {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 1px solid black;
            text-align: center;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #1a73e8;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        .table-heading {
            background-color: #f3f3f3;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Form Header -->
    <div class="header">
        <h1>BIR Form No. 1601-EQ</h1>
        <p>Quarterly Remittance Return of Creditable Income Taxes Withheld (Expanded)</p>
    </div>

    <!-- Section 1 - Form Details -->
    <div class="section">
        <div class="field">
            <span>For the Year:</span> <div class="line">{{ $form->year }}</div>
        </div>
        <div class="field">
            <span>Quarter:</span> <div class="line">{{ $form->getQuarterText() }}</div>
        </div>
        <div class="field">
            <span>Amended Return?</span>
            <div class="checkbox">{{ $form->amended_return ? 'X' : '' }}</div> Yes
            <div class="checkbox">{{ !$form->amended_return ? 'X' : '' }}</div> No
        </div>
        <div class="field">
            <span>Any Taxes Withheld?</span>
            <div class="checkbox">{{ $form->any_taxes_withheld ? 'X' : '' }}</div> Yes
            <div class="checkbox">{{ !$form->any_taxes_withheld ? 'X' : '' }}</div> No
        </div>
    </div>

    <!-- Background Information Section -->
    <div class="section">
        <h2>Part I – Background Information</h2>
        <div class="grid-container">
            <div class="field">
                <span>TIN:</span> <div class="line">{{ $form->organization->tin }}</div>
            </div>
            <div class="field">
                <span>RDO Code:</span> <div class="line">{{ $form->organization->rdo }}</div>
            </div>
            <div class="field">
                <span>Withholding Agent's Name:</span> <div class="line">{{ $form->organization->registration_name }}</div>
            </div>
            <div class="field">
                <span>Registered Address:</span> <div class="line">{{ $form->organization->address_line }}</div>
            </div>
            <div class="field">
                <span>ZIP Code:</span> <div class="line">{{ $form->organization->zip_code }}</div>
            </div>
            <div class="field">
                <span>Category of Withholding Agent:</span> 
                <div class="checkbox">{{ $form->category ? 'X' : '' }}</div> Private
                <div class="checkbox">{{ !$form->category ? 'X' : '' }}</div> Government
            </div>
            <div class="field">
                <span>Contact Number:</span> <div class="line">{{ $form->organization->contact_number }}</div>
            </div>
            <div class="field">
                <span>Email Address:</span> <div class="line">{{ $form->organization->email }}</div>
            </div>
        </div>
    </div>

    <!-- ATC Breakdown -->
    <h2>ATC Breakdown</h2>
    <table>
        <thead>
            <tr class="table-heading">
                <th>Tax Code</th>
                <th>Tax Base</th>
                <th>Tax Rate</th>
                <th>Tax Withheld</th>
            </tr>
        </thead>
        <tbody>
            @foreach($form->atcDetails as $detail)
            <tr>
                <td>{{ $detail->atc->tax_code }}</td>
                <td>PHP {{ number_format($detail->tax_base, 2) }}</td>
                <td>{{ $detail->tax_rate }}%</td>
                <td>PHP {{ number_format($detail->tax_withheld, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tax Computation Section -->
    <h2>Part II – Computation of Tax</h2>
    <div class="section">
        @foreach(['remittances_1st_month', 'remittances_2nd_month', 'remitted_previous', 'over_remittance', 'other_payments'] as $field)
        <div class="field">
            <span>{{ ucfirst(str_replace('_', ' ', $field)) }}:</span> PHP {{ number_format($form->$field, 2) }}
        </div>
        @endforeach
        <div class="field">
            <span>Total Remittances Made:</span> PHP {{ number_format($form->total_remittances_made, 2) }}
        </div>
        <div class="field">
            <span>Total Amount Due:</span> PHP {{ number_format($form->total_amount_due, 2) }}
        </div>
        <div class="field total">
            <span>Total Taxes Withheld:</span> PHP {{ number_format($form->total_taxes_withheld, 2) }}
        </div>
    </div>
</body>
</html>
