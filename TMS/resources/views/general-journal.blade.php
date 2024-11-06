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
                        <a href="{{ route('cash-disbursement') }}" class="hover:text-blue-950 dark:hover:text-white {{ request()->routeIs('cash-disbursement') ? 'breadcumb-active' : '' }}">Cash  Journal</a>
                    </li>
                </ol>
            </nav>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" x-data="filterComponent()">

                <div class="container mx-auto my-4 pt-4">
                    <div class="flex justify-between items-center px-10">
                        <p class="font-bold text-3xl text-left taxuri-color">General Journal </p>
                        <button type="button" onclick="exportReportExcel()" class="flex items-center text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                            </svg>
                            <span>Export Report</span>
                        </button>
                    </div>
                    <div class="flex justify-between items-center px-10">
                        <div class="flex items-center">            
                            <p class="font-normal text-sm">This book houses all the Journals entered in the Transactions Module.</p>
                        </div>
                    </div>  
                    <br>

                    <hr class="mb-4">

                    <!-- Filters Row -->
                    <div class=" grid grid-cols-8 gap-4 mx-10 overflow-x-auto whitespace-nowrap max-w-full">
                        <div class="flex items-center space-x-8">
                            <div class="col-span-2 p-4 rounded-tl-lg">
                                <p class="font-normal">Filter: <b>General Journal </b></p>
                                <p class="font-normal" x-text="getFormattedDate()"></p>
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

                        <div class="container mx-auto">
                            <div class="flex flex-row space-x-2 items-center justify-start">
                                <!-- Search row -->
                                <div class="relative w-80 p-4">
                                    <form x-target="general-table" action="/cash-disbursement" role="search" aria-label="Table" autocomplete="off">
                                        <input 
                                        type="search" 
                                        name="search" 
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
                            </div>
                        </div>
                        <hr class="flex w-full mx-auto">

                        <!-- Table for Cashs -->
                        <div class="mt-6 overflow-x-auto" id="general-table">
                            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                                <thead class="border-b border-gray-200 bg-gray-100 text-sm text-gray-600">
                                    <tr>
                                        <th scope="col" class="p-4">GL Date</th>
                                        <th scope="col" class="p-2">Reference</th>
                                        <th scope="col" class="p-2">Description</th>
                                        <th scope="col" class="p-2">Account Title</th>
                                        <th scope="col" class="p-2">(PHP)Debit</th>
                                        <th scope="col" class="p-2">(PHP)Credit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @if ($transactions->count() > 0)
                                        @foreach($transactions as $transaction)
                                            @foreach($transaction->taxRows as $taxRow)
                                                <tr class="border-b hover:bg-gray-50">
                                                    <td class="p-4">{{ \Carbon\Carbon::parse($transaction->date)->format('F d, Y') ?? 'N/A' }}</td>
                                                    <td class="p-4">{{ $transaction->reference ?? '000'}}</td>
                                                    <td class="p-4">{{ $taxRow->description ?? 'N/A'}}</td>
                                                    <td class="p-4">
                                                        {{ $taxRow->coaAccount->name ?? 'N/A' }}<br>
                                                        ({{ $taxRow->coaAccount->code ?? '000' }})
                                                    </td>
                                                    <td class="p-4">{{ number_format($taxRow->debit ?? 0, 2) }}</td>
                                                    <td class="p-4">{{ number_format($taxRow->credit ?? 0, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center p-6">
                                                <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                <h1 class="font-extrabold mt-4">No Transactions Available</h1>
                                                <p class="text-sm text-neutral-500 mt-2">You can start adding new transactions by going to the transactions page.</p>
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
                return ` as of ${month} 01, ${this.selectedYear}`;
            } else if (this.period === 'quarterly' && this.selectedQuarter) {
                return ` as of ${this.selectedQuarter}, ${this.selectedYear}`;
            } else {
                return ` as of January 01, ${this.selectedYear}`;
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
        let url = `{{ route('journal.exportExcel') }}?year=${year}&month=${month}&period=${period}&quarter=${quarter}&start_month=${startMonth}&end_month=${endMonth}&status=${status}`;
        
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
