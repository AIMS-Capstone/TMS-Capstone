<x-organization-layout>
    <div class="py-8 bg-white">
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
                <!-- Header -->
                <div class="overflow-x-auto pt-6 px-10">
                    <p class="font-bold mt-4 text-3xl taxuri-color">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-8 h-8 justify-center" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2m4.2 14.2L11 13V7h1.5v5.2l4.5 2.7z"/></svg>
                        <span> System Audit Log</span>
                    </p>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center">            
                            <p class="text-zinc-700 font-normal text-sm">The System Audit Log enables administrators to monitor and review team activities, <br> providing an audit trail to ensure accountability and transparency.</p>
                        </div>
                        <form method="GET" action="{{ route('audit_log.index') }}" class="flex items-center">
                        <div class="text-sm text-gray-600 w-full">
                            <span id="activity-log" class="bg-zinc-100 text-zinc-800 text-xs px-4 py-1.5 rounded-full">Activity Log for</span>
                            <!-- Organization Filter -->
                            <select name="organization_id" id="organization-select"
                                class="block w-full py-2 mt-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                onchange="this.form.submit()">
                                <option value="">All Organizations</option>
                                @foreach($organizations as $organization)
                                    <option value="{{ $organization->id }}" 
                                        {{ request('organization_id') == $organization->id ? 'selected' : '' }}>
                                        <span class="text-blue-900">{{ $organization->registration_name }}</span>, {{ $organization->tin }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div> 
                </div>

                <div class="flex flex-col md:flex-row px-10 justify-between">
                    <div class="w-full mt-8 ml-0 h-auto border border-zinc-300 rounded-lg p-4 bg-white">
                        <!-- Filter/Search Section -->
                        <div class="flex flex-row items-center">
                            <div class="relative w-80 p-5">
                                <form x-target="audit_table" action="/audit_log/index" role="search" aria-label="Table" autocomplete="off">
                                    <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-[7px] text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-900 focus:border-blue-900" 
                                        aria-label="Search Term" 
                                        placeholder="Search..." 
                                        @input.debounce="$el.form.requestSubmit()" 
                                        @search="$el.form.requestSubmit()"
                                    >
                                </form>
                                <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-zinc-400"></i>
                            </div>

                            <!-- General Filter -->
                            {{-- <select
                                name="filter"
                                class="block py-2 mt-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                >
                                <option value="">All</option>
                                <option value="user" {{ request('filter') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="page" {{ request('filter') == 'page' ? 'selected' : '' }}>Page</option>
                                <option value="activity" {{ request('filter') == 'activity' ? 'selected' : '' }}>Activity</option>
                                <option value="ip" {{ request('filter') == 'ip' ? 'selected' : '' }}>IP</option>
                                <option value="browser" {{ request('filter') == 'browser' ? 'selected' : '' }}>Browser</option>
                            </select> --}}
                            {{-- <div class="relative inline-block text-left sm:w-auto w-full">
                                <button id="filterButton" class="flex items-center text-zinc-600 hover:text-zinc-800 w-full hover:shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="none" stroke="#696969" stroke-width="2" d="M18 4H6c-1.105 0-2.026.91-1.753 1.98a8.02 8.02 0 0 0 4.298 5.238c.823.394 1.455 1.168 1.455 2.08v6.084a1 1 0 0 0 1.447.894l2-1a1 1 0 0 0 .553-.894v-5.084c0-.912.632-1.686 1.454-2.08a8.02 8.02 0 0 0 4.3-5.238C20.025 4.91 19.103 4 18 4z"/>
                                    </svg>
                                    <span id="selectedFilter" class="font-normal text-sm text-zinc-600 truncate">Filter</span>
                                    <svg id="dropdownArrow" class="w-2.5 h-2.5 ms-2 transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m1 1 4 4 4-4"/></svg>
                                </button>
                            
                                <div id="dropdownFilter" class="absolute mt-2 w-[340px] rounded-lg shadow-lg bg-white hidden z-40">
                                    <div class="py-2 px-2">
                                        <span class="block px-4 py-2 text-xs font-bold text-zinc-700">Filter</span>
                                        <span class="block px-4 py-1 text-zinc-700 font-bold text-xs">Timeframe</span>
                                        <div class="px-4 py-2 text-xs colspan-2 flex justify-between items-center space-x-4">
                                            <div class="flex flex-col w-full">
                                                <label for="fromDate" class="text-xs text-zinc-700 font-semibold mb-1">Start Date</label>
                                                <input id="fromDate" type="date" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" placeholder="Start Date"/>
                                            </div>
                                            <p class="text-xs text-zinc-700 font-semibold">to</p>
                                            <div class="flex flex-col w-full">
                                                <label for="toDate" class="text-xs text-zinc-700 font-semibold mb-1">End Date</label>
                                                <input id="toDate" type="date" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" placeholder="End Date"/>
                                            </div>
                                        </div>
                                        <span class="block px-4 py-1 text-zinc-700 font-bold text-xs">Page</span>
                                        <div id="statusFilterContainer" class="block px-4 py-2 text-xs">
                                            <label class="flex items-center space-x-2 py-1">
                                                <input type="checkbox" value="Organization" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                                <span>Organization</span>
                                            </label>
                                            <label class="flex items-center space-x-2 py-1">
                                                <input type="checkbox" value="Transactions" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                                <span>Transactions</span>
                                            </label>
                                            <label class="flex items-center space-x-2 py-1">
                                                <input type="checkbox" value="Contacts" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                                <span>Contacts</span>
                                            </label>
                                            <label class="flex items-center space-x-2 py-1">
                                                <input type="checkbox" value="Employees" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                                <span>Employees</span>
                                            </label>
                                            <label class="flex items-center space-x-2 py-1">
                                                <input type="checkbox" value="Books of Account" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                                <span>Books of Account</span>
                                            </label>
                                            <label class="flex items-center space-x-2 py-1">
                                                <input type="checkbox" value="Chart of Accounts" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                                <span>Chart of Accounts</span>
                                            </label>
                                            <label class="flex items-center space-x-2 py-1">
                                                <input type="checkbox" value="Tax Return" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                                <span>Tax Return</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-4 px-4 py-1.5 mb-1.5">
                                        <button id="applyFiltersButton" class="flex items-center bg-white border border-gray-300 hover:border-green-500 hover:bg-green-100 hover:text-green-500 transition rounded-md px-3 py-1.5 whitespace-nowrap group">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 fill-current group-hover:fill-green-500 hover:border-green-500 hover:text-green-500 transition" viewBox="0 0 32 32">
                                                <path fill="currentColor" d="M16 3C8.832 3 3 8.832 3 16s5.832 13 13 13s13-5.832 13-13S23.168 3 16 3m0 2c6.087 0 11 4.913 11 11s-4.913 11-11 11S5 22.087 5 16S9.913 5 16 5m-1 5v5h-5v2h5v5h2v-5h5v-2h-5v-5z"/>
                                            </svg>
                                            <span class="text-zinc-700 transition group-hover:text-green-500 text-xs">Apply Filter</span>
                                        </button>
                                        <button id="clearFiltersButton" class="text-xs text-zinc-600 hover:text-zinc-900 whitespace-nowrap">Clear all filters</button>
                                    </div>
                                </div>
                            </div> --}}
                            
                            </form>

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

                        <!-- Table -->
                        <div class="my-4 overflow-y-auto h-auto">
                            <table class="w-full items-start text-left text-sm text-neutral-600 p-4" id="audit_table">
                                <thead class="bg-neutral-100 text-sm text-neutral-700">
                                    <tr>
                                        <th class="py-3 px-4">Timestamp</th>
                                        <th class="py-3 px-4">User</th>
                                        <th class="py-3 px-4">Page</th>
                                        <th class="py-3 px-4">Activity</th>
                                        <th class="py-3 px-4">Details</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                    @forelse($activities as $activity)
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="py-3 px-4">{{ $activity->created_at->format('Y-m-d H:i:s') }}</td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center space-x-2">
                                                    <div>
                                                        <div class="font-medium text-gray-900">
                                                            {{ $activity->causer->name ?? 'Taxuri' }}
                                                        </div>
                                                        @if($activity->causer->role)
                                                            <div class="text-sm text-gray-500">
                                                                {{ $activity->causer->role }}
                                                            </div>
                                                        @else
                                                            <div class="text-sm text-gray-400 italic">
                                                                No role assigned
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2">
                                                <span class="inline-block bg-gray-100 text-gray-700 text-xs font-medium rounded-full px-3 py-1">
                                                    {{ $activity->log_name }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">
                                                {{ $activity->description }}     
                                                {{-- sample update showing --}}
                                                @if(isset($activity->properties['changes']) && is_array($activity->properties['changes']))
                                                    <div class="mt-2 text-sm text-gray-700">
                                                        <strong>Changes:</strong>
                                                        <ul class="list-disc list-inside">
                                                            @foreach($activity->properties['changes'] as $key => $change)
                                                                <li>
                                                                    <strong>{{ ucfirst($key) }}:</strong>
                                                                    <span class="text-red-500">{{ $change['old'] ?? 'N/A' }}</span> →
                                                                    <span class="text-green-500">{{ $change['new'] ?? 'N/A' }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                                {{-- for employee multi deleted --}}
                                                @if(isset($activity->properties['deleted_employees']) && is_array($activity->properties['deleted_employees']))
                                                    <div class="mt-2">
                                                        <strong>Deleted Employees:</strong>
                                                        <ul class="list-disc list-inside">
                                                            @foreach($activity->properties['deleted_employees'] as $employee)
                                                                <li>
                                                                    {{ $employee['name'] }} 
                                                                    <span class="text-xs">TIN: {{ $employee['tin'] }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-sm text-gray-500">
                                                IP: {{ $activity->properties['ip'] ?? 'N/A' }},<br>
                                                Browser: {{ $activity->properties['browser'] ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center p-4">
                                                <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                <h1 class="font-bold text-lg mt-2">No Activities Logged</h1>
                                                <p class="text-sm text-neutral-500 mt-2">No activities has been logged yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class=" mt-4">
                            <div>
                                {{ $activities->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ACTIVITY LOG FOR
        document.addEventListener('DOMContentLoaded', () => {
            const selectElement = document.getElementById('organization-select');
            const activityLogSpan = document.getElementById('activity-log');

            function handleStyleUpdate() {
                // Reset all options' text color
                Array.from(selectElement.options).forEach(option => {
                    option.style.color = ''; // Reset to default
                });

                if (selectElement.value) {
                    // Change "Activity Log for" text to green
                    activityLogSpan.classList.remove('text-zinc-800');
                    activityLogSpan.classList.add('text-green-600');
                    activityLogSpan.classList.add('bg-green-100');

                    // Highlight selected option text as blue
                    const selectedOption = selectElement.options[selectElement.selectedIndex];
                    selectedOption.style.color = '#1e3a8a'; // Blue (Tailwind: text-blue-900)
                } else {
                    // Revert "Activity Log for" text to default
                    activityLogSpan.classList.remove('text-green-600');
                    activityLogSpan.classList.add('text-zinc-800');
                }
            }

            // Run once on page load to set initial styles
            handleStyleUpdate();

            // Attach listener for style updates without altering the form submission
            selectElement.addEventListener('change', handleStyleUpdate);
        });


         //FILTER BUTTON
    const filterButton = document.getElementById('filterButton');
    const dropdownFilter = document.getElementById('dropdownFilter');
    const applyFiltersButton = document.getElementById('applyFiltersButton');
    const clearFiltersButton = document.getElementById('clearFiltersButton');
    const selectedFilter = document.getElementById('selectedFilter');
    const tableRows = document.querySelectorAll('tbody tr');
    const dropdownArrow = document.getElementById('dropdownArrow');

    filterButton.addEventListener('click', () => {
        dropdownArrow.classList.toggle('rotate-180');
        dropdownFilter.classList.toggle('hidden');
    });
    function getSelectedFilters() {
        const filters = {};
        document.querySelectorAll('.filter-checkbox:checked').forEach((checkbox) => {
            const category = checkbox.dataset.category;
            if (!filters[category]) filters[category] = [];
            filters[category].push(checkbox.value);
        });
        return filters;
    }
    // Attach event listeners for the date inputs
    document.getElementById('fromDate').addEventListener('input', updateApplyButtonState);
    document.getElementById('toDate').addEventListener('input', updateApplyButtonState);

    function applyFilters() {
    const selectedFilters = getSelectedFilters();
    const fromDate = document.getElementById('fromDate').value;
    const toDate = document.getElementById('toDate').value;

    tableRows.forEach((row) => {
        let isVisible = true;

        // Filter by date range
        const dateCell = row.cells[0]?.textContent.trim(); // Assuming date is in the first column
        const rowDate = dateCell ? new Date(dateCell) : null;

        if (fromDate || toDate) {
            const from = fromDate ? new Date(fromDate) : null;
            const to = toDate ? new Date(toDate) : null;

            if ((from && rowDate < from) || (to && rowDate > to)) {
                isVisible = false;
            }
        }

        // Filter by "Page" column
        const pageCellText = row.cells[2]?.textContent.trim(); // Correct column index for "Page"
        if (selectedFilters.Page && selectedFilters.Page.length > 0) {
            if (!selectedFilters.Page.includes(pageCellText)) {
                isVisible = false;
            }
        }

        // Apply visibility
        row.style.display = isVisible ? '' : 'none';
    });

    dropdownFilter.classList.add('hidden');
    selectedFilter.textContent = 'Filter';
    updateApplyButtonState();
}

    function getSelectedFilters() {
        const filters = {};
        document.querySelectorAll('.filter-checkbox:checked').forEach((checkbox) => {
            const category = checkbox.dataset.category; // e.g., "Page"
            if (!filters[category]) filters[category] = [];
            filters[category].push(checkbox.value);
        });
        return filters;
    }
    function updateApplyButtonState() {
        const hasCheckboxSelection = document.querySelectorAll('.filter-checkbox:checked').length > 0;
        const hasDateSelection = document.getElementById('fromDate').value || document.getElementById('toDate').value;
        const isFilterActive = hasCheckboxSelection || hasDateSelection;
        applyFiltersButton.disabled = !isFilterActive;
        if (isFilterActive) {
            applyFiltersButton.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            applyFiltersButton.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }
    document.querySelectorAll('.filter-checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', updateApplyButtonState);
    });

    function clearFilters() {
        document.querySelectorAll('.filter-checkbox').forEach((checkbox) => (checkbox.checked = false));
        document.getElementById('fromDate').value = '';
        document.getElementById('toDate').value = '';
        tableRows.forEach((row) => (row.style.display = ''));
        dropdownFilter.classList.add('hidden');
        selectedFilter.textContent = 'Filter';
        updateApplyButtonState();
    }
    applyFiltersButton.addEventListener('click', applyFilters);
    clearFiltersButton.addEventListener('click', clearFilters);

    window.addEventListener('click', (event) => {
        if (!filterButton.contains(event.target) && !dropdownFilter.contains(event.target)) {
            dropdownFilter.classList.add('hidden');
        }
    });
    // Initial setup: disable the "Apply Filter" button
    applyFiltersButton.disabled = true;
    applyFiltersButton.classList.add('opacity-50', 'cursor-not-allowed'); // Optional: Add styles for disabled state
    function updateApplyButtonState() {
        const hasSelection = document.querySelectorAll('.filter-checkbox:checked').length > 0;
        applyFiltersButton.disabled = !hasSelection;
        if (hasSelection) {
            applyFiltersButton.classList.remove('opacity-50', 'cursor-not-allowed'); // Optional: Remove disabled styles
        } else {
            applyFiltersButton.classList.add('opacity-50', 'cursor-not-allowed'); // Optional: Add disabled styles
        }
    }
    document.querySelectorAll('.filter-checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', updateApplyButtonState);
    });
    clearFiltersButton.addEventListener('click', () => {
        document.querySelectorAll('.filter-checkbox').forEach((checkbox) => (checkbox.checked = false));
        tableRows.forEach((row) => (row.style.display = ''));
        dropdownFilter.classList.add('hidden');
        selectedFilter.textContent = 'Filter';
        updateApplyButtonState();
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
            form.action = "{{ route('audit_log.index') }}";
            // Create a hidden input for perPage
            const perPageInput = document.createElement('input');
            perPageInput.type = 'hidden';
            perPageInput.name = 'perPage';
            perPageInput.value = entries;
            // Add search input value if needed
            const searchInput = document.createElement('input');
            searchInput.type = 'hidden';
            searchInput.name = 'user_search';
            searchInput.value = "{{ request('search') }}";
            // Append inputs to form
            form.appendChild(perPageInput);
            form.appendChild(searchInput);
            // Append the form to the body and submit
            document.body.appendChild(form);
            form.submit();
        }

        document.addEventListener('search', event => {
            window.location.href = `?search=${event.detail.search}`;
        });
    </script>
</x-organization-layout>
