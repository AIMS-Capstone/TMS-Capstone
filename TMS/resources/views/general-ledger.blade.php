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
                        <p class="font-bold text-3xl text-left taxuri-color">General Ledger Listing</p>
                    </div>
                    <div class="flex justify-between items-center px-10">
                        <div class="flex items-center">            
                            <p class="taxuri-text font-normal text-sm">This report is the Summary of all transactions entered in Taxuri,<br>whether paid or not.</p>
                        </div>
                        <div class="items-end float-end relative sm:w-auto">
                            <button type="button" onclick="exportReportExcel()" class="flex items-center text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                    <path fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                                </svg>
                                <span>Export Report</span>
                            </button>
                        </div>
                    </div>

                    <hr class="mt-6">

                        {{-- <div class="container mx-auto ps-8">
                            <div class="flex flex-row space-x-2 items-center justify-center">
                                <div x-data="{ selectedTab: 'Draft' }" class="w-full">
                                    <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300" role="tablist" aria-label="tab options">
                                        <button @click="selectedTab = 'Draft'" :aria-selected="selectedTab === 'Draft'" 
                                            :tabindex="selectedTab === 'Draft' ? '0' : '-1'" 
                                            :class="selectedTab === 'Draft' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900'   : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'" 
                                            class="h-min py-2 text-base" 
                                            type="button"
                                            role="tab" 
                                            aria-controls="tabpanelDraft" >
                                            Draft
                                        </button>
                                        <a href="cash-disbursement/posted">
                                            <button @click="selectedTab = 'Posted'" :aria-selected="selectedTab === 'Posted'" 
                                            :tabindex="selectedTab === 'Posted' ? '0' : '-1'" 
                                            :class="selectedTab === 'Posted' ? 'font-bold box-border text-sky-900 border-b-4 border-sky-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-sky-900 hover:text-sky-900'"
                                            class="h-min py-2 text-base" 
                                                type="button" 
                                                role="tab" 
                                                aria-controls="tabpanelPosted" >Posted
                                            </button>
                                        </a>
                                    </div>
                                </div>  
                            </div>
                        </div>
                    <hr class="mb-4"> --}}

                    <!-- Filters Row -->
                    <div class=" grid grid-cols-8 gap-4 mx-6 mt-2 overflow-x-auto whitespace-nowrap max-w-full custom-scrollbar">
                        <div class="flex items-center space-x-6">
                            <div class="col-span-2 p-4 rounded-tl-lg taxuri-color">
                                <p class="font-normal">Filter: <b>General Ledger</b></p>
                                <p class="font-normal" x-text="getFormattedDate()"></p>
                            </div>

                            <div class="flex items-center space-x-3">
                                <div class="flex flex-col w-32 taxuri-color">
                                    <label for="period_select" class="font-bold text-blue-900">Period </label>
                                    <select id="period_select" x-model="period" @change="updateYearAndMonthOptions" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="annually" selected>Annually</option>
                                    </select>
                                </div>
                                <div class="h-8 border-l border-gray-200"></div>
                                <!-- Year -->
                                <div class="flex flex-col w-32">
                                    <label for="year_select" class="font-bold text-blue-900">Year</label>
                                    <select id="year_select" x-model="selectedYear" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                        <template x-for="year in years" :key="year">
                                            <option :value="year" x-text="year"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="h-8 border-l border-gray-200"></div>
                                <!-- Quarter (Only visible if Quarterly is selected) -->
                                <div class="flex flex-col w-32" x-show="period === 'quarterly'">
                                    <label for="quarter_select" class="font-bold text-blue-900">Quarter</label>
                                    <select id="quarter_select" x-model="selectedQuarter" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                        <option value="Q1">1st Quarter</option>
                                        <option value="Q2">2nd Quarter</option>
                                        <option value="Q3">3rd Quarter</option>
                                        <option value="Q4">4th Quarter</option>
                                    </select>
                                </div>
                                <!-- Month (Only visible if Monthly is selected) -->
                                <div class="flex flex-col w-32" x-show="period === 'monthly'">
                                    <label for="month_select" class="font-bold text-blue-900">Month</label>
                                    <select id="month_select" x-model="selectedMonth" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                        <template x-for="month in months" :key="month.value">
                                            <option :value="month.value" x-text="month.label"></option>
                                        </template>
                                    </select>
                                </div>
                                <!-- Status Filter -->
                                <div class="flex flex-col w-32">
                                    <label for="status_filter" class="font-bold text-blue-900">Status</label>
                                    <select id="status_filter" name="status" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                                        <option value="">All</option>
                                        <option value="draft">Draft</option>
                                        <option value="posted">Posted</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Buttons -->
                            <div class="h-8 border-l border-gray-200"></div>
                            <div class="flex items-center space-x-4">
                                <button @click="applyFilters" class="flex items-center bg-white border border-gray-300 hover:border-green-500 hover:text-green-500 transition rounded-md px-4 py-2 whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 hover:border-green-500 hover:text-green-500 transition" viewBox="0 0 32 32">
                                        <path fill="#949494" d="M16 3C8.832 3 3 8.832 3 16s5.832 13 13 13s13-5.832 13-13S23.168 3 16 3m0 2c6.087 0 11 4.913 11 11s-4.913 11-11 11S5 22.087 5 16S9.913 5 16 5m-1 5v5h-5v2h5v5h2v-5h5v-2h-5v-5z"/>
                                    </svg>
                                    <span class="text-sm">Add Filter</span>
                                </button>
                                <button @click="resetFilters" class="text-sm text-gray-600 whitespace-nowrap">
                                    Clear all filters
                                </button>
                            </div>
                        </div>  
                    </div>

                    <hr class="my-4">

                    <!-- Start ng function ng table -->
                    <div class="mb-12 mt-6 mx-10 overflow-hidden max-w-full max-h-[300px] overflow-y-auto" id ="general-table">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-neutral-600">
                                <thead class="border-b bg-neutral-100 text-sm text-neutral-700">
                                    <tr>
                                        <th class="text-left py-4 px-4">Account Code</th>
                                        <th class="text-left py-4 px-4">Account</th>
                                        <th class="text-left py-4 px-4">Account Type</th>
                                        <th class="text-left py-4 px-4">Debit</th>
                                        <th class="text-left py-4 px-4">Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $transaction)
                                        @foreach($transaction->taxRows as $taxRow)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="text-left py-4 px-4">{{ $taxRow->coaAccount->code ?? 'N/A' }}</td>
                                                <td class="text-left py-4 px-4">{{ $taxRow->coaAccount->name ?? 'N/A' }}</td>
                                                <td class="text-left py-4 px-4">{{ $taxRow->coaAccount->type ?? 'N/A' }}</td>
                                                <td class="text-left py-4 px-4">{{ number_format($taxRow->debit ?? 0, 2) }}</td>
                                                <td class="text-left py-4 px-4">{{ number_format($taxRow->credit ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center p-6">
                                                <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                <h1 class="font-extrabold mt-4">No Transactions Available</h1>
                                                <p class="text-sm text-neutral-500 mt-2">You can start adding new transactions by going to the transactions page.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <hr>
                                
                                <!-- Totals Row for Debit and Credit -->
                                @if(count($transactions) > 0)
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" class="border-t-4 border-neutral-100"></td>
                                        </tr>
                                        <tr class=" font-bold">
                                            <td colspan="3" class="p-4 pe-56 text-right">Total</td>
                                            <td class="p-4">{{ number_format($totalDebit, 2) }}</td>
                                            <td class="p-4">{{ number_format($totalCredit, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>
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
                return `Listing as of ${month} 01, ${this.selectedYear}`;
            } else if (this.period === 'quarterly' && this.selectedQuarter) {
                return `Listing as of ${this.selectedQuarter}, ${this.selectedYear}`;
            } else {
                return `Listing as of January 01, ${this.selectedYear}`;
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

            // Add the status filter
            const statusFilter = document.getElementById('status_filter').value;
            if (statusFilter) {
                url.searchParams.set('status', statusFilter);
            }

            // Fetch filtered content
            await fetch(url.toString(), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newTableContent = doc.querySelector('#general-table').innerHTML;
                document.querySelector('#general-table').innerHTML = newTableContent;
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
                const newTableContent = doc.querySelector('#general-table').innerHTML;
                document.querySelector('#general-table').innerHTML = newTableContent;
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
        let url = `{{ route('ledger.exportExcel') }}?year=${year}&month=${month}&period=${period}&quarter=${quarter}&start_month=${startMonth}&end_month=${endMonth}&status=${status}`;
        
        // Log URL for debugging
        console.log("Export URL:", url);

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


</script>
