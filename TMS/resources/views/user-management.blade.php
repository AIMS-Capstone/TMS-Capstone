<x-organization-layout>
    <div class="bg-white py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-xs">
                <div class="overflow-x-auto pt-6 px-10">
                    <p class="font-bold text-3xl taxuri-color">
                        User Management
                    </p>

                    <div class="flex justify-between items-center">
                        <div class="flex items-center">            
                            <p class="font-normal text-sm">This page allows the Admin to efficiently manage all user accounts in Taxuri. The page <br /> displays a list of each type of account.</p>
                        </div>
                        <div class="items-end float-end">
                            <!-- routing for add account modal -->
                            @if (request('active_tab', 'acc') === 'acc')
                               
                            <button id="add-account-button" type="button" class="text-white bg-blue-900 hover:bg-blue-950 focus:outline-none focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2" 
                            x-data 
                            x-on:click="$dispatch('open-add-account-modal')">
                            <i class="fas fa-plus-circle mr-1"></i>
                            Add Account
                        </button>
                        
                                
                            @endif
                        </div>
                    </div> 

                    <nav class="flex gap-x-4 overflow-x-auto justify-center mt-4" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-4 text-sm font-medium text-center text-gray-500 hover:text-blue-900"
                            id="tab-acc"
                            role="tab"
                            aria-selected="true"
                            onclick="activateTab('tab-acc')">
                            Accountant Users
                        </button>
                        <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium text-center text-gray-500 hover:text-blue-900"
                            id="tab-client"
                            role="tab"
                            aria-selected="false"
                            onclick="activateTab('tab-client')">
                            Client Users
                        </button>
                    </nav>
        
                    <hr class="mx-1 mt-auto">

        {{-- Accountant Users Table/Tab --}}
    <div id="tab-acc-content" role="tabpanel" aria-labelledby="tab-acc" class="flex flex-col md:flex-row justify-between">
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
                <div data-sort="recently-added" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Added</div>
                <div data-sort="ascending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                <div data-sort="descending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
            </div>
        </div>
    </div>

        
                    {{-- Right side: Set as Session button and Dropdown --}}
                    <div class="ml-auto flex flex-row items-center space-x-4">
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
                                <th class="text-left py-3 px-4 font-semibold text-sm">Date Created</th>
                                <th class="text-left py-3 px-4 font-semibold text-sm">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto">
                            @forelse ($users as $user)
                                <tr id="user-{{ $user->id }}" class="hover:bg-slate-100 cursor-pointer">
                                    <td class="text-left py-[7px] px-4">{{ $user->first_name }} {{ $user->last_name }}</td>
                                    <td class="text-left py-[7px] px-4">{{ $user->email }}</td>
                                    <td class="text-left py-[7px] px-4">
                                        @if ($user->role == "Admin")
                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-4 py-2 rounded-full dark:bg-green-900 dark:text-green-300">Admin</span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-4 py-2 rounded-full dark:bg-green-900 dark:text-green-300">Accountant</span>
                                    @endif
                                    
                                    </td>
                              <td class="text-left py-[7px] px-4">{{ $user->created_at->format('F j, Y') }}</td>
                                    <td class="relative text-left py-2 px-3">
                                        <button type="button" id="dropdownMenuAction-{{ $user->id }}" class="text-zinc-500 hover:text-zinc-700">
                                            <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                                <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                            </svg>
                                        </button>
                                        <div id="dropdownAction-{{ $user->id }}" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-32 origin-top-right overflow-hidden max-h-64 overflow-y-auto">
                                            <div 
                                            x-data 
                                            x-on:click="$dispatch('open-delete-user-modal', { userId: '{{ $user->id }}', userName: '{{ $user->first_name }} {{ $user->last_name }}' })"  
                                            class="block px-4 py-2 w-full text-left hover-dropdown text-red-500 cursor-pointer">
                                            Delete
                                        </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center p-2">
                                        <img src="{{ asset('images/no-account.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                        <h1 class="font-extrabold">No Account yet</h1>
                                        <p class="text-sm text-neutral-500 mt-2">Start creating accounts with the <br> + Add Account button.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $users->appends(request()->input())->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
             {{-- Client Users Table/Tab --}}
<div id="tab-client-content" role="tabpanel" aria-labelledby="tab-client" class="flex flex-col md:flex-row justify-between">
    <div class="w-full mt-8 ml-0 max-h-[500px] border border-zinc-300 rounded-lg p-4 bg-white">
        <div class="flex flex-row items-center">
            <!-- Search row -->
            <div class="relative w-80 p-5">
                <form action="{{ url()->current() }}" method="GET" role="search" aria-label="Table" autocomplete="off">
                    <input type="hidden" name="tab" value="client"> <!-- Hidden field to retain tab state -->
                    <input 
                        type="search" 
                        name="client_search" 
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
                <button id="clientSortButton" class="flex items-center text-zinc-600 w-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24">
                        <path fill="#696969" fill-rule="evenodd" d="M22.75 7a.75.75 0 0 1-.75.75H2a.75.75 0 0 1 0-1.5h20a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H5a.75.75 0 0 1 0-1.5h14a.75.75 0 0 1 .75.75m-3 5a.75.75 0 0 1-.75.75H8a.75.75 0 0 1 0-1.5h8a.75.75 0 0 1 .75.75" clip-rule="evenodd"/>
                    </svg>
                    <span id="selectedOption" class="font-normal text-md text-zinc-700 truncate">Sort by</span>
                </button>

                <div id="clientDropdownMenu" class="absolute mt-2 w-44 rounded-lg shadow-lg bg-white hidden z-50">
                    <div class="py-2 px-2">
                        <span class="block px-4 py-2 text-sm font-bold text-zinc-700">Sort by</span>
                        <div data-sort="recently-Added" class="block px-4 py-2 w-full text-sm hover-dropdown">Recently Added</div>
                        <div data-sort="allAscending" class="block px-4 py-2 w-full text-sm hover-dropdown">Ascending</div>
                        <div data-sort="allDescending" class="block px-4 py-2 w-full text-sm hover-dropdown">Descending</div>
                    </div>
                </div>
            </div>

            <div class="ml-auto flex flex-row items-center space-x-4">
                <div class="relative inline-block space-x-4 text-left sm:w-auto">
                    <button id="dropdownMenuClientButton" data-dropdown-toggle="clientDropdownDots" class="flex items-center text-zinc-500 hover:text-zinc-700" type="button">
                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                        </svg>
                    </button>
                    <div id="clientDropdownDots" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-44 origin-top-right">
                        <div class="py-2 px-2 text-sm text-zinc-700" aria-labelledby="dropdownMenuClientButton">
                            <span class="block px-4 py-2 text-sm font-bold text-zinc-700 text-left">Show Entries</span>
                            <div onclick="setEntries(5)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">5 per page</div>
                            <div onclick="setEntries(25)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">25 per page</div>
                            <div onclick="setEntries(50)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">50 per page</div>
                            <div onclick="setEntries(100)" class="block px-4 py-2 w-full text-left hover-dropdown cursor-pointer">100 per page</div>
                            <input type="hidden" name="client_search" value="{{ request('client_search') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="border-zinc-300 w-[calc(100%+2rem)] mx-[-1rem]">

        <div class="my-4 overflow-y-auto max-h-[500px]">
            <table class="min-w-full bg-white" id="clientTable">
                <thead class="bg-zinc-100 text-zinc-700 font-extrabold sticky top-0">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-sm">Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm">TIN</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm">Account Type</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm">Date Created</th>
                        <th class="text-left py-3 px-4 font-semibold text-sm">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 text-sm text-zinc-700 overflow-y-auto">
                    @foreach($clients as $client)
                        <tr id="{{ $client->id }}" class="hover:bg-slate-100 cursor-pointer ease-in-out" onclick="selectRow()">
                            <form action="{{ route('orgaccount.destroy', $client->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="organization_id" value="{{ $client->id }}">
                                <td class="text-left py-[7px] px-4">{{ $client->orgSetup->registration_name ?? 'N/A' }}</td>
                                <td class="text-left py-[7px] px-4">{{ $client->orgSetup->tin ?? 'N/A' }}</td>
                                <td class="text-left py-[7px] px-4">
                                    <span class="bg-amber-100 text-zinc-700 text-xs font-medium me-2 px-4 py-2 rounded-full">Client</span>
                                </td>
                                
                                <td class="text-left py-[7px] px-4">{{ $client->created_at->format('F j, Y') }}</td>
                                <td class="relative text-left py-2 px-3">
                                    <button type="button" id="clientDropdownMenuAction-{{ $client->id }}" class="text-zinc-500 hover:text-zinc-700">
                                        <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 4 15">
                                            <path d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/>
                                        </svg>
                                    </button>
                                    <div id="dropdownClientAction-{{ $client->id }}" class="absolute right-0 z-10 hidden bg-white divide-zinc-100 rounded-lg shadow-lg w-32 origin-top-right overflow-hidden max-h-64 overflow-y-auto">
                                        <div 
                                        x-data 
                                        x-on:click="$dispatch('open-delete-client-modal', { clientId: '{{ $client->id }}', clientName: '{{ $client->name }}' })"  
                                        class="block px-4 py-2 w-full text-left hover-dropdown text-red-500 cursor-pointer">
                                        Delete
                                    </div>
                                    </div>
                                </td>
                            </form>
                        </tr>
                    @endforeach

                    @if($clients->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center p-2">
                                <img src="{{ asset('images/Box.png') }}" alt="No data available" class="mx-auto w-56 h-56" />
                                <h1 class="font-extrabold">No Client Users yet</h1>
                                <p class="text-sm text-neutral-500 mt-2">Start creating accounts with the <br> + Add Account button.</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        
            {{ $clients->appends(request()->input())->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>
    <!-- Delete Confirmation Modal CLIENT -->
<div x-data="{ open: false, clientId: null, clientName: '' }" 
@open-delete-client-modal.window="open = true; clientId = $event.detail.clientId; clientName = $event.detail.clientName" 
x-cloak>
<div x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
   <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
       <h2 class="text-lg font-semibold mb-4">Are you sure?</h2>
       <p class="mb-4">Do you really want to delete <strong x-text="clientName"></strong>? This action cannot be undone.</p>
       <div class="flex justify-end">
           <button type="button" @click="open = false" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">Cancel</button>
           <form method="POST" action="{{ route('orgaccount.destroy') }}" id="delete-form" class="inline">
               @csrf
               <input type="hidden" name="client_id" :value="clientId">
               <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">Delete</button>
           </form>
       </div>
   </div>
</div>
<!-- Add Account Modal -->
<div x-data="{ open: false }" 
     @open-add-account-modal.window="open = true" 
     x-show="open" 
     class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center" 
     x-cloak>
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-lg font-semibold mb-4">Add New Account</h2>
        <form id="addAccountForm" method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" id="last_name" name="last_name" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            <div class="mb-4">
                <label for="suffix" class="block text-sm font-medium text-gray-700">Suffix</label>
                <input type="text" id="suffix" name="suffix" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            <div class="mb-4">
                <label for="middle_name" class="block text-sm font-medium text-gray-700">Middle Name</label>
                <input type="text" id="middle_name" name="middle_name" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md p-2">
            </div>
            <div class="flex justify-end">
                <button type="button" @click="open = false" class="mr-2 text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg px-4 py-2">Cancel</button>
                <button type="submit" class="bg-blue-900 text-white rounded-lg px-4 py-2">Submit</button>
            </div>
        </form>
    </div>
</div>

</div>
<div x-data="{ showModal: false }" x-init="@if(session('success')) showModal = true @endif">
              
          
                 
    
    <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" @click.outside="showModal = false; " x-cloak>
        <div class="bg-white rounded-lg shadow-lg p-6 w-80 text-center">
            <div class="mb-4">
                <svg class="w-12 h-12 mx-auto text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2l4-4m0 6a9 9 0 1 0-6 0Z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold">Account Added</h3>
            <p class="text-sm text-gray-600 mt-2"> {{ session('success') }}</p>
            <div class="flex justify-end">
                <button type="button" @click="showModal = false;"       class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Close</button>
            </div>
        </div>
    </div>
</div>
        
<!-- Delete Confirmation Modal for User (Accountant) -->
<div x-data="{ open: false, userId: null, userName: '' }" 
     @open-delete-user-modal.window="open = true; userId = $event.detail.userId; userName = $event.detail.userName" 
     x-cloak>
    <div x-show="open" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h2 class="text-lg font-semibold mb-4">Are you sure?</h2>
            <p class="mb-4">Do you really want to delete <strong x-text="userName"></strong>? This action cannot be undone.</p>
            <div class="flex justify-end">
                <button type="button" @click="open = false" class="mr-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg">Cancel</button>
                <form method="POST" action="{{ route('users.destroy') }}" id="delete-form" class="inline">
                    @csrf
                    <input type="hidden" name="user_id" :value="userId">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">Delete</button>
                </form>
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

    // Handle visibility of the Add Account button
    const addAccountButton = document.getElementById('add-account-button');
    if (tabId === 'tab-acc') {
        addAccountButton?.classList.remove('hidden');
    } else {
        addAccountButton?.classList.add('hidden');
    }
}

// Function to initialize the active tab based on the URL query parameters
function initializeTabs() {
    const urlParams = new URLSearchParams(window.location.search);
    const activeTab = urlParams.get('tab') || 'accountant'; // Default to accountant if no tab param

    // Activate the tab based on the URL parameter
    if (activeTab === 'client') {
        activateTab('tab-client'); // Activate Client Users tab
    } else {
        activateTab('tab-acc'); // Default to Accountant Users tab
    }
}

// Call initializeTabs when the document is ready
document.addEventListener('DOMContentLoaded', initializeTabs);


// Call initializeTabs when the document is ready
document.addEventListener('DOMContentLoaded', initializeTabs);

    
        // Show/hide dropdown on button click
        document.getElementById('sortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        });
    
        // Unified sorting function for Account tab
        function sortAccountItems(criteria) {
            const table = document.querySelector('#tableid1 tbody');
            const rows = Array.from(table.querySelectorAll('tr'));
            console.log('Original rows:', rows); // Log the original rows
            let sortedRows;
    
            if (criteria === 'recently-added') {
                // Sort by the order of rows (most recent first)
                sortedRows = rows.reverse();
                console.log('Sorted rows (recently-added):', sortedRows); // Log the sorted rows
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
                console.log('Sorted rows (alphabetically):', sortedRows); // Log the sorted rows
            }
    
            // Clear and re-append sorted rows
            table.innerHTML = ''; 
            sortedRows.forEach(row => table.appendChild(row));
    
            // Log the final table state
            console.log('Final rows in table:', table.querySelectorAll('tr'));
        }
    
        // Add click event listeners to each sort option for Account tab
        document.querySelectorAll('#dropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                console.log('Selected criteria:', criteria); // Log the criteria to console
                sortAccountItems(criteria); // Call the sorting function for Account
                document.getElementById('selectedOption').textContent = this.textContent; // Update button text
                document.getElementById('dropdownMenu').classList.add('hidden'); // Hide dropdown
            });
        });
    
        // Client tab sorting logic
        document.getElementById('clientSortButton').addEventListener('click', function() {
            const dropdown = document.getElementById('clientDropdownMenu');
            dropdown.classList.toggle('hidden');
        });
    
        // Separate sorting function for Client tab
        function sortClientItems(criteria) {
            const table = document.querySelector('#clientTable tbody'); // Ensure correct table is selected
            const rows = Array.from(table.querySelectorAll('tr'));
            let sortedRows;
    
            if (criteria === 'recently-added') {
                sortedRows = rows.reverse();
            } else {
                sortedRows = rows.sort((a, b) => {
                    const aText = a.querySelector('td').textContent.trim().toLowerCase();
                    const bText = b.querySelector('td').textContent.trim().toLowerCase();
    
                    if (criteria === 'allAscending') {
                        return aText.localeCompare(bText);
                    } else if (criteria === 'allDescending') {
                        return bText.localeCompare(aText);
                    }
                });
            }
            // Clear and re-append sorted rows
            table.innerHTML = '';
            sortedRows.forEach(row => table.appendChild(row));
        }
    
        // Add click event listeners to each sort option for Client tab
        document.querySelectorAll('#clientDropdownMenu div[data-sort]').forEach(item => {
            item.addEventListener('click', function() {
                const criteria = this.getAttribute('data-sort');
                console.log('Selected criteria for client:', criteria); // Log criteria
                sortClientItems(criteria); // Call the sorting function for Client
            });
        });
    
        // FOR BUTTON OF SHOW ENTRIES - Accountant TAB
        document.getElementById('dropdownMenuIconButton').addEventListener('click', function() {
            const dropdown = document.getElementById('dropdownDots');
            dropdown.classList.toggle('hidden');
        });
    
        // FOR ACTION BUTTON - Accountant TAB
        document.querySelectorAll('[id^="dropdownMenuAction-"]').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.id.split('-')[1];
                const dropdown = document.getElementById(`dropdownAction-${id}`);
                dropdown.classList.toggle('hidden');
            });
        });
    
        // FOR SHOWING/SETTING ENTRIES - Accountant TAB
        function setEntries(entries) {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = "{{ route('user-management') }}"; // Ensure this matches your route name

    // Input for number of entries per page
    const perPageInput = document.createElement('input');
    perPageInput.type = 'hidden';
    perPageInput.name = 'perPage'; // Make sure this matches your controller's expected input
    perPageInput.value = entries;

    // Input for search term (user search)
    const searchInput = document.createElement('input');
    searchInput.type = 'hidden';
    searchInput.name = 'user_search'; // Make sure this matches your controller's expected input
    searchInput.value = "{{ request('user_search') }}"; // Correcting to 'user_search'

    // Append inputs to form
    form.appendChild(perPageInput);
    form.appendChild(searchInput);

    // Append form to the document and submit
    document.body.appendChild(form);
    form.submit();
}

    
        // FOR CLIENT
        // FOR BUTTON OF SHOW ENTRIES - Client TAB
        document.getElementById('dropdownMenuClientButton').addEventListener('click', function() {
            const dropdown = document.getElementById('clientDropdownDots');
            dropdown.classList.toggle('hidden');
        });
    
        // FOR ACTION BUTTON - Client TAB
        document.querySelectorAll('[id^="clientDropdownMenuAction-"]').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.id.split('-')[1];
                const dropdown = document.getElementById(`dropdownClientAction-${id}`);
                dropdown.classList.toggle('hidden');
            });
        });
    
        // FOR SHOWING/SETTING ENTRIES - Client TAB
        function setClientEntries(entries) {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = "{{ route('user-management') }}";
            const perPageInput = document.createElement('input');
            perPageInput.type = 'hidden';
            perPageInput.name = 'perPage';
            perPageInput.value = entries;
            const searchInput = document.createElement('input');
            searchInput.type = 'hidden';
            searchInput.name = 'client_search';
            searchInput.value = "{{ request('client_search') }}";
            form.appendChild(perPageInput);
            form.appendChild(searchInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    
</x-organization-layout>
