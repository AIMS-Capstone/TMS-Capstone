<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Navigation Tabs -->
                    <nav class="flex space-x-4 mb-6 border-b border-gray-200 pb-2">
                        <a href="{{ route('percentage_return.slsp_data', $taxReturn->id) }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('percentage_return.slsp_data') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            SLSP Data
                        </a>
                        <a href="{{ route('tax-returns.percentage-summary', $taxReturn->id) }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('summary') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            Summary
                        </a>
                        
                        <a href="{{ route('percentage_return.report', $taxReturn->id) }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('percentage_return.report') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                           Report
                        </a>
                        <a href="{{ route('notes_activity') }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('notes_activity') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            Notes & Activity
                        </a>
                  
                    </nav>

             <!-- Transactions Header -->
<div 
x-data="{
    showCheckboxes: false, 
    checkAll: false, 
    selectedRows: [], 
    showDeleteCancelButtons: false,

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
         this.selectedRows = {{ json_encode($paginatedTaxRows->pluck('transaction_id')->toArray()) }};

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
            fetch('/tax-return-transaction/deactivate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: this.selectedRows,
                tax_return_id: {{ $taxReturn->id }}
                }),
                
            })
            .then(response => {
                if (response.ok) {
                    location.reload();  
                } else {
                    alert('Error deleting transactions.');
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
    },

    // Handle filter change
    filterTransactions() {
        const url = new URL(window.location.href);
        url.searchParams.set('type', this.selectedType);
        window.location.href = url.toString();
    }
}"
class="mb-12 mx-12 overflow-hidden max-w-full rounded-md border-neutral-300 dark:border-neutral-700"
>
<!-- Transactions Header -->
<div class="container mx-auto">
    <div class="flex flex-row space-x-2 items-center justify-between">
        <!-- Search row -->
        <div class="relative w-80 p-4">
            <form x-target="tableid" action="/transactions" role="search" aria-label="Table" autocomplete="off">
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
                <th scope="col" class="py-4 px-2">ATC</th>
                <th scope="col" class="py-4 px-2">Date</th>
                <th scope="col" class="py-4 px-2">Tax Base</th>
                <th scope="col" class="py-4 px-2">Tax Amount</th>
                <th scope="col" class="py-4 px-2">Tax Rate</th>
                <th scope="col" class="py-4 px-2">COA Code</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
            @foreach ($paginatedTaxRows as $taxRow)
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
                    <td>{{ $taxRow->atc->tax_code }}</td>
                    <td>{{ \Carbon\Carbon::parse($taxRow->transaction->date)->format(' F j, Y') }}</td>

                  
                    <td>{{ $taxRow->net_amount }}</td>
                    <td>{{ $taxRow->atc_amount }}</td>
                    <td>{{ $taxRow->atc->tax_rate }}</td>
                    <td>{{ $taxRow->coaAccount->code }}</td>
                </tr>
            @endforeach
        </tbody>
        
        <!-- Pagination links -->
        <tr>
            <td colspan="12" class="p-4">
                <div class="flex justify-between items-center">
                    <div class="text-sm">
                        Showing {{ $paginatedTaxRows->firstItem() }} to {{ $paginatedTaxRows->lastItem() }} of {{ $paginatedTaxRows->total() }} tax rows
                    </div>
                    <div>
                        {{ $paginatedTaxRows->links('vendor.pagination.custom') }}
                    </div>
                </div>
            </td>
        </tr>
        
 
        
        


<!-- Action Buttons -->
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

<div 
    x-data="{
        open: false,
        transactions: [],
        selectedTransaction: null,
        year: null,
        monthOrQuarter: null,
        fetchTransactions(year, monthOrQuarter) {
            fetch('/tax-return-transaction/sales', {
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
    <form method="POST" action="{{ route('tax_return_transaction.addPercentage') }}">
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
                    <option :value="transaction.id" " x-text="transaction.contact_details.bus_name + ' - ' + transaction.inv_number + ' - ' + transaction.date"></option>
                </template>
            </select>
        </div>

        <!-- Hidden field to pass the taxReturn ID -->
        <input type="hidden" name="tax_return_id" value="{{ $taxReturn->id }}">

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
