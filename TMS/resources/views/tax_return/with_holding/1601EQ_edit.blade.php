<x-app-layout>
    <div class="max-w-6xl mx-auto py-12 px-6">
        <div class="relative flex items-center mb-6">
            <button onclick="window.location.href='{{ route('form1601EQ.preview', ['id' => $form->id]) }}'" class="text-zinc-600 hover:text-zinc-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                    <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g>
                </svg>
                <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go Back</span>
            </button>
        </div>

        <div class="px-6 py-4 bg-white shadow-sm sm:rounded-lg">
            <div class="container px-4">
                <div class="flex justify-between items-center mt-2">
                    <div class="flex flex-col items-start">
                        <!-- BIR Form text on top -->
                        <p class="text-sm taxuri-color">BIR Form No. 1601EQ Edit</p>
                        <p class="font-bold text-xl taxuri-color">Quartely Remittance Return <span class="text-lg">(of Creditable Income Taxes Withheld (Expanded))</span></p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-2 mb-4">
                    <div class="flex items-center">
                        <p class="taxuri-text font-normal text-sm">
                            Verify the tax information below, with some fields pre-filled from your organization's setup. Select options as needed to generate the BIR form.
                        </p>
                    </div>
                </div>  
            </div>
        </div>

        <div class="bg-white shadow-sm mt-6 rounded-lg overflow-hidden">
            <form method="POST" action="{{ route('form1601EQ.update', $form->id) }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="px-8 py-10">
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>
                    <!-- Amended Return -->
                    <div class="mb-2 flex flex-row justify-between gap-96">
                        <label class="indent-4 block text-zinc-700 text-sm w-1/3">Amended Return?</label>
                        <div class="flex items-center space-x-4 w-full py-2">
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="amended_return" value="1" class="h-5 w-5 text-blue-600 border-zinc-300 focus:ring-blue-500" {{ $form->amended_return ? 'checked' : '' }}>
                                <span class="ml-2 text-zinc-700">Yes</span>
                            </label>
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="amended_return" value="0" class="h-5 w-5 text-blue-600 border-zinc-300 focus:ring-blue-500" {{ !$form->amended_return ? 'checked' : '' }}>
                                <span class="ml-2 text-zinc-700">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Any Taxes Withheld -->
                    <div class="mb-2 flex flex-row justify-between gap-96">
                        <label class="indent-4 block text-zinc-700 text-sm w-1/3">Any Taxes Withheld?</label>
                        <div class="flex items-center space-x-4 w-full py-2">
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="any_taxes_withheld" value="1" class="h-5 w-5 text-blue-600 border-zinc-300 focus:ring-blue-500" {{ $form->any_taxes_withheld ? 'checked' : '' }}>
                                <span class="ml-2 text-zinc-700">Yes</span>
                            </label>
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="any_taxes_withheld" value="0" class="h-5 w-5 text-blue-600 border-zinc-300 focus:ring-blue-500" {{ !$form->any_taxes_withheld ? 'checked' : '' }}>
                                <span class="ml-2 text-zinc-700">No</span>
                            </label>
                        </div>
                    </div>

                    <!-- Category of Withholding Agent -->
                    <div class="mb-2 flex flex-row justify-between gap-14">
                        <label class="indent-4 block text-zinc-700 text-sm w-full">Category of Withholding Agent</label>
                        <div class="flex items-center space-x-4 w-full py-2">
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="category" value="1" class="h-5 w-5 text-blue-600 border-zinc-300 focus:ring-blue-500" {{ $form->category == 1 ? 'checked' : '' }}>
                                <span class="ml-2 text-zinc-700">Private</span>
                            </label>
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="category" value="0" class="h-5 w-5 text-blue-600 border-zinc-300 focus:ring-blue-500" {{ $form->category == 0 ? 'checked' : '' }}>
                                <span class="ml-2 text-zinc-700">Government</span>
                            </label>
                        </div>
                    </div>

                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Computation of Tax</h3>

                    <!-- Tax Remitted Previously -->
                    <div class="mb-2 flex flex-row justify-between gap-14">
                        <label for="remitted_previous" class="indent-4 block text-zinc-700 text-sm w-full">Tax Remitted in Return Previously Filed</label>
                        <input type="number" name="remitted_previous" value="{{ old('remitted_previous', $form->remitted_previous) }}" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Over-remittance -->
                    <div class="mb-2 flex flex-row justify-between gap-14">
                        <label for="over_remittance" class="indent-4 block text-zinc-700 text-sm w-full">Over-remittance from Previous Quarter of the same taxable year</label>
                        <input type="number" name="over_remittance" value="{{ old('over_remittance', $form->over_remittance) }}" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Other Payments Made -->
                    <div class="mb-2 flex flex-row justify-between gap-14">
                        <label for="other_payments" class="indent-4 block text-zinc-700 text-sm w-full">Other Payments Made</label>
                        <input type="number" name="other_payments" value="{{ old('other_payments', $form->other_payments) }}" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Surcharge -->
                    <div class="mb-2 flex flex-row justify-between gap-14">
                        <label for="surcharge" class="indent-4 block text-zinc-700 text-sm w-full">Surcharge</label>
                        <input type="number" name="surcharge" value="{{ old('surcharge', $form->surcharge) }}" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Interest -->
                    <div class="mb-2 flex flex-row justify-between gap-14">
                        <label for="interest" class="indent-4 block text-zinc-700 text-sm w-full">Interest</label>
                        <input type="number" name="interest" value="{{ old('interest', $form->interest) }}" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Compromise -->
                    <div class="mb-2 flex flex-row justify-between gap-14">
                        <label for="compromise" class="indent-4 block text-zinc-700 text-sm w-full">Compromise</label>
                        <input type="number" name="compromise" value="{{ old('compromise', $form->compromise) }}" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-center items-center">
                        <button type="submit" class="w-56 bg-blue-900 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-950">
                            Update Report
                        </button>
                        <a href="{{ route('form1601EQ.preview', ['id' => $form->id]) }}"
                            class="ml-4 text-zinc-600 hover:text-zinc-900 hover:font-bold font-medium py-2 px-4">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
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
