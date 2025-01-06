<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <button onclick="history.back()" class="flex items-center text-gray-600 hover:text-gray-800 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M16 12H8m4-4l-4 4l4 4"/>
                    </g>
                </svg>
                <span class="text-sm font-medium">Go Back</span>
            </button>
            <div class="px-6 py-4 bg-white shadow-sm sm:rounded-lg">
                <h1 class="text-sm font-semibold text-sky-900">BIR Form No. 0619-E</h1>
                <h2 class="text-xl font-semibold text-sky-900 mt-1">Monthly Remittance Form 
                    (of Creditable Income Taxes Withheld (Expanded))
                </h2>
                <p class="text-sm text-sky-900 mt-3">
                    Verify the tax information below, with some fields pre-filled from your organization's setup.
                    Select options as needed, then click 'Proceed to Report' to generate the BIR form. Hover over
                    icons for additional guidance on specific fields.
                </p>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <form action="{{ route('form0619E.store', ['id' => $withHolding->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="withholding_id" value="{{ $withHolding->id }}">

                    <!-- Filing Period -->
                    <h2 class="text-lg font-semibold text-gray-800 mt-6">Filing Period</h2>

                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="for_month" class="block text-sm font-medium text-gray-700">For the Month (MM/YYYY)</label>
                        <input 
                            type="month" 
                            id="for_month" 
                            name="for_month" 
                            class="mt-1 p-2 block w-full border border-gray-300 rounded-md" 
                            value="{{ isset($withHolding->month, $withHolding->year) ? $withHolding->year . '-' . str_pad($withHolding->month, 2, '0', STR_PAD_LEFT) : '' }}" 
                            readonly
                        >
                    </div>

                    <!-- Due Date -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                        <input type="date" id="due_date" name="due_date" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
                    </div>

                    <!-- Amended Return -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label class="block text-sm font-medium text-gray-700">Amended Form?</label>
                        <div class="mt-2 space-x-4">
                            <label>
                                <input type="radio" name="amended_return" value="1" required>
                                Yes
                            </label>
                            <label>
                                <input type="radio" name="amended_return" value="0" required>
                                No
                            </label>
                        </div>
                    </div>

                    <!-- Any Taxes Withheld -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label class="block text-sm font-medium text-gray-700">Any Taxes Withheld?</label>
                        <div class="mt-2 space-x-4">
                            <label>
                                <input type="radio" name="any_taxes_withheld" value="1" required>
                                Yes
                            </label>
                            <label>
                                <input type="radio" name="any_taxes_withheld" value="0" required>
                                No
                            </label>
                        </div>
                    </div>

                    <!-- Tax Type Code -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="tax_code" class="block text-sm font-medium text-gray-700">Tax Type Code</label>
                        <select id="tax_code" name="tax_code" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                            <option value="" disabled selected>Select Tax Type</option>
                            @foreach ($taxTypeCodes as $code => $description)
                                <option value="{{ $code }}">{{ $code }} - {{ $description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Alphanumeric Tax Code -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label for="atc_id" class="block text-sm font-medium text-gray-700">Alphanumeric Tax Code (ATC)</label>
                        <select id="atc_id" name="atc_id" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                            <option value="" disabled selected>Select Tax Code</option>
                            @foreach ($atcs as $atc)
                                <option value="{{ $atc->id }}" {{ old('atc_id', $form->atc_id ?? '') == $atc->id ? 'selected' : '' }}>
                                    {{ $atc->tax_code }} - {{ $atc->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Background Information -->
                    <h2 class="text-lg font-semibold text-gray-800 mt-8">Background Information</h2>
                        <!-- TIN -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="tin" class="block text-sm font-medium text-gray-700">Taxpayer Identification Number (TIN)</label>
                            <input type="text" id="tin" name="tin" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->tin }}">
                        </div>

                        <!-- RDO Code -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="rdo" class="block text-sm font-medium text-gray-700">Revenue District Office (RDO) Code</label>
                            <input type="text" id="rdo" name="rdo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->rdo }}">
                        </div>

                        <!-- Withholding Agent's Name -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="agent_name" class="block text-sm font-medium text-gray-700">Withholding Agent's Name</label>
                            <input type="text" id="agent_name" name="agent_name" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->registration_name }}">
                        </div>

                        <!-- Registered Address -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="address" class="block text-sm font-medium text-gray-700">Registered Address</label>
                            <input type="text" id="address" name="address" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->address_line }}">
                        </div>

                        <!-- Zip Code -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="zip_code" class="block text-sm font-medium text-gray-700">Zip Code</label>
                            <input type="text" id="zip_code" name="zip_code" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->zip_code }}">
                        </div>

                        <!-- Contact Number -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->contact_number }}">
                        </div>

                        <!-- Any Taxes Withheld -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label class="block text-sm font-medium text-gray-700">Category of Withholding Agent</label>
                        <div class="mt-2 space-x-4">
                            <label>
                                <input type="radio" name="category" value="1" required>
                                Private
                            </label>
                            <label>
                                <input type="radio" name="category" value="0" required>
                                Government
                            </label>
                        </div>
                    </div>

                        <!-- Email -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" id="email" name="email" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->email }}">
                        </div>

                    <!-- Tax Remittance -->
                    <h2 class="text-lg font-semibold text-gray-800 mt-8">Tax Remittance</h2>
                        <!-- Amount of Remittance -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="amount_of_remittance" class="block text-sm font-medium text-gray-700">Amount of Remittance</label>
                            <input type="number" id="amount_of_remittance" name="amount_of_remittance" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" required>
                        </div>

                        <!-- Less: Amount Remittance Previously Filed -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="remitted_previous" class="block text-sm font-medium text-gray-700">Less: Amount Remittance Previously Filed</label>
                            <input type="number" id="remitted_previous" name="remitted_previous" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                        </div>

                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="net_amount_of_remittance" class="block text-sm font-medium text-gray-700">Net Amount of Remittance</label>
                            <input type="text" id="net_amount_of_remittance" name="net_amount_of_remittance" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly>
                        </div>

                        <!-- Add: Surcharge -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="surcharge" class="block text-sm font-medium text-gray-700">Surcharge</label>
                            <input type="number" id="surcharge" name="surcharge" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                        </div>

                        <!-- Add: Interest -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="interest" class="block text-sm font-medium text-gray-700">Interest</label>
                            <input type="number" id="interest" name="interest" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                        </div>

                        <!-- Add: Compromise -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="compromise" class="block text-sm font-medium text-gray-700">Compromise</label>
                            <input type="number" id="compromise" name="compromise" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                        </div>

                        <!-- Total Penalties -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="total_penalties" class="block text-sm font-medium text-gray-700">Total Penalties</label>
                            <input type="text" id="total_penalties" name="total_penalties" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly>
                        </div>

                        <!-- Total Amount Due -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="total_remittance" class="block text-sm font-medium text-gray-700">Total Amount Due</label>
                            <input type="text" id="total_remittance" name="total_remittance" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly>
                        </div>

                    <div class="mt-8 flex justify-center items-center">
                        <button type="submit" class="flex justify-center px-4 py-2 bg-sky-900 text-white rounded-md hover:bg-sky-600">
                            Proceed to Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const calculateTotals = () => {
                // Get field values
                const remittance = parseFloat(document.getElementById("amount_of_remittance").value) || 0;
                const remittedPrevious = parseFloat(document.getElementById("remitted_previous").value) || 0;
                const surcharge = parseFloat(document.getElementById("surcharge").value) || 0;
                const interest = parseFloat(document.getElementById("interest").value) || 0;
                const compromise = parseFloat(document.getElementById("compromise").value) || 0;

                // Calculate penalties and total amount due
                const totalPenalties = surcharge + interest + compromise;
                const totalRemittance = remittance - remittedPrevious + totalPenalties;
                const netAmountOfRemittance = remittance - remittedPrevious;

                document.getElementById("net_amount_of_remittance").value = netAmountOfRemittance.toFixed(2);

                // Update the fields
                document.getElementById("total_penalties").value = totalPenalties.toFixed(2);
                document.getElementById("total_remittance").value = totalRemittance.toFixed(2);
            };

            // Attach event listeners to relevant input fields
            document.querySelectorAll("#amount_of_remittance, #remitted_previous, #surcharge, #interest, #compromise").forEach(input => {
                input.addEventListener("input", calculateTotals);
            });
        });
    </script>

</x-app-layout>
