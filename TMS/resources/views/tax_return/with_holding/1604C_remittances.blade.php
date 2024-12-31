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
                                    <a href="{{ route('with_holding.1604C') }}" 
                                        class="ms-1 text-sm font-medium {{ Request::routeIs('with_holding.1604C') ? 'font-extrabold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                        1604C
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="" class="ms-1 text-sm font-bold text-blue-900 md:ms-2">Remittance</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <hr>

                <!-- Filtering Tab/Third Header -->
                <div x-data="{selectedTab: 'Remittance', init() {this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || 'Remittance'; }
                    }" x-init="init" class="w-full p-5">
                    <div @keydown.right.prevent="$focus.wrap().next()" 
                        @keydown.left.prevent="$focus.wrap().previous()" 
                        class="flex flex-row text-center overflow-x-auto ps-5" 
                        role="tablist" 
                        aria-label="tab options">
                        
                        <!-- Tab 1: Remittance -->
                        <button @click="selectedTab = 'Remittance'; $dispatch('filter', { type: 'Remittance' })"
                            :aria-selected="selectedTab === 'Remittance'" 
                            :tabindex="selectedTab === 'Remittance' ? '0' : '-1'" 
                            :class="selectedTab === 'Remittance' 
                                ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                : 'text-zinc-600 font-medium hover:text-blue-900'"
                            class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                            type="button" 
                            role="tab" 
                            aria-controls="tabpanelRemittance">
                            Remittance
                        </button>
                        <!-- Tab 2: Sources -->
                        <a href="{{ route('with_holding.1604C_schedule1', ['id' => $with_holding->id]) }}">
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
                        <a href="{{ route('form1604C.create', ['id' => $with_holding->id]) }}">
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

                <div class="container mx-auto overflow-hidden">
                    <!-- Transactions Header -->
                    <div class="container mx-auto ps-8">
                        <div class="flex flex-row space-x-2 items-center justify-center">
                            <div x-data="{
                                    selectedType: new URLSearchParams(window.location.search).get('type') || 'Part II',
                                    filterTransactions() {
                                        const url = new URL(window.location.href);
                                        url.searchParams.set('type', this.selectedType);
                                        window.location.href = url.toString();
                                    }
                                }" class="w-full">
                                <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-8 border-neutral-300" role="tablist" aria-label="tab options">
                                    <button 
                                        @click="selectedType = 'Part II'; filterTransactions()" 
                                        :aria-selected="selectedType === 'Part II'"
                                        :tabindex="selectedType === 'Part II' ? '0' : '-1'" 
                                        :class="selectedType === 'Part II' ? 'font-bold text-blue-900 ' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                        class="h-min py-2 text-base relative" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelPartII">
                                        <span class="block">Part II</span>
                                        <span 
                                            :class="selectedType === 'Part II' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-12 mt-6 mx-12 overflow-hidden max-w-full border-neutral-300">
                        <div class="text-sm font-bold text-neutral-700 mb-4"><p>Summary of Remittances per BIR Form No. 1601C</p></div>
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-left text-sm text-neutral-600" id="tableid">
                                <thead class="bg-neutral-100 text-sm text-neutral-700">
                                    <tr>
                                        <th class="py-4 px-4">Month</th>
                                        <th class="py-4 px-4">Taxes Withheld</th>
                                        <th class="py-4 px-4">Adjustment</th>
                                        <th class="py-4 px-4">Penalties</th>
                                        <th class="py-4 px-4">Total Amount Remitted</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                    @foreach ($monthly_totals as $month => $data)
                                        <tr>
                                            <td class="py-4 px-4">{{ $month }}</td>
                                            <td class="py-4 px-4">{{ number_format($data['taxes_withheld'], 2) }}</td>
                                            <td class="py-4 px-4">{{ number_format($data['adjustment'], 2) }}</td>
                                            <td class="py-4 px-4">{{ number_format($data['penalties'], 2) }}</td>
                                            <td class="py-4 px-4">{{ number_format($data['total_amount_remitted'], 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6" class="text-center p-4">
                                                <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                <h1 class="font-bold text-lg mt-2">No Remittances yet</h1>
                                                {{-- <p class="text-sm text-neutral-500 mt-2">Start generating with the + button <br>at the top.</p> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
