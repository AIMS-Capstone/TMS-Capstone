<div 
    x-data="{ show: false, success: false }"
    x-show="show"
    x-on:open-add-modal.window="show = true"
    x-on:close-modal.window="show = false"
    @submit-success.window="show = false; success = true"
    x-effect="document.body.classList.toggle('overflow-hidden', show || success)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
    >
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-200 opacity-50"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden"
         x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
        <!-- Modal header -->
        <div class="relative flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto">
            <h1 class="text-lg font-bold text-white">Add New Account</h1>
            <button 
                x-on:click="$dispatch('close-modal')" 
                class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200 z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                          d="M8 8L16 16M8 16L16 8" 
                          stroke="#1e3a8a" 
                          class="transition duration-200 hover:stroke-gray-600" />
                </svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="p-10">
            <form id="addAccountForm" action="{{ route('coa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf   
                <input type="hidden" name="organization_id" value="{{ Auth::user()->organization_id }}">

                <div class="mb-5 flex justify-between items-start">
                    <!-- Account Type -->
                    <div class="w-2/3 pr-4">
                        <input type="hidden" name="submit_action" value="manual">
                        <label for="type" class="block font-semibold text-sm text-gray-700">
                            Account Type <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="type" id="type" required
                            placeholder="Expense"
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                    <div class="w-1/3 text-left">
                        <label for="sub_type" class="block font-semibold text-sm text-gray-700">
                            Sub Type <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="sub_type" id="sub_type" required
                            maxlength="10" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                </div>

                <!-- Account Name -->
                <div class="mb-3 flex justify-between items-start">
                    <div class="w-2/3 pr-4">
                        <label for="name" class="block font-semibold text-sm text-gray-700">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" required
                            maxlength="150" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        <p class="text-xs opacity-75 px-1">A short title for this account (limited 150 characters)</p>
                    </div>
                    <!-- Account Code -->
                    <div class="w-1/3 text-left">
                        <label for="code" class="block font-semibold text-sm text-gray-700">
                            Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" required
                            maxlength="10" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        <p class="text-[10px] opacity-75 px-1">Unique code/number for this account (limited 10 characters)</p>
                    </div>
                </div>

                <!-- Account Description -->    
                <div class="mb-3">
                    <label for="description" class="block font-semibold text-sm text-gray-700">
                        Description
                    </label>
                    <input type="text" name="description" id="description"  
                        maxlength="100" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    <p class="text-xs opacity-75 px-1">A description of how this account should be used</p>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end my-4">
                    <button 
                        x-on:click="$dispatch('close-modal')"
                        class="mr-2 font-semibold text-zinc-600 px-3 py-1 rounded-md hover:text-zinc-900 transition">
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="font-semibold bg-blue-900 text-white text-center px-6 py-1.5 rounded-md hover:bg-blue-950 border-blue-900 hover:text-white transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
    
</div>

{{-- <!-- Success Modal: Not appearing -->
    <div x-show="success" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center" x-cloak>
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto h-auto z-10 overflow-hidden p-10 relative">
            <!-- Close Button -->
            <button @click="success = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <!-- Success Modal Header (Centered Image) -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/Success.png') }}" alt="Chart of Accounts Added" class="w-28 h-28">
            </div>
            <!-- Success Modal Body -->
            <div class="text-center">
                <p class="text-emerald-500 font-bold text-3xl mb-2">New Account Added</p>
                <p class="text-sm text-zinc-700 mb-6">New Account has been successfully<br>added in COA.</p>
            </div>
        </div>
    </div> --}}