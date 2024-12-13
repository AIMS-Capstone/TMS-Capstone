<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-lg font-semibold text-gray-700">BIR Form No. 1604C</h1>
                </div>

                <form action="{{ route('form1604C.store', ['id' => $withHolding->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="withholding_id" value="{{ $withHolding->id }}">

                    <!-- Filing Period -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="filing_period" class="block text-sm font-medium text-gray-700">Filing Period</label>
                    </div>

                    <!-- Year -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="year" class="block text-sm font-medium text-gray-700">1. For the Year</label>
                        <input 
                            type="text" 
                            id="year" 
                            name="year" 
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" 
                            value="{{ $withHolding->year }}" 
                            readonly
                        >
                    </div>

                    <!-- Amended Return -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label class="block text-sm font-medium text-gray-700">Amended Return?</label>
                        <div class="mt-2 space-x-4">
                            <label>
                                <input type="radio" name="amended_return" value="1" {{ old('amended_return', optional($form1604C)->amended_return) == 1 ? 'checked' : '' }} required>
                                Yes
                            </label>
                            <label>
                                <input type="radio" name="amended_return" value="0" {{ old('amended_return', optional($form1604C)->amended_return) == 0 ? 'checked' : '' }}>
                                No
                            </label>
                        </div>
                    </div>

                    <!-- Number of Sheets Attached -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="number_of_sheets" class="block text-sm font-medium text-gray-700">Number of Sheets Attached</label>
                        <input 
                            type="number" 
                            id="number_of_sheets" 
                            name="number_of_sheets" 
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md" 
                            value="{{ old('number_of_sheets', $form1604C->number_of_sheets ?? 0) }}" 
                            min="0"
                            required
                        >
                    </div>

                    <!-- Background Information -->
                    <h2 class="text-lg font-semibold text-gray-800 mt-8">Background Information</h2>
                        <!-- TIN -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="tin" class="block text-sm font-medium text-gray-700">4. Taxpayer Identification Number (TIN)</label>
                            <input type="text" id="tin" name="tin" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->tin }}">
                        </div>

                        <!-- RDO Code -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="rdo" class="block text-sm font-medium text-gray-700">5. Revenue District Office (RDO) Code</label>
                            <input type="text" id="rdo" name="rdo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->rdo }}">
                        </div>

                        <!-- Withholding Agent's Name -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="agent_name" class="block text-sm font-medium text-gray-700">6. Withholding Agent's Name</label>
                            <input type="text" id="agent_name" name="agent_name" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->registration_name }}">
                        </div>

                        <!-- Registered Address -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="address" class="block text-sm font-medium text-gray-700">7. Registered Address</label>
                            <input type="text" id="address" name="address" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->address_line }}">
                        </div>

                        <!-- Zip Code -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="zip_code" class="block text-sm font-medium text-gray-700">7A. Zip Code</label>
                            <input type="text" id="zip_code" name="zip_code" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->zip_code }}">
                        </div>

                        <!-- Category of Withholding Agent -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="agent_category" class="block text-sm font-medium text-gray-700">8. Category of Withholding Agent</label>
                            <select id="agent_category" name="agent_category" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                                <option value="Private" {{ old('agent_category', optional($form1604C)->agent_category) == 'Private' ? 'selected' : '' }}>Private</option>
                                <option value="Government" {{ old('agent_category', optional($form1604C)->agent_category) == 'Government' ? 'selected' : '' }}>Government</option>
                            </select>
                        </div>

                        <!-- Private or not withholding Agent -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="agent_top" class="block text-sm font-medium text-gray-700">8A if private, top withholding agent?</label>
                            <select id="agent_top" name="agent_top" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                                <option value="Yes" {{ old('agent_top', optional($form1604C)->agent_top) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                <option value="No" {{ old('agent_top', optional($form1604C)->agent_top) == 'No' ? 'selected' : '' }}>No</option>
                            </select>
                        </div>


                        <!-- Contact Number -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->contact_number }}">
                        </div>

                        <!-- Email -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" id="email" name="email" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->email }}">
                        </div>

                    <!-- Over Remittances -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="over_remittances" class="block text-sm font-medium text-gray-700">Over Remittance?</label>
                        <select id="over_remittances" name="over_remittances" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                            <option value="1" {{ old('over_remittances', optional($form1604C)->over_remittances) == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="0" {{ old('over_remittances', optional($form1604C)->over_remittances) == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <!-- Refund Date -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="refund_date" class="block text-sm font-medium text-gray-700">Refund Date</label>
                        <input type="date" id="refund_date" name="refund_date" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="{{ old('refund_date', $form1604C->refund_date ?? '') }}" {{ old('over_remittances') == 'yes' ? 'required' : '' }}>
                    </div>

                    <!-- Total Over Remittances -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="total_over_remittances" class="block text-sm font-medium text-gray-700">Total Amount of Overremittance</label>
                        <input type="text" id="total_over_remittances" name="total_over_remittances" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" value="{{ old('total_over_remittances', $form1604C->total_over_remittances ?? '') }}" required>
                    </div>

                    <!-- First Month Remittances -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="first_month_remittances" class="block text-sm font-medium text-gray-700">First Month of Overremittance</label>
                        <input type="month" id="first_month_remittances" name="first_month_remittances" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" value="{{ old('first_month_remittances', $form1604C->first_month_remittances ?? '') }}" required>
                    </div>

                    <!-- Submission -->
                    <div class="mt-8 flex justify-center items-center">
                        <button type="submit" class="flex justify-center px-4 py-2 bg-sky-900 text-white rounded-md hover:bg-sky-600">
                            Proceed to Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
