<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-lg font-semibold text-gray-700">Withholding Tax Return > 1604E > Summary</h1>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-4 flex space-x-4 border-b">
                    <a href="{{ route('with_holding.1604E_summary', ['id' => $withHolding->id]) }}" class="pb-2 text-blue-500 border-b-2 border-blue-500 font-semibold">Summary</a>
                    <a href="{{ route('with_holding.1604E_remittances', ['id' => $withHolding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Remittance</a>
                    <a href="{{ route('with_holding.1604E_sources', ['id' => $withHolding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Sources</a>
                    <a href="{{ route('form1604E.create', ['id' => $withHolding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Form</a>
                </div>

                <div class="px-6 py-4">
                    <div class="bg-gray-50 p-4 rounded-md">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Vendor</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">ATC</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Amount</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Tax Rate</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Tax Withheld</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($payeeSummary as $payee)
                                    <tr>
                                        <td class="p-2 border">
                                            {{ $payee['vendor'] }}<br>
                                            {{ $payee['tin'] }}<br>
                                            {{ $payee['address'] }}
                                        </td>
                                        <td class="p-2 border text-right">{{ $payee['atc'] }}</td>
                                        <td class="p-2 border text-right">{{ number_format($payee['amount'], 2) }}</td>
                                        <td class="p-2 border text-right">
                                            {{ $payee['tax_rate'] !== 0 ? number_format($payee['tax_rate'], 2) : 'N/A' }}%
                                        </td>
                                        <td class="p-2 border text-right">{{ number_format($payee['tax_withheld'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-4">No QAP records found for this year.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $qapTransactions->links('vendor.pagination.custom') }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
