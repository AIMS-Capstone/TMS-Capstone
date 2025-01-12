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
                                    <div x-data="{ selectedTab: 'Archive Qap' }" class="w-full">
                                        <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 border-neutral-300" role="tablist" aria-label="tab options">
                                            <a href="{{ route('with_holding.1601EQ_Qap', ['id' => $withHolding->id]) }}">
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
                                            </a>
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
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <hr>

                            <div 
                            x-data="{
                                showCheckboxes: false, 
                                checkAll: false, 
                                selectedRows: [],
                                showDeleteCancelButtons: false,
                                showActivateCancelButtons: false, 
                                isDisabled: false,
                                activateMode: false,
                                deleteMode: false,
                                showConfirmActivateModal: false, 
                                showConfirmDeleteModal: false,

                                disableButtons() {
                                    this.isDisabled = true;
                                },

                                enableButtons() {
                                    this.isDisabled = false;
                                    this.showDeleteCancelButtons = false; 
                                    this.showActivateCancelButtons = false; 
                                    this.showConfirmActivateModal = false; 
                                    this.showConfirmDeleteModal = false;
                                },

                                toggleCheckbox(id) {
                                    if (this.selectedRows.includes(id)) {
                                        this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                                    } else {
                                        this.selectedRows.push(id);
                                    }
                                    this.checkAll = this.selectedRows.length === {{ json_encode($taxRows->count()) }};
                                },
                                toggleAll() {
                                    this.checkAll = !this.checkAll;
                                    if (this.checkAll) {
                                        this.selectedRows = {{ json_encode($taxRows->pluck('transaction.id')->toArray()) }}; 
                                    } else {
                                        this.selectedRows = [];
                                    }
                                },
                                activateRows() {
                                    if (this.selectedRows.length === 0) {
                                        alert('Select at least one item to activate.');
                                        return;
                                    }

                                    this.showConfirmActivateModal = true;
                                },
                                confirmActivation() {
                                    fetch('{{ route('with_holding.1601EQ_Qap.activate', ['id' => $withHolding->id]) }}', {
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
                                            alert('Failed to activate transactions.');
                                        }
                                    });
                                },
                                deleteRows() {
                                    if (this.selectedRows.length === 0) {
                                        alert('Select at least one item to delete.');
                                        return;
                                    }
                                    this.showConfirmDeleteModal = true;
                                },
                                confirmDeletion() {
                                    fetch('{{ route('with_holding.1601EQ_Qap.unassign', ['id' => $withHolding->id]) }}', {
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
                                            alert('Failed to delete transactions.');
                                        }
                                    });
                                },
                                activateModeToggle() {
                                    this.activateMode = true;
                                    this.deleteMode = false;
                                    this.disableDelete = true;
                                    this.disableActivate = false;
                                    this.showCheckboxes = true;
                                    this.selectedRows = [];
                                },
                                deleteModeToggle() {
                                    this.deleteMode = true;
                                    this.activateMode = false;
                                    this.disableActivate = true;
                                    this.disableDelete = false;
                                    this.showCheckboxes = true;
                                    this.selectedRows = [];
                                },
                                cancelSelection() {
                                    this.showCheckboxes = false;
                                    this.activateMode = false;
                                    this.deleteMode = false;
                                    this.disableActivate = false;
                                    this.disableDelete = false;
                                    this.selectedRows = [];
                                },
                                get canConfirm() {
                                return this.selectedRows.length > 0;
                                },
                                get selectedCount() {
                                    return this.selectedRows.length; 
                                }
                            }"
                            class="mb-12 mx-12 overflow-hidden max-w-full">

                            <div class="container mx-auto">
                                <div class="flex flex-row space-x-2 items-center justify-between">
                                    <div class="flex space-x-2 items-center">
                                        <!-- Search Row -->
                                        <div class="relative w-80 p-4">
                                            <form x-target="QAP" action="/1601EQ_Qap" role="search" aria-label="Table" autocomplete="off">
                                                <input 
                                                    type="search" 
                                                    name="search" 
                                                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900" 
                                                    aria-label="Search Term" 
                                                    placeholder="Search..." 
                                                    @input.debounce="$el.form.requestSubmit()" 
                                                    @search="$el.form.requestSubmit()"
                                                >
                                            </form>
                                            <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                        </div>
                                    </div>

                                    <div class="mx-auto space-x-4 pr-6">
                                        <!-- Activate Button -->
                                        <button 
                                            type="button"
                                            @click="activateModeToggle"
                                            :disabled="disableActivate"
                                            class="border px-3 py-2 rounded-lg text-sm text-blue-600 hover:border-blue-800 hover:text-blue-800 hover:bg-blue-100 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            Activate
                                        </button>
                                        <!-- Delete Button -->
                                        <button 
                                            type="button"
                                            @click="deleteModeToggle"
                                            :disabled="disableDelete"
                                            class="border px-3 py-2 rounded-lg text-sm text-red-600 hover:border-red-800 hover:text-red-800 hover:bg-red-100 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                            Delete
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

                                <div class="flex items-center space-x-4 mt-4" x-show="showCheckboxes">
                                    <button 
                                        @click="activateMode ? confirmActivation() : confirmDeletion()" 
                                        :disabled="!canConfirm"
                                        class="px-4 py-2 rounded-lg text-sm text-white"
                                        :class="activateMode ? 'bg-blue-600 hover:bg-blue-700' : 'bg-red-600 hover:bg-red-700'">
                                        <span x-text="activateMode ? 'Activate Selected' : 'Delete Selected'"></span>
                                    </button>
                                    <button @click="cancelSelection" class="px-4 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100">
                                        Cancel
                                    </button>
                                </div>

                                @if (count($taxRows) > 0)   
                                    {{ $taxRows->links('vendor.pagination.custom') }}
                                @endif

                                <!-- Modals -->
                                    <div 
                                        x-show="showConfirmActivateModal" 
                                        x-cloak 
                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                        @click.away="showConfirmActivateModal = false"
                                        x-effect="document.body.classList.toggle('overflow-hidden', showConfirmActivateModal)"
                                    >
                                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                                            <div class="flex flex-col items-center">
                                                <div class="mb-4">
                                                    <i class="fas fa-check-circle text-green-600 text-8xl"></i>
                                                </div>

                                                <h2 class="text-2xl font-bold text-zinc-700 mb-2">Activate Item(s)</h2>

                                                <p class="text-sm text-zinc-700 text-center">
                                                    You're about to activate the selected item(s) in the QAP Archive. Are you sure?
                                                </p>

                                                <div class="flex justify-center space-x-8 mt-6 w-full">
                                                    <button 
                                                        @click="showConfirmActivateModal = false"
                                                        class="px-4 py-2 rounded-lg text-sm text-zinc-700 font-bold transition">
                                                        Cancel
                                                    </button>
                                                    <button 
                                                        @click="activateRows(); showConfirmActivateModal = false;"
                                                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm transition">
                                                        Activate
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
                                            x-effect="document.body.classList.toggle('overflow-hidden', showConfirmDeleteModal)"
                                            @click.away="showConfirmDeleteModal = false"
                                        >
                                            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                                                <div class="flex flex-col items-center">
                                                    <!-- Icon -->
                                                    <div class="mb-4">
                                                        <i class="fas fa-exclamation-triangle text-red-600 text-8xl"></i>
                                                    </div>

                                                    <!-- Title -->
                                                    <h2 class="text-2xl font-extrabold text-zinc-800 mb-2">Delete COA</h2>

                                                    <!-- Description -->
                                                    <p class="text-sm text-zinc-600 text-center">
                                                        You're going to delete the selected item(s) in the COA Archive table. Are you sure?
                                                    </p>

                                                    <!-- Actions -->
                                                    <div class="flex justify-center space-x-8 mt-6 w-full">
                                                        <button 
                                                            @click="showConfirmDeleteModal = false; enableButtons(); enableButtons(); showDeleteCancelButtons = true; disableButtons();" 
                                                            class="px-4 py-2 rounded-lg text-sm text-zinc-600 hover:text-zinc-900 font-bold transition"
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
                                            <div class="flex justify-center py-4" x-show="showDeleteCancelButtons">
                                                <button 
                                                    type="button" 
                                                    @click="showConfirmDeleteModal = true; showDeleteCancelButtons = true;"
                                                    :disabled="selectedRows.length === 0"
                                                    class="border px-3 py-2 mx-2 rounded-lg text-sm text-red-600 border-red-600 bg-red-100 hover:bg-red-200 transition disabled:opacity-50 disabled:cursor-not-allowed group flex items-center space-x-2"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24">
                                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/>
                                                    </svg>
                                                    <span class="text-red-600 transition group-hover:text-red-600">Delete Selected <span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span></span>
                                                </button>
                                                <button 
                                                    @click="cancelSelection(); enableButtons();" 
                                                    class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"
                                                >
                                                    Cancel
                                                </button>
                                            </div>
                                            <!-- Unarchive and Cancel buttons -->
                                            <div class="flex justify-center py-4" x-show="showRestoreCancelButtons">
                                                <button 
                                                    type="button" 
                                                    @click="showConfirmActivateModal = true"
                                                    :disabled="selectedRows.length === 0"
                                                    class="border px-3 py-2 rounded-lg text-sm text-blue-600 hover:border-blue-800 hover:text-blue-800 hover:bg-blue-100 transition disabled:opacity-50 disabled:cursor-not-allowed">
                                                    Activate Selected (<span x-text="selectedCount"></span>)
                                                </button>
                                                <button @click="cancelSelection(); enableButtons();" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                    </div>
            </div>
        </div>
    </div>

    <script>
        activateRows() {
            if (this.selectedRows.length === 0) {
                alert('No rows selected for activation.');
                return;
            }

            fetch(`/${withHolding.id}/1601EQ_Qap/activate`, {
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
                    alert('Failed to activate transactions.');
                }
            });
        }
    </script>
</x-app-layout>
