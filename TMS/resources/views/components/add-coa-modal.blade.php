<div 
    x-data="{ show: false }"
    x-show="show"
    x-on:open-add-modal.window="show = true"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
    >
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-300 opacity-60"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden">
        <!-- Modal header -->
        <div class="flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto">
            <h1 class="text-lg font-bold text-white">Add New Account</h1>
        </div>

        <!-- Modal body -->
        <div class="p-10">
            <form id="addAccountForm" action="{{ route('coa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf   

                <div class="mb-5 flex justify-between items-start">
                    <!-- Account Type -->
                    <div class="w-2/3 pr-4">
                        <input type="hidden" name="submit_action" value="manual">
                        <label for="type" class="block font-semibold text-sm text-gray-700">
                            Account Type <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="account_type_input" id="accountTypeInput" required
                            placeholder="Expense | Ordinary Allowance Itemized Deductions"
                            class="peer py-2.5 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none">
                        <p class="text-[10px] opacity-75 px-1">Enter in the format: Type | Sub Type</p>
                    </div>
                    <!-- Account Code -->
                    <div class="w-1/3 text-left">
                        <label for="code" class="block font-semibold text-sm text-gray-700">
                            Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" required
                            maxlength="10" class="peer py-2.5 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none">
                        <p class="text-[10px] opacity-75 px-1">Unique code/number for this account (limited 10 characters)</p>
                    </div>
                </div>

                <!-- Account Name -->
                <div class="mb-3">
                    <label for="name" class="block font-semibold text-sm text-gray-700">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        maxlength="150" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none">
                    <p class="text-xs opacity-75 px-1">A short title for this account (limited 150 characters)</p>
                </div>

                <!-- Account Description -->    
                <div class="mb-3">
                    <label for="description" class="block font-semibold text-sm text-gray-700">
                        Description
                    </label>
                    <input type="text" name="description" id="description"  
                        maxlength="100" class="peer py-3 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none">
                    <p class="text-xs opacity-75 px-1">A description of how this account should be used</p>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end my-4">
                    <button 
                        x-on:click="$dispatch('close-modal')"
                        class="mr-2 font-semibold text-zinc-700 px-3 py-1 rounded-md hover:bg-zinc-300 hover:text-white transition">
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
