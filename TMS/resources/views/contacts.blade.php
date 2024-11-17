@php
$organizationId = session('organization_id');
$organization = \App\Models\OrgSetup::find($organizationId);
@endphp

<x-app-layout>
    {{-- Wag po guluhin mga div T^T --}}
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Page Header -->
            <div class="container mx-auto my-4 pt-6">
                <div class="px-10">
                    <div class="flex flex-row w-64 items-start space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 14 14"><path fill="#1e3a8a" fill-rule="evenodd" d="M8 3a3 3 0 1 1-6 0a3 3 0 0 1 6 0m2.75 4.5a.75.75 0 0 1 .75.75V10h1.75a.75.75 0 0 1 0 1.5H11.5v1.75a.75.75 0 0 1-1.5 0V11.5H8.25a.75.75 0 0 1 0-1.5H10V8.25a.75.75 0 0 1 .75-.75M5 7c1.493 0 2.834.655 3.75 1.693v.057h-.5a2 2 0 0 0-.97 3.75H.5A.5.5 0 0 1 0 12a5 5 0 0 1 5-5" clip-rule="evenodd"/></svg>
                        <p class="font-bold text-3xl text-left taxuri-color">Contacts</p>
                    </div>
                </div>
                <div class="flex justify-between items-center px-10">
                    <div class="flex items-center">            
                        <p class="font-normal text-sm text-zinc-700">The Contacts page allows quick entry and storage of essential client details to<br>keep records organized and accessible.</p>
                    </div>
                    <div class="items-end float-end relative sm:w-auto" 
                        x-data="{ selectedTab: (new URL(window.location.href)).searchParams.get('type') || 'Contacts' }" 
                        @filter.window="selectedTab = $event.detail.type">
                        <button type="button"
                                class="text-white bg-blue-900 hover:bg-blue-950 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            <i class="fas fa-plus-circle mr-1"></i>
                            Add Contact
                        </button>
                    </div>
                </div>
            </div>  
            <div class="container mx-auto ps-8">
                <div class="flex flex-row space-x-2 items-center justify-center">
                    <div x-data="{ selectedTab: 'Contacts' }" class="w-full">
                        <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300" role="tablist" aria-label="tab options">
                            <button @click="selectedTab = 'Contacts'" :aria-selected="selectedTab === 'Contacts'" 
                            :tabindex="selectedTab === 'Contacts' ? '0' : '-1'" 
                            :class="selectedTab === 'Contacts' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900'   : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'" 
                            class="h-min py-2 text-base" 
                            type="button"
                            role="tab" 
                            aria-controls="tabpanelContacts" >Contacts</button>
                            <a href="">
                                <button @click="selectedTab = 'Employees'" :aria-selected="selectedTab === 'Employees'" 
                                :tabindex="selectedTab === 'Employees' ? '0' : '-1'" 
                                :class="selectedTab === 'Employees' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900'   : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'"
                                class="h-min py-2 text-base" 
                                type="button" 
                                role="tab" 
                                aria-controls="tabpanelEmployees" >Employees</button>
                            </a>
                        </div>
                    </div>  
                </div>
            </div>

            <hr>

            <div x-data="{ showCheckboxes: false, selectedTab: 'All', checkAll: false, showDeleteCancelButtons: false,  selectedRows: [], showConfirmDeleteModal: false, checkAll: false,   // Toggle a single row
                    toggleCheckbox(id) {
                        if (this.selectedRows.includes(id)) {
                            this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                        } else {
                            this.selectedRows.push(id);
                        }
                        console.log(this.selectedRows); // Debugging line
                    },
                    
                    // Toggle all rows
                    {{-- toggleAll() {
                        this.checkAll = !this.caheckAll;
                        if (this.checkAll) {
                            this.selectedRows = {{ json_encode($contacts->pluck('id')->toArray()) }}; 
                        } else {
                            this.selectedRows = []; 
                        }
                        console.log(this.selectedRows); // Debugging line
                    }, --}}
                    
                    // Handle archiving
                    deleteRows() {
                        if (this.selectedRows.length === 0) {
                            alert('No rows selected for deletion.');
                            return;
                        }

                        fetch('/transaction/destroy', {
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
                                alert('Error archiving rows.');
                            }
                        });
                    },
                    
                    // Cancel selection
                    cancelSelection() {
                        this.selectedRows = []; 
                        this.checkAll = false;
                        this.showCheckboxes = false; 
                        this.showDeleteCancelButtons = false;
                        this.showConfirmDeleteModal = false;
                    },
                    
                    get selectedCount() {
                        return this.selectedRows.length;
                    }
                }"   class="container mx-auto pt-2">

                <!-- Search/Sort/Delete/Show Entries Header -->
                <div class="container mx-auto">
                    <div class="flex flex-row space-x-2 items-center justify-between">
                        <div class="flex flex-row space-x-2 items-center ps-8">
                            <!-- Search row -->
                            <div class="relative w-80 p-4">
                                <form x-target="tableContacts" action="/contacts" role="search" aria-label="Table" autocomplete="off">
                                    <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-900 focus:border-blue-900" 
                                        aria-label="Search Term" 
                                        placeholder="Search..." 
                                        @input.debounce="$el.form.requestSubmit()" 
                                        @search="$el.form.requestSubmit()"
                                    >
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
                
                        <!-- Buttons and Show Entries -->
                        <div class="flex space-x-4 items-center pr-10 ml-auto">
                            <button 
                                type="button" 
                                @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons: false, showDeleteCancelButtons = !showDeleteCancelButtons; $el.disabled = true;" 
                                :disabled="selectedRows.length === 1"
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

                    {{-- Contacts Table --}}
                    <div x-data="{ checkAll: false, currentPage: 1, perPage: 5 }" class="mb-12 mt-6 mx-12 overflow-hidden max-w-full border-neutral-300">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-neutral-600" id="tableTransaction">
                                <thead class="bg-neutral-100 text-sm text-neutral-700">
                                    <tr>
                                        <th scope="col" class="p-4">
                                            <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                <div class="relative flex items-center">
                                                    <input type="checkbox" x-model="checkAll" id="checkAll" @change="toggleAll()" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                </div>
                                            </label>
                                        </th>
                                        <th scope="col" class="text-left py-4 px-4">Name</th>
                                        <th scope="col" class="text-left py-4 px-4">TIN</th>
                                        <th scope="col" class="text-left py-4 px-4">Contact Type</th>
                                        <th scope="col" class="text-left py-4 px-4">Address</th>
                                        <th scope="col" class="text-left py-4 px-4">City</th>
                                        <th scope="col" class="text-left py-4 px-4">Zip Code</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-300">
                                    <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                        <td class="p-4">
                                            <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                <div class="relative flex items-center">
                                                    <input type="checkbox"  class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0 dark:border-neutral-700 dark:bg-neutral-900 dark:checked:border-white dark:checked:before:bg-white dark:focus:outline-neutral-300 dark:checked:focus:outline-white" />
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible dark:text-black">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                </div>
                                            </label>
                                        </td>
                                        <td class="text-left py-3 px-4 font-bold underline hover:text-blue-500"></td>
                                        <td class="text-left py-3 px-4"></td>
                                        <td class="text-left py-3 px-4"></td>
                                        <td class="text-left py-3 px-4"></td>
                                        <td class="text-left py-3 px-4"></td>
                                        <td class="text-left py-3 px-4"></td>
                                    </tr>  
                                    <tr>
                                        <td colspan="7" class="text-center p-4">
                                            <img src="{{ asset('images/no-account.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                            <h1 class="font-extrabold text-lg mt-2">No Contacts Added yet</h1>
                                            <p class="text-sm text-neutral-500 mt-2">Start adding customers and vendors by<br>clicking the + button above.</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Action Buttons -->
                            <div 
                                x-show="showConfirmDeleteModal" 
                                x-cloak 
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                                x-effect="document.body.classList.toggle('overflow-hidden', showConfirmDeleteModal)"
                                >
                                <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full overflow-auto">
                                    <div class="flex flex-col items-center">
                                        <!-- Icon -->
                                        <div class="mb-4">
                                            <i class="fas fa-exclamation-triangle text-red-600 text-8xl"></i>
                                        </div>

                                        <!-- Title -->
                                        <h2 class="text-2xl font-extrabold text-zinc-800 mb-2">Delete Item(s)</h2>

                                        <!-- Description -->
                                        <p class="text-sm text-zinc-700 text-center">
                                            You're going to Delete the selected item(s) in the Transactions table. Are you sure?
                                        </p>

                                        <!-- Actions -->
                                        <div class="flex justify-center space-x-8 mt-6 w-full">
                                            <button 
                                                @click="showConfirmDeleteModal = false; showDeleteCancelButtons = true;" 
                                                class="px-4 py-2 rounded-lg text-sm text-zinc-500 hover:text-zinc-800 font-bold transition"
                                            > 
                                                Cancel
                                            </button>
                                            <button 
                                                @click="deleteRows(); showConfirmDeleteModal = false;" 
                                                class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm transition"
                                            > 
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
                                    class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"
                                >
                                Cancel
                                </button>
                            </div>
                            <nav aria-label="pagination">
                                {{-- {{ $contacts->links('vendor.pagination.custom') }} --}}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>