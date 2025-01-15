<x-organization-layout>
    <!-- Page Heading -->
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-xs">
                <div class="overflow-x-auto ml-10 absolute flex items-center">
                    <button onclick="history.back()" class="text-zinc-600 hover:text-zinc-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                            <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g>
                        </svg>
                        <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go Back</span>
                    </button>
                </div>

                <div class="overflow-x-auto pt-6 mt-4 px-10">
                    <p class="font-bold text-3xl taxuri-color inline-flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#1e3a8a" d="M20 6a1 1 0 0 1 .117 1.993L20 8h-.081L19 19a3 3 0 0 1-2.824 2.995L16 22H8c-1.598 0-2.904-1.249-2.992-2.75l-.005-.167L4.08 8H4a1 1 0 0 1-.117-1.993L4 6zm-6-4a2 2 0 0 1 2 2a1 1 0 0 1-1.993.117L14 4h-4l-.007.117A1 1 0 0 1 8 4a2 2 0 0 1 1.85-1.995L10 2z"/>
                        </svg>
                        <span>Recycle Bin</span>
                    </p>

                    <div class="taxuri-text flex justify-between items-center mt-2">
                        <p class="font-normal text-sm">The Recycle Bin is a dedicated module accessible exclusively by system administrators. It<br>serves as a secure repository for soft-deleted items.</p>
                    </div>

                    <!-- Tabs Navigation -->
                    @php
                        $activeTab = request()->query('tab', 'contacts');
                    @endphp
                    <nav class="flex gap-x-4 overflow-x-auto justify-center mt-4" aria-label="Tabs">
                        <a href="{{ route('recycle-bin.organization.index') }}"
                           class="relative py-3 px-4 text-sm font-medium {{ $activeTab === 'org' ? 'text-blue-900 font-extrabold' : 'text-gray-500' }}">
                            Organizations
                            <span class="{{ $activeTab === 'org' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden' }}"></span>
                        </a>
                        <a href="{{ route('recycle-bin.accountant-users.index') }}"
                           class="relative py-3 px-4 text-sm font-medium {{ $activeTab === 'accountant' ? 'text-blue-900 font-extrabold' : 'text-gray-500' }}">
                            Accountant Users
                            <span class="{{ $activeTab === 'accountant' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden' }}"></span>
                        </a>
                        <a href="{{ route('recycle-bin.client-users.index') }}"
                           class="relative py-3 px-4 text-sm font-medium {{ $activeTab === 'client' ? 'text-blue-900 font-extrabold' : 'text-gray-500' }}">
                            Client Users
                            <span class="{{ $activeTab === 'client' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden' }}"></span>
                        </a>
                        <a href="{{ route('recycle-bin.transactions.index') }}"
                           class="relative py-3 px-4 text-sm font-medium {{ $activeTab === 'transaction' ? 'text-blue-900 font-extrabold' : 'text-gray-500' }}">
                            Transactions
                            <span class="{{ $activeTab === 'transaction' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden' }}"></span>
                        </a>
                        <a href="{{ route('recycle-bin.tax-returns.index') }}"
                           class="relative py-3 px-4 text-sm font-medium {{ $activeTab === 'tax' ? 'text-blue-900 font-extrabold' : 'text-gray-500' }}">
                            Tax Returns
                            <span class="{{ $activeTab === 'tax' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden' }}"></span>
                        </a>
                        <a href="{{ route('recycle-bin.contacts.index') }}"
                           class="relative py-3 px-4 text-sm font-medium {{ $activeTab === 'contacts' ? 'text-blue-900 font-extrabold' : 'text-gray-500' }}">
                            Contacts
                            <span class="{{ $activeTab === 'contacts' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden' }}"></span>
                        </a>
                        <a href="{{ route('recycle-bin.employees.index') }}"
                           class="relative py-3 px-4 text-sm font-medium {{ $activeTab === 'employees' ? 'text-blue-900 font-extrabold' : 'text-gray-500' }}">
                            Employees
                            <span class="{{ $activeTab === 'employees' ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden' }}"></span>
                        </a>
                    </nav>
                    <hr class="mx-1 mt-auto">

                    <div class="flex flex-col md:flex-row justify-between">
                        <div class="w-full mt-8 ml-0 h-auto border border-zinc-300 rounded-lg p-4 bg-white">
                            <div x-data="recycleBinHandler">
                                <!-- Search and Sort Options -->
                                <div class="flex flex-row items-center">
                                    <div class="relative w-80 p-5">
                                        <form x-target="contacts-table" action="{{ route('recycle-bin.contacts.index') }}" method="GET" role="search" aria-label="Table" autocomplete="off">
                                            <input 
                                            type="search" 
                                            name="search" 
                                            value="{{ request('search') }}"
                                            class="w-full pl-10 pr-4 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-900 focus:border-blue-900" 
                                            aria-label="Search Term" 
                                            placeholder="Search..." 
                                            @input.debounce="$el.form.requestSubmit()" 
                                            @search="$el.form.requestSubmit()"
                                            >
                                            </form>
                                        <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-zinc-400"></i>
                                    </div>

                                    <!-- Sort by Dropdown -->
                                    <div class="relative inline-block text-left sm:w-auto">
                                        <button id="sortButton" class="flex items-center text-zinc-600 w-full">
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

                                    <!-- Bulk Action Buttons -->
                                    <div class="ml-auto flex flex-row items-center space-x-4">
                                        <button @click="confirmBulkRestore()" class="border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm flex items-center hover:border-blue-500 hover:text-blue-500 hover:bg-blue-100 transition space-x-1 group">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition group-hover:text-blue-500" viewBox="0 0 24 24"><path fill="currentColor" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.28 2.54l.72-1.21l-3.5-2.08V8z"/></svg>
                                            <span class="text-zinc-600 transition group-hover:text-blue-500">Restore</span>
                                        </button>
                                        <button @click="confirmBulkDelete()" class="border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm flex items-center hover:border-red-500 hover:text-red-500 hover:bg-red-100 transition space-x-1 group">
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
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">

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
                                                <th class="text-left py-3 px-4 font-semibold text-sm">TIN</th>
                                                <th class="text-left py-3 px-4 font-semibold text-sm">Contact Type</th>
                                                <th class="text-left py-3 px-4 font-semibold text-sm">Address</th>
                                                <th class="text-left py-3 px-4 font-semibold text-sm">Date Deleted</th>
                                                <th class="text-left py-3 px-4 font-semibold text-sm">Deleted by</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700">
                                            @if($trashedContacts->isEmpty())
                                                <tr>
                                                    <td colspan="8" class="text-center p-4">
                                                        <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-48 h-48" />
                                                        <h1 class="font-extrabold">No Deleted Contacts yet</h1>
                                                        <p class="text-sm text-neutral-500 mt-2">Deleted items will show up here when you need to restore or permanently delete them.</p>
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach($trashedContacts as $contact)
                                                    <tr class="hover:bg-slate-100 cursor-pointer ease-in-out">
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
                                                        <td>
                                                            <button x-data 
                                                                x-on:click="$dispatch('open-view-contact-modal', { id: '{{ $contact->id }}', bus_name: '{{ $contact->bus_name ?? 'N/A' }}', 
                                                                contact_type: '{{ $contact->contact_type }}', contact_tin: '{{ $contact->contact_tin }}', classification: '{{ $contact->classification }}', contact_address: '{{ $contact->contact_address }}', 
                                                                contact_city: '{{ $contact->contact_city }}', contact_zip: '{{ $contact->contact_city }}'})"
                                                                class="text-left py-4 px-4 font-bold underline hover:text-blue-600">
                                                                {{ $contact->bus_name ?? 'N/A' }}
                                                            </button>
                                                        </td>
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
                                {{-- View Details --}}
                                <div x-data="{ showContact: false, contact: {} }" x-show="showContact"
                                    @open-view-contact-modal.window="showContact = true; contact = $event.detail" x-on:close-modal.window="showContact = false"
                                    x-effect="document.body.classList.toggle('overflow-hidden', showContact)" class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6" x-cloak>
                                    <!-- Modal background -->
                                    <div class="fixed inset-0 bg-gray-200 opacity-50"></div>
                                    <!-- Modal container -->
                                    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden"
                                    x-show="showContact" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                                        <!-- Modal header -->
                                        <div class="relative p-3 bg-blue-900 border-opacity-80 w-full">
                                            <h1 class="text-lg font-bold text-white text-center">Contact Details</h1>
                                            <button @click="showContact = false" class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 8L16 16M8 16L16 8" stroke="#1e3a8a" class="transition duration-200 hover:stroke-gray-600"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="p-10">
                                            <div class="mb-5 flex justify-between items-start">
                                                <div class="w-2/3 pr-4">
                                                    <label class="block text-sm font-bold text-zinc-700">Name</label>
                                                    <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                                                        x-bind:value="contact.bus_name" disabled readonly>
                                                </div>
                                                <div class="w-2/3 text-left">
                                                    <label class="block text-sm font-bold text-zinc-700">Contact Type</label>
                                                    <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                                                        x-bind:value="contact.contact_type" disabled readonly>
                                                </div>
                                            </div>
                                            <div class="mb-5 flex justify-between items-start">
                                                <div class="w-2/3 pr-4">
                                                    <label class="block text-sm font-bold text-zinc-700">TIN</label>
                                                    <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                                                        x-bind:value="contact.contact_tin" disabled readonly>
                                                </div>
                                                <div class="w-2/3 pr-4">
                                                    <label class="block text-sm font-bold text-zinc-700">Classification</label>
                                                    <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                                                        x-bind:value="contact.classification" disabled readonly>
                                                </div>
                                            </div>
                                            <div class="mb-5 flex justify-between items-start">
                                                <div class="w-2/3 pr-4">
                                                    <label class="block text-sm font-bold text-zinc-700">Address</label>
                                                    <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                                                        x-bind:value="contact.contact_address" disabled readonly>
                                                </div>
                                                <div class="w-2/3 text-left">
                                                    <label class="block text-sm font-bold text-zinc-700">City</label>
                                                    <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                                                        x-bind:value="contact.contact_city" disabled readonly>
                                                </div>
                                                <div class="w-2/3 space-x-2 text-left">
                                                    <label class="block text-sm font-bold text-zinc-700">Zip Code</label>
                                                    <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                                                        x-bind:value="contact.contact_zip" disabled readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Delete --}}
                                <div x-show="showConfirmDeleteModal" class="fixed inset-0 bg-gray-200 z-50 bg-opacity-50 flex justify-center items-center" @click.away="showConfirmDeleteModal = false"
                                    x-effect="document.body.classList.toggle('overflow-hidden', showConfirmDeleteModal)" x-cloak>
                                    <div class="bg-white p-10 rounded-lg shadow-lg max-w-lg w-full relative">
                                        <button @click="closeModal()" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                        <div class="flex justify-start mb-4">
                                            <i class="fas fa-exclamation-triangle text-red-500 text-8xl"></i>
                                        </div>
                                        <h2 class="text-xl text-zinc-700 font-bold text-start mb-4">Permanently Delete Contact(s)</h2>
                                        <p class="text-start mb-6 text-sm text-zinc-700">Are you sure you want to permanently delete the selected contact(s) to the recycle bin?</p>
                                        <div class="bg-red-100 border-l-8 border-red-500 text-red-500 p-6 rounded-lg mb-6">
                                            <ul class="list-disc pl-5 text-[13px]">
                                                <li class="pl-2">
                                                    <span class="inline-block align-top">This action cannot be undone, and the contact(s) will be completely removed from the system.</span>
                                                </li>
                                                <li class="pl-2">
                                                    <span class="inline-block align-top">Any process or reports tied to this contact(s) will be affected.</span>
                                                </li>
                                                <li class="pl-2">
                                                    <span class="inline-block align-top">Proceed only if you’re sure this contact(s) should<br />be removed from access.</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="flex justify-end gap-4">
                                            <button @click="closeModal()" class="mr-2 font-semibold text-zinc-600 px-3 py-1 rounded-md hover:text-zinc-900 transition">Cancel</button>
                                            <button @click="bulkDelete(); closeModal();" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-1.5 px-5 rounded-lg">Permanently Delete</button>
                                        </div>
                                    </div>
                                </div>

                                {{-- Restore --}}
                                <div x-show="showConfirmRestoreModal" class="fixed inset-0 z-50 bg-gray-200 bg-opacity-50 flex justify-center items-center" @click.away="showConfirmRestoreModal = false"
                                    x-effect="document.body.classList.toggle('overflow-hidden', showConfirmRestoreModal)" x-cloak>
                                    <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full relative">
                                        <div class="flex flex-col items-center">
                                            <button @click="closeModal()" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                            <div class="mb-4">
                                                <i class="fas fa-exclamation-triangle text-blue-600 text-8xl"></i>
                                            </div>
                                            <h2 class="text-2xl font-extrabold text-blue-600 mb-2">Restore Contact(s)</h2>
                                            <p class="text-sm text-zinc-700 text-center">You're going to restore the selected contact(s) in the Recycle Bin table. Are you sure?</p>
                                            <div class="flex justify-center space-x-8 mt-6 w-full">
                                                <button @click="closeModal()" class="px-4 py-2 rounded-lg text-sm text-zinc-600 hover:text-zinc-900 font-bold transition">Cancel</button>
                                                <button @click="bulkRestore(); closeModal();" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm transition">Restore</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Action buttons Inalis ko muna yung iba, ayaw kasi gumana; should be the same with other tables--}}
                                {{-- <div class="flex justify-center py-4" x-cloak>
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
                                    <!-- Restore and cancel buttons -->
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
                                            <span class="text-zinc-600 transition group-hover:text-zinc-800">Restore Selected</span><span x-text="selectedCount > 0 ? '(' + selectedCount + ')' : ''"></span>
                                        </button>
                                        <button @click="cancelSelection(); enableButtons();" class="border px-3 py-2 mx-2 rounded-lg text-sm text-neutral-600 hover:bg-neutral-100 transition">
                                            Cancel
                                        </button>
                                    </div>
                                </div> --}}
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

    // FOR SHOW ENTRIES
     // FOR BUTTON OF SHOW ENTRIES
    document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
        const dropdown = document.getElementById('dropdownDots');
        dropdown.classList.toggle('hidden');
    });
    // FOR SHOWING/SETTING ENTRIES
    function setEntries(entries) {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = "{{ route('recycle-bin.contacts.index') }}";
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

</x-organization-layout>
