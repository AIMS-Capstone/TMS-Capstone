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
    <div class="fixed inset-0 bg-gray-200 bg-opacity-20"></div>

    <!-- Modal Container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto z-10 overflow-hidden"
        x-show="show"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
    >
        <!-- Modal Header -->
        <div class="relative p-4 bg-blue-900 text-white rounded-t-lg">
            <h2 class="text-lg font-bold text-center">Contact Details</h2>
            <button 
                @click="$dispatch('close-modal')" 
                class="absolute right-3 top-3 text-white hover:text-gray-200 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <!-- Contact Type and TIN -->
            <div class="mb-5 grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Type</label>
                    <input 
                        class="peer py-2 px-0 w-full bg-transparent border-b-2 border-gray-300 text-sm focus:border-gray-400 focus:outline-none focus:ring-0" 
                        x-bind:value="contact.contact_type" 
                        disabled 
                        readonly
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tax Identification Number (TIN)</label>
                    <input 
                        class="peer py-2 px-0 w-full bg-transparent border-b-2 border-gray-300 text-sm focus:border-gray-400 focus:outline-none focus:ring-0" 
                        x-bind:value="contact.contact_tin" 
                        disabled 
                        readonly
                    >
                </div>
            </div>

            <!-- Name -->
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input 
                    class="peer py-2 px-0 w-full bg-transparent border-b-2 border-gray-300 text-sm focus:border-gray-400 focus:outline-none focus:ring-0" 
                    x-bind:value="contact.bus_name" 
                    disabled 
                    readonly
                >
            </div>

            <!-- Address, City, and Zip Code -->
            <div class="mb-5 grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <input 
                        class="peer py-2 px-0 w-full bg-transparent border-b-2 border-gray-300 text-sm focus:border-gray-400 focus:outline-none focus:ring-0" 
                        x-bind:value="contact.contact_address" 
                        disabled 
                        readonly
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">City</label>
                    <input 
                        class="peer py-2 px-0 w-full bg-transparent border-b-2 border-gray-300 text-sm focus:border-gray-400 focus:outline-none focus:ring-0" 
                        x-bind:value="contact.contact_city" 
                        disabled 
                        readonly
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Zip Code</label>
                    <input 
                        class="peer py-2 px-0 w-full bg-transparent border-b-2 border-gray-300 text-sm focus:border-gray-400 focus:outline-none focus:ring-0" 
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
                    class="bg-blue-900 text-white px-5 py-2 rounded-md hover:bg-blue-800 transition"
                >
                    Edit
                </button>
            </div>
        </div>
    </div>
</div>
