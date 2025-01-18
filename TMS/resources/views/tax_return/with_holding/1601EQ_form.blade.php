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
                            <p class="text-sm taxuri-color">BIR Form No. 1601-EQ</p>
                            <p class="font-bold text-3xl taxuri-color">Quarterly Remittance Return <span class="text-lg">(of Creditable Income Taxes Withheld (Expanded))</span></p>
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
                <form action="{{ route('form1601EQ.store', ['id' => $withHolding->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="withholding_id" value="{{ $withHolding->id }}">
                    <input type="hidden" id="atcDetailsData" value='@json($atcDetails)' />  
                    <div class="px-8 py-10">
                        <!-- Filing Period -->
                        <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>

                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label for="year" class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">1</span>For the Year</label>
                            <input type="text" id="year" name="year" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            value="{{ $withHolding->year }}" readonly>
                        </div>

                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">2</span>Quarter</label>
                            <div class="flex items-center space-x-4 w-full py-2">
                                @for ($i = 1; $i <= 4; $i++)
                                    <label class="flex items-center text-zinc-700 text-sm">
                                        <input type="radio" name="quarter" class="mr-2" value="{{ $i }}" {{ old('quarter', $withHolding->quarter) == $i ? 'checked' : '' }} required> {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }}
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Amended Return -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">3</span>Amended Return?</label>
                            <div class="flex items-center space-x-4 w-full py-2">
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="amended_return" class="mr-2" value="1" {{ old('amended_return') == '1' ? 'checked' : '' }} 
                                        required> Yes
                                </label>
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="amended_return" class="mr-2" value="0" {{ old('amended_return') == '0' ? 'checked' : '' }} required> No
                                </label>
                            </div>
                        </div>

                        <!-- Any Taxes Withheld -->
                        <div class="mb-2 flex flex-row justify-between gap-96">
                            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">4</span>Any Taxes Withheld?</label>
                            <div class="flex items-center space-x-4 w-full py-2">
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="any_taxes_withheld" class="mr-2" value="1" {{ old('any_taxes_withheld') == '1' ? 'checked' : '' }} required> Yes
                                </label>
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="any_taxes_withheld" class="mr-2" value="0" {{ old('any_taxes_withheld') == '0' ? 'checked' : '' }} required> No
                                </label>
                            </div>
                        </div>

                        <!-- Number of Sheets Attached -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="number_of_sheets" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">5</span>Number of Sheets Attached</label>
                            <input type="number" id="number_of_sheets" name="number_of_sheets" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                min="0" value="{{ old('number_of_sheets', 0) }}">
                        </div>

                        <!-- Background Information -->
                        <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>

                        <!-- Taxpayer Identification Number -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="tin" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">6</span>Taxpayer Identification Number (TIN)</label>
                            <input type="text" id="tin" name="tin" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ $orgSetup->tin }}" readonly>
                        </div>

                        <!-- Revenue District Office (RDO) Code -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="rdo" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">7</span>Revenue District Office (RDO) Code</label>
                            <input type="text" id="rdo" name="rdo" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            readonly value="{{ $orgSetup->rdo }}">
                        </div>

                        <!-- Withholding Agent's Name -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="agent_name" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">8</span>Withholding Agent's Name</label>
                            <input type="text" id="agent_name" name="agent_name" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ $orgSetup->registration_name }}" readonly>
                        </div>

                        <!-- Registered Address -->
                        <div  class="mb-2 flex flex-row justify-between gap-4">
                            <label for="address" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">9</span>Registered Address</label>
                            <input type="text" id="address" name="address" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ $orgSetup->address_line }}" readonly>
                        </div>

                        <!-- Zip Code -->
                        <div class="mb-2 flex flex-row justify-between">
                            <label for="zip_code" class="indent-4 px-8 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">9A</span>Zip Code</label>
                            <input type="text" id="zip_code" name="zip_code" class="block w-full py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ $orgSetup->zip_code }}" readonly>
                        </div>

                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="contact_number" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">10</span>Contact Number</label>
                            <input type="text" id="contact_number" name="contact_number" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ $orgSetup->contact_number }}" readonly>
                        </div>

                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">11</span>Category of Withholding Agent</label>
                            <div class="flex items-center space-x-4 w-full py-2">
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="category" value="1" class="mr-2" required>Private
                                </label>
                                <label class="flex items-center text-zinc-700 text-sm">
                                    <input type="radio" name="category" value="0" class="mr-2" required>Government
                                </label>
                            </div>
                        </div>

                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="email" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">12</span>Email Address</label>
                            <input type="email" id="email" name="email" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ $orgSetup->email }}" readonly>
                        </div>

                        <!-- Tax Computation -->
                        <h3 class="font-bold text-zinc-700 text-lg mb-4">Computation of Tax</h3>

                        <table class="min-w-full indent-4 text-left text-zinc-700 text-sm mt-4 mb-4">
                            <thead>
                                <tr>
                                    <th>ATC</th>
                                    <th>Tax Base</th>
                                    <th>Tax Rate</th>
                                    <th>Tax Withheld</th>
                                </tr>
                            </thead>
                            <tbody class="pl-20 p-4 px-2">
                                @forelse($atcDetails as $detail)
                                    <tr>
                                        <td class="px-2">{{ optional($detail->atc)->tax_code ?? 'N/A' }}</td>
                                        <td class="px-2"><input type="text" value="{{ $detail->tax_base }}" readonly class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"></td>
                                        <td class="px-2"><input type="text" value="{{ $detail->tax_rate }}" readonly class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"></td>
                                        <td class="px-2"><input type="text" value="{{ $detail->tax_withheld }}" readonly class="tax-withheld block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-gray-500">No ATC details available.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Computation of Total Taxes Withheld -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="total_taxes_withheld" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">19</span>Total Taxes Withheld for the Quarter</label>
                            <input type="text" id="total_taxes_withheld" name="total_taxes_withheld" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                readonly>
                        </div>

                        <!-- Other Computations --> 
                        <!-- Remittances Made: 1st Month -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="remittances_1st_month" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">20</span>Remittances Made: 1st Month</label>
                            <input type="number" id="remittances_1st_month" name="remittances_1st_month" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ $remittanceData->remittance_1st_month ?? 0 }}" readonly />
                        </div>

                        <!-- Remittances Made: 2nd Month -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="remittances_2nd_month" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">21</span>Remittances Made: 2nd Month</label>
                            <input type="number" id="remittances_2nd_month" name="remittances_2nd_month" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                value="{{ $remittanceData->remittance_2nd_month ?? 0 }}" readonly />
                        </div>
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="remitted_previous" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">22</span>Tax Remitted in Return Previously Filed</label>
                            <input type="number" id="remitted_previous" name="remitted_previous" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="over_remittance" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">23</span>Over-remittance from Previous Quarter of the same taxable year</label>
                            <input type="number" id="over_remittance" name="over_remittance" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                        </div>
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="other_payments" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">24</span>Other Payments Made</label>
                            <input type="number" id="other_payments" name="other_payments" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>

                        <!-- Total Remittances Made -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="total_remittances_made" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">25</span>Total Remittances Made<span class="text-xs italic"> (Sum of Items 20 to 24)</span></label>
                            <input type="text" id="total_remittances_made" name="total_remittances_made" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                        </div>

                        <!-- Tax Still Due / Over-remittance -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="tax_still_due" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">26</span><strong>Tax Still Due</strong>/Over-remittance <span class="text-xs italic">(Item 19 Less Item 25)</span></label>
                            <input type="text" id="tax_still_due" name="tax_still_due" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                        </div>

                        <!-- Add Penalties -->
                        <h2 class="text-sm font-bold indent-4 text-zinc-700 my-4">Add Penalties</h2>
                        <div class="mb-2 mt-2 flex flex-row justify-between gap-4">
                            <label for="surcharge" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">27</span>Surcharge</label>
                            <input type="number" id="surcharge" name="surcharge" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="interest" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">28</span>Interest</label>
                            <input type="number" id="interest" name="interest" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="compromise" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">29</span>Compromise</label>
                            <input type="number" id="compromise" name="compromise" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>

                        <!-- Total Penalties -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="penalties" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">30</span>Total Penalties</label>
                            <input type="text" id="penalties" name="penalties" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                        </div>

                        <!-- Total Amount Due -->
                        <div class="mb-2 flex flex-row justify-between gap-4">
                            <label for="total_amount_due" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">31</span><strong>TOTAL AMOUNT STILL DUE</strong>/(Over-remittance) <span class="text-xs italic">(Sum of Items 26 and 30)</span></label>
                            <input type="text" id="total_amount_due" name="total_amount_due" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
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
