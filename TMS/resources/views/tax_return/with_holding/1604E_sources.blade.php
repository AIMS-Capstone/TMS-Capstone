<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Header -->
                <div class="px-10 py-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">Withholding Tax Return</li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ route('with_holding.1604E') }}" 
                                        class="ms-1 text-sm font-medium {{ Request::routeIs('with_holding.1604E') ? 'font-extrabold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                        1604E
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
                            <div class="relative w-80 p-4">
                                <form x-target="table1604E" action="/{id}/1604E_sources" role="search" aria-label="Table" autocomplete="off">
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
                            <div class="flex flex-row items-center space-x-4">
                                <!-- Sort by dropdown -->
                                <div class="relative inline-block text-left">
                                    <button id="sortButton" class="flex items-center text-zinc-600 w-full hover:shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24"><path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
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
                        </div>
                        <!-- End row -->
                        <div class="flex space-x-4 items-center pr-10 ml-auto">
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
                            
                            <!-- Tab 1: Summary -->
                            <a href="{{ route('with_holding.1604E_summary', ['id' => $withHolding->id]) }}">
                                <button @click="selectedTab = 'Summary'; $dispatch('filter', { type: 'Summary' })"
                                    :aria-selected="selectedTab === 'Summary'" 
                                    :tabindex="selectedTab === 'Summary' ? '0' : '-1'" 
                                    :class="selectedTab === 'Summary' 
                                        ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                        : 'text-zinc-600 font-medium hover:text-blue-900'"
                                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="tabpanelSummary">
                                    Summary
                                </button>
                            </a>
                            <!-- Tab 2: Remittances -->
                            <a href="{{ route('with_holding.1604E_remittances', ['id' => $withHolding->id]) }}">
                                <button @click="selectedTab = 'Remittances'" 
                                    :aria-selected="selectedTab === 'Remittances'" 
                                    :tabindex="selectedTab === 'Remittances' ? '0' : '-1'" 
                                    :class="selectedTab === 'Remittances' 
                                        ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                        : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="tabpanelRemittances">
                                    Remittances
                                </button>
                            </a>
                            <!-- Tab 3: Sources -->
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
                            <!-- Tab 4: Report -->
                            <a href="{{ route('form1604E.create', ['id' => $withHolding->id]) }}">
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

                    <div class="mb-12 mt-1 mx-12 overflow-hidden max-w-full border-neutral-300">
                        <div class="overflow-x-auto">
                            <table class="w-full items-start text-left text-sm text-neutral-600 p-4" id="table1604ERemit">
                                <thead class="bg-neutral-100 text-sm text-neutral-700">
                                    <tr>
                                        <th class="py-4 px-4">Vendor</th>
                                        <th class="py-4 px-4">ATC</th>
                                        <th class="py-4 px-4">Amount</th>
                                        <th class="py-4 px-4">Tax Rate</th>
                                        <th class="py-4 px-4">Tax Withheld</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                    @forelse($payeeSummary as $payee)
                                        <tr>
                                            <td class="py-4 px-4">
                                                {{ $payee['vendor'] }}<br>
                                                {{ $payee['tin'] }}<br>
                                                {{ $payee['address'] }}
                                            </td>
                                            <td class="py-4 px-4">{{ $payee['atc'] }}</td>
                                            <td class="py-4 px-4">{{ number_format($payee['amount'], 2) }}</td>
                                            <td class="py-4 px-4">
                                                {{ $payee['tax_rate'] !== 0 ? number_format($payee['tax_rate'], 2) : 'N/A' }}%
                                            </td>
                                            <td class="py-4 px-4">{{ number_format($payee['tax_withheld'], 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center p-4">
                                                <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                <h1 class="font-bold text-lg mt-2">No QAP records found for this year.</h1>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $qapTransactions->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // FOR SORT BUTTON
        document.getElementById('sortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            const dropdownArrow = this.querySelector('svg:nth-child(3)');
            dropdown.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
        });

        // FOR SORT BY
        function sortItems(criteria) {
            const table = document.querySelector('#table1604ERemit tbody');
            const rows = Array.from(table.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
            let sortedRows;
            if (criteria === 'recently-added') {
                // Sort by 'Date Created' column (newest first)
                sortedRows = rows.sort((a, b) => {
                    const aDate = new Date(a.cells[5].textContent.trim()); // Adjust index for "Date Created"
                    const bDate = new Date(b.cells[5].textContent.trim());
                    return bDate - aDate; // Newest first
                });
            } else if (criteria === 'ascending' || criteria === 'descending') {
                sortedRows = rows.sort((a, b) => {
                    const aText = a.cells[3].textContent.trim().toLowerCase(); // Adjust index for "Created By"
                    const bText = b.cells[3].textContent.trim().toLowerCase();

                    if (criteria === 'ascending') {
                        return aText.localeCompare(bText);
                    } else if (criteria === 'descending') {
                        return bText.localeCompare(aText);
                    }
                });
            } else {
                console.error('Invalid sorting criteria:', criteria);
                return; // Exit the function if criteria is invalid
            }
            table.innerHTML = '';
            sortedRows.forEach(row => table.appendChild(row));
        }
        // Sort dropdown click event handling
        document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                sortItems(criteria);
                document.getElementById('selectedOption').textContent = this.textContent;
                document.getElementById('dropdownMenu').classList.add('hidden');
            });
        });
        window.addEventListener('click', (event) => {
            if (!sortButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        // FOR BUTTON OF SHOW ENTRIES
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('dropdownMenuIconButton').addEventListener('click', function () {
                const dropdown = document.getElementById('dropdownDots');
                dropdown.classList.toggle('hidden');
            });
        });

        function setEntries(entries) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('with_holding.1604E_remittances', ['id' => $withHolding->id]) }}";
            const perPageInput = document.createElement('input');
            perPageInput.type = 'hidden';
            perPageInput.name = 'perPage';
            perPageInput.value = entries;
            const searchInput = document.createElement('input');
            searchInput.type = 'hidden';
            searchInput.name = 'search';
            searchInput.value = "{{ request('search') }}";
            form.appendChild(perPageInput);
            form.appendChild(searchInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</x-app-layout>
