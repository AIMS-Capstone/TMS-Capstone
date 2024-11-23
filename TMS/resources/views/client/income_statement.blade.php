<x-client-layout>
    @php
    $organizationId = session('organization_id');
    $organization = \App\Models\OrgSetup::find($organizationId);
    @endphp

    {{-- Default date for initial export data --}}
    @php
        $currentYear = now()->year;
        $currentMonth = now()->format('m'); 
        $currentQuarter = 'Q' . ceil(now()->month / 3);
    @endphp
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto my-4 pt-4">
                    <div class="flex justify-between items-center px-10">
                        <div class="flex flex-row w-full items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24"><path fill="#273C75" d="M12 3.25c-5.59 0-10 2.82-10 6.46v4.58c0 3.62 4.39 6.46 10 6.46s10-2.84 10-6.46V9.72c0-3.63-4.43-6.47-10-6.47m8.5 11c0 2.69-3.89 5-8.5 5s-8.5-2.27-8.5-5V13.1c1.78 1.87 5 3 8.5 3s6.73-1.18 8.5-3z"/></svg>
                            <span><p class="font-bold text-3xl text-left taxuri-color">Income Statement</p></span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-10">
                        <div class="flex items-center">            
                            <p class="taxuri-text font-normal text-sm">This report is the Summary of all transactions entered in Taxuri, whether paid or not.</p>
                        </div>
                        <button type="button" onclick="exportReportExcel()" class="flex items-center text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                                <path fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                            </svg>
                            <span>Export Report</span>
                        </button>
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <div class="py-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="filterComponent()">
                <div class="container mx-auto my-4 pt-4">
                    <div class="text-sm grid grid-cols-8 gap-4 mx-8 overflow-x-auto whitespace-nowrap max-w-full border rounded-t-lg">
                        <div class="flex items-center space-x-6">
                            <div class="col-span-2 p-4 rounded-tl-lg bg-blue-50">
                                <p class="font-bold text-sky-900">{{ $organization->registration_name }}</p>
                                <p class="taxuri-text font-normal">Income Statement for the year</p>
                                <p class="taxuri-text font-normal" x-text="getFormattedDate()"></p>
                            </div>

                            <div class="flex items-center space-x-4">
                                <!-- Period -->
                                <div class="flex flex-col w-32">
                                    <label for="period_select" class="font-bold text-blue-900">Period</label>
                                    <select id="period_select" x-model="period" @change="updateYearAndMonthOptions"
                                            class="cursor-pointer block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <option value="monthly">Monthly</option>
                                        <option value="quarterly">Quarterly</option>
                                        <option value="annually" selected>Annually</option>
                                    </select>
                                </div>
                                <div class="h-8 border-l border-gray-200"></div>
                                <!-- Year -->
                                <div class="flex flex-col w-32">
                                    <label for="year_select" class="font-bold text-blue-900">Year</label>
                                    <select id="year_select" x-model="selectedYear"
                                            class="cursor-pointer block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <template x-for="year in years" :key="year">
                                            <option :value="year" x-text="year"></option>
                                        </template>
                                    </select>
                                </div>
                                <div class="h-8 border-l border-gray-200"></div>
                                <!-- Quarter (Only visible if Quarterly is selected) -->
                                <div class="flex flex-col w-32" x-show="period === 'quarterly'">
                                    <label for="quarter_select" class="font-bold text-blue-900">Quarter</label>
                                    <select id="quarter_select" x-model="selectedQuarter"
                                            class="cursor-pointer block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <option value="Q1">1st Quarter</option>
                                        <option value="Q2">2nd Quarter</option>
                                        <option value="Q3">3rd Quarter</option>
                                        <option value="Q4">4th Quarter</option>
                                    </select>
                                </div>

                                <!-- Month (Only visible if Monthly is selected) -->
                                <div class="flex flex-col w-32" x-show="period === 'monthly'">
                                    <label for="month_select" class="font-bold text-blue-900">Month</label>
                                    <select id="month_select" x-model="selectedMonth"
                                            class="cursor-pointer block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <template x-for="month in months" :key="month.value">
                                            <option :value="month.value" x-text="month.label"></option>
                                        </template>
                                    </select>
                                </div>

                                <!-- Status Filter -->
                                <div class="flex flex-col w-32">
                                    <label for="status_filter" class="font-bold text-blue-900">Status</label>
                                    <select id="status_filter" name="status"
                                            class="cursor-pointer block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <option value="draft">Draft</option>
                                        <option value="posted">Posted</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Filter Buttons -->
                            <div class="h-8 border-l border-gray-200"></div>
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

                        <div class=" p-8 overflow-x-auto" id = "financial" >
                            <div class="container bg-white border border-gray-300">
                                <div class="flex flex-row items-start space-x-2 pt-10 px-8 p-4 justify-between">
                                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Revenue</p>
                                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Amount</p>
                                </div>
                                <hr class="mx-8">

                                <div class="flex justify-between items-start space-x-2 p-4 px-10">
                                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Sales/Revenues/Receipts/Fees</p>
                                    <div>
                                        <p class=" text-gray-700">{{ number_format($totalCostOfSales, 2) }}</p>
                                    </div>
                                </div>

                                <hr class="mx-8 border-2 border-gray-800" />
                                
                                <div class="flex justify-between items-start px-10 space-x-2 p-4">
                                    <div>
                                        <p class="indent-8 font-bold text-sm text-gray-700">Total Revenue</p>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-700">{{ number_format($totalRevenue, 2) }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-row items-start space-x-2 p-4">
                                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Cost of Sales</p>
                                </div>
                                 
                                <hr class="mx-8">

                                <div class="flex justify-between items-start px-10 space-x-2 p-4">
                                    <div>
                                        <p class="indent-8 font-normal text-sm text-left text-gray-700">Cost of Goods Sold</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-700">{{ number_format($totalCostOfSales, 2) }}</p>
                                    </div>
                                </div>
                                <hr class="mx-8 border-2 border-gray-800" />

                                <div class="flex justify-between items-start px-10 space-x-2 p-4">
                                    <p class="indent-8 font-bold text-sm text-left text-gray-700">Total Cost of Sales</p>
                                    <div>
                                        <p class="font-bold text-gray-700">{{ number_format($totalCostOfSales, 2) }}</p>
                                    </div>
                                </div>
                            

                                <div class="flex justify-between items-start px-8 space-x-2 p-4">
                                    <div>
                                        <p class="font-bold text-xl text-left text-gray-700">Gross Profit</p>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-700">{{ number_format($grossProfit, 2) }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-row items-start space-x-2 p-4">
                                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Expenses</p>
                                </div>
                                <hr class="mx-8">
                                <div class="flex justify-between items-center space-x-2 p-4">
                                    <p class="indent-8 text-sm text-gray-700">Rental</p> 
                                    <p class="text-sm text-gray-700">{{ number_format($rentalTotal, 2) }}</p>
                                </div>
                                <hr class="mx-8">
                                <div class="flex justify-between items-center space-x-2 p-4">
                                    <p class="indent-8 text-sm text-gray-700">Depreciation</p>
                                    <p class="text-sm text-gray-700">{{ number_format($depreciationTotal, 2) }}</p>
                                </div>
                                <hr class="mx-8">
                                <div class="flex justify-between items-center space-x-2 p-4">
                                    <p class="indent-8 text-sm text-gray-700">Management and Consultancy Fee</p>
                                    <p class="text-sm text-gray-700">{{ number_format($managementFeeTotal, 2) }}</p>
                                </div>
                                <hr class="mx-8">
                                <div class="flex justify-between items-center space-x-2 p-4">
                                    <p class="indent-8 text-sm text-gray-700">Office Supplies</p>
                                    <p class="text-sm text-gray-700">{{ number_format($officeSuppliesTotal, 2) }}</p>
                                </div>
                                <hr class="mx-8">
                                <div class="flex justify-between items-center space-x-2 p-4">
                                    <p class="indent-8 text-sm text-gray-700">Professional Fees</p>
                                    <p class="text-sm text-gray-700">{{ number_format($professionalFeesTotal, 2) }}</p>
                                </div>
                                <hr class="mx-8">
                                <div class="flex justify-between items-center space-x-2 p-4">
                                    <p class="indent-8 text-sm text-gray-700">Representation and Entertainment</p>
                                    <p class="text-sm text-gray-700">{{ number_format($representationTotal, 2) }}</p>
                                </div>
                                <hr class="mx-8">
                                <div class="flex justify-between items-center space-x-2 p-4">
                                    <p class="indent-8 text-sm text-gray-700">Research and Development</p>
                                    <p class="text-sm text-gray-700">{{ number_format($researchDevelopmentTotal, 2) }}</p>
                                </div>
                                <hr class="mx-8">
                                <div class="flex justify-between items-center space-x-2 p-4">
                                    <p class="indent-8 text-sm text-gray-700">Salaries and Allowances</p>
                                    <p class="text-sm text-gray-700">{{ number_format($salariesAllowancesTotal, 2) }}</p>
                                </div>
                                <hr class="mx-8">
                                <div class="flex justify-between items-center space-x-2 p-4">
                                    <p class="indent-8 text-sm text-gray-700">SSS, GSIS, PhilHealth, HDMF and Other Contributions</p>
                                    <p class="text-sm text-gray-700">{{ number_format($contributionsTotal, 2) }}</p>
                                </div>
                                <hr class="mx-8">
                                <div class="flex justify-between items-center space-x-2 p-4">
                                    <p class="indent-8 text-sm text-gray-700">Others</p>
                                    <p class="text-sm text-gray-700">{{ number_format($otherExpensesTotal, 2) }}</p>
                                </div>

                                <hr class="mx-8 border-2 border-gray-800" />

                                <div class="flex justify-between items-start px-10 mt-4">
                                    <div>
                                        <p class="font-bold text-sm text-gray-700">Total Operating Expenses</p>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-700">{{ number_format($totalOperatingExpenses, 2) }}</p>
                                    </div>
                                </div>

                                <div class="flex justify-between items-start space-x-2 px-4 pt-4">
                                    <div>
                                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Net Income (Loss) From Operations</p>
                                    </div>
                                    <div>
                                        <p class="font-bold text-xl text-gray-700 pe-5">{{ number_format($netIncome, 2) }}</p>
                                    </div>
                                </div>

                                <div class="flex flex-row items-start space-x-2 p-4">
                                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Income Tax Expense</p>
                                </div>
                                <hr class="mx-8 border-2 border-gray-800" />

                                <div class="flex justify-between items-start space-x-2 p-4">
                                    <div>
                                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Net Income (Loss) From Operations</p>
                                    </div>
                                    <div>
                                        <p class="font-bold text-xl text-gray-700 pe-5">{{ number_format($netIncome, 2) }}</p>
                                    </div>
                                </div>

                                <div class="mx-8 mb-10 border-2 border-gray-800">
                                    <hr />
                                </div>
                        </div>
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
                    return `ending ${month} 30, ${this.selectedYear}`;
                } else if (this.period === 'quarterly' && this.selectedQuarter) {
                    return `ending ${this.selectedQuarter}, ${this.selectedYear}`;
                } else {
                    return `ending December 31, ${this.selectedYear}`;
                }
            },
            async applyFilters() {
                let url = new URL(window.location.origin + window.location.pathname);
                url.searchParams.set('year', this.selectedYear);
                url.searchParams.set('period', this.period);

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
                    const newTableContent = doc.querySelector('#financial').innerHTML;
                    document.querySelector('#financial').innerHTML = newTableContent;
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
                    const newTableContent = doc.querySelector('#financial').innerHTML;
                    document.querySelector('#financial').innerHTML = newTableContent;
                })
                .catch(error => console.error('Error resetting data:', error));
            }
        };
    }
        function exportReportExcel() {
            // Set default values using PHP variables
            const defaultYear = "{{ $currentYear }}";
            const defaultMonth = "{{ $currentMonth }}";
            const defaultQuarter = "{{ $currentQuarter }}";

            // Get values from dropdowns or use defaults if not selected
            const year = document.getElementById('year_select')?.value || defaultYear;
            const month = document.getElementById('month_select') ? document.getElementById('month_select').value || defaultMonth : '';
            const quarter = document.getElementById('quarter_select') ? document.getElementById('quarter_select').value || defaultQuarter : '';
            const status = document.getElementById('status_filter')?.value || '';

            // Build the URL with parameters
            let url = `{{ route('client.financial.exportExcel') }}?year=${year}&month=${month}&quarter=${quarter}&status=${status}`;
            
            window.location.href = url;
        }

    </script>
</x-client-layout>