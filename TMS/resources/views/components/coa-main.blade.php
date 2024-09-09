    <!-- Page Header -->
    <div class="container mx-auto my-auto pt-6">
        <div class="px-10">
            <div class="flex flex-row w-full items-center">
                <img src="{{ asset('images/Frame 17.png') }}" class="px-2"> 
                <p class="font-bold text-3xl auth-color">Charts of Accounts</p>
            </div>
        </div>
        <div class="flex items-center px-10">
            <div class="flex items-center px-2">            
                <p class="auth-color">The Chart of Accounts feature organizes all your financial accounts in one <br> place, making it simple to manage and track your company’s finances.</p>
            </div>
        </div>      
    </div>

    <div x-data="coaTable() { showCheckboxes: false, selectedTab: 'All', checkAll: false }" class="container mx-auto pt-2 ">
        <!-- Second Header -->
        <div class="container mx-auto ps-8">
            <div class="flex flex-row space-x-2 items-center justify-center">
                <div x-data="{ selectedTab: 'Accounts' }" class="w-full">
                    <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300 dark:border-neutral-700" role="tablist" aria-label="tab options">
                        <button @click="selectedTab = 'Accounts'" :aria-selected="selectedTab === 'Accounts'" 
                        :tabindex="selectedTab === 'Accounts' ? '0' : '-1'" 
                        :class="selectedTab === 'Accounts' ? 'font-bold box-border text-sky-900 border-b-4 border-sky-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-sky-900 hover:text-sky-900'" 
                        class="h-min py-2 text-base" 
                        type="button"
                        role="tab" 
                        aria-controls="tabpanelAccounts" >Accounts</button>
                        <button @click="selectedTab = 'Archive'" :aria-selected="selectedTab === 'Archive'" 
                        :tabindex="selectedTab === 'Archive' ? '0' : '-1'" 
                        :class="selectedTab === 'Archive' ? 'font-bold box-border text-sky-900 border-b-4 border-sky-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-sky-900 hover:text-sky-900'"
                        class="h-min py-2 text-base" 
                        type="button" 
                        role="tab" 
                        aria-controls="tabpanelArchive" >Archive</button>
                    </div>
                </div>  
            </div>
        </div>
        <hr>
    
        <!-- Filtering Tab/Third Header -->
        <div x-data="{ selectedTab: 'All', checkAll: false }" class="w-full p-5">
            <div @keydown.right.prevent="$focus.wrap().next()" 
                @keydown.left.prevent="$focus.wrap().previous()" 
                class="flex flex-row text-center overflow-x-auto ps-8" 
                role="tablist" 
                aria-label="tab options">
                
                <!-- Tab 1: All -->
                <button @click="selectedTab = 'All'; fetchData()" 
                    :aria-selected="selectedTab === 'All'" 
                    :tabindex="selectedTab === 'All' ? '0' : '-1'" 
                    :class="selectedTab === 'All' 
                        ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                        : 'text-neutral-600 font-medium hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelAll"
                    >
                    All
                </button>
                
                <!-- Tab 2: Assets -->
                <button @click="selectedTab = 'Assets'; fetchData()" 
                    :aria-selected="selectedTab === 'Assets'" 
                    :tabindex="selectedTab === 'Assets' ? '0' : '-1'" 
                    :class="selectedTab === 'Assets' 
                        ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                        : 'text-neutral-600 font-medium hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelAssets">
                    Assets
                </button>
                
                <!-- Tab 3: Liabilities -->
                <button @click="selectedTab = 'Liabilities'; fetchData()" 
                    :aria-selected="selectedTab === 'Liabilities'" 
                    :tabindex="selectedTab === 'Liabilities' ? '0' : '-1'" 
                    :class="selectedTab === 'Liabilities' 
                        ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                        : 'text-neutral-600 font-medium hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelLiabilities">
                    Liabilities
                </button>
                
                <!-- Tab 4: Equity -->
                <button @click="selectedTab = 'Equity'; fetchData()" 
                    :aria-selected="selectedTab === 'Equity'" 
                    :tabindex="selectedTab === 'Equity' ? '0' : '-1'" 
                    :class="selectedTab === 'Equity' 
                        ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                        : 'text-neutral-600 font-medium hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelEquity">
                    Equity
                </button>
                
                <!-- Tab 5: Revenue -->
                <button @click="selectedTab = 'Revenue'; fetchData()" 
                    :aria-selected="selectedTab === 'Revenue'" 
                    :tabindex="selectedTab === 'Revenue' ? '0' : '-1'" 
                    :class="selectedTab === 'Revenue' 
                        ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                        : 'text-neutral-600 font-medium hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelRevenue">
                    Revenue
                </button>

                <!-- Tab 6: Cost of Sales -->
                <button @click="selectedTab = 'Cost of Sales'; fetchData()" 
                    :aria-selected="selectedTab === 'Cost of Sales'" 
                    :tabindex="selectedTab === 'Cost of Sales' ? '0' : '-1'" 
                    :class="selectedTab === 'Cost of Sales' 
                        ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                        : 'text-neutral-600 font-medium hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelCostofSales">
                    Cost of Sales
                </button>
                
                <!-- Tab 7: Expenses -->
                <button @click="selectedTab = 'Expenses'; fetchData()" 
                    :aria-selected="selectedTab === 'Expenses'" 
                    :tabindex="selectedTab === 'Expenses' ? '0' : '-1'" 
                    :class="selectedTab === 'Expenses' 
                        ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                        : 'text-neutral-600 font-medium hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelExpenses">
                    Expenses
                </button>

                <!-- Tab 8: Percentage of Revenue -->
                <button @click="selectedTab = 'Percentage of Revenue'; fetchData()" 
                    :aria-selected="selectedTab === 'Percentage of Revenue'" 
                    :tabindex="selectedTab === 'Percentage of Revenue' ? '0' : '-1'" 
                    :class="selectedTab === 'Percentage of Revenue' 
                        ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                        : 'text-neutral-600 font-medium hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelPercentageOfRevenue">
                    Percentage of Revenue
                </button>
            </div>
        </div>
        <hr>

        <div x-data="{ showCheckboxes: false, selectedTab: 'All', checkAll: false }" class="container mx-auto">
            <!-- Fourth Header -->
            <div class="container mx-auto ps-8">
                <div class="flex flex-row space-x-2 items-center justify-between">
                    <!-- Search row -->
                    <div x-data="{ search: '' }" class="relative w-80 p-5">
                        <input 
                        type="text" 
                        x-model="search" 
                        placeholder="Search..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900" 
                        @input.debounce.500ms="fetchData"
                        />
                        <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="fa-solid fa-xmark absolute right-8 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer"></i>
                    </div>
                    <!-- End row -->
                    <div class="mx-auto space-x-4 pr-12">
                        <!-- Add COA Modal -->
                        <x-add-coa-modal />
                        <button 
                            x-data 
                            x-on:click="$dispatch('open-add-modal')" 
                            class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
                        >
                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Add
                        </button>
                        <!-- Import COA Modal -->
                        <x-import-coa-modal />     
                        <button  
                            x-data 
                            x-on:click="$dispatch('open-import-modal')" 
                            class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
                        >
                            <i class="fa-solid fa-file-import"></i> Import
                        </button>
                        <button type="button" @click="showCheckboxes = !showCheckboxes" class="border px-3 py-2 rounded-lg text-sm">
                            <i class="fa-solid fa-download"></i> Download
                        </button>
                        <button type="button" @click="showCheckboxes = !showCheckboxes" class="border px-3 py-2 rounded-lg text-sm">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                        <button type="button">
                            <i class="fa-solid fa-ellipsis-vertical"></i>
                        </button>
                    </div>
                </div>
            </div>

        <!-- Table -->
        <div x-data="{ 
            checkAll: false, 
            currentPage: 3, 
            totalPages: 5, 
            data: ['Contact', 'Nigga', 'Nigga', 'Nigga', 'Nigga', 'Nigga', 'Nigga'], 
            perPage: 4 
        }" class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300 dark:border-neutral-700">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                    <thead class="border-b border-neutral-300 bg-slate-200 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                        <tr>
                            <th scope="col" class="p-4">
                                <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600 dark:text-neutral-300">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" x-model="checkAll" id="checkAll" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                        </svg>
                                    </div>
                                </label>
                            </th>
                            <th scope="col" class="py-4 px-2">Code</th>
                            <th scope="col" class="py-4 px-2">Name</th>
                            <th scope="col" class="py-4 px-4">Type</th>
                            <th scope="col" class="py-4 px-3">Date Created</th>
                            <th scope="col" class="py-4 px-2">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                        <template x-for="coa in coas" :key="coa.id">
                            @if (count($coas) >0)
                                @foreach ($coas as $coa)
                                    <tr>
                                        <td class="p-4">
                                            <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600 dark:text-neutral-300">
                                                <div class="relative flex items-center">
                                                    <input type="checkbox" x-model="checkAll" @click="toggleCheckboxes">
                                                </div>
                                            </label>
                                        </td>
                                        <td x-text="coa.code">{{$coa ->code}}</td>
                                        <td x-text="coa.name">{{$coa ->name}}</td>
                                        <td x-text="coa.type">{{$coa ->type}}</td>
                                        <td x-text="coa.created_at">{{$coa ->created_at}}</td>
                                        <td>edit</td>
                                    </tr>                           
                                @endforeach
                        </template>  
                             @else
                                <tr>
                                    <td><!-- Placeholder Image -->
                                        <tr>
                                            <td colspan="6" class="text-center p-4">
                                                <img src="{{ asset('images/Wallet 02.png') }}" alt="No data available" class="mx-auto" />
                                                <h1 class="font-bold mt-2">No Charts of accounts yet</h1>
                                                <p class="text-sm text-neutral-500 mt-2">Start adding accounts with the <br> + button beside the import button.</p>
                                            </td>
                                        </tr>
                                    </td>
                                </tr>
                            @endif
                    </tbody>
                </table>
                    <!-- Pagination -->
                    <div class="pagination">
                        <button @click="prevPage" :disabled="currentPage === 1">Prev</button>
                        <span x-text="currentPage"></span>
                        <button @click="nextPage" :disabled="currentPage === totalPages">Next</button>
                    </div>
            </div>
        </div>
    </div>
</div>

<script>

    function coaTable() {
    return {
        coas: [],
        search: '',
        filter: 'All',
        currentPage: 1,
        totalPages: 1,

        fetchData() {
            fetch(`/api/coa/filter?search=${this.search}&filter=${this.filter}&page=${this.currentPage}`)
                .then(response => response.json())
                .then(data => {
                    this.coas = data.data;
                    this.totalPages = data.last_page;
                    this.currentPage = data.current_page;
                });
        },

        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchData();
            }
        },

        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
                this.fetchData();
            }
        }
    }
}
    document.addEventListener('search', event => {
        window.location.href = `?search=${event.detail.search}`;
    });

    document.addEventListener('filter', event => {
        window.location.href = `?type=${event.detail.type}`;
    });

    function toggleCheckboxes() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = this.checkAll;
        });
    }
    
</script>