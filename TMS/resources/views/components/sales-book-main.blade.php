<div class="container mx-auto my-4 pt-4">
    <div class="flex justify-between items-center px-10">
        <div class="flex flex-row items-start space-x-2">
            <p class="font-bold text-3xl text-left taxuri-color">Sales Book Journal</p>
        </div>
        <div>
            <button type="button" class="flex items-center text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <path fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                </svg>
                <span>Export Report</span>
            </button>
        </div>
    </div>
    <div class="flex justify-between items-center px-10">
        <div class="flex items-center">            
            <p class="font-normal text-sm">This book houses all the Sales entered in the Transactions Module.</p>
        </div>
    </div>  
    <hr class="mt-6">
    <br>

    <nav class="flex gap-x-4 overflow-x-auto ml-10" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-4 text-sm font-medium text-center text-gray-500 hover:text-blue-900 rounded-lg"
            id="tab-draft"
            role="tab"
            aria-selected="true"
            onclick="activateTab('tab-draft')">
            Draft
        </button>
        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-center text-gray-500 hover:text-blue-900 rounded-lg"
            id="tab-posted"
            role="tab"
            aria-selected="false"
            onclick="activateTab('tab-posted')">
            Posted
        </button>
    </nav>
    
    <br>

    {{-- Draft Tab --}}
    <div id="tab-draft-content" role="tabpanel" aria-labelledby="tab-draft" class="overflow-x-auto px-10">
        <p class="text-gray-500 dark:text-neutral-400">
            This is the <em class="font-semibold text-gray-800 dark:text-neutral-200">first</em> item's tab body.
        </p>
    </div>

    {{-- Posted Tab --}}
    <div id="tab-posted-content" role="tabpanel" aria-labelledby="tab-posted" class="overflow-x-auto px-10 hidden">
        <div class="bg-white border border-gray-300 rounded-tl-lg rounded-tr-lg grid grid-cols-8 gap-4">

            {{-- NAKA BASED SA KUNG ANONG NAKA FILTER, HINDI SA KUNG SINO CLIENT --}}
            <div class="col-span-2 bg-blue-50 p-4 rounded-tl-lg">
                {{-- Org/Client --}}
                <span class="font-bold text-blue-950">Redondo Inc - Champion Dept</span>
                <p class="font-normal text-sm">General Ledger Listing as of</p>
                {{-- Date from "Period of Time and Year selected" --}}
                <p class="font-normal text-sm">December 31, 2024</p>
            </div>
    
            <div class="col-span-5 flex items-center justify-between space-x-4 p-4">
                <div class="flex items-center space-x-8">
                    <div class="h-8 border-l border-gray-200"></div>
                    <div class="flex flex-col w-32">
                        <label for="year_select" class="font-bold text-blue-950">Month</label>
                        <select id="year_select" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option selected>January</option>
                            <option selected>February</option>
                            <option selected>March</option>
                            <option selected>April</option>
                            <option selected>May</option>
                            <option selected>June</option>
                            <option selected>July</option>
                            <option selected>August</option>
                            <option selected>September</option>
                            <option selected>October</option>
                            <option selected>November</option>
                            <option selected>December</option>
                            <!-- Other options based sa client/org -->
                        </select>
                    </div>
                    <div class="h-8 border-l border-gray-200"></div>
                    <!-- Status -->
                    <div class="flex flex-col w-32">
                        <label for="status_select" class="font-bold text-blue-950">Year</label>
                        <select id="status_select" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option selected>2020</option>
                            <option selected>2021</option>
                            <option selected>2022</option>
                            <option selected>2023</option>
                            <!-- Other options based sa client/org-->
                        </select>
                    </div>
                </div>
                <div class="h-8 border-l border-gray-200"></div>

                {{-- Add filter button: para magenerate kung anong MONTH and YEAR ipapakita niya --}}
                <div class="flex items-center space-x-4">
                    <button class="flex items-center bg-white border border-gray-300 rounded-md px-4 py-2 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 32 32">
                            <path fill="#949494" d="M16 3C8.832 3 3 8.832 3 16s5.832 13 13 13s13-5.832 13-13S23.168 3 16 3m0 2c6.087 0 11 4.913 11 11s-4.913 11-11 11S5 22.087 5 16S9.913 5 16 5m-1 5v5h-5v2h5v5h2v-5h5v-2h-5v-5z"/>
                        </svg>
                        <span class="text-sm text-gray-600">Add Filter</span>
                    </button>
                    <button class="text-sm text-gray-600 whitespace-nowrap">
                        Clear all filters
                    </button>
                </div>

                {{-- ITONG DROPDOWN NA TO, WALA NAMAN SA FIGMA PERO BA PARA SA TABI 'TO NG MARK AS DRAFT BUTTON --}}
                <div x-data="paginationComponent()" class="relative inline-block space-x-4 text-left">
                    <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" class="flex items-center text-gray-600" type="button">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                    </button>
                    <div id="dropdownDots" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-44 origin-top-right">
                        <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuIconButton">
                            <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Show Entries</span>
                            <div @click="setEntries(5)" class="block px-4 py-2 w-full text-left hover-dropdown">5 per page</div>
                            <div @click="setEntries(25)" class="block px-4 py-2 w-full text-left hover-dropdown">25 per page</div>
                            <div @click="setEntries(50)" class="block px-4 py-2 w-full text-left hover-dropdown">50 per page</div>
                            <div @click="setEntries(100)" class="block px-4 py-2 w-full text-left hover-dropdown">100 per page</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search, Mark as Draft --}}
        <div class="container">
            <div class="flex items-center justify-between py-4">
                <!-- Search row -->
                <div x-data="{ search: '' }" class="relative w-80">
                    <input type="text" x-model="search" placeholder="Search..." class="w-full pl-16 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-950 focus:border-blue-950" />
                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    <i class="fa-solid fa-xmark absolute right-8 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer" @click="search = ''"></i>
                </div>
        
                <!-- Buttons -->
                <div  x-data="paginationComponent()" class="flex items-center space-x-4">
                    <button type="button" @click="showCheckboxes = !showCheckboxes" class="border px-3 py-2 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24"><path fill="#949494" d="m17 18l-5-2.18L7 18V5h10m0-2H7a2 2 0 0 0-2 2v16l7-3l7 3V5a2 2 0 0 0-2-2"/></svg>
                        <span class="font-normal text-md text-gray-600">Mark as Draft</span>
                    </button>
                    
                    {{-- NOT SURE PA KUNG PARA SAAN 'TONG BUTTON NA 'TO --}}
                    <div class="relative inline-block text-left">
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
        </div>

        <div x-data="paginationComponent()" x-init="showCheckboxes = false">
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                    <thead class="border-b border-gray-200 bg-gray-100 text-sm text-gray-600">
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
                            <th scope="col" class="p-4">Date</th>
                            <th scope="col" class="p-2">TIN</th>
                            <th scope="col" class="p-2">Customer Name</th>
                            <th scope="col" class="p-2">Customer Address</th>
                            <th scope="col" class="p-2">Invoice Number</th>
                            <th scope="col" class="p-2">Reference Number</th>
                            <th scope="col" class="p-2">Vatable Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in paginatedData" :key="index">
                            <tr>
                                <td class="py-6 px-3">
                                    <label for="user2335" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" id="user2335" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-blue-950 checked:before:bg-blue-950 active:outline-offset-0" :checked="checkAll" />
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                        </div>
                                    </label>
                                </td>
                                <td x-text="item.date" class="py-6 px-3 font-bold"></td>
                                <td x-text="item.tin" class="py-6 px-3"></td>
                                <td x-text="item.name" class="py-6 px-3"></td>
                                <td x-text="item.address" class="py-6 px-3"></td>
                                <td x-text="item.invNum" class="py-6 px-3"></td>
                                <td x-text="item.refNum" class="py-6 px-3"></td>
                                <td x-text="item.vat" class="py-6 px-3"></td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                
                <!-- Pagination Component -->
                <div class="flex justify-between items-center mt-6">
                    <div class="flex items-center space-x-1">
                        <button @click="previousPage()" class="p-2 text-gray-600 hover:font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <template x-for="page in totalPages" :key="page">
                            <button 
                                @click="goToPage(page)" 
                                :class="{'bg-blue-950 text-white': currentPage === page}"
                                class="w-5 h-5 flex items-center justify-center text-gray-600 hover:font-bold rounded-full"
                                x-text="page">
                            </button>
                        </template>
                        <button @click="nextPage()" class="p-2 text-gray-600 hover:font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                
                    <div class="ml-auto text-gray-600">
                        Showing <span x-text="startEntry"></span>-<span x-text="endEntry"></span> of <span x-text="totalEntries"></span> Entries
                    </div>
                </div>                
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
    activateTab('tab-draft');

    document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownDots');
        dropdown.classList.toggle('hidden');
    });

    function setEntries(entries) {
        const component = document.querySelector('[x-data="paginationComponent()"]').__x;
        component.perPage = entries;
        component.currentPage = 1;
        console.log(`Setting ${entries} entries per page`);
        document.getElementById('dropdownDots').classList.add('hidden');
    }

    function paginationComponent() {
        return {
            data: [
                {date: 'January 01, 2020', tin: '001-112-223-334', name: 'Aluminum Supply', address: 'Taguig', invNum: '0.00', refNum: '382439', vat: '14,109', selected: false},
                {date: 'January 01, 2020', tin: '001-112-223-334', name: 'Anadio', address: 'Taguig', invNum: '0.00', refNum: '35345', vat: '14,109', selected: false},
                // dito di ko sure kaya hindi ko rin mapagana show entries and pagination
            ],
            perPage: 5,
            currentPage: 1,
            showCheckboxes: false,
            get totalPages() {
                return Math.ceil(this.data.length / this.perPage);
            },
            get paginatedData() {
                const start = (this.currentPage - 1) * this.perPage;
                const end = start + this.perPage;
                return this.data.slice(start, end);
            },
            get startEntry() {
                return (this.currentPage - 1) * this.perPage + 1;
            },
            get endEntry() {
                return Math.min(this.currentPage * this.perPage, this.data.length);
            },
            get totalEntries() {
                return this.data.length;
            },
            goToPage(page) {
                this.currentPage = page;
            },
            nextPage() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                }
            },
            previousPage() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                }
            },
            toggleAll(event) {
                const checked = event.target.checked;
                this.paginatedData.forEach(item => item.selected = checked);
            },
            markAsDraft() {
                const selectedItems = this.data.filter(item => item.selected);
                console.log("Marked as Draft:", selectedItems);
                // draft marking logic 
            }
        };
    }
</script>