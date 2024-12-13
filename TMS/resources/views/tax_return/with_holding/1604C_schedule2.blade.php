@php
$organizationId = session('organization_id');
@endphp
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4 py-8">
                <!-- Page Main -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-lg font-semibold text-gray-700">Withholding Tax Return > 1604C > Sources</h1>
                </div>

                    <div class="container mx-auto">
                        <div class="flex flex-row space-x-2 items-center justify-start">
                            <!-- Search row -->
                            <div class="relative w-80 p-4">
                                <form x-target="tableid" action="/1604C" role="search" aria-label="Table" autocomplete="off">
                                    <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900" 
                                        aria-label="Search Term" 
                                        placeholder="Search..." 
                                        @input.debounce="$el.form.requestSubmit()" 
                                        @search="$el.form.requestSubmit()"
                                    >
                                </form>
                                <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                        <!-- Tabs -->
                        <div class="px-6 py-4 flex space-x-4 border-b">
                            <a href="{{ route('with_holding.1604C_remittances', ['id' => $with_holding->id]) }}" class="pb-2 text-blue-500 border-b-2 border-blue-500 font-semibold">Remittance </a>
                            <a href="{{ route('with_holding.1604C_schedule1', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Sources</a>
                            <a href="{{ route('form1604C.create', ['id' => $with_holding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Report</a>
                        </div>
                    <hr>

                    <div class="flex flex-row justify-center items-center space-x-4">
                            <div>
                                <a href="{{ route('with_holding.1604C_schedule1', ['id' => $with_holding->id]) }}"><p>Schedule 1</p></a>
                            </div>
                            <div class="flex border-b-8 border-sky-900">
                                <a href="{{ route('with_holding.1604C_schedule2', ['id' => $with_holding->id]) }}"><p>Schedule 2</p></a>
                            </div>
                            
                    </div>
                    <hr>

                    <div class="justify-start px-4">
                        <p class="font-bold">Schedule 2 - Alphalist of Employees (Minimum Wage Earners)</p>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border-collapse border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Employee</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Gross Compensation</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Total Non-Taxable Compensation</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Taxable Compensation</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Tax Due</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($aggregatedData as $data)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ $data['employee']->first_name ?? 'N/A' }} {{ $data['employee']->last_name ?? '' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ number_format($data['gross_compensation'], 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ number_format($data['non_taxable_compensation'], 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ number_format($data['taxable_compensation'], 2) }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-700">
                                            {{ number_format($data['tax_due'], 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-2 text-center text-sm text-gray-500">
                                            No data available for minimum wage earners in the selected year.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($paginatedSources->hasPages())
                        <div class="mt-4">
                            {{ $paginatedSources->links('vendor.pagination.custom') }}
                        </div>
                    @endif
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('search', event => {
            window.location.href = `?search=${event.detail.search}`;
        });
    </script>   
</x-app-layout>
