<div 
    x-data="{ show: false, coa: {} }"
    x-show="show"
    x-on:open-edit-modal.window="show = true; coa = $event.detail"
    x-on:close-modal.window="show = false"
    class="fixed z-50 inset-0 flex items-center justify-center m-2"
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-300 opacity-60"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-screen-xl max-h-screen mx-auto h-auto z-10 overflow-hidden">
        <!-- Modal header -->
        <div class="flex justify-center items-center p-3 bg-sky-900 border-opacity-80 w-full">
            <h1 class="text-lg font-bold text-white">Edit Chart of Accounts</h1>
        </div>
        
        <!-- Modal body -->
        <div class="p-4">
            <form id="editAccountForm" method="POST" :action="'/coa/' + coa.id">
                @csrf
                @method('PUT')

                <!-- COA Code -->
                <div class="mb-4">
                    <label for="coaCode" class="block text-sm font-medium text-gray-700">Code</label>
                    <input type="text" id="coaCode" name="code" x-model="coa.code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm" required>
                </div>

                <!-- COA Name -->
                <div class="mb-4">
                    <label for="coaName" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="coaName" name="name" x-model="coa.name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm" required>
                </div>

                <!-- COA Type -->
                <div class="mb-4">
                    <label for="coaType" class="block text-sm font-medium text-gray-700">Type</label>
                    <input type="text" id="coaType" name="type" x-model="coa.type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-sky-500 focus:border-sky-500 sm:text-sm" required>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-sky-600 text-white px-4 py-2 rounded-md hover:bg-sky-700">
                        Save Changes
                    </button>
                    <button type="button" @click="$dispatch('close-modal')" class="ml-2 bg-gray-400 text-white px-4 py-2 rounded-md hover:bg-gray-500">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
