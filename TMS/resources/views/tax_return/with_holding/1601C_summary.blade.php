<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Main -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-lg font-semibold text-gray-700">Withholding Tax Return > 1601C > Summary</h1>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-4 flex space-x-4 border-b">
                    <a href="{{ route('with_holding.1601C_summary', ['id' => $with_holding->id]) }}" class="pb-2 text-blue-500 border-b-2 border-blue-500 font-semibold">Summary</a>
                    <a href="{{ route('with_holding.1601C_sources', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Sources</a>
                    <a href="{{ route('form1601C.create', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Report</a>
                    <a href="#" class="pb-2 text-gray-500 hover:text-blue-500">Notes and Activity</a>
                </div>

                <div class="px-6 py-4 grid grid-cols-3 gap-6">
                    <!-- Left Section: Particulars Table -->
                    <div class="col-span-2 bg-gray-50 p-4 rounded-md">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Particulars</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Amount</th>
                                </tr>
                            </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="py-2 px-4">Total Amount of Compensation</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($totals['total_compensation'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">Statutory Minimum Wage for Minimum Wage Earners (MWEs)</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($totals['statutory_minimum_wage'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">Holiday Pay, Overtime Pay, Night Shift, Differential Pay, Hazard Pay (for MWEs only)</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($totals['holiday_overtime_hazard'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">13th Month Pay and Other Benefits</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($totals['month_13_pay'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">De Minimis Benefits</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($totals['de_minimis_benefits'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">SSS, GSIS, PHIC, HDMF Mandatory Contributions & Union Dues (employee’s share only)</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($totals['mandatory_contributions'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-4">Other Non-Taxable Compensation</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($totals['other_non_taxable_compensation'], 2) }}</td>
                                    </tr>
                                </tbody>
                        </table>
                    </div>

                    <!-- Right Section: Report Detail -->
                    <div class="bg-white p-4 shadow rounded-md">
                        <h2 class="text-lg font-medium text-gray-800 mb-4">Report Detail</h2>
                        <div class="space-y-2">
                            <p><strong>Title:</strong> {{ $with_holding->title ?? 'N/A' }}</p>
                            <p><strong>Month:</strong> {{ \Carbon\Carbon::createFromDate($with_holding->year, $with_holding->month, 1)->format('F Y') ?? 'January 2025' }}</p>
                            <p><strong>Created By:</strong> {{ $with_holding->creator->name ?? 'N/A' }}</p>
                            <p><strong>Tax Identification Number:</strong> {{ $employee_tin ?? '123-456-789-000'}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
