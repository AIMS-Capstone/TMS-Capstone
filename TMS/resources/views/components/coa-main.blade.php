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

    <div x-data="{ showCheckboxes: false, selectedTab: 'All', checkAll: false }" class="container mx-auto pt-2 ">
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
                <button @click="selectedTab = 'All'" 
                    :aria-selected="selectedTab === 'All'" 
                    :tabindex="selectedTab === 'All' ? '0' : '-1'" 
                    :class="selectedTab === 'All' 
                        ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                        : 'text-neutral-600 font-medium hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelAll">
                    All
                </button>
                
                <!-- Tab 2: Assets -->
                <button @click="selectedTab = 'Assets'" 
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
                <button @click="selectedTab = 'Liabilities'" 
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
                <button @click="selectedTab = 'Equity'" 
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
                <button @click="selectedTab = 'Revenue'" 
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
                <button @click="selectedTab = 'Cost of Sales'" 
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
                <button @click="selectedTab = 'Expenses'" 
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
                <button @click="selectedTab = 'Percentage of Revenue'" 
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
                        <input type="text" x-model="search" placeholder="Search..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900" />
                        <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <i class="fa-solid fa-xmark absolute right-8 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer" @click="search = ''"></i>
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
                        <!-- Check if there is any data for the current page -->
                        <template x-if="data.slice((currentPage - 1) * perPage, currentPage * perPage).length > 0">
                            <template x-for="(item, index) in data.slice((currentPage - 1) * perPage, currentPage * perPage)" :key="index">
                                <tr>
                                    <td class="p-4">
                                        <label for="user2335" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600 dark:text-neutral-300">
                                            <div class="relative flex items-center">
                                                <input type="checkbox" id="user2335" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" :checked="checkAll" />
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                </svg>
                                            </div>
                                        </label>
                                    </td>
                                    <td x-text="item.contact">Contact</td>
                                    <td x-text="item.date">Date</td>
                                    <td x-text="item.reference">Reference</td>
                                    <td x-text="item.amount">Amount</td>
                                    <td x-text="item.type">Type</td>
                                </tr>
                            </template>
                        </template>
                        
                        <!-- Placeholder Image -->
                        <template x-if="data.slice((currentPage - 1) * perPage, currentPage * perPage).length === 0">
                            <tr>
                                <td colspan="6" class="text-center p-4">
                                    <img src="{{ asset('images/Wallet 02.png') }}" alt="No data available" class="mx-auto" />
                                    <h1 class="font-bold mt-2">No Charts of accounts yet</h1>
                                    <p class="text-sm text-neutral-500 mt-2">Start adding accounts with the <br> + button beside the import button.</p>
                                </td>
                            </tr>
                        </template> 
                    </tbody>
                </table>
                <nav aria-label="pagination">
                    <ul class="flex flex-shrink-0 items-center gap-2 text-sm font-medium mt-4">
                        <li>
                            <button @click="currentPage = Math.max(currentPage - 1, 1)" :disabled="currentPage === 1" class="flex items-center rounded-md p-1 text-neutral-600 hover:text-black dark:text-neutral-300 dark:hover:text-white" aria-label="previous page">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-6">
                                    <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </li>
                        <template x-for="page in totalPages" :key="page">
                            <li x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1) || page === currentPage">
                                <button @click="currentPage = page" :class="currentPage === page ? 'bg-sky-900 text-neutral-100 dark:bg-white dark:text-black' : 'text-neutral-600 hover:text-black dark:text-neutral-300 dark:hover:text-white'" class="flex size-6 items-center justify-center rounded-full p-1" :aria-current="currentPage === page" :aria-label="'page ' + page" x-text="page"></button>
                            </li>
                            <li x-show="page === currentPage - 2 || page === currentPage + 2">
                                <span class="flex items-center justify-center rounded-md p-1 text-neutral-600 dark:text-neutral-300" aria-label="ellipsis">...</span>
                            </li>
                        </template>
                        <li>
                            <button @click="currentPage = Math.min(currentPage + 1, totalPages)" :disabled="currentPage === totalPages" class="flex items-center rounded-md p-1 text-neutral-600 hover:text-black dark:text-neutral-300 dark:hover:text-white" aria-label="next page">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-6">
                                    <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
