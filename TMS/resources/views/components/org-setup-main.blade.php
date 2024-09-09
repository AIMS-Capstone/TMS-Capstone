<nav class="flex gap-x-4 overflow-x-auto ml-10" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
    <button type="button" class="py-3 px-4 inline-flex items-center gap-x-4 text-sm font-medium text-center text-gray-500 hover:text-blue-900 rounded-lg"
        id="tab-org"
        role="tab"
        aria-selected="true"
        onclick="activateTab('tab-org')">
        Organizations
    </button>
    <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-center text-gray-500 hover:text-blue-900 rounded-lg"
        id="tab-tax"
        role="tab"
        aria-selected="false"
        onclick="activateTab('tab-tax')">
        Tax Reminder
    </button>
</nav>

<hr class="mx-8 mt-4">

{{-- Organizations TAB --}}
<div id="tab-org-content" role="tabpanel" aria-labelledby="tab-org" class="overflow-x-auto pt-6 px-10">
    <p class="font-bold text-3xl taxuri-color">
        Organizations
    </p>

    <div class="flex justify-between items-center">
        <div class="flex items-center">            
            <p class="font-normal text-sm">All created organizations, whether for business or clients, are listed on this page for easy <br /> 
                identification and management. Select an organization to start a session.</p>
        </div>
        <div class="items-end float-end">
            <!-- routing for create org -->
            <a href = {{ route('create-org') }}>
            <button type="button" class= "text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                <i class="fas fa-plus-circle mr-1"></i>
                    Create Organization
            </button>   
            </a> 
        </div>
    </div> 

    <div class="flex flex-col md:flex-row justify-between">
        {{-- Left Metrics --}}
        <div class="w-full md:w-[30%] bg-gray-100 mt-8 py-[13px] px-[13px] rounded-lg">
            <!-- First Item -->
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                    22
                </div>
                <div class="ml-4">
                    <div class="text-gray-800 font-bold text-sm sm:text-base">Total Organizations</div>
                    <div class="text-gray-500 text-xs sm:text-sm">Total number of organizations<br/>currently managed</div>
                </div>
            </div>
            <hr class="mx-8 my-1">
            
            <!-- Second Item -->
            <div class="flex items-center my-4">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                    5
                </div>
                <div class="ml-4">
                    <div class="text-gray-800 font-bold text-sm sm:text-base">Individual Clients</div>
                    <div class="text-gray-500 text-xs sm:text-sm">Total number of individual<br/>clients registered</div>
                </div>
            </div>
            <hr class="mx-8 my-1">
            
            <!-- Third Item -->
            <div class="flex items-center my-4">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                    17
                </div>
                <div class="ml-4">
                    <div class="text-gray-800 font-bold text-sm sm:text-base">Non-Individual Clients</div>
                    <div class="text-gray-500 text-xs sm:text-sm">Total number of non-individual<br/> clients registered</div>
                </div>
            </div>
            <hr class="mx-8 my-1">
            
            <!-- Fourth Item -->
            <div class="flex items-center my-4">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                    36
                </div>
                <div class="ml-4">
                    <div class="text-gray-800 font-bold text-sm sm:text-base">Filed Taxes</div>
                    <div class="text-gray-500 text-xs sm:text-sm">Total number of completed<br/>tax filings</div>
                </div>
            </div>
            <hr class="mx-8 my-1">
            
            <!-- Fifth Item -->
            <div class="flex items-center my-4">
                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                    19
                </div>
                <div class="ml-4">
                    <div class="text-gray-800 font-bold text-sm sm:text-base">Unfiled Taxes</div>
                    <div class="text-gray-500 text-xs sm:text-sm">Total number of pending<br/>tax filings</div>
                </div>
            </div>
        </div>
        
        {{-- Right Table --}}
        <div class="w-full md:w-3/4 mt-8 ml-0 md:ml-8 border border-gray-300 rounded-lg p-4 bg-white">
            <div class="flex flex-row items-center">
                <!-- Search row -->
                <div x-data="{ search: '' }" class="relative sm:w-80 p-5">
                    <input type="text" x-model="search" placeholder="Search..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-950 focus:border-blue-950" />
                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <i class="fa-solid fa-xmark absolute right-8 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer" @click="search = ''"></i>
                </div>
    
                <!-- Sort by dropdown -->
                <div class="relative inline-block text-left sm:w-auto">
                    <button id="sortButton" class="flex items-center text-gray-600 w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                        </svg>
                        <span id="selectedOption" class="font-normal text-md truncate">Sort by</span>
                    </button>
        
                    <div id="dropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
                        <div class="py-2 px-2">
                            <span class="block px-4 py-2 text-sm font-bold text-gray-700">Sort by</span>
                            <div data-sort="recently-added" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Added</div>
                            <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                            <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                        </div>
                    </div>
                </div>
        
                <div class="relative inline-block ps-72 space-x-4 text-left sm:w-auto">
                    <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" class="flex items-center text-gray-600" type="button">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                    </button>
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
    
            <hr class="border-gray-300 w-[calc(100%+2rem)] mx-[-1rem]">
    
            <div class="my-4 overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead class="bg-zinc-100 text-zinc-700">
                        <tr>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Tax Type</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Classification</th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">
                                Session
                                <span class="relative group items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block ml-1 w-4 h-4 text-zinc-700" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                                    </svg>
                                    <div class="hidden group-hover:block absolute left-1/2 transform -translate-x-1/2 mt-2 w-64 bg-white text-zinc-700 font-normal text-sm rounded-lg shadow-lg p-2 overflow-hidden">
                                        Select and click an organization in this column to get started.
                                    </div>
                                </span>
                            </th>
                            <th class="text-left py-3 px-4 font-semibold text-sm">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700">
                        <tr>
                            <td class="text-left py-2 px-3">
                                <div class="font-bold">Green Leaf Cafe</div>
                                <div>123-456-789-012</div>
                            </td>
                            <td class="text-left py-2 px-3">Value-Added Tax</td>
                            <td class="text-left py-2 px-3">Non-Individual</td>
                            <td class="text-left py-2 px-3 text-blue-600 underline">
                                <a href="#">Open Organization
                                    <span><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#2563eb" d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76"/></svg>
                                    </span>
                                </a>
                            </td>
                            <td class="relative text-left py-2 px-3">
                                <button id="dropdownMenuAction" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                        <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                    </svg>
                                </button>
                                <div id="dropdownAction" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-40 origin-top-right">
                                    <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuAction">
                                        <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Action</span>
                                        <div onclick="action()" class="block px-4 py-2 w-full text-left hover-dropdown">Delete</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left py-2 px-3">
                                <div class="font-bold">GreenTech Innovations</div>
                                <div>987-654-321-098</div>
                            </td>
                            <td class="text-left py-2 px-3">Value-Added Tax</td>
                            <td class="text-left py-2 px-3">Non-Individual</td>
                            <td class="text-left py-2 px-3 text-blue-600 underline">
                                <a href="#">Open Organization
                                    <span><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#2563eb" d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76"/></svg>
                                    </span>
                                </a>
                            </td>
                            <td class="relative text-left py-2 px-3">
                                <button id="dropdownMenuAction" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                        <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                    </svg>
                                </button>
                                <div id="dropdownAction" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-40 origin-top-right">
                                    <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuAction">
                                        <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Action</span>
                                        <div onclick="action()" class="block px-4 py-2 w-full text-left hover-dropdown">Delete</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left py-2 px-3">
                                <div class="font-bold">Luis Martinez</div>
                                <div>345-678-901-222</div>
                            </td>
                            <td class="text-left py-2 px-3">Percentage Tax</td>
                            <td class="text-left py-2 px-3">Individual</td>
                            <td class="text-left py-2 px-3 text-blue-600 underline">
                                <a href="#">Open Organization
                                    <span><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#2563eb" d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76"/></svg>
                                    </span>
                                </a>
                            </td>
                            <td class="relative text-left py-2 px-3">
                                <button id="dropdownMenuAction" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                        <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                    </svg>
                                </button>
                                <div id="dropdownAction" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-40 origin-top-right">
                                    <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuAction">
                                        <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Action</span>
                                        <div onclick="action()" class="block px-4 py-2 w-full text-left hover-dropdown">Delete</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left py-2 px-3">
                                <div class="font-bold">Urban Fashion Boutique</div>
                                <div>567-890-123-999</div>
                            </td>
                            <td class="text-left py-2 px-3">Percentage Tax</td>
                            <td class="text-left py-2 px-3">Non-Individual</td>
                            <td class="text-left py-2 px-3 text-blue-600 underline">
                                <a href="#">Open Organization
                                    <span><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#2563eb" d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76"/></svg>
                                    </span>
                                </a>
                            </td>
                            <td class="relative text-left py-2 px-3">
                                <button id="dropdownMenuAction" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                        <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                    </svg>
                                </button>
                                <div id="dropdownAction" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-40 origin-top-right">
                                    <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuAction">
                                        <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Action</span>
                                        <div onclick="action()" class="block px-4 py-2 w-full text-left hover-dropdown">Delete</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-left py-2 px-3">
                                <div class="font-bold">Flower Fashion Boutique</div>
                                <div>567-890-123-999</div>
                            </td>
                            <td class="text-left py-2 px-3">Percentage Tax</td>
                            <td class="text-left py-2 px-3">Non-Individual</td>
                            <td class="text-left py-2 px-3 text-blue-600 underline">
                                <a href="#">Open Organization
                                    <span><svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#2563eb" d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76"/></svg>
                                    </span>
                                </a>
                            </td>
                            <td class="relative text-left py-2 px-3">
                                <button id="dropdownMenuAction" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                        <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                    </svg>
                                </button>
                                <div id="dropdownAction" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-40 origin-top-right">
                                    <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuAction">
                                        <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Action</span>
                                        <div onclick="action()" class="block px-4 py-2 w-full text-left hover-dropdown">Delete</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    function activateTab(tabId) {
        document.querySelectorAll('[role="tabpanel"]').forEach(function(panel) {
            panel.classList.add('hidden');
        });
        document.querySelectorAll('button[role="tab"]').forEach(function(tab) {
            tab.classList.remove('font-extrabold', 'text-blue-900', 'active-tab'); 
            tab.classList.add('text-gray-500'); 
            tab.setAttribute('aria-selected', 'false');
        });
        document.getElementById(tabId + '-content').classList.remove('hidden');
        const activeTab = document.getElementById(tabId);
        activeTab.classList.add('font-extrabold', 'text-blue-900', 'active-tab'); 
        activeTab.classList.remove('text-gray-500'); 
        activeTab.setAttribute('aria-selected', 'true');
    }
    activateTab('tab-org');

    // FOR SORT BUTTON
    document.getElementById('sortButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('hidden');
    });

    // FOR SORT BY
    function sortItems(criteria) {
        const table = document.querySelector('table tbody');
        const rows = Array.from(table.querySelectorAll('tr'));
        let sortedRows;
        if (criteria === 'recently-added') {
            // Sort by the order of rows (assuming they are in the order of addition)
            sortedRows = rows.reverse();
        } else {
            // Sort by text content of the first column
            sortedRows = rows.sort((a, b) => {
                const aText = a.querySelector('td').textContent.trim().toLowerCase();
                const bText = b.querySelector('td').textContent.trim().toLowerCase();

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
    // to sort options
    document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
        item.addEventListener('click', function() {
            const criteria = this.getAttribute('data-sort');
            sortItems(criteria);
        });
    });

    // FOR BUTTON OF SHOW ENTRIES
    document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownDots');
        dropdown.classList.toggle('hidden');
    });

    // FOR ACTION BUTTON
    document.getElementById('dropdownMenuAction').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownAction');
        dropdown.classList.toggle('hidden');
    });

    // FOR SHOWING/SETTING ENTRIES
    function setEntries(entries) {
        console.log(`Setting ${entries} entries per page`);
        // no showing entries since no data yet
        document.getElementById('dropdownDots').classList.add('hidden');
    }
</script>