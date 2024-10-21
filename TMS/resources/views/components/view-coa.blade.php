<div 
    x-data="{ show: false, coa: {} }"
    x-show="show"
    x-on:open-view-modal.window="show = true; coa = $event.detail"
    x-on:close-modal.window="show = false"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-300 opacity-40"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden">
        <!-- Modal header with dynamic COA Name -->
        <div class="relative p-3 bg-sky-900 border-opacity-80 w-full">
            <h1 class="text-lg font-bold text-white text-center" x-text="coa.name"></h1>
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
        <div class="p-5">
            <!-- COA Type and Code in a Row -->
            <div class="mb-5 flex justify-between items-start">
                <div class="w-2/3 pr-4">
                    <label class="block text-sm font-medium text-gray-700">Account Type</label>
                    <p class="mt-1 text-gray-800 truncate" x-text="coa.type"></p>
                </div>
                <div class="w-1/3 text-right">
                    <label class="block text-sm font-medium text-gray-700">Code</label>
                    <p class="mt-1 text-gray-800" x-text="coa.code"></p>
                </div>
            </div>

            <!-- COA Name -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <p class="mt-1 text-gray-800" x-text="coa.name"></p>
            </div>

            <!-- COA Description (Assuming there’s a description field) -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <p class="mt-1 text-gray-800" x-text="coa.description"></p>
            </div>
        </div>
    </div>
</div>
