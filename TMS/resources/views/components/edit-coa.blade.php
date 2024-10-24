<div 
    x-data="{ show: false, coa: {} }"
    x-show="show"
    x-on:open-edit-modal.window="show = true; coa = $event.detail"
    x-on:close-modal.window="show = false"
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
                    <circle cx="12" cy="12" r="10" fill="white" />
                    <!-- X Icon -->
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 8L16 16M8 16L16 8" stroke="black"/>
                </svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="p-6">
            <form id="editAccountForm" method="POST" :action="'/coa/' + coa.id">
                @csrf
                @method('PUT')

                <!-- COA Code -->
                <div class="mb-5">
                    <label for="coaCode" class="block text-sm font-medium text-gray-700">Code</label>
                    <input type="text" id="coaCode" name="code" x-model="coa.code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-900 focus:border-blue-900 sm:text-sm" required>
                </div>

                <!-- COA Name -->
                <div class="mb-5">
                    <label for="coaName" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="coaName" name="name" x-model="coa.name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-900 focus:border-blue-900 sm:text-sm" required>
                </div>

                <!-- COA Type -->
                <div class="mb-5">
                    <label for="coaType" class="block text-sm font-medium text-gray-700">Type</label>
                    <input type="text" id="coaType" name="type" x-model="coa.type" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-900 focus:border-blue-900 sm:text-sm" required>
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded-md hover:bg-blue-950">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
