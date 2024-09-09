<div 
    x-data="{ show: false, id: '', type: '', code: '', name: '', description: '' }"
    x-show="show"
    x-on:open-edit-modal.window="show = true; id = $event.detail.id; type = $event.detail.type; code = $event.detail.code; name = $event.detail.name; description = $event.detail.description"
    x-on:close-modal.window="show = false"
    class="fixed z-50 inset-0 flex items-center justify-center m-2"
>
    <!-- Modal content -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto z-10">
        <!-- Modal header -->
        <div class="flex justify-center items-center p-3 border-b">
            <h1 class="text-lg font-bold">Edit Account</h1>
        </div>

        <!-- Modal body -->
        <div class="px-3 mx-10 space-y-3">
            <form action="{{ route('coa.edit') }}" method="POST">
                @csrf
                <!-- Hidden input for ID -->
                <input type="hidden" name="coas_id" :value="id">
                
                <!-- Account Type -->
                <div class="mb-3">
                    <label for="type" class="block font-semibold text-sm text-gray-700">Account Type</label>
                    <select name="type" x-model="type" required class="mt-1 block w-full border rounded-md">
                        <option value="" disabled>Select Account Type</option>
                        <option value="assets">Assets</option>
                        <option value="liabilities">Liabilities</option>
                        <option value="equity">Equity</option>
                        <option value="revenues">Revenue</option>
                        <option value="sales">Cost of Sales</option>
                        <option value="expense">Expenses</option>
                        <option value="per-revenue">Percentage of Revenue</option>
                    </select>
                </div>

                <!-- Account Code -->
                <div class="mb-3">
                    <label for="code" class="block font-semibold text-sm text-gray-700">Code</label>
                    <input type="text" name="code" x-model="code" required class="mt-1 block w-full border rounded-md">
                </div>

                <!-- Account Name -->
                <div class="mb-3">
                    <label for="name" class="block font-semibold text-sm text-gray-700">Name</label>
                    <input type="text" name="name" x-model="name" required class="mt-1 block w-full border rounded-md">
                </div>

                <!-- Account Description -->
                <div class="mb-3">
                    <label for="description" class="block font-semibold text-sm text-gray-700">Description</label>
                    <input type="text" name="description" x-model="description" required class="mt-1 block w-full border rounded-md">
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end mb-4">
                    <button 
                        @click="$dispatch('close-modal')"
                        type="button"
                        class="mr-2 font-semibold text-black px-3 py-1 rounded-md hover:bg-gray-300">
                        Cancel
                    </button>
                    <button type="submit" class="font-semibold bg-sky-900 text-white px-3 py-1 rounded-md">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
