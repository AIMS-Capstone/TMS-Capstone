<x-app-layout>
    @php
    $organizationId = session('organization_id');
    $organization = \App\Models\OrgSetup::find($organizationId);
    @endphp
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="filterComponent()">
                <div class="container mx-auto my-4 pt-4">
                    <div class="flex justify-between items-center px-10">
                        <p class="font-bold text-3xl text-left taxuri-color">Cash Disbursement Book </p>
                    </div>
                    <div class="flex justify-between items-center px-10">
                        <div class="flex items-center">            
                            <p class="font-normal text-sm">This book houses all the invoices that are marked as paid in the Transactions Module.</p>
                        </div>
                        <button type="button" onclick="exportReportExcel()" class="flex items-center text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                            </svg>
                            <span>Export Report</span>
                        </button>
                    </div>  
                    
                    <br>

                    <div class="container mx-auto ps-8">
                        <div class="flex flex-row space-x-2 items-center justify-center">
                            <div x-data="{ selectedTab: 'Posted' }" class="w-full">
                                <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300" role="tablist" aria-label="tab options">
                                    <a href="/cash-disbursement">
                                        <button @click="selectedTab = 'Draft'" :aria-selected="selectedTab === 'Draft'" 
                                            :tabindex="selectedTab === 'Draft' ? '0' : '-1'" 
                                            :class="selectedTab === 'Draft' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900'   : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'" 
                                            class="h-min py-2 text-base" 
                                            type="button"
                                            role="tab" 
                                            aria-controls="tabpanelDraft" >
                                            Draft
                                        </button>
                                    </a>
                                    <button @click="selectedTab = 'Posted'" :aria-selected="selectedTab === 'Posted'" 
                                        :tabindex="selectedTab === 'Posted' ? '0' : '-1'" 
                                        :class="selectedTab === 'Posted' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'"
                                        class="h-min py-2 text-base" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelPosted" >Posted
                                    </button>
                                </div>
                            </div>  
                        </div>
                    </div>

                    <hr class="mb-4">

                    <!-- Filters Row -->
                    <div class="grid grid-cols-8 gap-4 mx-10 overflow-x-auto whitespace-nowrap max-w-full custom-scrollbar">
                        <div class="flex items-center space-x-8 ps-6">
                            <div class="col-span-2 p-4 text-blue-900">
                                <p class="font-normal">Filter: <b>Cash Disbursement Book </b></p>
                                <p class="font-normal text-sm" x-text="getFormattedDate()"></p>
                            </div>

                            <div class="flex items-center space-x-4">
                                <div class="flex flex-col w-32">
                                    <label for="period_select" class="font-bold text-blue-900">Period </label>
                                    <select id="period_select" x-model="period" @change="updateYearAndMonthOptions" class="cursor-pointer block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="annually" selected>Annually</option>
                                    </select>
                                </div>
                                <div class="h-8 border-l border-gray-200"></div>
                                <!-- Year -->
                                <div class="flex flex-col w-32">
                                    <label for="year_select" class="font-bold text-blue-900">Year</label>
                                    <select id="year_select" x-model="selectedYear" class="cursor-pointer block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <template x-for="year in years" :key="year">
                                            <option :value="year" x-text="year"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="h-8 border-l border-gray-200"></div>
                                <!-- Quarter (Only visible if Quarterly is selected) -->
                                <div class="flex flex-col w-32" x-show="period === 'quarterly'">
                                    <label for="quarter_select" class="font-bold text-blue-900">Quarter</label>
                                    <select id="quarter_select" x-model="selectedQuarter" class="cursor-pointer block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <option value="Q1">1st Quarter</option>
                                        <option value="Q2">2nd Quarter</option>
                                        <option value="Q3">3rd Quarter</option>
                                        <option value="Q4">4th Quarter</option>
                                    </select>
                                </div>
                                <!-- Month (Only visible if Monthly is selected) -->
                                <div class="flex flex-col w-32" x-show="period === 'monthly'">
                                    <label for="month_select" class="font-bold text-blue-900">Month</label>
                                    <select id="month_select" x-model="selectedMonth" class="cursor-pointer block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <template x-for="month in months" :key="month.value">
                                            <option :value="month.value" x-text="month.label"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Buttons -->
                            <div class="flex items-center space-x-4">
                                <button @click="applyFilters" class="flex items-center bg-white border border-gray-300 hover:border-green-500 hover:bg-green-100 hover:text-green-500 transition rounded-md px-4 py-2 whitespace-nowrap group">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 fill-current group-hover:fill-green-500 hover:border-green-500 hover:text-green-500 transition" viewBox="0 0 32 32">
                                        <path fill="currentColor" d="M16 3C8.832 3 3 8.832 3 16s5.832 13 13 13s13-5.832 13-13S23.168 3 16 3m0 2c6.087 0 11 4.913 11 11s-4.913 11-11 11S5 22.087 5 16S9.913 5 16 5m-1 5v5h-5v2h5v5h2v-5h5v-2h-5v-5z"/>
                                    </svg>
                                    <span class="text-zinc-700 transition group-hover:text-green-500 text-sm">Add Filter</span>
                                </button>
                                <button @click="resetFilters" class="text-sm text-zinc-700 hover:text-zinc-900 whitespace-nowrap">
                                    Clear all filters
                                </button>
                            </div>
                        </div>
                    </div>

                    <hr class="mt-4">

                    <div
                        x-data="{
                            showCheckboxes: false,
                            checkAll: false,
                            selectedRows: [],
                            showUpdateStatusButtons: false,
                            showConfirmUpdateModal: false,
                            showSuccessModal: false,

                            // Toggle a single row
                            toggleCheckbox(id) {
                                if (this.selectedRows.includes(id)) {
                                    this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                                } else {
                                    this.selectedRows.push(id);
                                }
                                this.syncCheckAllState();
                            },

                            // Toggle all rows
                            toggleAll() {
                                if (this.checkAll) {
                                    this.selectedRows = {{ json_encode($transactions->pluck('id')->toArray()) }};
                                } else {
                                    this.selectedRows = [];
                                }
                                this.syncCheckAllState();
                            },

                            syncCheckAllState() {
                                this.checkAll = this.selectedRows.length === {{ $transactions->count() }} && this.selectedRows.length > 0;
                            },

                            // Handle status update
                            updateStatus() {
                                if (this.selectedRows.length === 0) {
                                    alert('No rows selected for update.');
                                    return;
                                }
                                // Show confirmation modal
                                this.showConfirmUpdateModal = true;
                            },

                            // Confirm update to posted
                            confirmUpdateStatus() {
                                fetch('/cash-disbursement/draft', { 
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({ ids: this.selectedRows })
                                })
                                .then(response => {
                                    if (response.ok) {
                                        this.showConfirmUpdateModal = false;
                                        this.showSuccessModal = true; 
                                        location.reload(); 
                                    } else {
                                        alert('Error updating status.');
                                    }
                                })
                                .catch(error => {
                                    alert('Network error, please try again.');
                                });
                            },

                            // Cancel selection
                            cancelSelection() {
                                this.selectedRows = [];
                                this.checkAll = false;
                                this.showCheckboxes = false;
                                this.showUpdateStatusButtons = false;
                                this.showConfirmUpdateModal = false;
                                this.showSuccessModal = false;
                            },

                            get selectedCount() {
                                return this.selectedRows.length;
                            }
                        }"
                        class="container mx-auto pt-2 overflow-hidden"
                        >

                        <div class="container mx-auto">
                            <div class="flex flex-row space-x-2 items-center justify-between">
                                <div class="flex flex-row space-x-2 items-center ps-8">
                                    <!-- Search Input -->
                                    <div class="relative w-80 p-4">
                                        <form x-target="cash-posted-table" action="/cash-disbursement/posted" method="GET" role="search" aria-label="Table" autocomplete="off">
                                            <input
                                                type="search"
                                                name="search"
                                                value= "{{ request('search')}}"
                                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900"
                                                aria-label="Search Term"
                                                placeholder="Search..."
                                                @input.debounce="$el.form.requestSubmit()"
                                                @search="$el.form.requestSubmit()"
                                                value="{{ request('search') }}"
                                            >
                                        </form>
                                        <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    </div>
                                    <!-- Sort by dropdown -->
                                    <div class="relative inline-block text-left min-w-[150px]">
                                        <button id="sortButton" class="flex items-center text-zinc-600">
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
                                </div>

                                <div class="flex space-x-4 items-center pr-10 ml-auto">
                                    <!-- Update Status Button -->
                                    <button type="button"
                                        @click="showCheckboxes = !showCheckboxes; showUpdateStatusButtons = !showUpdateStatusButtons; $el.disabled = true;"
                                        :disabled="selectedRows.length === 1"
                                        class="flex items-center border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-amber-400 hover:bg-amber-100 hover:text-amber-400 transition disabled:opacity-50 disabled:cursor-not-allowed group"
                                        >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 transition group-hover:text-amber-400" viewBox="0 0 24 24"><path fill="currentColor" d="M5 21V5q0-.825.588-1.412T7 3h10q.825 0 1.413.588T19 5v16l-7-3zm2-3.05l5-2.15l5 2.15V5H7zM7 5h10z"/></svg>
                                        <span class="text-zinc-600 transition group-hover:text-amber-400">Mark as Draft</span>
                                    </button>
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

                            <!-- Table for cash Posted Transactions -->
                            <div x-data="{ checkAll: false, currentPage: 1, perPage: 5 }" class="mb-12 mt-6 mx-12 overflow-hidden max-w-full border-neutral-300" id="cash-posted-table">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left text-sm text-neutral-600">
                                        <thead class="bg-neutral-100 text-sm text-neutral-700">
                                            <tr>
                                                <th scope="col" class="p-4">
                                                    <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                        <div class="relative flex items-center">
                                                            <input type="checkbox" x-model="checkAll" id="checkAll" @change="toggleAll()" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-yellow-600 checked:before:bg-yellow-600 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-yellow-600 checked:focus:outline-yellow-600 active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 peer-checked:visible text-white">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                            </svg>
                                                        </div>
                                                    </label>
                                                </th>
                                                <th scope="col" class="p-4">Contact</th>
                                                <th scope="col" class="p-2">Date</th>
                                                <th scope="col" class="p-2">Invoice</th>
                                                <th scope="col" class="p-2">Reference</th>
                                                <th scope="col" class="p-2">Description</th>
                                                <th scope="col" class="p-2">VATable Amount</th>
                                                <th scope="col" class="p-2">Tax Exempt Amount</th>
                                                <th scope="col" class="p-2">Zero Rated Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if (count($transactions) > 0)
                                                @foreach($transactions as $transaction)
                                                <tr class="border-b hover:bg-gray-50">
                                                    <td class="p-4">
                                                        <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                            <div class="relative flex items-center">
                                                                <input type="checkbox" @change="toggleCheckbox('{{ $transaction->id }}')" :checked="selectedRows.includes('{{ $transaction->id }}')" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-yellow-600 checked:before:bg-yellow-600 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-yellow-600 checked:focus:outline-yellow-600 active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 peer-checked:visible text-white">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                            </div>
                                                        </label>
                                                    </td>
                                                    <td class="p-4">
                                                        <strong>{{ $transaction->contactDetails->bus_name ?? 'N/A' }}</strong><br>
                                                        {{ $transaction->contactDetails->contact_address ?? 'N/A' }}<br>
                                                        {{ $transaction->contactDetails->contact_tin ?? 'N/A'}}
                                                    </td>
                                                    <td class="p-4">{{ \Carbon\Carbon::parse($transaction->date)->format('F d, Y') ?? 'N/A' }}</td>
                                                    <td class="p-4">{{ $transaction->inv_number }}</td>
                                                    <td class="p-4">{{ $transaction->reference }}</td>
                                                    <td class="p-4">{{ $transaction->description }}</td>
                                                    <td class="p-4">{{ $transaction->vat_amount }}</td>
                                                    <td class="p-4">{{ $transaction->tax_exempt_amount ?? '0.00' }}</td>
                                                    <td class="p-4">{{ $transaction->zero_rated_amount ?? '0.00' }}</td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="8" class="text-center p-6">
                                                        <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                        <h1
                                                        <h1 class="font-extrabold mt-4">No Cash Disbursement Posted Transactions Available</h1>
                                                        <p class="text-sm text-neutral-500 mt-2">You can start adding new Cash Disbursement transactions by <br> going to the transactions page.</p>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    @if (count($transactions) > 0)
                                        <div class="mt-4">
                                            {{ $transactions->links('vendor.pagination.custom') }}
                                        </div>
                                    @endif
                                    <div x-show="showUpdateStatusButtons" class="flex items-center justify-center py-4" x-cloak>
                                        <button @click="updateStatus" :disabled="selectedRows.length === 0"
                                            class="flex items-center border px-3 py-2 mx-2 rounded-lg text-sm text-zinc-600 hover:border-amber-400 hover:bg-amber-100 hover:text-amber-400 transition disabled:opacity-50 disabled:cursor-not-allowed group"
                                            >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 transition group-hover:text-amber-400" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M5 21V5q0-.825.588-1.412T7 3h10q.825 0 1.413.588T19 5v16l-7-3zm2-3.05l5-2.15l5 2.15V5H7zM7 5h10z"/>
                                            </svg>
                                            <span class="text-zinc-600 transition group-hover:text-amber-400">
                                                Mark as Draft Selected 
                                                <span x-show="selectedCount > 0" x-text="'(' + selectedCount + ')'"></span>
                                            </span>
                                        </button>
                                        <button 
                                            @click="cancelSelection" 
                                            class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"
                                        >
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirm Update Status Modal -->
                        <div x-show="showConfirmUpdateModal" 
                            x-cloak 
                            class="fixed inset-0 z-50 flex items-center justify-center"
                            x-effect="document.body.classList.toggle('overflow-hidden', showConfirmUpdateModal)"
                            @click.away="showConfirmUpdateModal = false"
                            >
                            <div class="fixed inset-0 bg-gray-200 opacity-50"></div>

                            <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative" x-show="showConfirmUpdateModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                                <button @click="showConfirmUpdateModal = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <div class="flex flex-col items-center">
                                    <!-- Icon -->
                                    <div class="mb-6">
                                        <div class="flex items-center justify-center w-36 h-36">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="#fbbf24" d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10s-4.477 10-10 10m-1-7v2h2v-2zm2-1.645A3.502 3.502 0 0 0 12 6.5a3.5 3.5 0 0 0-3.433 2.813l1.962.393A1.5 1.5 0 1 1 12 11.5a1 1 0 0 0-1 1V14h2z"/></svg>
                                        </div>
                                    </div>
                                    <!-- Title -->
                                    <h2 class="text-2xl font-extrabold text-center text-zinc-600 mb-4">Mark as Draft Item(s)</h2>
                                    <!-- Description -->
                                    <p class="text-sm text-zinc-600 text-center mb-6">
                                        You're going to mark as draft the selected item(s) in the Cash Disbursement Book table.<br>Are you sure?
                                    </p>
                                    <!-- Actions -->
                                    <div class="flex justify-between -space-x-2 mb-2 w-full">
                                        <button 
                                            @click="showConfirmUpdateModal = false; showUpdateStatusButtons = true;" 
                                            class="px-5 py-2 mr-2 font-semibold text-zinc-600 rounded-md hover:text-zinc-900 transition text-sm n w-1/2 "
                                        >
                                            Cancel
                                        </button>
                                        <button 
                                            @click="confirmUpdateStatus();" 
                                            class="px-4 py-2 bg-amber-400 hover:bg-amber-500 text-white font-semibold rounded-lg text-sm transition w-1/2 ml-2"
                                        >
                                            Mark as Draft
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Success Modal -->
                        <div 
                            x-show="showSuccessModal" 
                            x-cloak 
                            class="fixed inset-0 z-50 flex items-center justify-center"
                            x-effect="document.body.classList.toggle('overflow-hidden', showSuccessModal)"
                            @click.away="showSuccessModal = false"
                            >
                            <div class="fixed inset-0 bg-gray-200 opacity-50"></div>

                            <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative" x-show="showSuccessModal" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                                <button @click="showSuccessModal = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2" >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <div class="flex flex-col items-center">
                                    <!-- Icon -->
                                    <div class="flex justify-center align-middle mb-4">
                                        <img src="{{ asset('images/Success.png') }}" alt="Item(s) Drafted" class="w-28 h-28">
                                    </div>
                                    <!-- Title -->
                                    <h2 class="text-2xl font-bold text-emerald-500 mb-4">Mark as Draft</h2>
                                    <!-- Description -->
                                    <p class="text-sm text-zinc-600 text-center mb-6">
                                        The selected item(s) have been successfully marked as draft.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function filterComponent() {
    return {
        period: 'annually',
        selectedYear: new Date().getFullYear(),
        selectedMonth: '',
        selectedQuarter: '',
        years: [],
        months: [
            { value: '01', label: 'January' },
            { value: '02', label: 'February' },
            { value: '03', label: 'March' },
            { value: '04', label: 'April' },
            { value: '05', label: 'May' },
            { value: '06', label: 'June' },
            { value: '07', label: 'July' },
            { value: '08', label: 'August' },
            { value: '09', label: 'September' },
            { value: '10', label: 'October' },
            { value: '11', label: 'November' },
            { value: '12', label: 'December' },
        ],
        init() {
            const currentYear = new Date().getFullYear();
            this.years = Array.from({ length: 5 }, (_, i) => currentYear - i);

            const urlParams = new URLSearchParams(window.location.search);
            const year = urlParams.get('year');
            const month = urlParams.get('month');
            const quarter = urlParams.get('quarter');

            if (year && this.years.includes(parseInt(year))) {
                this.selectedYear = parseInt(year);
            }

            if (month) {
                this.period = 'monthly';
                this.selectedMonth = month;
            } else if (quarter) {
                this.period = 'quarterly';
                this.selectedQuarter = quarter;
            }
        },
        updateYearAndMonthOptions() {
            if (this.period === 'annually') {
                this.selectedMonth = '';
                this.selectedQuarter = '';
            } else if (this.period === 'quarterly') {
                this.selectedMonth = '';
            } else {
                this.selectedQuarter = '';
            }
        },
        getFormattedDate() {
            if (this.period === 'monthly' && this.selectedMonth) {
                const month = this.months.find(m => m.value === this.selectedMonth).label;
                return `${month} 01, ${this.selectedYear}`;
            } else if (this.period === 'quarterly' && this.selectedQuarter) {
                return `${this.selectedQuarter}, ${this.selectedYear}`;
            } else {
                return `January 01, ${this.selectedYear}`;
            }
        },
        async applyFilters() {
            let url = new URL(window.location.origin + window.location.pathname);
            url.searchParams.set('year', this.selectedYear);

            if (this.period === 'monthly') {
                url.searchParams.set('month', this.selectedMonth);
            } else if (this.period === 'quarterly') {
                const quarterMonths = this.getQuarterMonths(this.selectedQuarter);
                url.searchParams.set('quarter', this.selectedQuarter);
                url.searchParams.set('start_month', quarterMonths.start);
                url.searchParams.set('end_month', quarterMonths.end);
            }

            // Fetch filtered content using x-fetch
            await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableContent = doc.querySelector('#cash-posted-table').innerHTML;
                document.querySelector('#cash-posted-table').innerHTML = newTableContent;
            })
            .catch(error => console.error('Error fetching data:', error));
        },
        getQuarterMonths(quarter) {
            switch (quarter) {
                case 'Q1':
                    return { start: '01', end: '03' };
                case 'Q2':
                    return { start: '04', end: '06' };
                case 'Q3':
                    return { start: '07', end: '09' };
                case 'Q4':
                    return { start: '10', end: '12' };
                default:
                    return { start: '', end: '' };
            }
        },
        resetFilters() {
            this.period = 'annually';
            this.selectedYear = new Date().getFullYear();
            this.selectedMonth = '';
            this.selectedQuarter = '';

            let url = new URL(window.location.origin + window.location.pathname);
            fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableContent = doc.querySelector('#cash-posted-table').innerHTML;
                document.querySelector('#cash-posted-table').innerHTML = newTableContent;
            })
            .catch(error => console.error('Error resetting data:', error));
        }
    };
}

    function exportReportExcel() {
        const period = document.getElementById('period_select')?.value || 'annually';
        const year = document.getElementById('year_select')?.value || '';
        const month = (period === 'monthly') ? document.getElementById('month_select')?.value || '' : '';
        const quarter = (period === 'quarterly') ? document.getElementById('quarter_select')?.value || '' : '';
        const status = document.getElementById('status_filter')?.value || '';
        const startMonth = (quarter) ? getQuarterStartMonth(quarter) : '';
        const endMonth = (quarter) ? getQuarterEndMonth(quarter) : '';

        // Build the URL for export
        let url = `{{ route('cash_disbursement-posted.exportExcel') }}?year=${year}&month=${month}&period=${period}&quarter=${quarter}&start_month=${startMonth}&end_month=${endMonth}&status=${status}`;

        // Redirect to the generated URL for download
        window.location.href = url;
    }

    // Helper functions to get the start and end months for each quarter
    function getQuarterStartMonth(quarter) {
        const quarters = {
            Q1: '01',
            Q2: '04',
            Q3: '07',
            Q4: '10'
        };
        return quarters[quarter] || '';
    }

    function getQuarterEndMonth(quarter) {
        const quarters = {
            Q1: '03',
            Q2: '06',
            Q3: '09',
            Q4: '12'
        };
        return quarters[quarter] || '';
    }

    // FOR SORT BUTTON
    document.getElementById('sortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        });

    // FOR SORT BY
    function sortItems(criteria) {
        const table = document.querySelector('table tbody');
        const rows = Array.from(table.querySelectorAll('tr')).filter(row => row.querySelector('td')); // Filter rows with data
        let sortedRows;

        if (criteria === 'recently-added') {
            // Sort by the order of rows (assuming they are in the order of addition)
            sortedRows = rows.reverse();
        } else {
            // Sort by text content of the 'Contact' column
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

        // Append sorted rows back to the table body
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
        form.action = "{{ route('disbPosted') }}";
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
