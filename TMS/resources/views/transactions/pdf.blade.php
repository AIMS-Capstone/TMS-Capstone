<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Transactions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<h1>List of Transactions</h1>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <th>Tax Code</th>
            <th>Tax Type</th>
            <th>COA</th>
            <th>Amount</th>
            <th>Tax Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            @foreach ($transaction->taxRows as $taxRow)
                <tr>
                    <td>{{ $transaction->contactDetails->bus_name ?? 'N/A' }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y') }}</td>
                    <td>{{ $taxRow->atc->tax_code }}</td>
                    <td>{{ $taxRow->taxType->short_code }}</td>
                    <td>{{ $taxRow->coaAccount->name }}</td>
                    <td>PHP{{ number_format($taxRow->amount, 2) }}</td>
                    <td>PHP{{ number_format($taxRow->tax_amount, 2) }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

</body>
</html>
