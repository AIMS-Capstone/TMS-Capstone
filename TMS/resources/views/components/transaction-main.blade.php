<!-- Page Header -->
@props(['transactions', 'purchaseCount', 'salesCount', 'allTransactionsCount','journalCount'])
<div class="container mx-auto my-4 pt-6">
    <div class="px-10">
        <div class="flex flex-row w-64 items-start space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 256 256"><path fill="#1e3a8a" d="M216 40H40a16 16 0 0 0-16 16v152a8 8 0 0 0 11.58 7.15L64 200.94l28.42 14.21a8 8 0 0 0 7.16 0L128 200.94l28.42 14.21a8 8 0 0 0 7.16 0L192 200.94l28.42 14.21A8 8 0 0 0 232 208V56a16 16 0 0 0-16-16m-40 104H80a8 8 0 0 1 0-16h96a8 8 0 0 1 0 16m0-32H80a8 8 0 0 1 0-16h96a8 8 0 0 1 0 16"/></svg>
            <p class="font-bold text-3xl text-left taxuri-color">Transactions</p>
        </div>
    </div>
    <div class="flex justify-between items-center px-10">
        <div class="flex items-center px-2">            
            <p class="font-normal text-sm text-zinc-700">The Transactions feature ensures accurate tracking and categorization <br> of each transaction.</p>
        </div>
        <div class="items-end float-end relative sm:w-auto" 
            x-data="{ selectedTab: (new URL(window.location.href)).searchParams.get('type') || 'All' }" 
            @filter.window="selectedTab = $event.detail.type">
            <button type="button" 
                    id="dropdownDefaultButton" 
                    :class="selectedTab === 'All' ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-900 hover:bg-blue-950'"
                    :disabled="selectedTab === 'All'"
                    class="text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none focus:ring-2 focus:ring-gray-300">
                <i class="fas fa-plus-circle mr-1"></i>
                Add Transaction
            </button>
            <div id="dropdown" class="absolute z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-md w-48 mt-2">
                <ul class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                    <!-- Options for Sales tab -->
                    <li x-show="selectedTab === 'Sales'">
                        <a href="{{ url('/transactions/create?type=sales') }}" class="block px-4 py-2 hover-dropdown">Add Manual</a>
                    </li>
                    <li x-show="selectedTab === 'Sales'">
                        <livewire:multi-step-import-modal/>
                    </li>
                    <li x-show="selectedTab === 'Sales'">
                        {{-- <a href="{{ url('/transactions/upload') }}" class="block px-4 py-2 hover-dropdown">Upload Image</a> --}}
                    </li>
               
                    <!-- Options for Purchase tab -->
                    <li x-show="selectedTab === 'Purchase'">
                        <a href="{{ url('/transactions/create?type=purchase') }}" class="block px-4 py-2 hover-dropdown">Add Manual</a>
                    </li>
                    <li x-show="selectedTab === 'Purchase'">
                        <livewire:purchase-multi-step-import/>
                    </li>
                    <li x-show="selectedTab === 'Purchase'">
                        <a href="{{ url('/transactions/upload') }}" class="block px-4 py-2 hover-dropdown">Upload Image</a>
                    </li>

                    <!-- Options for Journal tab -->
                    <li x-show="selectedTab === 'Journal'">
                        <a href="{{ url('/transactions/create?type=journal') }}" class="block px-4 py-2 hover-dropdown">Add Manual</a>
                    </li>
                    <li x-show="selectedTab === 'Journal'">
                        <livewire:journal-multi-step-import/>
                    </li>

                </ul>
            </div>
        </div>
    </div>  
</div>  

    <br>
    <hr>

    <div x-data="{ showCheckboxes: false, selectedTab: 'All', checkAll: false, showDeleteCancelButtons: false,  selectedRows: [], showConfirmDeleteModal: false, checkAll: false,   // Toggle a single row
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
                this.selectedRows = {{ json_encode($transactions->pluck('id')->toArray()) }}; 
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

            fetch('/transaction/destroy', {
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
            this.showConfirmDeleteModal = false;
        },
        
        get selectedCount() {
            return this.selectedRows.length;
        }
    }"   class="container mx-auto pt-2 ">
    <!-- Second Header -->
    <div class="container mx-auto ps-8">
        <div class="flex flex-row space-x-2 items-center">
            <!-- Search row -->
            <div class="relative w-80 p-4">
                <form x-target="tableTransaction" action="/transactions" role="search" aria-label="Table" autocomplete="off">
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

            <div class="flex flex-row items-center space-x-4">
                <div class="relative inline-block text-left sm:w-auto w-full">
                    <button id="filterButton" class="flex items-center text-zinc-600 hover:text-zinc-800 w-full hover:shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                            <path fill="none" stroke="#696969" stroke-width="2" d="M18 4H6c-1.105 0-2.026.91-1.753 1.98a8.02 8.02 0 0 0 4.298 5.238c.823.394 1.455 1.168 1.455 2.08v6.084a1 1 0 0 0 1.447.894l2-1a1 1 0 0 0 .553-.894v-5.084c0-.912.632-1.686 1.454-2.08a8.02 8.02 0 0 0 4.3-5.238C20.025 4.91 19.103 4 18 4z"/>
                        </svg>
                        <span id="selectedFilter" class="font-normal text-sm text-zinc-600 truncate">Filter</span>
                        <svg id="dropdownArrow" class="w-2.5 h-2.5 ms-2 transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m1 1 4 4 4-4"/></svg>
                    </button>
                
                    <div id="dropdownFilter" class="absolute mt-2 w-[340px] rounded-lg shadow-lg bg-white hidden z-40">
                        <div class="py-2 px-2">
                            <span class="block px-4 py-2 text-xs font-bold text-zinc-700">Filter</span>
                            <span class="block px-4 py-1 text-zinc-700 font-bold text-xs">Timeframe</span>
                            <div class="px-4 py-2 text-xs colspan-2 flex justify-between items-center space-x-4">
                                <div class="flex flex-col w-full">
                                    <label for="fromDate" class="text-xs text-zinc-700 font-semibold mb-1">Start Date</label>
                                    <input id="fromDate" type="date" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" placeholder="Start Date"/>
                                </div>
                                <p class="text-xs text-zinc-700 font-semibold">to</p>
                                <div class="flex flex-col w-full">
                                    <label for="toDate" class="text-xs text-zinc-700 font-semibold mb-1">End Date</label>
                                    <input id="toDate" type="date" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" placeholder="End Date"/>
                                </div>
                            </div>
                            <span class="block px-4 py-1 text-zinc-700 font-bold text-xs">Status</span>
                            <div id="statusFilterContainer" class="block px-4 py-2 text-xs">
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" value="Paid" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                    <span>Paid</span>
                                </label>
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" value="Awaiting Payment" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                    <span>Awaiting Payment</span>
                                </label>
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" value="Posted" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                    <span>Posted</span>
                                </label>
                                <label class="flex items-center space-x-2 py-1">
                                    <input type="checkbox" value="Draft" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                    <span>Draft</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 px-4 py-1.5 mb-1.5">
                            <button id="applyFiltersButton" class="flex items-center bg-white border border-gray-300 hover:border-green-500 hover:bg-green-100 hover:text-green-500 transition rounded-md px-3 py-1.5 whitespace-nowrap group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 fill-current group-hover:fill-green-500 hover:border-green-500 hover:text-green-500 transition" viewBox="0 0 32 32">
                                    <path fill="currentColor" d="M16 3C8.832 3 3 8.832 3 16s5.832 13 13 13s13-5.832 13-13S23.168 3 16 3m0 2c6.087 0 11 4.913 11 11s-4.913 11-11 11S5 22.087 5 16S9.913 5 16 5m-1 5v5h-5v2h5v5h2v-5h5v-2h-5v-5z"/>
                                </svg>
                                <span class="text-zinc-700 transition group-hover:text-green-500 text-xs">Apply Filter</span>
                            </button>
                            <button id="clearFiltersButton" class="text-xs text-zinc-600 hover:text-zinc-900 whitespace-nowrap">Clear all filters</button>
                        </div>
                    </div>
                </div>

                <div class="h-8 border-l border-zinc-300"></div>

                <!-- Sort by dropdown -->
                <div class="relative inline-block text-left min-w-[150px]">
                    <button id="sortButton" class="flex items-center text-zinc-600 w-full hover:shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24"><path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                        </svg>
                        <span id="selectedOption" class="font-normal text-sm text-zinc-600 hover:text-zinc-800 truncate">Sort by</span>
                        <svg class="w-2.5 h-2.5 ms-2 transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m1 1 4 4 4-4"/></svg>
                    </button>
        
                    <div id="dropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white hidden z-30">
                        <div class="py-2 px-2">
                            <span class="block px-4 py-2 text-sm font-bold text-zinc-700">Sort by</span>
                            <div data-sort="recently-added" class="block px-4 py-2 w-full text-xs hover-dropdown">Recently Added</div>
                            <div data-sort="ascending" class="block px-4 py-2 w-full text-xs hover-dropdown">Ascending</div>
                            <div data-sort="descending" class="block px-4 py-2 w-full text-xs hover-dropdown">Descending</div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Buttons and Show Entries -->
            <div class="flex space-x-4 ps-72 items-center">
                <button 
                    type="button" 
                    @click="showCheckboxes = !showCheckboxes;    showDeleteCancelButtons: false, showDeleteCancelButtons = !showDeleteCancelButtons; $el.disabled = true;" 
                    :disabled="selectedRows.length === 1"
                    class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-red-500 hover:text-red-500 hover:bg-red-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                    <span class="text-zinc-600 transition group-hover:text-red-500">Delete</span>
                </button>
                <a href="{{ url('transaction/download')}}">
                    <button type="button" class="border px-3 py-2 text-sm text-zinc-600 rounded-lg hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition flex items-center group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5 transition group-hover:text-green-500" viewBox="0 0 24 24">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                        </svg> 
                        <span class="text-zinc-600 transition group-hover:text-green-500">Download</span>
                    </button>
                </a>
            </div>
            <div class="relative inline-block space-x-4 text-left">
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

    <hr>

    <!-- Filtering Tab -->
    <div x-data="{
        selectedTab: (new URL(window.location.href)).searchParams.get('type') || 'All',
        checkAll: false,
        init() {this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || 'All'; }}" 
        x-init="init" 
        class="w-full p-5">
        <div @keydown.right.prevent="$focus.wrap().next()" 
            @keydown.left.prevent="$focus.wrap().previous()" 
            class="flex flex-row text-center gap-2 overflow-x-auto ps-8" 
            role="tablist" 
            aria-label="tab options">
            
            <!-- Tab 1: All -->
            <button @click="selectedTab = 'All'; $dispatch('filter', { type: 'All' }); updateURL('All')"
                :aria-selected="selectedTab === 'All'"
                :tabindex="selectedTab === 'All' ? '0' : '-1'"
                :class="selectedTab === 'All' 
                    ? 'font-bold text-blue-900 bg-slate-200 border rounded-lg' 
                    : 'text-neutral-600 font-medium hover:text-blue-900'"
                class="flex h-min items-center gap-2 px-4 py-2 text-sm"
                type="button"
                role="tab"
                aria-controls="tabpanelAll">
                All
                <span :class="selectedTab === 'All'
                    ? 'text-white bg-blue-900'
                    : 'bg-slate-500 text-white'"
                    class="text-xs font-medium px-1 rounded-full">{{$allTransactionsCount}}</span>
            </button>
            
            <!-- Tab 2: Sales -->
            <button @click="selectedTab = 'Sales'; $dispatch('filter', { type: 'Sales' }); updateURL('Sales')"
                :aria-selected="selectedTab === 'Sales'"
                :tabindex="selectedTab === 'Sales' ? '0' : '-1'"
                :class="selectedTab === 'Sales'
                    ? 'font-bold text-blue-900 bg-slate-200 border rounded-lg'
                    : 'text-neutral-600 font-medium hover:text-blue-900'"
                class="flex h-min items-center gap-2 px-4 py-2 text-sm"
                type="button"
                role="tab"
                aria-controls="tabpanelSales">
                Sales
                <span :class="selectedTab === 'Sales'
                    ? 'text-white bg-blue-900'
                    : 'bg-slate-500 text-white'"
                    class="text-xs font-medium px-1 rounded-full">{{$salesCount}}</span>
            </button>
            
            <!-- Tab 3: Purchases -->
            <button @click="selectedTab = 'Purchase'; $dispatch('filter', { type: 'Purchase' }); updateURL('Purchase')"
                :aria-selected="selectedTab === 'Purchase'"
                :tabindex="selectedTab === 'Purchase' ? '0' : '-1'"
                :class="selectedTab === 'Purchase'
                    ? 'font-bold text-blue-900 bg-slate-200 border rounded-lg'
                    : 'text-neutral-600 font-medium hover:text-blue-900'"
                class="flex h-min items-center gap-2 px-4 py-2 text-sm"
                type="button"
                role="tab"
                aria-controls="tabpanelPurchases">
                Purchases
                <span :class="selectedTab === 'Purchases'
                    ? 'text-white bg-blue-900'
                    : 'bg-slate-500 text-white'"
                    class="text-xs font-medium px-1 rounded-full">{{$purchaseCount}}</span>
            </button>
            
            <!-- Tab 4: Journal -->
            <button @click="selectedTab = 'Journal'; $dispatch('filter', { type: 'Journal' }); updateURL('Journal')"
                :aria-selected="selectedTab === 'Journal'"
                :tabindex="selectedTab === 'Journal' ? '0' : '-1'"
                :class="selectedTab === 'Journal'
                    ? 'font-bold text-blue-900 bg-slate-200 border rounded-lg'
                    : 'text-neutral-600 font-medium hover:text-blue-900'"
                class="flex h-min items-center gap-2 px-4 py-2 text-sm"
                type="button"
                role="tab"
                aria-controls="tabpanelJournal">
                Journal
                <span :class="selectedTab === 'Journal'
                    ? 'text-white bg-blue-900'
                    : 'bg-slate-500 text-white'"
                    class="text-xs font-medium px-1 rounded-full">{{$journalCount}}</span>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div x-data="{ checkAll: false, currentPage: 1, perPage: 5 }" class="mb-12 mx-12 overflow-hidden max-w-full border-neutral-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-neutral-600" id="tableTransaction">
                <thead class="bg-neutral-100 text-sm text-neutral-700">
                    <tr>
                        <th scope="col" class="p-4">
                            <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                <div class="relative flex items-center">
                                    <input type="checkbox" x-model="checkAll" id="checkAll" @change="toggleAll()" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0" />
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                            </label>
                        </th>
                        <th scope="col" class="text-left py-2.5 px-4">Contact</th>
                        <th scope="col" class="text-left py-2.5 px-4">Date</th>
                        <th scope="col" class="text-left py-2.5 px-4">Invoice Number</th>
                        <th scope="col" class="text-left py-2.5 px-4">Reference No.</th>
                        <th scope="col" class="text-left py-2.5 px-4">Type</th>
                        <th scope="col" class="text-left py-2.5 px-4">Gross Amount</th>
                        <th scope="col" class="text-left py-2.5 px-4">Payment</th>
                        <th scope="col" class="text-left py-2.5 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="text-[13px] divide-y divide-neutral-300">
                    <!-- Check if there is any data for the current page -->
                    @if($transactions && $transactions->count() > 0)
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                <td class="p-4">
                                    <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" @change="toggleCheckbox('{{ $transaction->id }}')" id="transaction{{ $transaction->id }}"  class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0" :checked="selectedRows.includes('{{ $transaction->id }}')" />
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                        </div>
                                    </label>
                                </td>
                                <td class="text-left py-3 px-4">
                                    <a href="{{ route('transactions.show', ['transaction' => $transaction->id]) }}" class="font-bold hover:font-bold hover:underline hover:text-blue-500">
                                    {{ $transaction->contactDetails->bus_name ?? 'N/A' }}
                                    </a><br>
                                    {{ $transaction->contactDetails->contact_address ?? 'N/A' }}<br>
                                    {{ $transaction->contactDetails->contact_tin ?? 'N/A'}}
                                </td>
                                <td class="text-left py-4 px-4" data-date="{{ $transaction->date }}">{{ \Carbon\Carbon::parse($transaction->date)->format('F j, Y') }}</td>
                                <td class="text-left py-4 px-4">{{$transaction ->inv_number}}</td>
                                <td class="text-left py-4 px-4">{{$transaction ->reference}}</td>
                                <td class="text-left py-4 px-4"><span class="bg-zinc-100 text-zinc-700 text-xs font-medium me-2 px-4 py-1.5 rounded-full">{{$transaction ->transaction_type}}</span></td>
                                <td class="text-left py-4 px-4">{{$transaction ->total_amount}}</td>
                                <td class="text-left py-4 px-4">
                                    @if($transaction->Paidstatus === 'Unpaid')
                                        <div class="flex items-center space-x-2">
                                            <div class="w-2 h-2 shrink-0 rounded-full bg-amber-300"></div>
                                            <span class="text-zinc-600">Awaiting Payment</span>
                                        </div>
                                    @elseif($transaction->Paidstatus === 'Partial')
                                        Partially Paid
                                    @else
                                        <div class="flex items-center space-x-2"> 
                                            <div class="w-2 h-2 shrink-0 rounded-full bg-emerald-500"></div>
                                            <span class="text-zinc-600">Paid</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-left py-4 px-4">
                                    @if($transaction->status === 'posted')
                                        <span class="text-emerald-600 bg-emerald-100 text-xs font-medium me-2 px-4 py-1.5 rounded-full">Posted</span>
                                    @else
                                    <span class="bg-amber-50 text-amber-500 text-xs font-medium me-2 px-4 py-1.5 rounded-full">Draft</span>
                                    @endif
                                </td>
                            </tr>                             
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center p-4">
                                <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                <h1 class="font-extrabold text-lg mt-2">No Transactions yet</h1>
                                <p class="text-sm text-neutral-500 mt-2">Start adding transactions with the <br> + button at the top.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <!-- Action Buttons -->
            <div 
                x-show="showConfirmDeleteModal" 
                x-cloak 
                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50"
                x-effect="document.body.classList.toggle('overflow-hidden', showConfirmDeleteModal)"
                    >
                <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full overflow-auto relative">
                    <div class="flex flex-col items-center">
                        <button @click="showConfirmDeleteModal = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <!-- Icon -->
                        <div class="mb-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-8xl"></i>
                        </div>

                        <!-- Title -->
                        <h2 class="text-2xl font-extrabold text-zinc-800 mb-2">Delete Item(s)</h2>

                        <!-- Description -->
                        <p class="text-sm text-zinc-700 text-center">
                            You're going to Delete the selected item(s) in the Transactions table. Are you sure?
                        </p>

                        <!-- Actions -->
                        <div class="flex justify-center space-x-8 mt-6 w-full">
                            <button 
                                @click="showConfirmDeleteModal = false; showDeleteCancelButtons = true;" 
                                class="px-4 py-2 rounded-lg text-sm text-zinc-500 hover:text-zinc-800 font-bold transition"
                                > 
                                Cancel
                            </button>
                            <button 
                                @click="deleteRows(); showConfirmDeleteModal = false;" 
                                class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition"
                                > 
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                <button 
                    @click="showConfirmDeleteModal = true; showDeleteCancelButtons = true;" 
                    :disabled="selectedRows.length === 0"
                    class="border px-3 py-2 mx-2 rounded-lg text-sm text-red-600 border-red-600 bg-red-100 hover:bg-red-200 transition disabled:opacity-50 disabled:cursor-not-allowed group flex items-center space-x-2"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24">
                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/>
                    </svg>
                    <span class="text-red-600 transition group-hover:text-red-600">Delete Selected <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span></span>
                </button>

                <button 
                    @click="cancelSelection" 
                    class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                    Cancel
                </button>
            </div>
            @if (count($transactions) > 0)
                <div class="mt-4">
                    {{ $transactions->links('vendor.pagination.custom') }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
       @if (session('alert'))
        alert('{{ session('alert') }}');
    @endif
    document.addEventListener('search', event => {
         window.location.href = `?search=${event.detail.search}`;
     });

     document.addEventListener('filter', event => {
         const url = new URL(window.location.href);
         url.searchParams.set('type', event.detail.type);
      
         window.location.href = url.toString();
     });    

     function toggleCheckboxes() {
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = this.checkAll;
            });
        }

    document.addEventListener('DOMContentLoaded', function () {
        const dropdownButton = document.getElementById('dropdownDefaultButton');
        const dropdown = document.getElementById('dropdown');

        dropdownButton.addEventListener('click', function () {
            dropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function (event) {
            if (!dropdownButton.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    //FILTER BUTTON
    const filterButton = document.getElementById('filterButton');
    const dropdownFilter = document.getElementById('dropdownFilter');
    const applyFiltersButton = document.getElementById('applyFiltersButton');
    const clearFiltersButton = document.getElementById('clearFiltersButton');
    const selectedFilter = document.getElementById('selectedFilter');
    const tableRows = document.querySelectorAll('tbody tr');
    const dropdownArrow = document.getElementById('dropdownArrow');

    filterButton.addEventListener('click', () => {
        dropdownArrow.classList.toggle('rotate-180');
        dropdownFilter.classList.toggle('hidden');
    });
    function getSelectedFilters() {
        const filters = {};
        document.querySelectorAll('.filter-checkbox:checked').forEach((checkbox) => {
            const category = checkbox.dataset.category;
            if (!filters[category]) filters[category] = [];
            filters[category].push(checkbox.value);
        });
        return filters;
    }
    // Attach event listeners for the date inputs
    document.getElementById('fromDate').addEventListener('input', updateApplyButtonState);
    document.getElementById('toDate').addEventListener('input', updateApplyButtonState);

    function applyFilters() {
        const filters = getSelectedFilters();
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;

        tableRows.forEach((row) => {
            let isVisible = true;

            const dateCell = row.cells[2]?.getAttribute('data-date');
            const rowDate = dateCell ? new Date(dateCell) : null;
            if (fromDate || toDate) {
                const from = fromDate ? new Date(fromDate) : null;
                const to = toDate ? new Date(toDate) : null;
                if ((from && rowDate < from) || (to && rowDate > to)) {
                    isVisible = false;
                }
            }
            for (const category in filters) {
                const selectedValues = filters[category];
                let cellText = '';
                if (category === 'Status') {
                    // Adjust the column index to match the status column
                    cellText = row.cells[8]?.querySelector('span')?.textContent.trim(); // Adjusted for nested span
                }
                if (selectedValues.length && !selectedValues.includes(cellText)) {
                    isVisible = false;
                    break;
                }
            }
            row.style.display = isVisible ? '' : 'none';
        });

        dropdownFilter.classList.add('hidden');
        selectedFilter.textContent = 'Filter';
        updateApplyButtonState();
    }
    function updateApplyButtonState() {
        const hasCheckboxSelection = document.querySelectorAll('.filter-checkbox:checked').length > 0;
        const hasDateSelection = document.getElementById('fromDate').value || document.getElementById('toDate').value;
        const isFilterActive = hasCheckboxSelection || hasDateSelection;
        applyFiltersButton.disabled = !isFilterActive;
        if (isFilterActive) {
            applyFiltersButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            applyFiltersButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    document.querySelectorAll('.filter-checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', updateApplyButtonState);
    });

    function clearFilters() {
        document.querySelectorAll('.filter-checkbox').forEach((checkbox) => (checkbox.checked = false));
        document.getElementById('fromDate').value = '';
        document.getElementById('toDate').value = '';
        tableRows.forEach((row) => (row.style.display = ''));
        dropdownFilter.classList.add('hidden');
        selectedFilter.textContent = 'Filter';
        updateApplyButtonState();
    }
    applyFiltersButton.addEventListener('click', applyFilters);
    clearFiltersButton.addEventListener('click', clearFilters);

    window.addEventListener('click', (event) => {
        if (!filterButton.contains(event.target) && !dropdownFilter.contains(event.target)) {
            dropdownFilter.classList.add('hidden');
        }
    });
    // Initial setup: disable the "Apply Filter" button
    applyFiltersButton.disabled = true;
    applyFiltersButton.classList.add('opacity-50', 'cursor-not-allowed'); // Optional: Add styles for disabled state
    function updateApplyButtonState() {
        const hasSelection = document.querySelectorAll('.filter-checkbox:checked').length > 0;
        applyFiltersButton.disabled = !hasSelection;
        if (hasSelection) {
            applyFiltersButton.classList.remove('opacity-50', 'cursor-not-allowed'); // Optional: Remove disabled styles
        } else {
            applyFiltersButton.classList.add('opacity-50', 'cursor-not-allowed'); // Optional: Add disabled styles
        }
    }
    document.querySelectorAll('.filter-checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', updateApplyButtonState);
    });
    clearFiltersButton.addEventListener('click', () => {
        document.querySelectorAll('.filter-checkbox').forEach((checkbox) => (checkbox.checked = false));
        tableRows.forEach((row) => (row.style.display = ''));
        dropdownFilter.classList.add('hidden');
        selectedFilter.textContent = 'Filter';
        updateApplyButtonState();
    });

    // FOR SORT BUTTON
    document.getElementById('sortButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownMenu');
        const dropdownArrow = this.querySelector('svg:nth-child(3)');
        dropdown.classList.toggle('hidden');
        dropdownArrow.classList.toggle('rotate-180');
    });

    // FOR SORT BY
    function sortItems(criteria) {
        const table = document.querySelector('table tbody');
        const rows = Array.from(table.querySelectorAll('tr')).filter(row => row.querySelector('td')); // Filter rows with data
        let sortedRows;
        if (criteria === 'recently-added') {
            sortedRows = rows.reverse();
        } else {
            sortedRows = rows.sort((a, b) => {
                const aText = a.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
                const bText = b.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
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
    // Dropdown event listeners
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

    // FOR SHOW ENTRIES
     // FOR BUTTON OF SHOW ENTRIES
    document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownDots');
        dropdown.classList.toggle('hidden');
    });
    // FOR SHOWING/SETTING ENTRIES
    function setEntries(entries) {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = "{{ route('transactions') }}";
        // Create a hidden input for perPage
        const perPageInput = document.createElement('input');
        perPageInput.type = 'hidden';
        perPageInput.name = 'perPage';
        perPageInput.value = entries;
        // Add search input value if needed
        const searchInput = document.createElement('input');
        searchInput.type = 'hidden';
        searchInput.name = 'search';
        searchInput.value = "{{ request('search') }}";
        // Append inputs to form
        form.appendChild(perPageInput);
        form.appendChild(searchInput);
        // Append the form to the body and submit
        document.body.appendChild(form);
        form.submit();
    }
 
</script>