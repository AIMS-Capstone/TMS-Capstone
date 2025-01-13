<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <button onclick="history.back()" class="flex items-center mb-4 text-gray-600 hover:text-gray-800 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M16 12H8m4-4l-4 4l4 4"/>
                    </g>
                </svg>
                <span class="text-sm font-medium">Go Back</span>
            </button>

            <div class="px-6 py-4 bg-white shadow-sm sm:rounded-lg">
                <div class="container px-4">
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col w-full items-start space-y-1">
                            <!-- BIR Form text on top -->
                            <p class="text-sm taxuri-color">BIR Form No. 1604-E</p>
                            <p class="font-bold text-3xl taxuri-color">Annual Information Return <span class="text-sm">(of Creditable Income Taxes Withheld (Expanded/Income Payments Exempt from Withholding Tax))</span></p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center">
                            <p class="taxuri-text font-normal text-sm">
                                Verify the tax information below, with some fields pre-filled from your organization's setup. Select options as needed, then click 'Proceed to Report' to generate the BIR form. Hover over
                                icons for additional guidance on specific fields.
                            </p>
                        </div>
                    </div>  
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6 p-4">
                <form action="{{ route('form1604E.store', ['id' => $withHolding->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="withholding_id" value="{{ $withHolding->id }}">
                    <div class="px-8 py-10">
                        <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>
                        <!-- Filing Period -->

                        <!-- Year -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label for="year" class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">1</span>For the Year</label>
                            <input type="text" id="year" name="year" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ $withHolding->year }}" readonly>
                        </div>

                        <!-- Amended Return -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">2</span>Amended Return?</label>
                            <div class="flex items-center space-x-4 w-full py-2">
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="amended_return" class="mr-2" value="1" {{ old('amended_return', optional($form1604E)->amended_return) == 1 ? 'checked' : '' }} required>
                                    Yes
                                </label>
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="amended_return" class="mr-2" value="0" {{ old('amended_return', optional($form1604E)->amended_return) == 0 ? 'checked' : '' }}>
                                    No
                                </label>
                            </div>
                        </div>

                        <!-- Number of Sheets Attached -->
                        <div class="mb-2 flex flex-row justify-between gap-6">
                            <label for="number_of_sheets" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">3</span>Number of Sheets Attached</label>
                            <input type="number" id="number_of_sheets" name="number_of_sheets" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ old('number_of_sheets', $form1604E->number_of_sheets ?? 0) }}" min="0" required>
                        </div>

                        <!-- Background Information -->
                        <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
                        
                        <!-- TIN -->
                        <div class="mb-2 flex flex-row justify-between gap-6">
                            <label for="tin" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">4</span>Taxpayer Identification Number (TIN)</label>
                            <input type="text" id="tin" name="tin" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            readonly value="{{ $orgSetup->tin }}">
                        </div>

                        <!-- RDO Code -->
                        <div class="mb-2 flex flex-row justify-between gap-6">
                            <label for="rdo" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">5</span>Revenue District Office (RDO) Code</label>
                            <input type="text" id="rdo" name="rdo" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            readonly value="{{ $orgSetup->rdo }}">
                        </div>

                        <!-- Withholding Agent's Name -->
                        <div class="mb-2 flex flex-row justify-between gap-6">
                            <label for="agent_name" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">6</span>Withholding Agent's Name</label>
                            <input type="text" id="agent_name" name="agent_name" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            readonly value="{{ $orgSetup->registration_name }}">
                        </div>

                        <!-- Registered Address -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label for="address" class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">7</span>Registered Address</label>
                            <input type="text" id="address" name="address" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            readonly value="{{ $orgSetup->address_line }}">
                        </div>

                        <!-- Zip Code -->
                        <div class="mb-2 flex flex-row justify-between">
                            <label for="zip_code" class="indent-4 px-8 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">7A</span>Zip Code</label>
                            <input type="text" id="zip_code" name="zip_code" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            readonly value="{{ $orgSetup->zip_code }}">
                        </div>

                        <!-- Category of Withholding Agent -->
                        <div class="mb-2 flex flex-row justify-between gap-6">
                            <label for="agent_category" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">8</span>Category of Withholding Agent</label>
                            <select id="agent_category" name="agent_category" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                <option value="Private" {{ old('agent_category', optional($form1604E)->agent_category) == 'Private' ? 'selected' : '' }}>Private</option>
                                <option value="Government" {{ old('agent_category', optional($form1604E)->agent_category) == 'Government' ? 'selected' : '' }}>Government</option>
                            </select>
                        </div>

                        <!-- Private or not withholding Agent -->
                        <div class="mb-2 flex flex-row justify-between">
                            <label for="agent_top" class="indent-4 block px-8 text-zinc-700 text-sm w-full"><span class="font-bold mr-2">8A</span>if private, top withholding agent?</label>
                            <select id="agent_top" name="agent_top" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                <option value="Yes" {{ old('agent_top', optional($form1604E)->agent_top) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('agent_top', optional($form1604E)->agent_top) == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>

                        <!-- Contact Number -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label for="contact_number" class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">9</span>Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            readonly value="{{ $orgSetup->contact_number }}">
                        </div>

                        <!-- Email -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label for="email" class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">10</span>Email Address</label>
                            <input type="email" id="email" name="email" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            readonly value="{{ $orgSetup->email }}">
                        </div>

                        <div class="mt-8 flex justify-center items-center">
                            <button type="submit" class="w-56 bg-blue-900 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-950">
                                Proceed to Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('agent_category').addEventListener('change', function () {
            const agentTopField = document.getElementById('agent_top');
            if (this.value === 'Private') {
                agentTopField.disabled = false;
            } else {
                agentTopField.disabled = true;
                agentTopField.value = ''; // Reset the field
            }
        });
    </script>

</x-app-layout>
