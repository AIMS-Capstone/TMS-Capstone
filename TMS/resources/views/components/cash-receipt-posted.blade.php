<x-app-layout>
    @php
    $organizationId = session('organization_id');
    $organization = \App\Models\OrgSetup::find($organizationId);
    @endphp
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <nav class="text-sm font-medium text-gray-600 dark:text-slate-300 mb-6" aria-label="breadcrumb">
                <ol class="flex flex-wrap items-center gap-1">
                    <li class="flex items-center gap-1">
                        <a href="{{ route('dashboard') }}" class="hover:text-black dark:hover:text-white">Home</a>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </li>
                    <li class="flex items-center gap-1">
                        <a href="{{ route('cash-receipt') }}" class="hover:text-blue-950 dark:hover:text-white {{ request()->routeIs('cash-receipt') ? 'breadcumb-active' : '' }}">cash Book </a>
                    </li>
                </ol>
            </nav>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" x-data="filterComponent()">

                <div class="container mx-auto my-4 pt-4">
                    <div class="flex justify-between items-center px-10">
                        <p class="font-bold text-3xl text-left taxuri-color">cash Book </p>
                        <button type="button" class="flex items-center text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                            </svg>
                            <span>Export Report</span>
                        </button>
                    </div>
                    <div class="flex justify-between items-center px-10">
                        <div class="flex items-center">            
                            <p class="font-normal text-sm">This book houses all the cash entered in the Transactions Module.</p>
                        </div>
                    </div>  
                    <hr class="mt-6">
                    <br>

                        <div class="container mx-auto ps-8">
                            <div class="flex flex-row space-x-2 items-center justify-center">
                                <div x-data="{ selectedTab: 'Posted' }" class="w-full">
                                    <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300" role="tablist" aria-label="tab options">
                                        <a href="/cash-receipt">
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
                                            :class="selectedTab === 'Posted' ? 'font-bold box-border text-sky-900 border-b-4 border-sky-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-sky-900 hover:text-sky-900'"
                                            class="h-min py-2 text-base" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="tabpanelPosted" >Posted
                                        </button>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    <hr>

                    <!-- Filters Row -->
                    <div class="bg-white border border-gray-300 rounded-tl-lg rounded-tr-lg grid grid-cols-8 gap-4 mx-10 overflow-x-auto whitespace-nowrap max-w-full">
                        <div class="flex items-center space-x-8">
                            <div class="col-span-2 bg-blue-50 p-4 rounded-tl-lg">
                                <span class="font-bold text-blue-950">{{ $organization->registration_name ?? 'Organization Name' }}</span>
                                <p class="font-normal">Filter: <b>cash Book </b></p>
                                <p class="font-normal text-sm" x-text="getFormattedDate()"></p>
                            </div>

                            <div class="flex items-center space-x-8">
                                <div class="flex flex-col w-32">
                                    <label for="period_select" class="font-bold text-blue-950">Period </label>
                                    <select id="period_select" x-model="period" @change="updateYearAndMonthOptions" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="annually" selected>Annually</option>
                                    </select>
                                </div>
                                <div class="h-8 border-l border-gray-200"></div>
                                <!-- Year -->
                                <div class="flex flex-col w-32">
                                    <label for="year_select" class="font-bold text-blue-950">Year</label>
                                    <select id="year_select" x-model="selectedYear" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                        <template x-for="year in years" :key="year">
                                            <option :value="year" x-text="year"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="h-8 border-l border-gray-200"></div>
                                <!-- Quarter (Only visible if Quarterly is selected) -->
                                <div class="flex flex-col w-32" x-show="period === 'quarterly'">
                                    <label for="quarter_select" class="font-bold text-blue-950">Quarter</label>
                                    <select id="quarter_select" x-model="selectedQuarter" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                        <option value="Q1">1st Quarter</option>
                                        <option value="Q2">2nd Quarter</option>
                                        <option value="Q3">3rd Quarter</option>
                                        <option value="Q4">4th Quarter</option>
                                    </select>
                                </div>
                                <!-- Month (Only visible if Monthly is selected) -->
                                <div class="flex flex-col w-32" x-show="period === 'monthly'">
                                    <label for="month_select" class="font-bold text-blue-950">Month</label>
                                    <select id="month_select" x-model="selectedMonth" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                        <template x-for="month in months" :key="month.value">
                                            <option :value="month.value" x-text="month.label"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Buttons -->
                            <div class="h-8 border-l border-gray-200"></div>
                            <div class="flex items-center space-x-4">
                                <button @click="applyFilters" class="flex items-center bg-white border border-gray-300 rounded-md px-4 py-2 whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 32 32">
                                        <path fill="#949494" d="M16 3C8.832 3 3 8.832 3 16s5.832 13 13 13s13-5.832 13-13S23.168 3 16 3m0 2c6.087 0 11 4.913 11 11s-4.913 11-11 11S5 22.087 5 16S9.913 5 16 5m-1 5v5h-5v2h5v5h2v-5h5v-2h-5v-5z"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">Add Filter</span>
                                </button>
                                <button @click="resetFilters" class="text-sm text-gray-600 whitespace-nowrap">
                                    Clear all filters
                                </button>
                            </div>
                        </div>
                    </div>

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
                                fetch('/cash-receipt/draft', { 
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
                        class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300 dark:border-neutral-700"
                    >
                        <div class="container mx-auto">
                            <div class="flex flex-row space-x-2 items-center justify-between">
                                <!-- Search Input -->
                                <div class="relative w-80 p-4">
                                    <form x-target="cash-posted-table" action="/cash-receipt/posted" method="GET" role="search" aria-label="Table" autocomplete="off">
                                        <input
                                            type="search"
                                            name="search"
                                            value= "{{ request('search')}}"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900"
                                            aria-label="Search Term"
                                            placeholder="Search..."
                                            @input.debounce="$el.form.requestSubmit()"
                                            @search="$el.form.requestSubmit()"
                                            value="{{ request('search') }}"
                                        >
                                    </form>
                                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <!-- Update Status Button -->
                                <div class="mb-3">
                                    <button
                                        type="button"
                                        @click="showCheckboxes = !showCheckboxes; showUpdateStatusButtons = !showUpdateStatusButtons; $el.disabled = true;"
                                        :disabled="selectedRows.length === 1"
                                        class="border px-3 py-2 rounded-lg text-sm hover:border-yellow-500 hover:text-yellow-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <i class="fa-solid fa-bookmark"></i> Mark as Draft
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Table for cash Posted Transactions -->
                        <div class="mt-6 overflow-x-auto" id="cash-posted-table">
                            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                                <thead class="border-b border-gray-200 bg-gray-100 text-sm text-gray-600">
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
                                                <h1 class="font-extrabold mt-4">No cash Posted Transactions Available</h1>
                                                <p class="text-sm text-neutral-500 mt-2">You can start adding new cash transactions by <br> going to the transactions page.</p>
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
                            <div x-show="showUpdateStatusButtons" class="flex justify-center py-4" x-cloak>
                                <button 
                                    @click="updateStatus" 
                                    :disabled="selectedRows.length === 0"
                                    class="border px-3 py-2 mx-2 rounded-lg text-sm hover:border-yellow-500 hover:text-yellow-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <i class="fa-solid fa-bookmark"></i> Mark as Draft Selected <span x-show="selectedCount > 0" x-text="'(' + selectedCount + ')'"></span>
                                </button>
                                <button 
                                    @click="cancelSelection" 
                                    class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>

                        <!-- Confirm Update Status Modal -->
                        <div 
                            x-show="showConfirmUpdateModal" 
                            x-cloak 
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                            @click.away="showConfirmUpdateModal = false"
                        >
                            <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative">
                                <button 
                                    @click="showConfirmUpdateModal = false" 
                                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition"
                                >
                                    <i class="fa fa-times"></i>
                                </button>
                                <div class="flex flex-col items-center">
                                    <!-- Icon -->
                                    <div class="mb-6">
                                        <div class="flex items-center justify-center w-24 h-24 rounded-full bg-yellow-500">
                                            <i class="fas fa-question text-white text-6xl"></i>
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <h2 class="text-xl font-bold text-gray-800 mb-4">Mark as Draft Item/s</h2>

                                    <!-- Description -->
                                    <p class="text-sm text-gray-600 text-center mb-6">
                                        You're going to mark as draft the selected item/s in the cash Book  table. Are you sure?
                                    </p>

                                    <!-- Actions -->
                                    <div class="flex justify-between w-full">
                                        <button 
                                            @click="showConfirmUpdateModal = false; showUpdateStatusButtons = true;" 
                                            class="px-5 py-2 hover:bg-gray-400 rounded-lg text-sm text-gray-700 transition w-1/2 mr-2"
                                        >
                                            Cancel
                                        </button>
                                        <button 
                                            @click="confirmUpdateStatus();" 
                                            class="px-5 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm transition w-1/2 ml-2"
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
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                            @click.away="showSuccessModal = false"
                        >
                            <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative">
                                <button 
                                    @click="showSuccessModal = false" 
                                    class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition"
                                >
                                    <i class="fa fa-times"></i>
                                </button>
                                <div class="flex flex-col items-center">
                                    <!-- Icon -->
                                    <div class="mb-6">
                                        <div class="flex items-center justify-center w-24 h-24 rounded-full bg-green-600">
                                            <i class="fas fa-check text-white text-6xl"></i>
                                        </div>
                                    </div>

                                    <!-- Title -->
                                    <h2 class="text-xl font-bold text-green-600 mb-4">Mark as Draft</h2>

                                    <!-- Description -->
                                    <p class="text-sm text-gray-600 text-center mb-6">
                                        The selected item(s) have been successfully mark as draft.
                                    </p>

                                    <!-- Close Button -->
                                    <button 
                                        @click="showSuccessModal = false; cancelSelection();" 
                                        class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm transition w-1/2"
                                    >
                                        OK
                                    </button>
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
                const newTableContent = doc.querySelector('#transaction-table').innerHTML;
                document.querySelector('#transaction-table').innerHTML = newTableContent;
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
                const newTableContent = doc.querySelector('#transaction-table').innerHTML;
                document.querySelector('#transaction-table').innerHTML = newTableContent;
            })
            .catch(error => console.error('Error resetting data:', error));
        }
    };
}
</script>
