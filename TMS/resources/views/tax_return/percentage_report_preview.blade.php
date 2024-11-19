<x-app-layout>
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-8">
        <!-- Back Button -->
        <a href="#" class="text-gray-600 hover:text-gray-800 text-sm flex items-center mb-4">
            <span class="mr-2">&#8592;</span> Go back
        </a>

        <!-- Header -->
        <h1 class="text-2xl font-bold text-blue-700 mb-2">BIR Form No. 2551Q</h1>
        <h2 class="text-xl font-semibold text-gray-800">Quarterly Percentage Tax Return</h2>
        <p class="text-gray-600 mb-6">Verify the tax information below, with some fields pre-filled from your organization’s setup. Select options as needed, then click 'Proceed to Report' to generate the BIR form. Hover over icons for additional guidance on specific fields.</p>

        <!-- Filing Period Section -->
        <div class="border-b pb-6 mb-6">
            <h3 class="font-semibold text-gray-700 text-lg mb-4">Filing Period</h3>
            
            <!-- For the -->
            <form action="{{ route('tax_return.store2551Q', ['taxReturn' => $taxReturn->id]) }}" method="POST">
                @csrf
                <!-- Period -->
                <div class="mb-4 flex items-start">
                    <label class="block text-gray-700 text-sm font-medium w-1/3">For the</label>
                    <div class="flex items-center space-x-4 w-2/3">
                        <label class="flex items-center">
                            <input type="radio" name="period" value="calendar" class="mr-2" 
                                @if($period == 'calendar') checked @endif> Calendar
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="period" value="fiscal" class="mr-2" 
                                @if($period == 'fiscal') checked @endif> Fiscal
                        </label>
                    </div>
                </div>
            
                <!-- Year Ended -->
                <div class="mb-4 flex items-start">
                    <label class="block text-gray-700 text-sm font-medium w-1/3">Year Ended</label>
                    <input type="month" name="year_ended" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" value="{{ old('year_ended', $yearEndedFormatted) }}">


                </div>
            
                <!-- Quarter -->
                <div class="mb-4 flex items-start">
                    <label class="block text-gray-700 text-sm font-medium w-1/3">Quarter</label>
                    <div class="flex items-center space-x-4 w-2/3">
                        <label class="flex items-center">
                            <input type="radio" name="quarter"   @if($taxReturn->month == 'Q1') checked @endif value="1st" class="mr-2"> 1st
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="quarter" @if($taxReturn->month == 'Q2') checked @endif value="2nd" class="mr-2"> 2nd
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="quarter" @if($taxReturn->month == 'Q3') checked @endif value="3rd" class="mr-2"> 3rd
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="quarter" @if($taxReturn->month == 'Q4') checked @endif value="4th" class="mr-2"> 4th
                        </label>
                    </div>
                </div>
                        

            <!-- Amended Return? -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">Amended Return?</label>
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center">
                        <input type="radio" name="amended_return" value="yes" class="mr-2"> Yes
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="amended_return" value="no" class="mr-2"> No
                    </label>
                </div>
            </div>

            <!-- Number of Sheets Attached -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">Number of sheets attached</label>
                <input type="number" name="sheets_attached" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
        </div>

        <!-- Background Information Section -->
        <div class="border-b">
            <h3 class="font-semibold text-gray-700 text-lg mb-4">Background Information</h3>
            
            <!-- TIN -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">Taxpayer Identification Number (TIN)</label>
                <input type="text" name="tin" placeholder="000-000-000-000" value = "{{$organization->tin;}} "class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- RDO Code -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">RDO</label>
                <input type="text" name="rdo_code" placeholder="000-000-000-000" value="{{ $rdoCode; }}" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">

            </div>

            <!-- Taxpayer's Name -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">Taxpayer's Name</label>
                <input type="text" name="taxpayer_name" value="{{$organization->registration_name;}}" placeholder="e.g. Dela Cruz, Juan, Protacio" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Registered Address -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">Registered Address</label>
                <input type="text" name="registered_address" value="{{ $organization->address_line . ', ' . $organization->city . ', ' . $organization->province . ', ' . $organization->region; }}" placeholder="e.g. 145 Yakal St. ESL Bldg., San Antonio Village Makati NCR" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Zip Code -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">Zip Code</label>
                <input type="text" name="zip_code" value="{{$organization->zip_code;}}" placeholder="e.g. 1203" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
     
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Contact Number</label>
            <input type="number" name="contact_number" value="{{$organization->contact_number;}}" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Email Address</label>
            <input type="text" name="email_address"  value="{{$organization->email;}}" placeholder="pedro@gmail.com" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Are you availing of tax relief under
                Special Law or International Tax Treaty?</label>
            <div class="flex items-center space-x-4 w-2/3">
                <label class="flex items-center">
                    <input type="radio" name="tax_relief" value="yes" class="mr-2"> Yes
                </label>
                <label class="flex items-center">
                    <input type="radio" name="tax_relief" value="no" class="mr-2"> No
                </label>
            </div>
        </div>
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">If yes, specify            </label>
            <input type="text" name="yes_specify" placeholder="Specified Tax Treaty" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Only for individual taxpayers whose sales/receipts are subject to Percentage Tax under Section 116 of the Tax Code, as amended:
                What income tax rates are you availing? (choose one) <br>(To be filled out only on the initial quarter of the taxable year)</label>
            <div class="flex items-center space-x-4 w-2/3">
            
                <label class="flex items-center">
                    <input type="radio" name="availed_tax_rate" value="Graduated" class="mr-2"> Graduated income tax rate on net
                    taxable income 
                </label>
                <label class="flex items-center">
                    <input type="radio" name="availed_tax_rate" value="Flat_rate" class="mr-2"> 8% income tax rate on gross sales/receipts/others
                </label>
            </div>
        </div>
    </div>
    <div class="border-b">
        <h3 class="font-semibold text-gray-700 text-lg mb-4">Total Tax Payable</h3>
    
        <!-- Total Tax Due (Sum of tax_amount from Schedule 1) -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Total Tax Due (From Schedule 1 Item 7)</label>
            <input 
                type="number" 
                name="tax_due" 
                id="tax_due" 
                value="{{ number_format($summaryData->sum('tax_due'), 2, '.', '') }}" 
                readonly 
                class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    
        <!-- Creditable Percentage Tax Withheld -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Creditable Percentage Tax Withheld per BIR Form No. 2307</label>
            <input 
                type="number" 
                name="creditable_tax" 
                id="creditable_tax" 
                placeholder="" 
                class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    
        <!-- Tax Paid in Return Previously Filed -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Tax Paid in Return Previously Filed, if this is an Amended Return</label>
            <input 
                type="number" 
                name="amended_tax" 
                id="amended_tax" 
                placeholder="" 
                class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    
        <!-- Other Tax Credit/Payment -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Other Tax Credit/Payment (specify)</label>
            <input 
                type="text" 
                name="other_tax_specify" 
                id="other_tax_specify" 
                placeholder="" 
                class="w-1/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            <input 
                type="number" 
                name="other_tax_amount" 
                id="other_tax_amount" 
                placeholder="" 
                class="w-1/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    
        <!-- Total Tax Credits/Payments -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Total Tax Credits/Payments (Sum of Items 15 to 17)</label>
            <input 
                type="number" 
                name="total_tax_credits" 
                id="total_tax_credits" 
                readonly 
                class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    
        <!-- Tax Still Payable/(Overpayment) -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Tax Still Payable/(Overpayment) (Item 14 Less Item 18)</label>
            <input 
                type="number" 
                name="tax_still_payable" 
                id="tax_still_payable" 
                readonly 
                class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    
        <!-- Surcharge -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Surcharge</label>
            <input 
                type="number" 
                name="surcharge" 
                id="surcharge" 
                placeholder="" 
                class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    
        <!-- Interest -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Interest</label>
            <input 
                type="number" 
                name="interest" 
                id="interest" 
                placeholder="" 
                class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    
        <!-- Compromise -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Compromise</label>
            <input 
                type="number" 
                name="compromise" 
                id="compromise" 
                placeholder="" 
                class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    
        <!-- Total Penalties -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Total Penalties (Sum of Items 20 to 22)</label>
            <input 
                type="number" 
                name="total_penalties" 
                id="total_penalties" 
                readonly 
                class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    
        <!-- Total Amount Payable/(Overpayment) -->
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">TOTAL AMOUNT PAYABLE/(Overpayment) (Sum of Items 19 and 23)</label>
            <input 
                type="number" 
                name="total_amount_payable" 
                id="total_amount_payable" 
                readonly 
                class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    </div>
    
<div class="border-b">
    <h3 class="font-semibold text-gray-700 text-lg mb-4">Schedule 1</h3>

    <div class="grid grid-cols-1 gap-4">
        @foreach ($summaryData as $atcCode => $data)
            <div class="grid grid-cols-4 gap-4 items-center">
                
                <!-- ATC Code Input -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700">ATC</label>
                    <input type="text" readonly value="{{ $atcCode }}" 
                           name="schedule[{{ $atcCode }}][atc_code]" 
                           class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <!-- Taxable Amount Input -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700">Taxable Amount</label>
                    <input type="text" readonly value="{{ number_format($data['taxable_amount'], 2) }}" 
                           name="schedule[{{ $atcCode }}][taxable_amount]" 
                           class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <!-- Tax Rate Input -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700">Tax Rate</label>
                    <input type="text" readonly value="{{ number_format($data['tax_rate'], 2) }}" 
                           name="schedule[{{ $atcCode }}][tax_rate]" 
                           class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

                <!-- Tax Due Input -->
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-700">Tax Due</label>
                    <input type="text" readonly value="{{ number_format($data['tax_due'], 2) }}" 
                           name="schedule[{{ $atcCode }}][tax_due]" 
                           class="border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>

            </div>
        @endforeach
    </div>

</div>




        <!-- Submit Button -->
        <div class="mt-6">
            <button class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
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
    