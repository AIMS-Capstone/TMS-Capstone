@php
$organizationId = session('organization_id');
@endphp
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Main -->

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
                }" class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300 dark:border-neutral-700">
                    
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
                                <button 
                                    type="button" 
                                    @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons" 
                                    class="border px-3 py-2 rounded-lg text-sm hover:border-red-500 hover:text-red-500 transition"
                                >
                                    <i class="fa fa-trash"></i> Delete
                                </button>
                                
                                <button 
                                    x-data 
                                    x-on:click="$dispatch('open-generate-modal')" 
                                    class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
                                >
                                    <i class="fa fa-plus-circle"></i> Generate
                                </button>
                              
                                <button type="button">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                   <!-- Filtering Tab/Third Header -->
                   <div x-data="{
                    selectedTab: '2550M',
                    init() {
                        this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || '2550M';
                    }
}" x-init="init" class="w-full p-5">
    <div @keydown.right.prevent="$focus.wrap().next()" 
         @keydown.left.prevent="$focus.wrap().previous()" 
         class="flex flex-row text-center overflow-x-auto ps-8" 
         role="tablist" 
         aria-label="tab options">
         
        <!-- Tab 1: 2550M/Q -->
        <button @click="selectedTab = '2550M'; $dispatch('filter', { type: '2550M' })"
            :aria-selected="selectedTab === '2550M'" 
            :tabindex="selectedTab === '2550M' ? '0' : '-1'" 
            :class="selectedTab === '2550M' 
                ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                : 'text-zinc-600 font-medium hover:text-blue-900'"
            class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
            type="button" 
            role="tab" 
            aria-controls="tabpanelAll">
            2550M/Q
        </button>
        
        <!-- Tab 2: Capital Goods -->
        <button @click="selectedTab = 'CapitalGoods'; $dispatch('filter', { type: 'CapitalGoods' })" 
            :aria-selected="selectedTab === 'CapitalGoods'" 
            :tabindex="selectedTab === 'CapitalGoods' ? '0' : '-1'" 
            :class="selectedTab === 'CapitalGoods' 
                ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                : 'text-zinc-600 font-medium hover:text-blue-900'" 
            class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
            type="button" 
            role="tab" 
            aria-controls="tabpanelAssets">
            Capital Goods
        </button>
    </div>
</div>
                    <hr>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300" id="tableid">
                            <thead class="border-b border-neutral-300 bg-slate-200 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
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
                                    <th scope="col" class="py-4 px-2">Title</th>
                                    <th scope="col" class="py-4 px-2">Name</th>
                                    <th scope="col" class="py-4 px-4">Created By</th>
                                    <th scope="col" class="py-4 px-3">Date Created</th>
                                    <th scope="col" class="py-4 px-3">Status</th>
                                    <th scope="col" class="py-4 px-2">Action</th>
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
                                                        <input type="checkbox" class="relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white dark:border-neutral-700 dark:bg-neutral-900" />
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                        </svg>
                                                    </div>
                                                </label>
                                            </td>
                                            <td class="py-4 px-2">{{ $taxReturn->title }}</td>
                                            <td class="py-4 px-2">{{ $taxReturn->year }}{{ $taxReturn->month }}</td>
                                            <td class="py-4 px-4">{{ $taxReturn->user ? $taxReturn->user->first_name . ' ' . $taxReturn->user->last_name : 'N/A' }}</td>
                                            <td class="py-4 px-3">{{ $taxReturn->created_at->format('Y-m-d') }}</td>
                                            <td class="py-4 px-4">{{ $taxReturn->status }}</td>
                                         
                                            <td class="py-4 px-2">
                                                <x-edit-coa />
                                                <a href="{{ route('tax_return.slsp_data', $taxReturn->id) }}" class="hover:border-slate-600 hover:text-slate-600 border px-3 py-2 rounded-lg text-sm">
                                                    Show
                                                </a>
                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                            
                        </table>
                    </div>

                    <!-- Action Buttons -->
                    <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                        <button @click="deleteRows" class="border px-3 py-2 mx-2 rounded-lg text-sm text-red-600 hover:bg-red-100 transition">
                            <i class="fa fa-trash"></i> Archive Selected <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                        </button>
                        <button @click="cancelSelection" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-data="{ open: false, selectedTab: '2550M' }" @open-generate-modal.window="open = true" x-cloak>
        <div x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                <h2 class="text-lg font-semibold mb-4">Generate VAT Return</h2>
                
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
                        <select id="month" name="month" class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
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
    
                    <input type="hidden" name="type" x-model="selectedTab"> <!-- Use x-model here -->
                    <input type="hidden" name="organization_id" value="{{ $organizationId }}">
    
                    <div class="flex justify-end">
                        <button type="button" @click="open = false" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">Cancel</button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Generate</button>
                    </div>
                </form>
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
