<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Main -->
                <div class="px-10 py-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">Withholding Tax Return</li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ route('with_holding.1601C') }}" 
                                        class="ms-1 text-sm font-medium {{ Request::routeIs('with_holding.1601C') ? 'font-extrabold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                        1601C
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="" class="ms-1 text-sm font-bold text-blue-900 md:ms-2">Summary</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <hr>

                <!-- Filtering Tab/Third Header -->
                <div x-data="{selectedTab: 'Summary', init() {this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || 'Summary'; }
                    }" x-init="init" class="w-full p-5">
                    <div @keydown.right.prevent="$focus.wrap().next()" 
                        @keydown.left.prevent="$focus.wrap().previous()" 
                        class="flex flex-row text-center overflow-x-auto ps-5" 
                        role="tablist" 
                        aria-label="tab options">
                        
                        <!-- Tab 1: Summary -->
                        <button @click="selectedTab = 'Summary'; $dispatch('filter', { type: 'Summary' })"
                            :aria-selected="selectedTab === 'Summary'" 
                            :tabindex="selectedTab === 'Summary' ? '0' : '-1'" 
                            :class="selectedTab === 'Summary' 
                                ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                : 'text-zinc-600 font-medium hover:text-blue-900'"
                            class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                            type="button" 
                            role="tab" 
                            aria-controls="tabpanelSummary">
                            Summary
                        </button>
                        <!-- Tab 2: Sources -->
                        <a href="{{ route('with_holding.1601C_sources', ['id' => $with_holding->id]) }}">
                            <button @click="selectedTab = 'Sources'" 
                                :aria-selected="selectedTab === 'Sources'" 
                                :tabindex="selectedTab === 'Sources' ? '0' : '-1'" 
                                :class="selectedTab === 'Sources' 
                                    ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                    : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                type="button" 
                                role="tab" 
                                aria-controls="tabpanelSources">
                                Sources
                            </button>
                        </a>
                        <!-- Tab 3: Report -->
                        <a href="{{ route('form1601C.create', ['id' => $with_holding->id]) }}">
                            <button @click="selectedTab = 'Report'" 
                                :aria-selected="selectedTab === 'Report'" 
                                :tabindex="selectedTab === 'Report' ? '0' : '-1'" 
                                :class="selectedTab === 'Report' 
                                    ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                    : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                type="button" 
                                role="tab" 
                                aria-controls="tabpanelReport">
                                Report
                            </button>
                        </a>
                    </div>
                </div>

                <div class="px-6 grid grid-cols-12 gap-5 mb-6">
                    <!-- Left Section: Particulars Table -->
                    <div class="col-span-8 p-4">
                        <table class="min-w-full items-start text-left text-sm text-neutral-600">
                            <thead class="bg-neutral-100 text-sm text-neutral-700">
                                <tr>
                                    <th scope="col" class="py-4 px-4 text-left">Particulars</th>
                                    <th scope="col" class="py-4 px-4 text-left">Amount</th>
                                </tr>
                            </thead>
                                <tbody class="divide-y text-[13px] divide-neutral-300">
                                    <tr>
                                        <td class="py-6 px-4 font-bold">Total Amount of Compensation</td>
                                        <td class="py-6 px-4 font-bold">{{ number_format($totals['total_compensation'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-6 px-4">Statutory Minimum Wage for Minimum Wage Earners (MWEs)</td>
                                        <td class="py-6 px-4">{{ number_format($totals['statutory_minimum_wage'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-6 px-4">Holiday Pay, Overtime Pay, Night Shift, Differential Pay, Hazard Pay (for MWEs only)</td>
                                        <td class="py-6 px-4">{{ number_format($totals['holiday_overtime_hazard'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-6 px-4">13th Month Pay and Other Benefits</td>
                                        <td class="py-6 px-4">{{ number_format($totals['month_13_pay'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-6 px-4">De Minimis Benefits</td>
                                        <td class="py-6 px-4">{{ number_format($totals['de_minimis_benefits'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-6 px-4">SSS, GSIS, PHIC, HDMF Mandatory Contributions & Union Dues (employee’s share only)</td>
                                        <td class="py-6 px-4">{{ number_format($totals['mandatory_contributions'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="py-6 px-4">Other Non-Taxable Compensation</td>
                                        <td class="py-6 px-4">{{ number_format($totals['other_non_taxable_compensation'], 2) }}</td>
                                    </tr>
                                </tbody>
                        </table>
                    </div>

                    <!-- Right Section: Report Detail -->
                    <div class="col-span-4 p-4">
                        <div class="border">
                            <div class="font-bold text-center bg-blue-900 text-white p-3">Report Detail</div>
                            <div class="space-y-2 p-7 divide-y text-xs divide-neutral-300 text-neutral-700">
                                <p class="py-4"><strong>Title</strong><br>{{ $with_holding->title ?? 'N/A' }}</p>
                                <p class="py-4"><strong>Month</strong><br>{{ \Carbon\Carbon::createFromDate($with_holding->year, $with_holding->month, 1)->format('F Y') ?? 'January 2025' }}</p>
                                <p class="py-4"><strong>Created By</strong><br>{{ $with_holding->creator->name ?? 'N/A' }}</p>
                                <p class="py-4"><strong>Tax Identification Number</strong><br>{{ $employee_tin ?? '123-456-789-000'}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
