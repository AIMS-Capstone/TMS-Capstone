<div 
    x-data="{ show: false, contact: {} }"
    x-show="show"
    x-on:open-edit-contact-modal.window="show = true; contact = $event.detail"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 opacity-5"></div>

    <!-- Modal Container -->
    <div class="bg-white rounded-lg w-full max-w-xl mx-auto z-10 overflow-hidden">
        <!-- Modal Header -->
        <div class="flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto relative">
            <h1 class="text-white text-lg font-bold text-center">Edit Contact</h1>
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
            <form id="editContactForm" method="POST" :action="'/contacts/' + contact.id" >
                @csrf
                @method('PUT')

                <div class="mb-6 flex justify-between items-start">
                    <!-- Name -->
                    <div class="w-[50%] pr-4">
                        <label for="contactName" class="block text-sm font-bold text-gray-700">Name <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="contactName" 
                            name="bus_name" 
                            x-model="contact.bus_name" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            placeholder="Contact Name"
                            required>
                    </div>
                    <!-- Contact Type -->
                    <div class="w-2/3">
                        <label for="contactType" class="block text-sm font-bold text-gray-700">Contact Type <span class="text-red-500">*</span></label>
                        <select id="contactType" name="contact_type" 
                            x-model="contact.contact_type" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            required>
                            <option value="Customer">Customer</option>
                            <option value="Vendor">Vendor</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6 flex justify-between items-start">
                    <!-- TIN -->
                    <div class="w-2/3">
                        <label for="contactTIN" class="block text-sm font-bold text-gray-700">Tax Identification Number (TIN) <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="contactTIN" 
                            name="contact_tin" 
                            x-model="contact.contact_tin" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            placeholder="000-000-000-000" 
                            required>
                    </div>
                    <!-- Classification -->
                    <div class="w-[50%] pl-4">
                        <label for="classification" class="block text-sm font-bold text-gray-700">Classification <span class="text-red-500">*</span></label>
                        <select id="classification" name="classification" 
                            x-model="contact.classification" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            required>
                            <option value="Invidiual">Invidiual</option>
                            <option value="Non-Invidiual">Non-Invidiual</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6 flex justify-between items-start">
                    <!-- Address -->
                    <div class="w-2/3 pr-4">
                        <label for="contactAddress" class="block text-sm font-bold text-gray-700">Address <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="contactAddress" 
                            name="contact_address" 
                            x-model="contact.contact_address" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            placeholder="Contact Address" 
                            required>
                    </div>

                    <div class="w-2/3 pr-4">
                        <label for="contactCity" class="block text-sm font-bold text-gray-700">City <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="contactCity" 
                            name="contact_city" 
                            x-model="contact.contact_city" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            placeholder="City" 
                            required>
                    </div>
                    <div class="w-2/3 pr-4">
                        <label for="contactZip" class="block text-sm font-bold text-gray-700">Zip Code <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="contactZip" 
                            name="contact_zip" 
                            x-model="contact.contact_zip" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            placeholder="Zip Code" 
                            required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6">
                    <button 
                        type="button"
                        x-on:click="$dispatch('close-modal')"
                        class="mr-2 font-semibold text-zinc-600 px-3 py-1 rounded-md hover:text-zinc-900 transition">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-900 text-white font-semibold px-5 py-2 rounded-md hover:bg-blue-950 transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- <!-- Success Modal: Not appearing -->
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
                <img src="{{ asset('images/Success.png') }}" alt="Contact Updated" class="w-28 h-28">
            </div>
            <!-- Success Modal Body -->
            <div class="text-center">
                <p class="text-emerald-500 font-bold text-3xl mb-2">Contact Details Updated</p>
                <p class="text-sm text-zinc-700 mb-6">The details of the contact has ben successfully<br>updated.</p>
            </div>
        </div>
    </div> --}}