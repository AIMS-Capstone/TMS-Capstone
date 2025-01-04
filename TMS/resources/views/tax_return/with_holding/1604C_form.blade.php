<x-app-layout>
    <!-- Back Button -->
    <div class="relative ml-20 mt-10 flex items-center">
        <button onclick="history.back()" class="text-zinc-600 hover:text-zinc-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g>
            </svg>
            <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go Back</span>
        </button>
    </div>

    <div class="py-6">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto pt-4">
                    <div class="flex justify-between items-center px-10">
                        <div class="flex flex-col w-full items-start space-y-1">
                            <!-- BIR Form text on top -->
                            <p class="text-sm taxuri-color">BIR Form No. 1604-C</p>
                            <p class="font-bold text-3xl taxuri-color">Annual Information Return <span class="text-lg">(of Income Taxes Withheld on Compensation)</span></p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-10 mb-4">
                        <div class="flex items-center">
                            <p class="taxuri-text font-normal text-sm">
                                Verify the tax information below, with some fields pre-filled from your organization's setup. Select options as needed, then click 'Proceed to Report' to generate the BIR form. Hover over
                                icons for additional guidance on specific fields.
                            </p>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
    
    <div class="max-w-6xl mx-auto bg-white shadow-sm rounded-lg">
        <div class="overflow-hidden shadow-sm sm:rounded-lg mt-6 p-4">
            <form action="{{ route('form1604C.store', ['id' => $withHolding->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="withholding_id" value="{{ $withHolding->id }}">

                <div class="p-8">
                    <!-- Filing Period -->
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>

                    <!-- Year -->
                    <div class="mb-2 flex flex-row justify-between gap-96">
                        <label for="year" class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">1</span>For the Year</label>
                        <input type="text" id="year" name="year" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        value="{{ $withHolding->year }}" readonly>
                    </div>

                    <!-- Amended Return -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">2</span>Amended Return?</label>
                        <div class="flex items-center space-x-4 w-2/3 py-2">
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="amended_return" value="1" class="mr-2" {{ old('amended_return', optional($form1604C)->amended_return) == 1 ? 'checked' : '' }} required>
                                Yes
                            </label>
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="amended_return" value="0" class="mr-2" {{ old('amended_return', optional($form1604C)->amended_return) == 0 ? 'checked' : '' }}>
                                No
                            </label>
                        </div>
                    </div>

                    <!-- Number of Sheets Attached -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="number_of_sheets" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">3</span>Number of Sheets Attached</label>
                        <input type="number" id="number_of_sheets" name="number_of_sheets" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                         value="{{ old('number_of_sheets', $form1604C->number_of_sheets ?? 0) }}" min="0" required>
                    </div>

                    <!-- Background Information -->
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
                    <!-- TIN -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="tin" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">4</span>Taxpayer Identification Number (TIN)</label>
                        <input type="text" id="tin" name="tin" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->tin }}">
                    </div>

                    <!-- RDO Code -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="rdo" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">5</span>Revenue District Office (RDO) Code</label>
                        <input type="text" id="rdo" name="rdo" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->rdo }}">
                    </div>

                    <!-- Withholding Agent's Name -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="agent_name" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">6</span>Withholding Agent's Name</label>
                        <input type="text" id="agent_name" name="agent_name" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->registration_name }}">
                    </div>

                    <!-- Registered Address -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="address" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">7</span>Registered Address</label>
                        <input type="text" id="address" name="address" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->address_line }}">
                    </div>

                    <!-- Zip Code -->
                    <div class="mb-6 pl-8 flex flex-row justify-between gap-96">
                        <label for="zip_code" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">7A</span>Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->zip_code }}">
                    </div>

                    <!-- Category of Withholding Agent -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="agent_category" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">8</span>Category of Withholding Agent</label>
                        <select id="agent_category" name="agent_category" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                            <option value="Private" {{ old('agent_category', optional($form1604C)->agent_category) == 'Private' ? 'selected' : '' }}>Private</option>
                            <option value="Government" {{ old('agent_category', optional($form1604C)->agent_category) == 'Government' ? 'selected' : '' }}>Government</option>
                        </select>
                    </div>
                    <!-- Private or not withholding Agent -->
                    <div class="mb-6 pl-8 flex flex-row justify-between gap-96">
                        <label for="agent_top" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">8A</span>if private, top withholding agent?</label>
                        <select id="agent_top" name="agent_top" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                            <option value="Yes" {{ old('agent_top', optional($form1604C)->agent_top) == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ old('agent_top', optional($form1604C)->agent_top) == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Contact Number -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="contact_number"class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">9</span>Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->contact_number }}">
                    </div>

                    <!-- Email -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="email" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">10</span>Email Address</label>
                        <input type="email" id="email" name="email" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->email }}">
                    </div>

                    <!-- Over Remittances -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="over_remittances" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">11</span> In case of overwithholding/overremittance after the year-end adjustments on compensation, have you released the refund/s to your employee/s?</label>
                        <select id="over_remittances" name="over_remittances" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                            <option value="1" {{ old('over_remittances', optional($form1604C)->over_remittances) == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('over_remittances', optional($form1604C)->over_remittances) == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Refund Date -->
                    <div class="mb-6 pl-8 flex flex-row justify-between gap-96">
                        <label for="refund_date" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">11A</span>If yes, specify the date of refund</label>
                        <input type="date" id="refund_date" name="refund_date" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        value="{{ old('refund_date', $form1604C->refund_date ?? '') }}" {{ old('over_remittances') == 'yes' ? 'required' : '' }}>
                    </div>

                    <!-- Total Over Remittances -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="total_over_remittances" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">12</span>Total Amount of Overremittance</label>
                        <input type="text" id="total_over_remittances" name="total_over_remittances" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        value="{{ old('total_over_remittances', $form1604C->total_over_remittances ?? '') }}" required>
                    </div>

                    <!-- First Month Remittances -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="first_month_remittances" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">13</span>First Month of Overremittance</label>
                        <input type="month" id="first_month_remittances" name="first_month_remittances" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                        value="{{ old('first_month_remittances', $form1604C->first_month_remittances ?? '') }}" required>
                    </div>

                    <!-- Submission -->
                    <div class="mt-8 flex justify-center items-center">
                        <button type="submit" class="w-56 bg-blue-900 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-950">
                            Proceed to Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
