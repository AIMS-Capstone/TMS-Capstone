<x-organization-layout>
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

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">            
                            <p class="font-normal text-sm">The Recycle Bin is a dedicated module accessible exclusively by system administrators. It <br>serves as a secure repository for soft-deleted items.</p>
                        </div>
                    </div> 

                    <nav class="flex gap-x-4 overflow-x-auto justify-center mt-4" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-4 text-sm font-medium text-center text-gray-500 hover:text-blue-900"
                            id="tab-org"
                            role="tab"
                            aria-selected="true"
                            onclick="activateTab('tab-org')">
                            Organizations
                        </button>
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-center text-gray-500 hover:text-blue-900"
                            id="tab-accountant"
                            role="tab"
                            aria-selected="false"
                            onclick="activateTab('tab-accountant')">
                            Accountant Users
                        </button>
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-center text-gray-500 hover:text-blue-900"
                            id="tab-accountClient"
                            role="tab"
                            aria-selected="false"
                            onclick="activateTab('tab-accountClient')">
                            Client Users
                        </button>
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-center text-gray-500 hover:text-blue-900"
                            id="tab-transaction"
                            role="tab"
                            aria-selected="false"
                            onclick="activateTab('tab-transaction')">
                            Transactions
                        </button>
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-center text-gray-500 hover:text-blue-900"
                            id="tab-tax"
                            role="tab"
                            aria-selected="false"
                            onclick="activateTab('tab-tax')">
                            Tax Return
                        </button>
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-center text-gray-500 hover:text-blue-900"
                            id="tab-coa"
                            role="tab"
                            aria-selected="false"
                            onclick="activateTab('tab-coa')">
                            Chart of Accounts
                        </button>
                    </nav>
        
                    <hr class="mx-1 mt-auto">

        {{-- Organizations Table/Tab --}}
        <div id="tab-org-content" role="tabpanel" aria-labelledby="tab-org" class="flex flex-col md:flex-row justify-between">
            <div class="w-full mt-8 ml-0 max-h-[500px] border border-zinc-300 rounded-lg p-4 bg-white">
                <div class="flex flex-row items-center">
                    <!-- Search row -->
                    <div class="relative w-80 p-5">
                        <form action="{{ url()->current() }}" method="GET" role="search" aria-label="Table" autocomplete="off">
                            <input type="hidden" name="tab" value="org"> <!-- Hidden field to retain tab state -->
                            <input 
                                type="search" 
                                name="user_search" 
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
                                <div data-sort="recently-deleted" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Deleted</div>
                                <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                            </div>
                        </div>
                    </div>

            
                        {{-- Right side: Set as Session button and Dropdown --}}
                        <div class="ml-auto flex flex-row items-center space-x-4">
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="#52525b" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.28 2.54l.72-1.21l-3.5-2.08V8z"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Restore</span>
                                </button>
                            </div>
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Delete</span>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">
                
                    <div class="my-4 overflow-y-auto max-h-[500px]">
                        <table class="min-w-full bg-white" id="tableid1">
                            <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                                <tr>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Tax Type</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Classification</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Account Status</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Date Deleted</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Deleted by</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto py-2 px-4">
                                    <tr id="" class="hover:bg-slate-100 cursor-pointer">
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="relative text-left py-2 px-3"></td>
                                    </tr>
                                {{-- Comment out na lang if may data na --}}
                                {{-- @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-2">
                                            <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-48 h-48" />
                                            <h1 class="font-extrabold">No Deleted Organizations yet</h1>
                                            <p class="text-sm text-neutral-500 mt-2">Deleted items will show up here when you need <br>to restore r permanently delete them.</p>
                                        </td>
                                    </tr>
                                @endforelse --}}
                            </tbody>
                        </table>
                        {{-- {{ $users->appends(request()->input())->links('vendor.pagination.custom') }} --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Accountant Users Table/Tab --}}
        <div id="tab-accountant-content" role="tabpanel" aria-labelledby="tab-accountant" class="flex flex-col md:flex-row justify-between">
            <div class="w-full mt-8 ml-0 max-h-[500px] border border-zinc-300 rounded-lg p-4 bg-white">
                <div class="flex flex-row items-center">
                    <!-- Search row -->
                    <div class="relative w-80 p-5">
                        <form action="{{ url()->current() }}" method="GET" role="search" aria-label="Table" autocomplete="off">
                            <input type="hidden" name="tab" value="accountant"> <!-- Hidden field to retain tab state -->
                            <input 
                                type="search" 
                                name="user_search" 
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
                                <div data-sort="recently-deleted" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Deleted</div>
                                <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                            </div>
                        </div>
                    </div>

            
                        {{-- Right side: Set as Session button and Dropdown --}}
                        <div class="ml-auto flex flex-row items-center space-x-4">
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="#52525b" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.28 2.54l.72-1.21l-3.5-2.08V8z"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Restore</span>
                                </button>
                            </div>
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Delete</span>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">
                
                    <div class="my-4 overflow-y-auto max-h-[500px]">
                        <table class="min-w-full bg-white" id="tableid1">
                            <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                                <tr>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Email Address</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Account Type</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Date Deleted</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Deleted by</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto py-2 px-4">
                                    <tr id="" class="hover:bg-slate-100 cursor-pointer">
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="relative text-left py-2 px-3"></td>
                                    </tr>
                                {{-- Comment out na lang if may data na --}}
                                {{-- @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-2">
                                            <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-48 h-48" />
                                            <h1 class="font-extrabold">No Deleted Accountant yet</h1>
                                            <p class="text-sm text-neutral-500 mt-2">Deleted items will show up here when you need <br>to restore r permanently delete them.</p>
                                        </td>
                                    </tr>
                                @endforelse --}}
                            </tbody>
                        </table>
                        {{-- {{ $users->appends(request()->input())->links('vendor.pagination.custom') }} --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Client Users Table/Tab --}}
        <div id="tab-accountClient-content" role="tabpanel" aria-labelledby="tab-accountClient" class="flex flex-col md:flex-row justify-between">
            <div class="w-full mt-8 ml-0 max-h-[500px] border border-zinc-300 rounded-lg p-4 bg-white">
                <div class="flex flex-row items-center">
                    <!-- Search row -->
                    <div class="relative w-80 p-5">
                        <form action="{{ url()->current() }}" method="GET" role="search" aria-label="Table" autocomplete="off">
                            <input type="hidden" name="tab" value="accountClient"> <!-- Hidden field to retain tab state -->
                            <input 
                                type="search" 
                                name="user_search" 
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
                                <div data-sort="recently-deleted" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Deleted</div>
                                <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                            </div>
                        </div>
                    </div>

            
                        {{-- Right side: Set as Session button and Dropdown --}}
                        <div class="ml-auto flex flex-row items-center space-x-4">
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="#52525b" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.28 2.54l.72-1.21l-3.5-2.08V8z"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Restore</span>
                                </button>
                            </div>
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Delete</span>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">
                
                    <div class="my-4 overflow-y-auto max-h-[500px]">
                        <table class="min-w-full bg-white" id="tableid1">
                            <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                                <tr>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">TIN</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Account Type</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Date Deleted</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Deleted by</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto py-2 px-4">
                                    <tr id="" class="hover:bg-slate-100 cursor-pointer">
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="relative text-left py-2 px-3"></td>
                                    </tr>
                                {{-- Comment out na lang if may data na --}}
                                {{-- @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-2">
                                            <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-48 h-48" />
                                            <h1 class="font-extrabold">No Deleted Client Users yet</h1>
                                            <p class="text-sm text-neutral-500 mt-2">Deleted items will show up here when you need <br>to restore r permanently delete them.</p>
                                        </td>
                                    </tr>
                                @endforelse --}}
                            </tbody>
                        </table>
                        {{-- {{ $users->appends(request()->input())->links('vendor.pagination.custom') }} --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Transactions Table/Tab --}}
        <div id="tab-transaction-content" role="tabpanel" aria-labelledby="tab-transaction" class="flex flex-col md:flex-row justify-between">
            <div class="w-full mt-8 ml-0 max-h-[500px] border border-zinc-300 rounded-lg p-4 bg-white">
                <div class="flex flex-row items-center">
                    <!-- Search row -->
                    <div class="relative w-80 p-5">
                        <form action="{{ url()->current() }}" method="GET" role="search" aria-label="Table" autocomplete="off">
                            <input type="hidden" name="tab" value="transaction"> <!-- Hidden field to retain tab state -->
                            <input 
                                type="search" 
                                name="user_search" 
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
                                <div data-sort="recently-deleted" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Deleted</div>
                                <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                            </div>
                        </div>
                    </div>

            
                        {{-- Right side: Set as Session button and Dropdown --}}
                        <div class="ml-auto flex flex-row items-center space-x-4">
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="#52525b" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.28 2.54l.72-1.21l-3.5-2.08V8z"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Restore</span>
                                </button>
                            </div>
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Delete</span>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">
                
                    <div class="my-4 overflow-y-auto max-h-[500px]">
                        <table class="min-w-full bg-white" id="tableid1">
                            <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                                <tr>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Contact</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Invoice Number</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Reference Number</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Transaction Type</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Date Deleted</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Deleted by</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto py-2 px-4">
                                    <tr id="" class="hover:bg-slate-100 cursor-pointer">
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-3"></td>
                                    </tr>
                                {{-- Comment out na lang if may data na --}}
                                {{-- @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-2">
                                            <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-48 h-48" />
                                            <h1 class="font-extrabold">No Deleted Transactions yet</h1>
                                            <p class="text-sm text-neutral-500 mt-2">Deleted items will show up here when you need <br>to restore r permanently delete them.</p>
                                        </td>
                                    </tr>
                                @endforelse --}}
                            </tbody>
                        </table>
                        {{-- {{ $users->appends(request()->input())->links('vendor.pagination.custom') }} --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Tax Return Table/Tab --}}
        <div id="tab-tax-content" role="tabpanel" aria-labelledby="tab-tax" class="flex flex-col md:flex-row justify-between">
            <div class="w-full mt-8 ml-0 max-h-[500px] border border-zinc-300 rounded-lg p-4 bg-white">
                <div class="flex flex-row items-center">
                    <!-- Search row -->
                    <div class="relative w-80 p-5">
                        <form action="{{ url()->current() }}" method="GET" role="search" aria-label="Table" autocomplete="off">
                            <input type="hidden" name="tab" value="tax"> <!-- Hidden field to retain tab state -->
                            <input 
                                type="search" 
                                name="user_search" 
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
                                <div data-sort="recently-deleted" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Deleted</div>
                                <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                            </div>
                        </div>
                    </div>

            
                        {{-- Right side: Set as Session button and Dropdown --}}
                        <div class="ml-auto flex flex-row items-center space-x-4">
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="#52525b" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.28 2.54l.72-1.21l-3.5-2.08V8z"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Restore</span>
                                </button>
                            </div>
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Delete</span>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">
                
                    <div class="my-4 overflow-y-auto max-h-[500px]">
                        <table class="min-w-full bg-white" id="tableid1">
                            <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                                <tr>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Tax Form</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">BIR Form No.</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Date Generated</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Generated By</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Date Deleted</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Deleted By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto py-2 px-4">
                                    <tr id="" class="hover:bg-slate-100 cursor-pointer">
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-3"></td>
                                    </tr>
                                {{-- Comment out na lang if may data na --}}
                                {{-- @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-2">
                                            <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-48 h-48" />
                                            <h1 class="font-extrabold">No Deleted Tax Returns yet</h1>
                                            <p class="text-sm text-neutral-500 mt-2">Deleted items will show up here when you need <br>to restore r permanently delete them.</p>
                                        </td>
                                    </tr>
                                @endforelse --}}
                            </tbody>
                        </table>
                        {{-- {{ $users->appends(request()->input())->links('vendor.pagination.custom') }} --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- COA Table/Tab --}}
        <div id="tab-coa-content" role="tabpanel" aria-labelledby="tab-coa" class="flex flex-col md:flex-row justify-between">
            <div class="w-full mt-8 ml-0 max-h-[500px] border border-zinc-300 rounded-lg p-4 bg-white">
                <div class="flex flex-row items-center">
                    <!-- Search row -->
                    <div class="relative w-80 p-5">
                        <form action="{{ url()->current() }}" method="GET" role="search" aria-label="Table" autocomplete="off">
                            <input type="hidden" name="tab" value="coa"> <!-- Hidden field to retain tab state -->
                            <input 
                                type="search" 
                                name="user_search" 
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
                                <div data-sort="recently-deleted" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Deleted</div>
                                <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                                <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                            </div>
                        </div>
                    </div>

            
                        {{-- Right side: Set as Session button and Dropdown --}}
                        <div class="ml-auto flex flex-row items-center space-x-4">
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="#52525b" d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89l.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7s-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.95 8.95 0 0 0 13 21a9 9 0 0 0 0-18m-1 5v5l4.28 2.54l.72-1.21l-3.5-2.08V8z"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Restore</span>
                                </button>
                            </div>
                            <div class="relative inline-block text-left sm:w-auto">
                                <button id="" disabled onclick="document.getElementById('form-' + selectedRowId).submit();" class="flex items-center border border-zinc-300 rounded-lg p-2 text-zinc-600 text-sm w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" class="mr-2" viewBox="0 0 24 24"><path fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/></svg>
                                    <span id="selectedOption" class="font-normal text-md">Delete</span>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            
                    <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">
                
                    <div class="my-4 overflow-y-auto max-h-[500px]">
                        <table class="min-w-full bg-white" id="tableid1">
                            <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                                <tr>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Code</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Account Type</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Date Deleted</th>
                                    <th class="text-left py-3 px-4 font-semibold text-sm">Deleted By</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto py-2 px-4">
                                    <tr id="" class="hover:bg-slate-100 cursor-pointer">
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-4"></td>
                                        <td class="text-left py-2 px-3"></td>
                                    </tr>
                                {{-- Comment out na lang if may data na --}}
                                {{-- @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-2">
                                            <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-48 h-48" />
                                            <h1 class="font-extrabold">No Deleted Accounts yet</h1>
                                            <p class="text-sm text-neutral-500 mt-2">Deleted items will show up here when you need <br>to restore r permanently delete them.</p>
                                        </td>
                                    </tr>
                                @endforelse --}}
                            </tbody>
                        </table>
                        {{-- {{ $users->appends(request()->input())->links('vendor.pagination.custom') }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

   
<script>
        // TAB Activation Function
     // Function to activate a tab based on the provided tab ID
    function activateTab(tabId) {
        // Hide all tab panels
        document.querySelectorAll('[role="tabpanel"]').forEach(function (panel) {
            panel.classList.add('hidden');
        });

        // Deactivate all tabs
        document.querySelectorAll('button[role="tab"]').forEach(function (tab) {
            tab.classList.remove('font-extrabold', 'text-blue-900', 'border-b-4', 'active-tab');
            tab.classList.add('text-gray-500');
            tab.setAttribute('aria-selected', 'false');
        });

        // Show the active tab's content
        const activePanel = document.getElementById(tabId + '-content');
        if (activePanel) {
            activePanel.classList.remove('hidden');
        }

        // Activate the selected tab
        const activeTab = document.getElementById(tabId);
        if (activeTab) {
            activeTab.classList.add('font-extrabold', 'text-blue-900', 'border-b-4', 'border-blue-900');
            activeTab.classList.remove('text-gray-500');
            activeTab.setAttribute('aria-selected', 'true');
        }
    }

    // Function to initialize the active tab based on the URL query parameters
    function initializeTabs() {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'org'; // Default to 'accountant' if no tab parameter is specified

        // Activate the tab based on the URL parameter
        switch (activeTab) {
            case 'accountant':
                activateTab('tab-accountant');
                break;
            case 'accountClient':
                activateTab('tab-accountClient');
                break;
            case 'transaction':
                activateTab('tab-transaction');
                break;
            case 'tax':
                activateTab('tab-tax');
                break;
            case 'coa':
                activateTab('tab-coa');
                break;
            default:
                activateTab('tab-org');
                break;
        }
    }

// Call initializeTabs when the document is ready
document.addEventListener('DOMContentLoaded', initializeTabs);


// Call initializeTabs when the document is ready
document.addEventListener('DOMContentLoaded', initializeTabs);

    
   </script>
    
</x-organization-layout>
