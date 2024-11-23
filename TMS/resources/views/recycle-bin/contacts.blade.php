<x-organization-layout>
    <!-- Page Heading -->
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-xs">
                <div class="overflow-x-auto pt-6 px-10">
                    <p class="font-bold text-3xl taxuri-color inline-flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#1e3a8a" d="M20 6a1 1 0 0 1 .117 1.993L20 8h-.081L19 19a3 3 0 0 1-2.824 2.995L16 22H8c-1.598 0-2.904-1.249-2.992-2.75l-.005-.167L4.08 8H4a1 1 0 0 1-.117-1.993L4 6zm-6-4a2 2 0 0 1 2 2a1 1 0 0 1-1.993.117L14 4h-4l-.007.117A1 1 0 0 1 8 4a2 2 0 0 1 1.85-1.995L10 2z"/>
                        </svg>
                        <span>Recycle Bin</span>
                    </p>

                    <div class="flex justify-between items-center mt-2">
                        <p class="font-normal text-sm">The Recycle Bin is a secure repository for soft-deleted items, accessible only to system administrators.</p>
                    </div>

                    <!-- Tabs Navigation -->
                    @php
                        $activeTab = request()->query('tab', 'contacts');
                    @endphp
                    <nav class="flex gap-x-4 overflow-x-auto justify-center mt-4" aria-label="Tabs">
                        <a href="{{ route('recycle-bin.organization.index') }}" class="py-3 px-4 text-sm font-medium {{ $activeTab === 'org' ? 'text-blue-900 font-extrabold border-b-4 border-blue-900' : 'text-gray-500' }}">Organizations</a>
                        <a href="{{ route('recycle-bin.accountant-users.index') }}" class="py-3 px-4 text-sm font-medium {{ $activeTab === 'accountant' ? 'text-blue-900 font-extrabold border-b-4 border-blue-900' : 'text-gray-500' }}">Accountant Users</a>
                        <a href="{{ route('recycle-bin.client-users.index') }}" class="py-3 px-4 text-sm font-medium {{ $activeTab === 'client' ? 'text-blue-900 font-extrabold border-b-4 border-blue-900' : 'text-gray-500' }}">Client Users</a>
                        <a href="{{ route('recycle-bin.transactions.index') }}" class="py-3 px-4 text-sm font-medium {{ $activeTab === 'transaction' ? 'text-blue-900 font-extrabold border-b-4 border-blue-900' : 'text-gray-500' }}">Transactions</a>
                        <a href="{{ route('recycle-bin.tax-returns.index') }}" class="py-3 px-4 text-sm font-medium {{ $activeTab === 'tax' ? 'text-blue-900 font-extrabold border-b-4 border-blue-900' : 'text-gray-500' }}">Tax Returns</a>
                        <a href="{{ route('recycle-bin.contacts.index') }}" class="py-3 px-4 text-sm font-medium {{ $activeTab === 'contacts' ? 'text-blue-900 font-extrabold border-b-4 border-blue-900' : 'text-gray-500' }}">Contacts</a>
                        <a href="{{ route('recycle-bin.employees.index') }}" class="py-3 px-4 text-sm font-medium {{ $activeTab === 'employees' ? 'text-blue-900 font-extrabold border-b-4 border-blue-900' : 'text-gray-500' }}">Employees</a>
                    </nav>

                    <div x-data="recycleBinHandler">
                        <!-- Search and Sort Options -->
                        <div class="flex items-center mt-6">
                            <!-- Search Box -->
                            <div class="relative w-80">
                                <form x-target="contacts-table" action="{{ route('recycle-bin.contacts.index') }}" method="GET" role="search" aria-label="Table" autocomplete="off">
                                    <input type="search" name="user_search" value="{{ request('user_search') }}" class="w-full pl-10 pr-4 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-sky-900 focus:border-sky-900" aria-label="Search Term" placeholder="Search...">
                                </form>
                                <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-zinc-400"></i>
                            </div>

                            <!-- Sort by Dropdown -->
                            <div class="relative ml-6">
                                <button id="sortButton" class="flex items-center text-zinc-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#696969" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" />
                                    </svg>
                                    <span id="selectedOption" class="font-normal text-md text-zinc-700 truncate">Sort by</span>
                                </button>
                                <div id="dropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white hidden z-50">
                                    <div class="py-2 px-2">
                                        <span class="block px-4 py-2 text-sm font-bold text-zinc-700">Sort by</span>
                                        <div data-sort="recently-deleted" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Deleted</div>
                                        <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                        <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bulk Action Buttons -->
                        <div class="flex justify-end space-x-4 my-4">
                            <button @click="confirmBulkRestore()" class="border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm flex items-center">
                                <span class="ml-2">Restore</span>
                            </button>
                            <button @click="confirmBulkDelete()" class="border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm flex items-center">
                                <span class="ml-2">Delete</span>
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
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- contacts Table -->
                        <div class="my-4 overflow-y-auto max-h-[500px]">
                            <table class="min-w-full bg-white" id="contacts-table">
                                <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                                    <tr>
                                        <th class="text-left py-3 px-4">
                                            <input type="checkbox" @click="toggleAll()" :checked="checkAll" aria-label="Select all items" />
                                        </th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Organization</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Tin</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Contact Type</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Address</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Date Deleted</th>
                                        <th class="text-left py-3 px-4 font-semibold text-sm">Deleted by</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700">
                                    @if($trashedContacts->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-center p-4">
                                                <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-48 h-48" />
                                                <h1 class="font-extrabold">No Deleted Contacts yet</h1>
                                                <p class="text-sm text-neutral-500 mt-2">Deleted items will show up here when you need to restore or permanently delete them.</p>
                                            </td>
                                        </tr>
                                    @else
                                        @foreach($trashedContacts as $contact)
                                            <tr>
                                                <td class="py-3 px-4">
                                                    <input 
                                                        type="checkbox" 
                                                        :checked="selectedRows.includes(@json($contact->id))" 
                                                        @click="toggleCheckbox(@json($contact->id))" 
                                                        aria-label="Select item {{ $contact->bus_name }}" 
                                                        data-contact-id="{{ $contact->id }}"
                                                    >
                                                </td>
                                                <td class="py-3 px-4">{{ $contact->organization->registration_name ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">{{ $contact->bus_name ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">{{ $contact->contact_tin ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">{{ $contact->contact_type }}</td>
                                                <td class="py-3 px-4">{{ $contact->contact_address }}</td>
                                                <td class="p-4">{{ \Carbon\Carbon::parse($contact->deleted_at)->format('F d, Y') ?? 'N/A' }}</td>
                                                <td class="py-3 px-4">{{ $contact->deletedByUser->name ?? 'Unknown' }}</td>
                                            </tr>
                                        @endforeach

                                </tbody>
                            </table>
                            <div class="mt-4">
                                {{ $trashedContacts->links('vendor.pagination.custom') }}
                            </div>
                            @endif
                        </div>

                        <!-- Modals -->
                        <div x-show="showConfirmDeleteModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center" x-cloak>
                            <div class="bg-white p-5 rounded-lg max-w-md w-full text-center">
                                <p class="text-lg font-semibold mb-4">Are you sure you want to delete the selected items?</p>
                                <div class="flex justify-center space-x-4">
                                    <button @click="bulkDelete(); closeModal();" class="bg-red-500 text-white py-2 px-4 rounded-lg">Yes, Delete</button>
                                    <button @click="closeModal()" class="bg-gray-300 py-2 px-4 rounded-lg">Cancel</button>
                                </div>
                            </div>
                        </div>

                        <div x-show="showConfirmRestoreModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center" x-cloak>
                            <div class="bg-white p-5 rounded-lg max-w-md w-full text-center">
                                <p class="text-lg font-semibold mb-4">Are you sure you want to restore the selected items?</p>
                                <div class="flex justify-center space-x-4">
                                    <button @click="bulkRestore(); closeModal();" class="bg-green-500 text-white py-2 px-4 rounded-lg">Yes, Restore</button>
                                    <button @click="closeModal()" class="bg-gray-300 py-2 px-4 rounded-lg">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('recycleBinHandler', () => ({
                checkAll: false,
                selectedRows: [],
                showConfirmDeleteModal: false,
                showConfirmRestoreModal: false,

                toggleCheckbox(id) {
                    if (this.selectedRows.includes(id)) {
                        this.selectedRows = this.selectedRows.filter(rowId => rowId !== id);
                    } else {
                        this.selectedRows.push(id);
                    }
                },

                toggleAll() {
                    this.checkAll = !this.checkAll;
                    this.selectedRows = this.checkAll ? @json($trashedContacts->pluck('id')) : [];
                },

                bulkRestore() {
                    axios.post('{{ route("recycle-bin.contacts.bulkRestore") }}', { ids: this.selectedRows })
                        .then(response => location.reload())
                        .catch(error => console.error(error));
                },

                bulkDelete() {
                    axios.post('{{ route("recycle-bin.contacts.bulkDelete") }}', { ids: this.selectedRows, _method: "DELETE" })
                        .then(response => location.reload())
                        .catch(error => console.error(error));
                },

                confirmBulkRestore() {
                    this.showConfirmRestoreModal = true;
                },

                confirmBulkDelete() {
                    this.showConfirmDeleteModal = true;
                },

                closeModal() {
                    this.showConfirmDeleteModal = false;
                    this.showConfirmRestoreModal = false;
                }
            }));
        });

        // Script to toggle sort dropdown visibility
        document.getElementById("sortButton").addEventListener("click", function () {
            document.getElementById("dropdownMenu").classList.toggle("hidden");
        });

        // Script to toggle show entries dropdown visibility
        document.getElementById("showEntriesButton").addEventListener("click", function () {
            document.getElementById("showEntriesDropdown").classList.toggle("hidden");
        });
    </script>

</x-organization-layout>
