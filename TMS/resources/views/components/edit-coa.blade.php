{{-- Note: Ito main na gamit for COA edit, hindi yung "edit-coa-modal" --}}

<div 
    x-data="{ show: false, coa: {} }"
    x-show="show"
    x-on:open-edit-modal.window="show = true; coa = $event.detail"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-300 opacity-40"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden">
        <!-- Modal header -->
        <div class="relative p-3 bg-blue-900 border-opacity-80 w-full">
            <h1 class="text-lg font-bold text-white text-center">Edit Chart of Accounts</h1>
            <button @click="$dispatch('close-modal')" class="absolute right-3 top-3 text-sm text-white hover:text-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <!-- Circle Background -->
                    <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300"/>
                    <!-- X Icon -->
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 8L16 16M8 16L16 8" stroke="#1e3a8a" class="transition duration-200 hover:stroke-gray-600"/>
                </svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="p-10">
            <form id="editAccountForm" method="POST" :action="'/coa/' + coa.id">
                @csrf
                @method('PUT')

                <div class="mb-5 flex justify-between items-start">
                    <!-- COA Type -->
                    <div class="w-2/3 pr-4">
                        <label for="coaType" class="block text-sm font-bold text-gray-700">Account Type</label>
                        <select name="type" id="coaType" x-model="coa.type" required class="peer block py-2.5 px-0 w-full text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 appearance-none cursor-pointer focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                            <option value="" disabled>Select Account Type</option>
                            <option value="Assets">Assets</option>
                            <option value="Liabilities">Liabilities</option>
                            <option value="Equity">Equity</option>
                            <option value="Revenues">Revenue</option>
                            <option value="Cost of Sales">Cost of Sales</option>
                            <option value="Expense">Expenses</option>
                        </select>
                    </div>
                    <!-- COA Code -->
                    <div class="w-1/3 text-left">
                        <label for="coaCode" class="block text-sm font-bold text-gray-700">Code</label>
                        <input type="text" id="coaCode" name="code" x-model="coa.code" class="peer py-3 pe-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" 
                        required maxlength="10">
                    </div>
                </div>

                <!-- COA Name -->
                <div class="mb-5">
                    <label for="coaName" class="block text-sm font-bold text-gray-700">Name</label>
                    <input type="text" id="coaName" name="name" x-model="coa.name" class="peer py-3 pe-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" required>
                </div>

                {{-- Please check, ayaw mag display --}}
                {{-- <div class="mb-5">
                    <label for="sub_type" class="block text-sm font-bold text-gray-700">Sub Category</label>
                    <input type="text" id="sub_type" name="sub_type" x-model="coa.sub_type" class="peer py-3 pe-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" required>
                </div> --}}

                {{-- Ayaw mag display ng inedit --}}
                <div class="mb-5">
                    <label for="coaDescription" class="block text-sm font-bold text-gray-700">Description</label>
                    <input type="text" id="coaDescription" name="description" x-model="coa.description" class="peer py-3 pe-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-t-transparent focus:border-x-transparent focus:border-b-blue-900 focus:ring-0 disabled:opacity-50 disabled:pointer-events-none" required>
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-900 text-white font-bold px-4 py-2 rounded-md hover:bg-blue-950">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
