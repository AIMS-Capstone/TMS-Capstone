<x-client-layout>
    @php
    $organizationId = session('organization_id');
    $organization = \App\Models\OrgSetup::find($organizationId);
    @endphp
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg" x-data="filterComponent()">
                <div class="container mx-auto my-4 pt-4">

                    <!-- Filters Row -->
                    <div class="grid grid-cols-8 gap-4 mx-8 overflow-x-auto whitespace-nowrap max-w-full">
                        <div class="flex items-center space-x-8">
                            <div class="col-span-2 p-4 rounded-tl-lg">
                                <p class = "font-bold text-lg text-blue-900">{{ $organization->registration_name ?? 'Organization'}}</p>
                                <p class="font-normal">Generated Forms as of</p>
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
                                <!-- Status Filter -->
                                <div class="flex flex-col w-32">
                                    <label for="status_filter" class="font-bold text-blue-950">Status</label>
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

                        <div class="container mx-auto">
                            <div class="ps-8 flex flex-row space-x-2 items-center justify-start">
                                <!-- Search row -->
                                <div class="relative w-80 p-4">
                                    <form x-target="forms" action="forms" role="search" aria-label="Table" autocomplete="off">
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

                        <!-- Start ng table -->
                            <div class="mt-6 overflow-x-auto" id ="forms" style="max-height: 300px; overflow-y: auto;">
                                <table class="w-full text-left text-sm text-gray-600 ">
                                    <thead class="border-b border-gray-200 bg-gray-100 text-gray-600">
                                        <tr>
                                            <th class="p-4">Title</th>
                                            <th class="p-4">Period</th>
                                            <th class="p-4">Created By</th>
                                            <th class="p-4">Status</th>
                                            <th class="p-4">Last Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($taxReturns as $taxReturn)
                                            <tr class="border-b hover:bg-gray-50">
                                                <td class="p-4">{{ $taxReturn->title }}</td>
                                                <td class="p-4">{{ $taxReturn->formatted_period }}</td>
                                                <td class="p-4">{{ $taxReturn->user->name ?? 'N/A' }}</td>
                                                <td class="p-4">
                                                    <span class="px-2 py-1 rounded-full text-white {{ $taxReturn->status === 'Filed' ? 'bg-green-500' : ($taxReturn->status === 'Amended-Filed' ? 'bg-yellow-500' : 'bg-gray-400') }}">
                                                        {{ ucfirst($taxReturn->status) }}
                                                    </span>
                                                </td>
                                                <td class="p-4">{{ $taxReturn->updated_at->format('F d, Y g:i A') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center p-6">
                                                    <p class="font-extrabold">No Tax Returns Available</p>
                                                    <p class="text-sm text-neutral-500 mt-2">Try adjusting the filters or adding new tax returns.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination Links -->
                            <div class="px-10 py-4">
                                {{ $taxReturns->links() }}
                            </div>

                </div>
            </div>
        </div>
    </div>

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
                const newTableContent = doc.querySelector('#forms').innerHTML;
                document.querySelector('#forms').innerHTML = newTableContent;
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
                const newTableContent = doc.querySelector('#forms').innerHTML;
                document.querySelector('#forms').innerHTML = newTableContent;
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


</x-client-layout>