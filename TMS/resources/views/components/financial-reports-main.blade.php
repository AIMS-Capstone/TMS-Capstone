<div class="container mx-auto my-4 pt-4">
    <div class="flex justify-between items-center px-10">
        <div class="flex flex-row items-start space-x-2">
            <p class="font-bold text-3xl text-left taxuri-color">Income Statement</p>
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
            <p class="font-normal text-sm">This report is the Summary of all transactions entered in Taxuri.</p>
        </div>
    </div>  
    
    <br>

    <div class="overflow-x-auto px-10">
        <div class="bg-white border border-gray-300 rounded-tl-lg rounded-tr-lg grid grid-cols-8 gap-4">
            <div class="col-span-2 bg-blue-50 p-4 rounded-tl-lg">
                {{-- Org/Client --}}
                <span class="font-bold text-blue-950">Redondo Inc - Champion Dept</span>
                <p class="font-normal text-sm">Income Statement for the year ending</p>
                {{-- Date from "Period of Time and Year selected" --}}
                <p class="font-normal text-sm">December 31, 2024</p>
            </div>
    
            <div class="col-span-5 flex items-center justify-between space-x-4 p-4">
                <div class="flex items-center space-x-8">
                    <!-- Period of Time -->
                    <div class="flex flex-col w-32">
                        <label for="period_select" class="font-bold text-blue-950">Period of Time</label>
                        <select id="period_select" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option selected>Annually</option>
                            <!-- Other options -->
                        </select>
                    </div>
                    <div class="h-8 border-l border-gray-200"></div>
                    <!-- Year -->
                    <div class="flex flex-col w-32">
                        <label for="year_select" class="font-bold text-blue-950">Year</label>
                        <select id="year_select" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option selected>2024</option>
                            <!-- Other options -->
                        </select>
                    </div>
                    <div class="h-8 border-l border-gray-200"></div>
                    <!-- Status -->
                    <div class="flex flex-col w-32">
                        <label for="status_select" class="font-bold text-blue-950">Status</label>
                        <select id="status_select" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option selected>Posted</option>
                            <!-- Other options -->
                        </select>
                    </div>
                </div>
                <div class="h-8 border-l border-gray-200"></div>
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
                {{-- <div x-data="paginationComponent()" class="relative inline-block space-x-4 text-left">
                    <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" class="flex items-center text-gray-600" type="button">
                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdownDots" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-44 origin-top-right">
                        <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuIconButton">
                            <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Show Entries</span>
                            <div @click="setEntries(5)" class="block px-4 py-2 w-full text-left hover-dropdown">5 per page</div>
                            <div @click="setEntries(25)" class="block px-4 py-2 w-full text-left hover-dropdown">25 per page</div>
                            <div @click="setEntries(50)" class="block px-4 py-2 w-full text-left hover-dropdown">50 per page</div>
                            <div @click="setEntries(100)" class="block px-4 py-2 w-full text-left hover-dropdown">100 per page</div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <div class="container bg-white border border-gray-300">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Revenue</p>
                </div>
                <hr class="mx-8">

                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Sales/Revenues/Receipts/Fees</p>
                </div>

                <hr class="mx-8 border-2 border-gray-800" />
                
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-bold text-sm text-left text-gray-700">Total Revenue</p>
                </div>

                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Cost of Sales</p>
                </div>
                <hr class="mx-8">

                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Cost of Goods Sold</p>
                </div>
                <hr class="mx-8 border-2 border-gray-800" />

                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-bold text-sm text-left text-gray-700">Total Cost of Sales</p>
                </div>

                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Gross Profit</p>
                </div>

                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Expenses</p>
                </div>
                <hr class="mx-8">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Depreciation</p>
                </div>
                <hr class="mx-8">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Management and Consultancy Fee</p>
                </div>
                <hr class="mx-8">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Office Supplies</p>
                </div>
                <hr class="mx-8">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Professional Fees</p>
                </div>
                <hr class="mx-8">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Rental</p>
                </div>
                <hr class="mx-8">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Representation and Entertainment</p>
                </div>
                <hr class="mx-8">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Research and Development</p>
                </div>
                <hr class="mx-8">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Salaries and Allowances</p>
                </div>
                <hr class="mx-8">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">SSS, GSIS, PhilHealth, HDMF and Other Contributions</p>
                </div>
                <hr class="mx-8">
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Others</p>
                </div>

                <hr class="mx-8 border-2 border-gray-800" />
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-bold text-sm text-left text-gray-700">Total Operating Expenses</p>
                </div>

                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Net Income (Loss) From Operations</p>
                </div>
                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-8 font-normal text-sm text-left text-gray-700">Income Tax Expense</p>
                </div>
                <hr class="mx-8 border-2 border-gray-800" />

                <div class="flex flex-row items-start space-x-2 p-4">
                    <p class="indent-4 font-bold text-xl text-left text-gray-700">Net Income (Loss) From Operations</p>
                </div>
                <div class="mx-8 mb-10 border-2 border-gray-800">
                    <hr />
                </div>

            <div>

        </div>

        
    </div>
</div>  
<script>
    document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownDots');
        dropdown.classList.toggle('hidden');
    });

    function setEntries(entries) {
        const component = document.querySelector('[x-data="paginationComponent()"]').__x;
        component.perPage = entries;
        component.currentPage = 1; // Reset to first page
        console.log(`Setting ${entries} entries per page`);
        document.getElementById('dropdownDots').classList.add('hidden');
    }

   
</script>