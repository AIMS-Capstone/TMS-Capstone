<div 
    x-data="{ show: false, contact: {} }"
    x-show="show"
    x-on:open-edit-contact-modal.window="show = true; contact = $event.detail"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
>
    <!-- Modal Background -->
    <div class="fixed inset-0 bg-gray-200 bg-opacity-20"></div>

    <!-- Modal Container -->
    <div class="bg-white rounded-lg w-full max-w-lg mx-auto z-10 overflow-hidden">
        <!-- Modal Header -->
        <div class="relative p-4 bg-blue-900 text-white rounded-t-lg">
            <h1 class="text-lg font-bold text-center">Edit Contact</h1>
            <button 
                @click="$dispatch('close-modal')" 
                class="absolute right-4 top-4 text-white hover:text-gray-200 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form id="editContactForm" method="POST" :action="'/contacts/' + contact.id">
                @csrf
                @method('PUT')

                <!-- Contact Type -->
                <div class="mb-5">
                    <label for="contactType" class="block text-sm font-bold text-gray-700">Contact Type</label>
                    <select id="contactType" name="contact_type" 
                        x-model="contact.contact_type" 
                        class="block w-full py-3 px-0 text-sm bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        required>
                        <option value="Individual">Individual</option>
                        <option value="Non-Individual">Non-Individual</option>
                    </select>
                </div>

                <!-- TIN -->
                <div class="mb-5">
                    <label for="contactTIN" class="block text-sm font-bold text-gray-700">Tax Identification Number (TIN)</label>
                    <input 
                        type="text" 
                        id="contactTIN" 
                        name="contact_tin" 
                        x-model="contact.contact_tin" 
                        class="block w-full py-3 px-0 text-sm bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        placeholder="000-000-000-000" 
                        required>
                </div>

                <!-- Name -->
                <div class="mb-5">
                    <label for="contactName" class="block text-sm font-bold text-gray-700">Name</label>
                    <input 
                        type="text" 
                        id="contactName" 
                        name="bus_name" 
                        x-model="contact.bus_name" 
                        class="block w-full py-3 px-0 text-sm bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        placeholder="Contact Name" 
                        required>
                </div>

                <!-- Address -->
                <div class="mb-5">
                    <label for="contactAddress" class="block text-sm font-bold text-gray-700">Address</label>
                    <input 
                        type="text" 
                        id="contactAddress" 
                        name="contact_address" 
                        x-model="contact.contact_address" 
                        class="block w-full py-3 px-0 text-sm bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        placeholder="Contact Address" 
                        required>
                </div>

                <!-- City and Zip Code -->
                <div class="mb-5 grid grid-cols-2 gap-4">
                    <div>
                        <label for="contactCity" class="block text-sm font-bold text-gray-700">City</label>
                        <input 
                            type="text" 
                            id="contactCity" 
                            name="contact_city" 
                            x-model="contact.contact_city" 
                            class="block w-full py-3 px-0 text-sm bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            placeholder="City" 
                            required>
                    </div>
                    <div>
                        <label for="contactZip" class="block text-sm font-bold text-gray-700">Zip Code</label>
                        <input 
                            type="text" 
                            id="contactZip" 
                            name="contact_zip" 
                            x-model="contact.contact_zip" 
                            class="block w-full py-3 px-0 text-sm bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            placeholder="Zip Code" 
                            required>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-blue-900 text-white px-5 py-2 rounded-md hover:bg-blue-800 transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
