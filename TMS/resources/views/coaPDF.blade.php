<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            page-break-inside: avoid;
        }
        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #333;
        }
        p {
            text-align: center;
            font-size: 1.2em;
            margin-bottom: 20px;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 1.1em;
            color: #333;
        }
        table th {
            background-color: #6c7ae0;
            color: #fff;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            padding: 10px;
            text-align: center;
            font-size: 0.9em;
            color: #888;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>{{ $title }}</h1>
    <p>Date: {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th>Name</th>
                <th>Code</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($coas as $coa)
                <tr>
                    <td>{{ $coa->type }}</td>
                    <td>{{ $coa->name }}</td>
                    <td>{{ $coa->code }}</td>
                    <td>{{ $coa->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<footer>
    Generated on {{ now()->format('Y-m-d') }}
</footer>

</body>
</html>
