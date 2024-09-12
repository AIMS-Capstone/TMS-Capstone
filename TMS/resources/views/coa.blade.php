<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Page Main -->
                        <div class="container mx-auto my-auto pt-6">
                            <div class="px-10">
                                <div class="flex flex-row w-full items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg"  class="w-8 h-8" viewBox="0 0 512 512"><path fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="48" d="M160 144h288M160 256h288M160 368h288"/><circle cx="80" cy="144" r="16" fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="80" cy="256" r="16" fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="80" cy="368" r="16" fill="none" stroke="#1e3a8a" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
                                    <p class="font-bold text-3xl auth-color">Charts of Accounts</p>
                                </div>
                            </div>
                            <div class="flex items-center px-10">
                                <div class="flex items-center">            
                                    <p class="taxuri-text text-sm font-normal">The Chart of Accounts feature organizes all your financial accounts in one <br> place, making it simple to manage and track your company’s finances.</p>
                                </div>
                            </div>      
                        </div>

                        <div x-data="{ showCheckboxes: false, selectedTab: 'All', checkAll: false }" class="container mx-auto pt-2 ">
                            <!-- Second Header -->
                            <div class="container mx-auto ps-8">
                                <div class="flex flex-row space-x-2 items-center justify-center">
                                    <div x-data="{ selectedTab: 'Accounts' }" class="w-full">
                                        <div @keydown.right.prevent="$focus.wrap().next()" @keydown.left.prevent="$focus.wrap().previous()" class="flex justify-center gap-24 overflow-x-auto  border-neutral-300" role="tablist" aria-label="tab options">
                                            <button @click="selectedTab = 'Accounts'" :aria-selected="selectedTab === 'Accounts'" 
                                            :tabindex="selectedTab === 'Accounts' ? '0' : '-1'" 
                                            :class="selectedTab === 'Accounts' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900'   : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'" 
                                            class="h-min py-2 text-base" 
                                            type="button"
                                            role="tab" 
                                            aria-controls="tabpanelAccounts" >Accounts</button>
                                            <button @click="selectedTab = 'Archive'" :aria-selected="selectedTab === 'Archive'" 
                                            :tabindex="selectedTab === 'Archive' ? '0' : '-1'" 
                                            :class="selectedTab === 'Archive' ? 'font-bold box-border text-blue-900 border-b-4 border-blue-900'   : 'text-neutral-600 font-medium hover:border-b-2 hover:border-b-blue-900 hover:text-blue-900'"
                                            class="h-min py-2 text-base" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="tabpanelArchive" >Archive</button>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                            <hr>
                        
                            <!-- Filtering Tab/Third Header -->
                            <div x-data="{
                                    selectedTab: (new URL(window.location.href)).searchParams.get('type') || 'All',
                                    checkAll: false,
                                    init() {
                                        this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || 'All';
                                    }
                                }" 
                                x-init="init" 
                                class="w-full p-5">
                                <div @keydown.right.prevent="$focus.wrap().next()" 
                                    @keydown.left.prevent="$focus.wrap().previous()" 
                                    class="flex flex-row text-center overflow-x-auto ps-8" 
                                    role="tablist" 
                                    aria-label="tab options">
                                    
                                    <!-- Tab 1: All -->
                                        <button @click="selectedTab = 'All'; $dispatch('filter', { type: 'All' })"
                                        :aria-selected="selectedTab === 'All'" 
                                        :tabindex="selectedTab === 'All' ? '0' : '-1'" 
                                        :class="selectedTab === 'All' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'"
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelAll">
                                        All
                                    </button>
                                    
                                    <!-- Tab 2: Assets -->
                                    <button @click="selectedTab = 'Assets'; $dispatch('filter', { type: 'Assets' })" 
                                        :aria-selected="selectedTab === 'Assets'" 
                                        :tabindex="selectedTab === 'Assets' ? '0' : '-1'" 
                                        :class="selectedTab === 'Assets' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelAssets">
                                        Assets
                                    </button>
                                    
                                    <!-- Tab 3: Liabilities -->
                                    <button @click="selectedTab = 'Liabilities'; $dispatch('filter', { type: 'Liabilities' })" 
                                        :aria-selected="selectedTab === 'Liabilities'" 
                                        :tabindex="selectedTab === 'Liabilities' ? '0' : '-1'" 
                                        :class="selectedTab === 'Liabilities' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelLiabilities">
                                        Liabilities
                                    </button>
                                    
                                    <!-- Tab 4: Equity -->
                                    <button @click="selectedTab = 'Equity'; $dispatch('filter', { type: 'Equity' })"
                                        :aria-selected="selectedTab === 'Equity'" 
                                        :tabindex="selectedTab === 'Equity' ? '0' : '-1'" 
                                        :class="selectedTab === 'Equity' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelEquity">
                                        Equity
                                    </button>
                                    
                                    <!-- Tab 5: Revenue -->
                                    <button @click="selectedTab = 'Revenue'; $dispatch('filter', { type: 'Revenue' })"
                                        :aria-selected="selectedTab === 'Revenue'" 
                                        :tabindex="selectedTab === 'Revenue' ? '0' : '-1'" 
                                        :class="selectedTab === 'Revenue' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelRevenue">
                                        Revenue
                                    </button>

                                    <!-- Tab 6: Cost of Sales -->
                                    <button @click="selectedTab = 'Cost of Sales'; $dispatch('filter', { type: 'Cost of Sales' })"
                                        :aria-selected="selectedTab === 'Cost of Sales'" 
                                        :tabindex="selectedTab === 'Cost of Sales' ? '0' : '-1'" 
                                        :class="selectedTab === 'Cost of Sales' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelCostofSales">
                                        Cost of Sales
                                    </button>
                                    
                                    <!-- Tab 7: Expenses -->
                                    <button @click="selectedTab = 'Expenses'; $dispatch('filter', { type: 'Expenses' })"
                                        :aria-selected="selectedTab === 'Expenses'" 
                                        :tabindex="selectedTab === 'Expenses' ? '0' : '-1'" 
                                        :class="selectedTab === 'Expenses' 
                                            ? 'font-bold text-blue-900 bg-slate-100 rounded-lg'
                                            : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                        class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap" 
                                        type="button" 
                                        role="tab" 
                                        aria-controls="tabpanelExpenses">
                                        Expenses
                                    </button>
                                </div>
                            </div>
                            <hr>

                            <div x-data="{ showCheckboxes: false, selectedTab: 'All', checkAll: false }" class="container mx-auto">
                                <!-- Fourth Header -->
                                <div class="container mx-auto ps-8">
                                    <div class="flex flex-row space-x-2 items-center justify-between">
                                        <!-- Search row -->
                                        <div class="relative w-80 p-5">
                                            <form x-target="tableid" action="/coa" role="search" aria-label="Table" autocomplete="off">
                                                <input 
                                                type="search" 
                                                name="search" 
                                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-900 focus:border-blue-900" 
                                                aria-label="Search Term" 
                                                placeholder="Search..." 
                                                @input.debounce="$el.form.requestSubmit()" 
                                                @search="$el.form.requestSubmit()"
                                                >
                                                </form>
                                            <i class="fa-solid fa-magnifying-glass absolute left-8 top-1/2 transform -translate-y-1/2 text-gray-400" @click="search = ''"></i>
                                        </div>
                                        <!-- End row -->
                                        <div class="mx-auto space-x-4 pr-12">
                                            <!-- Add COA Modal -->
                                            <x-add-coa-modal />
                                            <button 
                                                x-data 
                                                x-on:click="$dispatch('open-add-modal')" 
                                                class="border px-3 py-2 rounded-lg text-sm items-center hover:border-green-600 hover:text-green-600 hover:bg-green-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 transition-colors" viewBox="0 0 32 32" :class="{ 'fill-green-600': $el.closest('button'):hover }">
                                                    <path fill="currentColor" d="M16 3C8.832 3 3 8.832 3 16s5.832 13 13 13s13-5.832 13-13S23.168 3 16 3m0 2c6.087 0 11 4.913 11 11s-4.913 11-11 11S5 22.087 5 16S9.913 5 16 5m-1 5v5h-5v2h5v5h2v-5h5v-2h-5v-5z"/>
                                                </svg>
                                                <span class="text-sm text-zinc-600 hover:text-green-600">Add</span>
                                            </button>
                                            <!-- Import COA Modal -->
                                            <x-import-coa-modal />     
                                            <button  
                                                x-data 
                                                x-on:click="$dispatch('open-import-modal')" 
                                                class="border px-3 py-2 rounded-lg text-sm items-center hover:border-green-600 hover:text-green-600 hover:bg-green-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 transition-colors" viewBox="0 0 24 24" :class="{ 'fill-green-600': $el.closest('button'):hover }"><g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path fill="currentColor" d="M14 2v4a2 2 0 0 0 2 2h4M9 15h6m-3 3v-6"/></g></svg>
                                                <span class="text-sm text-zinc-600 hover:text-green-600">Import</span>
                                            </button>
                                            <button type="button" @click="showCheckboxes = !showCheckboxes" 
                                                class="border px-3 py-2 rounded-lg text-sm items-center hover:border-blue-900 hover:text-blue-900 hover:bg-blue-100 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2M7 11l5 5l5-5m-5-7v12"/>
                                                </svg>
                                                <span class="text-sm text-zinc-600 hover:text-blue-900">Download</span>
                                            </button>
                                            <button type="button" @click="showCheckboxes = !showCheckboxes" 
                                                class="border px-3 py-2 rounded-lg text-sm items-center hover:border-red-900 hover:text-red-900 hover:bg-red-100 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 transition-colors" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                                    <path d="M3 6h18m-2 0v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6m3 0V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2m-6 5v6m4-6v6"/>
                                                </svg>
                                                <span class="text-sm text-zinc-600 hover:text-red-900">Delete</span>
                                            </button>
                                            <button type="button">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            

                                <!-- Table -->
                                <div x-data="{ checkAll: false, }" 
                                    class="mb-12 mx-12 overflow-hidden max-w-full border-neutral-300">
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left text-sm text-neutral-600" id="tableid">
                                            <thead class="bg-neutral-100 text-sm text-neutral-900">
                                                <tr>
                                                    <th scope="col" class="p-4">
                                                        <label for="checkAll" x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                            <div class="relative flex items-center">
                                                                <input type="checkbox" x-model="checkAll" id="checkAll" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0" />
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                                </svg>
                                                            </div>
                                                        </label>
                                                    </th>
                                                    <th scope="col" class="py-4 px-2">Code</th>
                                                    <th scope="col" class="py-4 px-2">Name</th>
                                                    <th scope="col" class="py-4 px-4">Type</th>
                                                    <th scope="col" class="py-4 px-3">Date Created</th>
                                                    <th scope="col" class="py-4 px-2">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-neutral-300">
                                                @if (count($coas) >0)
                                                    @foreach ($coas as $coa)
                                                        <tr>
                                                            <td class="p-4">
                                                                <label x-show="showCheckboxes" class="flex items-center cursor-pointer text-neutral-600">
                                                                    <div class="relative flex items-center">
                                                                        <input type="checkbox" id="user2335" class="before:content[''] peer relative size-4 cursor-pointer appearance-none overflow-hidden rounded border border-neutral-300 bg-white before:absolute before:inset-0 checked:border-black checked:before:bg-black focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-neutral-800 checked:focus:outline-black active:outline-offset-0" :checked="checkAll" />
                                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="4" class="pointer-events-none invisible absolute left-1/2 top-1/2 size-3 -translate-x-1/2 -translate-y-1/2 text-neutral-100 peer-checked:visible">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                                        </svg>
                                                                    </div>
                                                                </label>
                                                            </td>
                                                            <td class="py-4 px-2">{{$coa ->code}}</td>
                                                            <td class="py-4 px-2">{{$coa ->name}}</td>
                                                            <td class="py-4 px-2">{{$coa ->type}}</td>
                                                            <td class="py-4 px-2">{{$coa ->created_at}}</td>
                                                            <td class="text-blue-500 underline py-4 px-2"><p>Edit</p></td>
                                                        </tr>                             
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="6" class="text-center p-4">
                                                            <img src="{{ asset('images/Wallet 02.png') }}" alt="No data available" class="mx-auto" />
                                                            <h1 class="font-bold mt-2">No Charts of accounts yet</h1>
                                                            <p class="text-sm text-neutral-500 mt-2">Start adding accounts with the <br> + button beside the import button.</p>
                                                        </td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                        {{ $coas->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End of Main Content -->

                    <!-- Extension modals -->
                    <x-add-coa-modal />
                    <x-import-coa-modal />
                </div>
            </div>
        </div>
        
    <!-- Script -->
    <script>
        document.addEventListener('search', event => {
            window.location.href = `?search=${event.detail.search}`;
        });

        document.addEventListener('filter', event => {
            const url = new URL(window.location.href);
            url.searchParams.set('type', event.detail.type);
            window.location.href = url.toString();
        });

        // document.addEventListener('filter', event => {
        //     window.location.href = `?type=${event.detail.type}`;
        // });

        function toggleCheckboxes() {
            document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.checked = this.checkAll;
            });
        }
        
    </script>
</x-app-layout>