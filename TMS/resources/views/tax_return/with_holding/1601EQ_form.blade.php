<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h1 class="text-lg font-semibold text-gray-700">BIR Form No. 1601-EQ</h1>
                    <p class="text-sm text-gray-600 mt-1">Quarterly Remittance Return of Creditable Income Taxes Withheld (Expanded)</p>
                </div>

                <form action="{{ route('form1601EQ.store', ['id' => $withHolding->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="withholding_id" value="{{ $withHolding->id }}">

                    <!-- Filing Period -->
                    
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

                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label class="block text-sm font-medium text-gray-700">Quarter</label>
                        <div class="mt-2 space-x-4">
                            @for ($i = 1; $i <= 4; $i++)
                                <label>
                                    <input 
                                        type="radio" 
                                        name="quarter" 
                                        value="{{ $i }}" 
                                        {{ old('quarter', $withHolding->quarter) == $i ? 'checked' : '' }} 
                                        required
                                    > {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }}
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Amended Return -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label class="block text-sm font-medium text-gray-700">Amended Return?</label>
                        <div class="mt-2 space-x-4">
                            <label>
                                <input 
                                    type="radio" 
                                    name="amended_return" 
                                    value="1" 
                                    {{ old('amended_return') == '1' ? 'checked' : '' }} 
                                    required
                                > Yes
                            </label>
                            <label>
                                <input 
                                    type="radio" 
                                    name="amended_return" 
                                    value="0" 
                                    {{ old('amended_return') == '0' ? 'checked' : '' }} 
                                    required
                                > No
                            </label>
                        </div>
                    </div>

                    <!-- Any Taxes Withheld -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                        <label class="block text-sm font-medium text-gray-700">Any Taxes Withheld?</label>
                        <div class="mt-2 space-x-4">
                            <label>
                                <input 
                                    type="radio" 
                                    name="any_taxes_withheld" 
                                    value="1" 
                                    {{ old('any_taxes_withheld') == '1' ? 'checked' : '' }} 
                                    required
                                > Yes
                            </label>
                            <label>
                                <input 
                                    type="radio" 
                                    name="any_taxes_withheld" 
                                    value="0" 
                                    {{ old('any_taxes_withheld') == '0' ? 'checked' : '' }} 
                                    required
                                > No
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
                            min="0" 
                            value="{{ old('number_of_sheets', 0) }}"
                        >
                    </div>

                    <!-- Background Information -->
                    <h2 class="text-lg font-semibold text-gray-800 mt-8">Background Information</h2>
                        <!-- Taxpayer Identification Number -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="tin" class="block text-sm font-medium text-gray-700">Taxpayer Identification Number (TIN)</label>
                            <input 
                                type="text" 
                                id="tin" 
                                name="tin" 
                                class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" 
                                value="{{ $orgSetup->tin }}" 
                                readonly
                            >
                        </div>

                        <!-- Revenue District Office (RDO) Code -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="rdo" class="block text-sm font-medium text-gray-700">Revenue District Office (RDO) Code</label>
                            <input type="text" id="rdo" name="rdo" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->rdo }}">
                        </div>

                        <!-- Withholding Agent's Name -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="agent_name" class="block text-sm font-medium text-gray-700">Withholding Agent's Name</label>
                            <input 
                                type="text" 
                                id="agent_name" 
                                name="agent_name" 
                                class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" 
                                value="{{ $orgSetup->registration_name }}" 
                                readonly
                            >
                        </div>

                        <!-- Registered Address -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="address" class="block text-sm font-medium text-gray-700">Registered Address</label>
                            <input 
                                type="text" 
                                id="address" 
                                name="address" 
                                class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" 
                                value="{{ $orgSetup->address_line }}" 
                                readonly
                            >
                        </div>

                        <!-- Zip Code -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="zip_code" class="block text-sm font-medium text-gray-700">Zip Code</label>
                            <input 
                                type="text" 
                                id="zip_code" 
                                name="zip_code" 
                                class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" 
                                value="{{ $orgSetup->zip_code }}" 
                                readonly
                            >
                        </div>
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="contact_number" class="block text-sm font-medium text-gray-700">Contact Number</label>
                            <input 
                                type="text" 
                                id="contact_number" 
                                name="contact_number" 
                                class="mt-1 p-2 block w-full border border-gray-300 rounded-md" 
                                value="{{ $orgSetup->contact_number }}" 
                                readonly
                            >
                        </div>

                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label class="block text-sm font-medium text-gray-700">Category of Withholding Agent</label>
                            <div class="mt-2 space-x-4">
                                <label>
                                    <input 
                                        type="radio" 
                                        name="category" 
                                        value="Private" 
                                        {{ $orgSetup->type === 'Private' ? 'checked' : '' }} 
                                        required
                                    > Private
                                </label>
                                <label>
                                    <input 
                                        type="radio" 
                                        name="category" 
                                        value="Government" 
                                        {{ $orgSetup->type === 'Government' ? 'checked' : '' }} 
                                        required
                                    > Government
                                </label>
                            </div>
                        </div>

                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                class="mt-1 p-2 block w-full border border-gray-300 rounded-md" 
                                value="{{ $orgSetup->email }}" 
                                readonly
                            >
                        </div>

                    <!-- Tax Computation -->
                    <h2 class="text-lg font-semibold text-gray-800 mt-8">Computation of Tax</h2>
                        <!-- Nag lagay muna akong 6. Pero baka maging variable yan, bibilangin ata existing forms -->
                        @for ($i = 0; $i < 6; $i++)
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="atc_{{ $i }}" class="block text-sm font-medium text-gray-700">ATC</label>
                                <select id="atc_{{ $i }}" name="atc[]" class="atc-dropdown mt-1 block w-full border border-gray-300 rounded-md" data-index="{{ $i }}">
                                    <option value="" disabled selected>Select Option</option>
                                    @foreach ($atcs as $atc)
                                        <option value="{{ $atc->id }}" data-tax-rate="{{ $atc->tax_rate }}">
                                            {{ $atc->tax_code }} - {{ $atc->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="tax_base_{{ $i }}" class="block text-sm font-medium text-gray-700">Tax Base</label>
                                <input type="number" id="tax_base_{{ $i }}" name="tax_base[]" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="tax_rate_{{ $i }}" class="block text-sm font-medium text-gray-700">Tax Rate</label>
                                <input type="number" id="tax_rate_{{ $i }}" name="tax_rate[]" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" readonly>
                            </div>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">   
                                <label for="tax_withheld_{{ $i }}" class="block text-sm font-medium text-gray-700">Tax Withheld</label>
                                <input type="number" id="tax_withheld_{{ $i }}" name="tax_withheld[]" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>
                        @endfor

                        <!-- Computation of Total Taxes Withheld -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="total_taxes_withheld" class="block text-sm font-medium text-gray-700">
                                    19. Total Taxes Withheld for the Quarter
                                </label>
                                <input
                                    type="text"
                                    id="total_taxes_withheld"
                                    name="total_taxes_withheld"
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100"
                                    readonly
                                >
                            </div>

                        <!-- Other Computations -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">   
                                <label for="remittances_1st_month" class="block text-sm font-medium text-gray-700">20. Remittances Made: 1st Month</label>
                                <input type="number" id="remittances_1st_month" name="remittances_1st_month" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="remittances_2nd_month" class="block text-sm font-medium text-gray-700">21. Remittances Made: 2nd Month</label>
                                <input type="number" id="remittances_2nd_month" name="remittances_2nd_month" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96"> 
                                <label for="remitted_previous" class="block text-sm font-medium text-gray-700">22. Tax Remitted Previously</label>
                                <input type="number" id="remitted_previous" name="remitted_previous" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="over_remittance" class="block text-sm font-medium text-gray-700">23. Over-remittance</label>
                                <input type="number" id="over_remittance" name="over_remittance" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="other_payments" class="block text-sm font-medium text-gray-700">24. Other Payments Made</label>
                                <input type="number" id="other_payments" name="other_payments" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>

                            <!-- Total Remittances Made -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="total_remittances_made" class="block text-sm font-medium text-gray-700">25. Total Remittances Made (Sum of Items 20 to 24)</label>
                                <input type="text" id="total_remittances_made" name="total_remittances_made" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly>
                            </div>

                            <!-- Tax Still Due / Over-remittance -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="tax_still_due" class="block text-sm font-medium text-gray-700">26. Tax Still Due / Over-remittance (Item 19 Less Item 25)</label>
                                <input type="text" id="tax_still_due" name="tax_still_due" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly>
                            </div>

                        <!-- Add Penalties -->
                        <h2 class="text-lg font-semibold text-gray-800 mt-8">Add Penalties</h2>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="surcharge" class="block text-sm font-medium text-gray-700">27. Surcharge</label>
                                <input type="number" id="surcharge" name="surcharge" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="interest" class="block text-sm font-medium text-gray-700">28. Interest</label>
                                <input type="number" id="interest" name="interest" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="compromise" class="block text-sm font-medium text-gray-700">29. Compromise</label>
                                <input type="number" id="compromise" name="compromise" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>

                        <!-- Total Penalties -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="penalties" class="block text-sm font-medium text-gray-700">30. Total Penalties</label>
                            <input type="text" id="penalties" name="penalties" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly>
                        </div>

                        <!-- Total Amount Due -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="total_amount_due" class="block text-sm font-medium text-gray-700">31. Total Amount Still Due</label>
                            <input type="text" id="total_amount_due" name="total_amount_due" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly>
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

    <!-- Script to calculate total amount due -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM fully loaded and parsed.');

            const fields = {
                totalTaxesWithheld: document.getElementById('total_taxes_withheld'),
                remittances1stMonth: document.getElementById('remittances_1st_month'),
                remittances2ndMonth: document.getElementById('remittances_2nd_month'),
                remittedPrevious: document.getElementById('remitted_previous'),
                overRemittance: document.getElementById('over_remittance'),
                otherPayments: document.getElementById('other_payments'),
                totalRemittancesMade: document.getElementById('total_remittances_made'),
                taxStillDue: document.getElementById('tax_still_due'),
                surcharge: document.getElementById('surcharge'),
                interest: document.getElementById('interest'),
                compromise: document.getElementById('compromise'),
                totalPenalties: document.getElementById('penalties'),
                totalAmountDue: document.getElementById('total_amount_due'),
            };

            const taxWithheldFields = document.querySelectorAll('input[name="tax_withheld[]"]');
            const atcDropdowns = document.querySelectorAll('.atc-dropdown');

            // Debug: Log ATC dropdown fields
            console.log('ATC Dropdown Fields:', atcDropdowns);

            // Function to autofill the tax_rate based on the selected ATC
            function autofillTaxRate(event) {
                const dropdown = event.target;
                const index = dropdown.getAttribute('data-index');
                const selectedOption = dropdown.options[dropdown.selectedIndex];
                const taxRate = selectedOption.getAttribute('data-tax-rate');

                console.log(`ATC Selected [Index ${index}]:`, selectedOption.value, 'Tax Rate:', taxRate);

                // Update the corresponding tax_rate input field
                const taxRateInput = document.getElementById(`tax_rate_${index}`);
                taxRateInput.value = taxRate || '';
            }

            // Attach change event listeners to each ATC dropdown
            atcDropdowns.forEach(dropdown => {
                dropdown.addEventListener('change', autofillTaxRate);
            });

            function calculateTotalTaxesWithheld() {
                console.log('Calculating total taxes withheld...');
                let totalTaxes = 0;
                taxWithheldFields.forEach((field, index) => {
                    const value = parseFloat(field.value) || 0;
                    console.log(`Tax Withheld Field [${index}]:`, value);
                    totalTaxes += value;
                });
                console.log('Total Taxes Withheld:', totalTaxes);
                fields.totalTaxesWithheld.value = totalTaxes.toFixed(2); // Set the calculated value
            }

            function calculateTotals() {
                console.log('Calculating all totals...');
                calculateTotalTaxesWithheld(); // Ensure totalTaxesWithheld is updated first.

                const totalTaxes = parseFloat(fields.totalTaxesWithheld.value) || 0;
                const remittances = [
                    parseFloat(fields.remittances1stMonth.value) || 0,
                    parseFloat(fields.remittances2ndMonth.value) || 0,
                    parseFloat(fields.remittedPrevious.value) || 0,
                    parseFloat(fields.overRemittance.value) || 0,
                    parseFloat(fields.otherPayments.value) || 0,
                ];
                console.log('Remittances:', remittances);

                const totalRemittances = remittances.reduce((a, b) => a + b, 0);
                console.log('Total Remittances:', totalRemittances);
                fields.totalRemittancesMade.value = totalRemittances.toFixed(2);

                const taxDue = totalTaxes - totalRemittances;
                console.log('Tax Still Due:', taxDue);
                fields.taxStillDue.value = taxDue.toFixed(2);

                const penalties = (parseFloat(fields.surcharge.value) || 0) +
                                (parseFloat(fields.interest.value) || 0) +
                                (parseFloat(fields.compromise.value) || 0);
                console.log('Total Penalties:', penalties);
                fields.totalPenalties.value = penalties.toFixed(2);

                const totalAmountDue = taxDue + penalties;
                console.log('Total Amount Due:', totalAmountDue);
                fields.totalAmountDue.value = totalAmountDue.toFixed(2);
            }

            // Attach event listeners to relevant fields
            [...taxWithheldFields, ...Object.values(fields)].forEach((field, index) => {
                console.log(`Attaching input listener to field [${index}]`, field);
                field.addEventListener('input', calculateTotals);
            });

            // Initial calculation on page load
            console.log('Performing initial calculations...');
            calculateTotals();
        });


    </script>
</x-app-layout>
