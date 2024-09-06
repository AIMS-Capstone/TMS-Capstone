    <!-- Page Header -->
    <div class="container mx-auto my-4 pt-6">
        <div class="px-10">
            <div class="flex flex-row w-64 items-start space-x-2">
                <img src="{{ asset('images/Frame 13.png') }}" class="px-2"> 
                <p class="font-bold text-3xl text-left auth-color">Transactions</p>
            </div>
        </div>
        <div class="flex justify-between items-center px-10">
            <div class="flex items-center px-2">            
                <p class="auth-color">The Transactions feature ensures accurate tracking and categorization <br> of each transaction.</p>
            </div>
            <div class="items-end float-end">
                <!-- routing papunta kay peter -->
                {{-- <a href = {{ route('/create') }}> --}}
                <button type="button" class= "text-white bg-sky-900 hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                    <i class="fas fa-plus-circle mr-1"></i>
                        Add Transaction
                </button>
                {{-- </a>  --}}
            </div>
        </div>  
    </div>  
        <br>
        <hr>

    <div x-data="{ showCheckboxes: false, selectedTab: 'All', checkAll: false }" class="container mx-auto pt-2 ">
        <!-- Second Header -->
        <div class="container mx-auto ps-8">
            <div class="flex flex-row space-x-2 items-center">
                <!-- Search row -->
                <div x-data="{ search: '' }" class="relative w-80 p-5">
                    <input type="text" x-model="search" placeholder="Search..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900" />
                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <i class="fa-solid fa-xmark absolute right-8 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer" @click="search = ''"></i>
                </div>
                <!-- Eto yung icon na hindi ko alam -->
                <div>

                </div>
                <div class="flex flex-row justify-between">
                    <div class="p-3 mt-1 text-slate-600">
                        <p>Recently Added</p>
                    </div>
                    <!-- End row -->
                    <div class= "mx-auto space-x-4 ps-64">
                        <button type="button" @click="showCheckboxes = !showCheckboxes" class="border px-3 py-2 rounded-lg">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                        <button type="button" @click="showCheckboxes = !showCheckboxes" class="border px-3 py-2 rounded-lg">
                            <i class="fa-solid fa-download"></i> Download
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    
        <!-- Filtering Tab -->
        <div x-data="{ selectedTab: 'All', checkAll: false }" class="w-full p-5">
            <div @keydown.right.prevent="$focus.wrap().next()" 
                @keydown.left.prevent="$focus.wrap().previous()" 
                class="flex flex-row text-center gap-2 overflow-x-auto ps-8" 
                role="tablist" 
                aria-label="tab options">
                
                <!-- Tab 1: All -->
                <button @click="selectedTab = 'All'" 
                    :aria-selected="selectedTab === 'All'" 
                    :tabindex="selectedTab === 'All' ? '0' : '-1'" 
                    :class="selectedTab === 'All' 
                        ? 'font-bold text-sky-900 bg-slate-200 border rounded-lg' 
                        : 'text-neutral-600 font-medium dark:text-white dark:hover:text-white hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelAll">
                    All
                    <span :class="selectedTab === 'All' 
                        ? 'text-white bg-sky-900 ' 
                        : 'bg-slate-500 text-white'" 
                        class="text-xs font-medium px-1 rounded-full">0</span>
                </button>
                
                <!-- Tab 2: Sales -->
                <button @click="selectedTab = 'Sales'" 
                    :aria-selected="selectedTab === 'Sales'" 
                    :tabindex="selectedTab === 'Sales' ? '0' : '-1'" 
                    :class="selectedTab === 'Sales' 
                        ? 'font-bold text-sky-900 bg-slate-200 border rounded-lg' 
                        : 'text-neutral-600 font-medium dark:text-white dark:hover:text-white hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelSales">
                    Sales
                    <span :class="selectedTab === 'Sales' 
                        ? 'text-white bg-sky-900 ' 
                        : 'bg-slate-500 text-white'" 
                        class="text-xs font-medium px-1 rounded-full">0</span>
                </button>
                
                <!-- Tab 3: Purchases -->
                <button @click="selectedTab = 'Purchases'" 
                    :aria-selected="selectedTab === 'Purchases'" 
                    :tabindex="selectedTab === 'Purchases' ? '0' : '-1'" 
                    :class="selectedTab === 'Purchases' 
                        ? 'font-bold text-sky-900 bg-slate-200 border rounded-lg' 
                        : 'text-neutral-600 font-medium dark:text-white dark:hover:text-white hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelPurchases">
                    Purchases
                    <span :class="selectedTab === 'Purchases' 
                        ? 'text-white bg-sky-900 ' 
                        : 'bg-slate-500 text-white'" 
                        class="text-xs font-medium px-1 rounded-full">0</span>
                </button>
                
                <!-- Tab 4: Journal -->
                <button @click="selectedTab = 'Journal'" 
                    :aria-selected="selectedTab === 'Journal'" 
                    :tabindex="selectedTab === 'Journal' ? '0' : '-1'" 
                    :class="selectedTab === 'Journal' 
                        ? 'font-bold text-sky-900 bg-slate-200 border rounded-lg' 
                        : 'text-neutral-600 font-medium dark:text-white dark:hover:text-white hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelJournal">
                    Journal
                    <span :class="selectedTab === 'Journal' 
                        ? 'text-white bg-sky-900 ' 
                        : 'bg-slate-500 text-white'" 
                        class="text-xs font-medium px-1 rounded-full">0</span>
                </button>
                
                <!-- Tab 5: Archived -->
                <button @click="selectedTab = 'Archived'" 
                    :aria-selected="selectedTab === 'Archived'" 
                    :tabindex="selectedTab === 'Archived' ? '0' : '-1'" 
                    :class="selectedTab === 'Archived' 
                        ? 'font-bold text-sky-900 bg-slate-200 border rounded-lg' 
                        : 'text-neutral-600 font-medium dark:text-white dark:hover:text-white hover:text-sky-900'" 
                    class="flex h-min items-center gap-2 px-4 py-2 text-sm" 
                    type="button" 
                    role="tab" 
                    aria-controls="tabpanelArchived">
                    Archived
                    <span :class="selectedTab === 'Archived' 
                        ? 'text-white bg-sky-900 ' 
                        : 'bg-slate-500 text-white'" 
                        class="text-xs font-medium px-1 rounded-full">0</span>
                </button>
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
                            <th scope="col" class="p-4">Contact</th>
                            <th scope="col" class="p-2">Date</th>
                            <th scope="col" class="p-2">Reference No.</th>
                            <th scope="col" class="p-2">Gross Amount</th>
                            <th scope="col" class="p-2">Type</th>
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
                                    <h1 class="font-bold mt-2">No Transactions yet</h1>
                                    <p class="text-sm text-neutral-500 mt-2">Start adding transactions with the <br> + button at the top.</p>
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