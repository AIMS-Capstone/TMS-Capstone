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

                        <!-- Second Header -->
                            <div class="container mx-auto ps-8">
                                <div class="flex flex-row space-x-2 items-center justify-center">
                                    <div x-data="{ selectedTab: 'Qap' }" class="w-full">
                                        <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 border-neutral-300" role="tablist" aria-label="tab options">
                                            <button 
                                                @click="selectedTab = 'Qap'" 
                                                :aria-selected="selectedTab === 'Qap'" 
                                                :tabindex="selectedTab === 'Qap' ? '0' : '-1'" 
                                                :class="selectedTab === 'Qap' ? 'font-bold text-blue-900 ' : 'text-neutral-600 font-medium hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                                class="h-min py-2 text-base relative" 
                                                type="button" 
                                                role="tab" 
                                                aria-controls="tabpanelQap">
                                                <span class="block">Qap</span>
                                                <span 
                                                    :class="selectedTab === 'Qap' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                                </span>
                                            </button>
                                            <a href="{{ route('with_holding.1601EQ_Qap.archive', ['id' => $withHolding->id]) }}">
                                                <button @click="selectedTab = 'Archive Qap'" :aria-selected="selectedTab === 'Archive Qap'" 
                                                    :tabindex="selectedTab === 'Archive Qap' ? '0' : '-1'" 
                                                    :class="selectedTab === 'Archive Qap' ? 'font-bold text-blue-900' : 'text-neutral-600 font-medium hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'"
                                                    class="h-min py-2 text-base relative" 
                                                    type="button" 
                                                    role="tab" 
                                                    aria-controls="tabpanelArchive Qap" >
                                                    <span class="block">Archive Qap</span>
                                                    <span
                                                        :class="selectedTab === 'Archive Qap' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                                    </span>
                                                </button>
                                            </a>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        <hr>

                    <!-- Krazy table interaction -->
                    <div class="p-6" x-data="{
                        showCheckboxes: false,
                        checkAll: false,
                        selectedRows: [],
                        showDeleteCancelButtons: false,
                        showConfirmArchiveModal: false,
                        
                        toggleCheckbox(id) {
                            if (this.selectedRows.includes(id)) {
                                this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                            } else {
                                this.selectedRows.push(id);
                            }
                        },
                        
                        toggleAll() {
                            this.checkAll = !this.checkAll;
                            if (this.checkAll) {
                                this.selectedRows = @json($taxRows->pluck('transaction.id'));
                            } else {
                                this.selectedRows = [];
                            }
                        },
                        
                        deactivate() {
                            if (this.selectedRows.length === 0) {
                                alert('No rows selected for deactivation.');
                                return;
                            }
                            this.showConfirmArchiveModal = true;
                        },
                        
                        confirmDeactivation() {
                            fetch('{{ route('with_holding.1601EQ_Qap.deactivate', ['id' => $withHolding->id]) }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ ids: this.selectedRows })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alert('Failed to deactivate transactions.');
                                }
                            });
                        },
                        
                        cancelSelection() {
                            this.selectedRows = [];
                            this.showCheckboxes = false;
                            this.showDeleteCancelButtons = false;
                        },
                        
                        get selectedCount() {
                            return this.selectedRows.length;
                        }
                    }">

                    <!-- Fourth Header -->
                    <div class="container mx-auto">
                        <div class="flex flex-row space-x-2 items-center justify-between">
                            <!-- Search row -->
                            <div class="relative w-80 p-4">
                                <form x-target="QAP" action="/1601EQ_Qap" role="search" aria-label="Table" autocomplete="off">
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
                                    @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons; $el.disabled = true;" 
                                    :disabled="selectedRows.length === 1"
                                    class="border px-3 py-2 rounded-lg text-sm text-gray-600 hover:border-gray-800 hover:text-gray-800 hover:bg-zinc-100 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fa fa-trash"></i> Deactivate
                                </button>

                                <button type="button">
                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                        <div class="overflow-x-auto">
                            <table class="w-full items-start text-left text-sm text-neutral-600" id="QAP">
                                <thead class="bg-neutral-100 text-sm text-neutral-700">
                                    <tr>
                                        <th class="py-2 px-4">
                                            <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer">
                                                <input type="checkbox" x-model="checkAll" id="checkAll" @click="toggleAll()" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition"/>
                                            </label>
                                        </th>
                                        <th scope="col" class="py-4 px-4">Vendor</th>
                                        <th scope="col" class="py-4 px-4">Description</th>
                                        <th scope="col" class="py-4 px-4">Reference</th>
                                        <th scope="col" class="py-4 px-4">ATC</th>
                                        <th scope="col" class="py-4 px-4">Date</th>
                                        <th scope="col" class="py-4 px-4">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-300">
                                    @foreach ($taxRows as $taxRow)
                                        <tr class="hover:bg-blue-50">
                                            <td class="p-4">
                                                <label x-show="showCheckboxes" class="flex items-center cursor-pointer">
                                                    <input type="checkbox" @click="toggleCheckbox('{{ $taxRow->transaction->id }}')" :checked="selectedRows.includes('{{ $taxRow->transaction->id }}')" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition"/>
                                                </label>
                                            </td>
                                            <td class="py-2 px-4">{{ optional($taxRow->transaction->contactDetails)->bus_name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ $taxRow->description ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ $taxRow->transaction->reference ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ optional($taxRow->atc)->tax_code ?? 'N/A' }}</td>
                                            <td class="py-2 px-4">{{ \Carbon\Carbon::parse($taxRow->transaction->date ?? now())->format('d/m/Y') }}</td>
                                            <td class="py-2 px-4">{{ number_format($taxRow->transaction->total_amount ?? 0, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex justify-center mb-4 py-6 space-x-8" x-cloak>
                            <button x-show="showDeleteCancelButtons" @click="deactivate"
                                class="bg-red-500 text-white px-4 py-2 rounded">
                                Deactivate Selected (<span x-text="selectedCount"></span>)
                            </button>
                            <button x-show="showDeleteCancelButtons" @click="cancelSelection"
                                class="bg-gray-500 text-white px-4 py-2 rounded">
                                Cancel
                            </button>
                        </div>

                        @if (count($taxRows) > 0)   
                            {{ $taxRows->links('vendor.pagination.custom') }}
                        @endif

                        <!-- Confirmation Modal -->
                        <div x-show="showConfirmArchiveModal" x-cloak
                            class="fixed inset-0 bg-opacity-50 flex items-center justify-center">
                            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                                <h2 class="text-xl font-bold mb-4">Confirm Deactivation</h2>
                                <p>Are you sure you want to deactivate the selected transactions?</p>
                                <div class="flex justify-end space-x-4 mt-6">
                                    <button @click="showConfirmArchiveModal = false"
                                        class="px-4 py-2 bg-gray-300 rounded">
                                        Cancel
                                    </button>
                                    <button @click="confirmDeactivation"
                                        class="px-4 py-2 bg-red-500 text-white rounded">
                                        Confirm
                                    </button>
                                </div>
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
