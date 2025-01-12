<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div x-data="{
                        selectedTab: 'input-summary',  // Default tab
                        changeTab(tab) {
                            this.selectedTab = tab;
                        }
                    }" class="container mx-auto">

                        <!-- Tabs Navigation -->
                        <div class="flex justify-start space-x-8 mb-6">
                            <a 
                            href="{{route('income_return.show', ['id' => $taxReturn->id, 'type' => $taxReturn->title])}}"
                                @click="changeTab('input-summary')" 
                                :class="selectedTab === 'input-summary' ? 'font-bold text-blue-900 bg-slate-100 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                class="px-4 py-2 text-sm"
                            >
                                Input Summary
                            </a>
                            <a 
                                href="{{ route('tax_return.report', ['id' => $taxReturn->id]) }}" 
                                class="text-zinc-600 font-medium hover:text-blue-900 px-4 py-2 text-sm"
                            >
                                Report
                            </a>
                        </div>
                    </div>
                    <!-- Tab Content for Input Summary -->
                 
<div 
x-data="{
    showCheckboxes: false, 
    checkAll: false, 
    selectedRows: [], 
    showDeleteCancelButtons: false,
   activeTab: '{{ $activeTab }}',
    selectedType: '',
    
    // Toggle a single row
    toggleCheckbox(id) {
        if (this.selectedRows.includes(id)) {
            this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
        } else {
            this.selectedRows.push(id);
        }
    },

    // Toggle all rows
    toggleAll() {
        if (this.checkAll) {
              selectedRows: {{ json_encode($activeTab === 'individual' ? $individualTaxRows->pluck('transaction_id')->toArray() : $spouseTaxRows->pluck('transaction_id')->toArray()) }};

        } else {
            this.selectedRows = []; 
        }
        console.log(this.selectedRows); // For debugging
    },

    // Handle deletion
deleteRows() {
    if (this.selectedRows.length === 0) {
        alert('No rows selected for deletion.');
        return;
    }

    if (confirm('Are you sure you want to archive the selected transaction(s)?')) {
        const payload = {
            ids: this.selectedRows, // Selected transaction IDs
            tax_return_id: {{ $taxReturn->id }}, // Pass the current tax return ID
            activeTab: this.activeTab // Pass the activeTab ('individual' or 'spouse')
        };

        fetch('/tax-return-transaction/deactivate_transaction', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(payload),
        })
        .then(response => {
            if (response.ok) {
                alert('Transactions archived successfully!');
                location.reload();  
            } else {
                response.json().then(data => {
                    alert(data.message || 'Error deleting transactions.');
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An unexpected error occurred.');
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
    },

    // Handle filter change
     setTab(tab) {
    window.location.href = window.location.pathname + '?tab=' + tab;
},
    filterTransactions() {
        const url = new URL(window.location.href);
        url.searchParams.set('type', this.selectedType);
        window.location.href = url.toString();
    }
}"
class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300 dark:border-neutral-700"
>
<div class="container mx-auto">
    <!-- Tab system to toggle between Individual and Spouse -->
  

    <!-- Search and Add Existing Transaction Buttons -->
    <div class="flex flex-row space-x-2 items-center justify-between mb-4">
        <div class="relative w-80 p-4">
            <form action="{{ route('tax_return.income_show_coa', ['taxReturn' => $taxReturn->id]) }}" method="GET" class="relative w-80 p-4">
                <input type="hidden" name="tab" value="{{ $activeTab }}">
                <input 
                    type="search" 
                    name="search" 
                    value="{{ request('search') }}" 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900" 
                    placeholder="Search..."
                >
                <button type="submit" class="absolute left-8 top-1/2 transform -translate-y-1/2">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                </button>
            </form>
            

        </div>

        <div class="mx-auto space-x-4 pr-6">
            <button 
                type="button"
                x-data="{}" 
                x-on:click="$dispatch('open-generate-modal', { year: '{{ $taxReturn->year }}', monthOrQuarter: '{{ $taxReturn->month }}' })" 
                class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
            >
                Add Existing Transactions
            </button>
            <button 
                type="button" 
                @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons" 
                class="border px-3 py-2 rounded-lg text-sm hover:border-red-500 hover:text-red-500 transition"
            >
                <i class="fa fa-trash"></i> Delete
            </button>
            <button type="button">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300" id="tableid">
            <thead class="border-b border-neutral-300 bg-slate-200 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                <tr>
                    <th scope="col" class="p-4">
                        <!-- Checkbox for selecting all -->
                        <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                            <div class="relative flex items-center">
                                <input type="checkbox" x-model="checkAll" id="checkAll" @change="toggleAll()" 
                                       class="peer relative cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white 
                                       before:content[''] before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 
                                       focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 
                                       dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white 
                                       dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" 
                                     stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-neutral-100 
                                     peer-checked:visible dark:text-black">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                        </label>
                    </th>
                    <th scope="col" class="py-4 px-2">Contact</th>
                    <th scope="col" class="py-4 px-2">Description</th>
                    <th scope="col" class="py-4 px-2">Invoice No.</th>
                    <th scope="col" class="py-4 px-2">Reference No.</th>
                    <th scope="col" class="py-4 px-2">Date</th>
                    <th scope="col" class="py-4 px-2">Amount</th>
                    <th scope="col" class="py-4 px-2">COA Code</th>
                </tr>
            </thead>
        
            <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                <!-- Loop through taxRows based on active tab -->
                @foreach ($activeTab === 'individual' ? $individualTaxRows : $spouseTaxRows as $taxRow)
                    <tr>
                        <td class="p-4">
                            <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                <div class="relative flex items-center">
                                    <input type="checkbox" @change="toggleCheckbox('{{ $taxRow->transaction_id }}')" :checked="selectedRows.includes('{{ $taxRow->transaction_id }}')" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-yellow-600 checked:before:bg-yellow-600 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-yellow-600 checked:focus:outline-yellow-600 active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 peer-checked:visible text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                            </label>
                        </td>
                        <td>{{ $taxRow->transaction->contactDetails->bus_name }}<br>
                            {{ $taxRow->transaction->contactDetails->contact_address }}<br>
                            {{ $taxRow->transaction->contactDetails->contact_tin }}</td>
                        <td>{{ $taxRow->description }}</td>
                        <td>{{ $taxRow->transaction->inv_number }}</td>
                        <td>{{ $taxRow->transaction->reference }}</td>
                        <td>{{ \Carbon\Carbon::parse($taxRow->transaction->date)->format(' F j, Y') }}</td>
                        <td>{{ $taxRow->net_amount }}</td>
                        <td>{{ $taxRow->coaAccount->code }}</td>
                    </tr>
                @endforeach
            </tbody>
        
            <!-- Pagination links -->
            <tr>
                <td colspan="12" class="p-4">
                    <div class="flex justify-between items-center">
                        <div class="text-sm">
                            Showing 
                            {{ $activeTab === 'individual' ? $individualTaxRows->firstItem() : $spouseTaxRows->firstItem() }} 
                            to 
                            {{ $activeTab === 'individual' ? $individualTaxRows->lastItem() : $spouseTaxRows->lastItem() }} 
                            of 
                            {{ $activeTab === 'individual' ? $individualTaxRows->total() : $spouseTaxRows->total() }} 
                            tax rows
                        </div>
                        <div>
                            <!-- Pagination links with the active tab and type parameter preserved -->
                            {{ $activeTab === 'individual' ? $individualTaxRows->appends(['type' => $type])->links('vendor.pagination.custom') : $spouseTaxRows->appends(['type' => $type])->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        
    </div>

    <!-- Action Buttons (same as before) -->
    <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
        <button 
            @click="deleteRows" 
            class="border px-3 py-2 mx-2 rounded-lg text-sm text-red-600 hover:bg-red-100 transition"
        >
            <i class="fa fa-trash"></i> Archive Selected <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
        </button>
        <button 
            @click="cancelSelection" 
            class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"
        >
            <i class="fa fa-times"></i> Cancel
        </button>
    </div>

    <!-- Modal for Adding Existing Transactions -->
    <div 
        x-data="{
            open: false,
            transactions: [],
            selectedTransaction: null,
            year: null,
            monthOrQuarter: null,
            fetchTransactions(year, monthOrQuarter) {
                fetch('/tax-return-transaction/all_transactions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ taxReturnYear: year, taxReturnMonth: monthOrQuarter })
                })
                .then(response => response.json())
                .then(data => {
                    this.transactions = data;
                });
            },
            openModal(year, monthOrQuarter) {
                this.year = year;
                this.monthOrQuarter = monthOrQuarter;
                this.fetchTransactions(year, monthOrQuarter);
                this.open = true;
            },
            closeModal() {
                this.open = false;
                this.transactions = [];
            }
        }" 
        @open-generate-modal.window="openModal($event.detail.year, $event.detail.monthOrQuarter)"
        x-show="open" 
        x-transition
        class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center"
        x-cloak
    >
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden" x-show="open" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <!-- Modal Header -->
            <div class="flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto">
                <h1 class="text-lg font-bold text-white">Select Transaction</h1>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <form method="POST" action="{{ route('tax_return_transaction.addTransaction') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="transaction-dropdown" class="block text-sm font-bold text-gray-700">Select a Transaction</label>
                        <select 
                            name="transaction_id"
                            id="transaction-dropdown" 
                            x-model="selectedTransaction" 
                            class="w-full border-gray-300 rounded-md shadow-sm"
                            :disabled="transactions.length === 0"
                        >
                            <option value="">-- Select a transaction --</option>
                            <template x-for="transaction in transactions" :key="transaction.id">
                                <option :value="transaction.id" 
                                x-text="(transaction.contact_details ? transaction.contact_details.bus_name : 'Journal Entry') + ' - ' + 
                                (transaction.inv_number ? transaction.inv_number : transaction.reference || 'No Reference') + 
                                ' - ' + (transaction.date || 'No Date')">
                                </option>
                            </template>
                        </select>
                    </div>
                
                    <!-- Hidden field to pass the taxReturn ID -->
                    <input type="hidden" name="tax_return_id" value="{{ $taxReturn->id }}">
                
                    <!-- Hidden field to dynamically set the activeTab -->
                    <input type="hidden" name="is_spouse" value="{{ $activeTab === 'spouse' ? 'true' : 'false' }}">
                
                    <div class="flex justify-end mt-6">
                        <button type="button" @click="closeModal" class="mr-4 font-semibold text-zinc-700 px-3 py-1 rounded-md hover:text-zinc-900 transition">Cancel</button>
                        <button type="submit" :disabled="!selectedTransaction" class="font-semibold bg-blue-900 text-white text-center px-6 py-1.5 rounded-md hover:bg-blue-950 border-blue-900 hover:text-white transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                            Submit
                        </button>
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
</script>

</x-app-layout>
