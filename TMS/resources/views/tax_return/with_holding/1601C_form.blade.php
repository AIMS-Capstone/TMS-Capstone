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
                            <p class="text-sm taxuri-color">BIR Form No. 1601-C</p>
                            <p class="font-bold text-3xl taxuri-color">Monthly Remittance Return <span class="text-lg">(of Income Taxes Withheld on Compensation)</span></p>
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
            <form action="{{ route('form1601C.store', ['id' => $withHolding->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="withholding_id" value="{{ $withHolding->id }}">

                <div class="p-8">
                    <!-- Filing Period -->
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>

                    <div class="mb-2 flex flex-row justify-between gap-96">
                        <label for="filing_period" class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">1</span>For the Month</label>
                        <input type="month" id="filing_period" name="filing_period" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                            value="{{ isset($withHolding->month, $withHolding->year) ? $withHolding->year . '-' . str_pad($withHolding->month, 2, '0', STR_PAD_LEFT) : '' }}" readonly>
                    </div>

                    <!-- Amended Return -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">2</span>Amended Return?</label>
                        <div class="flex items-center space-x-4 w-2/3 py-2">
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
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">3</span>Any Taxes Withheld?</label>
                        <div class="flex items-center space-x-4 w-2/3 py-2">
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

                    <!-- Number of Sheets -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="number_of_sheets" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">4</span>Number of Sheets Attached</label>
                        <input type="number" id="number_of_sheets" name="number_of_sheets" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" min="0" required>
                    </div>

                    <!-- Alphanumeric Tax Code -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="atc_id" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">5</span>Alphanumeric Tax Code (ATC)</label>
                        <select id="atc_id" name="atc_id" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                            <option value="" disabled selected>Select Tax Code</option>
                            @foreach ($atcs as $atc)
                                <option value="{{ $atc->id }}">{{ $atc->tax_code }} - {{ $atc->description }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Background Information -->
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
                    <!-- TIN -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="tin" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">6</span>Taxpayer Identification Number (TIN)</label>
                        <input type="text" id="tin" name="tin" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->tin }}">
                    </div>

                    <!-- RDO Code -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="rdo" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">7</span>Revenue District Office (RDO) Code</label>
                        <input type="text" id="rdo" name="rdo" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->rdo }}">
                    </div>

                    <!-- Withholding Agent's Name -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="agent_name" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">8</span>Withholding Agent's Name</label>
                        <input type="text" id="agent_name" name="agent_name" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer truncate" readonly value="{{ $orgSetup->registration_name }}">
                    </div>

                    <!-- Registered Address -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="address" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">9</span>Registered Address</label>
                        <input type="text" id="address" name="address" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->address_line }}">
                    </div>

                    <!-- Zip Code -->
                    <div class="mb-6 pl-8 flex flex-row justify-between gap-96">
                        <label for="zip_code" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">9A</span>Zip Code</label>
                        <input type="text" id="zip_code" name="zip_code" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->zip_code }}">
                    </div>

                    <!-- Contact Number -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="contact_number" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">10</span>Contact Number</label>
                        <input type="text" id="contact_number" name="contact_number" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->contact_number }}">
                    </div>

                    <!-- Category of Withholding Agent -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="agent_category" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">11</span>Category of Withholding Agent</label>
                        <select id="agent_category" name="agent_category" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                            <option value="Private" {{ $orgSetup->type === 'Private' ? 'selected' : '' }}>Private</option>
                            <option value="Government" {{ $orgSetup->type === 'Government' ? 'selected' : '' }}>Government</option>
                        </select>
                    </div>

                    <!-- Email -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="email" class="indent-4 block text-zinc-700 text-sm w-2/3"><span class="font-bold mr-2">12</span>Email Address</label>
                        <input type="email" id="email" name="email" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $orgSetup->email }}">
                    </div>

                    <!-- Tax Relief -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">13</span>Are there payees availing of tax relief under Special Law or International Tax Treaty?</label>
                        <div class="flex items-center space-x-4 py-2">
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="tax_relief" value="1" id="tax_relief_yes" class="mr-2" required> Yes
                            </label>
                            <label class="flex items-center text-zinc-700 text-sm">
                                <input type="radio" name="tax_relief" value="0" id="tax_relief_no" class="mr-2" required> No
                            </label>
                        </div>
                    </div>
                    <!-- Specify Tax Relief (conditionally displayed) -->
                    <div class="mb-6 px-8 flex flex-row justify-between gap-96" id="tax_relief_details_container" style="display: none;">
                        <label for="tax_relief_details" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">13A</span>If Yes, Please specify</label>
                        <input type="text" id="tax_relief_details" name="tax_relief_details" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" placeholder="Specify Tax Relief">
                    </div>


                    <!-- Computation of Tax -->
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Computation of Tax</h3>
                    <!-- Total Amount of Compensation -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="total_compensation" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">14</span>Total Amount of Compensation</label>
                        <input type="text" id="total_compensation" name="total_compensation" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('gross_compensation') }}">
                    </div>

                    <!-- Statutory Minimum Wage -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="statutory_minimum_wage" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">15</span>Statutory Minimum Wage for MWEs</label>
                        <input type="text" id="statutory_minimum_wage" name="statutory_minimum_wage" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('statutory_minimum_wage') }}">
                    </div>

                    <!-- Holiday Pay, Overtime Pay, Night Shift Differential, Hazard Pay -->
                    <div class="mb-2 flex flex-row justify-between">
                        <label for="holiday_overtime_hazard" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">16</span>Holiday Pay, Overtime Pay, Night Shift Differential, Hazard Pay</label>
                        <input type="text" id="holiday_overtime_hazard" name="holiday_overtime_hazard" class="py-2 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('holiday_pay') + $sources->sum('overtime_pay') + $sources->sum('night_shift_differential') + $sources->sum('hazard_pay') }}">
                    </div>

                    <!-- 13th Month Pay and Other Benefits -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="thirteenth_month_pay" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">17</span>13th Month Pay and Other Benefits</label>
                        <input type="text" id="thirteenth_month_pay" name="thirteenth_month_pay" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('month_13_pay') }}">
                    </div>

                    <!-- De Minimis Benefits -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="de_minimis_benefits" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">18</span>De Minimis Benefits</label>
                        <input type="text" id="de_minimis_benefits" name="de_minimis_benefits" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('de_minimis_benefits') }}">
                    </div>

                    <!-- Mandatory Contributions -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="mandatory_contributions" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">19</span>SSS, GSIS, PHIC, HDMF Contributions & Union Dues</label>
                        <input type="text" id="mandatory_contributions" name="mandatory_contributions" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('sss_gsis_phic_hdmf_union_dues') }}">
                    </div>

                    <!-- Other Non-Taxable Compensation -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="other_non_taxable_compensation" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">20</span>Other Non-Taxable Compensation</label>
                        <input type="text" id="other_non_taxable_compensation" name="other_non_taxable_compensation" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('other_non_taxable_compensation') }}">
                    </div>

                    <!-- Total Non-Taxable Compensation -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="total_non_taxable" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">21</span>Total Non-Taxable Compensation <span class="text-xs italic">(Sum of items 15 to 20)</span></label>
                        <input type="text" id="total_non_taxable" name="total_non_taxable" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('statutory_minimum_wage') + $sources->sum('holiday_pay') + $sources->sum('overtime_pay') + $sources->sum('night_shift_differential') + $sources->sum('hazard_pay') + $sources->sum('month_13_pay') + $sources->sum('de_minimis_benefits') + $sources->sum('sss_gsis_phic_hdmf_union_dues') + $sources->sum('other_non_taxable_compensation') }}">
                    </div>

                    <!-- Taxable Compensation -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="taxable_compensation" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">22</span>Total Taxable Compensation <span class="text-xs italic">(Item 14 Less Item 21)</span></label>
                        <input type="text" id="taxable_compensation" name="taxable_compensation" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('taxable_compensation') }}">
                    </div>

                    <!-- Less: Taxable Compensation Not Subject to Withholding Tax -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="non_withholding_taxable" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">23</span>Less: Taxable Compensation Not Subject to Withholding Tax</label>
                        <input type="text" id="non_withholding_taxable" name="non_withholding_taxable" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('taxable_compensation') <= 250000 ? $sources->sum('taxable_compensation') : 0 }}">
                    </div>

                    <!-- Net Taxable Compensation -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="net_taxable_compensation" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">24</span>Net Taxable Compensation <span class="text-xs italic">(Item 22 Less Item 23)</span></label>
                        <input type="text" id="net_taxable_compensation" name="net_taxable_compensation" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('taxable_compensation') - $sources->sum('tax_due') }}">
                    </div>

                    <!-- Total Taxes Withheld -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="total_taxes_withheld" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">25</span>Total Taxes Withheld</label>
                        <input type="text" id="total_taxes_withheld" name="total_taxes_withheld" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('tax_due') }}">
                    </div>

                    <!-- Add/Less: Adjustment of Taxes Withheld -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="adjustment_taxes_withheld" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">26</span>Add/(Less): Adjustment of Taxes Withheld from Previous Month/s</label>
                        <input type="number" id="adjustment_taxes_withheld" name="adjustment_taxes_withheld" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Taxes Withheld for Remittance -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="taxes_withheld_remittance" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">27</span>Taxes Withheld for Remittance <span class="text-xs italic">(Sum of Items 25 ans 26)</span></label>
                        <input type="text" id="taxes_withheld_remittance" name="taxes_withheld_remittance" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="{{ $sources->sum('tax_due') }}">
                    </div>

                    <!-- Less: Tax Remitted in Return Previously Filed -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="tax_remitted_return" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">28</span>Less: Tax Remitted in Return Previously Filed</label>
                        <input type="number" id="tax_remitted_return" name="tax_remitted_return" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Other Remittances Made -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="other_remittances" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">29</span>Other Remittances Made (Specify)</label>
                        <input type="number" id="other_remittances" name="other_remittances" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Total Tax Remittances Made -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="total_tax_remittances" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">30</span>Total Tax Remittances Made <span class="text-xs italic">(Item 27 Less Item 30)</span></label>
                        <input type="text" id="total_tax_remittances" name="total_tax_remittances" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer0" readonly value="0">
                    </div>

                    <!-- Tax Still Due / Over-remittance -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="tax_still_due" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">31</span><strong>Tax Still Due</strong>/(Over-remittance)</label>
                        <input type="text" id="tax_still_due" name="tax_still_due" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="0">
                    </div>

                    <!-- Add Penalties -->
                    <h5 class="indent-4 block text-zinc-700 text-sm w-full">Add Penalties</h5>
                    <div class="mb-2 px-8 flex flex-row justify-between gap-96">
                        <label for="surcharge" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">32</span>Surcharge</label>
                        <input type="number" id="surcharge" name="surcharge" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <div class="mb-2 px-8 flex flex-row justify-between gap-96">
                        <label for="interest" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">33</span>Interest</label>
                        <input type="number" id="interest" name="interest" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <div class="mb-2 px-8 flex flex-row justify-between gap-96">
                        <label for="compromise" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">34</span>Compromise</label>
                        <input type="number" id="compromise" name="compromise" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Total Penalties -->
                    <div class="mb-2 px-8 flex flex-row justify-between gap-96">
                        <label for="total_penalties" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">35</span>Total Penalties <span class="text-xs italic">(Sum of Items 32 and 34)</span></label>
                        <input type="text" id="total_penalties" name="total_penalties" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly value="0">
                    </div>

                    <!-- Total Amount Still Due / (Over-remittance) -->
                    <div class="mb-2 flex flex-row justify-between gap-80">
                        <label for="total_amount_still_due" class="indent-4 block text-zinc-700 text-sm w-full"><span class="font-bold mr-2">36</span><strong>TOTAL AMOUNT STILL DUE</strong>/(Over-remittance)</label>
                        <input type="text" id="total_amount_still_due" name="total_amount_still_due" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer0" readonly value="0">
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
