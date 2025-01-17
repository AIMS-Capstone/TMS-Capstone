@php
$organizationId = session('organization_id');
@endphp

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Header -->
                <div class="px-10 py-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">Withholding Tax Return</li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ route('with_holding.1601C') }}" 
                                        class="ms-1 text-sm font-medium {{ Request::routeIs('with_holding.1601C') ? 'font-extrabold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                        1601C
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="" class="ms-1 text-sm font-bold text-blue-900 md:ms-2">Sources</a>
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
                        showRestoreCancelButtons: false, 
                        isDisabled: false,
                        showConfirmUnarchiveModal: false, 
                        showConfirmDeleteModal: false,
                        showSuccessUnarchiveModal: false,
                        showSuccessDeleteModal: false,

                        disableButtons() {
                            this.isDisabled = true;
                        },

                        enableButtons() {
                            this.isDisabled = false;
                            this.showDeleteCancelButtons = false; 
                            this.showRestoreCancelButtons = false; 
                            this.showConfirmUnarchiveModal = false; 
                            this.showConfirmDeleteModal = false;
                        },

                        toggleCheckbox(id) {
                            if (this.selectedRows.includes(id)) {
                                this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                            } else {
                                this.selectedRows.push(id);
                            }
                            // Update the checkAll state based on all checkboxes' status
                            this.checkAll = this.selectedRows.length === {{ json_encode($sources->count()) }};
                        },
                        toggleAll() {
                            this.checkAll = !this.checkAll;
                            if (this.checkAll) {
                                this.selectedRows = {{ json_encode($sources->pluck('id')->toArray()) }}; 
                            } else {
                                this.selectedRows = []; // Unselect all
                            }
                        },
                        deleteRows() {
                            if (this.selectedRows.length === 0) {
                                alert('No rows selected for deletion.');
                                return;
                            }

                            fetch('{{ route('Sources1601C.destroy', ['id' => $with_holding->id]) }}', {
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
                        restoreRows() {
                            if (this.selectedRows.length === 0) {
                                alert('No rows selected for restoration.');
                                return;
                            }

                            fetch('{{ route('Sources1601C.restore', ['id' => $with_holding->id]) }}', {
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
                        cancelSelection() {
                            this.selectedRows = []; 
                            this.checkAll = false;
                            this.showCheckboxes = false; 
                            this.showDeleteCancelButtons = false;
                            this.showRestoreCancelButtons = false;
                            this.showConfirmUnarchiveModal = false;
                            this.showConfirmDeleteModal = false;
                        },
                        get selectedCount() {
                            return this.selectedRows.length; 
                        }
                    }" class="container mx-auto">
                    <!-- Actions -->
                    <div class="flex flex-row space-x-2 items-center justify-between">
                        <!-- Search Bar -->
                        <div class="flex flex-row space-x-2 items-center ps-6">
                            <div class="w-80 p-5">
                                <form x-target="tableid" action="/tax_return/with_holding/{{ $with_holding->id }}/1601C_sources" role="search" aria-label="Table" autocomplete="off">
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

                        <!-- Action Buttons -->
                        <div class="flex space-x-4 items-center pr-10 ml-auto">
                            <!-- Unarchive Button -->
                            <button type="button" @click="showCheckboxes = !showCheckboxes; showRestoreCancelButtons = !showRestoreCancelButtons; disableButtons();" 
                                :disabled="selectedRows.length === 1 || isDisabled"
                                class="border px-3 py-2 rounded-lg text-sm text-gray-600 hover:border-gray-800 hover:text-gray-800 hover:bg-zinc-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-zinc-500" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M3 10H2V4.003C2 3.449 2.455 3 2.992 3h18.016A.99.99 0 0 1 22 4.003V10h-1v10.002a.996.996 0 0 1-.993.998H3.993A.996.996 0 0 1 3 20.002zm16 0H5v9h14zM4 5v3h16V5zm5 7h6v2H9z"/>
                                </svg>
                                <span class="text-zinc-600 transition group-hover:text-zinc-500">Unarchive</span>
                            </button>
                            <!-- Delete Button -->
                            <button type="button" @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons; disableButtons();" 
                                :disabled="selectedRows.length === 1 || isDisabled"
                                class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-red-800 hover:text-red-800 hover:bg-red-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                >
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                <span class="text-zinc-600 transition group-hover:text-red-500">Delete</span>
                            </button>

                            <div class="relative inline-block space-x-4 text-left">
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

                    <!-- Filtering Tab/Third Header -->
                    <div x-data="{selectedTab: 'Sources', init() {this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || 'Sources'; }
                        }" x-init="init" class="w-full p-5">
                        <div @keydown.right.prevent="$focus.wrap().next()" 
                            @keydown.left.prevent="$focus.wrap().previous()" 
                            class="flex flex-row text-center overflow-x-auto ps-5" 
                            role="tablist" 
                            aria-label="tab options">
                            
                            <!-- Tab 1: Summary -->
                            <a href="{{ route('with_holding.1601C_summary', ['id' => $with_holding->id]) }}">
                                <button @click="selectedTab = 'Summary'; $dispatch('filter', { type: 'Summary' })"
                                    :aria-selected="selectedTab === 'Summary'" 
                                    :tabindex="selectedTab === 'Summary' ? '0' : '-1'" 
                                    :class="selectedTab === 'Summary' 
                                        ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                        : 'text-zinc-600 font-medium hover:text-blue-900'"
                                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="tabpanelSummary">
                                    Summary
                                </button>
                            </a>
                            <!-- Tab 2: Sources -->
                            <button @click="selectedTab = 'Sources'" 
                                :aria-selected="selectedTab === 'Sources'" 
                                :tabindex="selectedTab === 'Sources' ? '0' : '-1'" 
                                :class="selectedTab === 'Sources' 
                                    ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                    : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                type="button" 
                                role="tab" 
                                aria-controls="tabpanelSources">
                                Sources
                            </button>
                            <!-- Tab 3: Report -->
                            <a href="{{ route('form1601C.create', ['id' => $with_holding->id]) }}">
                                <button @click="selectedTab = 'Report'" 
                                    :aria-selected="selectedTab === 'Report'" 
                                    :tabindex="selectedTab === 'Report' ? '0' : '-1'" 
                                    :class="selectedTab === 'Report' 
                                        ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                        : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                    class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="tabpanelReport">
                                    Report
                                </button>
                            </a>
                        </div>
                    </div>

                    <!-- Sources Table -->
                    <div class="container mx-auto overflow-hidden">
                        <!-- Transactions Header -->
                        <div class="container mx-auto ps-8">
                            <div class="flex flex-row space-x-2 items-center justify-center">
                                <div x-data="{ selectedTab: 'Archive Compensation' }" class="w-full">
                                    <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 border-neutral-300" role="tablist" aria-label="tab options">
                                        <a href="{{route('with_holding.1601C_sources', ['id' => $with_holding->id]) }}">
                                            <button 
                                                @click="selectedTab = 'Compensation'" 
                                                :aria-selected="selectedTab === 'Compensation'" 
                                                :tabindex="selectedTab === 'Compensation' ? '0' : '-1'" 
                                                :class="selectedTab === 'Compensation' ? 'font-bold text-blue-900 ' : 'text-neutral-600 font-medium hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                                class="h-min py-2 text-base relative" 
                                                type="button" 
                                                role="tab" 
                                                aria-controls="tabpanelCompensation">
                                                <span class="block">Compensation</span>
                                                <span 
                                                    :class="selectedTab === 'Compensation' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                                </span>
                                            </button>
                                        </a>
                                        <button @click="selectedTab = 'ArchiveCompensation'" :aria-selected="selectedTab === 'Archive Compensation'" 
                                            :tabindex="selectedTab === 'Archive Compensation' ? '0' : '-1'" 
                                            :class="selectedTab === 'Archive Compensation' ? 'font-bold text-blue-900' : 'text-neutral-600 font-medium hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'"
                                            class="h-min py-2 text-base relative" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="tabpanelArchiveCompensation" >
                                            <span class="block">Archive Compensation</span>
                                            <span
                                                :class="selectedTab === 'Archive Compensation' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                            </span>
                                        </button>
                                    </div>
                                </div>  
                            </div>
                        </div>

                        <hr>

                        <div class="mb-12 mt-6 mx-12 overflow-hidden max-w-full border-neutral-300">
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left text-[13px] text-neutral-600" id="tableid">
                                    <thead class="bg-neutral-100 text-xs text-neutral-700">
                                        <tr>
                                            <th scope="col" class="py-2 px-4">
                                                <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600" x-cloak >
                                                    <div class="relative flex items-center">
                                                        <input type="checkbox" x-model="checkAll" id="checkAll" @click="toggleAll()" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-[''] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                        />
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                        </svg>
                                                    </div>
                                                </label>
                                            </th>
                                            <th class="py-2 px-4">Employee</th>
                                            <th class="py-2 px-4">Wage Status</th>
                                            <th class="py-2 px-4">Payment Date</th>
                                            <th class="py-2 px-4">Gross Compensation</th>
                                            <th class="py-2 px-4">Tax Due</th>
                                            <th class="py-2 px-4">Statutory Minimum Wage</th>
                                            <th class="py-2 px-4">Holiday Pay, OT Pay, etc.</th>
                                            <th class="py-2 px-4">13th Month Pay and Other Benefits</th>
                                            <th class="py-2 px-4">De Minimis Benefits</th>
                                            <th class="py-2 px-4">SSS, GSIS, PHIC, HDMF etc.</th>
                                            <th class="py-2 px-4">Other Non-Taxable Compensation</th>
                                            <th class="py-2 px-4">Total Non-Taxable Compensation</th>
                                            <th class="py-2 px-4">Taxable Compensation</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                        @forelse ($sources as $source)
                                            <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                                <td class="p-4">
                                                    <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600" x-cloak>
                                                        <div class="relative flex items-center">
                                                            <input type="checkbox" @click="toggleCheckbox('{{ $source->id }}')" :checked="selectedRows.includes('{{ $source->id }}')" id="source{{ $source->id }}"
                                                                class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-[''] checked:before:text-white checked:before:text-center focus:outline-none transition"
                                                                />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                        </div>
                                                    </label>
                                                </td>
                                                <td class="py-3 px-4">{{ $source->employee->first_name ?? 'N/A' }} {{ $source->employee->last_name ?? '' }}</td>
                                                <td class="py-3 px-4"><span class="bg-zinc-100 text-zinc-700 text-xs font-medium me-2 px-4 py-1.5 rounded-full">{{ $source->employment->employee_wage_status ?? 'N/A' }}</span></td>
                                                <td class="py-3 px-4">{{ \Carbon\Carbon::parse($source->payment_date ?? 'N/A')->format('F j, Y') }}</td>
                                                <td class="py-3 px-4">{{ number_format($source->gross_compensation, 2) ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">{{ number_format($source->tax_due, 2) ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">{{ number_format($source->statutory_minimum_wage, 2) ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">
                                                    {{ number_format($source->holiday_pay + $source->overtime_pay + $source->night_shift_differential + $source->hazard_pay, 2) ?? 'N/A' }}
                                                </td>
                                                <td class="py-3 px-4">{{ number_format($source->month_13_pay, 2) ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">{{ number_format($source->de_minimis_benefits, 2) ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">{{ number_format($source->sss_gsis_phic_hdmf_union_dues, 2) ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">{{ number_format($source->other_non_taxable_compensation, 2) ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">
                                                    {{ number_format($source->holiday_pay + $source->overtime_pay + $source->night_shift_differential + $source->hazard_pay + $source->statutory_minimum_wage + $source->month_13_pay + $source->de_minimis_benefits + $source->sss_gsis_phic_hdmf_union_dues + $source->other_non_taxable_compensation, 2) ?? 'N/A' }}
                                                </td>
                                                <td class="py-3 px-4">{{ number_format($source->taxable_compensation, 2) ?? 'N/A' }}</td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="14" class="text-center p-4">
                                                <img src="{{ asset('images/Wallet.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                <h1 class="font-bold text-lg mt-2">No Compensations Added yet</h1>
                                                <p class="text-sm text-neutral-500 mt-2">Start adding with the + button <br>at the top.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Unarchive Confirmation Modal -->
                            <div 
                                x-show="showConfirmUnarchiveModal" 
                                x-cloak 
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                @click.away="showConfirmUnarchiveModal = false"
                                x-effect="document.body.classList.toggle('overflow-hidden', showConfirmUnarchiveModal)"
                            >
                                <div class="bg-zinc-200 rounded-lg shadow-lg p-6 max-w-sm w-full">
                                    <div class="flex flex-col items-center">
                                        <!-- Icon -->
                                        <div class="mb-4">
                                            <i class="fas fa-exclamation-triangle text-zinc-700 text-8xl"></i>
                                        </div>

                                        <!-- Title -->
                                        <h2 class="text-2xl font-bold text-zinc-700 mb-2">Unarchive Item(s)</h2>

                                        <!-- Description -->
                                        <p class="text-sm text-zinc-700 text-center">
                                            You're going to unarchive the selected item(s) in the Sources Archive table. Are you sure?
                                        </p>

                                        <!-- Actions -->
                                        <div class="flex justify-center space-x-8 mt-6 w-full">
                                            <button 
                                                @click="showConfirmUnarchiveModal = false; enableButtons(); showRestoreCancelButtons = true; disableButtons();" 
                                                class="px-4 py-2 rounded-lg text-sm text-zinc-700 font-bold transition"
                                            >
                                                Cancel
                                            </button>
                                            <button 
                                                @click="restoreRows(); showConfirmUnarchiveModal = false;" 
                                                class="px-4 py-2 bg-zinc-700 hover:bg-zinc-800 text-white rounded-lg text-sm transition"
                                            >
                                                Unarchive
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
                                <div class="bg-zinc-200 rounded-lg shadow-lg p-6 max-w-sm w-full">
                                    <div class="flex flex-col items-center">
                                        <!-- Icon -->
                                        <div class="mb-4">
                                            <i class="fas fa-exclamation-triangle text-red-600 text-8xl"></i>
                                        </div>

                                        <!-- Title -->
                                        <h2 class="text-2xl font-extrabold text-zinc-800 mb-2">Delete Sources</h2>

                                        <!-- Description -->
                                        <p class="text-sm text-zinc-600 text-center">
                                            You're going to delete the selected item(s) in the Sources Archive table. Are you sure?
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
                                    <button 
                                        type = "button"
                                        @click="showConfirmUnarchiveModal = true; showRestoreCancelButtons = true;"
                                        :disabled="selectedRows.length === 0"
                                        class="border px-3 py-2 rounded-lg text-sm text-gray-800 border-gray-800 bg-zinc-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                    >
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

                            @if (count($sources) > 0)
                                <div class="mt-4">
                                    {{ $sources->links('vendor.pagination.custom') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('employee_id').addEventListener('change', function () {
        const selectedEmployeeId = this.value;
        const employees = @json($employees);
        const selectedEmployee = employees.find(employee => employee.id == selectedEmployeeId);

        const wageStatusInput = document.getElementById('employee_wage_status');
        const statutoryFields = [
            document.getElementById('statutory_minimum_wage'),
            document.getElementById('holiday_pay'),
            document.getElementById('overtime_pay'),
            document.getElementById('night_shift_differential'),
            document.getElementById('hazard_pay'),
        ];
        const taxDueField = document.getElementById('tax_due');

        if (selectedEmployee && selectedEmployee.latest_employment) {
            const wageStatus = selectedEmployee.latest_employment.employee_wage_status || 'N/A';
            wageStatusInput.value = wageStatus;

            // Enable/disable fields based on wage status
            if (wageStatus === 'Minimum Wage') {
                statutoryFields.forEach(field => field.disabled = false); // Enable for MWEs
                taxDueField.disabled = true; // Tax-exempt for MWEs
                taxDueField.value = 0; // Reset tax due to zero for MWEs
            } else if (wageStatus === 'Above Minimum Wage') {
                statutoryFields.forEach(field => field.disabled = true); // Disable for AMWEs
                taxDueField.disabled = false; // Taxable for AMWEs
            } else {
                // Default fallback if wage status is not recognized
                statutoryFields.forEach(field => field.disabled = true);
                taxDueField.disabled = false;
            }
        } else {
            wageStatusInput.value = 'N/A';
            statutoryFields.forEach(field => field.disabled = true);
            taxDueField.disabled = false;
        }
    });

    // Additional logic to calculate taxable compensation and tax due dynamically
    document.addEventListener("DOMContentLoaded", () => {
        const fields = [
            "gross_compensation",
            "statutory_minimum_wage",
            "holiday_pay",
            "overtime_pay",
            "night_shift_differential",
            "hazard_pay",
            "month_13_pay",
            "de_minimis_benefits",
            "sss_gsis_phic_hdmf_union_dues",
            "other_non_taxable_compensation"
        ];

        const calculateCompensation = () => {
            const grossCompensation = parseFloat(document.getElementById("gross_compensation").value) || 0;
            const statutoryMinWage = parseFloat(document.getElementById("statutory_minimum_wage").value) || 0;
            const holidayPay = parseFloat(document.getElementById("holiday_pay").value) || 0;
            const overtimePay = parseFloat(document.getElementById("overtime_pay").value) || 0;
            const nightShiftDiff = parseFloat(document.getElementById("night_shift_differential").value) || 0;
            const hazardPay = parseFloat(document.getElementById("hazard_pay").value) || 0;
            const deMinimisBenefits = parseFloat(document.getElementById("de_minimis_benefits").value) || 0;
            const sssGsisPhilhealth = parseFloat(document.getElementById("sss_gsis_phic_hdmf_union_dues").value) || 0;
            const otherNonTaxableComp = parseFloat(document.getElementById("other_non_taxable_compensation").value) || 0;

            const month13Pay = parseFloat(document.getElementById("month_13_pay").value) || 0;

            const nonTaxableBenefits = Math.min(month13Pay, 90000); 
            const taxableBenefits = Math.max(0, month13Pay - 90000);

            // Calculate total non-taxable benefits
            const totalNonTaxableBenefits = statutoryMinWage + holidayPay + overtimePay + nightShiftDiff + hazardPay +
                deMinimisBenefits + sssGsisPhilhealth + otherNonTaxableComp + nonTaxableBenefits;

            // Calculate taxable compensation
            const taxableCompensation = Math.max(0, grossCompensation - totalNonTaxableBenefits + taxableBenefits);

            // Update the Taxable Compensation UI
            document.getElementById("taxable_compensation").innerText = taxableCompensation.toFixed(2);
            document.getElementById("taxable_compensation_input").value = taxableCompensation.toFixed(2);

            // Calculate Tax Due
            const wageStatus = document.getElementById("employee_wage_status").value;
            const taxDueField = document.getElementById("tax_due");

            if (wageStatus === 'Above Minimum Wage' && taxableCompensation > 0) {
                const taxDue = computeTax(taxableCompensation);
                taxDueField.value = taxDue.toFixed(2);
                document.getElementById("tax_due_display").innerText = taxDue.toFixed(2);
            } else {
                taxDueField.value = 0;
                document.getElementById("tax_due_display").innerText = "0.00";
            }
        };

        // Attach event listeners to input fields
        fields.forEach(field => {
            document.getElementById(field).addEventListener("input", calculateCompensation);
        });
    });

    // Tax computation logic based on TRAIN law
        const computeTax = (taxableCompensation) => {
            const taxBrackets = [
                { limit: 20833, rate: 0 },        // Up to ₱20,833: 0% tax
                { limit: 33333, rate: 0.15 },     // ₱20,834 - ₱33,333: 15%
                { limit: 66667, rate: 0.20 },     // ₱33,334 - ₱66,667: 20%
                { limit: 166667, rate: 0.25 },    // ₱66,668 - ₱166,667: 25%
                { limit: 666667, rate: 0.30 },    // ₱166,668 - ₱666,667: 30%
                { limit: Infinity, rate: 0.35 },  // Over ₱666,667: 35%
            ];

            let taxDue = 0;
            let remainingCompensation = taxableCompensation;

            for (let i = 0; i < taxBrackets.length - 1; i++) {
                const lowerLimit = taxBrackets[i].limit;
                const upperLimit = taxBrackets[i + 1].limit;

                if (remainingCompensation > lowerLimit) {
                    const taxableAmount = Math.min(remainingCompensation, upperLimit) - lowerLimit;
                    taxDue += taxableAmount * taxBrackets[i + 1].rate;
                }
            }

            return taxDue;
        };

    // prevent future date
    document.addEventListener("DOMContentLoaded", function () {
        const paymentDateInput = document.getElementById('payment_date');

        // Disable typing by preventing keypress
        paymentDateInput.addEventListener('keydown', function (event) {
            event.preventDefault(); // Prevent any keyboard input
        });

        // Set the max attribute to today's date
        const today = new Date().toISOString().split('T')[0]; // Format as YYYY-MM-DD
        paymentDateInput.setAttribute('max', today);
    });

    // prevent data from other fields when the 
    document.addEventListener("DOMContentLoaded", () => {
        const fieldsToClear = [
            "statutory_minimum_wage",
            "holiday_pay",
            "overtime_pay",
            "night_shift_differential",
            "hazard_pay",
        ];

        document.getElementById('employee_id').addEventListener('change', function () {
            const selectedEmployeeId = this.value;
            const employees = @json($employees);
            const selectedEmployee = employees.find(employee => employee.id == selectedEmployeeId);

            const wageStatusInput = document.getElementById('employee_wage_status');
            if (selectedEmployee && selectedEmployee.latest_employment) {
                const wageStatus = selectedEmployee.latest_employment.employee_wage_status || 'N/A';
                wageStatusInput.value = wageStatus;

                if (wageStatus === 'Above Minimum Wage') {
                    // Clear and disable fields for Above Minimum Wage employees
                    fieldsToClear.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        field.value = ""; // Clear value
                        field.disabled = true; // Disable input
                    });
                } else if (wageStatus === 'Minimum Wage') {
                    // Enable fields for Minimum Wage employees
                    fieldsToClear.forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        field.disabled = false; // Enable input
                    });
                }
            } else {
                wageStatusInput.value = 'N/A';
                // Reset fields if no employee is selected
                fieldsToClear.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    field.value = "";
                    field.disabled = true;
                });
            }
        });
    });
    
    // Buttons
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
        form.action = "{{ route('with_holding.1601C_sources', ['id' => $with_holding->id]) }}";
        // Create a hidden input for perPage
        const perPageInput = document.createElement('input');
        perPageInput.type = 'hidden';
        perPageInput.name = 'perPage';
        perPageInput.value = entries;
        // Add search input value if needed
        const searchInput = document.createElement('input');
        searchInput.type = 'hidden';
        searchInput.name = 'search';
        searchInput.value = "{{ request('search') }}";
        // Append inputs to form
        form.appendChild(perPageInput);
        form.appendChild(searchInput);
        // Append the form to the body and submit
        document.body.appendChild(form);
        form.submit();
    }
</script>
