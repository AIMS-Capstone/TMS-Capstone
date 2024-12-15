<div 
    x-data="{ show: false, coa: {} }"
    x-show="show"
    x-on:open-edit-modal.window="show = true; coa = $event.detail"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 opacity-5"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden">
        <!-- Modal header -->
        <div class="relative p-3 bg-blue-900 w-full">
            <h1 class="text-lg font-bold text-white text-center">Edit Chart of Accounts</h1>
            <button @click="$dispatch('close-modal')" class="absolute right-3 top-4 text-sm text-white hover:text-gray-200">
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
            <form id="editAccountForm" method="POST" :action="'/coa/' + coa.id">
                @csrf
                @method('PUT')

                <div class="mb-5 flex justify-between items-start">
                    <!-- COA Type -->
                    <div class="w-2/3 pr-4">
                        <label for="coaType" class="block text-sm font-bold text-zinc-700">Account Type</label>
                        <input type="text" id="coaType" name="account_type_input" 
                            x-bind:value="coa.type" 
                            class="block w-full py-3 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            required 
                            placeholder="Expense"
                        >
                    </div>
                    <div class="w-2/3 pr-3">
                        <label for="coaSubType" class="block text-sm font-bold text-zinc-700">Sub Type</label>
                        <input type="text" name="sub_type" id="coaSubType"
                            x-bind:value="coa.sub_type" 
                            class="block w-full py-3 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            required
                            placeholder="Ordinary Allowance Itemized Deductions"
                        >
                    </div>
                    <!-- COA Code -->
                    <div class="w-1/3 text-left">
                        <label for="coaCode" class="block text-sm font-bold text-zinc-700">Code</label>
                        <input type="text" id="coaCode" name="code" x-model="coa.code" class="block w-full py-3 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        required maxlength="10">
                    </div>
                </div>

                <!-- COA Name -->
                <div class="mb-5">
                    <label for="coaName" class="block text-sm font-bold text-gray-700">Name</label>
                    <input type="text" id="coaName" name="name" x-model="coa.name" class="block w-full py-3 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                </div>

                <!-- COA Description -->
                <div class="mb-5">
                    <label for="coaDescription" class="block text-sm font-bold text-gray-700">Description</label>
                    <input type="text" id="coaDescription" name="description" x-model="coa.description" class="block w-full py-3 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>

                <!-- Submit -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-900 text-white font-bold px-4 py-2 rounded-md hover:bg-blue-950">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- <!-- Success Modal -->
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
                <img src="{{ asset('images/Success.png') }}" alt="Chart of Accounts Added" class="w-28 h-28">
            </div>
            <!-- Success Modal Body -->
            <div class="text-center">
                <p class="text-emerald-500 font-bold text-3xl mb-2">New Account Added</p>
                <p class="text-sm text-zinc-700 mb-6">New Account has been successfully<br>added in COA.</p>
            </div>
        </div>
    </div> --}}