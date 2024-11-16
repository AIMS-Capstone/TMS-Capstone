<div 
    x-data="{ show: false, coa: {} }"
    x-show="show"
    x-on:open-view-modal.window="show = true; coa = $event.detail"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-200 opacity-20"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-md w-full max-w-lg mx-auto h-auto z-10 overflow-hidden"
         x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
        <!-- Modal header with dynamic COA Name -->
        <div class="relative p-3 bg-blue-900 border-opacity-80 w-full">
            <h1 class="text-lg font-bold text-white text-center" x-text="coa.name"></h1>
            <button @click="$dispatch('close-modal')" class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <!-- Circle Background -->
                    <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300"/>
                    <!-- X Icon -->
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 8L16 16M8 16L16 8" stroke="#1e3a8a" class="transition duration-200 hover:stroke-gray-600"/>
                </svg>
            </button>
        </div>

        <!-- Modal body -->
        <div class="p-10">
            <!-- COA Type and Code in a Row -->
            <div class="mb-5 flex justify-between items-start">
                <div class="w-2/3 pr-4">
                    <label class="block text-sm font-bold text-zinc-700">Account Type</label>
                    <input class="peer py-3 pe-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200 focus:outline-none focus:ring-0"
                        x-bind:value="coa.sub_type ? `${coa.type} | ${coa.sub_type}` : coa.type" disabled readonly
                    >
                </div>
                <div class="w-1/3 text-left">
                    <label class="block text-sm font-bold text-zinc-700">Code</label>
                    <input class="peer py-3 pe-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200 focus:outline-none focus:ring-0"
                        x-bind:value="coa.code" disabled readonly
                    >
                </div>
            </div>
    
            <!-- COA Name -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-zinc-700">Name</label>
                <input class="peer py-3 pe-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200 focus:outline-none focus:ring-0"
                    x-bind:value="coa.name" disabled readonly
                >
            </div>
    
            <!-- COA Description -->
            <div class="mb-5">
                <label class="block text-sm font-bold text-zinc-700">Description</label>
                <input class="peer py-3 pe-0 block w-full bg-transparent border-t-transparent border-b-2 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200 focus:outline-none focus:ring-0"
                    x-bind:value="coa.description" disabled readonly
                >
            </div>
    
            <div class="flex justify-end">
                <x-edit-coa />
                <button 
                    @click="$dispatch('open-edit-modal', {
                        id: coa.id,
                        code: coa.code,
                        name: coa.name,
                        type: coa.type,
                        sub_type: coa.sub_type,
                        description: coa.description
                    })"
                class="font-semibold bg-blue-900 text-white px-4 py-2 rounded-md border-x-8 border-blue-900 hover:border-x-8 hover:text-white transition"
                >
                    Edit
                </button>
            </div>
        </div>
    </div>
</div>
