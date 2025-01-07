<x-app-layout>
    <div class="py-12">
        <div class="relative flex items-center mb-6">
            <button onclick="history.back()" class="flex items-center text-gray-600 hover:text-gray-800 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M16 12H8m4-4l-4 4l4 4"/>
                    </g>
                </svg>
                <span class="text-sm font-medium">Go Back</span>
            </button>
        </div>
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-white p-10 shadow-lg rounded-lg">
                <h1 class="text-4xl font-extrabold text-gray-800 mb-8">Edit 1601EQ Form</h1>
                
                <form method="POST" action="{{ route('form1601EQ.update', $form->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Amended Return -->
                    <div>
                        <label class="block text-lg font-medium text-gray-800">Amended Return?</label>
                        <div class="mt-2 flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="amended_return" value="1" class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500" {{ $form->amended_return ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Yes</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="amended_return" value="0" class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500" {{ !$form->amended_return ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Any Taxes Withheld -->
                    <div>
                        <label class="block text-lg font-medium text-gray-800">Any Taxes Withheld?</label>
                        <div class="mt-2 flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="any_taxes_withheld" value="1" class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500" {{ $form->any_taxes_withheld ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Yes</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="any_taxes_withheld" value="0" class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500" {{ !$form->any_taxes_withheld ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Category of Withholding Agent -->
                    <div>
                        <label class="block text-lg font-medium text-gray-800">Category of Withholding Agent</label>
                        <div class="mt-2 flex items-center space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="category" value="1" class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500" {{ $form->category == 1 ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Private</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="category" value="0" class="h-5 w-5 text-blue-600 border-gray-300 focus:ring-blue-500" {{ $form->category == 0 ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Government</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tax Remitted Previously -->
                    <div>
                        <label for="remitted_previous" class="block text-lg font-medium text-gray-800">22. Tax Remitted Previously</label>
                        <input type="number" name="remitted_previous" value="{{ old('remitted_previous', $form->remitted_previous) }}" 
                            class="mt-1 block w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg shadow-sm">
                    </div>

                    <!-- Over-remittance -->
                    <div>
                        <label for="over_remittance" class="block text-lg font-medium text-gray-800">23. Over-remittance</label>
                        <input type="number" name="over_remittance" value="{{ old('over_remittance', $form->over_remittance) }}" 
                            class="mt-1 block w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg shadow-sm">
                    </div>

                    <!-- Other Payments Made -->
                    <div>
                        <label for="other_payments" class="block text-lg font-medium text-gray-800">24. Other Payments Made</label>
                        <input type="number" name="other_payments" value="{{ old('other_payments', $form->other_payments) }}" 
                            class="mt-1 block w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg shadow-sm">
                    </div>

                    <!-- Surcharge -->
                    <div>
                        <label for="surcharge" class="block text-lg font-medium text-gray-800">27. Surcharge</label>
                        <input type="number" name="surcharge" value="{{ old('surcharge', $form->surcharge) }}" 
                            class="mt-1 block w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg shadow-sm">
                    </div>

                    <!-- Interest -->
                    <div>
                        <label for="interest" class="block text-lg font-medium text-gray-800">28. Interest</label>
                        <input type="number" name="interest" value="{{ old('interest', $form->interest) }}" 
                            class="mt-1 block w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg shadow-sm">
                    </div>

                    <!-- Compromise -->
                    <div>
                        <label for="compromise" class="block text-lg font-medium text-gray-800">29. Compromise</label>
                        <input type="number" name="compromise" value="{{ old('compromise', $form->compromise) }}" 
                            class="mt-1 block w-full border-gray-300 focus:ring-blue-500 focus:border-blue-500 rounded-lg shadow-sm">
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-center items-center">
                        <button type="submit" class="flex justify-center px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Update Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('Edit form loaded.');

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
                submitButton: document.querySelector('button[type="submit"]')
            };

            function validateNumber(input) {
                let value = parseFloat(input.value);
                if (isNaN(value) || value < 0) {
                    input.classList.add('border-red-500');
                    input.value = 0;
                } else {
                    input.classList.remove('border-red-500');
                }
            }

            function calculateTotalTaxesWithheld() {
                let totalTaxes = 0;
                const taxWithheldFields = document.querySelectorAll('input[name="tax_withheld[]"]');
                taxWithheldFields.forEach((field) => {
                    const value = parseFloat(field.value) || 0;
                    if (value < 0) {
                        field.value = 0;  // Reset invalid inputs
                    }
                    totalTaxes += value;
                });
                fields.totalTaxesWithheld.value = totalTaxes.toFixed(2);
            }

            function calculateTotalRemittances() {
                const remittances = [
                    parseFloat(fields.remittances1stMonth?.value) || 0,
                    parseFloat(fields.remittances2ndMonth?.value) || 0,
                    parseFloat(fields.remittedPrevious?.value) || 0,
                    parseFloat(fields.overRemittance?.value) || 0,
                    parseFloat(fields.otherPayments?.value) || 0,
                ];
                
                // Reset negative remittance values
                remittances.forEach((val, idx) => {
                    if (val < 0) remittances[idx] = 0;
                });

                const totalRemittances = remittances.reduce((a, b) => a + b, 0);
                fields.totalRemittancesMade.value = totalRemittances.toFixed(2);
            }

            function calculateTotalPenalties() {
                const penalties = (parseFloat(fields.surcharge.value) || 0) +
                    (parseFloat(fields.interest.value) || 0) +
                    (parseFloat(fields.compromise.value) || 0);

                if (penalties < 0) {
                    fields.surcharge.value = 0;
                    fields.interest.value = 0;
                    fields.compromise.value = 0;
                }
                fields.totalPenalties.value = penalties.toFixed(2);
            }

            function calculateTotals() {
                calculateTotalTaxesWithheld();
                calculateTotalRemittances();
                calculateTotalPenalties();

                const totalTaxes = parseFloat(fields.totalTaxesWithheld.value) || 0;
                const totalRemittances = parseFloat(fields.totalRemittancesMade.value) || 0;
                const penalties = parseFloat(fields.totalPenalties.value) || 0;

                const taxDue = totalTaxes - totalRemittances;
                fields.taxStillDue.value = taxDue.toFixed(2);

                const totalAmountDue = taxDue + penalties;
                fields.totalAmountDue.value = totalAmountDue.toFixed(2);

                validateSubmitButton();
            }

            function validateSubmitButton() {
                const taxStillDue = parseFloat(fields.taxStillDue.value) || 0;
                const totalAmountDue = parseFloat(fields.totalAmountDue.value) || 0;

                // Disable submit if negative or invalid
                if (totalAmountDue < 0 || isNaN(totalAmountDue)) {
                    fields.submitButton.disabled = true;
                    fields.submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    fields.submitButton.disabled = false;
                    fields.submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            // Attach input validation to key fields
            const watchFields = [
                fields.remittedPrevious,
                fields.overRemittance,
                fields.otherPayments,
                fields.surcharge,
                fields.interest,
                fields.compromise
            ];

            watchFields.forEach(field => {
                if (field) {
                    field.addEventListener('input', function () {
                        validateNumber(field);
                        calculateTotals();
                    });
                }
            });

            calculateTotals();
        });
    </script>

</x-app-layout>
