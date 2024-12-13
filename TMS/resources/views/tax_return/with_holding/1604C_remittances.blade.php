<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Main -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-lg font-semibold text-gray-700">Withholding Tax Return > 1604C > Remittances</h1>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-4 flex space-x-4 border-b">
                    <a href="{{ route('with_holding.1604C_remittances', ['id' => $with_holding->id]) }}" class="pb-2 text-blue-500 border-b-2 border-blue-500 font-semibold">Remittance </a>
                    <a href="{{ route('with_holding.1604C_schedule1', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Sources</a>
                    <a href="{{ route('form1604C.create', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Report</a>
                    <a href="#" class="pb-2 text-gray-500 hover:text-blue-500">Notes and Activity</a>
                </div>

                <div class="flex justify-center items-center border-b-5 border-color-sky-900">
                    Part II
                </div>

                <div class="px-6">
                    <p>Summary of Remittances per BIR Form No. 1601C</p>
                </div>

                <div class="px-6 py-4 gap-6">
                    <!-- Left Section: Particulars Table -->
                    <div class=" bg-gray-50 p-4 rounded-md">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Month</th>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Taxes Withheld</th>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Adjustment</th>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Penalties</th>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Total Amount Remitted</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($monthly_totals as $month => $data)
                                    <tr>
                                        <td class="py-2 px-4">{{ $month }}</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($data['taxes_withheld'], 2) }}</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($data['adjustment'], 2) }}</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($data['penalties'], 2) }}</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($data['total_amount_remitted'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
