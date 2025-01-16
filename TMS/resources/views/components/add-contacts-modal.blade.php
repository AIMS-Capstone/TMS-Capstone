<div 
    x-data="{ 
        show: false, 
        success: false, 
        isFormValid: false,
        validateForm() {
            const requiredFields = document.querySelectorAll('#addContactForm [required]');
            this.isFormValid = Array.from(requiredFields).every(field => field.value.trim() !== '');
        }
    }"
    x-show="show"
    x-on:open-add-contacts-modal.window="show = true"
    x-on:close-modal.window="show = false"
    @submit-success.window="show = false; success = true; setTimeout(() => success = false, 3000)"
    x-effect="document.body.classList.toggle('overflow-hidden', show || success)"
    x-init="$watch('show', () => validateForm())"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-200 opacity-50"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto z-10 overflow-hidden"
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
            <form id="addContactForm" action="{{ route('contacts.store') }}" method="POST" enctype="multipart/form-data"
                  @input="validateForm"
                  @change="validateForm">
                @csrf
                <input type="hidden" name="organization_id" value="{{ Auth::user()->organization_id }}">

                <!-- Form fields in a grid -->
                <div class="mb-6 flex justify-between items-start">
                    <!-- Name -->
                    <div class="mb-6">
                        <label for="bus_name" class="block font-semibold text-sm text-gray-700">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bus_name" id="bus_name" required
                            placeholder="Customer Name"
                            pattern="^[A-Za-z\s]+$"
                            title="Name should only contain letters and spaces."
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            oninput="validateName(this)">
                        <span id="nameError" class="text-red-500 text-xs hidden">Name cannot contain numbers or special characters.</span>
                    </div>

                    <!-- Contact Type -->
                    <div class="w-[50%] pr-4">
                        <label for="contact_type" class="block font-semibold text-sm text-gray-700">
                            Contact Type <span class="text-red-500">*</span>
                        </label>
                        <select name="contact_type" id="contact_type" required
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                            <option value="" disabled selected>Select Contact Type</option>
                            <option value="Customer">Customer</option>
                            <option value="Vendor">Vendor</option>
                        </select>
                    </div>
                </div>

                <div class="mb-6 flex justify-between items-start">
                    <!-- TIN -->
                    <div class="w-2/3">
                        <label for="contact_tin" class="block font-semibold text-sm text-gray-700">
                            Tax Identification Number (TIN) <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="contact_tin" 
                            id="contact_tin" 
                            required 
                            placeholder="Select Classification First" 
                            maxlength="15" 
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 peer" 
                            disabled
                            oninput="validateTIN(this)">
                        <span id="tinError" class="text-red-500 text-xs hidden">
                            TIN format is invalid for the selected classification.
                        </span>
                    </div>
                    <!-- Classification -->
                    <div class="w-[50%] pl-4">
                        <label for="classification" class="block font-semibold text-sm text-gray-700">
                            Classification <span class="text-red-500">*</span>
                        </label>
                        <select name="classification" id="classification" required
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 peer"
                            onchange="updateTINFormat()">
                            <option value="" disabled selected>Select Classification</option>
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
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>

                        <!-- City -->
                        <div class="w-2/3 pr-4">
                            <label for="contact_city" class="block font-semibold text-sm text-gray-700">
                                City <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="contact_city" 
                                id="contact_city" 
                                required
                                placeholder="City"
                                minlength="4" 
                                maxlength="25"
                                pattern="^[A-Za-z\s]+$"
                                title="City name should only contain letters and spaces, and must be between 4 and 25 characters."
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                oninput="validateCity(this)">
                            <span id="cityError" class="text-red-500 text-xs hidden">City name must only contain letters, be between 4 and 25 characters.</span>
                        </div>

                        <!-- Zip Code -->
                        <div class="w-2/3 pr-4">
                            <label for="contact_zip" class="block text-sm font-bold text-gray-700">
                                Zip Code <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="contact_zip" 
                                id="contact_zip" 
                                required
                                placeholder="e.g., 1446" 
                                maxlength="4" 
                                minlength="4" 
                                pattern="^\d{4}$" 
                                title="Zip Code must be exactly 4 digits." 
                                class="block w-full py-2 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                @input="validateZipCode">
                            <span id="editZipError" class="text-red-500 text-xs hidden">Zip Code must be exactly 4 digits.</span>
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
                    <button 
                        type="submit" 
                        id="submitBtn"
                        :disabled="!isFormValid"
                        :class="isFormValid ? 'bg-blue-900 hover:bg-blue-950' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-6 py-1.5 font-semibold text-white rounded-md transition">
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

<script>

    //name validation
    function validateName(input) {
        const pattern = /^[A-Za-z\s]*$/;
        const errorMessage = document.getElementById('nameError');
        if (!pattern.test(input.value)) {
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-200');
            errorMessage.classList.remove('hidden');
        } else {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-200');
            errorMessage.classList.add('hidden');
        }
    }

    //city validation
    function validateCity(input) {
        const pattern = /^[A-Za-z\s]{4,25}$/;
        const errorMessage = document.getElementById('cityError');
        if (!pattern.test(input.value)) {
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-200');
            errorMessage.classList.remove('hidden');
        } else {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-200');
            errorMessage.classList.add('hidden');
        }
    }

    //tin validation
    function updateTINFormat() {
        const classification = document.getElementById('classification').value;
        const tinInput = document.getElementById('contact_tin');
        const tinError = document.getElementById('tinError');

        if (classification === "Individual") {
            tinInput.placeholder = "000-123-456-001 (4,3 digits)";
            tinInput.pattern = "^\\d{3}-\\d{3}-\\d{3}-\\d{3}$"; // 4,3 digits format
            tinInput.disabled = false; // Enable the field
        } else if (classification === "Non-Individual") {
            tinInput.placeholder = "000-123-456 or 000-123-456-001";
            tinInput.pattern = "^(\\d{3}-\\d{3}-\\d{3}|\\d{3}-\\d{3}-\\d{3}-\\d{3})$"; // Either 3,3 or 4,3 digits format
            tinInput.disabled = false; // Enable the field
        } else {
            tinInput.placeholder = "Select Classification First";
            tinInput.pattern = ""; // Clear the pattern
            tinInput.disabled = true; // Disable the field
        }

        // Reset field and error state
        tinInput.value = "";
        tinInput.classList.remove('border-red-500');
        tinInput.classList.add('border-gray-200');
        tinError.classList.add('hidden');
    }

    // Validate TIN Input Dynamically
    function validateTIN(input) {
        const classification = document.getElementById('classification').value;
        const tinError = document.getElementById('tinError');
        let value = input.value.replace(/\D/g, ''); // Remove non-numeric characters

        if (classification === "Individual") {
            // Format TIN for Individual (4,3 digits)
            if (value.length <= 3) {
                input.value = value;
            } else if (value.length <= 6) {
                input.value = value.slice(0, 3) + '-' + value.slice(3);
            } else if (value.length <= 9) {
                input.value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6);
            } else if (value.length <= 12) {
                input.value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 9) + '-' + value.slice(9);
            }
        } else if (classification === "Non-Individual") {
            // Format TIN for Non-Individual (3,3 digits or 4,3 digits)
            if (value.length <= 3) {
                input.value = value;
            } else if (value.length <= 6) {
                input.value = value.slice(0, 3) + '-' + value.slice(3);
            } else if (value.length <= 9) {
                input.value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6);
            } else if (value.length <= 12) {
                input.value = value.slice(0, 3) + '-' + value.slice(3, 6) + '-' + value.slice(6, 9) + '-' + value.slice(9);
            }
        }

        // Validate TIN format
        const isValid = new RegExp(input.pattern).test(input.value);
        if (!isValid) {
            tinError.classList.remove('hidden');
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-200');
        } else {
            tinError.classList.add('hidden');
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-200');
        }
    }

//bakit hindi nagana yung num lang? 
    function validateZipCode(zipValue) {
        const input = document.getElementById('contact_zip');
        const errorMessage = document.getElementById('editZipError');

        const value = input.zipValue.replace(/\D/g, ''); // Remove non-numeric characters
        input.value = zipValue; // Update the input value

        if (/^\d{4}$/.test(value)) {
            errorMessage.classList.add('hidden');
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-200');
        } else {
            errorMessage.classList.remove('hidden');
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-200');
        }
    }

    function handleSubmit() {
        // Simulate form submission success
        setTimeout(() => {
            document.dispatchEvent(new CustomEvent('submit-success'));
        }, 500); // Simulate server response time
    }
    
</script>