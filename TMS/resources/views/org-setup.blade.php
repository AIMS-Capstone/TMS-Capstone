<x-organization-layout>
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-xs">
                <div class="overflow-x-auto pt-6 px-10">
                    <p class="font-bold text-3xl taxuri-color">
                        Organizations
                    </p>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">            
                            <p class="font-normal text-sm">All created organizations, whether for business or clients, are listed on this page for easy <br /> 
                                identification and management. Select an organization to start a session.</p>
                        </div>
                        <div class="items-end float-end">
                            <!-- routing for create org -->
                            <a href = {{ route('create-org') }}>
                            <button type="button" class= "text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                                <i class="fas fa-plus-circle mr-1"></i>
                                    Create Organization
                            </button>   
                            </a> 
                        </div>
                    </div> 

                    <div class="flex flex-col md:flex-row justify-between">
                        {{-- Left Metrics --}}
                        <div class="w-full md:w-[30%] max-w-[300px] max-h-[500px] bg-zinc-100 mt-8 py-[13px] px-[13px] rounded-lg">
                            <!-- First Item -->
                            <div class="flex items-center mb-4">
                                {{-- insert total org number --}}
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                   {{$orgSetupCount}}
                                </div>
                                <div class="ml-4">
                                    <div class="text-zinc-800 font-bold text-sm sm:text-base">Total Organizations</div>
                                    <div class="text-zinc-500 text-xs sm:text-sm">Total number of organizations<br/>currently managed</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Second Item -->
                            <div class="flex items-center my-4">
                                {{-- insert total number of all individual clients --}}
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    {{$individualClients}}
                                </div>
                                <div class="ml-4">
                                    <div class="text-zinc-800 font-bold text-sm sm:text-base">Individual Clients</div>
                                    <div class="text-zinc-500 text-xs sm:text-sm">Total number of individual<br/>clients registered</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Third Item -->
                            <div class="flex items-center my-4">
                                {{-- insert total number of non-individual clients --}}
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    {{$nonIndividualClients}}
                                </div>
                                <div class="ml-4">
                                    <div class="text-zinc-800 font-bold text-sm sm:text-base">Non-Individual Clients</div>
                                    <div class="text-zinc-500 text-xs sm:text-sm">Total number of non-individual<br/> clients registered</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Fourth Item -->
                            <div class="flex items-center my-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    {{$filedTaxReturnsCount}}
                                </div>
                                <div class="ml-4">
                                    <div class="text-zinc-800 font-bold text-sm sm:text-base">Filed Taxes</div>
                                    <div class="text-zinc-500 text-xs sm:text-sm">Total number of completed<br/>tax filings</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Fifth Item -->
                            <div class="flex items-center my-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    {{$unfiledTaxReturnsCount}}
                                </div>
                                <div class="ml-4">
                                    <div class="text-zinc-800 font-bold text-sm sm:text-base">Unfiled Taxes</div>
                                    <div class="text-zinc-500 text-xs sm:text-sm">Total number of pending<br/>tax filings</div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Right Table --}}
                        <div class="w-full md:w-3/4 mt-8 ml-0 md:ml-8 h-auto border border-zinc-300 rounded-lg p-4 bg-white">
                            <div class="flex flex-row items-center">
                                <!-- Search row -->
                                <div class="relative w-80 p-5">
                                    <form x-target="tableid" action="/org-setup" role="search" aria-label="Table" autocomplete="off">
                                        <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-sky-900 focus:border-sky-900" 
                                        aria-label="Search Term" 
                                        placeholder="Search..." 
                                        @input.debounce="$el.form.requestSubmit()" 
                                        @search="$el.form.requestSubmit()"
                                        >
                                        </form>
                                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-zinc-400"></i>
                                </div>
                    
                                <!-- Sort by dropdown -->
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

                                {{-- Right side: Set as Session button and Dropdown --}}
                                <div class="ml-auto flex flex-row items-center space-x-4">
                                    <div class="relative inline-block text-left sm:w-auto">
                                        <button id="setSessionButton" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M6.25 5C5.56 5 5 5.56 5 6.25v11.5c0 .69.56 1.25 1.25 1.25h11.5c.69 0 1.25-.56 1.25-1.25V14a1 1 0 1 1 2 0v3.75A3.25 3.25 0 0 1 17.75 21H6.25A3.25 3.25 0 0 1 3 17.75V6.25A3.25 3.25 0 0 1 6.25 3H10a1 1 0 1 1 0 2zM14 5a1 1 0 1 1 0-2h6a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0V6.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L17.586 5z"/>
                                            </svg>
                                            <span id="selectedOption" class="font-normal text-md">Set as Session</span>
                                        </button>
                                    </div>
                            
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
                    
                            <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">
                        
                            <div class="my-4 overflow-y-auto h-auto">
                                <table class="min-w-full bg-white" id="tableid">
                                    <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                                        <tr>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Tax Type</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Classification</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Account Status</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto">
                                        @if (count($orgsetups) > 0)
                                            @foreach ($orgsetups as $organization)
                                            <tr id="row-{{ $organization->id }}" class="hover:bg-slate-100 cursor-pointer ease-in-out" onclick="selectRow({{ $organization->id }})">
                                                <form action="{{ route('org-dashboard') }}" method="POST" id="form-{{ $organization->id }}">
                                                    @csrf
                                                    <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                                                    <td class="text-left py-[7px] px-4">
                                                        {{ $organization->registration_name }}<br/>{{ $organization->tin }}
                                                    </td>
                                                    <td class="text-left py-[7px] px-4">{{ $organization->tax_type }}</td>
                                                    <td class="text-left py-[7px] px-4">{{ $organization->type }}</td>
                                                    <td class="text-left py-[7px] px-4">
                                                        @if ($organization->account) 
                                                            <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-4 py-2 rounded-full">Account Active</span>
                                                        @else
                                                            <span class="bg-zinc-100 text-zinc-800 text-xs font-medium me-2 px-4 py-2 rounded-full">No Account Yet</span>
                                                        @endif
                                                    </td>
                                                    <td class="relative text-left py-2 px-3">
                                                        <button type="button" id="dropdownMenuAction-{{ $organization->id }}" class="text-zinc-500 hover:text-zinc-700">
                                                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                            </svg>
                                                        </button>
                                                        <div id="dropdownAction-{{ $organization->id }}" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-40 origin-top-right overflow-hidden max-h-64 overflow-y-auto">
                                                            <div class="py-2 px-2 text-sm text-zinc-700" aria-labelledby="dropdownMenuAction">
                                                                {{-- <div onclick="editOrganization('{{ $organization->id }}')" class="block px-4 py-2 w-full text-left hover-dropdown">Edit</div> --}}
                                                                {{-- Deafult selection in action button kapag status ay "No Account Yet" --}}
                                                                @if (!$organization->account)
                                                                <div x-data 
                                                                    x-on:click="$dispatch('open-generate-modal', { organizationId: '{{ $organization->id }}' })" class="block px-4 py-2 w-full text-left hover-dropdown">
                                                                    Create Account
                                                                </div>
                                                            @endif
                                                            <div 
                                                            x-data 
                                                            x-on:click="$dispatch('open-delete-modal', { organizationId: '{{ $organization->id }}', organizationName: '{{ $organization->registration_name }}' })"  
                                                            class="block px-4 py-2 w-full text-left hover-dropdown text-red-500 cursor-pointer">
                                                            Delete
                                                        </div>
                                                        </div>
                                                    </td>
                                                </form>
                                            </tr>
                                        </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center p-2">
                                                    <img src="{{ asset('images/no-account.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                    <h1 class="font-extrabold">No Organization yet</h1>
                                                    <p class="text-sm text-neutral-500 mt-2">Start creating organizations with the <br> + Create Organization button.</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{ $orgsetups->appends(request()->input())->links('vendor.pagination.custom') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div x-data="{ open: false, organizationId: null }" 
    @open-generate-modal.window="open = true; organizationId = $event.detail.organizationId" 
    x-cloak>
   <div x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
       <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
           <h2 class="text-lg font-semibold mb-4">Create Client Account</h2>
           
           <form method="POST" action="{{ route('org_accounts.store') }}">
               @csrf
               
               <!-- Organization ID (hidden) -->
               <input type="hidden" name="org_setup_id" :value="organizationId">

             
               <small class="text-gray-700 text-xs">The client must first provide their Email Address to the accounting firm in order to access their account. Creating an account for the client is optional, but once an account is created, relevant tax-related information will be reflected in the account, allowing the client to view and generate various reports.
            </small>
               <div class="mb-4">
                   <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                   <input type="email" id="email" name="email" class="mt-1 block w-full p-2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
               </div>



               <div class="flex justify-end">
                   <button type="button" @click="open = false" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">Cancel</button>
                   <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg">Create Account</button>
               </div>
           </form>
       </div>
   </div>
</div>

<!-- Delete Confirmation Modal -->
<div x-data="{ open: false, organizationId: null, organizationName: '' }" 
    @open-delete-modal.window="open = true; organizationId = $event.detail.organizationId; organizationName = $event.detail.organizationName" 
    x-cloak>
    <div x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-lg font-semibold mb-4">Are you sure?</h2>
            <p class="mb-4">Do you really want to delete <strong x-text="organizationName"></strong>? This action cannot be undone.</p>
            <div class="flex justify-end">
                <button type="button" @click="open = false" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">Cancel</button>
                <form method="POST" action="{{ route('orgSetup.destroy') }}" id="delete-form" class="inline">
                    @csrf
                    <input type="hidden" name="organization_id" :value="organizationId">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <script>
        // FOR SORT BUTTON

        document.getElementById('sortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        });

        // FOR SORT BY
        function sortItems(criteria) {
            const table = document.querySelector('table tbody');
            const rows = Array.from(table.querySelectorAll('tr'));
            let sortedRows;
            if (criteria === 'recently-added') {
                // Sort by the order of rows (assuming they are in the order of addition)
                sortedRows = rows.reverse();
            } else {
                // Sort by text content of the first column
                sortedRows = rows.sort((a, b) => {
                    const aText = a.querySelector('td').textContent.trim().toLowerCase();
                    const bText = b.querySelector('td').textContent.trim().toLowerCase();

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
        // to sort options
        document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                sortItems(criteria);
            });
        });

        // FOR BUTTON OF SHOW ENTRIES
        document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownDots');
            dropdown.classList.toggle('hidden');
        });

        // FOR ACTION BUTTON
        document.querySelectorAll('[id^="dropdownMenuAction-"]').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.id.split('-')[1];
                const dropdown = document.getElementById(`dropdownAction-${id}`);
                dropdown.classList.toggle('hidden');
            });
        });

        // FOR SHOWING/SETTING ENTRIES
        function setEntries(entries) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('org-setup') }}";
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

        // FOR SELECTING A SESSION
        let selectedRowId = null;
        function selectRow(id) {
            // If there's a previously selected row, reset its style
            if (selectedRowId) {
                document.getElementById('row-' + selectedRowId).classList.remove('bg-blue-100');
            }
            // Set the selected row and apply styles
            selectedRowId = id;
            document.getElementById('row-' + selectedRowId).classList.add('bg-blue-100');
            // Enable the "Set as Session" button and change it to blue
            const setSessionButton = document.getElementById('setSessionButton');
            setSessionButton.disabled = false;
            setSessionButton.classList.remove('border-gray-300', 'text-gray-600', 'hover:bg-blue-200', 'hover:border-blue-700');
            setSessionButton.classList.add('bg-blue-200', 'text-blue-700', 'border-blue-700');
        }
    </script>
</x-organization-layout>