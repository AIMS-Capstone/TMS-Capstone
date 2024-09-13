<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden sm:rounded-lg">
                <div class="container">
                    <div class="p-6 bg-white shadow rounded-lg">
                        <button>
                            <a href="{{ url('/transactions') }}" class="flex items-center space-x-2 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g></svg>
                                <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go back</span>
                            </a>
                        </button>
                        <!-- Transaction Details -->
                        <h2 class="text-3xl font-bold taxuri-color mb-4">Transaction Details: Ref {{ $transaction->reference }}</h2>
                        <hr class="mb-4">
                        <div class="mb-4">
                            <strong class="taxuri-color">Date:</strong> {{ $transaction->date }}
                        </div>
                        <div class="mb-4">
                            <strong class="taxuri-color">Invoice Number:</strong> {{ $transaction->inv_number }}
                        </div>
                        <div class="mb-4">
                            <strong class="taxuri-color">Reference:</strong> {{ $transaction->reference }}
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
                            <div class="mb-20 mt-10">
                                <div class="flex justify-end">
                                    <div class="mb-4 taxuri-color">
                                        <strong>Total Amount Due:</strong> {{ number_format($transaction->total_amount_credit, 2) }} | {{ number_format($transaction->total_amount_debit, 2) }} 
                             
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Tax Rows Table -->
                            {{-- <h3 class="text-xl font-semibold mb-4">Tax Rows</h3> --}}
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
                            <div class="mb-20 mt-10">
                                <div class="flex justify-end">
                                    <div class="mb-4 taxuri-color">
                                        <strong>Total Amount Due:</strong> {{ number_format($transaction->total_amount, 2) }}
                                    </div>
                                </div>
                            </div>
                        @endif
                 
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
