<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Header -->
                <div class="px-10 py-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">Withholding Tax Return</li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ route('with_holding.1604E') }}" 
                                        class="ms-1 text-sm font-medium {{ Request::routeIs('with_holding.1604E') ? 'font-extrabold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                        1604E
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
                        <!-- Tab 2: Remittances -->
                        <a href="{{ route('with_holding.1604E_remittances', ['id' => $with_holding->id]) }}">
                            <button @click="selectedTab = 'Remittances'" 
                                :aria-selected="selectedTab === 'Remittances'" 
                                :tabindex="selectedTab === 'Remittances' ? '0' : '-1'" 
                                :class="selectedTab === 'Remittances' 
                                    ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                    : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                type="button" 
                                role="tab" 
                                aria-controls="tabpanelRemittances">
                                Remittances
                            </button>
                        </a>
                        <!-- Tab 3: Sources -->
                        <a href="{{ route('with_holding.1604E_sources', ['id' => $with_holding->id]) }}">
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
                        <!-- Tab 4: Report -->
                        <a href="{{ route('form1604E.create', ['id' => $with_holding->id]) }}">
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
                    <!-- Left Section: Quarterly Table -->
                    <div class="col-span-8 p-4">
                        <table class="min-w-full items-start text-left text-sm text-neutral-600">
                            <thead class="bg-neutral-100 text-sm text-neutral-700">
                                <tr>
                                    <th class="py-4 px-4">Quarter</th>
                                    <th class="py-4 px-4">Taxes Withheld</th>
                                    <th class="py-4 px-4">Penalties</th>
                                    <th class="py-4 px-4">Total Amount Remitted</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y text-[13px] divide-neutral-300">
                                @forelse($quarters as $quarter)
                                <tr>
                                    <td class="py-6 px-4">{{ $quarter['name'] }}</td>
                                    <td class="py-6 px-4">{{ number_format($quarter['taxes_withheld'], 2) }}</td>
                                    <td class="py-6 px-4">{{ number_format($quarter['penalties'], 2) }}</td>
                                    <td class="py-6 px-4">{{ number_format($quarter['total_remitted'], 2) }}</td>
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
                    <div class="col-span-4 p-4">
                        <div class="border">
                            <div class="font-bold text-center bg-blue-900 text-white p-3">Report Detail</div>
                            <div class="space-y-2 p-7 divide-y text-xs divide-neutral-300 text-neutral-700">
                                <p class="py-4"><strong>Title:</strong> {{ $with_holding->title ?? 'Annual Withholding Tax' }}</p>
                                <p class="py-4"><strong>Year:</strong> {{ $with_holding->year ?? 'N/A' }}</p>
                                <p class="py-4"><strong>Created By:</strong> {{ $with_holding->creator->name ?? 'N/A' }}</p>
                                <p class="py-4"><strong>Tax Identification Number:</strong> {{ $with_holding->organization->tin ?? '000-000-000-000' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
