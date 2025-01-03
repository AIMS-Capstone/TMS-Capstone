<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>0619E Form PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .field {
            margin-bottom: 10px;
        }
        .field span {
            font-weight: bold;
            display: inline-block;
            width: 280px;
        }
        .line {
            border-bottom: 1px solid black;
            display: inline-block;
            width: 300px;
            margin-left: 10px;
        }
        .part-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .checkbox {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 1px solid black;
            text-align: center;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 30px;
            color: #1a73e8;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BIR Form No. 0619-E</h1>
        <p>Monthly Remittance Form of Creditable Income Taxes Withheld (Expanded)</p>
    </div>

    <div class="section">
        <div class="field">
            <span>For the Month (MM/YYYY):</span> <div class="line">{{ $form->for_month }}</div>
        </div>
        <div class="field">
            <span>Due Date (MM/DD/YYYY):</span> <div class="line">{{ $form->due_date }}</div>
        </div>
        <div class="field">
            <span>Amended Form?</span> 
            <div class="checkbox">{{ $form->amended_return ? 'X' : '' }}</div> Yes
            <div class="checkbox">{{ !$form->amended_return ? 'X' : '' }}</div> No
        </div>
        <div class="field">
            <span>Any Taxes Withheld?</span> 
            <div class="checkbox">{{ $form->any_taxes_withheld ? 'X' : '' }}</div> Yes
            <div class="checkbox">{{ !$form->any_taxes_withheld ? 'X' : '' }}</div> No
        </div>
        <div class="field">
            <span>Tax Code:</span> <div class="line">{{ $form->tax_code }}</div>
        </div>
        <div class="field">
            <span>ATC:</span> <div class="line">{{ $form->atc->tax_code }}</div>
        </div>
    </div>

    <div class="section">
        <h2 class="part-title">Part I – Background Information</h2>
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

    <div class="section">
        <h2 class="part-title">Part II – Tax Remittance</h2>
        <div class="field">
            <span>Amount of Remittance:</span> PHP {{ number_format($form->amount_of_remittance, 2) }}
        </div>
        <div class="field">
            <span>Less: Amount of Remittance from Previously Filed:</span> PHP {{ number_format($form->remitted_previous, 2) }}
        </div>
        <div class="field">
            <span>Net Amount of Remittance:</span> PHP {{ number_format($form->net_amount_of_remittance, 2) }}
        </div>
        <div class="field">
            <span>Surcharge:</span> PHP {{ number_format($form->surcharge, 2) }}
        </div>
        <div class="field">
            <span>Interest:</span> PHP {{ number_format($form->interest, 2) }}
        </div>
        <div class="field">
            <span>Compromise:</span> PHP {{ number_format($form->compromise, 2) }}
        </div>
        <div class="field total">
            <span>Total Amount Due:</span> PHP {{ number_format($form->total_amount_due, 2) }}
        </div>
    </div>
</body>
</html>
