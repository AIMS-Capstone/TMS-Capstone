@php
$organizationId = session('organization_id');
@endphp
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Main -->
                <div class="px-10 py-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">Withholding Tax Return</li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ route('with_holding.1604C') }}" 
                                        class="ms-1 text-sm font-medium {{ Request::routeIs('with_holding.1604C') ? 'font-extrabold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                        1604C
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="" class="ms-1 text-sm font-bold text-blue-900 md:ms-2">Sources</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <hr>

                <div class="container mx-auto">
                    <div class="flex flex-row space-x-2 items-center justify-between">
                        <div class="flex flex-row space-x-2 items-center ps-6">
                            <!-- Search row -->
                            <div class="relative w-80 p-4">
                                <form x-target="tableid" action="/1604C" role="search" aria-label="Table" autocomplete="off">
                                    <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-[7px] text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900" 
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
                                    <span id="selectedOption" class="font-normal text-sm text-zinc-600 hover:text-zinc-800 truncate">Sort by</span>
                                    <svg class="w-2.5 h-2.5 ms-2 transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m1 1 4 4 4-4"/></svg>
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
                        </div>

                        <div class="flex space-x-4 items-center pr-10 ml-auto">
                            {{-- Show Entries --}}
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
                    <hr>

                    <!-- Filtering Tab/Third Header -->
                    <div x-data="{selectedTab: 'Sources', init() {this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || 'Sources'; }
                        }" x-init="init" class="w-full p-5">
                        <div @keydown.right.prevent="$focus.wrap().next()" 
                            @keydown.left.prevent="$focus.wrap().previous()" 
                            class="flex flex-row text-center overflow-x-auto ps-5" 
                            role="tablist" 
                            aria-label="tab options">
                            
                            <!-- Tab 1: Remittance -->
                            <a href="{{ route('with_holding.1604C_remittances', ['id' => $with_holding->id]) }}">
                                <button @click="selectedTab = 'Remittance'; $dispatch('filter', { type: 'Remittance' })"
                                    :aria-selected="selectedTab === 'Remittance'" 
                                    :tabindex="selectedTab === 'Remittance' ? '0' : '-1'" 
                                    :class="selectedTab === 'Remittance' 
                                        ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                        : 'text-zinc-600 font-medium hover:text-blue-900'"
                                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="tabpanelRemittance">
                                    Remittance
                                </button>
                            </a>
                            <!-- Tab 2: Sources -->
                            <button @click="selectedTab = 'Sources'" 
                                :aria-selected="selectedTab === 'Sources'" 
                                :tabindex="selectedTab === 'Sources' ? '0' : '-1'" 
                                :class="selectedTab === 'Sources' 
                                    ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                    : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                type="button" 
                                role="tab" 
                                aria-controls="tabpanelSources">
                                Sources
                            </button>
                            <!-- Tab 3: Report -->
                            <a href="{{ route('form1604C.create', ['id' => $with_holding->id]) }}">
                                <button @click="selectedTab = 'Report'" 
                                    :aria-selected="selectedTab === 'Report'" 
                                    :tabindex="selectedTab === 'Report' ? '0' : '-1'" 
                                    :class="selectedTab === 'Report' 
                                        ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                        : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="tabpanelReport">
                                    Report
                                </button>
                            </a>
                        </div>
                    </div>
    
                    <div class="container mx-auto overflow-hidden">
                        <div class="container mx-auto ps-8">
                            <div class="flex flex-row space-x-2 items-center justify-center">
                                <div x-data="{selectedType: new URLSearchParams(window.location.search).get('type') || 'Schedule 1',
                                        filterTransactions() {const url = new URL(window.location.href);
                                            url.searchParams.set('type', this.selectedType);
                                            window.location.href = url.toString();
                                        }
                                    }" class="w-full">
                                    <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-8 border-neutral-300" role="tablist" aria-label="tab options">
                                        <a href="{{ route('with_holding.1604C_schedule1', ['id' => $with_holding->id]) }}">
                                        <button 
                                            @click="selectedType = 'Schedule 1'; filterTransactions()" 
                                            :aria-selected="selectedType === 'Schedule 1'"
                                            :tabindex="selectedType === 'Schedule 1' ? '0' : '-1'" 
                                            :class="selectedType === 'Schedule 1' ? 'font-bold text-blue-900 ' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                            class="h-min py-2 text-base relative" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="tabpanelsales">
                                            <span class="block">Schedule 1</span>
                                            <span 
                                                :class="selectedType === 'Schedule 1' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                            </span>
                                        </button>
                                        </a>
                                        <a href="{{ route('with_holding.1604C_schedule2', ['id' => $with_holding->id]) }}">
                                            <button 
                                                @click="selectedType = 'Schedule 2'; filterTransactions()" 
                                                :aria-selected="selectedType === 'Schedule 2'"
                                                :tabindex="selectedType === 'Schedule 2' ? '0' : '-1'" 
                                                :class="selectedType === 'Schedule 2' ? 'font-bold text-blue-900 ' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                                class="h-min py-2 text-base relative" 
                                                type="button" 
                                                role="tab" 
                                                aria-controls="tabpanelsales">
                                                <span class="block">Schedule 2</span>
                                                <span 
                                                    :class="selectedType === 'Schedule 2' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                                </span>
                                            </button>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
    
                        <hr>

                        <!-- Table -->
                        <div class="mb-12 mt-6 mx-12 overflow-hidden max-w-full border-neutral-300">
                            <div class="text-sm font-bold text-neutral-700 mb-4"><p>Schedule 1 - Alphalist of Employees (Above Minimum Wage Earners)</p></div>
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left text-sm text-neutral-600" id="tableid">
                                    <thead class="bg-neutral-100 text-sm text-neutral-700">
                                        <tr>
                                            <th class="px-4 py-4">Employee</th>
                                            <th class="px-4 py-4">Gross Compensation</th>
                                            <th class="px-4 py-4">Total Non-Taxable Compensation</th>
                                            <th class="px-4 py-4">Taxable Compensation</th>
                                            <th class="px-4 py-4">Tax Due</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                        @forelse ($aggregatedData as $data)
                                            <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                                <td class="px-4 py-2">
                                                    {{ $data['employee']->first_name ?? 'N/A' }} {{ $data['employee']->last_name ?? '' }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    {{ number_format($data['gross_compensation'], 2) }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    {{ number_format($data['non_taxable_compensation'], 2) }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    {{ number_format($data['taxable_compensation'], 2) }}
                                                </td>
                                                <td class="px-4 py-2">
                                                    {{ number_format($data['tax_due'], 2) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center p-4">
                                                    <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                    <h1 class="font-bold text-lg mt-2">No data available for above minimum wage earners in the selected year</h1>
                                                    {{-- <p class="text-sm text-neutral-500 mt-2">Start generating with the + button <br>at the top.</p> --}}
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination -->
                            @if ($paginatedSources->hasPages())
                                <div class="mt-4">
                                    {{ $paginatedSources->links('vendor.pagination.custom') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('search', event => {
            window.location.href = `?search=${event.detail.search}`;
        });

        // Buttons
        // FOR SORT BUTTON
        document.getElementById('sortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            const dropdownArrow = this.querySelector('svg:nth-child(3)');
            dropdown.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
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
            table.innerHTML = '';
            sortedRows.forEach(row => table.appendChild(row));
        }
        document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                document.getElementById('selectedOption').textContent = this.textContent; // Update selected option text
                sortItems(criteria);
            });
        });
        window.addEventListener('click', (event) => {
            if (!sortButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
        
        // FOR BUTTON OF SHOW ENTRIES
        document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownDots');
            dropdown.classList.toggle('hidden');
        });
        // FOR SHOWING/SETTING ENTRIES
        function setEntries(entries) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('with_holding.1604C_schedule1', ['id' => $with_holding->id]) }}";
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
</x-app-layout>
