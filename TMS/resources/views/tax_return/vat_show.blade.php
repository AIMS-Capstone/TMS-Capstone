<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg">
            {{-- Breadcrumbs --}}
            <div class="px-10 py-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center text-sm font-normal text-zinc-500">
                        {{-- <a href="{{ route('vat_return') }}" class="inline-flex items-center text-sm font-normal text-zinc-500">
                            
                        </a> --}}
                        Value Added Tax Return
                        </li>
                        <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('vat_return') }}" 
                                class="ms-1 text-sm font-medium {{ Request::routeIs('vat_return') ? 'font-bold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                2550M/Q
                            </a>
                        </div>
                        </li>
                        <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="" class="ms-1 text-sm font-bold text-blue-900 md:ms-2">SLSP Data</a>
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
                showConfirmDeleteModal: false,

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
                ,
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
            class="container mx-auto">
                <div class="flex flex-row space-x-2 items-center justify-between">
                    <!-- Search row -->
                    <div class="flex flex-row space-x-2 items-center ps-6">
                        <div class="relative w-80 p-4">
                            <form 
                            x-data="{
                                search: '{{ request('search', '') }}',
                                type: '{{ request('type', 'sales') }}',
                                perPage: {{ request('perPage', 10) }},
                                updateSearch() {
                                    this.$refs.searchForm.submit();
                                }
                            }"
                            x-ref="searchForm"
                            action="{{ route('tax_return.slsp_data', $taxReturn->id) }}" 
                            method="GET" 
                            role="search" 
                            aria-label="Table" 
                            autocomplete="off"
                        >
                            <input 
                                type="search" 
                                name="search" 
                                x-model="search"
                                x-on:input.debounce.500ms="updateSearch"
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-900 focus:border-blue-900" 
                                aria-label="Search Term" 
                                placeholder="Search..." 
                            >
                            
                            <input type="hidden" name="type" x-model="type">
                            <input type="hidden" name="perPage" x-model="perPage">
                        </form>
                            
                            <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        <!-- Sort by dropdown -->
                        <div class="relative inline-block text-left min-w-[150px]">
                            <button id="sortButton" class="flex items-center text-zinc-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                    <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                                </svg>
                                <span id="selectedOption" class="font-normal text-md text-zinc-700 truncate">Sort by</span>
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
                        <button 
                            type="button"
                            x-data="{}" 
                            x-on:click="$dispatch('open-generate-modal', { year: '{{ $taxReturn->year }}', monthOrQuarter: '{{ $taxReturn->month }}' })" 
                            class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-green-500" viewBox="0 0 256 256"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"><circle cx="128" cy="128" r="112"/><path d="M 79.999992,128 H 176.0001"/><path d="m 128.00004,79.99995 v 96.0001"/></g></svg>
                            <span class="text-zinc-600 transition group-hover:text-green-500">Add Transaction</span>
                        </button>
                        <button 
                            type="button" 
                            @click="showCheckboxes = !showCheckboxes;    showDeleteCancelButtons: false, showDeleteCancelButtons = !showDeleteCancelButtons; $el.disabled = true;" 
                            :disabled="selectedRows.length === 1"
                            class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-red-500 hover:text-red-500 hover:bg-red-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                            <span class="text-zinc-600 transition group-hover:text-red-500">Delete</span>
                        </button>
                        <a href="{{ url('transaction/download')}}">
                            <button type="button" class="border px-3 py-2 text-sm text-zinc-600 rounded-lg hover:border-green-500 hover:text-green-500 hover:bg-green-100 transition flex items-center group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5 transition group-hover:text-green-500" viewBox="0 0 24 24">
                                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                                </svg> 
                                <span class="text-zinc-600 transition group-hover:text-green-500">Download</span>
                            </button>
                        </a>

                        {{-- Show Entries --}}
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

                <div class="px-8 ps-10">
                    <!-- Navigation Tabs -->
                    <nav class="flex space-x-4 my-4">
                        <a href="{{ route('tax_return.slsp_data', $taxReturn->id) }}" class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('tax_return.slsp_data') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                            SLSP Data
                        </a>
                        <a href="{{ route('tax_return.summary', $taxReturn->id) }}" class="text-zinc-600 flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('summary') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                            Summary
                        </a>
                        <a href="{{ route('tax_return.report', $taxReturn->id) }}" class="text-zinc-600 flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('tax_return.report') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                            Report
                        </a>
                        {{-- <a href="{{ route('notes_activity') }}" class="text-zinc-600 flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('notes_activity') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                            Notes & Activity
                        </a> --}}
                    </nav>
                </div>

                <hr>
                
                <div 
                 
                    class="container mx-auto pt-2 overflow-hidden">

                    <!-- Transactions Header -->
                    <div class="container mx-auto ps-8">
                        <div class="flex flex-row space-x-2 mt-2 items-center justify-center">
                            <div x-data="{
                                    selectedType: new URLSearchParams(window.location.search).get('type') || 'sales',
                                    filterTransactions() {
                                        const url = new URL(window.location.href);
                                        url.searchParams.set('type', this.selectedType);
                                        window.location.href = url.toString();
                                    }
                                }" class="w-full">
                                <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-8 border-neutral-300" role="tablist" aria-label="tab options">
                                    <button 
                                        @click="selectedType = 'sales'; filterTransactions()" 
                                        :aria-selected="selectedType === 'sales'"
                                        :tabindex="selectedType === 'sales' ? '0' : '-1'" 
                                        :class="selectedType === 'sales' ? 'font-bold text-blue-900 ' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                        class="h-min py-2 text-base relative" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelsales">
                                        <span class="block">Sales</span>
                                        <span 
                                            :class="selectedType === 'sales' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                        </span>
                                    </button>
                                    <button 
                                        @click="selectedType = 'purchases'; filterTransactions()" 
                                        :aria-selected="selectedType === 'purchases'"
                                        :tabindex="selectedType === 'purchases' ? '0' : '-1'" 
                                        :class="selectedType === 'purchases' ? 'font-bold text-blue-900 ' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                        class="h-min py-2 text-base relative" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelpurchases">
                                        <span class="block">Purchases</span>
                                        <span 
                                            :class="selectedType === 'purchases' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                        </span>
                                    </button>
                                    <button 
                                        @click="selectedType = 'importation'; filterTransactions()" 
                                        :aria-selected="selectedType === 'importation'"
                                        :tabindex="selectedType === 'importation' ? '0' : '-1'" 
                                        :class="selectedType === 'importation' ? 'font-bold text-blue-900 ' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                        class="h-min py-2 text-base relative" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelimportation">
                                        <span class="block">Importation</span>
                                        <span 
                                            :class="selectedType === 'importation' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                        </span>
                                    </button>
                                    <button 
                                        @click="selectedType = 'capital_goods'; filterTransactions()" 
                                        :aria-selected="selectedType === 'capital_goods'"
                                        :tabindex="selectedType === 'capital_goods' ? '0' : '-1'" 
                                        :class="selectedType === 'capital_goods' ? 'font-bold text-blue-900 ' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                        class="h-min py-2 text-base relative" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelcapital_goods">
                                        <span class="block">Capital Goods</span>
                                        <span 
                                            :class="selectedType === 'capital_goods' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Table -->
                    <div class="mb-12 mt-6 mx-12 overflow-hidden max-w-full border-neutral-300">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-neutral-600" id="tableid">
                                <thead class="bg-neutral-100 text-sm text-neutral-700">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <!-- Checkbox for selecting all -->
                                            <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                <div class="relative flex items-center">
                                                    <input type="checkbox" x-model="checkAll" id="checkAll" @click="toggleAll()" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                    />
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" 
                                                        stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
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
                                <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                    @foreach ($paginatedTaxRows as $taxRow)
                                        <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
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
                                            <td class="text-left py-3 px-2">{{ $taxRow->transaction->contactDetails->bus_name }}<br>
                                                {{ $taxRow->transaction->contactDetails->contact_address }}<br>
                                                {{ $taxRow->transaction->contactDetails->contact_tin }}</td>
                                            <td class="text-left py-3 px-2">{{ $taxRow->description }}</td>
                                            <td class="text-left py-3 px-2">{{ $taxRow->transaction->inv_number }}</td>
                                            <td class="text-left py-3 px-2">{{ $taxRow->atc->tax_code }}</td>
                                            <td class="text-left py-3 px-2">{{ \Carbon\Carbon::parse($taxRow->transaction->date)->format(' F j, Y') }}</td>
                                            <td class="text-left py-3 px-2">{{ $taxRow->net_amount }}</td>
                                            <td class="text-left py-3 px-2">{{ $taxRow->atc_amount }}</td>
                                            <td class="text-left py-3 px-2">{{ $taxRow->atc->tax_rate }}</td>
                                            <td class="text-left py-3 px-2">{{ $taxRow->coaAccount->code }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                
                                {{-- <!-- Pagination links -->
                                <tr>
                                    <td colspan="12" class="p-4">
                                        <div class="flex justify-between items-center">
                                            
                                        </div>
                                    </td>
                                </tr> --}}
                            </table>
                        </div>
                    </div>

                  <!-- Delete Confirmation Modal -->
                  <div 
                  x-show="showConfirmDeleteModal" 
                  x-cloak 
                  class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50"
                  x-effect="document.body.classList.toggle('overflow-hidden', showConfirmDeleteModal)">
                  
                  <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full overflow-auto">
                      <div class="flex flex-col items-center">
                          <!-- Icon -->
                          <div class="mb-4">
                              <i class="fas fa-exclamation-triangle text-red-700 text-8xl"></i>
                          </div>

                          <!-- Title -->
                          <h2 class="text-2xl font-extrabold text-zinc-700 mb-2">Delete Item(s)</h2>

                          <!-- Description -->
                          <p class="text-sm text-zinc-700 text-center">
                              You're going to Delete the selected item(s) in the Value Added Tax Return table. Are you sure?
                          </p>

                          <!-- Actions -->
                          <div class="flex justify-center space-x-8 mt-6 w-full">
                              <button 
                                  @click="showConfirmDeleteModal = false; showDeleteCancelButtons = true;" 
                                  class="px-4 py-2 rounded-lg text-sm text-zinc-700 font-bold transition"
                                  > 
                                  Cancel
                              </button>
                              <button 
                                  @click="deleteRows(); showConfirmDeleteModal = false;" 
                                  class="px-5 py-2 bg-red-700 hover:bg-red-800 text-white rounded-lg text-sm font-medium transition"
                                  > 
                                  Delete
                              </button>
                          </div>
                      </div>
                  </div>
              </div>


                    <!-- Action Buttons -->
                    <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                        <button 
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
                            @click="cancelSelection" 
                            class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                            Cancel
                        </button>
                    </div>
                    <div class="mx-12 mb-4">
                        {{ $paginatedTaxRows->appends(['type' => $type])->links('vendor.pagination.custom') }}
                    </div>
                </div>
               

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
                                            <option :value="transaction.id"   x-text="transaction.contact_details.bus_name + ' - ' + 
                                            (transaction.inv_number ? transaction.inv_number : transaction.reference) + 
                                            ' - ' + transaction.date"></option>
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
    function toggleCheckboxes() {
        document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.checked = this.checkAll;
        });
    }

    // FOR SORT BUTTON
    document.getElementById('sortButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownMenu');
        dropdown.classList.toggle('hidden');
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

        // Append sorted rows back to the table body
        table.innerHTML = '';
        sortedRows.forEach(row => table.appendChild(row));
    }

    // Dropdown event listeners
    document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
        item.addEventListener('click', function() {
            const criteria = this.getAttribute('data-sort');
            document.getElementById('selectedOption').textContent = this.textContent; // Update selected option text
            sortItems(criteria);
        });
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
    form.action = "{{ route('tax_return.slsp_data', ['taxReturn' => $taxReturn->id]) }}";

    // Retrieve selectedType from URL parameter as a fallback
    const urlParams = new URLSearchParams(window.location.search);
    const selectedType = urlParams.get('type') || 'sales'; // Default to 'sales' if not found

    // Create hidden inputs
    const inputs = [
        { name: 'perPage', value: entries },
        { name: 'search', value: document.querySelector('input[name="search"]')?.value || '' },
        { name: 'type', value: selectedType }
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
