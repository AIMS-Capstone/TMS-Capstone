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
                                    <div x-data="{ selectedTab: 'Accounts' }" class="w-full">
                                        <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300" role="tablist" aria-label="tab options">
                                            <button @click="selectedTab = 'Accounts'" :aria-selected="selectedTab === 'Accounts'" 
                                            :tabindex="selectedTab === 'Accounts' ? '0' : '-1'" 
                                            :class="selectedTab === 'Accounts' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900'   : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'" 
                                            class="h-min py-2 text-base" 
                                            type="button"
                                            role="tab" 
                                            aria-controls="tabpanelAccounts" >Accounts</button>
                                            <a href="/coa/archive">
                                                <button @click="selectedTab = 'Archive'" :aria-selected="selectedTab === 'Archive'" 
                                                :tabindex="selectedTab === 'Archive' ? '0' : '-1'" 
                                                :class="selectedTab === 'Archive' ? 'font-bold box-border text-sky-900 border-b-4 border-sky-900 dark:border-white dark:text-white'   : 'text-neutral-600 font-medium dark:text-neutral-300 dark:hover:border-b-neutral-300 dark:hover:text-white hover:border-b-2 hover:border-b-sky-900 hover:text-sky-900'"
                                                class="h-min py-2 text-base" 
                                                type="button" 
                                                role="tab" 
                                                aria-controls="tabpanelArchive" >Archive</button>
                                            </a>
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

                            <!-- Third Header -->
                                <div 
                                    x-data="{
                                        showCheckboxes: false, 
                                        checkAll: false, 
                                        selectedRows: [],
                                        showDeleteCancelButtons: false,
                                        showConfirmArchiveModal: false,
                                        
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
                                            this.checkAll = !this.checkAll;
                                            if (this.checkAll) {
                                                this.selectedRows = {{ json_encode($coas->pluck('id')->toArray()) }}; 
                                            } else {
                                                this.selectedRows = []; 
                                            }
                                            console.log(this.selectedRows); // Debugging line
                                        },
                                        
                                        // Handle archiving
                                        deleteRows() {
                                            if (this.selectedRows.length === 0) {
                                                alert('No rows selected for deletion.');
                                                return;
                                            }

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
                                                    alert('Error archiving rows.');
                                                }
                                            });
                                        },
                                        
                                        // Cancel selection
                                        cancelSelection() {
                                            this.selectedRows = []; 
                                            this.checkAll = false;
                                            this.showCheckboxes = false; 
                                            this.showDeleteCancelButtons = false;
                                            this.showConfirmArchiveModal = false;
                                        },
                                        
                                        get selectedCount() {
                                            return this.selectedRows.length;
                                        }
                                    }"
                                    class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300 dark:border-neutral-700"
                                >

                                    <!-- Fourth Header -->
                                    <div class="container mx-auto">
                                        <div class="flex flex-row space-x-2 items-center justify-between">
                                            <!-- Search row -->
                                            <div class="relative w-80 p-4">
                                                <form x-target="tableid" action="/coa" role="search" aria-label="Table" autocomplete="off">
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
                                            <!-- End row -->
                                            <div class="mx-auto space-x-4 pr-6">
                                                <!-- Add COA Modal -->
                                                <x-add-coa-modal />
                                                <button 
                                                    x-data 
                                                    x-on:click="$dispatch('open-add-modal')" 
                                                    class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
                                                >
                                                    <i class="fa fa-plus-circle" aria-hidden="true"></i> Add
                                                </button>
                                                <!-- Import COA Modal -->
                                                <x-import-coa-modal />     
                                                <button  
                                                    x-data 
                                                    x-on:click="$dispatch('open-import-modal')" 
                                                    class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
                                                >
                                                    <i class="fa-solid fa-file-import"></i> Import
                                                </button>
                                                <a href="{{ url('download_coa')}}">
                                                    <button
                                                        type="button"
                                                        class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
                                                    > 
                                                        <i class="fa fa-download"></i> Download
                                                    </button>
                                                </a>
                                                <button 
                                                    type="button" 
                                                    @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons; $el.disabled = true;" 
                                                    :disabled="selectedRows.length === 1"
                                                    class="border px-3 py-2 rounded-lg text-sm text-gray-600 hover:border-gray-800 hover:text-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    <i class="fa fa-trash"></i> Archive
                                                </button>
                                                <button type="button">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Table -->
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300" id="tableid">
                                            <thead class="border-b border-neutral-300 bg-slate-200 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                                <tr>
                                                    <th scope="col" class="p-4">
                                                        <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                            <div class="relative flex items-center">
                                                                <input type="checkbox" x-model="checkAll" id="checkAll" @change="toggleAll()" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                            </div>
                                                        </label>
                                                    </th>
                                                    <th scope="col" class="py-4 px-2">Code</th>
                                                    <th scope="col" class="py-4 px-2">Name</th>
                                                    <th scope="col" class="py-4 px-4">Type</th>
                                                    <th scope="col" class="py-4 px-3">Date Created</th>
                                                    <th scope="col" class="py-4 px-2">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                                                @if (count($coas) > 0)
                                                    @foreach ($coas as $coa)
                                                        <tr>
                                                            <td class="p-4">
                                                                <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                                    <div class="relative flex items-center">
                                                                        <input type="checkbox" @change="toggleCheckbox('{{ $coa->id }}')" id="coa{{ $coa->id }}"  class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" :checked="selectedRows.includes('{{ $coa->id }}')" />
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
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
                                                            <td class="py-4 px-2">
                                                                <x-edit-coa />
                                                                <p
                                                                    @click="$dispatch('open-edit-modal', {
                                                                        id: '{{ $coa->id }}',
                                                                        code: '{{ $coa->code }}',
                                                                        name: '{{ $coa->name }}',
                                                                        type: '{{ $coa->type }}'
                                                                    })"
                                                                    class="underline hover:border-sky-900 hover:text-sky-900 hover:cursor-pointer px-3 py-y text-sm"
                                                                >
                                                                    Edit
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center p-4">
                                                            <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                            <h1 class="font-extrabold text-lg mt-2">No Chart of Accounts yet</h1>
                                                            <p class="text-sm text-neutral-500 mt-2">Start adding accounts with the <br> + Add button.</p>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Modal -->

                                    <!-- Archive Confirmation Modal -->
                                    <div 
                                        x-show="showConfirmArchiveModal" 
                                        x-cloak 
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                        @click.away="showConfirmArchiveModal = false"
                                    >
                                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                                            <div class="flex flex-col items-center">
                                                <!-- Icon -->
                                                <div class="mb-4">
                                                    <i class="fas fa-exclamation-triangle text-gray-500 text-8xl"></i>
                                                </div>

                                                <!-- Title -->
                                                <h2 class="text-xl font-bold text-gray-800 mb-2">Archive Item/s</h2>

                                                <!-- Description -->
                                                <p class="text-sm text-gray-600 text-center">
                                                    You're going to Archive the selected item/s in the Charts of Account table. Are you sure?
                                                </p>

                                                <!-- Actions -->
                                                <div class="flex justify-center space-x-8 mt-6 w-full">
                                                    <button 
                                                        @click="showConfirmArchiveModal = false; showDeleteCancelButtons = true;" 
                                                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 rounded-lg text-sm text-gray-700 transition"
                                                    >
                                                        Cancel
                                                    </button>
                                                    <button 
                                                        @click="deleteRows(); showConfirmArchiveModal = false;" 
                                                        class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-lg text-sm transition"
                                                    >
                                                        Archive
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                                        <button 
                                            @click="showConfirmArchiveModal = true; showDeleteCancelButtons = true;" 
                                            :disabled="selectedRows.length === 0"
                                            class="border px-3 py-2 mx-2 rounded-lg text-sm text-gray-700 bg-gray-200 hover:bg-gray-300 transition disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <i class="fa fa-box"></i> Archive Selected <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                                        </button>
                                        <button 
                                            @click="cancelSelection" 
                                            class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"
                                        >
                                            <i class="fa fa-times"></i> Cancel
                                        </button>
                                    </div>
                                    
                                    {{ $coas->links('vendor.pagination.custom') }}
                                </div>

                            </div>
                        </div>
                        <!-- End of Main Content -->
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

        // document.addEventListener('filter', event => {
        //     window.location.href = `?type=${event.detail.type}`;
        // });

        function toggleCheckboxes() {
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = this.checkAll;
            });
        }
        
    </script>
</x-app-layout>