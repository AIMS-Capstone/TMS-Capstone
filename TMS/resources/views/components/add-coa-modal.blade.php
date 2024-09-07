<div 
    x-data="{ show: false }"
    x-show="show"
    x-on:open-add-modal.window="show = true"
    x-on:close-modal.window="show = false"
    class="fixed z-50 inset-0 flex items-center justify-center m-2"
>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-300 opacity-60"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md max-h-screen mx-auto h-auto z-10">
        <!-- Modal header -->
        <div class="flex justify-center items-center p-3 border-b border-opacity-80 w-2/3 mx-auto">
            <h1 class="text-lg font-bold">Add New Account</h1>
        </div>

        <!-- Modal body -->
        <div class="pt-4 px-3 mx-10 space-y-3">
            <form id="addAccountForm" action="/add-coa-account" method="POST">
                @csrf

                <!-- Account Type -->
                <div class="mb-3">
                    <label for="accountType" class="block font-semibold text-sm text-gray-700">
                        Account Type <span class="text-red-500">*</span>
                    </label>
                    <select name="accountType" id="accountType" required
                        class="mt-1 block w-full ps-4 p-2 text-xs border rounded-md focus:ring-sky-900 focus:border-sky-900">
                        <option value="" disabled selected>Sales/Revenues/Receipts/Fees</option>
                        <option value="assets">Assets</option>
                        <option value="liabilities">Liabilities</option>
                        <option value="equity">Equity</option>
                        <option value="revenues">Revenue</option>
                        <option value="sales">Cost of Sales</option>
                        <option value="expense">Expenses</option>
                        <option value="per-revenue">Percentage of Revenue</option>
                    </select>
                </div>

                <!-- Account Code -->
                <div class="mb-3">
                    <label for="accountCode" class="block font-semibold text-sm text-gray-700">
                        Code <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="accountCode" id="accountCode" required
                        class="mt-1 block w-full ps-4 p-2 text-sm border rounded-md focus:ring-sky-900 focus:border-sky-900">
                    <p class="text-xs opacity-75 px-1">Unique code/number for this account (max 10 characters)</p>
                </div>

                <!-- Account Name -->
                <div class="mb-3">
                    <label for="accountName" class="block font-semibold text-sm text-gray-700">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="accountName" id="accountName" required
                        class="mt-1 block w-full ps-4 p-2 text-sm border rounded-md focus:ring-sky-900 focus:border-sky-900">
                    <p class="text-xs opacity-75 px-1">Short title for this account (max 150 characters)</p>
                </div>

                <!-- Account Description -->    
                <div class="mb-3">
                    <label for="accountDescription" class="block font-semibold text-sm text-gray-700">Description</label>
                    <input type="text" name="accountDescription" id="accountDescription" required
                        class="mt-1 block w-full ps-4 p-2 text-sm border rounded-md focus:ring-sky-900 focus:border-sky-900">
                    <p class="text-xs opacity-75 px-1">A description of how this account should be used</p>
                </div>

            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end p-3 mx-6 mb-4">
            <button 
                x-on:click="$dispatch('close-modal')"
                class="mr-2 font-semibold text-black px-3 py-1 rounded-md hover:bg-sky-900 hover:text-white transition"
            >
                Cancel
            </button>
            <button 
                type="submit" form="addAccountForm"
                class="font-semibold bg-sky-900 text-white px-3 py-1 me-8 rounded-md border-x-8 border-sky-900 hover:border-x-8 hover:text-black transition"
            >
                Save
            </button>
        </div>
    </div>
</div>
