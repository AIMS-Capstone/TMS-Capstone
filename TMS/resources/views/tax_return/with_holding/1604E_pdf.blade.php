<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form 1604E PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }
        .section {
            margin-bottom: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .bg-gray {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .border-box {
            border: 1px solid #000;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>REPUBLIC OF THE PHILIPPINES</h1>
        <h2>BUREAU OF INTERNAL REVENUE</h2>
        <p><strong>Annual Information Return of Creditable Income Taxes Withheld (Expanded)</strong></p>
    </div>

    <div class="section border-box">
        <strong>For the Year (YYYY):</strong> {{ $form->year }} <br>
        <strong>Amended Return?</strong> {{ $form->amended_return ? 'Yes' : 'No' }} <br>
        <strong>Number of Sheets Attached:</strong> {{ $form->number_of_sheets ?? 'N/A' }}
    </div>

    <h2>Part I – Background Information</h2>
    <div class="section border-box">
        <p><strong>4. Taxpayer Identification Number (TIN):</strong> {{ $organization->tin ?? 'N/A' }}</p>
        <p><strong>5. RDO Code:</strong> {{ $organization->rdo ?? 'N/A' }}</p>
        <p><strong>6. Withholding Agent's Name:</strong> {{ $organization->registration_name ?? 'N/A' }}</p>
        <p><strong>7. Registered Address:</strong> {{ $organization->address_line ?? 'N/A' }}</p>
        <p><strong>7A. ZIP Code:</strong> {{ $organization->zip_code ?? 'N/A' }}</p>
        <p><strong>8. Category of Withholding Agent:</strong> {{ $form->agent_category }}</p>
        <p><strong>8A. If Private, Top Withholding Agent?</strong> {{ $form->agent_top ? 'Yes' : 'No' }}</p>
        <p><strong>9. Contact Number:</strong> {{ $organization->contact_number ?? 'N/A' }}</p>
        <p><strong>10. Email Address:</strong> {{ $organization->email ?? 'N/A' }}</p>
    </div>

    <h2>Part II – Summary of Remittances</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Quarter</th>
                <th>Date of Remittance (MM/DD/YYYY)</th>
                <th>Taxes Withheld</th>
                <th>Penalties</th>
                <th>Total Remitted</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quarters as $quarter)
                <tr>
                    <td>{{ $quarter['name'] }}</td>
                    <td>{{ $quarter['remittance_date'] }}</td>
                    <td>PHP {{ number_format($quarter['taxes_withheld'], 2) }}</td>
                    <td>PHP {{ number_format($quarter['penalties'], 2) }}</td>
                    <td>PHP {{ number_format($quarter['total_remitted'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gray">
                <th colspan="2">TOTAL</th>
                <th>PHP {{ number_format($totalTaxesWithheld, 2) }}</th>
                <th>PHP {{ number_format($totalPenalties, 2) }}</th>
                <th>PHP {{ number_format($totalRemitted, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
