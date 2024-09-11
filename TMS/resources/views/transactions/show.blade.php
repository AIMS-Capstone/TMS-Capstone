<x-app-layout>

    <div class="container">
        <div class="p-6 bg-white shadow rounded-lg">
            <!-- Transaction Details -->
            <h2 class="text-2xl font-bold mb-4">Transaction Details</h2>
            <div class="mb-4">
                <strong>Date:</strong> {{ $transaction->date }}
            </div>
            <div class="mb-4">
                <strong>Invoice Number:</strong> {{ $transaction->inv_number }}
            </div>
            <div class="mb-4">
                <strong>Reference:</strong> {{ $transaction->reference }}
            </div>
            <div class="mb-4">
                <strong>Total Amount:</strong> {{ number_format($transaction->total_amount, 2) }}
            </div>

            @if ($transaction->transaction_type === 'Journal')
                <!-- Journal Entries Table -->
                <h3 class="text-xl font-semibold mb-4">Journal Entries</h3>
                <table class="table-auto w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">COA (Account)</th>
                            <th class="px-4 py-2">Debit</th>
                            <th class="px-4 py-2">Credit</th>
                     
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taxRows as $index => $row)
                            <tr>
                                <td class="border px-4 py-2">{{ $row->description }}</td>
                                <td class="border px-4 py-2">{{ $row->coaAccount->code }}-{{ $row->coaAccount->name }}</td>
                                <td class="border px-4 py-2">{{ number_format($row->debit, 2) }}</td>
                                <td class="border px-4 py-2">{{ number_format($row->credit, 2) }}</td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <!-- Tax Rows Table -->
                <h3 class="text-xl font-semibold mb-4">Tax Rows</h3>
                <table class="table-auto w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Tax Type</th>
                            <th class="px-4 py-2">ATC</th>
                            <th class="px-4 py-2">COA</th>
                            <th class="px-4 py-2">Amount (VAT Inclusive)</th>
                            <th class="px-4 py-2">Tax Amount</th>
                            <th class="px-4 py-2">Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taxRows as $taxRow)
                            <tr>
                                <td class="border px-4 py-2">{{ $taxRow->description }}</td>
                                <td class="border px-4 py-2">{{ $taxRow->taxType->tax_type }} ({{ $taxRow->taxType->VAT }}%)</td>
                                <td class="border px-4 py-2">{{ $taxRow->atc->tax_code }} ({{ $taxRow->atc->tax_rate }}%)</td>
                                <td class="border px-4 py-2">{{ $taxRow->coa }}</td>
                                <td class="border px-4 py-2">{{ number_format($taxRow->amount, 2) }}</td>
                                <td class="border px-4 py-2">{{ number_format($taxRow->tax_amount, 2) }}</td>
                                <td class="border px-4 py-2">{{ number_format($taxRow->net_amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
