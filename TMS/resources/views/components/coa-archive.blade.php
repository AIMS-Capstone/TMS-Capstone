<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Page Main -->
                        <div class="container mx-auto my-auto pt-6">
                            <div class="px-10">
                                <div class="flex flex-row w-full items-center">
                                    <img src="{{ asset('images/Frame 17.png') }}" class="px-2"> 
                                    <p class="font-bold text-3xl auth-color">Archive</p>
                                </div>
                            </div>
                            <div class="flex items-center px-10">
                                <div class="flex items-center px-2">            
                                    <p class="auth-color">The Chart of Accounts feature organizes all your financial accounts in one <br> place, making it simple to manage and track your company’s finances.</p>
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
                            <div x-data="{ selectedTab: 'All', checkAll: false }" class="w-full p-5">
                                <div @keydown.right.prevent="$focus.wrap().next()" 
                                    @keydown.left.prevent="$focus.wrap().previous()" 
                                    class="flex flex-row text-center overflow-x-auto ps-8" 
                                    role="tablist" 
                                    aria-label="tab options">
                                    
                                    <!-- Tab 1: All -->
                                    <button @click="$dispatch('filter', { type: 'All' })"
                                        :aria-selected="selectedTab === 'All'" 
                                        :tabindex="selectedTab === 'All' ? '0' : '-1'" 
                                        :class="selectedTab === 'All' 
                                            ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                                            : 'text-neutral-600 font-medium hover:text-sky-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelAll">
                                        All
                                    </button>
                                    
                                    <!-- Tab 2: Assets -->
                                    <button @click="$dispatch('filter', { type: 'Assets' })" 
                                        :aria-selected="selectedTab === 'Assets'" 
                                        :tabindex="selectedTab === 'Assets' ? '0' : '-1'" 
                                        :class="selectedTab === 'Assets' 
                                            ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                                            : 'text-neutral-600 font-medium hover:text-sky-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelAssets">
                                        Assets
                                    </button>
                                    
                                    <!-- Tab 3: Liabilities -->
                                    <button @click="$dispatch('filter', { type: 'Liabilities' })" 
                                        :aria-selected="selectedTab === 'Liabilities'" 
                                        :tabindex="selectedTab === 'Liabilities' ? '0' : '-1'" 
                                        :class="selectedTab === 'Liabilities' 
                                            ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                                            : 'text-neutral-600 font-medium hover:text-sky-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelLiabilities">
                                        Liabilities
                                    </button>
                                    
                                    <!-- Tab 4: Equity -->
                                    <button @click="$dispatch('filter', { type: 'Equity' })"
                                        :aria-selected="selectedTab === 'Equity'" 
                                        :tabindex="selectedTab === 'Equity' ? '0' : '-1'" 
                                        :class="selectedTab === 'Equity' 
                                            ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                                            : 'text-neutral-600 font-medium hover:text-sky-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelEquity">
                                        Equity
                                    </button>
                                    
                                    <!-- Tab 5: Revenue -->
                                    <button @click="$dispatch('filter', { type: 'Revenue' })"
                                        :aria-selected="selectedTab === 'Revenue'" 
                                        :tabindex="selectedTab === 'Revenue' ? '0' : '-1'" 
                                        :class="selectedTab === 'Revenue' 
                                            ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                                            : 'text-neutral-600 font-medium hover:text-sky-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelRevenue">
                                        Revenue
                                    </button>

                                    <!-- Tab 6: Cost of Sales -->
                                    <button @click="$dispatch('filter', { type: 'Sales' })"
                                        :aria-selected="selectedTab === 'Cost of Sales'" 
                                        :tabindex="selectedTab === 'Cost of Sales' ? '0' : '-1'" 
                                        :class="selectedTab === 'Cost of Sales' 
                                            ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                                            : 'text-neutral-600 font-medium hover:text-sky-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelCostofSales">
                                        Cost of Sales
                                    </button>
                                    
                                    <!-- Tab 7: Expenses -->
                                    <button @click="$dispatch('filter', { type: 'Expenses' })"
                                        :aria-selected="selectedTab === 'Expenses'" 
                                        :tabindex="selectedTab === 'Expenses' ? '0' : '-1'" 
                                        :class="selectedTab === 'Expenses' 
                                            ? 'font-semibold text-sky-900 bg-slate-100 border-slate-200 border-b-2 rounded-t-lg'
                                            : 'text-neutral-600 font-medium hover:text-sky-900'" 
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
                                        toggleCheckbox(id) {
                                            if (this.selectedRows.includes(id)) {
                                                this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                                            } else {
                                                this.selectedRows.push(id);
                                            }
                                            this.checkAll = this.selectedRows.length === {{ json_encode($inactiveCoas->count()) }}; // Update checkAll state
                                        },
                                        toggleAll() {
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

                                            if (confirm('Are you sure you want to delete the selected row/s?')) {
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
                                            }
                                        },
                                        restoreRows() {
                                            if (this.selectedRows.length === 0) {
                                                alert('No rows selected for restoration.');
                                                return;
                                            }

                                            if (confirm('Are you sure you want to restore the selected row/s?')) {
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
                                            }
                                        },
                                        cancelSelection() {
                                            this.selectedRows = []; 
                                            this.checkAll = false;
                                            this.showCheckboxes = false; 
                                            this.showDeleteCancelButtons = false;
                                            this.showRestoreCancelButtons = false;
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
                                                <!-- Delete button -->
                                                <button 
                                                    type="button" 
                                                    @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons" 
                                                    class="border px-3 py-2 rounded-lg text-sm hover:border-red-500 hover:text-red-500 transition"
                                                >
                                                    <i class="fa fa-trash"></i> Delete 
                                                </button>
                                                <!-- Restore button -->
                                                <button 
                                                    type="button" 
                                                    @click="showCheckboxes = !showCheckboxes; showRestoreCancelButtons = !showRestoreCancelButtons" 
                                                    class="border px-3 py-2 rounded-lg text-sm hover:border-blue-900 hover:text-blue-900 transition"
                                                >
                                                    <i class="fas fa-trash-restore"></i> Restore 
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
                                                        <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600 dark:text-neutral-300">
                                                            <div class="relative flex items-center">
                                                                <input type="checkbox" x-model="checkAll" id="checkAll" @click="toggleAll()" class="before:content-none peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
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
                                                            <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600 dark:text-neutral-300">
                                                                <div class="relative flex items-center">
                                                                    <input type="checkbox" x-model="SelectedRows" @click="toggleCheckbox('{{ $coa->id }}')" id="coa{{ $coa->id }}" class="before:content-none peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" :checked="selectedRows.includes('{{ $coa->id }}')" />
                                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                    </svg>
                                                                </div>
                                                            </label>
                                                        </td>
                                                        <td>{{ $coa->type }}</td>
                                                        <td>{{ $coa->code }}</td>
                                                        <td>{{ $coa->name }}</td>
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

                                        <div class="flex justify-center py-4" x-cloak>
                                            <!-- Delete and Cancel buttons -->
                                            <div class="mt-4" x-show="showDeleteCancelButtons">
                                                <button @click="deleteRows()" class="border px-3 py-2 mx-2 rounded-lg text-sm text-red-600 hover:bg-red-100 transition">
                                                    <i class="fa fa-trash"></i> Confirm Delete <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                                                </button>
                                                <button @click="cancelSelection()" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                                                    <i class="fa fa-times"></i> Cancel
                                                </button>
                                            </div>
                                            <!-- Restore and Cancel buttons -->
                                            <div class="mt-4" x-show="showRestoreCancelButtons">
                                                <button @click="restoreRows()" class="border px-3 py-2 mx-2 rounded-lg text-sm text-green-500 hover:bg-red-100 transition">
                                                    <i class="fas fa-trash-restore"></i>Confirm Restore <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                                                </button>
                                                <button @click="cancelSelection()" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                                                    <i class="fa fa-times"></i> Cancel
                                                </button>
                                            </div>
                                        </div>
                                    {{ $inactiveCoas ->links()}}
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