<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Navigation Tabs -->
                    <nav class="flex space-x-4 mb-6 border-b border-gray-200 pb-2">
                        <a href="{{ route('tax_return.slsp_data', $taxReturn->id) }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('tax_return.slsp_data') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            SLSP Data
                        </a>
                        <a href="{{ route('summary') }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('summary') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            Summary
                        </a>
                        <a href="{{ route('tax_return.report', $taxReturn->id) }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('tax_return.report') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                           Report
                        </a>
                        <a href="{{ route('notes_activity') }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('notes_activity') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            Notes & Activity
                        </a>
                        <a href="{{ route('transactions') }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('transactions') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            Transactions
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
         this.selectedRows = {{ json_encode($taxReturn->transactions->pluck('id')->toArray()) }};

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
            fetch('/transactions/deactivate', {
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
                x-data 
                x-on:click="$dispatch('open-add-modal')" 
                class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
            >
                <i class="fa fa-plus-circle" aria-hidden="true"></i> Add
            </button>
  
            <button  
                x-data 
                x-on:click="$dispatch('open-import-modal')" 
                class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
            >
                <i class="fa-solid fa-file-import"></i> Import
            </button>
            <a href="{{ url('download_transactions') }}">
                <button
                    type="button"
                    class="border px-3 py-2 rounded-lg text-sm"
                > 
                    <i class="fa fa-download"></i> Download
                </button>
            </a>
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
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300" id="tableid">
            <thead class="border-b border-neutral-300 bg-slate-200 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                <tr>
                    <th scope="col" class="p-4"> <!-- Checkbox for selecting all -->
                        <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                            <div class="relative flex items-center">
                                <input type="checkbox" x-model="checkAll" id="checkAll" @change="toggleAll()" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </div>
                        </label>
                    </th>
                    <th scope="col" class="py-4 px-2">Contact</th>
                    <th scope="col" class="py-4 px-2">Invoice Number</th>
                    <th scope="col" class="py-4 px-2">Reference No.</th>
                    <th scope="col" class="py-4 px-2">Date</th>
                    <th scope="col" class="py-4 px-2">Description</th>
                    <th scope="col" class="py-4 px-2">Sales Amount</th>
                    <th scope="col" class="py-4 px-2">Tax Amount</th>
                    <th scope="col" class="py-4 px-2">Tax Type</th>
                    <th scope="col" class="py-4 px-2">ATC</th>
                    <th scope="col" class="py-4 px-2">COA</th>
                </tr>
            </thead>
        
            <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                @forelse ($transactions as $transaction) 
                    @foreach ($transaction->taxRows as $taxRow) 
                        <tr>
                            @if ($loop->first)   <!-- Only show transaction info on the first tax row -->
                            <td>
                                <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                    <input type="checkbox" @change="toggleCheckbox('{{ $transaction->id }}')" id="transaction{{ $transaction->id }}" 
                                        class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" 
                                        :checked="selectedRows.includes('{{ $transaction->id }}')" 
                                        x-show="showCheckboxes" 
                                        style="display: none;" 
                                        x-bind:style="showCheckboxes ? 'display: block;' : 'display: none;'" />
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" 
                                        class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </label>
                            </td>
                                <td rowspan="{{ $transaction->taxRows->count() }}">{{ $transaction->contact }}</td>
                                <td rowspan="{{ $transaction->taxRows->count() }}">{{ $transaction->inv_number }}</td>
                                <td rowspan="{{ $transaction->taxRows->count() }}">{{ $transaction->reference }}</td>
                                <td rowspan="{{ $transaction->taxRows->count() }}">{{ $transaction->date }}</td>
                            @endif
                            <td class="p-5">{{ $taxRow->description }}</td>
                            <td>{{ $taxRow->amount }}</td>
                            <td>{{ $taxRow->tax_amount }}</td>
                            <td>{{ $taxRow->tax_type }}</td>
                            <td>{{ $taxRow->tax_code }}</td>
                            <td>{{ $taxRow->coa }}</td>
                        </tr>
                    @endforeach
                @empty <!-- Check if there are no transactions -->
                    <tr>
                        <td colspan="12" class="text-center p-4">
                            <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                            <h1 class="font-extrabold text-lg mt-2">No Transactions yet</h1>
                            <p class="text-sm text-neutral-500 mt-2">Start adding transactions with the <br> + Add button.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    


<!-- Pagination Links -->


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

{{ $transactions->links() }} <!-- Display pagination links -->

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
