<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Page Main -->
                    <div class="container mx-auto my-auto pt-6">
                        <div class="px-10">
                            <div class="flex flex-row w-full items-center space-x-2">
                                <svg xmlns="http://www.w3.org/2000/svg"  class="w-8 h-8" viewBox="0 0 512 512"><path fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M160 144h288M160 256h288M160 368h288"/><circle cx="80" cy="144" r="16" fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="80" cy="256" r="16" fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="80" cy="368" r="16" fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
                                <p class="font-bold text-3xl auth-color">Chart of Accounts</p>
                            </div>
                        </div>
                        <div class="flex items-center px-10">
                            <div class="flex items-center">            
                                <p class="taxuri-text text-sm font-normal">The Chart of Accounts feature organizes all your financial accounts in one <br> place, making it simple to manage and track your company’s finances.</p>
                            </div>
                        </div>      
                    </div>

                    <div class="container mx-auto pt-2">
                            <!-- Second Header -->
                            <div class="container mx-auto ps-8">
                                <div class="flex flex-row space-x-2 items-center justify-center">
                                    <div x-data="{ selectedTab: 'Archive' }" class="w-full">
                                        <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300 dark:border-neutral-700" role="tablist" aria-label="tab options">
                                            <a href="/coa">
                                                <button @click="selectedTab = 'Accounts'" :aria-selected="selectedTab === 'Accounts'" 
                                                :tabindex="selectedTab === 'Accounts' ? '0' : '-1'" 
                                                :class="selectedTab === 'Accounts' ? 'font-bold box-border text-sky-900 border-b-4 border-sky-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-sky-900 hover:text-sky-900'" 
                                                class="h-min py-2 text-base" 
                                                type="button"
                                                role="tab" 
                                                aria-controls="tabpanelAccounts" >Accounts</button>
                                            </a>
                                            <button @click="selectedTab = 'Archive'" :aria-selected="selectedTab === 'Archive'" 
                                            :tabindex="selectedTab === 'Archive' ? '0' : '-1'" 
                                            :class="selectedTab === 'Archive' ? 'font-bold box-border text-sky-900 border-b-4 border-sky-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-sky-900 hover:text-sky-900'"
                                            class="h-min py-2 text-base" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="tabpanelArchive" >Archive</button>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <hr>
                        
                            <!-- Filtering Tab/Third Header -->
                            <div x-data="{
                                    selectedTab: (new URL(window.location.href)).searchParams.get('type') || 'All',
                                    checkAll: false,
                                    init() {
                                        this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || 'All';
                                    }
                                }" 
                                x-init="init" 
                                class="w-full p-5">
                                <div @keydown.right.prevent="$focus.wrap().next()" 
                                    @keydown.left.prevent="$focus.wrap().previous()" 
                                    class="flex flex-row text-center overflow-x-auto ps-8" 
                                    role="tablist" 
                                    aria-label="tab options">
                                    
                                    <!-- Tab 1: All -->
                                        <button @click="selectedTab = 'All'; $dispatch('filter', { type: 'All' })"
                                        :aria-selected="selectedTab === 'All'" 
                                        :tabindex="selectedTab === 'All' ? '0' : '-1'" 
                                        :class="selectedTab === 'All' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'"
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelAll">
                                        All
                                    </button>
                                    
                                    <!-- Tab 2: Assets -->
                                    <button @click="selectedTab = 'Assets'; $dispatch('filter', { type: 'Assets' })" 
                                        :aria-selected="selectedTab === 'Assets'" 
                                        :tabindex="selectedTab === 'Assets' ? '0' : '-1'" 
                                        :class="selectedTab === 'Assets' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelAssets">
                                        Assets
                                    </button>
                                    
                                    <!-- Tab 3: Liabilities -->
                                    <button @click="selectedTab = 'Liabilities'; $dispatch('filter', { type: 'Liabilities' })" 
                                        :aria-selected="selectedTab === 'Liabilities'" 
                                        :tabindex="selectedTab === 'Liabilities' ? '0' : '-1'" 
                                        :class="selectedTab === 'Liabilities' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelLiabilities">
                                        Liabilities
                                    </button>
                                    
                                    <!-- Tab 4: Equity -->
                                    <button @click="selectedTab = 'Equity'; $dispatch('filter', { type: 'Equity' })"
                                        :aria-selected="selectedTab === 'Equity'" 
                                        :tabindex="selectedTab === 'Equity' ? '0' : '-1'" 
                                        :class="selectedTab === 'Equity' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelEquity">
                                        Equity
                                    </button>
                                    
                                    <!-- Tab 5: Revenue -->
                                    <button @click="selectedTab = 'Revenue'; $dispatch('filter', { type: 'Revenue' })"
                                        :aria-selected="selectedTab === 'Revenue'" 
                                        :tabindex="selectedTab === 'Revenue' ? '0' : '-1'" 
                                        :class="selectedTab === 'Revenue' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelRevenue">
                                        Revenue
                                    </button>

                                    <!-- Tab 6: Cost of Sales -->
                                    <button @click="selectedTab = 'Cost of Sales'; $dispatch('filter', { type: 'Cost of Sales' })"
                                        :aria-selected="selectedTab === 'Cost of Sales'" 
                                        :tabindex="selectedTab === 'Cost of Sales' ? '0' : '-1'" 
                                        :class="selectedTab === 'Cost of Sales' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelCostofSales">
                                        Cost of Sales
                                    </button>

                                    <!-- Tab 7: Expenses -->
                                    <button @click="selectedTab = 'Expenses'; $dispatch('filter', { type: 'Expense' })"
                                        :aria-selected="selectedTab === 'Expenses'" 
                                        :tabindex="selectedTab === 'Expenses' ? '0' : '-1'" 
                                        :class="selectedTab === 'Expense' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelExpenses">
                                        Expenses
                                    </button>
                                </div>
                            </div>
                            <hr>
                    
                                <div 
                                    x-data="{
                                        showCheckboxes: false, 
                                        checkAll: false, 
                                        selectedRows: [],
                                        showDeleteCancelButtons: false,
                                        showRestoreCancelButtons: false, 
                                        isDisabled: false,
                                        showConfirmUnarchiveModal: false, 
                                        showConfirmDeleteModal: false,

                                        disableButtons() {
                                            this.isDisabled = true;
                                        },

                                        enableButtons() {
                                            this.isDisabled = false;
                                            this.showDeleteCancelButtons = false; 
                                            this.showRestoreCancelButtons = false; 
                                            this.showConfirmUnarchiveModal = false; 
                                            this.showConfirmDeleteModal = false;
                                        },

                                        toggleCheckbox(id) {
                                            if (this.selectedRows.includes(id)) {
                                                this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                                            } else {
                                                this.selectedRows.push(id);
                                            }
                                            // Update the checkAll state based on all checkboxes' status
                                            this.checkAll = this.selectedRows.length === {{ json_encode($inactiveCoas->count()) }};
                                        },
                                        toggleAll() {
                                            this.checkAll = !this.checkAll;
                                            if (this.checkAll) {
                                                this.selectedRows = {{ json_encode($inactiveCoas->pluck('id')->toArray()) }}; 
                                            } else {
                                                this.selectedRows = []; // Unselect all
                                            }
                                        },
                                        deleteRows() {
                                            if (this.selectedRows.length === 0) {
                                                alert('No rows selected for deletion.');
                                                return;
                                            }

                                            fetch('{{ route('coa.delete') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({ ids: this.selectedRows })
                                            })
                                            .then(response => {
                                                if (response.ok) {
                                                    location.reload();  // Reload page to reflect deletion
                                                } else {
                                                    alert('Error deleting rows.');
                                                }
                                            });
                                        },
                                        restoreRows() {
                                            if (this.selectedRows.length === 0) {
                                                alert('No rows selected for restoration.');
                                                return;
                                            }

                                            fetch('{{ route('coa.restore') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({ ids: this.selectedRows })
                                            })
                                            .then(response => {
                                                if (response.ok) {
                                                    location.reload();  // Reload page to reflect restoration
                                                } else {
                                                    alert('Error restoring rows.');
                                                }
                                            });
                                        },
                                        cancelSelection() {
                                            this.selectedRows = []; 
                                            this.checkAll = false;
                                            this.showCheckboxes = false; 
                                            this.showDeleteCancelButtons = false;
                                            this.showRestoreCancelButtons = false;
                                            this.showConfirmUnarchiveModal = false;
                                            this.showConfirmDeleteModal = false;
                                        },
                                        get selectedCount() {
                                            return this.selectedRows.length; 
                                        }
                                    }"
                                    class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300 dark:border-neutral-700"
                                >
                                    <div class="container mx-auto">
                                        <div class="flex flex-row space-x-2 items-center justify-between">
                                             <!-- Search row -->
                                            <div class="relative w-80 p-4">
                                                <form x-target="tableid" action="/coa/archive" role="search" aria-label="Table" autocomplete="off">
                                                    <input 
                                                        type="search" 
                                                        name="search" 
                                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900" 
                                                        aria-label="Search Term" 
                                                        placeholder="Search..." 
                                                        @input.debounce="$el.form.requestSubmit()" 
                                                        @search="$el.form.requestSubmit()"
                                                    >
                                                </form>
                                                <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                            </div>
                                            <div class="mx-auto space-x-4 pr-6">
                                                <!-- Unarchive Button -->
                                                <button 
                                                    type="button" 
                                                    @click="showCheckboxes = !showCheckboxes; showRestoreCancelButtons = !showRestoreCancelButtons; disableButtons();" 
                                                    :disabled="selectedRows.length === 1 || isDisabled"
                                                    class="border px-3 py-2 rounded-lg text-sm text-gray-700 hover:border-gray-700 hover:text-gray-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    <i class="fas fa-box-open"></i> Unarchive
                                                </button>
                                                <!-- Delete Button -->
                                                <button 
                                                    type="button" 
                                                    @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons; disableButtons();" 
                                                    :disabled="selectedRows.length === 1 || isDisabled"
                                                    class="border px-3 py-2 rounded-lg text-sm hover:border-red-500 hover:text-red-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    <i class="fa fa-trash"></i> Delete 
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table of Inactive CoAs -->
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300" id="tableid">
                                            <thead class="border-b border-neutral-300 bg-slate-200 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                                <tr>
                                                    <th scope="col" class="p-4">
                                                        <!-- Header Checkbox for Select All -->
                                                        <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600 dark:text-neutral-300">
                                                            <div class="relative flex items-center">
                                                                <input 
                                                                    type="checkbox" 
                                                                    x-model="checkAll" 
                                                                    id="checkAll" 
                                                                    @click="toggleAll()" 
                                                                    class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-gray-700 rounded-full checked:border-gray-700 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                                />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                            </div>
                                                        </label>
                                                    </th>
                                                    <th scope="col" class="py-4 px-2">Code</th>
                                                    <th scope="col" class="py-4 px-3">Name</th>
                                                    <th scope="col" class="py-4 px-3">Type</th>
                                                    <th scope="col" class="py-4 px-4">Date Created</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                                                @foreach ($inactiveCoas as $coa)
                                                    <tr>
                                                        <td class="p-4">
                                                            <!-- Body Checkbox for Individual Selection -->
                                                            <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600 dark:text-neutral-300">
                                                                <div class="relative flex items-center">
                                                                    <input 
                                                                        type="checkbox" 
                                                                        @click="toggleCheckbox('{{ $coa->id }}')" 
                                                                        :checked="selectedRows.includes('{{ $coa->id }}')"
                                                                        id="coa{{ $coa->id }}" 
                                                                        class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-gray-700 rounded-full checked:border-gray-700 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                                    />
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </div>
                                                            </label>
                                                        </td>
                                                        <td>{{ $coa->code }}</td>
                                                        <td> 
                                                            <x-view-coa />
                                                            <button @click="$dispatch('open-view-modal', {{ json_encode($coa) }})" class="underline hover:text-blue-500">
                                                                {{ $coa->name }}
                                                            </button>
                                                        </td>
                                                        <td>{{ $coa->type }}</td>
                                                        <td>{{ $coa->created_at }}</td>
                                                    </tr>
                                                @endforeach
                                                @if ($inactiveCoas->isEmpty())
                                                    <td colspan="6" class="text-center p-4">
                                                        <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                        <h1 class="font-extrabold text-lg mt-2">No Archived Chart of Accounts yet</h1>
                                                    </td>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Modals -->

                                        <!-- Unarchive Confirmation Modal -->
                                        <div 
                                            x-show="showConfirmUnarchiveModal" 
                                            x-cloak 
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                            @click.away="showConfirmUnarchiveModal = false"
                                        >
                                            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                                                <div class="flex flex-col items-center">
                                                    <!-- Icon -->
                                                    <div class="mb-4">
                                                        <i class="fas fa-exclamation-triangle text-gray-500 text-8xl"></i>
                                                    </div>

                                                    <!-- Title -->
                                                    <h2 class="text-xl font-bold text-gray-800 mb-2">Unarchive Item/s</h2>

                                                    <!-- Description -->
                                                    <p class="text-sm text-gray-600 text-center">
                                                        You're going to unarchive the selected item/s in the COA Archive table. Are you sure?
                                                    </p>

                                                    <!-- Actions -->
                                                    <div class="flex justify-center space-x-8 mt-6 w-full">
                                                        <button 
                                                            @click="showConfirmUnarchiveModal = false; enableButtons(); showRestoreCancelButtons = true; disableButtons();" 
                                                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm text-gray-700 transition"
                                                        >
                                                            Cancel
                                                        </button>
                                                        <button 
                                                            @click="restoreRows(); showConfirmUnarchiveModal = false;" 
                                                            class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-lg text-sm transition"
                                                        >
                                                            Unarchive
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Confirmation Modal -->
                                        <div 
                                            x-show="showConfirmDeleteModal" 
                                            x-cloak 
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                            @click.away="showConfirmDeleteModal = false"
                                        >
                                            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                                                <div class="flex flex-col items-center">
                                                    <!-- Icon -->
                                                    <div class="mb-4">
                                                        <i class="fas fa-exclamation-triangle text-red-500 text-8xl"></i>
                                                    </div>

                                                    <!-- Title -->
                                                    <h2 class="text-xl font-bold text-gray-800 mb-2">Delete COA</h2>

                                                    <!-- Description -->
                                                    <p class="text-sm text-gray-600 text-center">
                                                        You're going to delete the selected item/s in the COA Archive table. Are you sure?
                                                    </p>

                                                    <!-- Actions -->
                                                    <div class="flex justify-center space-x-8 mt-6 w-full">
                                                        <button 
                                                            @click="showConfirmDeleteModal = false; enableButtons(); enableButtons(); showDeleteCancelButtons = true; disableButtons();" 
                                                            class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm text-gray-700 transition"
                                                        >
                                                            Cancel
                                                        </button>
                                                        <button 
                                                            @click="deleteRows(); showConfirmDeleteModal = false;" 
                                                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition"
                                                        >
                                                            Delete
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-center py-4" x-cloak>
                                            <!-- Delete and Cancel buttons -->
                                            <div class="mt-4" x-show="showDeleteCancelButtons">
                                                <button 
                                                    type="button" 
                                                    @click="showConfirmDeleteModal = true; showDeleteCancelButtons = true;"
                                                    :disabled="selectedRows.length === 0"
                                                    class="border px-3 py-2 mx-2 rounded-lg text-sm text-red-600 bg-gray-200 hover:bg-red-100 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    <i class="fa fa-trash"></i> Delete <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                                                </button>
                                                <button 
                                                    @click="cancelSelection(); enableButtons();" 
                                                    class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"
                                                >
                                                    Cancel
                                                </button>
                                            </div>
                                            <!-- Unarchive and Cancel buttons -->
                                            <div class="mt-4" x-show="showRestoreCancelButtons">
                                                <button 
                                                    type = "button"
                                                    @click="showConfirmUnarchiveModal = true; showRestoreCancelButtons = true;"
                                                    :disabled="selectedRows.length === 0"
                                                    class="border px-3 py-2 mx-2 rounded-lg text-sm text-gray-700 bg-gray-200 hover:bg-gray-300 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    <i class="fas fa-box-open"></i> Confirm Unarchive <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                                                </button>
                                                <button @click="cancelSelection(); enableButtons();" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    {{ $inactiveCoas ->links('vendor.pagination.custom')}}
                                    </div>
                                </div>
                        </div>  
                    </div>
                </div>

                <script>
                document.addEventListener('search', event => {
                    window.location.href = `?search=${event.detail.search}`;
                });

                document.addEventListener('filter', event => {
                    window.location.href = `?type=${event.detail.type}`;
                });

                function toggleCheckboxes() {
                    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                        checkbox.checked = this.checkAll;
                    });
                }
                
            </script>
</x-app-layout>