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
                    <a href="{{ route('with_holding.1604E_summary', ['id' => $with_holding->id]) }}" class="pb-2 text-blue-500 border-b-2 border-blue-500 font-semibold">Summary</a>
                    <a href="{{ route('with_holding.1604E_remittances', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Remittance</a>
                    {{-- <a href="{{ route('with_holding.1604E_sources', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Sources</a> --}}
                    <a href="{{ route('form1604E.create', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Form</a>
                </div>

                <div class="flex flex-row justify-center items-center space-x-4">
                    <div class="flex border-b-8 border-sky-900">
                        <a href="{{ route('with_holding.1604E_remittances', ['id' => $with_holding->id]) }}"><p>Schedule 1</p></a>
                    </div>
                    <div>
                        <a href="{{ route('with_holding.1604E_schedule2', ['id' => $with_holding->id]) }}"><p>Schedule 2</p></a>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <!-- Left Section: Quarterly Table -->
                    <div class=" bg-gray-50 p-4 rounded-md">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Quarter</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Date of Remittance</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Taxes Withheld</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Penalties</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Total Amount Remitted</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($form1601EQRecords as $record)
                                <tr>
                                    <td class="py-2 px-4">
                                        {{ $record->getQuarterText() }}
                                    </td>
                                    <td class="py-2 px-4 text-center">{{ $record->created_at->format('d/m/Y') }}</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($record->total_taxes_withheld, 2) }}</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($record->calculateTotalPenalties(), 2) }}</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($record->calculateTotalRemittances(), 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-gray-500">No data available for this year.</td>
                                </tr>
                                @endforelse

                                <!-- Total Row -->
                                <tr class="bg-gray-100 font-semibold">
                                    <td class="py-2 px-4 text-right" colspan="2">Total</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($totalTaxesWithheld, 2) }}</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($totalPenalties, 2) }}</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($totalRemittances, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
