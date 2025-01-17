<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-10 py-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">
                            Withholding Tax Return
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ route('with_holding.1601EQ') }}" 
                                        class="ms-1 text-sm font-medium {{ Request::routeIs('with_holding.1601EQ') ? 'font-extrabold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                        1601EQ
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="" class="ms-1 text-sm font-bold text-blue-900 md:ms-2">QAP</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
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
                        showSuccessUnarchiveModal: false,
                        showSuccessDeleteModal: false,

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
                            .then(response => {
                                if (response.ok) {
                                    this.showSuccessDeleteModal = true;
                                    this.selectedRows = [];
                                    this.checkAll = false;
                                    setTimeout(() => {
                                        location.reload();
                                    }, 700);
                                } else {
                                    alert('Error deleting rows.');
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
                            .then(response => {
                                if (response.ok) {
                                    this.showSuccessUnarchiveModal = true;
                                    this.selectedRows = [];
                                    this.checkAll = false;
                                    setTimeout(() => {
                                        location.reload();
                                    }, 700);
                                } else {
                                    alert('Error restoring rows.');
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
                    }" class="container mx-auto">

                    <div class="flex flex-row space-x-2 items-center justify-between">
                        <div class="flex flex-row space-x-2 items-center ps-6">
                            <!-- Search Row -->
                            <div class="w-80 p-5">
                                <form x-target="QAP" action="/tax_return/with_holding/{{ $withHolding->id }}/1601EQ_Qap/archive" role="search" method="GET" aria-label="Table" autocomplete="off">
                                    <input 
                                        type="search" 
                                        name="search"
                                        class="w-full pl-10 pr-4 py-[7px] text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-900 focus:border-blue-900" 
                                        aria-label="Search Term" 
                                        placeholder="Search..." 
                                        @input.debounce="$el.form.requestSubmit()" 
                                        @search="$el.form.requestSubmit()"
                                    >
                                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-zinc-400"></i>
                                </form>
                            </div>
                            <!-- Sort by dropdown -->
                            <div class="relative inline-block text-left min-w-[150px]">
                                <button id="sortButton" class="flex items-center text-zinc-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                                    </svg>
                                    <span id="selectedOption" class="font-normal text-sm text-zinc-600 hover:text-zinc-800 truncate">Sort by</span>
                                    <svg class="w-2.5 h-2.5 ms-2 transition-transform duration-300" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m1 1 4 4 4-4"/></svg>
                                </button>
                                <div id="dropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white hidden z-50">
                                    <div class="py-2 px-2">
                                        <span class="block px-4 py-2 text-sm font-bold text-zinc-700">Sort by</span>
                                        <div data-sort="recently-added" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Added</div>
                                        <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                        <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex space-x-4 items-center pr-10 ml-auto">
                            <!-- Activate Button -->
                            <button type="button" @click="activateModeToggle" :disabled="disableActivate"
                                class="border px-3 py-2 rounded-lg text-sm text-gray-600 hover:border-gray-800 hover:text-gray-800 hover:bg-zinc-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-zinc-500" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M3 10H2V4.003C2 3.449 2.455 3 2.992 3h18.016A.99.99 0 0 1 22 4.003V10h-1v10.002a.996.996 0 0 1-.993.998H3.993A.996.996 0 0 1 3 20.002zm16 0H5v9h14zM4 5v3h16V5zm5 7h6v2H9z"/>
                                </svg>
                                <span class="text-zinc-600 transition group-hover:text-zinc-500">Activate</span>
                            </button>
                            <!-- Delete Button -->
                            <button type="button" @click="deleteModeToggle" :disabled="disableDelete"
                                class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-red-800 hover:text-red-800 hover:bg-red-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                <span class="text-zinc-600 transition group-hover:text-red-500">Delete</span>
                            </button>
                            <div class="relative inline-block space-x-4 text-left sm:w-auto">
                                <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" class="flex items-center text-zinc-500 hover:text-zinc-700" type="button">
                                    <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                        <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                    </svg>
                                </button>
                                <div id="dropdownDots" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-44 origin-top-right">
                                    <div class="py-2 px-2 text-sm text-zinc-700" aria-labelledby="dropdownMenuIconButton">
                                        <span class="block px-4 py-2 text-sm font-bold text-zinc-700 text-left">Show Entries</span>
                                        <div onclick="setEntries(5)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">5 per page</div>
                                        <div onclick="setEntries(25)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">25 per page</div>
                                        <div onclick="setEntries(50)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">50 per page</div>
                                        <div onclick="setEntries(100)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">100 per page</div>
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Tabs -->
                    <div class="px-8 ps-10">
                        <!-- Navigation Tabs -->
                        <nav class="flex space-x-4 my-4">
                            <a href="{{ route('with_holding.1601EQ_Qap.archive', ['id' => $withHolding->id]) }}" class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('with_holding.1601EQ_Qap.archive') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                                QAP Data
                            </a>
                            <a href="{{ route('form1601EQ.create', ['id' => $withHolding->id]) }}" class="text-zinc-600 flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('form1601EQ.create') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                                Report
                            </a>
                        </nav>
                    </div>

                    <hr>

                    <div class="container mx-auto pt-2 overflow-hidden">
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
                                                <span class="block">QAP</span>
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
                                                <span class="block">Archive QAP</span>
                                                <span
                                                    :class="selectedTab === 'Archive Qap' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                                </span>
                                            </button>
                                    </div>
                                </div>  
                            </div>
                        </div>

                        <hr>

                        <div class="mb-12 mt-6 mx-12 overflow-hidden max-w-full border-neutral-300">
                            <div class="overflow-x-auto">
                                <table class="w-full items-start text-left text-sm text-neutral-600" id="QAP">
                                    <thead class="bg-neutral-100 text-sm text-neutral-700">
                                        <tr>
                                            <th class="py-2 px-4">
                                                <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer">
                                                    <input type="checkbox" x-model="checkAll" id="checkAll" @click="toggleAll()" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-[''] checked:before:text-white checked:before:text-center focus:outline-none transition"/>
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
                                                        <input type="checkbox" @click="toggleCheckbox('{{ $taxRow->transaction->id }}')" :checked="selectedRows.includes('{{ $taxRow->transaction->id }}')" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-[''] checked:before:text-white checked:before:text-center focus:outline-none transition"/>
                                                    </label>
                                                </td>
                                                <td class="py-4 px-4">{{ optional($taxRow->transaction->contactDetails)->bus_name ?? 'N/A' }}</td>
                                                <td class="py-4 px-4">{{ $taxRow->description ?? 'N/A' }}</td>
                                                <td class="py-4 px-4">{{ $taxRow->transaction->reference ?? 'N/A' }}</td>
                                                <td class="py-4 px-4">{{ optional($taxRow->atc)->tax_code ?? 'N/A' }}</td>
                                                <td class="py-4 px-4">{{ \Carbon\Carbon::parse($taxRow->transaction->date ?? now())->format('d/m/Y') }}</td>
                                                <td class="py-4 px-4">{{ number_format($taxRow->transaction->total_amount ?? 0, 2) }}</td>
                                            </tr>
                                        @endforeach
                                        @if ($taxRows->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center p-4">
                                                    <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                    <h1 class="font-bold text-lg mt-2">No Archived QAP Data yet</h1>
                                                    <p class="text-sm text-neutral-500 mt-2">Archived QAP Data will appear here for<br />safekeeping allowing you to manage inactive or<br />unused QAP Data.</p>
                                                </td>
                                            </tr>
                                        @else
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        
                            {{-- <div class="flex justify-center py-4" x-cloak>
                                <button x-show="showDeleteCancelButtons" @click="deactivate"
                                    class="bg-red-500 text-white px-4 py-2 rounded">
                                    Deactivate Selected (<span x-text="selectedCount"></span>)
                                </button>
                                <button x-show="showDeleteCancelButtons" @click="cancelSelection"
                                    class="bg-gray-500 text-white px-4 py-2 rounded">
                                    Cancel
                                </button>
                            </div> --}}

                            <div class="flex justify-center py-4" x-show="showCheckboxes">
                                <button 
                                    @click="activateMode ? confirmActivation() : confirmDeletion()" 
                                    :disabled="!canConfirm"
                                    class="px-4 py-2 rounded-lg text-sm"
                                    :class="activateMode ? 'border px-3 py-2 rounded-lg text-sm text-gray-800 border-gray-800 bg-zinc-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group' 
                                    : 'border px-3 py-2 mx-2 rounded-lg text-sm text-red-600 border-red-600 bg-red-100 hover:bg-red-200 transition disabled:opacity-50 disabled:cursor-not-allowed group flex items-center space-x-2'">
                                    <span x-text="activateMode ? 'Activate Selected' : 'Delete Selected'"></span>
                                </button>
                                <button @click="cancelSelection" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                                    Cancel
                                </button>
                            </div>

                            @if (count($taxRows) > 0)   
                                {{ $taxRows->links('vendor.pagination.custom') }}
                            @endif
                        </div>

                        <!-- Modals -->
                        <div x-show="showConfirmActivateModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                            @click.away="showConfirmActivateModal = false"
                            x-effect="document.body.classList.toggle('overflow-hidden', showConfirmActivateModal)">
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

                        <!-- Success Unarchive Modal -->
                                        <div 
                                            x-show="showSuccessUnarchiveModal" 
                                            x-cloak 
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                            x-effect="document.body.classList.toggle('overflow-hidden', showSuccessUnarchiveModal)"
                                            @click.away="showSuccessUnarchiveModal = false"
                                        >
                                            <div class="bg-zinc-200 rounded-lg shadow-lg p-6 max-w-sm w-full">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                                                    <h2 class="text-2xl font-bold text-zinc-700 mb-2">Unarchived Successful!</h2>
                                                    <p class="text-sm text-zinc-700 text-center">
                                                        The selected items have been successfully unarchived.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Success Delete Modal -->
                                        <div 
                                            x-show="showSuccessDeleteModal" 
                                            x-cloak 
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                            x-effect="document.body.classList.toggle('overflow-hidden', showSuccessDeleteModal)"
                                            @click.away="showSuccessDeleteModal = false"
                                        >
                                            <div class="bg-zinc-200 rounded-lg shadow-lg p-6 max-w-sm w-full">
                                                <div class="flex flex-col items-center">
                                                    <i class="fas fa-check-circle text-red-500 text-6xl mb-4"></i>
                                                    <h2 class="text-2xl font-bold text-zinc-700 mb-2">Deletion Successful!</h2>
                                                    <p class="text-sm text-zinc-700 text-center">
                                                        The selected items have been permanently deleted.
                                                    </p>
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
                                <button type="button" @click="showConfirmActivateModal = true" :disabled="selectedRows.length === 0"
                                    class="border px-3 py-2 rounded-lg text-sm text-gray-800 border-gray-800 bg-zinc-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-zinc-800" viewBox="0 0 24 24">
                                            <path fill="currentColor" d="M3 10H2V4.003C2 3.449 2.455 3 2.992 3h18.016A.99.99 0 0 1 22 4.003V10h-1v10.002a.996.996 0 0 1-.993.998H3.993A.996.996 0 0 1 3 20.002zm16 0H5v9h14zM4 5v3h16V5zm5 7h6v2H9z"/>
                                        </svg>
                                        <span class="text-zinc-600 transition group-hover:text-zinc-800">Unarchive Selected</span><span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
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

    // FOR SORT BUTTON
    document.getElementById('sortButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownMenu');
        const dropdownArrow = this.querySelector('svg:nth-child(3)');
        dropdown.classList.toggle('hidden');
        dropdownArrow.classList.toggle('rotate-180');
    });

    // FOR SORT BY
    function sortItems(criteria) {
        const table = document.querySelector('table tbody');
        const rows = Array.from(table.querySelectorAll('tr')).filter(row => row.querySelector('td')); // Filter rows with data
        let sortedRows;

        if (criteria === 'recently-added') {
            // Sort by the order of rows (assuming they are in the order of addition)
            sortedRows = rows.reverse();
        } else {
            // Sort by text content of the 'Contact' column
            sortedRows = rows.sort((a, b) => {
                const aText = a.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();
                const bText = b.querySelector('td:nth-child(2)').textContent.trim().toLowerCase();

                if (criteria === 'ascending') {
                    return aText.localeCompare(bText);
                } else if (criteria === 'descending') {
                    return bText.localeCompare(aText);
                }
            });
        }
        table.innerHTML = '';
        sortedRows.forEach(row => table.appendChild(row));
    }
    document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
        item.addEventListener('click', function() {
            const criteria = this.getAttribute('data-sort');
            document.getElementById('selectedOption').textContent = this.textContent; // Update selected option text
            sortItems(criteria);
        });
    });
    window.addEventListener('click', (event) => {
        if (!sortButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });

    // FOR BUTTON OF SHOW ENTRIES
    document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownDots');
        dropdown.classList.toggle('hidden');
    });
    // FOR SHOWING/SETTING ENTRIES
    function setEntries(entries) {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = "{{ route('with_holding.1601EQ_Qap.archive', ['id' => $withHolding->id]) }}";

        // Retrieve selectedType from URL parameter as a fallback
        const urlParams = new URLSearchParams(window.location.search);
        const selectedTab = urlParams.get('type') || 'Archive Qap'; // Default to 'Qap' if not found

        // Create hidden inputs
        const inputs = [
            { name: 'perPage', value: entries },
            { name: 'search', value: document.querySelector('input[name="search"]')?.value || '' },
            { name: 'type', value: selectedTab }
        ];

        inputs.forEach(input => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = input.name;
            hiddenInput.value = input.value;
            form.appendChild(hiddenInput);
        });

        document.body.appendChild(form);
        form.submit();
    }
    </script>
</x-app-layout>
