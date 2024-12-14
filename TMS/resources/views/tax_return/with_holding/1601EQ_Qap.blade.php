<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-lg font-semibold text-gray-700">Withholding Tax Return > 1601EQ > QAP</h1>
                </div>

                <!-- Tabs -->
                <div class="px-6 py-4 flex space-x-4 border-b">
                    <a href="{{ route('with_holding.1601EQ_Qap', ['id' => $withHolding->id]) }}" class="pb-2 text-blue-500 border-b-2 border-blue-500 font-semibold">QAP</a>
                    <a href="{{ route('form1601EQ.create', ['id' => $withHolding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Form</a>
                </div>

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
                        this.selectedRows = {{ json_encode($withHolding->pluck('id')->toArray()) }}; 
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
                            fetch('/tax_return/with_holding/1604E/destroy', {
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
                                <form x-target="transaction" action="/1604E_schedule4" role="search" aria-label="Table" autocomplete="off">
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

                                <!-- Add Transactions Button -->
                                <button 
                                    x-data 
                                    x-on:click="$dispatch('open-add-transaction-modal')" 
                                    class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
                                >
                                    <i class="fa fa-plus-circle"></i> Add Transaction
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
                    </div>

                    <div class="px-6 py-4">
                        <!-- Transactions Table -->
                        <div class="bg-gray-50 p-4 rounded-md">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Vendor</th>
                                        <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Description</th>
                                        <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Reference</th>
                                        <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">ATC</th>
                                        <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Date</th>
                                        <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Amount</th>
                                        <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Withheld</th>
                                        <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Tax Rate</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($transactions as $transaction)
                                        <tr>
                                            <td class="py-2 px-4">
                                                {{ $transaction->contactDetails->bus_name ?? 'N/A' }}<br>
                                                {{ $transaction->contactDetails->contact_address ?? 'N/A' }}<br>
                                                {{ $transaction->contactDetails->contact_tin ?? 'N/A' }}
                                            </td>
                                            <td class="py-2 px-4">{{ $transaction->transaction_type ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ $transaction->reference }}</td>
                                            <td class="py-2 px-4">{{ $transaction->withholding->tax_code ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($transaction->date)->format('d/m/Y') }}</td>
                                            <td class="py-2 px-4 text-right">{{ number_format($transaction->total_amount, 2) }}</td>
                                            <td class="py-2 px-4 text-right">{{ number_format($transaction->total_amount * 0.1, 2) }}</td> <!-- Assuming withheld is 10% -->
                                            <td class="py-2 px-4 text-right">10%</td> <!-- Hardcoded tax rate -->
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="py-4 text-center text-gray-500">No transactions associated with this withholding ID.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $transactions->links('vendor.pagination.custom') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Add Transactions Modal -->
    <div x-data="addTransactionModal()" @open-add-transaction-modal.window="open = true">
        <div 
            x-show="open" 
            class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center"
            x-cloak
        >
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
                <h2 class="text-lg font-semibold mb-4">Add Transaction</h2>

                <!-- Modal Form -->
                <form @submit.prevent="submit">
                    <template x-for="(item, index) in transactions" :key="index">
                        <div class="grid grid-cols-2 gap-20 mb-4">
                            <!-- Transaction -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Transaction</label>
                                <select 
                                    x-model="item.transaction_id" 
                                    class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="" disabled>Select Transaction</option>
                                    @foreach ($unassignedTransactions as $transaction)
                                        <option value="{{ $transaction->id }}">{{ $transaction->contactDetails->bus_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Remove Button -->
                            <div class="flex items-center justify-center">
                                <button 
                                    type="button" 
                                    @click="removeTransaction(index)" 
                                    x-show="transactions.length > 1 && transactions.length >= 2"
                                    class="text-red-500 hover:underline"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Add New Line -->
                    <button 
                        type="button" 
                        @click="addTransaction" 
                        class="text-blue-500 hover:underline mb-4" 
                        x-show="transactions.length < 5"
                    >
                        + Add New Line
                    </button>

                    <!-- Action Buttons -->
                    <div class="flex justify-end">
                        <button type="button" @click="open = false" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md mr-4">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Set</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addTransactionModal() {
            return {
                open: false,
                transactions: [{ transaction_id: '' }], // Initialize with 1 row

                addTransaction() {
                    if (this.transactions.length < 5) {
                        this.transactions.push({ transaction_id: '' });
                    } else {
                        alert("You can only add up to 5 transactions.");
                    }
                },

                removeTransaction(index) {
                    if (this.transactions.length > 1) {
                        this.transactions.splice(index, 1);
                    }
                },

                submit() {
                    fetch('{{ route('with_holding.1601EQ_Qap.set', ['id' => $withHolding->id]) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ transactions: this.transactions }),
                    })
                    .then((response) => {
                        if (response.ok) {
                            location.reload();
                        } else {
                            alert('Error assigning transactions.');
                        }
                    });
                },
            };
        }

    </script>

</x-app-layout>
