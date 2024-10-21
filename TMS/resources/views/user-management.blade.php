<x-organization-layout>
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-xs">
                <div class="overflow-x-auto pt-6 px-10">
                    <p class="font-bold text-3xl taxuri-color">
                        User Management
                    </p>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">            
                            <p class="font-normal text-sm">This page allows the Admin to efficiently manage all user accounts in Taxuri. The page <br /> displays a list of each type of account.</p>
                        </div>
                        <div class="items-end float-end">
                            <!-- routing for add account modal -->
                            @if (request('active_tab', 'acc') === 'acc')
                                <a href="#">
                                    <button id="add-account-button" type="button" class="text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                                        <i class="fas fa-plus-circle mr-1"></i>
                                        Add Account
                                    </button>   
                                </a> 
                            @endif
                        </div>
                    </div> 

                    <nav class="flex gap-x-4 overflow-x-auto justify-center mt-4" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-4 text-sm font-medium text-center text-gray-500 hover:text-blue-900"
                            id="tab-acc"
                            role="tab"
                            aria-selected="true"
                            onclick="activateTab('tab-acc')">
                            Accountant Users
                        </button>
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-center text-gray-500 hover:text-blue-900"
                            id="tab-client"
                            role="tab"
                            aria-selected="false"
                            onclick="activateTab('tab-client')">
                            Client Users
                        </button>
                    </nav>
        
                    <hr class="mx-1 mt-auto">

                    {{-- Accountant Users Table/Tab --}}
                    <div id="tab-acc-content" role="tabpanel" aria-labelledby="tab-acc" class="flex flex-col md:flex-row justify-between">
                        <div class="w-full mt-8 ml-0 max-h-[500px] border border-zinc-300 rounded-lg p-4 bg-white">
                            <div class="flex flex-row items-center">
                                <!-- Search row -->
                                <div class="relative w-80 p-5">
                                    <form x-target="tableid" action="/org-setup" role="search" aria-label="Table" autocomplete="off">
                                        <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-sky-900 focus:border-sky-900" 
                                        aria-label="Search Term" 
                                        placeholder="Search..." 
                                        @input.debounce="$el.form.requestSubmit()" 
                                        @search="$el.form.requestSubmit()"
                                        >
                                        </form>
                                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-zinc-400"></i>
                                </div>
                    
                                <!-- Sort by dropdown -->
                                <div class="relative inline-block text-left sm:w-auto">
                                    <button id="sortButton" class="flex items-center text-zinc-600 w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                            <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                                        </svg>
                                        <span id="selectedOption" class="font-normal text-md text-zinc-700 truncate">Sort by</span>
                                    </button>
                        
                                    <div id="dropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white hidden z-50">
                                        <div class="py-2 px-2">
                                            <span class="block px-4 py-2 text-sm font-bold text-zinc-700">Sort by</span>
                                            <div data-sort="recently-added" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Added</div>
                                            <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                            <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right side: Set as Session button and Dropdown --}}
                                <div class="ml-auto flex flex-row items-center space-x-4">
                                    <div class="relative inline-block space-x-4 text-left sm:w-auto">
                                        <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" class="flex items-center text-zinc-500 hover:text-zinc-700" type="button">
                                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                            </svg>
                                        </button>
                                        <div id="dropdownDots" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-44 origin-top-right">
                                            <div class="py-2 px-2 text-sm text-zinc-700" aria-labelledby="dropdownMenuIconButton">
                                                <span class="block px-4 py-2 text-sm font-bold text-zinc-700 text-left">Show Entries</span>
                                                <div onclick="setEntries(5)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">5 per page</div>
                                                <div onclick="setEntries(25)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">25 per page</div>
                                                <div onclick="setEntries(50)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">50 per page</div>
                                                <div onclick="setEntries(100)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">100 per page</div>
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">
                        
                            <div class="my-4 overflow-y-auto max-h-[500px]">
                                <table class="min-w-full bg-white" id="tableid">
                                    <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                                        <tr>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Email Address</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Account Type</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Date Created</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto">
                                            <tr id="" class="hover:bg-slate-100 cursor-pointer ease-in-out" onclick="selectRow()">
                                                <form action="" method="POST" id="">
                                                    @csrf
                                                    <input type="hidden" name="organization_id" value="">
                                                    <td class="text-left py-[7px] px-4">
                                                        <br/>
                                                    </td>
                                                    <td class="text-left py-[7px] px-4"></td>
                                                    <td class="text-left py-[7px] px-4">
                                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-4 py-2 rounded-full dark:bg-green-900 dark:text-green-300">Admin</span>
                                                        {{-- see display below kapag "Accountant" ang status --}}
                                                        {{-- <span class="bg-zinc-100 text-zinc-800 text-xs font-medium me-2 px-4 py-2 rounded-full dark:bg-zinc-700 dark:text-zinc-300">Accountant</span> --}}
                                                    </td>
                                                    <td class="text-left py-[7px] px-4"></td>
                                                    <td class="relative text-left py-2 px-3">
                                                        <button type="button" id="dropdownMenuAction-" class="text-zinc-500 hover:text-zinc-700">
                                                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                            </svg>
                                                        </button>
                                                        <div id="dropdownAction-" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-32 origin-top-right overflow-hidden max-h-64 overflow-y-auto">
                                                            <div class="py-2 px-2 text-sm text-zinc-700" aria-labelledby="dropdownMenuAction">
                                                                <div onclick="deleteAccount('')" class="block px-4 py-2 w-full text-left hover-dropdown">Delete</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </form>
                                            </tr>
                                        </tr>
                                            <tr>
                                                <td colspan="6" class="text-center p-2">
                                                    <img src="{{ asset('images/no-account.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                    <h1 class="font-extrabold">No Account yet</h1>
                                                    <p class="text-sm text-neutral-500 mt-2">Start creating accounts with the <br> + Add Account button.</p>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                                {{-- comment out na lang if may data na --}}
                                {{-- {{ $orgsetups->appends(request()->input())->links() }} --}}
                            </div>
                        </div>
                    </div>

                    {{-- Client Users Table/Tab --}}
                    <div id="tab-client-content" role="tabpanel" aria-labelledby="tab-client" class="flex flex-col md:flex-row justify-between">
                        <div class="w-full mt-8 ml-0 max-h-[500px] border border-zinc-300 rounded-lg p-4 bg-white">
                            <div class="flex flex-row items-center">
                                <!-- Search row -->
                                <div class="relative w-80 p-5">
                                    <form x-target="tableid" action="/user-management" role="search" aria-label="Table" autocomplete="off">
                                        <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-sky-900 focus:border-sky-900" 
                                        aria-label="Search Term" 
                                        placeholder="Search..." 
                                        @input.debounce="$el.form.requestSubmit()" 
                                        @search="$el.form.requestSubmit()"
                                        >
                                        </form>
                                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-zinc-400"></i>
                                </div>
                    
                                <!-- Sort by dropdown -->
                                <div class="relative inline-block text-left sm:w-auto">
                                    <button id="clientSortButton" class="flex items-center text-zinc-600 w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                            <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                                        </svg>
                                        <span id="selectedOption" class="font-normal text-md text-zinc-700 truncate">Sort by</span>
                                    </button>
                        
                                    <div id="clientDropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white hidden z-50">
                                        <div class="py-2 px-2">
                                            <span class="block px-4 py-2 text-sm font-bold text-zinc-700">Sort by</span>
                                            <div data-sort="recently-Added" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Added</div>
                                            <div data-sort="allAscending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                            <div data-sort="allDescending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right side: Set as Session button and Dropdown --}}
                                <div class="ml-auto flex flex-row items-center space-x-4">
                                    <div class="relative inline-block space-x-4 text-left sm:w-auto">
                                        <button id="dropdownMenuClientButton" data-dropdown-toggle="clientDropdownDots" class="flex items-center text-zinc-500 hover:text-zinc-700" type="button">
                                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                            </svg>
                                        </button>
                                        <div id="clientDropdownDots" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-44 origin-top-right">
                                            <div class="py-2 px-2 text-sm text-zinc-700" aria-labelledby="dropdownMenuClientButton">
                                                <span class="block px-4 py-2 text-sm font-bold text-zinc-700 text-left">Show Entries</span>
                                                <div onclick="setEntries(5)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">5 per page</div>
                                                <div onclick="setEntries(25)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">25 per page</div>
                                                <div onclick="setEntries(50)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">50 per page</div>
                                                <div onclick="setEntries(100)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">100 per page</div>
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">
                        
                            <div class="my-4 overflow-y-auto max-h-[500px]">
                                <table class="min-w-full bg-white" id="tableid">
                                    <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                                        <tr>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">TIN</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Account Type</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Date Created</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto">
                                            <tr id="" class="hover:bg-slate-100 cursor-pointer ease-in-out" onclick="selectRow()">
                                                <form action="" method="POST" id="">
                                                    @csrf
                                                    <input type="hidden" name="organization_id" value="">
                                                    <td class="text-left py-[7px] px-4">
                                                        <br/>
                                                    </td>
                                                    <td class="text-left py-[7px] px-4"></td>
                                                    <td class="text-left py-[7px] px-4">
                                                        <span class="bg-amber-100 text-zinc-700 text-xs font-medium me-2 px-4 py-2 rounded-full">Client</span>
                                                    </td>
                                                    <td class="text-left py-[7px] px-4"></td>
                                                    <td class="relative text-left py-2 px-3">
                                                        <button type="button" id="clientDropdownMenuAction-" class="text-zinc-500 hover:text-zinc-700">
                                                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                            </svg>
                                                        </button>
                                                        <div id="dropdownClientAction-" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-32 origin-top-right overflow-hidden max-h-64 overflow-y-auto">
                                                            <div class="py-2 px-2 text-sm text-zinc-700" aria-labelledby="dropdownMenuAction">
                                                                <div onclick="deleteClient('')" class="block px-4 py-2 w-full text-left hover-dropdown">Delete</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </form>
                                            </tr>
                                        </tr>
                                            <tr>
                                                <td colspan="6" class="text-center p-2">
                                                    <img src="{{ asset('images/no-account.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                    <h1 class="font-extrabold">No Account yet</h1>
                                                    <p class="text-sm text-neutral-500 mt-2">Start creating accounts with the <br> + Add Account button.</p>
                                                </td>
                                            </tr>
                                    </tbody>
                                </table>
                                {{-- comment out na lang if may data na --}}
                                {{-- {{ $orgsetups->appends(request()->input())->links() }} --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        //TAB
        function activateTab(tabId) {
            document.querySelectorAll('[role="tabpanel"]').forEach(function(panel) {
                panel.classList.add('hidden');
            });
            document.querySelectorAll('button[role="tab"]').forEach(function(tab) {
                tab.classList.remove('font-extrabold', 'text-blue-900', 'border-b-4', 'active-tab');
                tab.classList.add('text-gray-500');
                tab.setAttribute('aria-selected', 'false');
            });
            document.getElementById(tabId + '-content').classList.remove('hidden');
            
            const activeTab = document.getElementById(tabId);
            activeTab.classList.add('font-extrabold', 'text-blue-900', 'border-b-4', 'border-blue-900');
            activeTab.classList.remove('text-gray-500');
            activeTab.setAttribute('aria-selected', 'true');

            // Handle the visibility of the Add Account button via JavaScript
            const addAccountButton = document.getElementById('add-account-button');
            if (tabId === 'tab-acc') {
                addAccountButton?.classList.remove('hidden');
            } else {
                addAccountButton?.classList.add('hidden');
            }
        }
        activateTab('tab-acc'); 


        // FOR SORT BUTTON - Accountant TAB
        document.getElementById('sortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        });

        // FOR SORT BY - Accountant TAB
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

        // FOR BUTTON OF SHOW ENTRIES - Accountant TAB
        document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownDots');
            dropdown.classList.toggle('hidden');
        });

        // FOR ACTION BUTTON - Accountant TAB
        document.querySelectorAll('[id^="dropdownMenuAction-"]').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.id.split('-')[1];
                const dropdown = document.getElementById(`dropdownAction-${id}`);
                dropdown.classList.toggle('hidden');
            });
        });

        // FOR SHOWING/SETTING ENTRIES - Accountant TAB
        function setEntries(entries) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('user-management') }}";
            // Create a hidden input for perPage
            const perPageInput = document.createElement('input');
            perPageInput.type = 'hidden';
            perPageInput.name = 'perPage';
            perPageInput.value = entries;
            // Add search input value if needed
            const searchInput = document.createElement('input');
            searchInput.type = 'hidden';
            searchInput.name = 'search';
            searchInput.value = "{{ request('search') }}";
            // Append inputs to form
            form.appendChild(perPageInput);
            form.appendChild(searchInput);
            // Append the form to the body and submit
            document.body.appendChild(form);
            form.submit();
        }

        // FOR CLIENT
        // FOR SORT BUTTON - Client TAB
        document.getElementById('clientSortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('clientDropdownMenu');
            dropdown.classList.toggle('hidden');
        });

        // FOR SORT BY - Client TAB
        function sortItems(criteria) {
            const table = document.querySelector('table tbody');
            const rows = Array.from(table.querySelectorAll('tr'));
            let sortedRows;
            if (criteria === 'recently-Added') {
                // Sort by the order of rows (assuming they are in the order of addition)
                sortedRows = rows.reverse();
            } else {
                // Sort by text content of the first column
                sortedRows = rows.sort((a, b) => {
                    const aText = a.querySelector('td').textContent.trim().toLowerCase();
                    const bText = b.querySelector('td').textContent.trim().toLowerCase();

                    if (criteria === 'allAscending') {
                        return aText.localeCompare(bText);
                    } else if (criteria === 'allDescending') {
                        return bText.localeCompare(aText);
                    }
                });
            }
            // Append sorted rows back to the table body
            table.innerHTML = '';
            sortedRows.forEach(row => table.appendChild(row));
        }
        // to sort options
        document.querySelectorAll('#clientDropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                sortItems(criteria);
            });
        });

        // FOR BUTTON OF SHOW ENTRIES - Client TAB
        document.getElementById('dropdownMenuClientButton').addEventListener('click', function() {
            const dropdown = document.getElementById('clientDropdownDots');
            dropdown.classList.toggle('hidden');
        });

        // FOR ACTION BUTTON - Client TAB
        document.querySelectorAll('[id^="clientDropdownMenuAction-"]').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.id.split('-')[1];
                const dropdown = document.getElementById(`dropdownClientAction-${id}`);
                dropdown.classList.toggle('hidden');
            });
        });

        // FOR SHOWING/SETTING ENTRIES - Client TAB
        function setEntries(entries) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('user-management') }}";
            // Create a hidden input for perPage
            const perPageInput = document.createElement('input');
            perPageInput.type = 'hidden';
            perPageInput.name = 'perPage';
            perPageInput.value = entries;
            // Add search input value if needed
            const searchInput = document.createElement('input');
            searchInput.type = 'hidden';
            searchInput.name = 'search';
            searchInput.value = "{{ request('search') }}";
            // Append inputs to form
            form.appendChild(perPageInput);
            form.appendChild(searchInput);
            // Append the form to the body and submit
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</x-organization-layout>
