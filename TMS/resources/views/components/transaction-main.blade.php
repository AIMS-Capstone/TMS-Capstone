<!-- Page Header -->
<div class="container mx-auto my-4 pt-6">
    <div class="px-10">
        <div class="flex flex-row w-64 items-start space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 256 256"><path fill="#172554" d="M216 40H40a16 16 0 0 0-16 16v152a8 8 0 0 0 11.58 7.15L64 200.94l28.42 14.21a8 8 0 0 0 7.16 0L128 200.94l28.42 14.21a8 8 0 0 0 7.16 0L192 200.94l28.42 14.21A8 8 0 0 0 232 208V56a16 16 0 0 0-16-16m-40 104H80a8 8 0 0 1 0-16h96a8 8 0 0 1 0 16m0-32H80a8 8 0 0 1 0-16h96a8 8 0 0 1 0 16"/></svg>
            <p class="font-bold text-3xl text-left taxuri-color">Transactions</p>
        </div>
    </div>
    <div class="flex justify-between items-center px-10">
        <div class="flex items-center px-2">            
            <p class="font-normal text-sm">The Transactions feature ensures accurate tracking and categorization <br> of each transaction.</p>
        </div>
        <div class="items-end float-end">
            <!-- routing papunta kay peter -->
            {{-- <a href = {{ route('/create') }}> --}}
            <button type="button" class= "text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
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
                <input type="text" x-model="search" placeholder="Search..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-950 focus:border-blue-950" />
                <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <i class="fa-solid fa-xmark absolute right-8 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer" @click="search = ''"></i>
            </div>
    
            <!-- Sort by dropdown -->
            <div class="relative inline-block text-left min-w-[150px]">
                <button id="sortButton" class="flex items-center text-gray-600 w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                    </svg>
                    <span id="selectedOption" class="font-normal text-md truncate">Sort by</span>
                </button>
    
                <div id="dropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
                    <div class="py-2 px-2">
                        <span class="block px-4 py-2 text-sm font-bold text-gray-700">Sort by</span>
                        <div onclick="sortItems('Recently Added')" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Added</div>
                        <div onclick="sortItems('Alphabetical')" class="block px-4 py-2 w-full text-sm hover-dropdown">Alphabetical</div>
                    </div>
                </div>
            </div>
    
            <!-- Spacer -->
            {{-- <div class="flex-grow"></div> --}}
    
            <!-- Buttons and Show Entries -->
            <div class="flex space-x-4 ps-96 items-center">
                <button type="button" @click="showCheckboxes = !showCheckboxes" class="border px-3 py-2 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                        <path fill="none" stroke="#696969" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/>
                    </svg>
                    <span class="font-normal text-md text-gray-600">Delete</span>
                </button>
                <button type="button" @click="showCheckboxes = !showCheckboxes" class="border px-3 py-2 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                        <path fill="none" stroke="#696969" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                    </svg> 
                    <span class="font-normal text-md text-gray-600">Download</span>
                </button>
            </div>
    
            <div class="relative inline-block space-x-4 text-left">
                <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" class="flex items-center text-gray-600" type="button">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                        <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                    </svg>
                </button>
                <!-- Dropdown menu -->
                <div id="dropdownDots" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-44 origin-top-right">
                    <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuIconButton">
                        <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Show Entries</span>
                        <div onclick="setEntries(5)" class="block px-4 py-2 w-full text-left hover-dropdown">5 per page</div>
                        <div onclick="setEntries(25)" class="block px-4 py-2 w-full text-left hover-dropdown">25 per page</div>
                        <div onclick="setEntries(50)" class="block px-4 py-2 w-full text-left hover-dropdown">50 per page</div>
                        <div onclick="setEntries(100)" class="block px-4 py-2 w-full text-left hover-dropdown">100 per page</div>
                    </div>
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
                    : 'text-neutral-600 font-medium hover:text-sky-900'" 
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
                    : 'text-neutral-600 font-medium hover:text-sky-900'" 
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
                    : 'text-neutral-600 font-medium hover:text-sky-900'" 
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
                    : 'text-neutral-600 font-medium hover:text-sky-900'" 
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
                    : 'text-neutral-600 font-medium hover:text-sky-900'" 
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
        currentPage: 1, 
        totalPages: 5, 
        data: ['Contact', 'Nigga', 'Chigga', 'Blassian', 'Nigga', 'Nigga', 'Nigga'], 
        perPage: 5 
    }" class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                <thead class="border-b border-gray-200 bg-zinc-100 text-sm text-gray-600">
                    <tr>
                        <th scope="col" class="p-4">
                            <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                <div class="relative flex items-center">
                                    <input type="checkbox" x-model="checkAll" id="checkAll" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-blue-950 checked:before:bg-blue-950 active:outline-offset-0" />
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
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
                <tbody class="divide-y divide-neutral-300">
                    <!-- Check if there is any data for the current page -->
                    <template x-if="data.slice((currentPage - 1) * perPage, currentPage * perPage).length > 0">
                        <template x-for="(item, index) in data.slice((currentPage - 1) * perPage, currentPage * perPage)" :key="index">
                            <tr>
                                <td class="p-4">
                                    <label for="user2335" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" id="user2335" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-blue-950 checked:before:bg-blue-950 active:outline-offset-0" :checked="checkAll" />
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
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
                        <button @click="currentPage = Math.max(currentPage - 1, 1)" :disabled="currentPage === 1" class="flex items-center rounded-md p-1 text-neutral-600 hover:text-black" aria-label="previous page">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" class="size-6">
                                <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </li>
                    <template x-for="page in totalPages" :key="page">
                        <li x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1) || page === currentPage">
                            <button @click="currentPage = page" :class="currentPage === page ? 'bg-sky-900 text-neutral-100' : 'text-neutral-600 hover:text-black'" class="flex size-6 items-center justify-center rounded-full p-1" :aria-current="currentPage === page" :aria-label="'page ' + page" x-text="page"></button>
                        </li>
                        <li x-show="page === currentPage - 2 || page === currentPage + 2">
                            <span class="flex items-center justify-center rounded-md p-1 text-neutral-600 dark:text-neutral-300" aria-label="ellipsis">...</span>
                        </li>
                    </template>
                    <li>
                        <button @click="currentPage = Math.min(currentPage + 1, totalPages)" :disabled="currentPage === totalPages" class="flex items-center rounded-md p-1 text-neutral-600 hover:text-black" aria-label="next page">
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
<script>
    document.getElementById('sortButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('hidden');
    });

    function sortItems(option) {
        document.getElementById('selectedOption').innerText = option;
        console.log("Sorting by:", option);
        // no sort yet, since no data
        document.getElementById('dropdownMenu').classList.add('hidden');
    }

    document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownDots');
        dropdown.classList.toggle('hidden');
    });

    function setEntries(entries) {
        console.log(`Setting ${entries} entries per page`);
        // no showing entries since no data yet
        document.getElementById('dropdownDots').classList.add('hidden');
    }
</script>