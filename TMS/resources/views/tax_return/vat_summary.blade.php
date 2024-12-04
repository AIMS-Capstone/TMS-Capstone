<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                {{-- Breadcrumbs --}}
                <div class="px-10 py-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">
                            {{-- <a href="{{ route('vat_return') }}" class="inline-flex items-center text-sm font-normal text-zinc-500">
                                
                            </a> --}}
                            Value Added Tax Return
                            </li>
                            <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('vat_return') }}" 
                                    class="ms-1 text-sm font-medium {{ Request::routeIs('vat_return') ? 'font-bold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                    2550M/Q
                                </a>
                            </div>
                            </li>
                            <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="" class="ms-1 text-sm font-bold text-blue-900 md:ms-2">Summary</a>
                            </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <hr>

                <div class="container mx-auto">
                    <div class="flex flex-row space-x-2 items-center justify-between">
                        <!-- Search row -->
                        <div class="flex flex-row space-x-2 items-center ps-6">
                            <div class="relative w-80 p-4">
                                <form x-target="tableid" action="/transactions" role="search" aria-label="Table" autocomplete="off">
                                    <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900" 
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
                        </div>
                        <!-- End row -->
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

                    <div class="px-8 ps-10">
                        <!-- Navigation Tabs -->
                        <nav class="flex space-x-4 my-4">
                            <a href="{{ route('tax_return.slsp_data', $taxReturn->id) }}" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap 
                                {{ request()->routeIs('tax_return.slsp_data') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }}">
                                 SLSP Data
                             </a>
                             
                             <a href="{{ route('tax_return.summary', $taxReturn->id) }}" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap 
                                {{ request()->routeIs('tax_return.summary') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }}">
                                 Summary
                             </a>
                             
                             <a href="{{ route('tax_return.report', $taxReturn->id) }}" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap 
                                {{ request()->routeIs('tax_return.report') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }}">
                                 Report
                             </a>
                             
                             <a href="{{ route('notes_activity') }}" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap 
                                {{ request()->routeIs('notes_activity') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }}">
                                 Notes & Activity
                             </a>
                        </nav>
                    </div>

                    <hr>
                    
                    <!-- Summary Table -->
                    <div class="mb-12 mt-6 mx-12 overflow-hidden max-w-full border-neutral-300">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-neutral-600" id="tableid">
                                <thead class="bg-neutral-100 text-sm text-neutral-700">
                                    <tr>
                                        <th scope="col" class="py-4 px-2">Tax Type</th>
                                        <th scope="col" class="py-4 px-2">Sales/Purchases</th>
                                        <th scope="col" class="py-4 px-2">Tax Amount</th>
                                        <th scope="col" class="py-4 px-2">Tax Rate</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                    @foreach ($paginatedSummaryData as $data)
                                        <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                            <td class="text-left py-3 px-2">{{ ucfirst($data['tax_type']) }}</td>
                                            <td class="text-left py-3 px-2">{{ number_format($data['vatable_sales'], 2) }}</td>
                                            <td class="text-left py-3 px-2">{{ number_format($data['tax_due'], 2) }}</td>
                                            <td class="text-left py-3 px-2">{{ number_format($data['tax_rate'], 2) }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                         <!-- Pagination Controls -->
                        <div class="mb-4">
                            <div>
                                {{ $paginatedSummaryData->links('vendor.pagination.custom') }}
                            </div>
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

        // FOR BUTTON OF SHOW ENTRIES
        document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownDots');
            dropdown.classList.toggle('hidden');
        });
        // FOR SHOWING/SETTING ENTRIES
        function setEntries(entries) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('transactions') }}"; //where??
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
