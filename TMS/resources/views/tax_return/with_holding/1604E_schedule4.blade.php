<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-lg font-semibold text-gray-700">Withholding Tax Return > 1604E > Sources</h1>
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
                                <form x-target="payees" action="/1604E_schedule4" role="search" aria-label="Table" autocomplete="off">
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

                                <!-- Add Payees Button -->
                                <button 
                                    x-data 
                                    x-on:click="$dispatch('open-add-payees-modal')" 
                                    class="border px-3 py-2 rounded-lg text-sm hover:border-green-500 hover:text-green-500 transition"
                                >
                                    <i class="fa fa-plus-circle"></i> Add Payees
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

                <!-- Tabs -->
                <div class="px-6 py-4 flex space-x-4 border-b">
                    <a href="{{ route('with_holding.1604E_summary', ['id' => $withHolding->id]) }}" class="pb-2 text-blue-500 border-b-2 border-blue-500 font-semibold">Summary</a>
                    <a href="{{ route('with_holding.1604E_remittances', ['id' => $withHolding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Remittance</a>
                    <a href="{{ route('with_holding.1604E_sources', ['id' => $withHolding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Sources</a>
                    <a href="{{ route('form1604E.create', ['id' => $withHolding->id]) }}" class="pb-2 text-gray-500 hover:text-blue-500">Form</a>
                </div>

                <div class="flex flex-row justify-center items-center space-x-4">
                    <div>
                        <a href="{{ route('with_holding.1604E_sources', ['id' => $withHolding->id]) }}"><p>Schedule 3</p></a>
                    </div>
                    <div class="flex border-b-8 border-sky-900">
                        <a href="{{ route('with_holding.1604E_schedule4', ['id' => $withHolding->id]) }}"><p>Schedule 4</p></a>
                    </div>
                </div>

                <div class="px-6 py-4">
                    <div class=" bg-gray-50 p-4 rounded-md">
                        <table class="min-w-full divide-y divide-gray-300" id = "payees">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Vendor</th>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">ATC</th>
                                    <th class="py-2 px-4 text-left text-sm font-medium text-gray-700">Nature of Income Payment</th>
                                    <th class="py-2 px-4 text-right text-sm font-medium text-gray-700">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($payees as $payee)
                                    <tr>
                                        <td class="py-2 px-4">
                                            {{ $payee->contact->bus_name ?? 'N/A' }}<br>
                                            {{ $payee->contact->contact_address ?? 'N/A' }}<br>
                                            {{ $payee->contact->contact_tin ?? 'N/A' }}
                                        </td>
                                        <td class="py-2 px-4">{{ $payee->atc->tax_code ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">Default Income Tax</td>
                                        <td class="py-2 px-4 text-right">{{ number_format($payee->amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500">No data available for this schedule.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                        <div class="mt-4">
                            {{ $payees->links('vendor.pagination.custom') }}
                        </div>
                </div>

                <!-- Action Buttons --> 
                    <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                        <button @click="deleteRows" class="border px-3 py-2 mx-2 rounded-lg text-sm text-red-600 hover:bg-red-100 transition">
                            <i class="fa fa-trash"></i> Delete Selected <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                        </button>
                        <button @click="cancelSelection" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div x-data="addPayeesModal()" @open-add-payees-modal.window="open = true">
        <div 
            x-show="open" 
            class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center"
            x-cloak
        >
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full">
                <h2 class="text-lg font-semibold mb-4">Add New Payees</h2>

                <!-- Form -->
                <form @submit.prevent="submit">
                    <template x-for="(payee, index) in payees" :key="index">
                        <div class="grid grid-cols-4 gap-4 mb-4">
                            <!-- Payee -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Payee</label>
                                <select 
                                    x-model="payee.contact_id" 
                                    @change="filterAtcs(index)" 
                                    class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="" disabled>Select Payee</option>
                                    @foreach ($contacts as $contact)
                                        <option value="{{ $contact->id }}">{{ $contact->bus_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- ATC -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ATC</label>
                                <select 
                                    x-model="payee.atc_id" 
                                    class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="" disabled>Select ATC</option>
                                    <template x-for="atc in filteredAtcs[index]" :key="atc.id">
                                        <option :value="atc.id" x-text="atc.tax_code"></option>
                                    </template>
                                </select>
                            </div>

                            <!-- Amount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Amount</label>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    x-model="payee.amount" 
                                    class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm"
                                    required
                                />
                            </div>

                            <!-- Remove Button -->
                            <div class="flex items-center justify-center">
                                <button 
                                    type="button" 
                                    @click="removePayee(index)" 
                                    class="text-red-500 hover:underline"
                                    x-show="payees.length > 1"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Add New Line Button -->
                    <button 
                        type="button" 
                        @click="addPayee" 
                        class="text-blue-500 hover:underline mb-4" 
                        x-show="payees.length < 5"
                    >
                        + Add New Line
                    </button>

                    <!-- Action Buttons -->
                    <div class="flex justify-end mt-4">
                        <button type="button" @click="open = false" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2 px-4 rounded-lg mr-4">Cancel</button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg">Add Payees</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
       function addPayeesModal() {
        return {
            open: false,
            payees: [{ contact_id: '', atc_id: '', amount: '' }],
            filteredAtcs: [],

            // Preload contact-to-ATC mapping
            contacts: @json($contacts->mapWithKeys(function ($contact) {
                return [
                    $contact->id => $contact->atcs->map(function ($atc) {
                        return ['id' => $atc->id, 'tax_code' => $atc->tax_code];
                    }),
                ];
            })),

            addPayee() {
                if (this.payees.length < 5) {
                    this.payees.push({ contact_id: '', atc_id: '', amount: '' });
                    this.filteredAtcs.push([]); // Add an empty array for filtering ATCs
                }
            },

            removePayee(index) {
                if (this.payees.length > 1) {
                    this.payees.splice(index, 1);
                    this.filteredAtcs.splice(index, 1);
                }
            },

            filterAtcs(index) {
                const contactId = this.payees[index].contact_id;
                this.filteredAtcs[index] = this.contacts[contactId] || [];
            },

            submit() {
                fetch('{{ route('with_holding.1604E_store', ['id' => $withHolding->id]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ payees: this.payees }),
                })
                .then((response) => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Error adding payees.');
                    }
                });
            },
        };
    }

    </script>
</x-app-layout>
