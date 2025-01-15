    @php
    $organizationId = session('organization_id');
    $organization = \App\Models\OrgSetup::find($organizationId);
    @endphp

<x-app-layout>
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
                        <x-add-contacts-modal />
                        <div 
                            class="items-end float-end relative sm:w-auto" 
                            x-data 
                            x-on:click="$dispatch('open-add-contacts-modal')"   
                            >
                            <button type="button"
                                    class="text-white bg-blue-900 hover:bg-blue-950 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 focus:outline-none focus:ring-2 focus:ring-gray-300">
                                <i class="fas fa-plus-circle mr-1"></i>
                                Add Contact
                            </button>
                        </div>
                    </div>
                </div>  
                <div class="container mx-auto ps-4">
                    <div class="flex flex-row space-x-2 items-center justify-center">
                        <div x-data="{ selectedTab: 'Contacts' }" class="w-full">
                            <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300" role="tablist" aria-label="tab options">
                                <button @click="selectedTab = 'Contacts'" :aria-selected="selectedTab === 'Contacts'" 
                                :tabindex="selectedTab === 'Contacts' ? '0' : '-1'" 
                                :class="selectedTab === 'Contacts' ? 'font-bold text-blue-900' : 'text-neutral-600 font-medium hover:text-blue-900 hover:font-bold'" 
                                class="h-min py-2 text-base relative" 
                                type="button"
                                role="tab" 
                                aria-controls="tabpanelContacts" ><span class="block">Contacts</span>
                                <span 
                                    :class="selectedTab === 'Contacts' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                </span>
                            </button>
                                <a href="/employees">
                                    <button @click="selectedTab = 'Employees'" :aria-selected="selectedTab === 'Employees'" 
                                    :tabindex="selectedTab === 'Employees' ? '0' : '-1'" 
                                    :class="selectedTab === 'Employees' ? 'font-bold text-blue-900' : 'text-neutral-600 font-medium hover:text-blue-900 hover:font-bold'"
                                    class="h-min py-2 text-base relative" 
                                    type="button" 
                                    role="tab" 
                                    aria-controls="tabpanelEmployees" ><span class="block">Employees</span>
                                    <span 
                                        :class="selectedTab === 'Emplpoyees' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                    </span>
                                </button>
                                </a>
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
                        showConfirmDeleteModal: false,
                        
                        // Toggle a single row
                        toggleCheckbox(id) {
                            if (this.selectedRows.includes(id)) {
                                this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                            } else {
                                this.selectedRows.push(id);
                            }
                            console.log('Selected rows:', this.selectedRows); // Debugging
                        },

                        // Toggle all rows
                        toggleAll() {
                            this.checkAll = !this.checkAll;
                            if (this.checkAll) {
                                this.selectedRows = [...document.querySelectorAll('[id^=contact]')].map(el => el.id.replace('contact', ''));
                            } else {
                                this.selectedRows = [];
                            }
                            console.log('All rows:', this.selectedRows); // Debugging
                        },

                        // Handle delete (soft delete)
                        deleteRows() {
                            if (this.selectedRows.length === 0) {
                                alert('No rows selected for deletion.');
                                return;
                            }


                            // Send the request to the backend
                            fetch('{{ route("contacts.destroy") }}', {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({ ids: this.selectedRows })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Refresh or update the table
                                    location.reload(); 
                                } else {
                                    alert('Error deleting rows. Please try again.');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred. Please try again.');
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

                        // Get count of selected rows
                        get selectedCount() {
                            return this.selectedRows.length;
                        }
                    }"
                    class="container mx-auto pt-2 overflow-hidden">

                    <!-- Fourth Header -->
                    <div class="container mx-auto">
                        <div class="flex flex-row space-x-2 items-center justify-between">
                            <!-- Search row -->
                            <div class="flex flex-row space-x-2 items-center ps-8">
                                <!-- Search bar -->
                                <div class="relative w-80 p-4">
                                    <form x-target="tableid" action="/contacts" role="search" aria-label="Table" autocomplete="off">
                                        <input 
                                            type="search" 
                                            name="search" 
                                            class="w-full pl-10 pr-4 py-[7px] text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-blue-900 focus:border-blue-900" 
                                            aria-label="Search Term" 
                                            placeholder="Search..." 
                                            @input.debounce="$el.form.requestSubmit()"
                                            value="{{ request('search') }}"
                                            @search="$el.form.requestSubmit()"
                                        >
                                    </form>
                                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                    
                                <!-- Sort by dropdown -->
                                <div class="relative inline-block text-left">
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

                            <!-- End row -->
                            <div class="flex space-x-4 items-center pr-10 ml-auto">
                                <button 
                                    type="button" 
                                    @click="showCheckboxes = !showCheckboxes; showDeleteCancelButtons = !showDeleteCancelButtons; $el.disabled = true;" 
                                    :disabled="selectedRows.length === 1"
                                    class="border px-3 py-2 rounded-lg text-sm text-zinc-600 hover:border-red-500 hover:bg-red-100 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                    >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                    <span class="text-zinc-600 transition group-hover:text-red-500">Delete</span>
                                </button>
                                
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

                        <!-- Table -->
                        <div class="mb-12 mt-6 mx-12 overflow-hidden max-w-full border-neutral-300">
                            <div class="overflow-x-auto">
                                <table class="w-full items-start text-left text-sm text-neutral-600 p-4" id="tableid">
                                    <thead class="bg-neutral-100 text-sm text-neutral-700">
                                        <tr>
                                            <th scope="col" class="p-4">
                                                <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                    <div class="relative flex items-center">
                                                        <input type="checkbox" x-model="checkAll" id="checkAll" @click="toggleAll()" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition" />
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                        </svg>
                                                    </div>
                                                </label>
                                            </th>
                                            <th scope="col" class="text-left py-4 px-4">Name</th>
                                            <th scope="col" class="text-left py-4 px-4">Contact Type</th>
                                            <th scope="col" class="text-left py-4 px-4">TIN</th>
                                            <th scope="col" class="text-left py-4 px-4">Classification</th>
                                            <th scope="col" class="text-left py-4 px-4">Address</th>
                                            <th scope="col" class="text-left py-4 px-4">City</th>
                                            <th scope="col" class="text-left py-4 px-4">Zip Code</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-300 text-left py-[7px]">
                                        @if (count($contacts) > 0)
                                            @foreach ($contacts as $contact)
                                                <tr class="hover:bg-blue-50 cursor-pointer ease-in-out">
                                                    <td class="p-4">
                                                        <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                            <div class="relative flex items-center">
                                                                <input type="checkbox" @click="toggleCheckbox('{{ $contact->id }}')" :checked="selectedRows.includes('{{ $contact->id }}')" id="contact{{ $contact->id }}" class="peer relative w-5 h-5 appearance-none border border-gray-400 bg-white checked:bg-blue-900 rounded-full checked:border-blue-900 checked:before:content-['✓'] checked:before:text-white checked:before:text-center focus:outline-none transition" />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="2" class="pointer-events-none invisible absolute left-1/2 top-1/2 w-3.5 h-3.5 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                                </svg>
                                                            </div>
                                                        </label>
                                                    </td>
                                                    <td class="text-left py-3 px-4">
                                                        <x-view-contact />
                                                        <button 
                                                            @click="$dispatch('open-view-contact-modal', {{ json_encode($contact) }})" 
                                                            class="hover:underline hover:text-blue-500"
                                                            >
                                                            {{ $contact->bus_name}}
                                                        </button>
                                                    </td>
                                                    <td class="text-left py-3 px-4">
                                                        <span class="inline-block bg-gray-100 text-gray-700 text-xs font-medium rounded-full px-3 py-1">
                                                            {{ $contact->contact_type }}
                                                        </span>
                                                    </td>
                                                    <td class="text-left py-3 px-4">{{ $contact->contact_tin }}</td>
                                                    <td class="text-left py-3 px-4">{{ $contact->classification }}</td>
                                                    <td class="text-left py-3 px-4">{{ $contact->contact_address }}</td>
                                                    <td class="text-left py-3 px-4">{{ $contact->contact_city }}</td>
                                                    <td class="text-left py-3 px-4">{{ $contact->contact_zip }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center justify-center p-4">
                                                    <img src="{{ asset('images/no-account.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                    <h1 class="font-bold text-neutral-600 text-lg mt-2">No Contacts Added Yet</h1>
                                                    <p class="text-sm text-neutral-500 mt-2">Start adding contacts and vendors by<br>clicking the + button above.</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <!-- Action Buttons -->
                                <div x-show="showDeleteCancelButtons" class="flex justify-center py-4" x-cloak>
                                    <button @click="showConfirmDeleteModal = true; showDeleteCancelButtons = true;" :disabled="selectedRows.length === 0"
                                        class="border px-3 py-2 rounded-lg text-sm text-red-600 border-red-600 bg-red-100 hover:bg-red-200 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center space-x-1 group"
                                        >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-red-500" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                        <span class="text-red-600 transition group-hover:text-red-600">Delete Selected</span><span class="transition group-hover:text-red-500" x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                                    </button>
                                    <button @click="cancelSelection" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition"
                                        > Cancel
                                    </button>
                                </div>
                                @if (count($contacts) > 0)
                                    <div class="mt-4">
                                        {{ $contacts->links('vendor.pagination.custom') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Modal -->

                    <!-- Delete Confirmation Modal -->
                    <div 
                        x-show="showConfirmDeleteModal" 
                        x-cloak 
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
                        x-effect="document.body.classList.toggle('overflow-hidden', showConfirmDeleteModal)">
                        
                        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full overflow-auto relative">
                            <div class="flex flex-col items-center">
                                <button @click="showConfirmDeleteModal = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                                <!-- Icon -->
                                <div class="mb-4">
                                    <i class="fas fa-exclamation-triangle text-red-700 text-8xl"></i>
                                </div>

                                <!-- Title -->
                                <h2 class="text-2xl font-extrabold text-zinc-700 mb-2">Delete Item(s)</h2>

                                <!-- Description -->
                                <p class="text-sm text-zinc-700 text-center">
                                    You're going to Delete the selected item(s) in the Contacts table. Are you sure?
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
                </div>
            </div>
        </div>
    </div>


   <script>
        document.addEventListener('search', event => {
            window.location.href = `?search=${event.detail.search}`;
        });

        document.addEventListener('filter', event => {
            const type = event.detail.type;

            // Modify URL or use AJAX request to fetch filtered data
            const url = new URL(window.location.href);
            if (type === 'All') {
                url.searchParams.delete('type'); // Remove any existing filter
            } else {
                url.searchParams.set('type', type); // Set filter to the selected type
            }
            window.location.href = url.toString();
        });

        function toggleCheckboxes() {
            // Get all checkbox elements inside the table body
            const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');

            // Clear or populate the selectedRows array based on checkAll state
            if (this.checkAll) {
                // Check all checkboxes and add their IDs to selectedRows
                this.selectedRows = Array.from(rowCheckboxes).map(checkbox => checkbox.dataset.id);
            } else {
                // Uncheck all checkboxes and clear selectedRows
                this.selectedRows = [];
            }

            // Update the DOM to reflect the state
            rowCheckboxes.forEach(checkbox => {
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
            const table = document.querySelector('#tableid tbody');
            const rows = Array.from(table.querySelectorAll('tr')).filter(row => row.style.display !== 'none');
            let sortedRows;

            if (criteria === 'recently-added') {
                // Sort by the 'Date Created' column; adjust index as necessary
                sortedRows = rows.sort((a, b) => {
                    const aDate = new Date(a.cells[4].textContent.trim());
                    const bDate = new Date(b.cells[4].textContent.trim());
                    return bDate - aDate; // Newest first
                });
            } else {
                sortedRows = rows.sort((a, b) => {
                    const aText = a.cells[1].textContent.trim().toLowerCase(); // Adjust index for 'Code' column
                    const bText = b.cells[1].textContent.trim().toLowerCase();

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

        // Sort dropdown click event handling
        document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                sortItems(criteria);

                // Update displayed text and close dropdown
                document.getElementById('selectedOption').textContent = this.textContent;
                document.getElementById('dropdownMenu').classList.add('hidden');
            });
        });

        // FOR BUTTON OF SHOW ENTRIES
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('dropdownMenuIconButton').addEventListener('click', function () {
                const dropdown = document.getElementById('dropdownDots');
                dropdown.classList.toggle('hidden');
            });
        });

        function setEntries(entries) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('contacts') }}";
            const perPageInput = document.createElement('input');
            perPageInput.type = 'hidden';
            perPageInput.name = 'perPage';
            perPageInput.value = entries;
            const searchInput = document.createElement('input');
            searchInput.type = 'hidden';
            searchInput.name = 'search';
            searchInput.value = "{{ request('search') }}";
            form.appendChild(perPageInput);
            form.appendChild(searchInput);
            document.body.appendChild(form);
            form.submit();
        }

        document.addEventListener('open-view-contact-modal', event => {
    console.log(event.detail); // Debug modal data
});
        
    </script>
</x-app-layout>