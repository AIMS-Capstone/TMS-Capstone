
<x-app-layout>
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-8">
        <!-- Back Button -->
        <a href="#" class="text-gray-600 hover:text-gray-800 text-sm flex items-center mb-4">
            <span class="mr-2">&#8592;</span> Go back
        </a>

        <!-- Header -->
        <h1 class="text-2xl font-bold text-blue-700 mb-2">BIR Form No. 1702Q</h1>
        <h2 class="text-xl font-semibold text-gray-800">Quarterly Income Tax Return
            For Corporations, Partnerships and Other Non-Individual Taxpayers</h2>
        <p class="text-gray-600 mb-6">Verify the tax information below, with some fields pre-filled from your organization’s setup. Select options as needed, then click 'Proceed to Report' to generate the BIR form. Hover over icons for additional guidance on specific fields.</p>

        <!-- Filing Period Section -->
        <div class="border-b pb-6 mb-6">
            <h3 class="font-semibold text-gray-700 text-lg mb-4">Filing Period</h3>
            
            <!-- For the -->
            <form action="{{ route('tax_return.store2550Q', ['taxReturn' => $taxReturn->id]) }}" method="POST">
                @csrf
                <!-- Period -->
                <div class="mb-4 flex items-start">
                    <label class="block text-gray-700 text-sm font-medium w-1/3">1 For the</label>
                    <div class="flex items-center space-x-4 w-2/3">
                        <label class="flex items-center">
                            <input type="radio" name="period" value="calendar" class="mr-2" 
                                @if($period == 'calendar') checked @endif> Calendar
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="period" value="fiscal" class="mr-2"     $organization_id = session("organization_id");
                                @if($period == 'fiscal') checked @endif> Fiscal
                        </label>
                    </div>
                </div>
            
                <!-- Year Ended -->
                <div class="mb-4 flex items-start">
                    <label class="block text-gray-700 text-sm font-medium w-1/3">2 Year Ended</label>
                    <input type="month" name="year_ended" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" value="{{ old('year_ended', $yearEndedFormatted) }}">


                </div>
            
                <!-- Quarter -->
                <div class="mb-4 flex items-start">
                    <label class="block text-gray-700 text-sm font-medium w-1/3">3 Quarter</label>
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
                <label class="block text-gray-700 text-sm font-medium w-1/3">4 Amended Return?</label>
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center">
                        <input type="radio" name="amended_return" value="yes" class="mr-2"> Yes
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="amended_return" value="no" class="mr-2"> No
                    </label>
                </div>
            </div>

            <!-- Alphanumeric Tax Code -->
     
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">5 Alphanumeric Tax Code</label>
                <input type="text" name="alphanumeric_tax_code" placeholder="IC 010" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>


        <!-- Background Information Section -->
        <div class="border-b">
            <h3 class="font-semibold text-gray-700 text-lg mb-4">Background Information</h3>
            
            <!-- TIN -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">6 Taxpayer Identification Number (TIN)</label>
                <input type="text" name="tin" placeholder="000-000-000-000" value = "{{$organization->tin;}} "class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- RDO Code -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">7 Revenue District Office (RDO) Code</label>
                <input type="text" name="rdo_code" placeholder="000-000-000-000" value="{{ $rdoCode; }}" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">

            </div>

            <!-- Taxpayer's Name -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">8 Registered Name</label>
                <input type="text" name="taxpayer_name" value="{{$organization->registration_name;}}" placeholder="e.g. Dela Cruz, Juan, Protacio" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Registered Address -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">9 Registered Address</label>
                <input type="text" name="registered_address" value="{{ $organization->address_line . ', ' . $organization->city . ', ' . $organization->province . ', ' . $organization->region; }}" placeholder="e.g. 145 Yakal St. ESL Bldg., San Antonio Village Makati NCR" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- Zip Code -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">9A Zip Code</label>
                <input type="text" name="zip_code" value="{{$organization->zip_code;}}" placeholder="e.g. 1203" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
     
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">10 Contact Number</label>
            <input type="text" name="contact_number" value="{{$organization->contact_number;}}" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">11 Email Address</label>
            <input type="text" name="email_address"  value="{{$organization->email;}}" placeholder="pedro@gmail.com" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">12 Method of Deduction</label>
            <div class="flex items-center space-x-4 w-2/3">
                <label class="flex items-center">
                    <input type="radio" name="taxpayer_classification" value="itemized" class="mr-2"> Itemized Deductions [Section 34 (A-J), NIRC]
                </label>
                <label class="flex items-center">
                    <input type="radio" name="taxpayer_classification" value="osd" class="mr-2"> Optional Standard Deduction (OSD) – 40% of Gross Income
                </label>
         
            </div>
        </div>
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">13 Are you availing of tax relief under
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
            <label class="block text-gray-700 text-sm font-medium w-1/3">13A If yes, specify            </label>
            <input type="text" name="yes_specify" placeholder="Specified Tax Treaty" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="border-b ">
            <h3 class="font-semibold text-gray-700 text-lg mb-4">Part II - Total Tax Payables</h3>
            <div class="grid grid-cols-3 gap-4 border-t border-gray-300 pt-2">
                <!-- Header Row -->
               
        
       <!-- VATable Sales -->
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium">14 Income Tax Due - Regular/Normal Rate (From Part IV - Schedule 2, Item 13) 
        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text"
            id="net_vat_payable" 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium">15 Less: Unexpired Excess of Prior Year’s MCIT over Regular/Normal Income Tax Rate  (deductible only if the quarterly’s tax due is the regular/normal rate) 
        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text" 
            name="creditable_vat_withheld" 
            id="creditable_vat_withheld" 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium">16 Balance/Income Tax Still Due – Regular/Normal Rate (Item 14 Less item 15) 


        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text" 
            name="advance_vat_payment" 
            id="advance_vat_payment" 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium">17  Add: Income Tax Due – Special Rate (From Part IV - Schedule 1, Item 13) 

        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text" 
            name="vat_paid_if_amended" 
            id="vat_paid_if_amended" 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium">18 Aggregate Income Tax Due (Sum of Items 16 and 17) 


        </label>
    </div>
    <div>
        
    </div>
    <div>
        
        <input 
            type="text" 
            name="other_credits_specify_amount" 
            id="other_credits_specify_amount" 
         
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium">19 Less: Total Tax Credits/Payments (From Part IV - Schedule 4, Item 7) 

        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text" 
            name="total_tax_credits" 
            id="total_tax_credits" 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium">20 Net Tax Payable / (Overpayment) (Item 18 Less Item 19)  

        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text" 
            name="tax_still_payable" 
            id="tax_still_payable" 
    
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium"> Add: Penalties 21 Surcharge 


        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text" 
            name="surcharge" 
            id="surcharge" 
        
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium"> 22 Interest 


        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text" 
            name="interest" 
            id="interest" 
          
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium">23 Compromise

        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text" 
            name="compromise" 
            id="compromise" 
          
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium"> 24 Total Penalties (Sum of Items 22 to 24) 


        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text" 
            name="total_penalties" 
            id="total_penalties" 
           
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium">25 TOTAL AMOUNT PAYABLE / (Overpayment) (Sum of  Items 20 and 24)   

        </label>
    </div>
    <div>
    
    </div>
    <div>
        
        <input 
            type="text" 
            name="total_amount_payable" 
            id="total_amount_payable" 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            >
    </div>
    
    </div>
    
    
    <div class="border-b ">
        <h3 class="font-semibold text-gray-700 text-lg mb-4 pt-4">Part IV Schedules</h3>
    </div>
        <div class="grid grid-cols-3 gap-4  pt-2">
            <!-- Header Row -->
            <div class="font-semibold text-gray-700 text-base">Schedule 1 - Declaration this quarter</div>
            <div class="font-semibold text-gray-700 text-base">A. Exempt</div>
            <div class="font-semibold text-gray-700 text-base">B. Special</div>
    



<!-- Total Sales & Output Tax Due -->
<div class="flex items-center font-semibold">
    <label class="block text-gray-700 text-sm font-medium">
        1 Sales/Receipts/Revenues/Fees

    </label>
</div>
<div>
</div>
<div>
    <input 
        type="text" 
        name="total_sales" 
        id="total_sales" 
        readonly 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
</div>

                <!-- Less: Output VAT on Uncollected Receivables -->
<div class="flex items-center font-semibold">
    <label class="block text-gray-700 text-sm font-medium">
        2 Less: Cost of Sales/Services
    </label>
</div>
<div></div>
<div>
    <input 
        type="text" 
        name="cost_of_sales" 
        id="cost_of_sales" 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
        onchange="calculateAdjustedOutputTax()">
</div>

<!-- Add: Output VAT on Recovered Uncollected Receivables Previously Deducted -->
<div class="flex items-center font-semibold">
    <label class="block text-gray-700 text-sm font-medium">
        3 Gross Income from Operation (Item 1 Less Item 2)
    </label>
</div>
<div></div>
<div>
    <input 
        type="text" 
        name="recovered_uncollected_receivables" 
        id="recovered_uncollected_receivables" 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
        onchange="calculateAdjustedOutputTax()">
</div>

<!-- Total Adjusted Output Tax Due -->
<div class="flex items-center font-semibold">
    <label class="block text-gray-700 text-sm font-medium">
        4 Add: Non-Operating and Other Taxable Income
    </label>
</div>
<div></div>
<div>
    <input 
        type="text" 
        name="total_adjusted_output_tax" 
        id="total_adjusted_output_tax" 
        readonly 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
</div>

                <div class="font-semibold text-gray-700 text-sm">Less: Allowable Input Tax</div>
                <div class="font-semibold text-gray-700 text-sm"></div>
                <div class="font-semibold text-gray-700 text-sm">B. Input Tax</div>
                <!-- 38 Input Tax Carried Over from Previous Quarter -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        5 Total Gross Income (Sum of Items 3 and 4)
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="input_carried_over" 
                        id="input_carried_over" 
                        readonly 
                        value="{{ $excessInputTax ?? '0' }}" 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                
                <!-- 39 Input Tax Deferred on Capital Goods -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        6 Less: Deductions
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="input_tax_deferred" 
                        id="input_tax_deferred"  
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                
                <!-- 40 Transitional Input Tax -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        7 Taxable Income this Quarter (Item 5 less Item 6)
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="transitional_input_tax" 
                        id="transitional_input_tax" 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                
                <!-- 41 Presumptive Input Tax -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        8 Add: Taxable Income Previous Quarter/s
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="presumptive_input_tax" 
                        id="presumptive_input_tax" 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                
                <!-- 42 Others -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        9 Total Taxable Income to Date (Sum of Items 7 & 8)
                    </label>
                </div>
                <div>
                    <input 
                        type="text" 
                        name="other_specify" 
                        id="other_specify" 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                <div>
                    <input 
                        type="text" 
                        name="other_input_tax" 
                        id="other_input_tax" 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                
                <!-- 43 Total Input Tax -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        10 Applicable Income Tax Rate [except minimum corporate income tax (MCIT) rate] 0 %
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="total_input_tax" 
                        id="total_input_tax" 
                        readonly 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                        <!-- 43 Total Input Tax -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-gray-700 text-sm font-medium">
                                10 Applicable Income Tax Rate [except minimum corporate income tax (MCIT) rate] 
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="total_input_tax" 
                                id="total_input_tax" 
                                readonly 
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                        </div>
                                <!-- 43 Total Input Tax -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        11 Income Tax Due Other than MCIT (Item 9 x Item 10) 
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="text" 
                        name="total_input_tax" 
                        id="total_input_tax" 
                        readonly 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                        <!-- 43 Total Input Tax -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-gray-700 text-sm font-medium">
                                12 Less: Share of Other Agencies, if remitted directly
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="total_input_tax" 
                                id="total_input_tax" 
                                readonly 
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                        </div>
                                                <!-- 43 Total Input Tax -->
                                                <div class="flex items-center font-semibold">
                                                    <label class="block text-gray-700 text-sm font-medium">
                                                        13 Net Income Tax Due to National Government (Item 11 Less Item 12) (To Part II Item 17)

                                                    </label>
                                                </div>
                                                <div></div>
                                                <div>
                                                    <input 
                                                        type="text" 
                                                        name="total_input_tax" 
                                                        id="total_input_tax" 
                                                        readonly 
                                                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                                                </div>
             <!-- Empty Cell for Alignment -->
             <div class="font-semibold text-gray-700 text-sm col-span-2">Schedule 2 – Declaration this Quarter – REGULAR/NORMAL RATE 
            </div>
         
             <div class="font-semibold text-gray-700 text-sm"></div>
             
         

             
             <!-- 47. Others (Specify) (Manual Input Field) -->
             <div class="flex items-center font-semibold">
                 <label class="block text-gray-700 text-sm font-medium">
                    1 Sales/Receipts/Revenues/Fees 
                 </label>
         
             </div>
             
             <!-- 47B. Others (Specify Amount) (Manual Input Field) -->
             <div>
            
             </div>
             
             <!-- 47C. Others (Specify Input Tax) (Manual Input Field) -->
             <div>
                 <input 
                     type="text" 
                     name="purchases_others_specify_input_tax" 
                     id="purchases_others_specify_input_tax" 
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             
    
             <!-- 50. Total Current Purchases/Input Tax (Calculated Field) -->
             <div class="font-semibold text-gray-700 text-sm">
                2 Less: Cost of Sales/Services
             </div>
             <div>
               
             </div>
             
             <div>
                 <input 
                     type="text" 
                     name="total_current_purchase_input_tax" 
                     id="total_current_purchase_input_tax" 
                     readonly 
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
             </div>
             
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    3 Gross Income from Operation (Item 1 Less Item 2)


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="total_available_input_tax" 
                    id="total_available_input_tax" 
                    readonly 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    4 Add: Non-Operating and Other Taxable Income 


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="importation_million_deferred_input_tax" 
                    id="importation_million_deferred_input_tax" 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    5 Total Gross Income (Sum of Items 3 and 4)



                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="attributable_vat_exempt_input_tax" 
                    id="attributable_vat_exempt_input_tax" 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    6 Less: Deductions



                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="vat_refund_input_tax" 
                    id="vat_refund_input_tax" 
    
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    7 Taxable Income this Quarter (Item 5 less Item 6)


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="unpaid_payables_input_tax" 
                    id="unpaid_payables_input_tax" 
              
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    8 Add: Taxable Income Previous Quarter/s
                </label>
            </div>
            <div>
             
        </div>
        
            <div>
                <input 
                    type="text" 
                    name="other_deduction_specify_input_tax" 
                    id="other_deduction_specify_input_tax" 
            
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    9 Total Taxable Income to Date (Sum of Items 7 and 8)


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="total_deductions_input_tax" 
                    id="total_deductions_input_tax" 
                    readonly 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    10 Applicable Income Tax Rate (except MCIT rate) %


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="settled_unpaid_input_tax" 
                    id="settled_unpaid_input_tax" 
                     
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    11 Income Tax Due Other than MCIT (Item 9 multiply by Item 10) 


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="adjusted_deductions_input_tax" 
                    id="adjusted_deductions_input_tax" 
                    readonly 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    12 Minimum Corporate Income Tax (MCIT) (From Schedule 3 Item 6)


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="total_allowable_input_Tax" 
                    id="total_allowable_input_Tax" 
                    readonly 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    13 Income Tax Due (Normal Income Tax in Item 11 or MCIT in Item 12 whichever is higher)
                    (To Part II Item 14)


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="text" 
                    name="excess_input_tax" 
                    id="excess_input_tax" 
                    readonly 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
  
        
        
            
        </div>
      
        
        
    </div>
    <div class="grid grid-cols-3 gap-4 border-t border-gray-300 pt-2">
    <div class="font-semibold text-gray-700 text-sm col-span-2">Schedule 3 – Computation of Minimum Corporate Income (MSIT) Tax for the Quarter/s
    </div>
    
 
     <div class="font-semibold text-gray-700 text-sm"></div>

    <div class="flex items-center font-semibold">
        <label class="block text-gray-700 text-sm font-medium">
            1 Gross Income Regular/Normal Rate - 1st Quarter


        </label>
    </div>
    <div>
    </div>
  
    <div>
        <input 
            type="text" 
            name="total_allowable_input_Tax" 
            id="total_allowable_input_Tax" 
            readonly 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>
    <div class="flex items-center font-semibold">
        <label class="block text-gray-700 text-sm font-medium">
            2 Gross Income Regular/Normal Rate - 2nd Quarter


        </label>
    </div>
    <div>
    </div>
  
    <div>
        <input 
            type="text" 
            name="total_allowable_input_Tax" 
            id="total_allowable_input_Tax" 
            readonly 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>
    <div class="flex items-center font-semibold">
        <label class="block text-gray-700 text-sm font-medium">
            3 Gross Income Regular/Normal Rate - 3rd Quarter


        </label>
    </div>
    <div>
    </div>
  
    <div>
        <input 
            type="text" 
            name="total_allowable_input_Tax" 
            id="total_allowable_input_Tax" 
            readonly 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>
    <div class="flex items-center font-semibold">
        <label class="block text-gray-700 text-sm font-medium">
            4 Total Gross Income (Sum of Items 1 to 3)


        </label>
    </div>
    <div>
    </div>
  
    <div>
        <input 
            type="text" 
            name="total_allowable_input_Tax" 
            id="total_allowable_input_Tax" 
            readonly 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>
    <div class="flex items-center font-semibold">
        <label class="block text-gray-700 text-sm font-medium">
            5 MCIT Rate


        </label>
    </div>
    <div>
    </div>
  
    <div>
        <input 
            type="text" 
            name="total_allowable_input_Tax" 
            id="total_allowable_input_Tax" 
            readonly 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>
    <div class="flex items-center font-semibold">
        <label class="block text-gray-700 text-sm font-medium">
            6 Minimum Corporate Income Tax (To Schedule 2 Item 12)  


        </label>
    </div>
    <div>
    </div>
  
    <div>
        <input 
            type="text" 
            name="total_allowable_input_Tax" 
            id="total_allowable_input_Tax" 
            readonly 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>

    </div>
    <div class="grid grid-cols-3 gap-4 border-t border-gray-300 pt-2">
        <div class="font-semibold text-gray-700 text-sm col-span-2">Schedule 4 – Tax Credits/Payments 
        </div>
        
     
         <div class="font-semibold text-gray-700 text-sm"></div>
    
        <div class="flex items-center font-semibold">
            <label class="block text-gray-700 text-sm font-medium">
                1 Prior Year’s Excess Credits
    
    
            </label>
        </div>
        <div>
        </div>
      
        <div>
            <input 
                type="text" 
                name="total_allowable_input_Tax" 
                id="total_allowable_input_Tax" 
                readonly 
                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="flex items-center font-semibold">
            <label class="block text-gray-700 text-sm font-medium">
                2 Tax payment/s for the previous quarter/s of the same taxable year other than MCIT
    
    
            </label>
        </div>
        <div>
        </div>
      
        <div>
            <input 
                type="text" 
                name="total_allowable_input_Tax" 
                id="total_allowable_input_Tax" 
                readonly 
                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="flex items-center font-semibold">
            <label class="block text-gray-700 text-sm font-medium">
                3 MCIT payment/s for the previous quarter/s of the same taxable year
    
    
            </label>
        </div>
        <div>
        </div>
      
        <div>
            <input 
                type="text" 
                name="total_allowable_input_Tax" 
                id="total_allowable_input_Tax" 
                readonly 
                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="flex items-center font-semibold">
            <label class="block text-gray-700 text-sm font-medium">
                4 Creditable Tax Withheld for the previous quarter/s
    
    
            </label>
        </div>
        <div>
        </div>
      
        <div>
            <input 
                type="text" 
                name="total_allowable_input_Tax" 
                id="total_allowable_input_Tax" 
                readonly 
                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="flex items-center font-semibold">
            <label class="block text-gray-700 text-sm font-medium">
                5 Creditable Tax Withheld per BIR Form No. 2307 for this quarter 
    
    
            </label>
        </div>
        <div>
        </div>
      
        <div>
            <input 
                type="text" 
                name="total_allowable_input_Tax" 
                id="total_allowable_input_Tax" 
                readonly 
                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="flex items-center font-semibold">
            <label class="block text-gray-700 text-sm font-medium">
                6 Tax paid in return previously filed if this is an amended return 
    
    
            </label>
        </div>
        <div>
        </div>
      
        <div>
            <input 
                type="text" 
                name="total_allowable_input_Tax" 
                id="total_allowable_input_Tax" 
                readonly 
                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        <div class="font-semibold text-gray-700 text-sm col-span-2"> Other Tax Credits/Payments (specify below)

        </div>
        
     
         <div class="font-semibold text-gray-700 text-sm"></div>
    
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
    