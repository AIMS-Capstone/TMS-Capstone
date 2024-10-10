<x-organization-layout>
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <nav class="flex gap-x-4 overflow-x-auto ml-10" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                    <button type="button" class="py-3 px-4 inline-flex items-center gap-x-4 text-sm font-medium text-center text-gray-500 hover:text-blue-900 rounded-lg"
                        id="tab-org"
                        role="tab"
                        aria-selected="true"
                        onclick="activateTab('tab-org')">
                        Organizations
                    </button>
                    <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-center text-gray-500 hover:text-blue-900 rounded-lg"
                        id="tab-acc"
                        role="tab"
                        aria-selected="false"
                        onclick="activateTab('tab-acc')">
                        User Management
                    </button>
                </nav>
    
                <hr class="mx-8 mt-4">

                {{-- Organizations TAB --}}
                <div id="tab-org-content" role="tabpanel" aria-labelledby="tab-org" class="overflow-x-auto pt-6 px-10">
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
                            <button type="button" class= "text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                                <i class="fas fa-plus-circle mr-1"></i>
                                    Create Organization
                            </button>   
                            </a> 
                        </div>
                    </div> 

                    <div class="flex flex-col md:flex-row justify-between">
                        {{-- Left Metrics --}}
                        <div class="w-full md:w-[30%] max-w-[300px] max-h-[500px] bg-gray-100 mt-8 py-[13px] px-[13px] rounded-lg">
                            <!-- First Item -->
                            <div class="flex items-center mb-4">
                                {{-- insert total org number --}}
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    22
                                </div>
                                <div class="ml-4">
                                    <div class="text-gray-800 font-bold text-sm sm:text-base">Total Organizations</div>
                                    <div class="text-gray-500 text-xs sm:text-sm">Total number of organizations<br/>currently managed</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Second Item -->
                            <div class="flex items-center my-4">
                                {{-- insert total number of all individual clients --}}
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    5
                                </div>
                                <div class="ml-4">
                                    <div class="text-gray-800 font-bold text-sm sm:text-base">Individual Clients</div>
                                    <div class="text-gray-500 text-xs sm:text-sm">Total number of individual<br/>clients registered</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Third Item -->
                            <div class="flex items-center my-4">
                                {{-- insert total number of non-individual clients --}}
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    17
                                </div>
                                <div class="ml-4">
                                    <div class="text-gray-800 font-bold text-sm sm:text-base">Non-Individual Clients</div>
                                    <div class="text-gray-500 text-xs sm:text-sm">Total number of non-individual<br/> clients registered</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Fourth Item -->
                            <div class="flex items-center my-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    36
                                </div>
                                <div class="ml-4">
                                    <div class="text-gray-800 font-bold text-sm sm:text-base">Filed Taxes</div>
                                    <div class="text-gray-500 text-xs sm:text-sm">Total number of completed<br/>tax filings</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Fifth Item -->
                            <div class="flex items-center my-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    19
                                </div>
                                <div class="ml-4">
                                    <div class="text-gray-800 font-bold text-sm sm:text-base">Unfiled Taxes</div>
                                    <div class="text-gray-500 text-xs sm:text-sm">Total number of pending<br/>tax filings</div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Right Table --}}
                        <div class="w-full md:w-3/4 mt-8 ml-0 md:ml-8 border border-gray-300 rounded-lg p-4 bg-white">
                            <div class="flex flex-row items-center">
                                <!-- Search row -->
                                <div class="relative w-80 p-5">
                                    <form x-target="tableid" action="/org-setup" role="search" aria-label="Table" autocomplete="off">
                                        <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900" 
                                        aria-label="Search Term" 
                                        placeholder="Search..." 
                                        @input.debounce="$el.form.requestSubmit()" 
                                        @search="$el.form.requestSubmit()"
                                        >
                                        </form>
                                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <i class="fa-solid fa-xmark absolute right-8 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer" @click="search = ''"></i>
                                </div>
                    
                                <!-- Sort by dropdown -->
                                <div class="relative inline-block text-left sm:w-auto">
                                    <button id="sortButton" class="flex items-center text-gray-600 w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                            <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                                        </svg>
                                        <span id="selectedOption" class="font-normal text-md truncate">Sort by</span>
                                    </button>
                        
                                    <div id="dropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
                                        <div class="py-2 px-2">
                                            <span class="block px-4 py-2 text-sm font-bold text-gray-700">Sort by</span>
                                            <div data-sort="recently-added" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Added</div>
                                            <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                            <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Right side: Set as Session button and Dropdown --}}
                                <div class="ml-auto flex flex-row items-center space-x-4">
                                    <div class="relative inline-block text-left sm:w-auto">
                                        <button id="setSessionButton" class="flex items-center border border-gray-300 rounded-lg p-2 text-gray-600 w-full hover:text-blue-700 hover:bg-blue-200 focus:text-blue-700 hover:border-blue-700 focus:border-blue-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2 hover:text-blue-700 focus:text-blue-700" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="M6.25 5C5.56 5 5 5.56 5 6.25v11.5c0 .69.56 1.25 1.25 1.25h11.5c.69 0 1.25-.56 1.25-1.25V14a1 1 0 1 1 2 0v3.75A3.25 3.25 0 0 1 17.75 21H6.25A3.25 3.25 0 0 1 3 17.75V6.25A3.25 3.25 0 0 1 6.25 3H10a1 1 0 1 1 0 2zM14 5a1 1 0 1 1 0-2h6a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0V6.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L17.586 5z"/>
                                            </svg>
                                            <span id="selectedOption" class="font-normal text-md truncate">Set as Session</span>
                                        </button>
                                    </div>
                            
                                    <div class="relative inline-block space-x-4 text-left sm:w-auto">
                                        <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" class="flex items-center text-gray-600" type="button">
                                            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                            </svg>
                                        </button>
                            
                                        <div id="dropdownDots" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-44 origin-top-right">
                                            <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuIconButton">
                                                <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Show Entries</span>
                                                <div onclick="setEntries(5)" class="block px-4 py-2 w-full text-left hover-dropdown">5 per page</div>
                                                <div onclick="setEntries(25)" class="block px-4 py-2 w-full text-left hover-dropdown">25 per page</div>
                                                <div onclick="setEntries(50)" class="block px-4 py-2 w-full text-left hover-dropdown">50 per page</div>
                                                <div onclick="setEntries(100)" class="block px-4 py-2 w-full text-left hover-dropdown">100 per page</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <hr class="border-gray-300 w-[calc(100%+2rem)] mx-[-1rem]">
                    
                            <div class="my-4 overflow-x-auto">
                                <table class="min-w-full bg-white" id="tableid">
                                    <thead class="bg-zinc-100 text-zinc-700 font-extrabold">
                                        <tr>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Tax Type</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Classification</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">
                                                Account Status
                                                {{-- <span class="relative group items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block ml-1 w-4 h-4 text-zinc-700" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                                                    </svg>
                                                    <div class="hidden group-hover:block absolute left-1/2 transform -translate-x-1/2 mt-2 w-64 bg-white text-zinc-700 font-normal text-sm rounded-lg shadow-lg p-2 overflow-hidden">
                                                        Select and click an organization in this column to get started.
                                                    </div>
                                                </span> --}}
                                            </th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700">
                                        @if (count($orgsetups) > 0)
                                        @foreach ($orgsetups as $organization)
                                            <tr>
                                                <form action="{{ route('org-dashboard') }}" method="POST" id="form-{{ $organization->id }}">
                                                    @csrf
                                                    <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                                                    <td class="text-left py-3 px-4">
                                                        {{ $organization->registration_name }}<br/>{{ $organization->tin }}
                                                    </td>
                                                    <td class="text-left py-3 px-4">{{ $organization->tax_type }}</td>
                                                    <td class="text-left py-3 px-4">{{ $organization->type }}</td>
                                                    <td class="text-left py-3 px-4 text-blue-600 underline" onclick="document.getElementById('form-{{ $organization->id }}').submit();">
                                                        <button type="submit" class="text-blue-600 hover:translate-x-1 transition-transform duration-300 underline flex items-center space-x-1">Open Organization
                                                            <span class="hover:translate-x-1 transition-transform duration-300">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 group-hover:fill-current group-hover:text-blue-800 transition-colors duration-300" viewBox="0 0 16 16">
                                                                    <path fill="#2563eb" d="M8.22 2.97a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.042-.018a.75.75 0 0 1-.018-1.042l2.97-2.97H3.75a.75.75 0 0 1 0-1.5h7.44L8.22 4.03a.75.75 0 0 1 0-1.06"/>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    </td>
                                                    <td class="relative text-left py-2 px-3">
                                                        <button type="button" id="dropdownMenuAction" class="text-gray-500 hover:text-gray-700">
                                                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                            </svg>
                                                        </button>
                                                        <div id="dropdownAction" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-32 origin-top-right overflow-hidden max-h-64 overflow-y-auto">
                                                            <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuAction">
                                                                <div onclick="deleteOrganization('{{ $organization->id }}')" class="block px-4 py-2 w-full text-left hover-dropdown">Edit</div>
                                                                <div onclick="deleteOrganization('{{ $organization->id }}')" class="block px-4 py-2 w-full text-left hover-dropdown">Delete</div>
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
                                                    <img src="{{ asset('images/Wallet 02.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                                    <h1 class="font-bold">No Organization yet</h1>
                                                    <p class="text-sm text-neutral-500 mt-2">Start creating organizations with the <br> + Create Organization button.</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{ $orgsetups->links() }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- User Management --}}
                <div id="tab-acc-content" role="tabpanel" aria-labelledby="tab-acc" class="overflow-x-auto pt-6 px-10">
                    <p class="font-bold text-3xl taxuri-color">
                        User Management
                    </p>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">            
                            <p class="font-normal text-sm">This page allows the Admin to efficiently manage all user accounts in Taxuri.<br/> The page displays a list of each type of account.</p>
                        </div>
                        <div class="items-end float-end">
                            <!-- routing for create org -->
                            <a href = {{ route('create-org') }}>
                            <button type="button" class= "text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700">
                                <i class="fas fa-plus-circle mr-1"></i>
                                    Create Organization
                            </button>   
                            </a> 
                        </div>
                    </div> 

                    <div class="flex flex-col md:flex-row justify-between">
                        {{-- Left Metrics --}}
                        <div class="w-full md:w-[30%] max-w-[300px] max-h-[500px] bg-gray-100 mt-8 py-[13px] px-[13px] rounded-lg">
                            <!-- First Item -->
                            <div class="flex items-center mb-4">
                                {{-- insert total org number --}}
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    22
                                </div>
                                <div class="ml-4">
                                    <div class="text-gray-800 font-bold text-sm sm:text-base">Total Organizations</div>
                                    <div class="text-gray-500 text-xs sm:text-sm">Total number of organizations<br/>currently managed</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Second Item -->
                            <div class="flex items-center my-4">
                                {{-- insert total number of all individual clients --}}
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    5
                                </div>
                                <div class="ml-4">
                                    <div class="text-gray-800 font-bold text-sm sm:text-base">Individual Clients</div>
                                    <div class="text-gray-500 text-xs sm:text-sm">Total number of individual<br/>clients registered</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Third Item -->
                            <div class="flex items-center my-4">
                                {{-- insert total number of non-individual clients --}}
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    17
                                </div>
                                <div class="ml-4">
                                    <div class="text-gray-800 font-bold text-sm sm:text-base">Non-Individual Clients</div>
                                    <div class="text-gray-500 text-xs sm:text-sm">Total number of non-individual<br/> clients registered</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Fourth Item -->
                            <div class="flex items-center my-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    36
                                </div>
                                <div class="ml-4">
                                    <div class="text-gray-800 font-bold text-sm sm:text-base">Filed Taxes</div>
                                    <div class="text-gray-500 text-xs sm:text-sm">Total number of completed<br/>tax filings</div>
                                </div>
                            </div>
                            <hr class="mx-8 my-1">
                            
                            <!-- Fifth Item -->
                            <div class="flex items-center my-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-900 text-white rounded-full text-2xl font-semibold aspect-w-1 aspect-h-1 sm:w-14 sm:h-14">
                                    19
                                </div>
                                <div class="ml-4">
                                    <div class="text-gray-800 font-bold text-sm sm:text-base">Unfiled Taxes</div>
                                    <div class="text-gray-500 text-xs sm:text-sm">Total number of pending<br/>tax filings</div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Right Table --}}
                        <div class="w-full md:w-3/4 mt-8 ml-0 md:ml-8 border border-gray-300 rounded-lg p-4 bg-white">
                            <div class="flex flex-row items-center">
                                <!-- Search row -->
                                <div class="relative w-80 p-5">
                                    <form x-target="tableid" action="/org-setup" role="search" aria-label="Table" autocomplete="off">
                                        <input 
                                        type="search" 
                                        name="search" 
                                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-900 focus:border-sky-900" 
                                        aria-label="Search Term" 
                                        placeholder="Search..." 
                                        @input.debounce="$el.form.requestSubmit()" 
                                        @search="$el.form.requestSubmit()"
                                        >
                                        </form>
                                    <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <i class="fa-solid fa-xmark absolute right-8 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer" @click="search = ''"></i>
                                </div>
                    
                                <!-- Sort by dropdown -->
                                <div class="relative inline-block text-left sm:w-auto">
                                    <button id="sortButton" class="flex items-center text-gray-600 w-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                                            <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                                        </svg>
                                        <span id="selectedOption" class="font-normal text-md truncate">Sort by</span>
                                    </button>
                        
                                    <div id="dropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden">
                                        <div class="py-2 px-2">
                                            <span class="block px-4 py-2 text-sm font-bold text-gray-700">Sort by</span>
                                            <div data-sort="recently-added" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Added</div>
                                            <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                            <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                                        </div>
                                    </div>
                                </div>
                        
                                <div class="relative inline-block ps-72 space-x-4 text-left sm:w-auto">
                                    <button id="dropdownMenuIconButton" data-dropdown-toggle="dropdownDots" class="flex items-center text-gray-600" type="button">
                                        <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                        </svg>
                                    </button>
                                    <div id="dropdownDots" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-44 origin-top-right">
                                        <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuIconButton">
                                            <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Show Entries</span>
                                            <div onclick="setEntries(5)" class="block px-4 py-2 w-full text-left hover-dropdown">5 per page</div>
                                            <div onclick="setEntries(25)" class="block px-4 py-2 w-full text-left hover-dropdown">25 per page</div>
                                            <div onclick="setEntries(50)" class="block px-4 py-2 w-full text-left hover-dropdown">50 per page</div>
                                            <div onclick="setEntries(100)" class="block px-4 py-2 w-full text-left hover-dropdown">100 per page</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                            <hr class="border-gray-300 w-[calc(100%+2rem)] mx-[-1rem]">
                    
                            <div class="my-4 overflow-x-auto">
                                <table class="min-w-full bg-white" id="tableid">
                                    <thead class="bg-zinc-100 text-zinc-700">
                                        <tr>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Tax Type</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Classification</th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">
                                                Session
                                                <span class="relative group items-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block ml-1 w-4 h-4 text-zinc-700" viewBox="0 0 24 24">
                                                        <path fill="currentColor" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                                                    </svg>
                                                    <div class="hidden group-hover:block absolute left-1/2 transform -translate-x-1/2 mt-2 w-64 bg-white text-zinc-700 font-normal text-sm rounded-lg shadow-lg p-2 overflow-hidden">
                                                        Select and click an organization in this column to get started.
                                                    </div>
                                                </span>
                                            </th>
                                            <th class="text-left py-3 px-4 font-semibold text-sm">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700">
                                        @if (count($orgsetups) > 0)
                                        @foreach ($orgsetups as $organization)
                                            <tr>
                                                <form action="{{ route('org-dashboard') }}" method="POST" id="form-{{ $organization->id }}">
                                                    @csrf
                                                    <input type="hidden" name="organization_id" value="{{ $organization->id }}">
                                                    <td class="text-left py-3 px-4">
                                                        {{ $organization->registration_name }}
                                                    </td>
                                                    <td class="text-left py-3 px-4">{{ $organization->tax_type }}</td>
                                                    <td class="text-left py-3 px-4">{{ $organization->type }}</td>
                                                    <td class="text-left py-3 px-4 text-blue-600 underline" onclick="document.getElementById('form-{{ $organization->id }}').submit();">
                                                        <button type="submit" class="text-blue-600 hover:translate-x-1 transition-transform duration-300 underline flex items-center space-x-1">Open Organization
                                                            <span class="hover:translate-x-1 transition-transform duration-300">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2 group-hover:fill-current group-hover:text-blue-800 transition-colors duration-300" viewBox="0 0 16 16">
                                                                    <path fill="#2563eb" d="M8.22 2.97a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.042-.018a.75.75 0 0 1-.018-1.042l2.97-2.97H3.75a.75.75 0 0 1 0-1.5h7.44L8.22 4.03a.75.75 0 0 1 0-1.06"/>
                                                                </svg>
                                                            </span>
                                                        </button>
                                                    </td>
                                                    <td class="relative text-left py-2 px-3">
                                                        <button type="button" id="dropdownMenuAction" class="text-gray-500 hover:text-gray-700">
                                                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                                            </svg>
                                                        </button>
                                                        <div id="dropdownAction" class="absolute right-0 z-10 hidden bg-white divide-gray-100 rounded-lg shadow-lg w-40 origin-top-right overflow-hidden max-h-64 overflow-y-auto">
                                                            <div class="py-2 px-2 text-sm text-gray-700" aria-labelledby="dropdownMenuAction">
                                                                <span class="block px-4 py-2 text-sm font-bold text-gray-700 text-left">Action</span>
                                                                <div onclick="deleteOrganization('{{ $organization->id }}')" class="block px-4 py-2 w-full text-left hover-dropdown">Delete</div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </form>
                                            </tr>
                                        </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center p-4">
                                                    <img src="{{ asset('images/Wallet 02.png') }}" alt="No data available" class="mx-auto" />
                                                    <h1 class="font-bold mt-2">No Organization yet</h1>
                                                    <p class="text-sm text-neutral-500 mt-2">Start creating organizations with the <br> + Create Organization button.</p>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                {{ $orgsetups->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function activateTab(tabId) {
            document.querySelectorAll('[role="tabpanel"]').forEach(function(panel) {
                panel.classList.add('hidden');
            });
            document.querySelectorAll('button[role="tab"]').forEach(function(tab) {
                tab.classList.remove('font-extrabold', 'text-blue-900', 'active-tab'); 
                tab.classList.add('text-gray-500'); 
                tab.setAttribute('aria-selected', 'false');
            });
            document.getElementById(tabId + '-content').classList.remove('hidden');
            const activeTab = document.getElementById(tabId);
            activeTab.classList.add('font-extrabold', 'text-blue-900', 'active-tab'); 
            activeTab.classList.remove('text-gray-500'); 
            activeTab.setAttribute('aria-selected', 'true');
        }
        activateTab('tab-org');

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
        document.getElementById('dropdownMenuAction').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownAction');
            dropdown.classList.toggle('hidden');
        });

        // FOR SHOWING/SETTING ENTRIES
        function setEntries(entries) {
            console.log(`Setting ${entries} entries per page`);
            // no showing entries since no data yet
            document.getElementById('dropdownDots').classList.add('hidden');
        }
    </script>
</x-organization-layout>