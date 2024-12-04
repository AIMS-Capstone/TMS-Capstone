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
                            <p class="text-sm taxuri-color">BIR Form No. 2550Q</p>
                            <p class="font-bold text-3xl taxuri-color">Quarterly Value-Added Tax (VAT) Return</p>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-10 mb-4">
                        <div class="flex items-center">
                            <p class="taxuri-text font-normal text-sm">
                                Fill out and verify tax information below. Select the necessary options and ensure all entries are accurate. When ready, click 'Proceed to Report' to generate the finalized BIR form. Hover over icons for additional guidance on each field.
                            </p>
                        </div>
                    </div>  
                </div>
            </div>
        </div>
    </div>
    <div class="max-w-6xl mx-auto bg-white shadow-sm rounded-lg p-8">
        
        <!-- Filing Period Section -->
        <div class="mb-6">
            <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>
            
            <!-- For the -->
            <form action="{{ route('tax_return.store2550Q', ['taxReturn' => $taxReturn->id]) }}" method="POST">
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
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">4</span>Return Period (From-To)</label>
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="date" name="return_from" class="mr-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"> 
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="date" name="return_to" class="mr-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"> 
                    </label>
                </div>
            </div>

            <!-- Amended Return? -->
            <div class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">5</span>Amended Return?</label>
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
             <!-- Amended Return? -->
             <div class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">6</span>Short Period Return?</label>
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="short_period_return" value="yes" class="mr-2"> Yes
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="short_period_return" value="no" class="mr-2"> No
                    </label>
                </div>
            </div>

        <!-- Background Information Section -->
        <div class="border-b">
            <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
            
            <!-- TIN -->
            <div class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">7</span>Taxpayer Identification Number (TIN)</label>
                <input type="text" name="tin" placeholder="000-000-000-000" value = "{{$organization->tin;}} "class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>

            <!-- RDO Code -->
            <div class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">8</span>RDO</label>
                <input type="text" name="rdo_code" placeholder="000-000-000-000" value="{{ $rdoCode; }}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">

            </div>

            <!-- Taxpayer's Name -->
            <div class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">9</span>Taxpayer's Name</label>
                <input type="text" name="taxpayer_name" value="{{$organization->registration_name;}}" placeholder="e.g. Dela Cruz, Juan, Protacio" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>

            <!-- Registered Address -->
            <div class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">10</span>Registered Address</label>
                <input type="text" name="registered_address" value="{{ $organization->address_line . ', ' . $organization->city . ', ' . $organization->province . ', ' . $organization->region; }}" placeholder="e.g. 145 Yakal St. ESL Bldg., San Antonio Village Makati NCR" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>

            <!-- Zip Code -->
            <div class="mb-2 flex items-start">
                <label class="indent-12 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">10A</span>Zip Code</label>
                <input type="text" name="zip_code" value="{{$organization->zip_code;}}" placeholder="e.g. 1203" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
     
            <div class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">11</span>Contact Number</label>
                <input type="text" name="contact_number" value="{{$organization->contact_number;}}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">12</span>Email Address</label>
                <input type="text" name="email_address"  value="{{$organization->email;}}" placeholder="pedro@gmail.com" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">13</span>Taxpayer Classification</label>
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="taxpayer_classification" value="Micro" class="mr-2"> Micro
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="taxpayer_classification" value="Small" class="mr-2"> Small
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="taxpayer_classification" value="Medium" class="mr-2"> Medium
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="taxpayer_classification" value="Large" class="mr-2"> Large
                    </label>
                </div>
            </div>
            <div class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">14</span>Are you availing of tax relief under
                    Special Law or International Tax Treaty?</label>
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="tax_relief" value="yes" class="mr-2"> Yes
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="tax_relief" value="no" class="mr-2"> No
                    </label>
                </div>
            </div>
        <div class="mb-2 flex items-start">
            <label class="indent-12 block text-zinc-700 text-sm w-1/3"><span class="font-bold mr-2">14A</span>If yes, specify</label>
            <input type="text" name="yes_specify" placeholder="Specified Tax Treaty" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>

        <div class="border-b">
            <h3 class="font-bold text-zinc-700 text-lg mb-4">Total Tax Payables</h3>
            <div class="grid grid-cols-3 gap-4 border-t mb-4 border-zinc-300 pt-2">
                <!-- Header Row -->
               
        
        <!-- VATable Sales -->
        <div class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">15</span>Net VAT Payable/(Excess Input Tax) (From Part IV, Item 61) 
            </label>
        </div>
        <div>
        
        </div>
        <div>
            
            <input 
                type="text"
                id="net_vat_payable" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
        </div>
        <div class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">16</span>Creditable VAT Withheld (From Part V - Schedule 3, Column D) .

            </label>
        </div>
        <div>
        
        </div>
        <div>
            <input 
                type="text" 
                name="creditable_vat_withheld" 
                id="creditable_vat_withheld" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        <div class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">17</span>Advance VAT Payments (From Part V - Schedule 4) 


            </label>
        </div>
        <div>
        
        </div>
        <div>
            
            <input 
                type="text" 
                name="advance_vat_payment" 
                id="advance_vat_payment" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
        </div>
        <div class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">18</span>VAT paid in return previously filed, if this is an amended return 

            </label>
        </div>
        <div>
        
        </div>
        <div>
            <input 
                type="text" 
                name="vat_paid_if_amended" 
                id="vat_paid_if_amended" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
        </div>
        <div class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">19</span>Other Credits/Payment (Specify)</label>
        </div>
        <div>
            <input 
                type="text" 
                name="other_credits_specify" 
                id="other_credits_specify" 
            
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        <div>
            <input 
                type="text" 
                name="other_credits_specify_amount" 
                id="other_credits_specify_amount" 
            
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        <div class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">20</span>Total Tax Credits/Payment (Sum of Items 16 to 19)</label>
        </div>
        <div>
        </div>
        <div>
            
            <input 
                type="text" 
                name="total_tax_credits" 
                id="total_tax_credits" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
        </div>
        <div class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">21</span>Tax Still Payable/(Excess Credits) (Item 15 Less Item 20)</label>
        </div>
        <div>
        
        </div>
        <div>
            
            <input 
                type="text" 
                name="tax_still_payable" 
                id="tax_still_payable" 
        
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
        </div>
        <div class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm"> Add: Penalties<br><span class="font-bold mr-2">22</span>Surcharge</label>
        </div>
        <div>
        </div>
        <div>
            <input 
                type="text" 
                name="surcharge" 
                id="surcharge" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        <div class="flex items-center">
            <label class="indent-4 block text-zinc-700 text-sm"><span class="font-bold mr-2">23</span>Interest</label>
        </div>
        <div>
        </div>
        <div>
            <input 
                type="text" 
                name="interest" 
                id="interest" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        <div class="flex items-center">
            <label class="indent-4 block text-zinc-700 text-sm"><span class="font-bold mr-2">24</span>Compromise</label>
        </div>
        <div>
        </div>
        <div>
            <input 
                type="text" 
                name="compromise" 
                id="compromise"
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        <div class="flex items-center">
            <label class="indent-4 block text-zinc-700 text-sm"><span class="font-bold mr-2">25</span>Total Penalties (Sum of Items 22 to 24)</label>
        </div>
        <div>
        </div>
        <div>
            <input 
                type="text" 
                name="total_penalties" 
                id="total_penalties" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        <div class="flex items-center">
            <label class="indent-4 block text-zinc-700 text-sm"><span class="font-bold mr-2">26</span>TOTAL AMOUNT PAYABLE/(Excess Credits) (Sum of Items 21 and 25)</label>
        </div>
        <div>
        </div>
        <div>
            <input 
                type="text" 
                name="total_amount_payable" 
                id="total_amount_payable" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
    
    </div>
    
    <div class="border-t">
        <h3 class="font-bold text-zinc-700 text-lg my-4">Details of VAT Computation</h3>
        <div class="grid grid-cols-3 gap-4 pt-2">
            <!-- Header Row -->
            <div class="font-bold text-zinc-700 text-sm">Total Sales and Output Tax</div>
            <div class="font-bold text-zinc-700 text-sm">A. Sales for the Quarter (Exclusive of VAT)</div>
            <div class="font-bold text-zinc-700 text-sm">B. Output Tax for the Quarter</div>
    
            <!-- VATable Sales -->
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">31</span>VATable Sales</label>
            </div>
            <div>
                <input 
                    type="text" 
                    name="vatable_sales" 
                    id="vatable_sales" 
                    value="{{ $vatOnSalesGoods + $vatOnSalesServices + $salesToGovernmentGoods + $salesToGovernmentServices }}" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            <div>
                <input 
                    type="text" 
                    name="vatable_sales_tax_amount" 
                    id="vatable_sales_tax_amount" 
                    value="{{ $vatOnSalesGoodsTax + $vatOnSalesServicesTax + $salesToGovernmentGoodsTax + $salesToGovernmentServicesTax }}" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>

            <!-- Zero-Rated Sales -->
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">32</span>Zero-Rated Sales</label>
            </div>
            <div>
                <input 
                    type="text" 
                    name="zero_rated_sales" 
                    id="zero_rated_sales" 
                    value="{{ $zeroRatedSalesGoods + $zeroRatedSalesServices }}" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            <div>
            </div>
            <!-- Exempt Sales -->
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">33</span>Exempt Sales</label>
            </div>
            <div>
                <input 
                    type="text" 
                    name="exempt_sales" 
                    id="exempt_sales" 
                    value="{{ $taxExemptSalesGoods + $taxExemptSalesServices }}" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            <div>
            </div>

            <!-- Total Sales & Output Tax Due -->
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">34</span>Total Sales & Output Tax Due (Sum of Items 31A to 33A) / (Item 31B)</label>
            </div>
            <div>
                <input 
                    type="text" 
                    name="total_sales" 
                    id="total_sales" 
                    readonly 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div>
                <input 
                    type="text" 
                    name="total_output_tax" 
                    id="total_output_tax" 
                    readonly 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <!-- Less: Output VAT on Uncollected Receivables -->
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">35</span>Less: Output VAT on Uncollected Receivables</label>
            </div>
            <div></div>
            <div>
                <input 
                    type="text" 
                    name="uncollected_receivable_vat" 
                    id="uncollected_receivable_vat" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateAdjustedOutputTax()">
            </div>

            <!-- Add: Output VAT on Recovered Uncollected Receivables Previously Deducted -->
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">36</span>Add: Output VAT on Recovered Uncollected Receivables Previously Deducted</label>
            </div>
            <div></div>
            <div>
                <input 
                    type="text" 
                    name="recovered_uncollected_receivables" 
                    id="recovered_uncollected_receivables" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateAdjustedOutputTax()">
            </div>

            <!-- Total Adjusted Output Tax Due -->
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">37</span>Total Adjusted Output Tax Due (Item 34B Less Item 35B Add Item 36B)</label>
            </div>
            <div></div>
            <div>
                <input 
                    type="text" 
                    name="total_adjusted_output_tax" 
                    id="total_adjusted_output_tax" 
                    readonly 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>

                <div class="indent-4 font-semibold text-zinc-700 text-sm">Less: Allowable Input Tax</div>
                <div class="font-semibold text-zinc-700 text-sm"></div>
                <div class="font-semibold text-zinc-700 text-sm">B. Input Tax</div>
                <!-- 38 Input Tax Carried Over from Previous Quarter -->
                <div class="flex items-center">
                    <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">38</span>Input Tax Carried Over from Previous Quarter</label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="input_carried_over" 
                        id="input_carried_over" 
                        readonly 
                        value="{{ $excessInputTax ?? '0' }}" 
                        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                <!-- 39 Input Tax Deferred on Capital Goods -->
                <div class="flex items-center">
                    <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">39</span>Input Tax Deferred on Capital Goods Exceeding P1 Million from Previous Quarter (From Part V - Schedule 1 Col E)</label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="input_tax_deferred" 
                        id="input_tax_deferred"  
                        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                <!-- 40 Transitional Input Tax -->
                <div class="flex items-center">
                    <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">40</span>Transitional Input Tax</label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="transitional_input_tax" 
                        id="transitional_input_tax" 
                        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                <!-- 41 Presumptive Input Tax -->
                <div class="flex items-center">
                    <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">41</span>Presumptive Input Tax</label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="presumptive_input_tax" 
                        id="presumptive_input_tax" 
                        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                <!-- 42 Others -->
                <div class="flex items-center">
                    <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">42</span>Others (specify)</label>
                </div>
                <div>
                    <input 
                        type="text" 
                        name="other_specify" 
                        id="other_specify" 
                        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                <div>
                    <input 
                        type="text" 
                        name="other_input_tax" 
                        id="other_input_tax" 
                        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                <!-- 43 Total Input Tax -->
                <div class="flex items-center">
                    <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">43</span>Total (Sum of Items 38B to 42B)</label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="total_input_tax" 
                        id="total_input_tax" 
                        readonly 
                        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
             <!-- Empty Cell for Alignment -->
             <div class="font-semibold text-zinc-700 text-sm">Current Transactions</div>
             <div class="font-semibold text-zinc-700 text-sm">A. Purchases</div>
             <div class="font-semibold text-zinc-700 text-sm">B. Input Tax</div>
             
             <!-- 44. Domestic Purchases (Dynamic Field) -->
             <div class="flex items-center">
                 <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">44</span>Domestic Purchases</label>
             </div>
             <div>
                 <input 
                     type="text" 
                     name="domestic_purchase" 
                     id="domestic_purchase" 
                     value="{{ $vatOnPurchasesGoods + $vatOnPurchasesServices }}" 
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 44B. Domestic Purchases Input Tax (Dynamic Field) -->
             <div>
                 <input 
                     type="text" 
                     name="domestic_purchase_input_tax" 
                     id="domestic_purchase_input_tax" 
                     value="{{ $vatOnPurchasesGoodsTax + $vatOnPurchasesServicesTax }}"  
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 45. Services Rendered by Non-Residents (Dynamic Field) -->
             <div class="flex items-center">
                 <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">45</span>Services Rendered by Non-Residents</label>
             </div>
             <div>
                 <input 
                     type="text" 
                     name="services_non_resident" 
                     id="services_non_resident" 
                     value="{{ $servicesByNonResidents }}"  
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 45B. Services Non-Resident Input Tax (Dynamic Field) -->
             <div>
                 <input 
                     type="text" 
                     name="services_non_resident_input_tax" 
                     id="services_non_resident_input_tax" 
                     value="{{ $servicesByNonResidentsTax }}"  
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 46. Importations (Dynamic Field) -->
             <div class="flex items-center">
                 <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">46</span>Importations</label>
             </div>
             <div>
                 <input 
                     type="text" 
                     name="importations" 
                     id="importations" 
                     value="{{ $importationOfGoods }}"  
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 46B. Importations Input Tax (Dynamic Field) -->
             <div>
                 <input 
                     type="text" 
                     name="importations_input_tax" 
                     id="importations_input_tax" 
                     value="{{ $importationOfGoodsTax }}"  
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 47. Others (Specify) (Manual Input Field) -->
             <div class="flex items-center">
                 <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">47</span>Others (Specify)</label>
                 <input 
                     type="text" 
                     name="purchases_others_specify" 
                     id="purchases_others_specify" 
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 47B. Others (Specify Amount) (Manual Input Field) -->
             <div>
                 <input 
                     type="text" 
                     name="purchases_others_specify_amount" 
                     id="purchases_others_specify_amount" 
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 47C. Others (Specify Input Tax) (Manual Input Field) -->
             <div>
                 <input 
                     type="text" 
                     name="purchases_others_specify_input_tax" 
                     id="purchases_others_specify_input_tax" 
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 48. Domestic Purchases with No Input Tax (Dynamic Field) -->
             <div class="flex items-center">
                 <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">48</span>Domestic Purchases with No Input Tax</label>
             </div>
             <div>
                 <input 
                     type="text" 
                     name="domestic_no_input" 
                     id="domestic_no_input" 
                     value="{{ $goodsNotQualifiedForInputTax }}"  
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             <div> 
             </div>
             <!-- 50. Total Current Purchases/Input Tax (Calculated Field) -->
             <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">50</span>Total Current Purchases/Input Tax (Sum of Items 44A to 49A)/(Sum of Items 44B to 47B)</label>
             </div>
             <div>
                 <input 
                     type="text" 
                     name="total_current_purchase" 
                     id="total_current_purchase" 
                     readonly 
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
             </div>
             
             <div>
                 <input 
                     type="text" 
                     name="total_current_purchase_input_tax" 
                     id="total_current_purchase_input_tax" 
                     readonly 
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
             </div>
             
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">51</span>Total Available Input Tax (Sum of Items 43B and 50B)</label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="total_available_input_tax" 
                    id="total_available_input_tax" 
                    readonly 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>

            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">52</span>Input Tax on Purchases/Importation of Capital Goods exceeding P1 Million deferred for the succeeding period
                    (From Part V Schedule 1, Column I)</label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="importation_million_deferred_input_tax" 
                    id="importation_million_deferred_input_tax" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">53</span>Input Tax Attributable to VAT Exempt Sales (From Part V - Schedule 2)</label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="attributable_vat_exempt_input_tax" 
                    id="attributable_vat_exempt_input_tax" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">54</span>VAT Refund/TCC Claimed</label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="vat_refund_input_tax" 
                    id="vat_refund_input_tax" 
    
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">55</span>Input VAT on Unpaid Payables</label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="unpaid_payables_input_tax" 
                    id="unpaid_payables_input_tax" 
              
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">56</span>Others (specify)</label>
            </div>
            <div>
                <input 
                type="text" 
                name="other_deduction_specify" 
                id="other_deduction_specify" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
        
            <div>
                <input 
                    type="text" 
                    name="other_deduction_specify_input_tax" 
                    id="other_deduction_specify_input_tax" 
            
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">57</span>Total Deductions from Input Tax (Sum of Items 52B to 56B)</label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="total_deductions_input_tax" 
                    id="total_deductions_input_tax" 
                    readonly 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">58</span>Add: Input VAT on Settled Unpaid Payables Previously Deducted</label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="settled_unpaid_input_tax" 
                    id="settled_unpaid_input_tax" 
                     
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">59</span>Adjusted Deductions from Input Tax (Sum of Items 57B and 58B)</label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="adjusted_deductions_input_tax" 
                    id="adjusted_deductions_input_tax" 
                    readonly 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">60</span>Total Allowable Input Tax (Item 51B Less Item 59B)</label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="total_allowable_input_Tax" 
                    id="total_allowable_input_Tax" 
                    readonly 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            <div class="flex items-center">
                <label class="block text-zinc-700 text-sm"><span class="font-bold mr-2">61</span>Net VAT Payable/(Excess Input Tax) (Item 37B Less Item 60B) (To Part II, Item 15)</label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="excess_input_tax" 
                    id="excess_input_tax" 
                    readonly 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
        </div>
        {{-- <div class="border-t border-zinc-300 py-4">
            <h3 class="font-semibold text-zinc-700 text-lg mb-4">Part V – Schedules</h3>
        
            <!-- Schedule 1: Amortized Input Tax from Capital Goods -->
            <div>
                <h4 class="font-semibold text-zinc-700 text-base mb-2">Schedule 1 – Amortized Input Tax from Capital Goods</h4>
                <table class="w-full table-auto border border-zinc-300 text-sm">
                    <thead>
                        <tr class="bg-zinc-200 text-zinc-700">
                            <th class="border px-2 py-1">Date Purchased/ Imported (MM/DD/YYYY)</th>
                            <th class="border px-2 py-1">Source Code*</th>
                            <th class="border px-2 py-1">Description</th>
                            <th class="border px-2 py-1">Amount of Purchases/ Importation of Capital Goods Exceeding P1 M</th>
                            <th class="border px-2 py-1">Estimated Life (in months)</th>
                            <th class="border px-2 py-1">Recognized Life (in Months) Remaining Life</th>
                            <th class="border px-2 py-1">Allowable Input Tax for the Period**</th>
                            <th class="border px-2 py-1">Balance of Input Tax to be carried to Next Period</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-2 py-1">
                                <input type="date" name="schedule1_date" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="schedule1_source_code" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="schedule1_description" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="schedule1_amount" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="schedule1_estimated_life" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="schedule1_recognized_life" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="schedule1_allowable_input" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="schedule1_balance" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-sm text-zinc-500 mt-1">* D for Domestic Purchase; I for Importation</p>
                <p class="text-sm text-zinc-500">** Divide B by G multiplied by the Number of months in use during the quarter</p>
            </div>
        
            <!-- Schedule 2: Input Tax Attributable to VAT Exempt Sales -->
            <div class="mt-6">
                <h4 class="font-semibold text-zinc-700 text-base mb-2">Schedule 2 – Input Tax Attributable to VAT Exempt Sales</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-zinc-700 text-sm mb-1">Input Tax directly attributable to VAT Exempt Sale</label>
                        <input type="text" name="input_tax_direct" class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-zinc-700 text-sm mb-1">Add: Ratable portion of Input Tax not directly attributable</label>
                        <input type="text" name="input_tax_ratable" class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 mt-4">
                    <div>
                        <label class="block text-zinc-700 text-sm mb-1">Total Input Tax attributable to Exempt Sale</label>
                        <input type="text" name="input_tax_total" readonly class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                </div>
            </div>
        
            <!-- Schedule 3: Creditable VAT Withheld -->
            <div class="mt-6">
                <h4 class="font-semibold text-zinc-700 text-base mb-2">Schedule 3 – Creditable VAT Withheld</h4>
                <table class="w-full table-auto border border-zinc-300 text-sm">
                    <thead>
                        <tr class="bg-zinc-200 text-zinc-700">
                            <th class="border px-2 py-1">Period Covered</th>
                            <th class="border px-2 py-1">Name of Withholding Agent</th>
                            <th class="border px-2 py-1">Income Payment</th>
                            <th class="border px-2 py-1">Total Tax Withheld</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-2 py-1">
                                <input type="text" name="withheld_period" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="withheld_agent" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="withheld_income_payment" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="text" name="withheld_tax" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> --}}
        
        
    </div>
        <!-- Submit Button -->
        <div class="flex items-center justify-center mt-6">
            <button type="submit" class="w-56 bg-blue-900 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-950">
                Proceed to Report
            </button>
        </div>
    </form>
    </div>
</x-app-layout>
<script>
    // Function to calculate Total Available Input Tax (Sum of Items 43B and 50B)
    function calculateTotalAvailableInputTax() {
        // Retrieve values for Total Input Tax and Total Current Purchase Input Tax
        const totalInputTax = parseFloat(document.getElementById('total_input_tax').value) || 0;
        const totalCurrentPurchaseInputTax = parseFloat(document.getElementById('total_current_purchase_input_tax').value) || 0;
    
        // Calculate the Total Available Input Tax (Sum of 43B and 50B)
        const totalAvailableInputTax = totalInputTax + totalCurrentPurchaseInputTax;
    
        // Update the Total Available Input Tax field
        document.getElementById('total_available_input_tax').value = totalAvailableInputTax.toFixed(2);
    }
    
    // Function to calculate all totals including the Total Input Tax and Adjusted Output Tax
    function calculateTotals() {
        const vatableSales = parseFloat(document.getElementById('vatable_sales').value) || 0;
        const vatableSalesTaxAmount = parseFloat(document.getElementById('vatable_sales_tax_amount').value) || 0;
        const zeroRatedSales = parseFloat(document.getElementById('zero_rated_sales').value) || 0;
        const exemptSales = parseFloat(document.getElementById('exempt_sales').value) || 0;
    
        // Calculate total sales
        const totalSales = vatableSales + zeroRatedSales + exemptSales;
        document.getElementById('total_sales').value = totalSales.toFixed(2);
    
        // Calculate total output tax
        document.getElementById('total_output_tax').value = vatableSalesTaxAmount.toFixed(2);
    
        // Trigger input tax calculation
        calculateTotalInputTax();
    
        // Trigger adjusted output tax calculation
        calculateAdjustedOutputTax();
    
        // Trigger purchases input tax calculation
        calculateTotalPurchasesAndInputTax();
    
        // Calculate Total Available Input Tax after all other calculations
        calculateTotalAvailableInputTax();
        calculateTotalDeductionsInputTax();
        calculateAdjustedDeductionsInputTax();
    calculateTotalAllowableInputTax();
    calculateTotalTaxCredits();
    calculateNetVATPayable();
    calculateTaxStillPayable();
    calculateTotalPenalties();
    calculateTotalAmountPayable();
    }
    function calculateAdjustedDeductionsInputTax() {
    // Retrieve values for total deductions from input tax and settled unpaid input tax
    const totalDeductionsInputTax = parseFloat(document.getElementById('total_deductions_input_tax').value) || 0; // Item 57B
    const settledUnpaidInputTax = parseFloat(document.getElementById('settled_unpaid_input_tax').value) || 0; // Item 58B

    // Calculate adjusted deductions from input tax
    const adjustedDeductionsInputTax = totalDeductionsInputTax + settledUnpaidInputTax;

    // Update the adjusted deductions from input tax field
    document.getElementById('adjusted_deductions_input_tax').value = adjustedDeductionsInputTax.toFixed(2);
}

// Function to calculate Total Allowable Input Tax (Item 60B)
function calculateTotalAllowableInputTax() {
    // Retrieve values for total available input tax and adjusted deductions from input tax
    const totalAvailableInputTax = parseFloat(document.getElementById('total_available_input_tax').value) || 0; // Item 51B
    const adjustedDeductionsInputTax = parseFloat(document.getElementById('adjusted_deductions_input_tax').value) || 0; // Item 59B

    // Calculate total allowable input tax (total available input tax - adjusted deduct231ions)
    const totalAllowableInputTax = totalAvailableInputTax - adjustedDeductionsInputTax;

    // Update the total allowable input tax field
    document.getElementById('total_allowable_input_Tax').value = totalAllowableInputTax.toFixed(2);
}

// Function to calculate Net VAT Payable/(Excess Input Tax) (Item 61B)
function calculateNetVATPayable() {
    // Retrieve values for total adjusted output tax and total allowable input tax
    const totalAdjustedOutputTax = parseFloat(document.getElementById('total_adjusted_output_tax').value) || 0; // Item 37B
    const totalAllowableInputTax = parseFloat(document.getElementById('total_allowable_input_Tax').value) || 0; // Item 60B

    // Calculate net VAT payable/excess input tax (total adjusted output tax - total allowable input tax)
    const netVATPayable = totalAdjustedOutputTax - totalAllowableInputTax;

    // Update the net VAT payable field
    document.getElementById('excess_input_tax').value = netVATPayable.toFixed(2);   
     document.getElementById('net_vat_payable').value = netVATPayable.toFixed(2);
}
    function calculateTotalDeductionsInputTax() {
    // Retrieve values for the deduction fields (Items 52B to 56B)
    const importationMillionDeferredInputTax = parseFloat(document.getElementById('importation_million_deferred_input_tax').value) || 0; // Item 52B
    const attributableVatExemptInputTax = parseFloat(document.getElementById('attributable_vat_exempt_input_tax').value) || 0;  // Item 53B
    const vatRefundInputTax = parseFloat(document.getElementById('vat_refund_input_tax').value) || 0; // Item 54B
    const unpaidPayablesInputTax = parseFloat(document.getElementById('unpaid_payables_input_tax').value) || 0; // Item 55B
    const otherDeductionSpecifyInputTax = parseFloat(document.getElementById('other_deduction_specify_input_tax').value) || 0; // Item 56B

    // Calculate the total deductions from input tax (sum of 52B to 56B)
    const totalDeductionsInputTax = importationMillionDeferredInputTax + attributableVatExemptInputTax + vatRefundInputTax + unpaidPayablesInputTax + otherDeductionSpecifyInputTax;

    // Update the total deductions input tax field
    document.getElementById('total_deductions_input_tax').value = totalDeductionsInputTax.toFixed(2);
}
    // Function to calculate Total Input Tax (Sum of Items 38B to 42B)
    function calculateTotalInputTax() {
        const inputTaxCarriedOver = parseFloat(document.getElementById('input_carried_over').value) || 0;  // 38B
        const inputTaxDeferred = parseFloat(document.getElementById('input_tax_deferred').value) || 0;        // 39B
        const transitionalInputTax = parseFloat(document.getElementById('transitional_input_tax').value) || 0; // 40B
        const presumptiveInputTax = parseFloat(document.getElementById('presumptive_input_tax').value) || 0;   // 41B
        const otherInputTax = parseFloat(document.getElementById('other_input_tax').value) || 0;             // 42B
    
        const totalInputTax = inputTaxCarriedOver + inputTaxDeferred + transitionalInputTax + presumptiveInputTax + otherInputTax;
        document.getElementById('total_input_tax').value = totalInputTax.toFixed(2);
    }
    
    // Function to calculate Adjusted Output Tax
    function calculateAdjustedOutputTax() {
        const totalOutputTax = parseFloat(document.getElementById('total_output_tax').value) || 0; // Item 34B
        const uncollectedReceivableVAT = parseFloat(document.getElementById('uncollected_receivable_vat').value) || 0; // Item 35B
        const recoveredReceivablesVAT = parseFloat(document.getElementById('recovered_uncollected_receivables').value) || 0; // Item 36B
    
        const totalAdjustedOutputTax = totalOutputTax - uncollectedReceivableVAT + recoveredReceivablesVAT;
        document.getElementById('total_adjusted_output_tax').value = totalAdjustedOutputTax.toFixed(2);
    }
    
    // Function to calculate Total Purchases and Input Tax
    function calculateTotalPurchasesAndInputTax() {
        const domesticPurchase = parseFloat(document.getElementById('domestic_purchase').value) || 0;
        const domesticPurchaseInputTax = parseFloat(document.getElementById('domestic_purchase_input_tax').value) || 0;
        const servicesNonResident = parseFloat(document.getElementById('services_non_resident').value) || 0;
        const servicesNonResidentInputTax = parseFloat(document.getElementById('services_non_resident_input_tax').value) || 0;
        const importations = parseFloat(document.getElementById('importations').value) || 0;
        const importationsInputTax = parseFloat(document.getElementById('importations_input_tax').value) || 0;
        const domesticNoInput = parseFloat(document.getElementById('domestic_no_input').value) || 0;
    
        const othersSpecifyAmount = parseFloat(document.getElementById('purchases_others_specify_amount').value) || 0;
        const othersSpecifyInputTax = parseFloat(document.getElementById('purchases_others_specify_input_tax').value) || 0;
    
        // Calculate total purchases and total input tax
        const totalPurchases = domesticPurchase + servicesNonResident + importations + othersSpecifyAmount + domesticNoInput;
        const totalInputTax = domesticPurchaseInputTax + servicesNonResidentInputTax + importationsInputTax + othersSpecifyInputTax;
    
        document.getElementById('total_current_purchase').value = totalPurchases.toFixed(2);
        document.getElementById('total_current_purchase_input_tax').value = totalInputTax.toFixed(2);
    }
    function calculateTotalTaxCredits() {
        const creditableVATWithheld = parseFloat(document.getElementById('creditable_vat_withheld').value) || 0; // Item 16
        const advanceVATPayment = parseFloat(document.getElementById('advance_vat_payment').value) || 0;         // Item 17
        const vatPaidIfAmended = parseFloat(document.getElementById('vat_paid_if_amended').value) || 0;         // Item 18
        const otherCreditsSpecifyAmount = parseFloat(document.getElementById('other_credits_specify_amount').value) || 0; // Item 19

        // Calculate total tax credits
        const totalTaxCredits = creditableVATWithheld + advanceVATPayment + vatPaidIfAmended + otherCreditsSpecifyAmount;

        // Update the total tax credits field
        document.getElementById('total_tax_credits').value = totalTaxCredits.toFixed(2);
    }
    // Add event listeners for all input fields
    document.addEventListener('DOMContentLoaded', () => {
        // Trigger initial calculations
        calculateTotals();
    
        // List of fields to trigger recalculation
        const fieldsToWatch = [
            'vatable_sales', 
            'vatable_sales_tax_amount', 
            'zero_rated_sales', 
            'exempt_sales', 
            'input_carried_over', 
            'input_tax_deferred', 
            'transitional_input_tax', 
            'presumptive_input_tax', 
            'other_input_tax',
            'domestic_purchase', 
            'domestic_purchase_input_tax',
            'services_non_resident', 
            'services_non_resident_input_tax',
            'importations', 
            'importations_input_tax', 
            'domestic_no_input',
            'purchases_others_specify_amount',
            'purchases_others_specify_input_tax',
            'uncollected_receivable_vat', 
            'recovered_uncollected_receivables',
            'importation_million_deferred_input_tax',
        'attributable_vat_exempt_input_tax',
        'vat_refund_input_tax',
        'unpaid_payables_input_tax',
        'other_deduction_specify_input_tax',
        'total_deductions_input_tax',
        'settled_unpaid_input_tax',
        'total_available_input_tax',
        'creditable_vat_withheld', 
            'advance_vat_payment', 
            'vat_paid_if_amended', 
            'other_credits_specify_amount',
            'net_vat_payable', 'total_tax_credits',
        'total_adjusted_output_tax',
        'tax_still_payable', 'total_penalties',
        'surcharge', 'interest', 'compromise'
        ];
   
    
        
        fieldsToWatch.forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('input', () => {
                calculateTotals();  // Update the values when any field changes
            });
        });
    });

    function calculateTaxStillPayable() {
        const netVatPayable = parseFloat(document.getElementById('net_vat_payable').value) || 0;  // Item 15
        const totalTaxCredits = parseFloat(document.getElementById('total_tax_credits').value) || 0; // Item 20

        // Calculate Tax Still Payable/(Excess Credits)
        const taxStillPayable = netVatPayable - totalTaxCredits;

        // Update the field for Item 21
        document.getElementById('tax_still_payable').value = taxStillPayable.toFixed(2);
    }

    function calculateTotalPenalties() {
    // Retrieve values for Items 22, 23, and 24
    const surcharge = parseFloat(document.getElementById('surcharge').value) || 0; // Item 22
    const interest = parseFloat(document.getElementById('interest').value) || 0; // Item 23
    const compromise = parseFloat(document.getElementById('compromise').value) || 0; // Item 24

    // Calculate Total Penalties
    const totalPenalties = surcharge + interest + compromise;

    // Update the field for Item 25
    document.getElementById('total_penalties').value = totalPenalties.toFixed(2);
}
function calculateTotalAmountPayable() {
    // Retrieve values for Items 21 and 25
    const taxStillPayable = parseFloat(document.getElementById('tax_still_payable').value) || 0; // Item 21
    const totalPenalties = parseFloat(document.getElementById('total_penalties').value) || 0; // Item 25

    // Calculate Total Amount Payable
    const totalAmountPayable = taxStillPayable + totalPenalties;

    // Update the field for Item 26
    document.getElementById('total_amount_payable').value = totalAmountPayable.toFixed(2);
}
      

    </script>
    