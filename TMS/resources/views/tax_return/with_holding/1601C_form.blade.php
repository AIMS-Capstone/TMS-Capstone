<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 border-white bg-white shadow-sm sm:rounded-lg">
                <h1 class="text-lg font-semibold text-sky-900">BIR Form No. 1601-C</h1>
                <h2 class="text-2xl font-semibold text-sky-900 mt-2">Monthly Remittance Return  (of Income Taxes Withheld on Compensation)</h2>

                <p class="text-sm text-sky-900 mt-4">
                    Verify the tax information below, with some fields pre-filled from your organization's setup.
                    Select options as needed, then click 'Proceed to Report' to generate the BIR form. Hover over
                    icons for additional guidance on specific fields.
                </p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <form action="{{ route('form1601C.store', ['id' => $withHolding->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="withholding_id" value="{{ $withHolding->id }}">
    
                    <div class="p-8">
                        <!-- Filing Period -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="filing_period" class="block text-sm font-medium text-gray-700">Filing Period (MM/YYYY)</label>
                            <input 
                                type="month" 
                                id="filing_period" 
                                name="filing_period" 
                                class="mt-1 p-2 block w-full border border-gray-300 rounded-md" 
                                value="{{ isset($withHolding->month, $withHolding->year) ? $withHolding->year . '-' . str_pad($withHolding->month, 2, '0', STR_PAD_LEFT) : '' }}" 
                                readonly
                            >
                        </div>

                        <!-- Amended Return -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label class="block text-sm font-medium text-gray-700">Amended Return?</label>
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

                        <!-- Number of Sheets -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="number_of_sheets" class="block text-sm font-medium text-gray-700">Number of Sheets Attached</label>
                            <input type="number" id="number_of_sheets" name="number_of_sheets" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" min="0" required>
                        </div>

                        <!-- Alphanumeric Tax Code -->
                        <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                            <label for="atc_id" class="block text-sm font-medium text-gray-700">Alphanumeric Tax Code (ATC)</label>
                            <select id="atc_id" name="atc_id" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                                <option value="" disabled selected>Select Tax Code</option>
                                @foreach ($atcs as $atc)
                                    <option value="{{ $atc->id }}">{{ $atc->tax_code }} - {{ $atc->description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Background Information -->
                        <h2 class="text-lg font-semibold text-gray-800 mt-8">Background Information</h2>
                            <!-- TIN -->
                            <div class="mt-6 mb-6 px-8 flex flex-row justify-between gap-96">
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

                            <!-- Category of Withholding Agent -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="agent_category" class="block text-sm font-medium text-gray-700">Category of Withholding Agent</label>
                                <select id="agent_category" name="agent_category" class="mt-1 block w-full border border-gray-300 rounded-md" required>
                                    <option value="Private" {{ $orgSetup->type === 'Private' ? 'selected' : '' }}>Private</option>
                                    <option value="Government" {{ $orgSetup->type === 'Government' ? 'selected' : '' }}>Government</option>
                                </select>
                            </div>

                            <!-- Email -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" id="email" name="email" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $orgSetup->email }}">
                            </div>

                            <!-- Tax Relief -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-48  ">
                                <label class="block text-sm font-medium text-gray-700">Are there payees availing of tax relief under Special Law or International Tax Treaty?</label>
                                <div class="mt-2 space-x-2  ">
                                    <label>
                                        <input type="radio" name="tax_relief" value="1" id="tax_relief_yes" required> Yes
                                    </label>
                                    <label>
                                        <input type="radio" name="tax_relief" value="0" id="tax_relief_no" required> No
                                    </label>
                                </div>

                                <!-- Specify Tax Relief (conditionally displayed) -->
                                <div class="mb-6 px-8 flex flex-row justify-between gap-96" id="tax_relief_details_container" style="display: none;">
                                    <label for="tax_relief_details" class="block text-sm font-medium text-gray-700">If Yes, Please specify</label>
                                    <input type="text" id="tax_relief_details" name="tax_relief_details" class="mt-1 p-2 block w-full border border-gray-300 rounded-md" placeholder="Specify Tax Relief">
                                </div>
                            </div>


                        <!-- Computation of Tax -->
                        <h2 class="text-lg font-semibold text-gray-800 mt-8">Computation of Tax</h2>
                            <!-- Total Amount of Compensation -->
                            <div class="mt-6 mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="total_compensation" class="block text-sm font-medium text-gray-700">Total Amount of Compensation</label>
                                <input type="text" id="total_compensation" name="total_compensation" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('gross_compensation') }}">
                            </div>

                            <!-- Statutory Minimum Wage -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="statutory_minimum_wage" class="block text-sm font-medium text-gray-700">Statutory Minimum Wage for MWEs</label>
                                <input type="text" id="statutory_minimum_wage" name="statutory_minimum_wage" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('statutory_minimum_wage') }}">
                            </div>

                            <!-- Holiday Pay, Overtime Pay, Night Shift Differential, Hazard Pay -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="holiday_overtime_hazard" class="block text-sm font-medium text-gray-700">Holiday Pay, Overtime Pay, Night Shift Differential, Hazard Pay</label>
                                <input type="text" id="holiday_overtime_hazard" name="holiday_overtime_hazard" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('holiday_pay') + $sources->sum('overtime_pay') + $sources->sum('night_shift_differential') + $sources->sum('hazard_pay') }}">
                            </div>

                            <!-- 13th Month Pay and Other Benefits -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="thirteenth_month_pay" class="block text-sm font-medium text-gray-700">13th Month Pay and Other Benefits</label>
                                <input type="text" id="thirteenth_month_pay" name="thirteenth_month_pay" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('month_13_pay') }}">
                            </div>

                            <!-- De Minimis Benefits -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="de_minimis_benefits" class="block text-sm font-medium text-gray-700">De Minimis Benefits</label>
                                <input type="text" id="de_minimis_benefits" name="de_minimis_benefits" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('de_minimis_benefits') }}">
                            </div>

                            <!-- Mandatory Contributions -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="mandatory_contributions" class="block text-sm font-medium text-gray-700">SSS, GSIS, PHIC, HDMF Contributions & Union Dues</label>
                                <input type="text" id="mandatory_contributions" name="mandatory_contributions" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('sss_gsis_phic_hdmf_union_dues') }}">
                            </div>

                            <!-- Other Non-Taxable Compensation -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="other_non_taxable_compensation" class="block text-sm font-medium text-gray-700">Other Non-Taxable Compensation</label>
                                <input type="text" id="other_non_taxable_compensation" name="other_non_taxable_compensation" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('other_non_taxable_compensation') }}">
                            </div>

                            <!-- Total Non-Taxable Compensation -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="total_non_taxable" class="block text-sm font-medium text-gray-700">Total Non-Taxable Compensation</label>
                                <input type="text" id="total_non_taxable" name="total_non_taxable" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('statutory_minimum_wage') + $sources->sum('holiday_pay') + $sources->sum('overtime_pay') + $sources->sum('night_shift_differential') + $sources->sum('hazard_pay') + $sources->sum('month_13_pay') + $sources->sum('de_minimis_benefits') + $sources->sum('sss_gsis_phic_hdmf_union_dues') + $sources->sum('other_non_taxable_compensation') }}">
                            </div>

                            <!-- Taxable Compensation -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="taxable_compensation" class="block text-sm font-medium text-gray-700">Taxable Compensation</label>
                                <input type="text" id="taxable_compensation" name="taxable_compensation" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('taxable_compensation') }}">
                            </div>

                            <!-- Less: Taxable Compensation Not Subject to Withholding Tax -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="non_withholding_taxable" class="block text-sm font-medium text-gray-700">Less: Taxable Compensation Not Subject to Withholding Tax</label>
                                <input type="text" id="non_withholding_taxable" name="non_withholding_taxable" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('taxable_compensation') <= 250000 ? $sources->sum('taxable_compensation') : 0 }}">
                            </div>

                            <!-- Net Taxable Compensation -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="net_taxable_compensation" class="block text-sm font-medium text-gray-700">Net Taxable Compensation</label>
                                <input type="text" id="net_taxable_compensation" name="net_taxable_compensation" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('taxable_compensation') - $sources->sum('tax_due') }}">
                            </div>

                            <!-- Total Taxes Withheld -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="total_taxes_withheld" class="block text-sm font-medium text-gray-700">Total Taxes Withheld</label>
                                <input type="text" id="total_taxes_withheld" name="total_taxes_withheld" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('tax_due') }}">
                            </div>

                            <!-- Add/Less: Adjustment of Taxes Withheld -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="adjustment_taxes_withheld" class="block text-sm font-medium text-gray-700">Add/(Less): Adjustment of Taxes Withheld from Previous Month/s</label>
                                <input type="number" id="adjustment_taxes_withheld" name="adjustment_taxes_withheld" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>

                            <!-- Taxes Withheld for Remittance -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="taxes_withheld_remittance" class="block text-sm font-medium text-gray-700">Taxes Withheld for Remittance</label>
                                <input type="text" id="taxes_withheld_remittance" name="taxes_withheld_remittance" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="{{ $sources->sum('tax_due') }}">
                            </div>

                            <!-- Less: Tax Remitted in Return Previously Filed -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="tax_remitted_return" class="block text-sm font-medium text-gray-700">Less: Tax Remitted in Return Previously Filed</label>
                                <input type="number" id="tax_remitted_return" name="tax_remitted_return" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>

                            <!-- Other Remittances Made -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="other_remittances" class="block text-sm font-medium text-gray-700">Other Remittances Made (Specify)</label>
                                <input type="number" id="other_remittances" name="other_remittances" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>

                            <!-- Total Tax Remittances Made -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="total_tax_remittances" class="block text-sm font-medium text-gray-700">Total Tax Remittances Made</label>
                                <input type="text" id="total_tax_remittances" name="total_tax_remittances" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="0">
                            </div>

                            <!-- Tax Still Due / Over-remittance -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="tax_still_due" class="block text-sm font-medium text-gray-700">Tax Still Due / (Over-remittance)</label>
                                <input type="text" id="tax_still_due" name="tax_still_due" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="0">
                            </div>

                            <!-- Add Penalties -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="surcharge" class="block text-sm font-medium text-gray-700">Surcharge</label>
                                <input type="number" id="surcharge" name="surcharge" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>

                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="interest" class="block text-sm font-medium text-gray-700">Interest</label>
                                <input type="number" id="interest" name="interest" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>

                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="compromise" class="block text-sm font-medium text-gray-700">Compromise</label>
                                <input type="number" id="compromise" name="compromise" class="mt-1 p-2 block w-full border border-gray-300 rounded-md">
                            </div>

                            <!-- Total Penalties -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="total_penalties" class="block text-sm font-medium text-gray-700">Total Penalties</label>
                                <input type="text" id="total_penalties" name="total_penalties" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="0">
                            </div>

                            <!-- Total Amount Still Due / (Over-remittance) -->
                            <div class="mb-6 px-8 flex flex-row justify-between gap-96">
                                <label for="total_amount_still_due" class="block text-sm font-medium text-gray-700">Total Amount Still Due / (Over-remittance)</label>
                                <input type="text" id="total_amount_still_due" name="total_amount_still_due" class="mt-1 p-2 block w-full border border-gray-300 rounded-md bg-gray-100" readonly value="0">
                            </div>

                        <div class="mt-8 flex justify-center items-center">
                            <button type="submit" class="px-4 py-2 bg-sky-900 text-white rounded-md hover:bg-sky-600">
                                Proceed to Report
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show or hide "Specify Tax Relief" input based on selection
        document.addEventListener("DOMContentLoaded", () => {
            const taxReliefYes = document.getElementById("tax_relief_yes");
            const taxReliefNo = document.getElementById("tax_relief_no");
            const taxReliefDetailsContainer = document.getElementById("tax_relief_details_container");

            taxReliefYes.addEventListener("change", () => {
                if (taxReliefYes.checked) {
                    taxReliefDetailsContainer.style.display = "block";
                }
            });

            taxReliefNo.addEventListener("change", () => {
                if (taxReliefNo.checked) {
                    taxReliefDetailsContainer.style.display = "none";
                }
            });
        });

        // Calculate totals dynamically
        document.addEventListener("DOMContentLoaded", () => {
            const calculateTotals = () => {
                const totalTaxesWithheld = parseFloat(document.getElementById("total_taxes_withheld").value) || 0;
                const adjustmentTaxes = parseFloat(document.getElementById("adjustment_taxes_withheld").value) || 0;
                const taxRemitted = parseFloat(document.getElementById("tax_remitted_return").value) || 0;
                const otherRemittances = parseFloat(document.getElementById("other_remittances").value) || 0;
                const surcharge = parseFloat(document.getElementById("surcharge").value) || 0;
                const interest = parseFloat(document.getElementById("interest").value) || 0;
                const compromise = parseFloat(document.getElementById("compromise").value) || 0;

                // Remittance calculation
                const remittance = totalTaxesWithheld + adjustmentTaxes - taxRemitted - otherRemittances;

                // Total penalties
                const totalPenalties = surcharge + interest + compromise;

                // Update totals
                document.getElementById("total_tax_remittances").value = (taxRemitted + otherRemittances).toFixed(2);
                document.getElementById("tax_still_due").value = remittance.toFixed(2);
                document.getElementById("total_penalties").value = totalPenalties.toFixed(2);
                document.getElementById("total_amount_still_due").value = (remittance + totalPenalties).toFixed(2);
            };

            // Add event listeners to recalculate totals when inputs change
            document.querySelectorAll("input").forEach((input) => {
                input.addEventListener("input", calculateTotals);
            });
        });
    </script>
</x-app-layout>
