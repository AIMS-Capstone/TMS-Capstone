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
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">Percentage Tax Return</li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ route('percentage_return') }}" 
                                        class="ms-1 text-sm font-medium {{ Request::routeIs('percentage_return') ? 'font-extrabold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                        2551M/Q
                                    </a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <hr>

                <!-- Third Header -->
                <div x-data="{
                    showCheckboxes: false, 
                    checkAll: false, 
                    selectedRows: [],
                    showDeleteCancelButtons: false,

                    // Toggle a single row
                    toggleCheckbox(id) {
                        if (this.selectedRows.includes(id)) {
                            this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                        } else {
                            this.selectedRows.push(id);
                        }
                    },

                    // Handle deletion
                    deleteRows() {
                        if (this.selectedRows.length === 0) {
                            alert('No rows selected for deletion.');
                            return;
                        }

                        if (confirm('Are you sure you want to archive the selected row/s?')) {
                            fetch('/coa/deactivate', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ ids: this.selectedRows })
                            })
                            .then(response => {
                                if (response.ok) {
                                    location.reload();  
                                } else {
                                    alert('Error deleting rows.');
                                }
                            });
                        }
                    },

                    // Cancel selection
                    cancelSelection() {
                        this.selectedRows = []; 
                        this.checkAll = false;
                        this.showCheckboxes = false; 
                        this.showDeleteCancelButtons = false;
                    },

                    get selectedCount() {
                        return this.selectedRows.length;
                    }
                    }" class="container mx-auto pt-2 overflow-hidden">
                    
                    <!-- Fourth Header -->
                    <div class="container mx-auto">
                        <div class="flex flex-row space-x-2 items-center justify-between">
                            <!-- Search row -->
                            <div class="flex flex-row space-x-2 items-center ps-8">
                                <div class="relative w-80 p-4">
                                    <form x-target="tableid" action="/percentage_return" role="search" aria-label="Table" autocomplete="off">
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
                                    <div class="relative inline-block text-left sm:w-auto w-full z-50">
                                        <button id="filterButton" class="flex items-center text-zinc-600 hover:text-zinc-800 w-full hover:shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                                <path fill="none" stroke="#696969" stroke-width="2" d="M18 4H6c-1.105 0-2.026.91-1.753 1.98a8.02 8.02 0 0 0 4.298 5.238c.823.394 1.455 1.168 1.455 2.08v6.084a1 1 0 0 0 1.447.894l2-1a1 1 0 0 0 .553-.894v-5.084c0-.912.632-1.686 1.454-2.08a8.02 8.02 0 0 0 4.3-5.238C20.025 4.91 19.103 4 18 4z"/>
                                            </svg>
                                            <span id="selectedFilter" class="font-normal text-sm text-zinc-600 truncate">Filter</span>
                                            <svg id="dropdownArrow" class="w-2.5 h-2.5 ms-2 transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m1 1 4 4 4-4"/></svg>
                                        </button>
                                    
                                        <div id="dropdownFilter" class="absolute mt-2 w-[340px] rounded-lg shadow-lg bg-white hidden z-50">
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
                                                <span class="block px-4 py-1 text-zinc-700 font-bold text-xs">Status</span>
                                                <div id="statusFilterContainer" class="block px-4 py-2 text-xs">
                                                    <label class="flex items-center space-x-2 py-1">
                                                        <input type="checkbox" value="Filed" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                                        <span>Filed</span>
                                                    </label>
                                                    <label class="flex items-center space-x-2 py-1">
                                                        <input type="checkbox" value="Unfiled" class="filter-checkbox rounded-full peer checked:bg-blue-900 checked:ring-2 checked:ring-blue-900 focus:ring-blue-900" data-category="Status" />
                                                        <span>Unfiled</span>
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
                                    </div>

                                    <div class="h-6 border-l border-zinc-300"></div>

                                    <!-- Sort by dropdown -->
                                    <div class="relative inline-block text-left">
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
                            </div>
                            <!-- End row -->
                            <div class="flex space-x-4 items-center pr-10 ml-auto">
                                <button 
                                    type="button" 
                                    @click="showCheckboxes = !showCheckboxes;    showDeleteCancelButtons: false, showDeleteCancelButtons = !showDeleteCancelButtons; $el.disabled = true;" 
                                    :disabled="selectedRows.length === 1"
                                    class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-red-500 hover:text-red-500 hover:bg-red-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                    >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                    <span class="text-zinc-600 transition group-hover:text-red-500">Delete</span>
                                </button>
                                
                                <button 
                                    x-data 
                                    x-on:click="$dispatch('open-generate-modal')" 
                                    class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                    >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-green-500" viewBox="0 0 256 256"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"><circle cx="128" cy="128" r="112"/><path d="M 79.999992,128 H 176.0001"/><path d="m 128.00004,79.99995 v 96.0001"/></g></svg>
                                    <span class="text-zinc-600 transition group-hover:text-green-500">Generate</span>
                                </button>

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
                        <div x-data="{
                            selectedTab: '2551M',
                            init() {
                                this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || '2551M';
                            }
                            }" x-init="init" class="w-full p-5">
                            <div @keydown.right.prevent="$focus.wrap().next()" 
                                @keydown.left.prevent="$focus.wrap().previous()" 
                                class="flex flex-row text-center overflow-x-auto ps-8" 
                                role="tablist" 
                                aria-label="tab options">
                                
                                <!-- Tab 1: 2550M/Q -->
                                <button @click="selectedTab = '2551M'; $dispatch('filter', { type: '2551M' })"
                                    :aria-selected="selectedTab === '2551M'" 
                                    :tabindex="selectedTab === '2551M' ? '0' : '-1'" 
                                    :class="selectedTab === '2551M' 
                                        ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                        : 'text-zinc-600 font-medium hover:text-blue-900'"
                                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="tabpanelAll">
                                    2551M/Q
                                </button>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="mb-12 mt-1 mx-12 overflow-hidden max-w-full border-neutral-300">
                            <div class="overflow-x-auto">
                                <table class="w-full items-start text-left text-sm text-neutral-600 p-4" id="tableid">
                                    <thead class="bg-neutral-100 text-sm text-neutral-700">
                                        <tr>
                                            <th scope="col" class="p-4">
                                                <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                    <div class="relative flex items-center">
                                                        <input type="checkbox" x-model="checkAll" id="checkAll" @change="toggleAll()" class="relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 dark:border-neutral-700 dark:bg-neutral-900" />
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                        </svg>
                                                    </div>
                                                </label>
                                            </th>
                                            <th scope="col" class="text-left py-4 px-4">Title</th>
                                            <th scope="col" class="text-left py-4 px-4">Period</th>
                                            <th scope="col" class="text-left py-4 px-4">Created By</th>
                                            <th scope="col" class="text-left py-4 px-4">Status</th>
                                            <th scope="col" class="text-left py-4 px-4">Date Created</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                        @if ($taxReturns->isEmpty())
                                            <tr>
                                                <td colspan="6" class="text-center p-4">
                                                    <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                    <h1 class="font-bold text-lg mt-2">No Generated Returns yet</h1>
                                                    <p class="text-sm text-neutral-500 mt-2">Start generating with the + button <br>at the top.</p>
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($taxReturns as $taxReturn)
                                                <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                                    <td class="p-4">
                                                        <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                            <div class="relative flex items-center">
                                                                <input type="checkbox" @click="toggleCheckbox('{{ $taxReturn->id }}')" :checked="selectedRows.includes('{{ $taxReturn->id }}')" id="taxReturn{{ $taxReturn->id }}" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition" />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                            </div>
                                                        </label>
                                                    </td>
                                                    <td class="text-left py-3 px-4 font-bold underline hover:font-bold hover:underline hover:text-blue-500">
                                                        <a href="{{ route('tax-returns.percentage-summary', $taxReturn->id) }}">
                                                            {{ $taxReturn->title }}
                                                        </a>
                                                    </td>
                                                    <td class="text-left py-3 px-4">
                                                        @php
                                                            $monthName = '';
                                                            $monthValue = $taxReturn->month;
                                                    
                                                            if (is_numeric($monthValue)) {
                                                                $monthName = DateTime::createFromFormat('!m', $monthValue)->format('F');
                                                            } elseif (str_contains($monthValue, 'Q')) {
                                                                switch ($monthValue) {
                                                                    case 'Q1':
                                                                        $monthName = 'January - March (Q1)';
                                                                        break;
                                                                    case 'Q2':
                                                                        $monthName = 'April - June (Q2)';
                                                                        break;
                                                                    case 'Q3':
                                                                        $monthName = 'July - September (Q3)';
                                                                        break;
                                                                    case 'Q4':
                                                                        $monthName = 'October - December (Q4)';
                                                                        break;
                                                                }
                                                            }
                                                        @endphp
                                                        {{ $monthName }} {{ $taxReturn->year }}
                                                    </td>
                                                    <td class="text-left py-3 px-4">{{ $taxReturn->user ? $taxReturn->user->first_name . ' ' . $taxReturn->user->last_name : 'N/A' }}</td>
                                                    <td class="text-left py-3 px-4"><span class="bg-zinc-100 text-zinc-800 text-xs px-4 py-2 rounded-full">{{ $taxReturn->status }}</span></td>
                                                    <td class="text-left py-3 px-4">{{ \Carbon\Carbon::parse($taxReturn->created_at)->format('F d, Y g:i A') }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                   <!-- Delete Confirmation Modal -->
                   <div x-show="showConfirmDeleteModal" x-cloak 
                        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50"
                        x-effect="document.body.classList.toggle('overflow-hidden', showConfirmDeleteModal)">
                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full overflow-auto">
                            <div class="flex flex-col items-center">
                                <!-- Icon -->
                                <div class="mb-4">
                                    <i class="fas fa-exclamation-triangle text-red-700 text-8xl"></i>
                                </div>

                                <!-- Title -->
                                <h2 class="text-2xl font-extrabold text-zinc-700 mb-2">Delete Item(s)</h2>

                                <!-- Description -->
                                <p class="text-sm text-zinc-700 text-center">
                                    You're going to Delete the selected item(s) in the Value Added Tax Return table. Are you sure?
                                </p>

                                <!-- Actions -->
                                <div class="flex justify-center space-x-8 mt-6 w-full">
                                    <button 
                                        @click="showConfirmDeleteModal = false; showDeleteCancelButtons = true;" 
                                        class="px-4 py-2 rounded-lg text-sm text-zinc-700 font-bold transition"
                                        > 
                                        Cancel
                                    </button>
                                    <button 
                                        @click="deleteRows(); showConfirmDeleteModal = false;" 
                                        class="px-5 py-2 bg-red-700 hover:bg-red-800 text-white rounded-lg text-sm font-medium transition"
                                        > 
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                        <button @click="showConfirmDeleteModal" = true; showDeleteCancelButtons = true;" :disabled="selectedRows.length === 0"
                            class="border px-3 py-2 rounded-lg text-sm text-red-600 border-red-600 bg-red-100 hover:bg-red-200 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                            <span class="text-red-600 transition group-hover:text-red-600">Delete Selected</span><span class="transition group-hover:text-red-500" x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                        </button>
                        <button @click="cancelSelection" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"> 
                            Cancel
                        </button>
                    </div>
                    {{-- Pagination --}}
                    {{-- <div class="mx-12 mb-4">
                        {{ $taxReturns->appends(['type' => $type])->links('vendor.pagination.custom') }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <div x-data="{ open: false, selectedTab: '2550M', selectedType: '', month: '' }" @open-generate-modal.window="open = true" x-cloak>
        <div x-show="open" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" 
            class="fixed inset-0 bg-gray-200 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-auto h-auto z-10 overflow-hidden">
                <div class="relative p-3 bg-blue-900 border-opacity-80 w-full">
                    <h1 class="text-lg font-bold text-white text-center">Quarterly Percentage Tax Return</h2>
                    <button @click="open = false" class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 8L16 16M8 16L16 8" stroke="#1e3a8a" class="transition duration-200 hover:stroke-gray-600"/>
                        </svg>
                    </button>
                </div>
                
                <div class="p-10">
                    <form method="POST" action="/percentage_return">
                        @csrf
                        <div class="grid grid-cols-2 gap-6 mb-5">
                            <div class="mb-4">
                                <label for="year" class="block text-sm font-semibold text-gray-700">Year</label>
                                <select id="year" name="year" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                    <option value="">Select Year</option>
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 100;
                                    @endphp
                                    @for ($year = $startYear; $year <= $currentYear; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
            
                            <div class="mb-4">
                                <label for="month" class="block text-sm font-semibold text-gray-700">Month</label>
                                <select id="month" name="month" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                    x-model="month" 
                                    @change="selectedType = month.includes('Q') ? '2551Q' : '2551M'" 
                                    required>
                                    <option value="">Select Month</option>
                                    <!-- Monthly options -->
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                    <!-- Quarterly options -->
                                    <option value="Q1">January - March (Q1)</option>
                                    <option value="Q2">April - June (Q2)</option>
                                    <option value="Q3">July - September (Q3)</option>
                                    <option value="Q4">October - December (Q4)</option>
                                </select>
                            </div>
                        </div>
        
                        <!-- Hidden inputs -->
                        <input type="hidden" name="type" x-model="selectedType"> <!-- Dynamically set type -->
                        <input type="hidden" name="organization_id" value="{{ $organizationId }}">
                        <div class="flex justify-end mt-4">
                            <button type="button" @click="open = false" class="mr-2 hover:text-zinc-900 text-zinc-600 text-sm font-semibold py-2 px-4">Cancel</button>
                            <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white font-semibold text-sm py-1 px-6 rounded-lg">Generate</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script -->
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
            // Get all checkbox elements inside the table body
            const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

            // Clear or populate the selectedRows array based on checkAll state
            if (this.checkAll) {
                // Check all checkboxes and add their IDs to selectedRows
                this.selectedRows = Array.from(rowCheckboxes).map(checkbox => checkbox.dataset.id);
            } else {
                // Uncheck all checkboxes and clear selectedRows
                this.selectedRows = [];
            }

            // Update the DOM to reflect the state
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checkAll;
            });
        }

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
            const filters = getSelectedFilters();
            const fromDate = document.getElementById('fromDate').value;
            const toDate = document.getElementById('toDate').value;
            tableRows.forEach((row) => {
                let isVisible = true;
                // Period filtering
                const periodCell = row.cells[2]?.textContent.trim(); // Adjust to match Period column index
                if (fromDate || toDate) {
                    const periodDateMatch = periodCell.match(/(\w+)\s+(\d+)/); // Extract month and year
                    if (periodDateMatch) {
                        const month = periodDateMatch[1];
                        const year = periodDateMatch[2];
                        const rowDate = new Date(`${month} 1, ${year}`);
                        const from = fromDate ? new Date(fromDate) : null;
                        const to = toDate ? new Date(toDate) : null;

                        if ((from && rowDate < from) || (to && rowDate > to)) {
                            isVisible = false;
                        }
                    } else {
                        isVisible = false; // Hide rows with invalid Period data
                    }
                }
                // Status filtering
                const statusCell = row.cells[4]?.textContent.trim(); // Adjust to match Status column index
                if (filters.Status?.length && !filters.Status.includes(statusCell)) {
                    isVisible = false;
                }
                row.style.display = isVisible ? '' : 'none';
            });

            dropdownFilter.classList.add('hidden');
            selectedFilter.textContent = 'Filter';
            updateApplyButtonState();
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

        // FOR SORT BUTTON
        document.getElementById('sortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            const dropdownArrow = this.querySelector('svg:nth-child(3)');
            dropdown.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
        });

        // FOR SORT BY
        function sortItems(criteria) {
            const table = document.querySelector('#tableid tbody');
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
            form.action = "{{ route('percentage_return') }}";
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