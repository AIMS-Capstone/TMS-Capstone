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
                                        <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300" role="tablist" aria-label="tab options">
                                            <button @click="selectedTab = 'Accounts'" :aria-selected="selectedTab === 'Accounts'" 
                                            :tabindex="selectedTab === 'Accounts' ? '0' : '-1'" 
                                            :class="selectedTab === 'Accounts' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900'   : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'" 
                                            class="h-min py-2 text-base" 
                                            type="button"
                                            role="tab" 
                                            aria-controls="tabpanelAccounts" >Accounts</button>
                                            <a href="/coa/archive">
                                                <button @click="selectedTab = 'Archive'" :aria-selected="selectedTab === 'Archive'" 
                                                :tabindex="selectedTab === 'Archive' ? '0' : '-1'" 
                                                :class="selectedTab === 'Archive' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'"
                                                class="h-min py-2 text-base" 
                                                type="button" 
                                                role="tab" 
                                                aria-controls="tabpanelArchive" >Archive</button>
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
                                            this.checkAll = !this.checkAll;
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
                                    class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300"
                                >

                                    <!-- Fourth Header -->
                                    <div class="container mx-auto">
                                        <div class="flex flex-row space-x-2 items-center justify-between">
                                            <!-- Search row -->
                                            <div class="relative w-80 p-4">
                                                <form x-target="tableid" action="/coa" role="search" aria-label="Table" autocomplete="off">
                                                    <input 
                                                    type="search" 
                                                    name="search" 
                                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-900 focus:border-blue-900" 
                                                    aria-label="Search Term" 
                                                    placeholder="Search..." 
                                                    @input.debounce="$el.form.requestSubmit()" 
                                                    @search="$el.form.requestSubmit()"
                                                    >
                                                </form>
                                                <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                            </div>

                                            <!-- Sort by dropdown -->
                                            <div class="relative inline-block text-left sm:w-auto">
                                                <button id="sortButton" class="flex items-center text-zinc-600 w-full">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                                        <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span id="selectedOption" class="font-normal text-md text-zinc-700 truncate">Sort by</span>
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
                                                <!-- Import COA Modal -->
                                                <x-import-coa-modal />     
                                                <button  
                                                    x-data 
                                                    x-on:click="$dispatch('open-import-modal')" 
                                                    class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition flex items-center space-x-1 group"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5 text-zinc-600 group-hover:text-green-500 transition">
                                                        <path fill="currentColor" fill-rule="evenodd" d="M8 3.25A2.756 2.756 0 0 0 5.25 6v12A2.756 2.756 0 0 0 8 20.75h8A2.756 2.756 0 0 0 18.75 18V9.5a.75.75 0 0 0-.22-.53l-5.5-5.5a.75.75 0 0 0-.53-.22zM6.75 6c0-.686.564-1.25 1.25-1.25h3.75V9.5c0 .414.336.75.75.75h4.75V18c0 .686-.564 1.25-1.25 1.25H8c-.686 0-1.25-.564-1.25-1.25zm9.44 2.75l-2.94-2.94v2.94zM15.25 15a.75.75 0 0 1-.75.75h-1.75v1.75a.75.75 0 0 1-1.5 0v-1.75H9.5a.75.75 0 0 1 0-1.5h1.75V12.5a.75.75 0 0 1 1.5 0v1.75h1.75a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-zinc-600 group-hover:text-green-500 transition">Import</span>
                                                </button>
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
                                                                <input type="checkbox" x-model="checkAll" id="checkAll" @change="toggleAll()" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                            </div>
                                                        </label>
                                                    </th>
                                                    <th scope="col" class="py-4 px-1">Code</th>
                                                    <th scope="col" class="py-4 px-1">Name</th>
                                                    <th scope="col" class="py-4 px-1">Type</th>
                                                    <th scope="col" class="py-4 px-1">Date Created</th>
                                                    <th scope="col" class="py-4 px-4">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                                @if (count($coas) > 0)
                                                    @foreach ($coas as $coa)
                                                        <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                                            <td class="p-4">
                                                                <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                                    <div class="relative flex items-center">
                                                                        <input type="checkbox" @change="toggleCheckbox('{{ $coa->id }}')" id="coa{{ $coa->id }}"  class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" :checked="selectedRows.includes('{{ $coa->id }}')" />
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                        </svg>
                                                                    </div>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <x-view-coa />
                                                                <button @click="$dispatch('open-view-modal', {{ json_encode($coa) }})" class="underline hover:text-blue-500">
                                                                    {{ $coa->code }}
                                                                </button>
                                                            </td>
                                                            <td>
                                                                <x-view-coa />
                                                                <button @click="$dispatch('open-view-modal', {{ json_encode($coa) }})">
                                                                    {{ $coa->name }}
                                                                </button>
                                                            </td>
                                                            <td>{{ $coa->type }} <br/> {{ $coa->sub_type ? $coa->sub_type : $coa->description }}</td>
                                                            <td>{{ $coa->created_at }}</td>
                                                            <td class="py-4 px-2">
                                                                <x-edit-coa />
                                                                <p
                                                                    @click="$dispatch('open-edit-modal', {
                                                                        id: '{{ $coa->id }}',
                                                                        code: '{{ $coa->code }}',
                                                                        name: '{{ $coa->name }}',
                                                                        type: '{{ $coa->type }}',
                                                                        sub_type: '{{ $coa->sub_type }}',
                                                                        description: '{{ $coa->description }}'
                                                                    })"
                                                                    class="underline hover:border-blue-900 hover:text-blue-900 hover:cursor-pointer px-3 py-y text-sm"
                                                                >
                                                                    Edit
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center p-4">
                                                            <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                            <h1 class="font-extrabold text-lg mt-2">No Chart of Accounts yet</h1>
                                                            <p class="text-sm text-neutral-500 mt-2">Start adding accounts with the <br> + Add button.</p>
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
                                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full overflow-auto">
                                            <div class="flex flex-col items-center">
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
                                        <button 
                                            @click="showConfirmArchiveModal = true; showDeleteCancelButtons = true;" 
                                            :disabled="selectedRows.length === 0"
                                            class="border px-3 py-2 mx-2 rounded-lg text-sm text-gray-700 bg-gray-200 hover:bg-gray-300 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <i class="fa fa-box"></i> Archive Selected <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                                        </button>
                                        <button 
                                            @click="cancelSelection" 
                                            class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"
                                        >
                                            <i class="fa fa-times"></i> Cancel
                                        </button>
                                    </div>
                                    
                                    {{ $coas->links('vendor.pagination.custom') }}
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
            const url = new URL(window.location.href);
            url.searchParams.set('type', event.detail.type);
            window.location.href = url.toString();
        });

        // document.addEventListener('filter', event => {
        //     window.location.href = `?type=${event.detail.type}`;
        // });

        function toggleCheckboxes() {
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = this.checkAll;
            });
        }

        // FOR SORT BUTTON
        document.getElementById('sortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        });

        // FOR SORT BY
        function sortItems(criteria) {
            const table = document.querySelector('table tbody');
            const rows = Array.from(table.querySelectorAll('tr'));
            let sortedRows;
            if (criteria === 'recently-added') {
                // Sort by the order of rows (assuming they are in the order of addition)
                sortedRows = rows.reverse();
            } else {
                // Sort by text content of the first column
                sortedRows = rows.sort((a, b) => {
                    const aText = a.querySelector('td').textContent.trim().toLowerCase();
                    const bText = b.querySelector('td').textContent.trim().toLowerCase();

                    if (criteria === 'ascending') {
                        return aText.localeCompare(bText);
                    } else if (criteria === 'descending') {
                        return bText.localeCompare(aText);
                    }
                });
            }
            // Append sorted rows back to the table body
            table.innerHTML = '';
            sortedRows.forEach(row => table.appendChild(row));
        }
        // to sort options
        document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                sortItems(criteria);
            });
        });

        // FOR BUTTON OF SHOW ENTRIES
        document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownDots');
            dropdown.classList.toggle('hidden');
        });
        // FOR SHOWING/SETTING ENTRIES
        function setEntries(entries) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('coa') }}";
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
</x-app-layout>