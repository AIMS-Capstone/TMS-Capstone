<div 
    x-data="{ show: false }"
    x-show="show"
    x-on:open-add-modal.window="show = true"
    x-on:close-modal.window="show = false"
    class="fixed z-50 inset-0 flex items-center justify-center m-2"
    x-cloak
    >
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-300 opacity-60"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md max-h-screen mx-auto h-auto z-10">
        <!-- Modal header -->
        <div class="flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto">
            <h1 class="text-lg font-bold text-white">Add New Account</h1>
        </div>

        <!-- Modal body -->
        <div class="pt-4 px-3 mx-10 space-y-3">
            <form id="addAccountForm" action="{{ route('coa.store') }}" method="POST" enctype="multipart/form-data">
                @csrf   

                <!-- Account Type -->
                <div class="mb-3">
                    <input type="hidden" name="submit_action" value="manual">
                    <label for="type" class="block font-semibold text-sm text-gray-700">
                        Account Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="accountType" required
                        class="mt-1 block w-full ps-4 p-2 text-xs border rounded-md focus:ring-blue-900 focus:border-blue-900">
                        <option value="" disabled selected>Select Account Type</option>
                        <option value="Assets">Assets</option>
                        <option value="Liabilities">Liabilities</option>
                        <option value="Equity">Equity</option>
                        <option value="Revenue">Revenue</option>
                        <option value="Cost of Sales">Cost of Sales</option>
                        <option value="Expenses">Expenses</option>
                    </select>
                </div>

                <!-- Account Code -->
                <div class="mb-3">
                    <label for="code" class="block font-semibold text-sm text-gray-700">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" id="code" required
                        maxlength="5" class="mt-1 block w-full ps-4 p-2 text-sm border rounded-md focus:ring-blue-900 focus:border-blue-900">
                    <p class="text-xs opacity-75 px-1">Unique code/number for this account (limited 5 characters)</p>
                </div>

                <!-- Account Name -->
                <div class="mb-3">
                    <label for="name" class="block font-semibold text-sm text-gray-700">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        maxlength="150" class="mt-1 block w-full ps-4 p-2 text-sm border rounded-md focus:ring-blue-900 focus:border-blue-900">
                    <p class="text-xs opacity-75 px-1">Short title for this account (limited 150 characters)</p>
                </div>

                <!-- Account Description -->    
                <div class="mb-3">
                    <label for="description" class="block font-semibold text-sm text-gray-700">
                        Description
                    </label>
                    <input type="text" name="description" id="description"  
                        maxlength="100" class="mt-1 block w-full ps-4 p-2 text-sm border rounded-md focus:ring-blue-900 focus:border-blue-900">
                    <p class="text-xs opacity-75 px-1">A description of how this account should be used</p>
                </div>
                <!-- Modal Footer -->
                <div class="flex justify-end mb-4 float-end">
                    <button 
                        x-on:click="$dispatch('close-modal')"
                        class="mr-2 font-semibold text-zinc-700 px-3 py-1 rounded-md hover:bg-blue-900 hover:text-white transition">
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="font-semibold bg-blue-900 text-white px-3 py-1 me-8 rounded-md border-x-8 border-blue-900 hover:border-x-8 hover:text-white transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
