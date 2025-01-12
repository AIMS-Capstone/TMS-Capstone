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
                    <input type="hidden" id="atcDetailsData" value='@json($atcDetails)' />  

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
                                        value="1" 
                                        required
                                    > Private
                                </label>
                                <label>
                                    <input 
                                        type="radio" 
                                        name="category" 
                                        value="0" 
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
                    <table class="min-w-full mt-4">
                        <thead>
                            <tr>
                                <th>Tax Code</th>
                                <th>Tax Base</th>
                                <th>Tax Rate</th>
                                <th>Tax Withheld</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($atcDetails as $detail)
                                <tr>
                                    <td>{{ optional($detail->atc)->tax_code ?? 'N/A' }}</td>
                                    <td><input type="text" value="{{ $detail->tax_base }}" readonly class="form-input"></td>
                                    <td><input type="text" value="{{ $detail->tax_rate }}" readonly class="form-input"></td>
                                    <td><input type="text" value="{{ $detail->tax_withheld }}" readonly class="form-input tax-withheld"></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-gray-500">No ATC details available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

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
                            <!-- Remittances Made: 1st Month -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="remittances_1st_month" class="block text-sm font-medium text-gray-700">
                                    20. Remittances Made: 1st Month
                                </label>
                                <input type="number" 
                                    id="remittances_1st_month" 
                                    name="remittances_1st_month" 
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" 
                                    value="{{ $remittanceData->remittance_1st_month ?? 0 }}" 
                                    readonly />
                            </div>

                            <!-- Remittances Made: 2nd Month -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="remittances_2nd_month" class="block text-sm font-medium text-gray-700">
                                    21. Remittances Made: 2nd Month
                                </label>
                                <input type="number" 
                                    id="remittances_2nd_month" 
                                    name="remittances_2nd_month" 
                                    class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" 
                                    value="{{ $remittanceData->remittance_2nd_month ?? 0 }}" 
                                    readonly />
                            </div>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96"> 
                                <label for="remitted_previous" class="block text-sm font-medium text-gray-700">22. Tax Remitted Previously</label>
                                <input type="number" id="remitted_previous" name="remitted_previous" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="over_remittance" class="block text-sm font-medium text-gray-700">23. Over-remittance</label>
                                <input type="number" id="over_remittance" name="over_remittance" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly>
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

                function calculateTotalTaxesWithheld() {
                    console.log('Fetching and calculating total taxes withheld...');
                    
                    // Fetch ATC details from hidden input
                    const atcDetails = JSON.parse(document.getElementById('atcDetailsData').value || '[]');
                    let totalTaxes = atcDetails.reduce((sum, detail) => sum + parseFloat(detail.tax_withheld || 0), 0);

                    console.log('Total Taxes Withheld:', totalTaxes);
                    fields.totalTaxesWithheld.value = totalTaxes.toFixed(2);
                }

                function calculateTotalRemittances() {
                    const remittances = [
                        parseFloat(fields.remittances1stMonth.value) || 0,
                        parseFloat(fields.remittances2ndMonth.value) || 0,
                        parseFloat(fields.remittedPrevious.value) || 0,
                        parseFloat(fields.otherPayments.value) || 0,
                    ];
                    const totalRemittances = remittances.reduce((a, b) => a + b, 0);
                    fields.totalRemittancesMade.value = totalRemittances.toFixed(2);
                }

                function calculateTotalPenalties() {
                    const penalties = (parseFloat(fields.surcharge.value) || 0) +
                        (parseFloat(fields.interest.value) || 0) +
                        (parseFloat(fields.compromise.value) || 0);
                    fields.totalPenalties.value = penalties.toFixed(2);
                }

                function calculateTotals() {
                    console.log('Calculating all totals...');
                    calculateTotalTaxesWithheld();
                    calculateTotalRemittances();
                    calculateTotalPenalties();

                    const totalTaxes = parseFloat(fields.totalTaxesWithheld.value) || 0;
                    const totalRemittances = parseFloat(fields.totalRemittancesMade.value) || 0;
                    const penalties = parseFloat(fields.totalPenalties.value) || 0;

                    // Calculate tax due or over-remittance
                    let taxDue = 0;
                    let overRemittance = 0;

                    if (totalRemittances >= totalTaxes) {
                        overRemittance = totalRemittances - totalTaxes;
                    } else {
                        taxDue = totalTaxes - totalRemittances;
                    }

                    // Update fields
                    fields.overRemittance.value = overRemittance.toFixed(2);
                    fields.taxStillDue.value = taxDue > 0 ? taxDue.toFixed(2) : '0.00';

                    // Total amount due calculation
                    const totalAmountDue = taxDue + penalties;
                    fields.totalAmountDue.value = totalAmountDue.toFixed(2);
                }

                Object.values(fields).forEach((field) => {
                    if (field) {
                        field.addEventListener('input', calculateTotals);
                    }
                });

                calculateTotals();
            });
        </script>
</x-app-layout>
