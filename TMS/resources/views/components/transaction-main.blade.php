<!-- Page Header -->
@props(['transactions'])
<div class="container mx-auto my-4 pt-6">
    <div class="px-10">
        <div class="flex flex-row w-64 items-start space-x-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 256 256"><path fill="#1e3a8a" d="M216 40H40a16 16 0 0 0-16 16v152a8 8 0 0 0 11.58 7.15L64 200.94l28.42 14.21a8 8 0 0 0 7.16 0L128 200.94l28.42 14.21a8 8 0 0 0 7.16 0L192 200.94l28.42 14.21A8 8 0 0 0 232 208V56a16 16 0 0 0-16-16m-40 104H80a8 8 0 0 1 0-16h96a8 8 0 0 1 0 16m0-32H80a8 8 0 0 1 0-16h96a8 8 0 0 1 0 16"/></svg>
            <p class="font-bold text-3xl text-left taxuri-color">Transactions</p>
        </div>
    </div>
    <div class="flex justify-between items-center px-10">
        <div class="flex items-center px-2">            
            <p class="font-normal text-sm">The Transactions feature ensures accurate tracking and categorization <br> of each transaction.</p>
        </div>
        <div class="items-end float-end relative sm:w-auto" 
            x-data="{ selectedTab: (new URL(window.location.href)).searchParams.get('type') || 'All' }" 
            @filter.window="selectedTab = $event.detail.type">
            <button type="button" 
                    id="dropdownDefaultButton" 
                    :class="selectedTab === 'All' ? 'bg-gray-300 cursor-not-allowed' : 'bg-blue-900 hover:bg-blue-950'"
                    :disabled="selectedTab === 'All'"
                    class="text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none focus:ring-2 focus:ring-gray-300">
                <i class="fas fa-plus-circle mr-1"></i>
                Add Transaction
            </button>
            <div id="dropdown" class="absolute z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-md w-48 mt-2">
                <ul class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownDefaultButton">
                    
                    <!-- Options for Sales tab -->
                    <li x-show="selectedTab === 'Sales'">
                        <a href="{{ url('/transactions/create?type=sales') }}" class="block px-4 py-2 hover-dropdown">Add Manual</a>
                    </li>
                    <li x-show="selectedTab === 'Sales'">
                     
                    


                        <livewire:multi-step-import-modal/>

                    </li>
                    <li x-show="selectedTab === 'Sales'">
                        <a href="{{ url('/transactions/upload') }}" class="block px-4 py-2 hover-dropdown">Upload Image</a>
                    </li>
        
                    <!-- Options for Purchase tab -->
                    <li x-show="selectedTab === 'Purchase'">
                        <a href="{{ url('/transactions/create?type=purchase') }}" class="block px-4 py-2 hover-dropdown">Add Manual</a>
                    </li>
                    <li x-show="selectedTab === 'Purchase'">
                  
        
                    </li>
                    <li x-show="selectedTab === 'Purchase'">
                        <a href="#" class="block px-4 py-2 hover-dropdown">Upload Image</a>
                    </li>
        
                    <!-- Options for Journal tab -->
                    <li x-show="selectedTab === 'Journal'">
                        <a href="{{ url('/transactions/create?type=journal') }}" class="block px-4 py-2 hover-dropdown">Add Manual</a>
                    </li>
                    <li x-show="selectedTab === 'Journal'">
                        <a href="#" class="block px-4 py-2 hover-dropdown">Import CSV</a>
                    </li>
                </ul>
            </div>
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
            <div class="relative w-80 p-4">
                <form x-target="tableTransaction" action="/transactions" role="search" aria-label="Table" autocomplete="off">
                    <input 
                    type="search" 
                    name="search" 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-900 focus:border-blue-900" 
                    aria-label="Search Term" 
                    placeholder="Search..." 
                    @input.debounce="$el.form.requestSubmit()" 
                    @search="$el.form.requestSubmit()"
                    >
                </form>
                <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <!-- Sort by dropdown -->
            <div class="relative inline-block text-left min-w-[150px]">
                <button id="sortButton" class="flex items-center text-zinc-600">
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
    <hr>

    <!-- Filtering Tab -->
    <div x-data="{
        selectedTab: (new URL(window.location.href)).searchParams.get('type') || 'All',
        checkAll: false,
        init() {this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || 'All'; }}" 
        x-init="init" 
        class="w-full p-5">
        <div @keydown.right.prevent="$focus.wrap().next()" 
            @keydown.left.prevent="$focus.wrap().previous()" 
            class="flex flex-row text-center gap-2 overflow-x-auto ps-8" 
            role="tablist" 
            aria-label="tab options">
            
            <!-- Tab 1: All -->
            <button @click="selectedTab = 'All'; $dispatch('filter', { type: 'All' }); updateURL('All')"
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
                {{-- <span :class="selectedTab === 'All'
                    ? 'text-white bg-sky-900'
                    : 'bg-slate-500 text-white'"
                    class="text-xs font-medium px-1 rounded-full"></span> --}}
            </button>
            
            <!-- Tab 2: Sales -->
            <button @click="selectedTab = 'Sales'; $dispatch('filter', { type: 'Sales' }); updateURL('Sales')"
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
                {{-- <span :class="selectedTab === 'Sales'
                    ? 'text-white bg-sky-900'
                    : 'bg-slate-500 text-white'"
                    class="text-xs font-medium px-1 rounded-full"></span> --}}
            </button>
            
            <!-- Tab 3: Purchases -->
            <button @click="selectedTab = 'Purchase'; $dispatch('filter', { type: 'Purchase' }); updateURL('Purchase')"
                :aria-selected="selectedTab === 'Purchase'"
                :tabindex="selectedTab === 'Purchase' ? '0' : '-1'"
                :class="selectedTab === 'Purchase'
                    ? 'font-bold text-sky-900 bg-slate-200 border rounded-lg'
                    : 'text-neutral-600 font-medium hover:text-sky-900'"
                class="flex h-min items-center gap-2 px-4 py-2 text-sm"
                type="button"
                role="tab"
                aria-controls="tabpanelPurchases">
                Purchases
                {{-- <span :class="selectedTab === 'Purchases'
                    ? 'text-white bg-sky-900'
                    : 'bg-slate-500 text-white'"
                    class="text-xs font-medium px-1 rounded-full">0</span> --}}
            </button>
            
            <!-- Tab 4: Journal -->
            <button @click="selectedTab = 'Journal'; $dispatch('filter', { type: 'Journal' }); updateURL('Journal')"
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
                {{-- <span :class="selectedTab === 'Journal'
                    ? 'text-white bg-sky-900'
                    : 'bg-slate-500 text-white'"
                    class="text-xs font-medium px-1 rounded-full">0</span> --}}
            </button>
        </div>
    </div>

    <!-- Table -->
    <div x-data="{ checkAll: false, currentPage: 1, perPage: 5 }" class="mb-12 mx-12 overflow-hidden max-w-full border-neutral-300">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-neutral-600" id="tableTransaction">
                <thead class="bg-neutral-100 text-sm text-neutral-700">
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
                        <th scope="col" class="p-2">Invoice Number</th>
                        <th scope="col" class="p-2">Reference No.</th>
                        <th scope="col" class="p-2">Gross Amount</th>
                        <th scope="col" class="p-2">Type</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-300">
                    <!-- Check if there is any data for the current page -->
                    @if($transactions && $transactions->count() > 0)
                        @foreach ($transactions as $transaction)
                            <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                <td class="p-4">
                                    <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" id="user2335" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0" :checked="checkAll" />
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                        </div>
                                    </label>
                                </td>
                                    <td class="py-4 px-2 font-bold underline hover:text-blue-500">
                                        <a href="{{ route('transactions.show', ['transaction' => $transaction->id]) }}">
                                        {{ $transaction->contactDetails->bus_name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="py-4 px-2">{{$transaction ->date}}</td>
                                    <td class="py-4 px-2">{{$transaction ->inv_number}}</td>
                                    <td class="py-4 px-2">{{$transaction ->reference}}</td>
                                    <td class="py-4 px-2">{{$transaction ->vat_amount}}</td>
                                    <td class="py-4 px-2"><span class="bg-zinc-100 text-zinc-800 text-xs font-medium me-2 px-4 py-2 rounded-full">{{$transaction ->transaction_type}}</span></td>
                            </tr>                             
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="text-center p-4">
                                <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                <h1 class="font-extrabold text-lg mt-2">No Transactions yet</h1>
                                <p class="text-sm text-neutral-500 mt-2">Start adding transactions with the <br> + Add Transaction button.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <nav aria-label="pagination">
                {{ $transactions->links('vendor.pagination.custom') }}
            </nav>
        </div>
    </div>
</div>

<script>
    document.addEventListener('search', event => {
         window.location.href = `?search=${event.detail.search}`;
     });

     document.addEventListener('filter', event => {
         const url = new URL(window.location.href);
         url.searchParams.set('type', event.detail.type);
      
         window.location.href = url.toString();
     });    

     function toggleCheckboxes() {
         document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
             checkbox.checked = this.checkAll;
         });
     }

    document.addEventListener('DOMContentLoaded', function () {
        const dropdownButton = document.getElementById('dropdownDefaultButton');
        const dropdown = document.getElementById('dropdown');

        dropdownButton.addEventListener('click', function () {
            dropdown.classList.toggle('hidden');
        });
        document.addEventListener('click', function (event) {
            if (!dropdownButton.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });

    // FOR SORT BUTTON
    document.getElementById('sortButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('hidden');
    });

    // FOR SORT BY
    function sortItems(criteria) {
        const table = document.querySelector('table tbody');
        const rows = Array.from(table.querySelectorAll('tr')).filter(row => row.querySelector('td')); // Filter rows with data
        let sortedRows;

        if (criteria === 'recently-added') {
            // Sort by the order of rows (assuming they are in the order of addition)
            sortedRows = rows.reverse();
        } else {
            // Sort by text content of the 'Contact' column
            sortedRows = rows.sort((a, b) => {
                const aText = a.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
                const bText = b.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();

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

    // Dropdown event listeners
    document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
        item.addEventListener('click', function() {
            const criteria = this.getAttribute('data-sort');
            document.getElementById('selectedOption').textContent = this.textContent; // Update selected option text
            sortItems(criteria);
        });
    });

    // FOR SHOW ENTRIES
     // FOR BUTTON OF SHOW ENTRIES
    document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownDots');
        dropdown.classList.toggle('hidden');
    });
    // FOR SHOWING/SETTING ENTRIES
    function setEntries(entries) {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = "{{ route('transactions') }}";
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
