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
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search"
                                class="w-full pl-10 pr-4 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-900 focus:border-blue-900" 
                                aria-label="Search Term" 
                                placeholder="Search..." 
                                @input.debounce="$el.form.requestSubmit()" 
                                @search="$el.form.requestSubmit()"
                                />
                                <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-zinc-400"></i>
                            </div>

                            <!-- General Filter -->
                            <select
                                name="filter"
                                class="border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2"
                            >
                                <option value="">All</option>
                                <option value="user" {{ request('filter') == 'user' ? 'selected' : '' }}>User</option>
                                <option value="page" {{ request('filter') == 'page' ? 'selected' : '' }}>Page</option>
                                <option value="activity" {{ request('filter') == 'activity' ? 'selected' : '' }}>Activity</option>
                                <option value="ip" {{ request('filter') == 'ip' ? 'selected' : '' }}>IP</option>
                                <option value="browser" {{ request('filter') == 'browser' ? 'selected' : '' }}>Browser</option>
                            </select>
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
                            <table class="w-full items-start text-left text-sm text-neutral-600 p-4">
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
    </script>
</x-organization-layout>
