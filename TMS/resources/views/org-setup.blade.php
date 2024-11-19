<x-organization-layout>
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-xs">
                <div class="overflow-x-auto pt-6 px-10">
                    <p class="font-bold text-3xl taxuri-color">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-8 h-8 justify-center" viewBox="0 0 16 16"><path fill="#1e3a8a" d="M7 14s-1 0-1-1s1-4 5-4s5 3 5 4s-1 1-1 1zm4-6a3 3 0 1 0 0-6a3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5a2.5 2.5 0 0 0 0 5"/></svg>
                        Organizations
                    </p>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">            
                            <p class="text-zinc-700 font-normal text-sm">All created organizations, whether for business or clients, are listed on this page for easy <br /> 
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
                                        class="w-full pl-10 pr-4 py-2 text-sm border border-zinc-300 rounded-lg focus:outline-none focus:ring-blue-900 focus:border-blue-900" 
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
                                                    <td class="overflow-visible relative text-left py-2 px-3">
                                                        <button type="button" id="dropdownMenuAction-{{ $organization->id }}" class="text-zinc-500 hover:text-zinc-700">
                                                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                            </svg>
                                                        </button>
                                                        <div id="dropdownAction-{{ $organization->id }}" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-40 origin-top-right overflow-y-auto max-h-64">
                                                            <div class="py-2 px-2 text-sm text-zinc-700" aria-labelledby="dropdownMenuAction">
                                                                <div x-data x-on:click="$dispatch('open-view-org-modal', { organization: {{ $organization->toJson() }} })" class="block px-4 py-2 w-full text-left hover-dropdown">View Details</div>
                                                                <div  x-data x-on:click="$dispatch('open-edit-org-modal', { organizationId: '{{ $organization->id }}' })" class="block px-4 py-2 w-full text-left hover-dropdown">Edit</div>
                                                                @if (!$organization->account)
                                                                <div x-data x-on:click="$dispatch('open-generate-modal', { organizationId: '{{ $organization->id }}' })" class="block px-4 py-2 w-full text-left hover-dropdown">
                                                                    Create Account
                                                                </div>
                                                            @endif
                                                            <div x-data x-on:click="$dispatch('open-delete-modal', { organizationId: '{{ $organization->id }}', organizationName: '{{ $organization->registration_name }}' })"  
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

    {{-- View Modal --}}
    <div x-data="{ showOrg: false, organization: {}, formatDate(date) {const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString(undefined, options); } }"
        x-show="showOrg"
        @open-view-org-modal.window="showOrg = true; organization = $event.detail.organization" 
        x-on:close-modal.window="showOrg = false"
        x-effect="document.body.classList.toggle('overflow-hidden', showOrg)"
        class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
        x-cloak>
        <!-- Modal background -->
        <div class="fixed inset-0 bg-gray-200 opacity-50"></div>
        <!-- Modal container -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-auto h-auto z-10 overflow-hidden"
         x-show="showOrg" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <!-- Modal header -->
            <div class="relative p-3 bg-blue-900 border-opacity-80 w-full">
                <h1 class="text-lg font-bold text-white text-center">Organization Details</h1>
                <button @click="showOrg = false" class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 8L16 16M8 16L16 8" stroke="#1e3a8a" class="transition duration-200 hover:stroke-gray-600"/>
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-10">
                <div class="grid grid-cols-3 gap-6 mb-5">
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Classification</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.type" disabled readonly>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Registered Name</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.registration_name" disabled readonly>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Line of Business</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.line_of_business" disabled readonly>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-6 mb-5">
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Address Line</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.address_line" disabled readonly>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Region</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.region" disabled readonly>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">City</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.city" disabled readonly>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-6 mb-5">
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Zip Code</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.zip_code" disabled readonly>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Contact Number</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.contact_number" disabled readonly>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Email Address</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.email" disabled readonly>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-6 mb-5">
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">TIN</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.tin" disabled readonly>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">RDO</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.rdo" disabled readonly>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Tax Type</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.tax_type" disabled readonly>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-6 mb-5">
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Start Date</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="formatDate(organization.start_date)" disabled readonly>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Registration Date</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="formatDate(organization.registration_date)" disabled readonly>
                    </div>
                    <div class="w-full">
                        <label class="block text-sm font-bold text-zinc-700">Financial Year End</label>
                        <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 truncate border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200"
                            x-bind:value="organization.financial_year_end" disabled readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Edit Modal: Shows Error with "PUT" + not sure about the action + Selections of Address and RDO --}}
    <div x-data="{ showEdit: false, organization: {} }"
        x-show="showEdit"
        @open-edit-org-modal.window="showEdit = true; organization = $event.detail.organization" 
        x-on:close-modal.window="showEdit = false"
        x-effect="document.body.classList.toggle('overflow-hidden', showEdit)"
        class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
        x-cloak>
        <!-- Modal background -->
        <div class="fixed inset-0 bg-gray-200 opacity-50"></div>
        <!-- Modal container -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-auto h-auto z-10 overflow-hidden"
         x-show="showEdit" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <!-- Modal header -->
            <div class="relative p-3 bg-blue-900 border-opacity-80 w-full">
                <h1 class="text-lg font-bold text-white text-center">Edit Organization Details</h1>
            </div>
            <!-- Modal body -->
            <div class="p-10">
                <form id="editAccountForm" method="POST" :action="'/org-setup/' + organziation.id">
                    @csrf
                    @method('PUT')
    
                    <div class="grid grid-cols-3 gap-6 mb-5">
                        <div class="w-full">
                            <label class="block text-sm font-bold text-zinc-700">Address Line<span class="text-red-500">*</span></label>
                            <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                        </div>
                        <div class="w-full">
                            <label class="block text-sm font-bold text-zinc-700">Region<span class="text-red-500">*</span></label>
                            {{-- PLace the region selection here create-org --}}
                            <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                        </div>
                        <div class="w-full">
                            <label class="block text-sm font-bold text-zinc-700">City<span class="text-red-500">*</span></label>
                            {{-- Place the city selection here from create-org --}}
                            <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6 mb-5">
                        <div class="w-full">
                            <label class="block text-sm font-bold text-zinc-700">Zip Code<span class="text-red-500">*</span></label>
                            {{-- Place the same zip code autofill from create-org --}}
                            <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                        </div>
                        <div class="w-full">
                            <label class="block text-sm font-bold text-zinc-700">RDO<span class="text-red-500">*</span></label>
                            {{-- Place the selection of RDO here --}}
                            <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-6 mb-5">
                        <div class="w-full">
                            <label class="block text-sm font-bold text-zinc-700">Contact Number<span class="text-red-500">*</span></label>
                            <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                        </div>
                        <div class="w-full">
                            <label class="block text-sm font-bold text-zinc-700">Email Address<span class="text-red-500">*</span></label>
                            <input class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                        </div>
                    </div>
                    
    
                    <!-- Submit -->
                    <div class="flex justify-end my-4">
                        <button 
                            x-on:click="$dispatch('close-modal')"
                            class="mr-2 font-semibold text-zinc-600 px-3 py-1 rounded-md hover:text-zinc-900 transition">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="font-semibold bg-blue-900 text-white text-center px-6 py-1.5 rounded-md hover:bg-blue-950 border-blue-900 hover:text-white transition">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CREATE CLIENT ACCOUNT WITH SUCCESS MODAL NA MABAGAL. I prefer the modal above kasi nakikita na nagpprocess yung submission--}}
    <div x-data="{ open: false, organizationId: null, email: '', success: false, 
               isEmailValid() { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email); } }" 
        @open-generate-modal.window="open = true; organizationId = $event.detail.organizationId" 
        @submit-success.window="open = false; success = true"
        x-effect="document.body.classList.toggle('overflow-hidden', open || success)"
        x-cloak>
        
        <!-- Create Account Modal -->
        <div x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden" x-show="open" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <!-- Modal header -->
                <div class="flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto">
                    <h1 class="text-lg font-bold text-white">Create Client Account</h1>
                </div>
                <!-- Modal Body -->
                <div class="p-10">
                    <form method="POST" action="{{ route('org_accounts.store') }}" @submit.prevent="submitForm">
                        @csrf
                        <!-- Organization ID (hidden) -->
                        <input type="hidden" name="org_setup_id" :value="organizationId">

                        <div class="text-zinc-500 text-xs leading-4 mb-4">
                            The client must first provide their <b class="text-zinc-700">Email Address</b> to the accounting firm in order to access their account. Creating an account for the client is optional, but once an account is created, relevant tax-related information will be reflected in the account, allowing the client to view and generate various reports.
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-bold text-zinc-700">Email Address<span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" x-model="email" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                placeholder="Email Address" maxlength="100" required>
                        </div>

                        <div class="flex justify-end mt-10">
                            <button type="button" @click="open = false" class="mr-4 font-semibold text-zinc-700 px-3 py-1 rounded-md hover:text-zinc-900 transition">Cancel</button>
                            <button type="submit" 
                                    :disabled="!isEmailValid()"
                                    class="font-semibold bg-blue-900 text-white text-center px-6 py-1.5 rounded-md hover:bg-blue-950 border-blue-900 hover:text-white transition disabled:bg-gray-300 disabled:cursor-not-allowed">
                                Create Account
                            </button>                   
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div x-show="success" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto h-auto z-10 overflow-hidden p-10 relative">
                <!-- Close Button -->
                <button @click="success = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <!-- Success Modal Header (Centered Image) -->
                <div class="flex justify-center mb-4">
                    <img src="{{ asset('images/Success.png') }}" alt="Organization Added" class="w-28 h-28">
                </div>
                <!-- Success Modal Body -->
                <div class="text-center">
                    <p class="text-emerald-500 font-bold text-3xl mb-2">Client Account Created</p>
                    <p class="text-sm text-zinc-700 mb-6">
                        The account for <strong x-text="organizationName"></strong> has been successfully created. An email has been sent to <strong x-text="organizationEmail"></strong> with login details and a link to access their account.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-data="{ open: false, organizationId: null, organizationName: '' }" 
        @open-delete-modal.window="open = true; organizationId = $event.detail.organizationId; organizationName = $event.detail.organizationName"
        x-effect="document.body.classList.toggle('overflow-hidden', open)" 
        x-cloak>
        <div x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
            <div class="bg-white p-10 rounded-lg shadow-lg max-w-lg w-full relative" x-show="open" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <!-- Close Button -->
                <button @click="open = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <!-- Warning Icon -->
                <div class="flex justify-start mb-4">
                    <i class="fas fa-exclamation-triangle text-red-500 text-8xl"></i>
                </div>
                <!-- Title -->
                <h2 class="text-xl text-zinc-700 font-bold text-start mb-4">Confirm Delete Organization</h2>
                <!-- Description -->
                <p class="text-start mb-6 text-sm text-zinc-700">Are you sure you want to move the organization <br /><strong x-text="organizationName"></strong> to the recycle bin?</p>
                <!-- Warning Box -->
                <div class="bg-red-100 border-l-8 border-red-500 text-red-500 p-6 rounded-lg mb-6">
                    <ul class="list-disc pl-5 text-[13px]">
                        <li class="pl-2">
                            <span class="inline-block align-top">This action will immediately hide the organization<br />and all its data from active views.</span>
                        </li>
                        <li class="pl-2">
                            <span class="inline-block align-top">The organization can be restored later, but it will be<br />inaccessible until then.</span>
                        </li>
                        <li class="pl-2">
                            <span class="inline-block align-top">Proceed only if you’re sure this organization should<br />be removed from regular access.</span>
                        </li>
                    </ul>
                </div>
                <!-- Action Buttons -->
                <div class="flex justify-end gap-4">
                    <button type="button" @click="open = false" class="mr-2 font-semibold text-zinc-600 px-3 py-1 rounded-md hover:text-zinc-900 transition">Cancel</button>
                    <form method="POST" action="{{ route('orgSetup.destroy') }}" id="delete-form" class="inline">
                        @csrf
                        <input type="hidden" name="organization_id" :value="organizationId">
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-semibold py-1.5 px-5 rounded-lg">Delete</button>
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

    
        function submitForm() {
            // Example asynchronous submission (AJAX)
            fetch('{{ route("org_accounts.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                },
                body: JSON.stringify({ 
                    org_setup_id: this.organizationId, 
                    email: this.email 
                })
            })
            .then(response => {
                if (response.ok) {
                    // Emit success event
                    window.dispatchEvent(new CustomEvent('submit-success'));
                } else {
                    alert("Failed to create account.");
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
</x-organization-layout>