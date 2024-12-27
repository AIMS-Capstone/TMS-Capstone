<div 
    x-data="{ show: false, contact: {} }"
    x-show="show"
    x-on:open-view-contact-modal.window="show = true; contact = $event.detail"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
>
    <!-- Modal Background -->
    <div class="fixed inset-0 bg-gray-200 bg-opacity-50"></div>

    <!-- Modal Container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xl mx-auto z-10 overflow-hidden"
        x-show="show"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        >
        <!-- Modal Header -->
        <div class="flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto relative">
            <h1 class="text-white text-lg font-bold text-center">Contact Details</h1>
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

        <!-- Modal Body -->
        <div class="p-10">
            <!-- Contact Type and TIN -->
            <div class="mb-6 flex justify-between items-start">
                <!-- Name -->
                <div class="w-[50%] pr-4">
                    <label class="block text-sm font-semibold text-gray-700">Name</label>
                    <input 
                        class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        x-bind:value="contact.bus_name" 
                        disabled 
                        readonly
                    >
                </div>
                <div class="w-2/3">
                    <label class="block text-sm font-semibold text-gray-700">Contact Type</label>
                    <input 
                        class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        x-bind:value="contact.contact_type" 
                        disabled 
                        readonly
                    >
                </div>
            </div>

            <div class="mb-6 flex justify-between items-start">
                <!-- TIN -->
                <div class="w-2/3">
                    <label class="block text-sm font-semibold text-gray-700">Tax Identification Number (TIN)</label>
                    <input 
                        class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        x-bind:value="contact.contact_tin" 
                        disabled 
                        readonly
                    >
                </div>
                <!-- Classification -->
                <div class="w-[50%] pl-4">
                    <label class="block text-sm font-semibold text-gray-700">Classification</label>
                    <input 
                        class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        x-bind:value="contact.classification" 
                        disabled 
                        readonly
                    >
                </div>

            </div>

            <!-- Address, City, and Zip Code -->
            <div class="mb-6 flex justify-between items-start">
                <div class="w-2/3 pr-4">
                    <label class="block text-sm font-semibold text-gray-700">Address</label>
                    <input 
                        class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        x-bind:value="contact.contact_address" 
                        disabled 
                        readonly
                    >
                </div>
                <div class="w-2/3 pr-4">
                    <label class="block text-sm font-semibold text-gray-700">City</label>
                    <input 
                        class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        x-bind:value="contact.contact_city" 
                        disabled 
                        readonly
                    >
                </div>
                <div class="w-2/3 pr-4">
                    <label class="block text-sm font-semibold text-gray-700">Zip Code</label>
                    <input 
                        class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        x-bind:value="contact.contact_zip" 
                        disabled 
                        readonly
                    >
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end">
                <x-edit-contact />
                <button 
                    @click="$dispatch('open-edit-contact-modal', contact)" 
                    class="bg-blue-900 text-white px-5 py-2 font-semibold rounded-md hover:bg-blue-950 transition"
                    >
                    Edit
                </button>
            </div>
        </div>
    </div>
</div>
