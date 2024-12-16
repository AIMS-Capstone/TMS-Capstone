<div 
    x-data="{ show: false, success: false }"
    x-show="show"
    x-on:open-add-contacts-modal.window="show = true"
    x-on:close-modal.window="show = false"
    @submit-success.window="show = false; success = true"
    x-effect="document.body.classList.toggle('overflow-hidden', show || success)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
    >
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-200 opacity-50"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xl mx-auto z-10 overflow-hidden"
         x-show="show" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="opacity-0 scale-90" 
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-90">
        <!-- Modal header -->
        <div class="flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto relative">
            <h1 class="text-lg font-bold text-white">Add New Contact</h1>
            <!-- Top close button -->
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

        <!-- Modal body -->
        <div class="p-10">
            <form id="addContactForm" action="{{ route('contacts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="organization_id" value="{{ Auth::user()->organization_id }}">

                <div class="mb-6 flex justify-between items-start">
                    <!-- Name -->
                    <div class="w-[50%] pr-4">
                        <label for="bus_name" class="block font-semibold text-sm text-gray-700">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bus_name" id="bus_name" required
                            placeholder="Customer Name"
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Contact Type -->
                    <div class="w-[50%] pr-4">
                        <label for="contact_type" class="block font-semibold text-sm text-gray-700">
                            Contact Type <span class="text-red-500">*</span>
                        </label>
                        <select name="contact_type" id="contact_type" required
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                            <option value="" disabled selected>Select Contact Type</option>
                            <option value="Customer">Customer</option>
                            <option value="Vendor">Vendor</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6 flex justify-between items-start">
                    <!-- TIN -->
                    <div class="w-[50%] pr-4">
                        <label for="contact_tin" class="block font-semibold text-sm text-gray-700">
                            Tax Identification Number (TIN) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="contact_tin" id="contact_tin" required
                            placeholder="000-000-000-000" maxlength="17"
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <div class="w-[50%] pr-4">
                        <label for="classification" class="block font-semibold text-sm text-gray-700">
                           Classification<span class="text-red-500">*</span>
                        </label>
                        <select name="classification" id="classification" required
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                            <option value="" disabled selected>Select Classification Type</option>
                            <option value="Individual">Individual</option>
                            <option value="Non-Individual">Non-Individual</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6 flex justify-between items-start">
                    <!-- Address -->
                    <div class="w-2/3 pr-4">
                        <label for="contact_address" class="block font-semibold text-sm text-gray-700">
                            Address <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="contact_address" id="contact_address" required
                            placeholder="Address"
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- City -->
                    <div class="w-2/3 pr-4">
                        <label for="contact_city" class="block font-semibold text-sm text-gray-700">
                            City <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="contact_city" id="contact_city" required
                            placeholder="City"
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Zip Code -->
                    <div class="w-2/3 pr-4">
                        <label for="contact_zip" class="block font-semibold text-sm text-gray-700">
                            Zip Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="contact_zip" id="contact_zip" required
                            placeholder="e.g., 1446" maxlength="4"
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button 
                        type="button" 
                        x-on:click="$dispatch('close-modal')"
                        class="mr-4 font-semibold text-zinc-600 px-3 py-1 rounded-md hover:text-zinc-900 transition"
                        >
                        Cancel
                    </button>
                    {{-- Edit button dapat: same sa coa, and then sa edit-contact modal yung may "Update" button --}}
                    <button 
                        type="submit" 
                        class="px-6 py-1.5 font-semibold bg-blue-900 text-white rounded-md hover:bg-blue-950 transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Success Modal: Not appearing -->
    <div x-show="success" class="fixed inset-0 bg-gray-600 bg-opacity-50 z-50 flex items-center justify-center" x-cloak>
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto h-auto z-10 overflow-hidden p-10 relative">
            <!-- Close Button -->
            <button @click="success = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <!-- Success Modal Header (Centered Image) -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/Success.png') }}" alt="Contact Added" class="w-28 h-28">
            </div>
            <!-- Success Modal Body -->
            <div class="text-center">
                <p class="text-emerald-500 font-bold text-3xl mb-2">New Contact Added</p>
                <p class="text-sm text-zinc-700 mb-6">The new contact has been successfully<br> added.</p>
            </div>
        </div>
    </div>