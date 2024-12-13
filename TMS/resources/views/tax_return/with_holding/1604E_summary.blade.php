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
                    <a href="{{ route('with_holding.1604E_sources', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Sources</a>
                    <a href="{{ route('form1604E.create', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Form</a>
                </div>

                <div class="px-6 py-4 grid grid-cols-3 gap-6">
                    <!-- Left Section: Quarterly Table -->
                    <div class="col-span-2 bg-gray-50 p-4 rounded-md">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Quarter</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Taxes Withheld</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Penalties</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Total Amount Remitted</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($quarters as $quarter)
                                <tr>
                                    <td class="py-2 px-4">{{ $quarter['name'] }}</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($quarter['taxes_withheld'], 2) }}</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($quarter['penalties'], 2) }}</td>
                                    <td class="py-2 px-4 text-right">{{ number_format($quarter['total_remitted'], 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-500">No data available for this year.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Right Section: Report Detail -->
                    <div class="bg-white p-4 shadow rounded-md">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Report Detail</h2>
                        <div class="space-y-2">
                            <p><strong>Title:</strong> {{ $with_holding->title ?? 'Annual Withholding Tax' }}</p>
                            <p><strong>Year:</strong> {{ $with_holding->year ?? 'N/A' }}</p>
                            <p><strong>Created By:</strong> {{ $with_holding->creator->name ?? 'N/A' }}</p>
                            <p><strong>Tax Identification Number:</strong> {{ $with_holding->organization->tin ?? '000-000-000-000' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
