<div 
    x-data="{ show: false, step: 1 }"
    x-show="show"
    x-on:open-import-modal.window="show = true"
    x-on:close-modal.window="show = false"
    class="fixed z-50 inset-0 flex items-center justify-center m-2"
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-300 opacity-60"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md max-h-screen mx-auto h-auto z-10">
        <!-- Modal header -->
        <div class="flex justify-center items-center p-3 border-b border-opacity-80 w-2/3 mx-auto">
            <h1 class="text-lg font-bold">Chart of Accounts</h1>
        </div>

        <!-- Progress bar -->
        <div class="flex justify-around py-3">
            <div :class="{ 'text-sky-900': step >= 1 }" class="flex flex-col items-center rounded-full">
                <span class="font-semibold">1</span>
                <span>Upload CSV</span>
            </div>
            <div :class="{ 'text-sky-900': step >= 2 }" class="flex flex-col items-center">
                <span class="font-semibold">2</span>
                <span>Specify Fields</span>
            </div>
            <div :class="{ 'text-sky-900': step >= 3 }" class="flex flex-col items-center">
                <span class="font-semibold">3</span>
                <span>Review Import</span>
            </div>
            <div :class="{ 'text-sky-900': step >= 4 }" class="flex flex-col items-center">
                <span class="font-semibold">4</span>
                <span>Complete Import</span>
            </div>
        </div>

        <!-- Modal body with step-based form -->
        <div class="p-6 m-auto h-[300px]"> <!-- Set a fixed height for all steps -->
            <!-- Step 1: Upload CSV -->
            <template x-if="step === 1">
                <div 
                x-transition:enter="transition ease-out duration-300" 
                x-transition:leave="transition ease-in duration-200" 
                class="flex flex-col justify-center items-center h-full"
                >
                    <!-- Header of container -->
                    <div class="items-center flex flex-col text-center">
                        <h1>Importing</h1>
                        <h1 class="text-lg font-bold">Charts of Accounts from CSV Files</h1>
                        <p class="text-sm">By default, all CSV files can be imported and mapped to the required fields later, but you can also use our CSV Template.</p>
                    </div>
                    <h2 class="font-semibold text-lg mt-3 mb-2">Upload CSV File</h2>
                    <p class="text-gray-600">Please upload a CSV file containing chart of accounts information.</p>
                    <input type="file" id="csvFile" class="mt-4">
                </div>
            </template>

            <!-- Step 2: Specify Fields -->
            <template x-if="step === 2">
                <div 
                x-transition:enter="transition ease-out duration-300" 
                x-transition:leave="transition ease-in duration-200" 
                class="flex flex-col justify-center h-full space-y-3"
                >
                    <h2 class="font-semibold text-lg">Map CSV Fields</h2>
                    <div>
                        <label for="accountType">Account Type</label>
                        <select id="accountType" class="block w-full p-2 border rounded-md">
                            <option value="">Select...</option>
                        </select>
                    </div>
                    <div>
                        <label for="subCategory">Sub Category</label>
                        <select id="subCategory" class="block w-full p-2 border rounded-md">
                            <option value="">Select...</option>
                        </select>
                    </div>
                    <div>
                        <label for="code">Code</label>
                        <input type="text" id="code" class="block w-full p-2 border rounded-md">
                    </div>
                    <div>
                        <label for="name">Name</label>
                        <input type="text" id="name" class="block w-full p-2 border rounded-md">
                    </div>
                    <div>
                        <label for="description">Description</label>
                        <input type="text" id="description" class="block w-full p-2 border rounded-md">
                    </div>
                </div>
            </template>

            <!-- Step 3: Review Import -->
            <template x-if="step === 3">
                <div 
                x-transition:enter="transition ease-out duration-300" 
                x-transition:leave="transition ease-in duration-200"
                class="flex flex-col justify-center h-full"
                >
                    <h2 class="font-semibold text-lg mb-3">Review Import</h2>
                    <p>Review the imported data before finalizing the import.</p>
                    <!-- Add preview table or content for reviewing the CSV data -->
                </div>
            </template>

            <!-- Step 4: Complete Import -->
            <template x-if="step === 4">
                <div 
                x-transition:enter="transition ease-out duration-300" 
                x-transition:leave="transition ease-in duration-200" 
                class="flex flex-col justify-center h-full"
                >
                    <h2 class="font-semibold text-lg mb-3">Import Complete</h2>
                    <p>The chart of accounts data has been successfully imported.</p>
                </div>
            </template>
        </div>

        <!-- Modal Footer with Navigation buttons -->
        <div class="flex justify-between p-6">
            <button 
                x-show="step > 1"
                x-on:click="step--"
                class="px-4 py-2 bg-gray-500 text-white rounded-md"
            >
                Previous
            </button>

            <button 
                x-show="step < 4"
                class="px-4 py-2 bg-gray-500 text-white rounded-md"
                x-on:click="$dispatch('close-modal')" 
            >
                Cancel
            </button>

            <button 
                x-show="step < 4"
                x-on:click="step++"
                class="px-4 py-2 bg-sky-900 text-white rounded-md"
            >
                Next
            </button>

            <button 
                x-show="step === 4"
                class="px-4 py-2 bg-sky-900 text-white rounded-md"
                x-on:click="$dispatch('close-modal')"
            >
                Done
            </button>
        </div>
    </div>
</div>
