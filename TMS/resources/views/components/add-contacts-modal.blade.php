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
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto z-10 overflow-hidden"
         x-show="show" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="opacity-0 scale-90" 
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200 transform" 
         x-transition:leave-start="opacity-100 scale-100" 
         x-transition:leave-end="opacity-0 scale-90">
        <!-- Modal header -->
        <div class="bg-blue-900 text-center rounded-t-lg p-4">
            <h1 class="text-lg font-bold text-white">Add New Contact</h1>
        </div>

        <!-- Modal body -->
        <div class="p-6">
            <form id="addContactForm" action="{{ route('contacts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="organization_id" value="{{ Auth::user()->organization_id }}">

                <!-- Form fields in a grid -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Contact Type -->
                    <div>
                        <label for="contact_type" class="block font-medium text-sm text-gray-700">
                            Contact Type <span class="text-red-500">*</span>
                        </label>
                        <select name="contact_type" id="contact_type" required
                            class="block w-full mt-1 py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="Individual">Individual</option>
                            <option value="Non-Individual">Non-Individual</option>
                        </select>
                    </div>

                    <!-- TIN -->
                    <div>
                        <label for="contact_tin" class="block font-medium text-sm text-gray-700">
                            Tax Identification Number (TIN) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="contact_tin" id="contact_tin" required
                            placeholder="000-000-000-000"
                            class="block w-full mt-1 py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <!-- Name -->
                    <div>
                        <label for="bus_name" class="block font-medium text-sm text-gray-700">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="bus_name" id="bus_name" required
                            placeholder="Customer Name"
                            class="block w-full mt-1 py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>
                    
                </div>

                <div class="flex flex-row justify-between">

                    <!-- Address -->
                        <div>
                            <label for="contact_address" class="block font-medium text-sm text-gray-700">
                                Address <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="contact_address" id="contact_address" required
                                placeholder="Address"
                                class="block w-full mt-1 py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <!-- City -->
                        <div>
                            <label for="contact_city" class="block font-medium text-sm text-gray-700">
                                City <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="contact_city" id="contact_city" required
                                placeholder="City"
                                class="block w-full mt-1 py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>

                        <!-- Zip Code -->
                        <div>
                            <label for="contact_zip" class="block font-medium text-sm text-gray-700">
                                Zip Code <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="contact_zip" id="contact_zip" required
                                placeholder="e.g., 1446"
                                class="block w-full mt-1 py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                </div>

                <!-- Modal Footer -->
                <div class="mt-6 flex justify-end space-x-3">
                    <button 
                        type="button" 
                        x-on:click="$dispatch('close-modal')"
                        class="px-4 py-2 text-sm font-semibold text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-md transition"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="px-5 py-2 text-sm font-semibold bg-blue-900 text-white rounded-md hover:bg-blue-950 transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
