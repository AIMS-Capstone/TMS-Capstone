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
                            {{-- <a href="{{ route('vat_return') }}" class="inline-flex items-center text-sm font-normal text-zinc-500">
                                
                            </a> --}}
                            Income Tax Return
                            </li>
                            <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('income_return') }}" 
                                    class="ms-1 text-sm font-medium {{ Request::routeIs('income_return') ? 'font-extrabold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                    1701Q
                                </a>
                            </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <hr>

                <!-- Third Header -->
                <div x-data="{
                 searchTerm: '',
        search() {
            // Update the URL with the search term
            window.location.href = `?search=${this.searchTerm}`;
        },
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
                            fetch('/tax_return/destroy', {
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
                    
                <div class="container mx-auto">
                    <div class="flex flex-row space-x-2 items-center justify-between">
                        <!-- Search row -->
                        <div class="flex flex-row space-x-2 items-center ps-8">
                            <div class="relative w-80 p-4">
                                <form x-target="tableid" action="/income_return" role="search" aria-label="Table" autocomplete="off">
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
                            <div class="relative inline-block text-left">
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
                </div>

                <hr>

                   <!-- Filtering Tab/Third Header -->
                   <div x-data="{
                        selectedTab: '1701Q',
                        init() {
                            this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || '1701Q';
                        }
                        }" x-init="init" class="w-full p-5">
                        <div @keydown.right.prevent="$focus.wrap().next()" 
                            @keydown.left.prevent="$focus.wrap().previous()" 
                            class="flex flex-row text-center overflow-x-auto ps-8" 
                            role="tablist" 
                            aria-label="tab options">
                            
                            <!-- Tab 1: 1701Q -->
                            <button @click="selectedTab = '1701Q'; $dispatch('filter', { type: '1701Q' })"
                                :aria-selected="selectedTab === '1701Q'" 
                                :tabindex="selectedTab === '1701Q' ? '0' : '-1'" 
                                :class="selectedTab === '1701Q' 
                                    ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                    : 'text-zinc-600 font-medium hover:text-blue-900'"
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                type="button" 
                                role="tab" 
                                aria-controls="tabpanelAll">
                                1701Q
                            </button>
                            
                            <!-- Tab 2: 1702Q -->
                            <button @click="selectedTab = '1702Q'; $dispatch('filter', { type: '1702Q' })" 
                                :aria-selected="selectedTab === '1702Q'" 
                                :tabindex="selectedTab === '1702Q' ? '0' : '-1'" 
                                :class="selectedTab === '1702Q' 
                                    ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                    : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                type="button" 
                                role="tab" 
                                aria-controls="tabpanelAssets">
                                1702Q
                            </button>
                            <!-- Tab 2: 1702Q -->
                            <button @click="selectedTab = '1701'; $dispatch('filter', { type: '1701' })" 
                            :aria-selected="selectedTab === '1701'" 
                            :tabindex="selectedTab === '1701' ? '0' : '-1'" 
                            :class="selectedTab === '1701' 
                                ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                : 'text-zinc-600 font-medium hover:text-blue-900'" 
                            class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                            type="button" 
                            role="tab" 
                            aria-controls="tabpanelAssets">
                            1701
                        </button>
                            <!-- Tab 2: 1702Q -->
                            <button @click="selectedTab = '1702RT'; $dispatch('filter', { type: '1702RT' })" 
                            :aria-selected="selectedTab === '1702RT'" 
                            :tabindex="selectedTab === '1702RT' ? '0' : '-1'" 
                            :class="selectedTab === '1702RT' 
                                ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                : 'text-zinc-600 font-medium hover:text-blue-900'" 
                            class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                            type="button" 
                            role="tab" 
                            aria-controls="tabpanelAssets">
                            1702RT
                        </button>
                            <!-- Tab 2: 1702Q -->
                            <button @click="selectedTab = '1702MX'; $dispatch('filter', { type: '1702MX' })" 
                            :aria-selected="selectedTab === '1702MX'" 
                            :tabindex="selectedTab === '1702MX' ? '0' : '-1'" 
                            :class="selectedTab === '1702MX' 
                                ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                : 'text-zinc-600 font-medium hover:text-blue-900'" 
                            class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                            type="button" 
                            role="tab" 
                            aria-controls="tabpanelAssets">
                            1702MX
                        </button>
                            <!-- Tab 2: 1702Q -->
                            <button @click="selectedTab = '1702EX'; $dispatch('filter', { type: '1702EX' })" 
                                :aria-selected="selectedTab === '1702EX'" 
                                :tabindex="selectedTab === '1702EX' ? '0' : '-1'" 
                                :class="selectedTab === '1702EX' 
                                    ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                    : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                type="button" 
                                role="tab" 
                                aria-controls="tabpanelAssets">
                                1702EX
                            </button>
                        </div>
                        
                        <div x-data="{ open: false, selectedType: '', month: '' }" @open-generate-modal.window="open = true" x-cloak>
                            <div x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
                                <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                                    <h2 class="text-lg font-semibold mb-4">Income Tax Return</h2>
                                    
                                    <form method="POST" action="/vat_return">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                                            <select id="year" name="year" class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
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
                                            <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                                            <select id="month" name="month" class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                                x-model="month" 

                                                required>
                                                <option value="">Select Month</option>
                                                <option value="Q1">January - March (Q1)</option>
                                                <option value="Q2">April - June (Q2)</option>
                                                <option value="Q3">July - September (Q3)</option>
                                                <option value="Q4">October - December (Q4)</option>
                                            </select>
                                        </div>
                        
                                        <!-- Hidden inputs -->
                                        <input type="hidden" name="type" x-model="selectedTab"> <!-- Dynamically set type -->
                                        <input type="hidden" name="organization_id" value="{{ $organizationId }}">
                        
                                        <div class="flex justify-end">
                                            <button type="button" @click="open = false" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">Cancel</button>
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Generate</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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
                                <tbody class="divide-y divide-neutral-300">
                                    @if ($taxReturns->isEmpty())
                                        <tr>
                                            <td colspan="6" class="text-center p-4">
                                                <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                <h1 class="font-bold mt-2">No Generated VAT yet</h1>
                                                <p class="text-sm text-neutral-500 mt-2">Start generating with the + button <br>at the top.</p>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach ($taxReturns as $taxReturn)
                                            <tr>
                                                <td class="p-4">
                                                    <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                        <div class="relative flex items-center">
                                                            <input type="checkbox"  @change="toggleCheckbox('{{ $taxReturn->id }}')" id="taxReturn{{ $taxReturn->id }}"  class="relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white dark:border-neutral-700 dark:bg-neutral-900"  :checked="selectedRows.includes('{{ $taxReturn->id }}')"/>
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                            </svg>
                                                        </div>
                                                    </label>
                                                </td>
                                                <td class="text-left py-3 px-4 font-bold underline hover:font-bold hover:underline hover:text-blue-500">
                                                    <a href="{{ route('income_return.show', ['id' => $taxReturn->id, 'type' => $taxReturn->title]) }}">
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
                                                <td class="text-left py-3 px-4"><span class="bg-zinc-100 text-zinc-800 text-xs font-medium me-2 px-4 py-2 rounded-full">{{ $taxReturn->status }}</span></td>
                                                <td class="text-left py-3 px-4">{{ \Carbon\Carbon::parse($taxReturn->created_at)->format('F j, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                        <button @click="showConfirmDeleteModal = true; showDeleteCancelButtons = true;" :disabled="selectedRows.length === 0"
                            class="border px-3 py-2 rounded-lg text-sm text-red-600 border-red-600 bg-red-100 hover:bg-red-200 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                            <span class="text-red-600 transition group-hover:text-red-600">Delete Selected</span><span class="transition group-hover:text-red-500" x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                        </button>
                        <button @click="cancelSelection" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"> 
                            Cancel
                        </button>
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

        document.addEventListener('filter', event => {
            const url = new URL(window.location.href);
            url.searchParams.set('type', event.detail.type);
            window.location.href = url.toString();
        });
    </script>
</x-app-layout>