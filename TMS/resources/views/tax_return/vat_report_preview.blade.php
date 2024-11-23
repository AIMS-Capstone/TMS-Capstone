
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
            <form action="{{ route('tax_return.store2550Q', ['taxReturn' => $taxReturn->id]) }}" method="POST">
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
                <label class="block text-gray-700 text-sm font-medium w-1/3">Return Period (From-To)</label>
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center">
                        <input type="date" name="return_from" class="mr-2"> 
                    </label>
                    <label class="flex items-center">
                        <input type="date" name="return_to" class="mr-2"> 
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
             <!-- Amended Return? -->
             <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">Short Period Return?</label>
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center">
                        <input type="radio" name="short_period_return" value="yes" class="mr-2"> Yes
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="short_period_return" value="no" class="mr-2"> No
                    </label>
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
            <label class="block text-gray-700 text-sm font-medium w-1/3">Taxpayer Classification</label>
            <div class="flex items-center space-x-4 w-2/3">
                <label class="flex items-center">
                    <input type="radio" name="taxpayer_classification" value="Micro" class="mr-2"> Micro
                </label>
                <label class="flex items-center">
                    <input type="radio" name="taxpayer_classification" value="Small" class="mr-2"> Small
                </label>
                <label class="flex items-center">
                    <input type="radio" name="taxpayer_classification" value="Medium" class="mr-2"> Medium
                </label>
                <label class="flex items-center">
                    <input type="radio" name="taxpayer_classification" value="Large" class="mr-2"> Large
                </label>
            </div>
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

    </div>
    <div class="border-b">
        <h3 class="font-semibold text-gray-700 text-lg mb-4">Details of VAT Computation</h3>
        <div class="grid grid-cols-3 gap-4 border-t border-gray-300 pt-2">
            <!-- Header Row -->
            <div class="font-semibold text-gray-700 text-sm">Total Sales and Output Tax</div>
            <div class="font-semibold text-gray-700 text-sm">A. Sales for the Quarter (Exclusive of VAT)</div>
            <div class="font-semibold text-gray-700 text-sm">B. Output Tax for the Quarter</div>
    
   <!-- VATable Sales -->
<div class="flex items-center">
    <label class="block text-gray-700 text-sm font-medium">31. VATable Sales</label>
</div>
<div>
    <input 
        type="number" 
        name="vatable_sales" 
        id="vatable_sales" 
        value="{{ $vatOnSalesGoods + $vatOnSalesServices + $salesToGovernmentGoods + $salesToGovernmentServices }}" 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
        onchange="calculateTotals()">
</div>
<div>
    <input 
        type="number" 
        name="vatable_sales_tax_amount" 
        id="vatable_sales_tax_amount" 
        value="{{ $vatOnSalesGoodsTax + $vatOnSalesServicesTax + $salesToGovernmentGoodsTax + $salesToGovernmentServicesTax }}" 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
        onchange="calculateTotals()">
</div>

<!-- Zero-Rated Sales -->
<div class="flex items-center">
    <label class="block text-gray-700 text-sm font-medium">32. Zero-Rated Sales</label>
</div>
<div>
    <input 
        type="number" 
        name="zero_rated_sales" 
        id="zero_rated_sales" 
        value="{{ $zeroRatedSalesGoods + $zeroRatedSalesServices }}" 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
        onchange="calculateTotals()">
</div>
<div>
</div>
<!-- Exempt Sales -->
<div class="flex items-center">
    <label class="block text-gray-700 text-sm font-medium">33. Exempt Sales</label>
</div>
<div>
    <input 
        type="number" 
        name="exempt_sales" 
        id="exempt_sales" 
        value="{{ $taxExemptSalesGoods + $taxExemptSalesServices }}" 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
        onchange="calculateTotals()">
</div>
<div>
</div>

<!-- Total Sales & Output Tax Due -->
<div class="flex items-center font-semibold">
    <label class="block text-gray-700 text-sm font-medium">
        34. Total Sales & Output Tax Due (Sum of Items 31A to 33A) / (Item 31B)
    </label>
</div>
<div>
    <input 
        type="number" 
        name="total_sales" 
        id="total_sales" 
        readonly 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
</div>
<div>
    <input 
        type="number" 
        name="total_output_tax" 
        id="total_output_tax" 
        readonly 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">

            </div>
                <!-- Less: Output VAT on Uncollected Receivables -->
<div class="flex items-center font-semibold">
    <label class="block text-gray-700 text-sm font-medium">
        Less: Output VAT on Uncollected Receivables
    </label>
</div>
<div></div>
<div>
    <input 
        type="number" 
        name="uncollected_receivable_vat" 
        id="uncollected_receivable_vat" 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
        onchange="calculateAdjustedOutputTax()">
</div>

<!-- Add: Output VAT on Recovered Uncollected Receivables Previously Deducted -->
<div class="flex items-center font-semibold">
    <label class="block text-gray-700 text-sm font-medium">
        Add: Output VAT on Recovered Uncollected Receivables Previously Deducted
    </label>
</div>
<div></div>
<div>
    <input 
        type="number" 
        name="recovered_uncollected_receivables" 
        id="recovered_uncollected_receivables" 
        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
        onchange="calculateAdjustedOutputTax()">
</div>

<!-- Total Adjusted Output Tax Due -->
<div class="flex items-center font-semibold">
    <label class="block text-gray-700 text-sm font-medium">
        37. Total Adjusted Output Tax Due (Item 34B Less Item 35B Add Item 36B)
    </label>
</div>
<div></div>
<div>
    <input 
        type="number" 
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
                        38 Input Tax Carried Over from Previous Quarter
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        name="input_carried_over" 
                        id="input_carried_over" 
                        readonly 
                        value="{{ $excessInputTax ?? '' }}" 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                
                <!-- 39 Input Tax Deferred on Capital Goods -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        39 Input Tax Deferred on Capital Goods Exceeding P1 Million from Previous Quarter (From Part V - Schedule 1 Col E).
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        name="input_tax_deferred" 
                        id="input_tax_deferred"  
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                
                <!-- 40 Transitional Input Tax -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        40 Transitional Input Tax
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        name="transitional_input_tax" 
                        id="transitional_input_tax" 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                
                <!-- 41 Presumptive Input Tax -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        41 Presumptive Input Tax
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        name="presumptive_input_tax" 
                        id="presumptive_input_tax" 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                
                <!-- 42 Others -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        42 Others (specify)
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
                        type="number" 
                        name="other_input_tax" 
                        id="other_input_tax" 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
                
                <!-- 43 Total Input Tax -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        43. Total (Sum of Items 38B to 42B)
                    </label>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        name="total_input_tax" 
                        id="total_input_tax" 
                        readonly 
                        class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
             <!-- Empty Cell for Alignment -->
             <div class="font-semibold text-gray-700 text-sm">Current Transactions</div>
             <div class="font-semibold text-gray-700 text-sm">A. Purchases</div>
             <div class="font-semibold text-gray-700 text-sm">B. Input Tax</div>
             
             <!-- 44. Domestic Purchases (Dynamic Field) -->
             <div class="flex items-center font-semibold">
                 <label class="block text-gray-700 text-sm font-medium">
                     44. Domestic Purchases
                 </label>
             </div>
             <div>
                 <input 
                     type="number" 
                     name="domestic_purchase" 
                     id="domestic_purchase" 
                     value="{{ $vatOnPurchasesGoods + $vatOnPurchasesServices }}" 
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 44B. Domestic Purchases Input Tax (Dynamic Field) -->
             <div>
                 <input 
                     type="number" 
                     name="domestic_purchase_input_tax" 
                     id="domestic_purchase_input_tax" 
                     value="{{ $vatOnPurchasesGoodsTax + $vatOnPurchasesServicesTax }}"  
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 45. Services Rendered by Non-Residents (Dynamic Field) -->
             <div class="flex items-center font-semibold">
                 <label class="block text-gray-700 text-sm font-medium">
                     45. Services Rendered by Non-Residents
                 </label>
             </div>
             <div>
                 <input 
                     type="number" 
                     name="services_non_resident" 
                     id="services_non_resident" 
                     value="{{ $servicesByNonResidents }}"  
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 45B. Services Non-Resident Input Tax (Dynamic Field) -->
             <div>
                 <input 
                     type="number" 
                     name="services_non_resident_input_tax" 
                     id="services_non_resident_input_tax" 
                     value="{{ $servicesByNonResidentsTax }}"  
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 46. Importations (Dynamic Field) -->
             <div class="flex items-center font-semibold">
                 <label class="block text-gray-700 text-sm font-medium">
                     46. Importations
                 </label>
             </div>
             <div>
                 <input 
                     type="number" 
                     name="importations" 
                     id="importations" 
                     value="{{ $importationOfGoods }}"  
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 46B. Importations Input Tax (Dynamic Field) -->
             <div>
                 <input 
                     type="number" 
                     name="importations_input_tax" 
                     id="importations_input_tax" 
                     value="{{ $importationOfGoodsTax }}"  
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 47. Others (Specify) (Manual Input Field) -->
             <div class="flex items-center font-semibold">
                 <label class="block text-gray-700 text-sm font-medium">
                     47. Others (Specify)
                 </label>
                 <input 
                     type="text" 
                     name="purchases_others_specify" 
                     id="purchases_others_specify" 
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 47B. Others (Specify Amount) (Manual Input Field) -->
             <div>
                 <input 
                     type="number" 
                     name="purchases_others_specify_amount" 
                     id="purchases_others_specify_amount" 
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 47C. Others (Specify Input Tax) (Manual Input Field) -->
             <div>
                 <input 
                     type="number" 
                     name="purchases_others_specify_input_tax" 
                     id="purchases_others_specify_input_tax" 
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             
             <!-- 48. Domestic Purchases with No Input Tax (Dynamic Field) -->
             <div class="flex items-center font-semibold">
                 <label class="block text-gray-700 text-sm font-medium">
                     48. Domestic Purchases with No Input Tax
                 </label>
             </div>
             <div>
                 <input 
                     type="number" 
                     name="domestic_no_input" 
                     id="domestic_no_input" 
                     value="{{ $goodsNotQualifiedForInputTax }}"  
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                     onchange="calculateTotals()">
             </div>
             <div> 
             </div>
             <!-- 50. Total Current Purchases/Input Tax (Calculated Field) -->
             <div class="font-semibold text-gray-700 text-sm">
                 50. Total Current Purchases/Input Tax
                 (Sum of Items 44A to 49A)/(Sum of Items 44B to 47B)
             </div>
             <div>
                 <input 
                     type="number" 
                     name="total_current_purchase" 
                     id="total_current_purchase" 
                     readonly 
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
             </div>
             
             <div>
                 <input 
                     type="number" 
                     name="total_current_purchase_input_tax" 
                     id="total_current_purchase_input_tax" 
                     readonly 
                     class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
             </div>
             
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    51 Total Available Input Tax (Sum of Items 43B and 50B) 

                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="number" 
                    name="total_available_input_tax" 
                    id="total_available_input_tax" 
                    readonly 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                 52   Input Tax on Purchases/Importation of Capital Goods exceeding P1 Million deferred for the succeeding period
                    (From Part V Schedule 1, Column I)

                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="number" 
                    name="importation_million_deferred_input_tax" 
                    id="importation_million_deferred_input_tax" 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    53 Input Tax Attributable to VAT Exempt Sales (From Part V - Schedule 2) 


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="number" 
                    name="attributable_vat_exempt_input_tax" 
                    id="attributable_vat_exempt_input_tax" 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    54 VAT Refund/TCC Claimed



                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="number" 
                    name="vat_refund_input_tax" 
                    id="vat_refund_input_tax" 
    
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    55 Input VAT on Unpaid Payables 


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="number" 
                    name="unpaid_payables_input_tax" 
                    id="unpaid_payables_input_tax" 
              
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                 Others (specify)
                </label>
            </div>
            <div>
                <input 
                type="text" 
                name="other_deduction_specify" 
                id="other_deduction_specify" 
        
                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
        
            <div>
                <input 
                    type="number" 
                    name="other_deduction_specify_input_tax" 
                    id="other_deduction_specify_input_tax" 
            
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    57 Total Deductions from Input Tax (Sum of Items 52B to 56B)  


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="number" 
                    name="total_deductions_input_tax" 
                    id="total_deductions_input_tax" 
                    readonly 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    58 Add: Input VAT on Settled Unpaid Payables Previously Deducted 


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="number" 
                    name="settled_unpaid_input_tax" 
                    id="settled_unpaid_input_tax" 
                     
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    59 Adjusted Deductions from Input Tax (Sum of Items 57B and 58B) 


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="number" 
                    name="adjusted_deductions_input_tax" 
                    id="adjusted_deductions_input_tax" 
                    readonly 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    60 Total Allowable Input Tax (Item 51B Less Item 59B) 


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="number" 
                    name="total_allowable_input_Tax" 
                    id="total_allowable_input_Tax" 
                    readonly 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            <div class="flex items-center font-semibold">
                <label class="block text-gray-700 text-sm font-medium">
                    61 Net VAT Payable/(Excess Input Tax) (Item 37B Less Item 60B) (To Part II, Item 15) .


                </label>
            </div>
            <div>
            </div>
          
            <div>
                <input 
                    type="number" 
                    name="excess_input_tax" 
                    id="excess_input_tax" 
                    readonly 
                    class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
  
        
        
            
        </div>
        {{-- <div class="border-t border-gray-300 py-4">
            <h3 class="font-semibold text-gray-700 text-lg mb-4">Part V – Schedules</h3>
        
            <!-- Schedule 1: Amortized Input Tax from Capital Goods -->
            <div>
                <h4 class="font-semibold text-gray-700 text-base mb-2">Schedule 1 – Amortized Input Tax from Capital Goods</h4>
                <table class="w-full table-auto border border-gray-300 text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
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
                                <input type="number" name="schedule1_amount" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="number" name="schedule1_estimated_life" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="number" name="schedule1_recognized_life" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="number" name="schedule1_allowable_input" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="number" name="schedule1_balance" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-sm text-gray-500 mt-1">* D for Domestic Purchase; I for Importation</p>
                <p class="text-sm text-gray-500">** Divide B by G multiplied by the Number of months in use during the quarter</p>
            </div>
        
            <!-- Schedule 2: Input Tax Attributable to VAT Exempt Sales -->
            <div class="mt-6">
                <h4 class="font-semibold text-gray-700 text-base mb-2">Schedule 2 – Input Tax Attributable to VAT Exempt Sales</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Input Tax directly attributable to VAT Exempt Sale</label>
                        <input type="number" name="input_tax_direct" class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Add: Ratable portion of Input Tax not directly attributable</label>
                        <input type="number" name="input_tax_ratable" class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-4 mt-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1">Total Input Tax attributable to Exempt Sale</label>
                        <input type="number" name="input_tax_total" readonly class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                </div>
            </div>
        
            <!-- Schedule 3: Creditable VAT Withheld -->
            <div class="mt-6">
                <h4 class="font-semibold text-gray-700 text-base mb-2">Schedule 3 – Creditable VAT Withheld</h4>
                <table class="w-full table-auto border border-gray-300 text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
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
                                <input type="number" name="withheld_income_payment" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                            <td class="border px-2 py-1">
                                <input type="number" name="withheld_tax" class="w-full p-1 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div> --}}
        
        
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
    calculateNetVATPayable();
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

    // Calculate total allowable input tax (total available input tax - adjusted deductions)
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
        'total_adjusted_output_tax'
        ];
   
    
        
        fieldsToWatch.forEach(fieldId => {
            document.getElementById(fieldId).addEventListener('input', () => {
                calculateTotals();  // Update the values when any field changes
            });
        });
    });
    </script>
    