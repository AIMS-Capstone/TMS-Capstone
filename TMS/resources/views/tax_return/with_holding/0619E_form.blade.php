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
                            <p class="text-sm taxuri-color">BIR Form No. 0619-E</p>
                            <p class="font-bold text-3xl taxuri-color">Monthly Remittance Return <span class="text-lg">(of Creditable Income Taxes Withheld (Expanded))</span></p>
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
                <form action="{{ route('form0619E.store', ['id' => $withHolding->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="withholding_id" value="{{ $withHolding->id }}">

                    <div class="p-8">
                        <!-- Filing Period -->
                        <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>

                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label for="for_month" class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">1</span>For the Month</label>
                            <input type="month" id="for_month" name="for_month" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ isset($withHolding->month, $withHolding->year) ? $withHolding->year . '-' . str_pad($withHolding->month, 2, '0', STR_PAD_LEFT) : '' }}" readonly>
                        </div>

                        <!-- Due Date -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label for="due_date" class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">2</span>Due Date</label>
                            <input type="date" id="due_date" name="due_date" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                        </div>

                        <!-- Amended Return -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">3</span>Amended Form?</label>
                            <div class="flex items-center space-x-4 w-full py-2">
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="amended_return" value="1" class="mr-2" required>
                                    Yes
                                </label>
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="amended_return" value="0" class="mr-2" required>
                                    No
                                </label>
                            </div>
                        </div>

                        <!-- Any Taxes Withheld -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">4</span>Any Taxes Withheld?</label>
                            <div class="flex items-center space-x-4 w-full py-2">
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="any_taxes_withheld" value="1" class="mr-2" required>
                                    Yes
                                </label>
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="any_taxes_withheld" value="0" class="mr-2" required>
                                    No
                                </label>
                            </div>
                        </div>

                        <!-- Tax Type Code -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label for="tax_code" class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">5</span>Tax Type Code</label>
                            <select id="tax_code" name="tax_code" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                <option value="" disabled selected>Select Tax Type</option>
                                @foreach ($taxTypeCodes as $code => $description)
                                    <option value="{{ $code }}">{{ $code }} - {{ $description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Alphanumeric Tax Code -->
                        <div class="mb-2 flex flex-row justify-between gap-3">
                            <label for="atc_id" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">6</span>Alphanumeric Tax Code (ATC)</label>
                            <select id="atc_id" name="atc_id" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                <option value="" disabled selected>Select Tax Code</option>
                                @foreach ($atcs as $atc)
                                    <option value="{{ $atc->id }}" {{ old('atc_id', $form->atc_id ?? '') == $atc->id ? 'selected' : '' }}>
                                        {{ $atc->tax_code }} - {{ $atc->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Background Information -->
                        <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
                        <!-- TIN -->
                        <div class="mb-2 flex flex-row justify-between gap-48">
                            <label for="tin" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">7</span>Taxpayer Identification Number (TIN)</label>
                            <input type="text" id="tin" name="tin" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly 
                            value="{{ $orgSetup->tin }}">
                        </div>

                        <!-- RDO Code -->
                        <div class="mb-2 flex flex-row justify-between gap-48">
                            <label for="rdo" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">8</span>Revenue District Office (RDO) Code</label>
                            <input type="text" id="rdo" name="rdo" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly 
                            value="{{ $orgSetup->rdo }}">
                        </div>

                        <!-- Withholding Agent's Name -->
                        <div class="mb-2 flex flex-row justify-between gap-48">
                            <label for="agent_name" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">9</span>Withholding Agent's Name</label>
                            <input type="text" id="agent_name" name="agent_name" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly 
                            value="{{ $orgSetup->registration_name }}">
                        </div>

                        <!-- Registered Address -->
                        <div class="mb-2 flex flex-row justify-between gap-48">
                            <label for="address" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">10</span>Registered Address</label>
                            <input type="text" id="address" name="address" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly 
                            value="{{ $orgSetup->address_line }}">
                        </div>

                        <!-- Zip Code -->
                        <div class="mb-6 flex flex-row justify-between">
                            <label for="zip_code" class="indent-4 block px-8 text-zinc-700 py-2 text-sm w-full"><span class="font-bold mr-2">10A</span>Zip Code</label>
                            <input type="text" id="zip_code" name="zip_code" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly 
                            value="{{ $orgSetup->zip_code }}">
                        </div>

                        <!-- Contact Number -->
                        <div class="mb-2 flex flex-row justify-between gap-48">
                            <label for="contact_number" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">11</span>Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly 
                            value="{{ $orgSetup->contact_number }}">
                        </div>

                        <!-- Any Taxes Withheld -->
                        <div class="mb-2 flex flex-row justify-between gap-48">
                            <label class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">12</span>Category of Withholding Agent</label>
                            <div class="flex items-center space-x-4 w-full py-2">
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="category" value="1" class="mr-2" required>Private
                                </label>
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="category" value="0" class="mr-2" required>Government
                                </label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-2 flex flex-row justify-between gap-48">
                            <label for="email" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">13</span>Email Address</label>
                            <input type="email" id="email" name="email" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly 
                            value="{{ $orgSetup->email }}">
                        </div>

                        <!-- Tax Remittance -->
                        <h3 class="font-bold text-zinc-700 text-lg mb-4">Tax Remittance</h3>
                        <!-- Amount of Remittance -->
                        <div class="mb-2 flex flex-row justify-between gap-48">
                            <label for="email" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">14</span>Amount of Remittance</label>
                            <input type="number" id="amount_of_remittance" name="amount_of_remittance" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                        </div>

                        <!-- Less: Amount Remittance Previously Filed -->
                        <div class="mb-2 flex flex-row justify-between gap-1">
                            <label for="remitted_previous" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">15</span>Less: Amount Remittance Previously Filed</label>
                            <input type="number" id="remitted_previous" name="remitted_previous" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>

                        <div class="mb-2 flex flex-row justify-between gap-48">
                            <label for="net_amount_of_remittance" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">16</span>Net Amount of Remittance</label>
                            <input type="text" id="net_amount_of_remittance" name="net_amount_of_remittance" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                        </div>

                        <!-- Add: Surcharge -->
                        <h5 class="indent-4 block mb-2 text-zinc-700 text-sm w-full"><span class="font-bold mr-2">17</span>Add Penalties</h5>
                        <div class="mb-2 flex flex-row justify-between gap-40">
                            <label for="surcharge" class="indent-4 block px-8 text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">17A</span>Surcharge</label>
                            <input type="number" id="surcharge" name="surcharge" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>

                        <!-- Add: Interest -->
                        <div class="mb-2 flex flex-row justify-between gap-40">
                            <label for="interest" class="indent-4 block px-8 text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">17B</span>Interest</label>
                            <input type="number" id="interest" name="interest" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>

                        <!-- Add: Compromise -->
                        <div class="mb-2 flex flex-row justify-between gap-40">
                            <label for="compromise" class="indent-4 block px-8 text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">17C</span>Compromise</label>
                            <input type="number" id="compromise" name="compromise" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>

                        <!-- Total Penalties -->
                        <div class="mb-2 flex flex-row justify-between gap-40">
                            <label for="total_penalties" class="indent-4 block px-8 text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">17D</span>Total Penalties</label>
                            <input type="text" id="total_penalties" name="total_penalties" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                        </div>

                        <!-- Total Amount Due -->
                        <div class="mb-2 flex flex-row justify-between gap-1">
                            <label for="total_remittance" class="indent-4 block text-zinc-700 font-bold text-sm w-full"><span class="font-bold mr-2">18</span>Total Amount of Remittance <span class="font-normal italic text-xs">(Sum of Items 16 and 17D)</span></label>
                            <input type="text" id="total_remittance" name="total_remittance" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
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
