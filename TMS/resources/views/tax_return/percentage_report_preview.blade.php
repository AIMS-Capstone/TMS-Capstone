<x-app-layout>
    <!-- Back Button -->
    <div class="ml-20 mt-10 flex items-center">
        <button onclick="history.back()" class="text-zinc-600 hover:text-zinc-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g>
            </svg>
            <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go Back</span>
        </button>
    </div>
    <div class="py-10">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="container mx-auto pt-4">
                    <div class="flex justify-between items-center px-10">
                        <div class="flex flex-col w-full items-start space-y-1">
                            <!-- BIR Form text on top -->
                            <p class="text-sm taxuri-color">BIR Form No. 2551Q</p>
                            <p class="font-bold text-3xl taxuri-color">Quarterly Percentage Tax Return</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-10 mb-4">
                        <div class="flex items-center">
                            <p class="taxuri-text font-normal text-sm">
                                Verify the tax information below, with some fields pre-filled from your organization’s setup. Select options as needed, then click 'Proceed to Report' to generate the BIR form. Hover over icons for additional guidance on specific fields.
                            </p>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-8">
        <!-- Filing Period Section -->
        <div class="border-b pb-6 mb-6">
            <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>
            
            <!-- For the -->
            <form action="{{ route('tax_return.store2551Q', ['taxReturn' => $taxReturn->id]) }}" method="POST">
                @csrf
                <!-- Period -->
                <div class="mb-4 flex items-start">
                    <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">1</span>For the</label>
                    <div class="flex items-center space-x-4 w-2/3">
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="period" value="calendar" class="mr-2" 
                                @if($period == 'calendar') checked @endif> Calendar
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="period" value="fiscal" class="mr-2" 
                                @if($period == 'fiscal') checked @endif> Fiscal
                        </label>
                    </div>
                </div>
            
                <!-- Year Ended -->
                <div class="mb-4 flex items-start">
                    <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">2</span>Year Ended</label>
                    <input type="month" name="year_ended" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" value="{{ old('year_ended', $yearEndedFormatted) }}">
                </div>
            
                <!-- Quarter -->
                <div class="mb-4 flex items-start">
                    <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">3</span>Quarter</label>
                    <div class="flex items-center space-x-4 w-2/3">
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter"   @if($taxReturn->month == 'Q1') checked @endif value="1st" class="mr-2"> 1st
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" @if($taxReturn->month == 'Q2') checked @endif value="2nd" class="mr-2"> 2nd
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" @if($taxReturn->month == 'Q3') checked @endif value="3rd" class="mr-2"> 3rd
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" @if($taxReturn->month == 'Q4') checked @endif value="4th" class="mr-2"> 4th
                        </label>
                    </div>
                </div>
                        

            <!-- Amended Return? -->
            <div class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">4</span>Amended Return?</label>
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="amended_return" value="yes" class="mr-2"> Yes
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="amended_return" value="no" class="mr-2"> No
                    </label>
                </div>
            </div>

            <!-- Number of Sheets Attached -->
            <div class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">5</span>Number of sheets attached</label>
                <input type="number" name="sheets_attached" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
        </div>

        <!-- Background Information Section -->
        <div class="border-b">
            <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
            
            <!-- TIN -->
            <div class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">6</span>Taxpayer Identification Number (TIN)</label>
                <input type="text" name="tin" placeholder="000-000-000-000" value = "{{$organization->tin;}} "class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>

            <!-- RDO Code -->
            <div class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">7</span>RDO</label>
                <input type="text" name="rdo_code" placeholder="000-000-000-000" value="{{ $rdoCode; }}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">

            </div>

            <!-- Taxpayer's Name -->
            <div class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">8</span>Taxpayer's Name</label>
                <input type="text" name="taxpayer_name" value="{{$organization->registration_name;}}" placeholder="e.g. Dela Cruz, Juan, Protacio" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>

            <!-- Registered Address -->
            <div class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">9</span>Registered Address</label>
                <input type="text" name="registered_address" value="{{ $organization->address_line . ', ' . $organization->city . ', ' . $organization->province . ', ' . $organization->region; }}" placeholder="e.g. 145 Yakal St. ESL Bldg., San Antonio Village Makati NCR" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>

            <!-- Zip Code -->
            <div class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">9A</span>Zip Code</label>
                <input type="text" name="zip_code" value="{{$organization->zip_code;}}" placeholder="e.g. 1203" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
     
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">10</span>Contact Number</label>
            <input type="number" name="contact_number" value="{{$organization->contact_number;}}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">11</span>Email Address</label>
            <input type="text" name="email_address"  value="{{$organization->email;}}" placeholder="pedro@gmail.com" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">12</span>Are you availing of tax relief under Special Law or International Tax Treaty?</label>
            <div class="flex items-center space-x-4 w-2/3">
                <label class="flex items-center text-zinc-700 text-sm">
                    <input type="radio" name="tax_relief" value="yes" class="mr-2"> Yes
                </label>
                <label class="flex items-center text-zinc-700 text-sm">
                    <input type="radio" name="tax_relief" value="no" class="mr-2"> No
                </label>
            </div>
        </div>
        <div class="mb-4 flex items-start">
            <label class="indent-12 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">12A</span>If yes, specify</label>
            <input type="text" name="yes_specify" placeholder="Specified Tax Treaty" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">13</span>Only for individual taxpayers whose sales/receipts are subject to Percentage Tax under Section 116 of the Tax Code, as amended:
                What income tax rates are you availing? (choose one) <br>(To be filled out only on the initial quarter of the taxable year)</label>
            <div class="flex items-center space-x-4 w-2/3">
                <label class="flex items-center text-zinc-700 text-sm">
                    <input type="radio" name="availed_tax_rate" value="Graduated" class="mr-2"> Graduated income tax rate on net
                    taxable income 
                </label>
                <label class="flex items-center text-zinc-700 text-sm">
                    <input type="radio" name="availed_tax_rate" value="Flat_rate" class="mr-2"> 8% income tax rate on gross sales/receipts/others
                </label>
            </div>
        </div>
    </div>
    <div class="border-b">
        <h3 class="font-semibold text-zinc-700 text-lg mb-4">Total Tax Payable</h3>
    
        <!-- Total Tax Due (Sum of tax_amount from Schedule 1) -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">14</span>Total Tax Due (From Schedule 1 Item 7)</label>
            <input 
                type="number" 
                name="tax_due" 
                id="tax_due" 
                value="{{ number_format($summaryData->sum('tax_due'), 2, '.', '') }}" 
                readonly 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    
        <!-- Creditable Percentage Tax Withheld -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">15</span>Creditable Percentage Tax Withheld per BIR Form No. 2307</label>
            <input 
                type="number" 
                name="creditable_tax" 
                id="creditable_tax" 
                placeholder="" 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    
        <!-- Tax Paid in Return Previously Filed -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">16</span>Tax Paid in Return Previously Filed, if this is an Amended Return</label>
            <input 
                type="number" 
                name="amended_tax" 
                id="amended_tax" 
                placeholder="" 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    
        <!-- Other Tax Credit/Payment -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">17</span>Other Tax Credit/Payment (specify)</label>
            <input 
                type="text" 
                name="other_tax_specify" 
                id="other_tax_specify" 
                placeholder="" 
                class="w-1/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            <input 
                type="number" 
                name="other_tax_amount" 
                id="other_tax_amount" 
                placeholder="" 
                class="w-1/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    
        <!-- Total Tax Credits/Payments -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">18</span>Total Tax Credits/Payments (Sum of Items 15 to 17)</label>
            <input 
                type="number" 
                name="total_tax_credits" 
                id="total_tax_credits" 
                readonly 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    
        <!-- Tax Still Payable/(Overpayment) -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">19</span>Tax Still Payable/(Overpayment) (Item 14 Less Item 18)</label>
            <input 
                type="number" 
                name="tax_still_payable" 
                id="tax_still_payable" 
                readonly 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    
        <!-- Surcharge -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">20</span>Surcharge</label>
            <input 
                type="number" 
                name="surcharge" 
                id="surcharge" 
                placeholder="" 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    
        <!-- Interest -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">21</span>Interest</label>
            <input 
                type="number" 
                name="interest" 
                id="interest" 
                placeholder="" 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    
        <!-- Compromise -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">22</span>Compromise</label>
            <input 
                type="number" 
                name="compromise" 
                id="compromise" 
                placeholder="" 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    
        <!-- Total Penalties -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">23</span>Total Penalties (Sum of Items 20 to 22)</label>
            <input 
                type="number" 
                name="total_penalties" 
                id="total_penalties" 
                readonly 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    
        <!-- Total Amount Payable/(Overpayment) -->
        <div class="mb-4 flex items-start">
            <label class="indent-4 font-bold block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">24</span>TOTAL AMOUNT PAYABLE/(Overpayment) (Sum of Items 19 and 23)</label>
            <input 
                type="number" 
                name="total_amount_payable" 
                id="total_amount_payable" 
                readonly 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
    </div>
    
<div>
    <h3 class="font-bold text-zinc-700 text-lg mb-4">Schedule 1</h3>

    <div class="grid grid-cols-1 gap-4">
        @foreach ($summaryData as $atcCode => $data)
            <div class="grid grid-cols-4 gap-4 items-center">
                
                <!-- ATC Code Input -->
                <div class="flex flex-col">
                    <label class="text-sm text-zinc-700">ATC</label>
                    <input type="text" readonly value="{{ $atcCode }}" 
                           name="schedule[{{ $atcCode }}][atc_code]" 
                           class="block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>

                <!-- Taxable Amount Input -->
                <div class="flex flex-col">
                    <label class="text-sm text-zinc-700">Taxable Amount</label>
                    <input type="text" readonly value="{{ number_format($data['taxable_amount'], 2) }}" 
                           name="schedule[{{ $atcCode }}][taxable_amount]" 
                           class="block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>

                <!-- Tax Rate Input -->
                <div class="flex flex-col">
                    <label class="text-sm text-zinc-700">Tax Rate</label>
                    <input type="text" readonly value="{{ number_format($data['tax_rate'], 2) }}" 
                           name="schedule[{{ $atcCode }}][tax_rate]" 
                           class="block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>

                <!-- Tax Due Input -->
                <div class="flex flex-col">
                    <label class="text-sm text-zinc-700">Tax Due</label>
                    <input type="text" readonly value="{{ number_format($data['tax_due'], 2) }}" 
                           name="schedule[{{ $atcCode }}][tax_due]" 
                           class="block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>

            </div>
        @endforeach
    </div>

</div>




        <!-- Submit Button -->
        <div class="flex items-center justify-center mt-6">
            <button class="w-56 bg-blue-900 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-950">
                Proceed to Report
            </button>
        </div>
    </form>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const creditableTax = document.getElementById('creditable_tax');
        const amendedTax = document.getElementById('amended_tax');
        const otherTaxAmount = document.getElementById('other_tax_amount');
        const totalTaxCredits = document.getElementById('total_tax_credits');
        const taxDue = document.getElementById('tax_due');
        const taxStillPayable = document.getElementById('tax_still_payable');
        const surcharge = document.getElementById('surcharge');
        const interest = document.getElementById('interest');
        const compromise = document.getElementById('compromise');
        const totalPenalties = document.getElementById('total_penalties');
        const totalAmountPayable = document.getElementById('total_amount_payable');
    
        function calculateTotalCredits() {
            const total = 
                (parseFloat(creditableTax.value) || 0) + 
                (parseFloat(amendedTax.value) || 0) + 
                (parseFloat(otherTaxAmount.value) || 0);
            totalTaxCredits.value = total.toFixed(2);
            calculateTaxStillPayable();
        }
    
        function calculateTaxStillPayable() {
            const due = parseFloat(taxDue.value) || 0;
            const credits = parseFloat(totalTaxCredits.value) || 0;
            const stillPayable = due - credits;
            taxStillPayable.value = stillPayable.toFixed(2);
            calculateTotalAmountPayable();
        }
    
        function calculateTotalPenalties() {
            const penalties = 
                (parseFloat(surcharge.value) || 0) + 
                (parseFloat(interest.value) || 0) + 
                (parseFloat(compromise.value) || 0);
            totalPenalties.value = penalties.toFixed(2);
            calculateTotalAmountPayable();
        }
    
        function calculateTotalAmountPayable() {
            const stillPayable = parseFloat(taxStillPayable.value) || 0;
            const penalties = parseFloat(totalPenalties.value) || 0;
            totalAmountPayable.value = (stillPayable + penalties).toFixed(2);
        }
    
        // Add event listeners for manual input fields
        [creditableTax, amendedTax, otherTaxAmount].forEach(field => {
            field.addEventListener('input', calculateTotalCredits);
        });
    
        [surcharge, interest, compromise].forEach(field => {
            field.addEventListener('input', calculateTotalPenalties);
        });
    });
    </script>
    