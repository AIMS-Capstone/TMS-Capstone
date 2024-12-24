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
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">
                            Withholding Tax Return
                            </li>
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
                        console.log(this.selectedRows); // Debugging line
                    },
                                        
                    // Toggle all rows
                    toggleAll() {
                    this.checkAll = !this.caheckAll;
                    if (this.checkAll) {
                        this.selectedRows = {{ json_encode($with_holdings->pluck('id')->toArray()) }}; 
                    } else {
                        this.selectedRows = []; 
                    }
                        console.log(this.selectedRows); // Debugging line
                    },

                    // Handle deletion
                    deleteRows() {
                        if (this.selectedRows.length === 0) {
                            alert('No rows selected for deletion.');
                            return;
                        }

                        if (confirm('Are you sure you want to archive the selected row/s?')) {
                            fetch('/tax_return/with_holding/1604C/destroy', {
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
                            <div class="flex flex-row space-x-2 items-center ps-6">
                                <div class="relative w-80 p-4">
                                    <form x-target="table1604C" action="/1604C" role="search" aria-label="Table" autocomplete="off">
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
                        
                        <div x-data="{selectedTab: '1604C'}" class="w-full p-5">
                            <div @keydown.right.prevent="$focus.wrap().next()" 
                                @keydown.left.prevent="$focus.wrap().previous()" 
                                class="flex flex-row text-center overflow-x-auto ps-6" 
                                role="tablist" 
                                aria-label="tab options">
                                <!-- Tab 1: 1601C -->
                                <a href="{{ route('with_holding.0619E') }}">
                                    <button @click="selectedTab = '1601C'"
                                        :aria-selected="selectedTab === '1601C'" 
                                        :tabindex="selectedTab === '1601C' ? '0' : '-1'" 
                                        :class="selectedTab === '1601C' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'"
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanel1601C">
                                        1601C
                                    </button>
                                </a>
                                <!-- Tab 2: 0619E -->
                                <a href="{{ route('with_holding.0619E') }}">
                                    <button @click="selectedTab = '0619E'" 
                                        :aria-selected="selectedTab === '0619E'" 
                                        :tabindex="selectedTab === '0619E' ? '0' : '-1'" 
                                        :class="selectedTab === '0619E' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanel0619E">
                                        0619E
                                    </button>
                                </a>
                                <!-- Tab 3: 1601EQ -->
                                <a href="{{ route('with_holding.1601EQ') }}">
                                    <button @click="selectedTab = '1601EQ'" 
                                        :aria-selected="selectedTab === '1601EQ'" 
                                        :tabindex="selectedTab === '1601EQ' ? '0' : '-1'" 
                                        :class="selectedTab === '1601EQ' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanel1601EQ">
                                        1601EQ
                                    </button>
                                </a>
                                <!-- Tab 4: 1604C -->
                                <button @click="selectedTab = '1604C'" 
                                    :aria-selected="selectedTab === '1604C'" 
                                    :tabindex="selectedTab === '1604C' ? '0' : '-1'" 
                                    :class="selectedTab === '1604C' 
                                        ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                        : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="tabpanel1604C">
                                    1604C
                                </button>
                                <!-- Tab 5: 1604E -->
                                <a href="{{ route('with_holding.1604E') }}">
                                    <button @click="selectedTab = '1604E'" 
                                        :aria-selected="selectedTab === '1604E'" 
                                        :tabindex="selectedTab === '1604E' ? '0' : '-1'" 
                                        :class="selectedTab === '1604E' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanel1604E">
                                        1604E
                                    </button>
                                </a>
                            </div>
                            <div x-data="{ open: false, month: '', year: '' }" @open-generate-modal.window="open = true" x-cloak>
                                <!-- Modal Background -->
                                <div x-show="open" class="fixed inset-0 bg-gray-200 bg-opacity-50 z-50 flex items-center justify-center transition-opacity"
                                    x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                    <!-- Modal Content -->
                                    <div class="bg-white rounded-lg shadow-lg max-w-lg w-full mx-auto h-auto z-10 overflow-hidden">
                                        <div class="relative p-3 bg-blue-900 border-opacity-80 w-full">
                                            <h1 class="text-lg font-bold text-white text-center">Annual Information Return of Income Taxes<br>Withheld on Compensation</h1>
                                            <button @click="open = false" class="absolute right-4 top-7 items-center text-sm text-white hover:text-zinc-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 8L16 16M8 16L16 8" stroke="#1e3a8a" class="transition duration-200 hover:stroke-gray-600"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="p-10">
                                            <form method="POST" action="{{ route('with_holding.1604C.generate') }}">
                                                @csrf
                                                <input type="hidden" name="type" value="1604C">
                                                <!-- Year Selection -->
                                                <div class="mb-4">
                                                    <label for="year" class="block text-sm font-bold text-gray-700">Year</label>
                                                    <select id="year" name="year" x-model="year" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                                        required>
                                                        <option value="" disabled>Select Year</option>
                                                        @php
                                                            $currentYear = date('Y');
                                                            $startYear = $currentYear - 100;
                                                        @endphp
                                                        @for ($year = $startYear; $year <= $currentYear; $year++)
                                                            <option value="{{ $year }}">{{ $year }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                            
                                                <!-- Action Buttons -->
                                                <div class="flex justify-end mt-4">
                                                    <button type="button" @click="open = false" class="mr-2 hover:text-zinc-900 text-zinc-600 text-sm font-semibold py-2 px-4">Cancel</button>
                                                    <button type="submit" class="bg-blue-900 hover:bg-blue-950 text-white font-semibold text-sm py-1 px-6 rounded-lg">Generate</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="mb-12 mt-1 mx-12 overflow-hidden max-w-full border-neutral-300">
                            <div class="overflow-x-auto">
                                <table class="w-full items-start text-left text-sm text-neutral-600 p-4" id="table1604C">
                                    <thead class="bg-neutral-100 text-sm text-neutral-700">
                                        <tr>
                                            <th scope="col" class="p-4">
                                                <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                    <div class="relative flex items-center">
                                                        <input type="checkbox" x-model="checkAll" id="checkAll" @click="toggleAll()" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                        />
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                        </svg>
                                                    </div>  
                                                </label>
                                            </th>
                                            <th scope="col" class="py-3 px-4 text-left">Title</th>
                                            <th scope="col" class="py-3 px-4 text-left">Period</th>
                                            <th scope="col" class="py-3 px-4 text-left">Created By</th>
                                            <th scope="col" class="py-3 px-4 text-left">Status</th>
                                            <th scope="col" class="py-3 px-4 text-left">Date Created</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                        @forelse ($with_holdings as $with_holding)
                                            <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                                <td class="p-4">
                                                    <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                        <div class="relative flex items-center">
                                                            <input type="checkbox" @click="toggleCheckbox('{{ $with_holding->id }}')" :checked="selectedRows.includes('{{ $with_holding->id }}')" id="with_holding{{ $with_holding->id }}" 
                                                                class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                            />
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                            </svg>
                                                        </div>
                                                    </label>
                                                </td>
                                                <td class="py-3 px-4 font-semibold hover:font-bold hover:underline hover:text-blue-500"
                                                    onclick="window.location='{{ route('with_holding.1604C_remittances', ['id' => $with_holding->id]) }}'">{{ $with_holding->title ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">
                                                    {{ $with_holding->year ?? 'N/A' }}
                                                </td>
                                                <td class="py-3 px-4">{{ $with_holding->creator->name ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-zinc-100">
                                                        {{ $with_holding->status ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4">{{ $with_holding->created_at->format('F d, Y g:i A') ?? 'N/A' }}</td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center p-4">
                                                <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                <h1 class="font-bold text-lg mt-2">No Generated Returns yet</h1>
                                                <p class="text-sm text-neutral-500 mt-2">Start generating with the + button <br>at the top.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- <!-- Delete Confirmation Modal -->Hindi nag-aappear lahat ng delete modal sa TAX RETURNS, pls help --}}
                    <div  x-show="showConfirmDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50"
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
                                    You're going to Delete the selected item(s) in the Withholding Tax Return table. Are you sure?
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
                        <button @click="deleteRows" :disabled="selectedRows.length === 0" 
                            class="border px-3 py-2 rounded-lg text-sm text-red-600 border-red-600 bg-red-100 hover:bg-red-200 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                            <span class="text-red-600 transition group-hover:text-red-600">Delete Selected</span><span class="transition group-hover:text-red-500" x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                        </button>
                        <button @click="cancelSelection" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"> 
                            Cancel
                        </button>
                    </div>

                    <!-- Pagination -->
                    @if (count($with_holdings) > 0)
                        <div class="px-4 pb-4 mx-4">
                            {{-- {{ $with_holdings->links() }} --}}
                            {{ $with_holdings->appends(['perPage' => request('perPage')])->links('vendor.pagination.custom') }}
                        </div>
                    @endif
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

            // Update the D OM to reflect the state
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checkAll;
            });
        }

       // FOR SORT BUTTON
       document.getElementById('sortButton').addEventListener('click', function () {
            const dropdown = document.getElementById('dropdownMenu');
            const dropdownArrow = this.querySelector('svg:nth-child(3)');
            dropdown.classList.toggle('hidden');
            dropdownArrow.classList.toggle('rotate-180');
        });

        // FOR SORT BY
        function sortItems(criteria) {
            const table = document.querySelector('#table1604C tbody');
            const rows = Array.from(table.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
            let sortedRows;

            if (criteria === 'recently-added') {
                sortedRows = rows.sort((a, b) => {
                    const aDate = new Date(a.cells[5].textContent.trim());
                    const bDate = new Date(b.cells[5].textContent.trim());
                    return bDate - aDate; // Newest first
                });
            } else if (criteria === 'ascending') {
                sortedRows = rows.sort((a, b) => {
                    const aPeriod = new Date(a.cells[2].textContent.trim());
                    const bPeriod = new Date(b.cells[2].textContent.trim());
                    return aPeriod - bPeriod;
                });
            } else if (criteria === 'descending') {
                sortedRows = rows.sort((a, b) => {
                    const aPeriod = new Date(a.cells[2].textContent.trim());
                    const bPeriod = new Date(b.cells[2].textContent.trim());
                    return bPeriod - aPeriod;
                });
            }
            table.innerHTML = '';
            sortedRows.forEach(row => table.appendChild(row));
        }

        document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function () {
                const criteria = this.getAttribute('data-sort');
                document.getElementById('selectedOption').textContent = this.textContent; // Update selected option text
                sortItems(criteria);
            });
        });
        window.addEventListener('click', (event) => {
            const sortButton = document.getElementById('sortButton');
            const dropdownMenu = document.getElementById('dropdownMenu');
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
            form.action = "{{ route('with_holding.1604C') }}";
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