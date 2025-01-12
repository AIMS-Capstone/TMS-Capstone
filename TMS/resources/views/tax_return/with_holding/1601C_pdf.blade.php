<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form 1601C PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .section {
            margin: 20px 0;
        }
        .label {
            font-weight: bold;
        }
        .field {
            margin-bottom: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">BIR Form No. 1601C</div>

    <!-- General Information -->
    <div class="section">
        <h3>General Information</h3>
        <div class="field">
            <span class="label">Filing Period:</span> {{ $form->filing_period }}
        </div>
        <div class="field">
            <span class="label">Amended Return:</span> {{ $form->amended_return ? 'Yes' : 'No' }}
        </div>
        <div class="field">
            <span class="label">Any Taxes Withheld:</span> {{ $form->any_taxes_withheld ? 'Yes' : 'No' }}
        </div>
        <div class="field">
            <span class="label">Number of Sheets Attached:</span> {{ $form->number_of_sheets }}
        </div>
        <div class="field">
            <span class="label">Alphanumeric Tax Code (ATC):</span> {{ $form->atc->tax_code ?? 'N/A' }}
        </div>
    </div>

    <!-- Agent Information -->
    <div class="section">
        <h3>Agent Information</h3>
        <div class="field">
            <span class="label">Withholding Agent's Name:</span> {{ $form->withholding->organization->registration_name ?? 'N/A' }}
        </div>
        <div class="field">
            <span class="label">TIN:</span> {{ $form->withholding->organization->tin ?? 'N/A' }}
        </div>
        <div class="field">
            <span class="label">RDO Code:</span> {{ $form->withholding->organization->rdo ?? 'N/A' }}
        </div>
        <div class="field">
            <span class="label">Address:</span> {{ $form->withholding->organization->address_line ?? 'N/A' }}
        </div>
        <div class="field">
            <span class="label">Zip Code:</span> {{ $form->withholding->organization->zip_code ?? 'N/A' }}
        </div>
        <div class="field">
            <span class="label">Contact Number:</span> {{ $form->withholding->organization->contact_number ?? 'N/A' }}
        </div>
        <div class="field">
            <span class="label">Email:</span> {{ $form->withholding->organization->email ?? 'N/A' }}
        </div>
        <div class="field">
            <span class="label">Category of Withholding Agent:</span> {{ $form->agent_category ?? 'N/A' }}
        </div>
    </div>

    <!-- Tax Relief -->
    <div class="section">
        <h3>Tax Relief</h3>
        <div class="field">
            <span class="label">Are there payees availing of tax relief?</span> {{ $form->tax_relief ? 'Yes' : 'No' }}
        </div>
        @if ($form->tax_relief)
        <div class="field">
            <span class="label">Tax Relief Details:</span> {{ $form->tax_relief_details ?? 'N/A' }}
        </div>
        @endif
    </div>

    <!-- Computation of Tax -->
    <div class="section">
        <h3>Computation of Tax</h3>
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Amount of Compensation</td>
                    <td>PHP{{ number_format($form->total_compensation, 2) }}</td>
                </tr>
                <tr>
                    <td>Taxable Compensation</td>
                    <td>PHP{{ number_format($form->taxable_compensation, 2) }}</td>
                </tr>
                <tr>
                    <td>Total Taxes Withheld</td>
                    <td>PHP{{ number_format($form->tax_due, 2) }}</td>
                </tr>
                <tr>
                    <td>Add/(Less): Adjustment of Taxes Withheld</td>
                    <td>PHP{{ number_format($form->adjustment_taxes_withheld, 2) }}</td>
                </tr>
                <tr>
                    <td>Less: Tax Remitted in Return Previously Filed</td>
                    <td>PHP{{ number_format($form->tax_remitted_return, 2) }}</td>
                </tr>
                <tr>
                    <td>Other Remittances</td>
                    <td>PHP{{ number_format($form->other_remittances, 2) }}</td>
                </tr>
                <tr>
                    <td>Penalties (Surcharge, Interest, Compromise)</td>
                    <td>PHP{{ number_format($form->surcharge + $form->interest + $form->compromise, 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Amount Still Due</strong></td>
                    <td><strong>PHP{{ number_format($form->total_amount_due, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
