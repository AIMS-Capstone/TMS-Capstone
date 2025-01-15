<div 
    x-data="{ show: false, contact: {}, isFormValid: false, validateForm() {
        const requiredFields = document.querySelectorAll('#editContactForm [required]');
        this.isFormValid = Array.from(requiredFields).every(field => field.value.trim() !== '');
    }}" 
    x-show="show" 
    x-on:open-edit-contact-modal.window="document.querySelectorAll('.modal').forEach(el => el.style.zIndex = -1); show = true; contact = $event.detail; validateForm()"
    x-on:close-modal.window="show = false" 
    x-effect="document.body.classList.toggle('overflow-hidden', show)" 
    x-init="$watch('show', () => validateForm())"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6" 
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-200 opacity-5"></div>

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
            <form id="editContactForm" method="POST" :action="'/contacts/' + contact.id" 
                  @input="validateForm" 
                  @change="validateForm">
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
                            placeholder="Name should only contain letters"
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 peer"
                            pattern="^[A-Za-z\s]+$"
                            title="Name should only contain letters and spaces."
                            oninput="validateEditName(this)" 
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
                            placeholder="000-123-456-001"
                            maxlength="15" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 peer"
                            oninput="validateEditTIN(this)" 
                            required>
                    </div>

                    <!-- Classification -->
                    <div class="w-[50%] pl-4">
                        <label for="classification" class="block text-sm font-bold text-gray-700">Classification <span class="text-red-500">*</span></label>
                        <select id="classification" name="classification" 
                            x-model="contact.classification" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            required>
                            <option value="Individual">Individual</option>
                            <option value="Non-Individual">Non-Individual</option>
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
                            placeholder="Contact Address" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            required>
                    </div>

                    <!-- City -->
                    <div class="w-2/3 pr-4">
                        <label for="contactCity" class="block text-sm font-bold text-gray-700">City <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="contactCity" 
                            name="contact_city" 
                            x-model="contact.contact_city" 
                            placeholder="City must contain 4-25 letters"
                            minlength="4" 
                            maxlength="25" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 peer"
                            pattern="^[A-Za-z\s]{4,25}$"
                            title="City must contain 4–25 letters and spaces."
                            oninput="validateEditCity(this)" 
                            required>
                    </div>

                    <!-- Zip Code -->
                    <div class="w-2/3 pr-4">
                        <label for="contactZip" class="block text-sm font-bold text-gray-700">Zip Code <span class="text-red-500">*</span></label>
                        <input 
                            type="text" 
                            id="contactZip" 
                            name="contact_zip" 
                            x-model="contact.contact_zip" 
                            placeholder="Zip Code must be number and 4 digits"
                            maxlength="4" 
                            minlength="4" 
                            class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 peer"
                            oninput="validateEditZipCode(this)" 
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
                    <button 
                        type="submit" 
                        :disabled="!isFormValid" 
                        :class="isFormValid ? 'bg-blue-900 hover:bg-blue-950' : 'bg-gray-300 cursor-not-allowed'" 
                        class="px-6 py-1.5 font-semibold text-white rounded-md transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Name validation
    function validateEditName(input) {
        const pattern = /^[A-Za-z\s]*$/;
        if (!pattern.test(input.value)) {
            input.parentElement.classList.add('border-red-500'); // Add red border to the container
            input.parentElement.classList.remove('border-gray-200');
        } else {
            input.parentElement.classList.remove('border-red-500');
            input.parentElement.classList.add('border-gray-200');
        }
    }

    // TIN validation
    function validateEditTIN(input) {
        let value = input.value.replace(/\D/g, ''); // Remove non-digits

        // Format TIN based on value length
        if (value.length <= 3) {
            input.value = value;
        } else if (value.length <= 6) {
            input.value = value.slice(0, 3) + '-' + value.slice(3);
        } else if (value.length <= 9) {
            input.value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6);
        } else if (value.length <= 12) {
            input.value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 9) + '-' + value.slice(9);
        }

        // Classification-based validation
        const classification = document.getElementById('classification').value;
        let isValid = false;

        if (classification === "Individual") {
            // Strictly enforce 3-3-3-3 format for Individual
            isValid = /^\d{3}-\d{3}-\d{3}-\d{3}$/.test(input.value);
        } else if (classification === "Non-Individual") {
            // Allow 3-3-3 or 3-3-3-3 formats for Non-Individual
            isValid = /^\d{3}-\d{3}-\d{3}$/.test(input.value) || /^\d{3}-\d{3}-\d{3}-\d{3}$/.test(input.value);
        }

        // Apply styling based on validity
        if (isValid) {
            input.parentElement.classList.remove('border-red-500');
            input.parentElement.classList.add('border-gray-200');
        } else {
            input.parentElement.classList.add('border-red-500');
            input.parentElement.classList.remove('border-gray-200');
        }
    }

    // City validation
    function validateEditCity(input) {
        const pattern = /^[A-Za-z\s]{4,25}$/; // 4–25 letters and spaces
        if (!pattern.test(input.value)) {
            input.parentElement.classList.add('border-red-500'); // Add red border to the container
            input.parentElement.classList.remove('border-gray-200');
        } else {
            input.parentElement.classList.remove('border-red-500');
            input.parentElement.classList.add('border-gray-200');
        }
    }

    // Zip Code validation
    function validateEditZipCode(input) {
        const value = input.value.replace(/\D/g, ''); // Remove non-numeric characters
        input.value = value; // Update the input value

        if (!/^\d{4}$/.test(value)) {
            input.parentElement.classList.add('border-red-500'); // Add red border to the container
            input.parentElement.classList.remove('border-gray-200');
        } else {
            input.parentElement.classList.remove('border-red-500');
            input.parentElement.classList.add('border-gray-200');
        }
    }


</script>

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
                <img src="{{ asset('images/Success.png') }}" alt="Contact Updated" class="w-28 h-28">
            </div>
            <!-- Success Modal Body -->
            <div class="text-center">
                <p class="text-emerald-500 font-bold text-3xl mb-2">Contact Details Updated</p>
                <p class="text-sm text-zinc-700 mb-6">The details of the contact has ben successfully<br>updated.</p>
            </div>
        </div>
    </div>