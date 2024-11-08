<x-organization-layout>
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-xs">
                <div class="overflow-x-auto ml-10 absolute flex items-center">
                    <button onclick="history.back()" class="text-zinc-600 hover:text-zinc-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                            <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g>
                        </svg>
                        <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go Back</span>
                    </button>
                </div>
                <div class="overflow-x-auto pt-6 px-10">
                    <p class="font-bold mt-4 text-3xl taxuri-color">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-8 h-8 justify-center" viewBox="0 0 16 16"><path fill="#1e3a8a" d="M7 14s-1 0-1-1s1-4 5-4s5 3 5 4s-1 1-1 1zm4-6a3 3 0 1 0 0-6a3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5a2.5 2.5 0 0 0 0 5"/></svg>
                        <span> User Management</span>
                    </p>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">            
                            <p class="font-normal text-sm">This page allows the Admin to efficiently manage all user accounts in Taxuri. The page <br /> displays a list of each type of account.</p>
                        </div>
                        <div class="items-end float-end">
                            <!-- routing for add account modal -->
                            @if (request('active_tab', 'acc') === 'acc')
                               
                            <button id="add-account-button" type="button" class="text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2" 
                            x-data 
                            x-on:click="$dispatch('open-add-account-modal')">
                            <i class="fas fa-plus-circle mr-1"></i>
                            Add Account
                        </button>
                            @endif
                        </div>
                    </div> 

                    <div class="flex gap-x-4 overflow-x-auto justify-center mt-4">
                        <div x-data="{ selectedTab: 'Client Users' }" class="w-full">
                            <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300 dark:border-neutral-700" role="tablist" aria-label="tab options">
                                <a href="{{ route('user-management.user') }}">
                                    <button @click="selectedTab = 'Accountant Users'" :aria-selected="selectedTab === 'Accountant Users'" 
                                    :tabindex="selectedTab === 'Accountant Users' ? '0' : '-1'" 
                                    :class="selectedTab === 'Accountant Users' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'" 
                                    class="h-min py-2 text-base" 
                                    type="button"
                                    role="tab" 
                                    aria-controls="tabpanelAccountantUsers" >Accountant Users</button>
                                </a>
                                <button @click="selectedTab = 'Client Users'" :aria-selected="selectedTab === 'Client Users'" 
                                :tabindex="selectedTab === 'Client Users' ? '0' : '-1'" 
                                :class="selectedTab === 'Client Users' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'"
                                class="h-min py-2 text-base" 
                                type="button" 
                                role="tab" 
                                aria-controls="tabpanelClientUsers" >Client Users</button>
                            </div>
                        </div>  
                    </div>
        
                    <hr class="mx-1 mt-auto">

        {{-- Accountant Users Table/Tab --}}
    

{{-- Client Users Table/Tab --}}
<div id="tab-client-content" role="tabpanel" aria-labelledby="tab-client" class="flex flex-col md:flex-row justify-between">
    <div class="w-full mt-8 ml-0 max-h-[500px] border border-zinc-300 rounded-lg p-4 bg-white">
        <div class="flex flex-row items-center">
            <!-- Search row -->
            <div class="relative w-80 p-5">
                <form action="{{ url()->current() }}" method="GET" role="search" aria-label="Table" autocomplete="off">
                    <input type="hidden" name="tab" value="client"> <!-- Hidden field to retain tab state -->
                    <input 
                        type="search" 
                        name="client_search" 
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
                            <input type="hidden" name="client_search" value="{{ request('client_search') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">

        <div class="my-4 overflow-y-auto max-h-[500px]">
            <table class="min-w-full bg-white" id="clientTable">
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
                    @foreach($clients as $client)
                        <tr id="{{ $client->id }}" class="hover:bg-slate-100 cursor-pointer ease-in-out" onclick="selectRow()">
                            <form action="{{ route('orgaccount.destroy', $client->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="organization_id" value="{{ $client->id }}">
                                <td class="text-left py-[7px] px-4">{{ $client->orgSetup->registration_name ?? 'N/A' }}</td>
                                <td class="text-left py-[7px] px-4">{{ $client->orgSetup->tin ?? 'N/A' }}</td>
                                <td class="text-left py-[7px] px-4">
                                    <span class="bg-amber-100 text-zinc-700 text-xs font-medium me-2 px-4 py-2 rounded-full">Client</span>
                                </td>
                                
                                <td class="text-left py-[7px] px-4">{{ $client->created_at->format('F j, Y') }}</td>
                                <td class="relative text-left py-2 px-3">
                                    <button type="button" id="clientDropdownMenuAction-{{ $client->id }}" class="text-zinc-500 hover:text-zinc-700">
                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                        </svg>
                                    </button>
                                    <div id="dropdownClientAction-{{ $client->id }}" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-32 origin-top-right overflow-hidden max-h-64 overflow-y-auto">
                                        <div 
                                        x-data 
                                        x-on:click="$dispatch('open-delete-client-modal', { clientId: '{{ $client->id }}', clientName: '{{ $client->name }}' })"  
                                        class="block px-4 py-2 w-full text-left hover-dropdown text-red-500 cursor-pointer">
                                        Delete
                                    </div>
                                    </div>
                                </td>
                            </form>
                        </tr>
                    @endforeach

                    @if($clients->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center p-2">
                                <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-40 h-40" />
                                <h1 class="font-extrabold">No Client Users yet</h1>
                                <p class="text-sm text-neutral-500">Start creating accounts with the <br> + Add Account button.</p>
                                <div class="flex items-center justify-center mb-2 mt-2">
                                    <a href="{{ route('org-setup') }}"
                                        <button type="button" class="inline-flex items-center w-48 justify-center px-3 py-2 bg-blue-900 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-blue-950 focus:bg-blue-950 active:bg-blue-950 focus:outline-none disabled:opacity-50 transition ease-in-out duration-150">
                                            {{ __('Go to Organizations') }} 
                                            <div class="ml-2 w-5 h-5 flex items-center justify-center border-2 border-white rounded-full">
                                                <svg class="rtl:rotate-180 w-3.5 h-3.5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                                </svg>
                                            </div>
                                        </button>         
                                    </a>                
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        
            {{ $clients->appends(request()->input())->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal CLIENT -->
<div x-data="{ open: false, clientId: null, clientName: '' }" 
@open-delete-client-modal.window="open = true; clientId = $event.detail.clientId; clientName = $event.detail.clientName" 
x-cloak>
<div x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
   <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
       <h2 class="text-lg font-semibold mb-4">Are you sure?</h2>
       <p class="mb-4">Do you really want to delete <strong x-text="clientName"></strong>? This action cannot be undone.</p>
       <div class="flex justify-end">
           <button type="button" @click="open = false" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">Cancel</button>
           <form method="POST" action="{{ route('orgaccount.destroy') }}" id="delete-form" class="inline">
               @csrf
               <input type="hidden" name="client_id" :value="clientId">
               <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">Delete</button>
           </form>
       </div>
   </div>
</div>

</div>

    <script>
        // TAB Activation Function
     // Function to activate a tab based on the provided tab ID
function activateTab(tabId) {
    // Hide all tab panels
    document.querySelectorAll('[role="tabpanel"]').forEach(function (panel) {
        panel.classList.add('hidden');
    });

    // Deactivate all tabs
    document.querySelectorAll('button[role="tab"]').forEach(function (tab) {
        tab.classList.remove('font-extrabold', 'text-blue-900', 'border-b-4', 'active-tab');
        tab.classList.add('text-gray-500');
        tab.setAttribute('aria-selected', 'false');
    });

    // Show the active tab's content
    const activePanel = document.getElementById(tabId + '-content');
    if (activePanel) {
        activePanel.classList.remove('hidden');
    }

    // Activate the selected tab
    const activeTab = document.getElementById(tabId);
    if (activeTab) {
        activeTab.classList.add('font-extrabold', 'text-blue-900', 'border-b-4', 'border-blue-900');
        activeTab.classList.remove('text-gray-500');
        activeTab.setAttribute('aria-selected', 'true');
    }

    // Handle visibility of the Add Account button
    const addAccountButton = document.getElementById('add-account-button');
    if (tabId === 'tab-acc') {
        addAccountButton?.classList.remove('hidden');
    } else {
        addAccountButton?.classList.add('hidden');
    }
}

// Function to initialize the active tab based on the URL query parameters
function initializeTabs() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'accountant'; // Default to accountant if no tab param

    // Activate the tab based on the URL parameter
    if (activeTab === 'client') {
        activateTab('tab-client'); // Activate Client Users tab
    } else {
        activateTab('tab-acc'); // Default to Accountant Users tab
    }
}

// Call initializeTabs when the document is ready
document.addEventListener('DOMContentLoaded', initializeTabs);


// Call initializeTabs when the document is ready
document.addEventListener('DOMContentLoaded', initializeTabs);

    
        // Show/hide dropdown on button click
        document.getElementById('sortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        });
    
        // Unified sorting function for Account tab
        function sortAccountItems(criteria) {
            const table = document.querySelector('#tableid1 tbody');
            const rows = Array.from(table.querySelectorAll('tr'));
            console.log('Original rows:', rows); // Log the original rows
            let sortedRows;
    
            if (criteria === 'recently-added') {
                // Sort by the order of rows (most recent first)
                sortedRows = rows.reverse();
                console.log('Sorted rows (recently-added):', sortedRows); // Log the sorted rows
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
                console.log('Sorted rows (alphabetically):', sortedRows); // Log the sorted rows
            }
    
            // Clear and re-append sorted rows
            table.innerHTML = ''; 
            sortedRows.forEach(row => table.appendChild(row));
    
            // Log the final table state
            console.log('Final rows in table:', table.querySelectorAll('tr'));
        }
    
        // Add click event listeners to each sort option for Account tab
        document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                console.log('Selected criteria:', criteria); // Log the criteria to console
                sortAccountItems(criteria); // Call the sorting function for Account
                document.getElementById('selectedOption').textContent = this.textContent; // Update button text
                document.getElementById('dropdownMenu').classList.add('hidden'); // Hide dropdown
            });
        });
    
        // Client tab sorting logic
        document.getElementById('clientSortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('clientDropdownMenu');
            dropdown.classList.toggle('hidden');
        });
    
        // Separate sorting function for Client tab
        function sortClientItems(criteria) {
            const table = document.querySelector('#clientTable tbody'); // Ensure correct table is selected
            const rows = Array.from(table.querySelectorAll('tr'));
            let sortedRows;
    
            if (criteria === 'recently-added') {
                sortedRows = rows.reverse();
            } else {
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
            // Clear and re-append sorted rows
            table.innerHTML = '';
            sortedRows.forEach(row => table.appendChild(row));
        }
    
        // Add click event listeners to each sort option for Client tab
        document.querySelectorAll('#clientDropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                console.log('Selected criteria for client:', criteria); // Log criteria
                sortClientItems(criteria); // Call the sorting function for Client
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
        // function setEntries(entries) {
        //     const form = document.createElement('form');
        //     form.method = 'GET';
        //     form.action = "{{ route('user-management') }}"; // Ensure this matches your route name

        //     // Input for number of entries per page
        //     const perPageInput = document.createElement('input');
        //     perPageInput.type = 'hidden';
        //     perPageInput.name = 'perPage'; // Make sure this matches your controller's expected input
        //     perPageInput.value = entries;

        //     // Input for search term (user search)
        //     const searchInput = document.createElement('input');
        //     searchInput.type = 'hidden';
        //     searchInput.name = 'user_search'; // Make sure this matches your controller's expected input
        //     searchInput.value = "{{ request('user_search') }}"; // Correcting to 'user_search'

        //     // Append inputs to form
        //     form.appendChild(perPageInput);
        //     form.appendChild(searchInput);

        //     // Append form to the document and submit
        //     document.body.appendChild(form);
        //     form.submit();
        // }

    
        // FOR CLIENT
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
        // function setClientEntries(entries) {
        //     const form = document.createElement('form');
        //     form.method = 'GET';
        //     form.action = "{{ route('user-management') }}";
        //     const perPageInput = document.createElement('input');
        //     perPageInput.type = 'hidden';
        //     perPageInput.name = 'perPage';
        //     perPageInput.value = entries;
        //     const searchInput = document.createElement('input');
        //     searchInput.type = 'hidden';
        //     searchInput.name = 'client_search';
        //     searchInput.value = "{{ request('client_search') }}";
        //     form.appendChild(perPageInput);
        //     form.appendChild(searchInput);
        //     document.body.appendChild(form);
        //     form.submit();
        // }
    </script>
    
</x-organization-layout>
