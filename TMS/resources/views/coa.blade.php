@php
$organizationId = session('organization_id');
$organization = \App\Models\OrgSetup::find($organizationId);
@endphp

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Page Main -->
                        <div class="container mx-auto my-auto pt-6">
                            <div class="px-10">
                                <div class="flex flex-row w-full items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg"  class="w-8 h-8" viewBox="0 0 512 512"><path fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M160 144h288M160 256h288M160 368h288"/><circle cx="80" cy="144" r="16" fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="80" cy="256" r="16" fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="80" cy="368" r="16" fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
                                    <p class="font-bold text-3xl auth-color">Chart of Accounts</p>
                                </div>
                            </div>
                            <div class="flex items-center px-10">
                                <div class="flex items-center">            
                                    <p class="taxuri-text text-sm font-normal">The Chart of Accounts feature organizes all your financial accounts in one <br> place, making it simple to manage and track your company’s finances.</p>
                                </div>
                            </div>      
                        </div>

                        <div class="container mx-auto pt-2">
                            <!-- Second Header -->
                            <div class="container mx-auto ps-8">
                                <div class="flex flex-row space-x-2 items-center justify-center">
                                    <div x-data="{ selectedTab: 'Accounts' }" class="w-full">
                                        <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 border-neutral-300" role="tablist" aria-label="tab options">
                                            <button 
                                                @click="selectedTab = 'Accounts'" 
                                                :aria-selected="selectedTab === 'Accounts'" 
                                                :tabindex="selectedTab === 'Accounts' ? '0' : '-1'" 
                                                :class="selectedTab === 'Accounts' ? 'font-bold text-blue-900 ' : 'text-neutral-600 font-medium hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                                class="h-min py-2 text-base relative" 
                                                type="button" 
                                                role="tab" 
                                                aria-controls="tabpanelAccounts">
                                                <span class="block">Accounts</span>
                                                <span 
                                                    :class="selectedTab === 'Accounts' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                                </span>
                                            </button>
                                            <a href="/coa/archive">
                                                <button @click="selectedTab = 'Archive'" :aria-selected="selectedTab === 'Archive'" 
                                                    :tabindex="selectedTab === 'Archive' ? '0' : '-1'" 
                                                    :class="selectedTab === 'Archive' ? 'font-bold text-blue-900' : 'text-neutral-600 font-medium hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'"
                                                    class="h-min py-2 text-base relative" 
                                                    type="button" 
                                                    role="tab" 
                                                    aria-controls="tabpanelArchive" >
                                                    <span class="block">Archive</span>
                                                    <span 
                                                        :class="selectedTab === 'Archive' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                                    </span>
                                                </button>
                                            </a>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <hr>
                        
                            <!-- Filtering Tab/Third Header -->
                            <div x-data="{
                                    selectedTab: (new URL(window.location.href)).searchParams.get('type') || 'All',
                                    checkAll: false,
                                    init() {
                                        this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || 'All';
                                    }
                                }" 
                                x-init="init" 
                                class="w-full p-5">
                                <div @keydown.right.prevent="$focus.wrap().next()" 
                                    @keydown.left.prevent="$focus.wrap().previous()" 
                                    class="flex flex-row text-center overflow-x-auto ps-8" 
                                    role="tablist" 
                                    aria-label="tab options">
                                    
                                    <!-- Tab 1: All -->
                                        <button @click="selectedTab = 'All'; $dispatch('filter', { type: 'All' })"
                                        :aria-selected="selectedTab === 'All'" 
                                        :tabindex="selectedTab === 'All' ? '0' : '-1'" 
                                        :class="selectedTab === 'All' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'"
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelAll">
                                        All
                                    </button>
                                    
                                    <!-- Tab 2: Assets -->
                                    <button @click="selectedTab = 'Assets'; $dispatch('filter', { type: 'Assets' })" 
                                        :aria-selected="selectedTab === 'Assets'" 
                                        :tabindex="selectedTab === 'Assets' ? '0' : '-1'" 
                                        :class="selectedTab === 'Assets' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelAssets">
                                        Assets
                                    </button>
                                    
                                    <!-- Tab 3: Liabilities -->
                                    <button @click="selectedTab = 'Liabilities'; $dispatch('filter', { type: 'Liabilities' })" 
                                        :aria-selected="selectedTab === 'Liabilities'" 
                                        :tabindex="selectedTab === 'Liabilities' ? '0' : '-1'" 
                                        :class="selectedTab === 'Liabilities' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelLiabilities">
                                        Liabilities
                                    </button>
                                    
                                    <!-- Tab 4: Equity -->
                                    <button @click="selectedTab = 'Equity'; $dispatch('filter', { type: 'Equity' })"
                                        :aria-selected="selectedTab === 'Equity'" 
                                        :tabindex="selectedTab === 'Equity' ? '0' : '-1'" 
                                        :class="selectedTab === 'Equity' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelEquity">
                                        Equity
                                    </button>
                                    
                                    <!-- Tab 5: Revenue -->
                                    <button @click="selectedTab = 'Revenue'; $dispatch('filter', { type: 'Revenue' })"
                                        :aria-selected="selectedTab === 'Revenue'" 
                                        :tabindex="selectedTab === 'Revenue' ? '0' : '-1'" 
                                        :class="selectedTab === 'Revenue' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelRevenue">
                                        Revenue
                                    </button>

                                    <!-- Tab 6: Cost of Sales -->
                                    <button @click="selectedTab = 'Cost of Sales'; $dispatch('filter', { type: 'Cost of Sales' })"
                                        :aria-selected="selectedTab === 'Cost of Sales'" 
                                        :tabindex="selectedTab === 'Cost of Sales' ? '0' : '-1'" 
                                        :class="selectedTab === 'Cost of Sales' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelCostofSales">
                                        Cost of Sales
                                    </button>

                                    <!-- Tab 7: Expenses -->
                                    <button @click="selectedTab = 'Expenses'; $dispatch('filter', { type: 'Expense' })"
                                        :aria-selected="selectedTab === 'Expenses'" 
                                        :tabindex="selectedTab === 'Expenses' ? '0' : '-1'" 
                                        :class="selectedTab === 'Expense' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelExpenses">
                                        Expenses
                                    </button>
                                </div>
                            </div>
                            <hr>

                            <!-- Third Header -->
                                <div 
                                    x-data="{
                                        showCheckboxes: false, 
                                        checkAll: false, 
                                        selectedRows: [],
                                        showDeleteCancelButtons: false,
                                        showConfirmArchiveModal: false,
                                        
                                        // Toggle a single row
                                        toggleCheckbox(id) {
                                            if (this.selectedRows.includes(id)) {
                                                this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                                            } else {
                                                this.selectedRows.push(id);
                                            }
                                            console.log(this.selectedRows); // Debugging line
                                        },
                                        
                                        // Toggle all rows
                                        toggleAll() {
                                            this.checkAll = !this.caheckAll;
                                            if (this.checkAll) {
                                                this.selectedRows = {{ json_encode($coas->pluck('id')->toArray()) }}; 
                                            } else {
                                                this.selectedRows = []; 
                                            }
                                            console.log(this.selectedRows); // Debugging line
                                        },
                                        
                                        // Handle archiving
                                        deleteRows() {
                                            if (this.selectedRows.length === 0) {
                                                alert('No rows selected for deletion.');
                                                return;
                                            }

                                            fetch('/coa/deactivate', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({ ids: this.selectedRows })
                                            })
                                            .then(response => {
                                                if (response.ok) {
                                                    location.reload();  
                                                } else {
                                                    alert('Error archiving rows.');
                                                }
                                            });
                                        },
                                        
                                        // Cancel selection
                                        cancelSelection() {
                                            this.selectedRows = []; 
                                            this.checkAll = false;
                                            this.showCheckboxes = false; 
                                            this.showDeleteCancelButtons = false;
                                            this.showConfirmArchiveModal = false;
                                        },
                                        
                                        get selectedCount() {
                                            return this.selectedRows.length;
                                        }
                                    }"
                                    class="mb-12 mx-10 overflow-hidden max-w-full rounded-md border-neutral-300"
                                    >

                                    <!-- Fourth Header -->
                                    <div class="container mx-auto">
                                        <div class="flex flex-row space-x-2 items-center justify-between">
                                            <div class="flex space-x-2 items-center">
                                                <!-- Search bar -->
                                                <div class="relative w-80 p-4">
                                                    <form x-target="tableid" action="/coa" role="search" aria-label="Table" autocomplete="off">
                                                        <input 
                                                            type="search" 
                                                            name="search" 
                                                            class="w-full pl-10 pr-4 py-[7px] text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-900 focus:border-blue-900" 
                                                            aria-label="Search Term" 
                                                            placeholder="Search..." 
                                                            @input.debounce="$el.form.requestSubmit()" 
                                                            @search="$el.form.requestSubmit()"
                                                        >
                                                    </form>
                                                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                                </div>
                                    
                                                <!-- Sort by dropdown -->
                                                <div class="relative inline-block text-left">
                                                    <button id="sortButton" class="flex items-center text-zinc-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                                            <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span id="selectedOption" class="font-normal text-sm text-zinc-600 hover:text-zinc-800 truncate">Sort by</span>
                                                        <svg class="w-2.5 h-2.5 ms-2 transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m1 1 4 4 4-4"/></svg>
                                                    </button>
                                    
                                                    <div id="dropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white hidden z-50">
                                                        <div class="py-2 px-2">
                                                            <span class="block px-4 py-2 text-sm font-bold text-zinc-700">Sort by</span>
                                                            <div data-sort="recently-added" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Added</div>
                                                            <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                                            <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- End row -->
                                            <div class="mx-auto space-x-4 pr-6 flex items-center">
                                                <!-- Add COA Modal -->
                                                <x-add-coa-modal />
                                                <button 
                                                    x-data 
                                                    x-on:click="$dispatch('open-add-modal')" 
                                                    class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition flex items-center space-x-1 group"
                                                    >
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 text-zinc-600 group-hover:text-green-500 transition">
                                                        <g fill="currentColor" fill-rule="evenodd" clip-rule="evenodd">
                                                            <path d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12m10-8a8 8 0 1 0 0 16a8 8 0 0 0 0-16"/>
                                                            <path d="M13 7a1 1 0 1 0-2 0v4H7a1 1 0 1 0 0 2h4v4a1 1 0 1 0 2 0v-4h4a1 1 0 1 0 0-2h-4z"/>
                                                        </g>
                                                    </svg>
                                                    <span class="text-zinc-600 group-hover:text-green-500 transition">Add</span>
                                                </button>
                                                                       
                                                <!-- Livewire Coa Import -->
                                                <livewire:coa-multi-step-import />

                                                <a href="{{ url('download_coa')}}">
                                                    <button
                                                        type="button"
                                                        class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition flex items-center space-x-1 group"
                                                        > 
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-green-500" viewBox="0 0 24 24">
                                                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                                                        </svg> 
                                                        <span class="text-zinc-600 transition group-hover:text-green-500">Download</span>
                                                    </button>
                                                </a>
                                                <button 
                                                    type="button" 
                                                    @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons; $el.disabled = true;" 
                                                    :disabled="selectedRows.length === 1"
                                                    class="border px-3 py-2 rounded-lg text-sm text-gray-600 hover:border-gray-800 hover:text-gray-800 hover:bg-zinc-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                                    >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-zinc-500" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="M3 10H2V4.003C2 3.449 2.455 3 2.992 3h18.016A.99.99 0 0 1 22 4.003V10h-1v10.002a.996.996 0 0 1-.993.998H3.993A.996.996 0 0 1 3 20.002zm16 0H5v9h14zM4 5v3h16V5zm5 7h6v2H9z"/>
                                                    </svg>
                                                    <span class="text-zinc-600 transition group-hover:text-zinc-500">Archive</span>
                                                </button>
                                                
                                                {{-- Show Entries --}}
                                                <div class="relative inline-block space-x-4 text-left sm:w-auto">
                                                    <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" class="flex items-center text-zinc-500 hover:text-zinc-700" type="button">
                                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                        </svg>
                                                    </button>
                                                    <div id="dropdownDots" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-44 origin-top-right">
                                                        <div class="py-2 px-2 text-sm text-zinc-700" aria-labelledby="dropdownMenuIconButton">
                                                            <span class="block px-4 py-2 text-sm font-bold text-zinc-700 text-left">Show Entries</span>
                                                            <div onclick="setEntries(5)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">5 per page</div>
                                                            <div onclick="setEntries(25)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">25 per page</div>
                                                            <div onclick="setEntries(50)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">50 per page</div>
                                                            <div onclick="setEntries(100)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">100 per page</div>
                                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <div class="overflow-x-auto">
                                        <table class="w-full items-start text-left text-sm text-neutral-600" id="tableid">
                                            <thead class="bg-neutral-100 text-sm text-neutral-700">
                                                <tr>
                                                    <th scope="col" class="p-4">
                                                        <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                            <div class="relative flex items-center">
                                                                <input type="checkbox" x-model="checkAll" id="checkAll" @click="toggleAll()" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-[''] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                                />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                            </div>
                                                        </label>
                                                    </th>
                                                    <th scope="col" class="text-left py-4 px-4">Code</th>
                                                    <th scope="col" class="text-left py-4 px-4">Name</th>
                                                    <th scope="col" class="text-left py-4 px-4">Type</th>
                                                    <th scope="col" class="text-left py-4 px-4">Date Created</th> 
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                                @if (count($coas) > 0)
                                                    @foreach ($coas as $coa)
                                                        <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                                            <td class="p-4">
                                                                <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                                    <div class="relative flex items-center">
                                                                        <input type="checkbox" @click="toggleCheckbox('{{ $coa->id }}')" :checked="selectedRows.includes('{{ $coa->id }}')" id="coa{{ $coa->id }}" 
                                                                            class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-[''] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                                        />
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                        </svg>
                                                                    </div>
                                                                </label>
                                                            </td>
                                                            <td class="text-left py-3 px-4">
                                                                <x-view-coa />
                                                                <button @click="$dispatch('open-view-modal', {{ json_encode($coa) }})" class="hover:underline hover:text-blue-500">
                                                                    {{ $coa->code }}
                                                                </button>
                                                            </td>
                                                            <td class="text-left py-3 px-4">
                                                                <x-view-coa />
                                                                <button @click="$dispatch('open-view-modal', {{ json_encode($coa) }})">
                                                                    {{ $coa->name }}
                                                                </button>
                                                            </td>
                                                            <td class="text-left py-3 px-4">
                                                                <b>{{ $coa->type }}</b> <br/> 
                                                                {{ $coa->sub_type ? $coa->sub_type : '' }} 
                                                                @if($coa->description)
                                                                    | {{ $coa->description }}
                                                                @endif
                                                            </td>
                                                            <td class="text-left py-3 px-4">{{ $coa->created_at ? $coa->created_at->format('F j, Y h:i:s A') : 'N/A'}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center p-4">
                                                            <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                            <h1 class="font-extrabold text-neutral-600 text-lg mt-2">No Chart of Accounts yet</h1>
                                                            <p class="text-sm text-neutral-500 mt-2">Start building organization's financial structure<br> with the + button at the top.</p>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Modal -->

                                    <!-- Archive Confirmation Modal -->
                                    <div 
                                        x-show="showConfirmArchiveModal" 
                                        x-cloak 
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                        x-effect="document.body.classList.toggle('overflow-hidden', showConfirmArchiveModal)"
                                    >
                                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full overflow-auto relative">
                                            <div class="flex flex-col items-center">
                                                <button @click="showConfirmArchiveModal = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                                <!-- Icon -->
                                                <div class="mb-4">
                                                    <i class="fas fa-exclamation-triangle text-zinc-700 text-8xl"></i>
                                                </div>

                                                <!-- Title -->
                                                <h2 class="text-2xl font-extrabold text-zinc-700 mb-2">Archive Item(s)</h2>

                                                <!-- Description -->
                                                <p class="text-sm text-zinc-700 text-center">
                                                    You're going to Archive the selected item(s) in the Charts of Account table. Are you sure?
                                                </p>

                                                <!-- Actions -->
                                                <div class="flex justify-center space-x-8 mt-6 w-full">
                                                    <button 
                                                        @click="showConfirmArchiveModal = false; showDeleteCancelButtons = true;" 
                                                        class="px-4 py-2 rounded-lg text-sm text-zinc-700 font-bold transition"
                                                    > 
                                                        Cancel
                                                    </button>
                                                    <button 
                                                        @click="deleteRows(); showConfirmArchiveModal = false;" 
                                                        class="px-5 py-2 bg-zinc-700 hover:bg-zinc-800 text-white rounded-lg text-sm font-medium transition"
                                                    > 
                                                        Archive
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                                        <button @click="showConfirmArchiveModal = true; showDeleteCancelButtons = true;" :disabled="selectedRows.length === 0"
                                            class="border px-3 py-2 rounded-lg text-sm text-gray-800 border-gray-800 bg-zinc-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                         >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-zinc-800" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M3 10H2V4.003C2 3.449 2.455 3 2.992 3h18.016A.99.99 0 0 1 22 4.003V10h-1v10.002a.996.996 0 0 1-.993.998H3.993A.996.996 0 0 1 3 20.002zm16 0H5v9h14zM4 5v3h16V5zm5 7h6v2H9z"/>
                                            </svg>
                                            <span class="text-zinc-600 transition group-hover:text-zinc-800">Archive Selected</span><span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                                        </button>
                                        <button @click="cancelSelection" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"
                                         > Cancel
                                        </button>
                                    </div>
                                    
                                    @if (count($coas) > 0)
                                        {{ $coas->links('vendor.pagination.custom') }}
                                    @endif
                                </div>

                            </div>
                        </div>
                        <!-- End of Main Content -->
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('search', event => {
            window.location.href = `?search=${event.detail.search}`;
        });
        document.addEventListener('filter', event => {
            const type = event.detail.type;

            // Modify URL or use AJAX request to fetch filtered data
            const url = new URL(window.location.href);
            if (type === 'All') {
                url.searchParams.delete('type'); // Remove any existing filter
            } else {
                url.searchParams.set('type', type); // Set filter to the selected type
            }
            window.location.href = url.toString();
        });
        function toggleCheckboxes() {
            // Get all checkbox elements inside the table body
            const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
            // Clear or populate the selectedRows array based on checkAll state
            if (this.checkAll) {
                // Check all checkboxes and add their IDs to selectedRows
                this.selectedRows = Array.from(rowCheckboxes).map(checkbox => checkbox.dataset.id);
            } else {
                // Uncheck all checkboxes and clear selectedRows
                this.selectedRows = [];
            }
            // Update the DOM to reflect the state
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checkAll;
            });
        }

        // FOR SORT BUTTON
        document.getElementById('sortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            const dropdownArrow = this.querySelector('svg:nth-child(3)');
            dropdown.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
        });

        // FOR SORT BY
        function sortItems(criteria) {
            const table = document.querySelector('#tableid tbody');
            const rows = Array.from(table.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
            let sortedRows;

            if (criteria === 'recently-added') {
                sortedRows = rows.sort((a, b) => {
                    const aDate = new Date(a.cells[4].textContent.trim());
                    const bDate = new Date(b.cells[4].textContent.trim());
                    return bDate - aDate; // Newest first
                });
            } else {
                sortedRows = rows.sort((a, b) => {
                    const aText = a.cells[1].textContent.trim().toLowerCase(); // Adjust index for 'Code' column
                    const bText = b.cells[1].textContent.trim().toLowerCase();
                    if (criteria === 'ascending') {
                        return aText.localeCompare(bText);
                    } else if (criteria === 'descending') {
                        return bText.localeCompare(aText);
                    }
                });
            }
            table.innerHTML = '';
            sortedRows.forEach(row => table.appendChild(row));
        }
        // Sort dropdown click event handling
        document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                document.getElementById('selectedOption').textContent = this.textContent; // Update selected option text
                sortItems(criteria);
            });
        });
        window.addEventListener('click', (event) => {
            if (!sortButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        // FOR BUTTON OF SHOW ENTRIES
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('dropdownMenuIconButton').addEventListener('click', function () {
                const dropdown = document.getElementById('dropdownDots');
                dropdown.classList.toggle('hidden');
            });
        });

        function setEntries(entries) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('coa') }}";
            const perPageInput = document.createElement('input');
            perPageInput.type = 'hidden';
            perPageInput.name = 'perPage';
            perPageInput.value = entries;
            const searchInput = document.createElement('input');
            searchInput.type = 'hidden';
            searchInput.name = 'search';
            searchInput.value = "{{ request('search') }}";
            form.appendChild(perPageInput);
            form.appendChild(searchInput);
            document.body.appendChild(form);
            form.submit();
        }

        document.addEventListener('livewire:load', function () {
            Livewire.on('openModalCoaImport', () => {
                Livewire.emit('openModalCoaImport');
            });
        });
        
    </script>
</x-app-layout>