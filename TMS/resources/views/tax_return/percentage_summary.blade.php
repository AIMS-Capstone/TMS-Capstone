<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                      <!-- Navigation Tabs -->
                      <nav class="flex space-x-4 mb-6 border-b border-gray-200 pb-2">
                        <a href="{{ route('percentage_return.slsp_data', $taxReturn->id) }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('percentage_return.slsp_data') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            SLSP Data
                        </a>
                        <a href="{{ route('tax-returns.percentage-summary', $taxReturn->id) }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('summary') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            Summary
                        </a>
                        
                        <a href="{{ route('percentage_return.report', $taxReturn->id) }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('percentage_return.report') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                           Report
                        </a>
                        <a href="{{ route('notes_activity') }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('notes_activity') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            Notes & Activity
                        </a>
                  
                    </nav>
              <div class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300 dark:border-neutral-700">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300" id="tableid">
                            <thead class="border-b border-neutral-300 bg-slate-200 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                <tr>
                                    <th scope="col" class="py-4 px-2">ATC</th>
                                    <th scope="col" class="py-4 px-2">Taxable Amount</th>
                                    <th scope="col" class="py-4 px-2">Tax Rate</th>
                                    <th scope="col" class="py-4 px-2">Tax Due</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-300">
                                @foreach ($paginatedSummaryData as $atcCode => $data)
                                    <tr class="hover:bg-gray-100">
                                        <td class="py-4 px-2">{{ $atcCode }}</td>
                                        <td class="py-4 px-2">{{ number_format($data['taxable_amount'], 2) }}</td>
                                        <td class="py-4 px-2">{{ number_format($data['tax_rate'], 2) }}</td>
                                        <td class="py-4 px-2">{{ number_format($data['tax_due'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        

        <!-- Pagination Controls for Summary -->
        <tr>
            <td colspan="12" class="p-4">
                <div class="flex justify-between items-center">
                    <div class="text-sm">
                        Showing {{ $paginatedTaxRows->firstItem() }} to {{ $paginatedTaxRows->lastItem() }} of {{ $paginatedTaxRows->total() }} tax rows
                    </div>
        <div class="mt-4">
            {{ $paginatedSummaryData->links('vendor.pagination.custom') }}
        </div>
    </td>
</tr>
</table>
</div>
</div>
</div>
</div>
</div>

    </div>
</x-app-layout>
