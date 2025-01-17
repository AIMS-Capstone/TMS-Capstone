<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Main -->
                <div class="px-10 py-6" 
                    x-data="{ selectedTab: '1701Q', init() { this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || '1701Q'; } }" 
                    x-init="init">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">
                                Income Tax Return
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <a href="{{ route('income_return')}}"
                                    class="ms-1 text-sm font-medium md:ms-2" 
                                    :class="selectedTab ? 'text-zinc-500' : 'text-zinc-500'">
                                        <span x-text="selectedTab"></span>
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="" class="ms-1 text-sm font-bold text-blue-900 md:ms-2">Input Summary</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <hr>

                <div class="p-6 bg-white border-b border-gray-200">

                    <div x-data="{
                        selectedTab: 'input-summary',  // Default tab
                        changeTab(tab) {
                            this.selectedTab = tab;
                        }
                        }" class="container mx-auto">

                        <!-- Tabs Navigation -->
                        <div class="flex justify-start space-x-8 mb-6">
                            <a 
                            href="{{route('income_return.show', ['id' => $taxReturn->id, 'type' => $taxReturn->title])}}"
                                @click="changeTab('input-summary')" 
                                :class="selectedTab === 'input-summary' ? 'font-bold text-blue-900 bg-slate-100 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                class="px-4 py-2 text-sm"
                            >
                                Input Summary
                            </a>
                            <a 
                            href="{{ route('income_return.report', ['id' => $taxReturn->id]) }}" 
                            class="text-zinc-600 font-medium hover:text-blue-900 px-4 py-2 text-sm"
                        >
                            Report
                        </a>
                        </div>
                    </div>
                    <!-- Tab Content for Input Summary -->
                 
                    <div 
                        x-data="{
                            showCheckboxes: false, 
                            checkAll: false, 
                            selectedRows: [], 
                            showDeleteCancelButtons: false,
                            activeTab: '{{ $activeTab }}',
                            selectedType: '',
                            
                            // Toggle a single row
                            toggleCheckbox(id) {
                                if (this.selectedRows.includes(id)) {
                                    this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                                } else {
                                    this.selectedRows.push(id);
                                }
                            },

                            // Toggle all rows
                            toggleAll() {
                                if (this.checkAll) {
                                    selectedRows: {{ json_encode($activeTab === 'individual' ? $individualTaxRows->pluck('transaction_id')->toArray() : $spouseTaxRows->pluck('transaction_id')->toArray()) }};

                                } else {
                                    this.selectedRows = []; 
                                }
                                console.log(this.selectedRows); // For debugging
                            },

                            // Handle deletion
                            deleteRows() {
                                if (this.selectedRows.length === 0) {
                                    alert('No rows selected for deletion.');
                                    return;
                                }

                                if (confirm('Are you sure you want to archive the selected transaction(s)?')) {
                                    const payload = {
                                        ids: this.selectedRows, // Selected transaction IDs
                                        tax_return_id: {{ $taxReturn->id }}, // Pass the current tax return ID
                                        activeTab: this.activeTab // Pass the activeTab ('individual' or 'spouse')
                                    };

                                    fetch('/tax-return-transaction/deactivate_transaction', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                        },
                                        body: JSON.stringify(payload),
                                    })
                                    .then(response => {
                                        if (response.ok) {
                                            alert('Transactions archived successfully!');
                                            location.reload();  
                                        } else {
                                            response.json().then(data => {
                                                alert(data.message || 'Error deleting transactions.');
                                            });
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('An unexpected error occurred.');
                                    });
                                }
                            },


                            // Cancel selection
                            cancelSelection() {
                                this.selectedRows = []; 
                                this.checkAll = false; 
                                this.showCheckboxes = false; 
                                this.showDeleteCancelButtons = false;
                            },

                            get selectedCount() {
                                return this.selectedRows.length;
                            },

                            // Handle filter change
                            setTab(tab) {
                                window.location.href = window.location.pathname + '?tab=' + tab;
                            },
                            filterTransactions() {
                                const url = new URL(window.location.href);
                                url.searchParams.set('type', this.selectedType);
                                window.location.href = url.toString();
                            }
                        }" class="container mx-auto pt-2 overflow-hidden">
                        <div class="px-4 grid grid-cols-12 gap-5 mb-6">
                            <div class="col-span-12 border">
                                <div class="font-bold text-sm text-left bg-neutral-100 text-neutral-700 p-2 flex items-center justify-between">
                                    <span>Itemized Deduction and Cost of Goods Sold</span>
                                    <button onclick="history.back()" class="flex items-center text-zinc-600 hover:text-zinc-800 font-normal transition duration-150">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10" /><path d="M16 12H8m4-4l-4 4l4 4" /></g></svg>
                                        <span class="text-sm hover:text-zinc-900">Back to Input Summary</span>
                                    </button>
                                </div>

                                <!-- Search and Add Existing Transaction Buttons -->
                                <div class="flex flex-row space-x-2 items-center justify-between mb-4">
                                    <div class="flex flex-row space-x-4 items-center">
                                        <div class="relative w-80 p-4">
                                            <form action="{{ route('tax_return.income_show_coa', ['taxReturn' => $taxReturn->id]) }}" method="GET" class="relative w-80 p-4">
                                                <input type="hidden" name="tab" value="{{ $activeTab }}">
                                                <input 
                                                    type="search" 
                                                    name="search" 
                                                    value="{{ request('search') }}" 
                                                    class="w-full pl-10 pr-4 py-[7px] text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900" 
                                                    placeholder="Search..."
                                                >
                                                <button type="submit" class="absolute left-8 top-1/2 transform -translate-y-1/2">
                                                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                                                </button>
                                            </form>
                                        </div>
                                        <!-- Sort by dropdown -->
                                        <div class="relative inline-block text-left min-w-[150px]">
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

                                    <div class="flex space-x-4 items-center pr-10 ml-auto">
                                        <button 
                                            type="button"
                                            x-data="{}" 
                                            x-on:click="$dispatch('open-generate-modal', { year: '{{ $taxReturn->year }}', monthOrQuarter: '{{ $taxReturn->month }}' })" 
                                            class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                            >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-green-500" viewBox="0 0 256 256"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"><circle cx="128" cy="128" r="112"/><path d="M 79.999992,128 H 176.0001"/><path d="m 128.00004,79.99995 v 96.0001"/></g></svg>
                                            <span class="text-zinc-600 transition group-hover:text-green-500">Add Transaction</span>
                                        </button>
                                        <button 
                                            type="button" 
                                            @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons" 
                                            class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-red-500 hover:text-red-500 hover:bg-red-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                            >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                            <span class="text-zinc-600 transition group-hover:text-red-500">Delete</span>
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

                                <!-- Table -->
                                <div class="mb-12 mx-8 overflow-hidden max-w-full border-neutral-300">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-sm text-neutral-600 p-4" id="tableid">
                                            <thead class="bg-neutral-100 text-sm text-neutral-700">
                                                <tr>
                                                    <th scope="col" class="p-4">
                                                        <!-- Checkbox for selecting all -->
                                                        <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                            <div class="relative flex items-center">
                                                                <input type="checkbox" x-model="checkAll" id="checkAll" @change="toggleAll()" 
                                                                    class="peer relative cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white 
                                                                    before:content[''] before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 
                                                                    focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0" />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" 
                                                                    stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-neutral-100 
                                                                    peer-checked:visible">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                            </div>
                                                        </label>
                                                    </th>
                                                    <th scope="col" class="py-4 px-2">Contact</th>
                                                    <th scope="col" class="py-4 px-2">Description</th>
                                                    <th scope="col" class="py-4 px-2">Invoice No.</th>
                                                    <th scope="col" class="py-4 px-2">Reference No.</th>
                                                    <th scope="col" class="py-4 px-2">Date</th>
                                                    <th scope="col" class="py-4 px-2">Amount</th>
                                                    <th scope="col" class="py-4 px-2">COA Code</th>
                                                </tr>
                                            </thead>
                                        
                                            <tbody class="divide-y divide-neutral-300">
                                                <!-- Loop through taxRows based on active tab -->
                                                @foreach ($activeTab === 'individual' ? $individualTaxRows : $spouseTaxRows as $taxRow)
                                                    <tr>
                                                        <td class="p-4">
                                                            <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                                <div class="relative flex items-center">
                                                                    <input type="checkbox" @change="toggleCheckbox('{{ $taxRow->transaction_id }}')" :checked="selectedRows.includes('{{ $taxRow->transaction_id }}')" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-yellow-600 checked:before:bg-yellow-600 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-yellow-600 checked:focus:outline-yellow-600 active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 peer-checked:visible text-white">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </div>
                                                            </label>
                                                        </td>
                                                        <td>{{ $taxRow->transaction->contactDetails->bus_name }}<br>
                                                            {{ $taxRow->transaction->contactDetails->contact_address }}<br>
                                                            {{ $taxRow->transaction->contactDetails->contact_tin }}</td>
                                                        <td>{{ $taxRow->description }}</td>
                                                        <td>{{ $taxRow->transaction->inv_number }}</td>
                                                        <td>{{ $taxRow->transaction->reference }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($taxRow->transaction->date)->format(' F j, Y') }}</td>
                                                        <td>{{ $taxRow->net_amount }}</td>
                                                        <td>{{ $taxRow->coaAccount->code }}</td>
                                                    </tr>
                                                @endforeach
                                                @if ($spouseTaxRows->isEmpty())
                                                    <tr>
                                                        <td colspan="10" class="text-center p-4">
                                                            <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                            <h1 class="font-bold text-lg mt-2">No Transactions yet</h1>
                                                            <p class="text-sm text-neutral-500 mt-2">Start adding with the + button <br>at the top.</p>
                                                        </td>
                                                    </tr>
                                                @else
                                                @endif
                                            </tbody>
                                        
                                            <!-- Pagination links -->
                                            <tr>
                                                <td colspan="12" class="p-4">
                                                    {{-- <div class="flex justify-between items-center">
                                                        <div class="text-sm">
                                                            Showing 
                                                            {{ $activeTab === 'individual' ? $individualTaxRows->firstItem() : $spouseTaxRows->firstItem() }} 
                                                            to 
                                                            {{ $activeTab === 'individual' ? $individualTaxRows->lastItem() : $spouseTaxRows->lastItem() }} 
                                                            of 
                                                            {{ $activeTab === 'individual' ? $individualTaxRows->total() : $spouseTaxRows->total() }} 
                                                            tax rows
                                                        </div> --}}
                                                        <div>
                                                            <!-- Pagination links with the active tab and type parameter preserved -->
                                                            {{ $activeTab === 'individual' ? $individualTaxRows->appends(['type' => $type])->links('vendor.pagination.custom') : $spouseTaxRows->appends(['type' => $type])->links('vendor.pagination.custom') }}
                                                        </div>
                                                    {{-- </div> --}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Action Buttons (same as before) -->
                                <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                                    <button @click="deleteRows" 
                                        class="border px-3 py-2 rounded-lg text-sm text-red-600 border-red-600 bg-red-100 hover:bg-red-200 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                        <span class="text-red-600 transition group-hover:text-red-600">Delete Selected</span><span class="transition group-hover:text-red-500" x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                                    </button>
                                    <button @click="cancelSelection" 
                                        class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"> 
                                        Cancel
                                    </button>
                                </div>

                                <!-- Modal for Adding Existing Transactions -->
                                <div 
                                    x-data="{
                                        open: false,
                                        transactions: [],
                                        selectedTransaction: null,
                                        year: null,
                                        monthOrQuarter: null,
                                        fetchTransactions(year, monthOrQuarter) {
                                            fetch('/tax-return-transaction/all_transactions', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({ taxReturnYear: year, taxReturnMonth: monthOrQuarter })
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                this.transactions = data;
                                            });
                                        },
                                        openModal(year, monthOrQuarter) {
                                            this.year = year;
                                            this.monthOrQuarter = monthOrQuarter;
                                            this.fetchTransactions(year, monthOrQuarter);
                                            this.open = true;
                                        },
                                        closeModal() {
                                            this.open = false;
                                            this.transactions = [];
                                        }
                                    }" 
                                    @open-generate-modal.window="openModal($event.detail.year, $event.detail.monthOrQuarter)"
                                    x-show="open" 
                                    x-transition
                                    class="fixed inset-0 bg-gray-200 bg-opacity-50 z-50 flex items-center justify-center"
                                    x-cloak>
                                    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden" x-show="open" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                                        <!-- Modal Header -->
                                        <div class="relative flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto">
                                            <h1 class="text-lg font-bold text-white">Select Transaction</h1>
                                            <button @click="closeModal" class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 8L16 16M8 16L16 8" stroke="#1e3a8a" class="transition duration-200 hover:stroke-gray-600"/>
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="p-6">
                                            <form method="POST" action="{{ route('tax_return_transaction.addTransaction') }}">
                                                @csrf
                                                <div class="mb-4">
                                                    <label for="transaction-dropdown" class="block text-sm font-bold text-gray-700">Select a Transaction</label>
                                                    <select 
                                                        name="transaction_id"
                                                        id="transaction-dropdown" 
                                                        x-model="selectedTransaction" 
                                                        class="w-full border-gray-300 rounded-md shadow-sm"
                                                        :disabled="transactions.length === 0"
                                                    >
                                                        <option value="">-- Select a transaction --</option>
                                                        <template x-for="transaction in transactions" :key="transaction.id">
                                                            <option :value="transaction.id" 
                                                            x-text="(transaction.contact_details ? transaction.contact_details.bus_name : 'Journal Entry') + ' - ' + 
                                                            (transaction.inv_number ? transaction.inv_number : transaction.reference || 'No Reference') + 
                                                            ' - ' + (transaction.date || 'No Date')">
                                                            </option>
                                                        </template>
                                                    </select>
                                                </div>
                                            
                                                <!-- Hidden field to pass the taxReturn ID -->
                                                <input type="hidden" name="tax_return_id" value="{{ $taxReturn->id }}">
                                            
                                                <!-- Hidden field to dynamically set the activeTab -->
                                                <input type="hidden" name="is_spouse" value="{{ $activeTab === 'spouse' ? 'true' : 'false' }}">
                                            
                                                <div class="flex justify-end mt-6">
                                                    <button type="button" @click="closeModal" class="mr-4 font-semibold text-zinc-700 px-3 py-1 rounded-md hover:text-zinc-900 transition">Cancel</button>
                                                    <button type="submit" :disabled="!selectedTransaction" class="font-semibold bg-blue-900 text-white text-center px-6 py-1.5 rounded-md hover:bg-blue-950 border-blue-900 hover:text-white transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                                                        Submit
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<!-- Script -->
<script>
        @if(session('error'))
  
  alert("{{ session('error') }}");

@endif
document.addEventListener('search', event => {
    window.location.href = `?search=${event.detail.search}`;
});

document.addEventListener('filter', event => {
    const url = new URL(window.location.href);
    url.searchParams.set('type', event.detail.type);
    window.location.href = url.toString();
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
    const table = document.querySelector('#tableid tbody');
    const rows = Array.from(table.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
    let sortedRows;
    if (criteria === 'recently-added') {
        // Sort by 'Date Created' column (newest first)
        sortedRows = rows.sort((a, b) => {
            const aDate = new Date(a.cells[5].textContent.trim()); // Adjust index for "Date Created"
            const bDate = new Date(b.cells[5].textContent.trim());
            return bDate - aDate; // Newest first
        });
    } else if (criteria === 'ascending' || criteria === 'descending') {
        sortedRows = rows.sort((a, b) => {
            const aText = a.cells[3].textContent.trim().toLowerCase(); // Adjust index for "Created By"
            const bText = b.cells[3].textContent.trim().toLowerCase();

            if (criteria === 'ascending') {
                return aText.localeCompare(bText);
            } else if (criteria === 'descending') {
                return bText.localeCompare(aText);
            }
        });
    } else {
        console.error('Invalid sorting criteria:', criteria);
        return; // Exit the function if criteria is invalid
    }
    table.innerHTML = '';
    sortedRows.forEach(row => table.appendChild(row));
}
// Sort dropdown click event handling
document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
    item.addEventListener('click', function() {
        const criteria = this.getAttribute('data-sort');
        sortItems(criteria);
        document.getElementById('selectedOption').textContent = this.textContent;
        document.getElementById('dropdownMenu').classList.add('hidden');
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
    form.action = "{{ route('tax_return.income_show_coa', ['taxReturn' => $taxReturn->id]) }}";
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
</script>

</x-app-layout>
