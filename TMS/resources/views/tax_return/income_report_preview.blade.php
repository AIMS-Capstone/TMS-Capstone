
<x-app-layout>
    @php
    $deductionMethod = $individualTaxOptionRate->deduction_method ?? 'default'; // Get deduction method
@endphp

    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-8">
        <!-- Back Button -->
        <a href="#" class="text-gray-600 hover:text-gray-800 text-sm flex items-center mb-4">
            <span class="mr-2">&#8592;</span> Go back
        </a>

        <!-- Header -->
        <h1 class="text-2xl font-bold text-blue-700 mb-2">BIR Form No. 2550Q</h1>
        <h2 class="text-xl font-semibold text-gray-800">Quarterly VAT Tax Return</h2>
        <p class="text-gray-600 mb-6">Verify the tax information below, with some fields pre-filled from your organization’s setup. Select options as needed, then click 'Proceed to Report' to generate the BIR form. Hover over icons for additional guidance on specific fields.</p>

        <!-- Filing Period Section -->
        <div class="border-b pb-6 mb-6">
            <h3 class="font-semibold text-gray-700 text-lg mb-4">Filing Period</h3>
            
            <!-- For the -->
            <form action="{{ route('tax_return.store2550Q', ['taxReturn' => $taxReturn->id]) }}" method="POST">
                @csrf
                <!-- Period -->
      
            
                <!-- Year Ended -->
                <div class="mb-4 flex items-start">
                    <label class="block text-gray-700 text-sm font-medium w-1/3">For the year</label>
                    
                    <!-- Select dropdown for years -->
                    <select name="for_the_year" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                        @php
                            // Get the current year
                            $currentYear = now()->year;
                            // Define the range of years (100 years before the current year)
                            $years = range($currentYear - 100, $currentYear);
                        @endphp
                
                        @foreach ($years as $year)
                            <option value="{{ $year }}" {{ $taxReturn->year == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
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
                <label class="block text-gray-700 text-sm font-medium w-1/3">Number of Sheets Attached</label>
                <input type="text" name="sheets" placeholder="No. of Sheets" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>



        <!-- Background Information Section -->
        <div class="border-b">
            <h3 class="font-semibold text-gray-700 text-lg mb-4">Background Information</h3>
            
            <!-- TIN -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">5 Taxpayer Identification Number (TIN)</label>
                <input type="text" name="tin" placeholder="000-000-000-000" value = "{{$organization->tin;}} "class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>

            <!-- RDO Code -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">6 Revenue District Office (RDO) Code</label>
                <input type="text" name="rdo_code" value="{{ $rdoCode }}" readonly class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
            </div>
            
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">7 Taxpayer/Filer Type</label>
                <div class="w-2/3">
                    <!-- Text input for Filer Type (readonly) -->
                    <input type="text" name="filer_type" 
                           value="{{ $taxReturn->individualBackgroundInformation->filer_type == 'single_proprietor' ? 'Single Proprietor' : 
                                   ($taxReturn->individualBackgroundInformation->filer_type == 'professional' ? 'Professional' : 
                                   ($taxReturn->individualBackgroundInformation->filer_type == 'estate' ? 'Estate' : 
                                   'Trust')) }}" 
                           readonly class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
            </div>
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">8 Alphanumeric Tax Code (ATC)</label>
                <div class="w-2/3">
                    <!-- Read-only text field for Alphanumeric Tax Code (ATC) -->
                    <input type="text" name="alphanumeric_tax_code" value="{{ $taxReturn->individualBackgroundInformation->alphanumeric_tax_code }}" 
                           class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" readonly>
                </div>
            </div>
            
            
            
            
            


            <!-- Taxpayer's Name -->
            <div class="mb-4 flex items-start">
                <label class="block text-gray-700 text-sm font-medium w-1/3">Taxpayer's Name</label>
                <input type="text" name="taxpayer_name" value="{{$organization->registration_name;}}" readonly placeholder="e.g. Dela Cruz, Juan, Protacio" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
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
                <label class="block text-gray-700 text-sm font-medium w-1/3">11 Date of Birth</label>
                <div class="w-2/3">
                    <!-- Date input for Date of Birth -->
                    <input type="date" name="date_of_birth" 
                           value="{{ $taxReturn->individualBackgroundInformation->date_of_birth ? \Carbon\Carbon::parse($taxReturn->individualBackgroundInformation->date_of_birth)->format('Y-m-d') : '' }}" 
                           class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </div>
            </div>
            
        <div class="mb-4 flex items-start">
            <label class="block text-gray-700 text-sm font-medium w-1/3">Email Address</label>
            <input type="text" name="email_address"  value="{{$organization->email;}}" placeholder="pedro@gmail.com" class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
  <!-- Citizenship Field (Readonly) -->
<div class="mb-4 flex items-start">
    <label class="block text-gray-700 text-sm font-medium w-1/3">13 Citizenship</label>
    <div class="w-2/3">
        <!-- Readonly text input for Citizenship -->
        <input type="text" name="citizenship" 
               value="{{ $taxReturn->individualBackgroundInformation->citizenship }}" 
               readonly class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>
</div>

<!-- Foreign Tax Number Field (Readonly) -->
<div class="mb-4 flex items-start">
    <label class="block text-gray-700 text-sm font-medium w-1/3">14 Foreign Tax Number</label>
    <div class="w-2/3">
        <!-- Readonly text input for Foreign Tax Number -->
        <input type="text" name="foreign_tax" 
               value="{{ $taxReturn->individualBackgroundInformation->foreign_tax }}" 
               readonly class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>
</div>

<!-- Claiming Foreign Tax Credits Field (Readonly Radio Buttons) -->
<div class="mb-4 flex items-start">
    <label class="block text-gray-700 text-sm font-medium w-1/3">15 Claiming Foreign Tax Credits?</label>
    <div class="w-2/3">
        <!-- Radio button for Yes (Readonly) -->
        <label class="inline-flex items-center mr-6">
            <input type="radio" name="claiming_foreign_credits" value="1" 
                   {{ $taxReturn->individualBackgroundInformation->claiming_foreign_credits == 1 ? 'checked' : 'disabled' }} 
                   readonly class="form-radio text-blue-600">
            <span class="ml-2">Yes</span>
        </label>

        <!-- Radio button for No (Readonly) -->
        <label class="inline-flex items-center mr-6">
            <input type="radio" name="claiming_foreign_credits" value="0" 
                   {{ $taxReturn->individualBackgroundInformation->claiming_foreign_credits == 0 ? 'checked' : 'disabled' }} 
                   readonly class="form-radio text-blue-600">
            <span class="ml-2">No</span>
        </label>
    </div>
</div>
<div>
    <!-- Individual Tax Option Rate Section -->
 <!-- Individual Tax Option Rate Section -->
<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700">Choose Tax Option</label>
    <div class="mt-2 flex items-center space-x-4">
        <label class="inline-flex items-center">
            <input type="radio" name="individual_rate_type" value="graduated_rates"
                   {{ $individualTaxOptionRate && $individualTaxOptionRate->rate_type === 'graduated_rates' ? 'checked' : 'disabled' }}
                   class="form-radio text-blue-600">
            <span class="ml-2">Graduated Rates</span>
        </label>
        <label class="inline-flex items-center">
            <input type="radio" name="individual_rate_type" value="8_percent"
                   {{ $individualTaxOptionRate && $individualTaxOptionRate->rate_type === '8_percent' ? 'checked' : 'disabled' }}
                   class="form-radio text-blue-600">
            <span class="ml-2">8% Gross Sales/Receipts</span>
        </label>
    </div>
    @error('individual_rate_type')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>

    <!-- Individual Deduction Method Section -->
    @if($individualTaxOptionRate && $individualTaxOptionRate->rate_type === 'graduated_rates')
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Choose Deduction Method</label>
            <div class="mt-2 flex items-center space-x-4">
                <label class="inline-flex items-center">
                    <input type="radio" name="individual_deduction_method" value="itemized"
                           {{ $individualTaxOptionRate->deduction_method === 'itemized' ? 'checked' : 'disabled' }}
                           class="form-radio text-blue-600">
                    <span class="ml-2">Itemized Deductions</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="radio" name="individual_deduction_method" value="osd"
                           {{ $individualTaxOptionRate->deduction_method === 'osd' ? 'checked' : 'disabled' }}
                           class="form-radio text-blue-600">
                    <span class="ml-2">Optional Standard Deduction (OSD)</span>
                </label>
            </div>
            @error('individual_deduction_method')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <!-- Background Information On Spouse Section -->
    @if($individualBackground->civil_status === 'married' && $spouseBackground)
        <div class="border-b">
            <h3 class="font-semibold text-gray-700 text-lg mb-4">Background Information On Spouse</h3>
   <!-- Background Information On Spouse Section -->
   <div class="border-b">
    <h3 class="font-semibold text-gray-700 text-lg mb-4">Background Information On Spouse</h3>
    
    <!-- TIN -->
    <div class="mb-4 flex items-start">
        <label class="block text-gray-700 text-sm font-medium w-1/3">17 Spouse's Taxpayer Identification Number (TIN)</label>
        <input type="text" name="spouse_tin" placeholder="000-000-000-000" value = "{{$spouseBackground->tin;}} "class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <!-- RDO Code -->
    <div class="mb-4 flex items-start">
        <label class="block text-gray-700 text-sm font-medium w-1/3">18 Spouse's Revenue District Office (RDO) Code</label>
        <input type="text" name="spouse_rdo" value="{{ $spouseBackground->rdo; }}" readonly class="w-2/3 p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
    </div>
    
    <div class="mb-4 flex items-start">
        <label class="block text-gray-700 text-sm font-medium w-1/3">19 Filer's Spouse Type Taxpayer/Filer Type</label>
        <div class="w-2/3">
            <!-- Text input for Filer Type (readonly) -->
            <input type="text" name="spouse_filer_type" 
                   value="{{ $spouseBackground->filer_type == 'single_proprietor' ? 'Single Proprietor' : 
                           ($spouseBackground->filer_type == 'professional' ? 'Professional' : 
                           ($spouseBackground->filer_type == 'estate' ? 'Estate' : 
                           'Trust')) }}" 
                   readonly class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    </div>
    <div class="mb-4 flex items-start">
        <label class="block text-gray-700 text-sm font-medium w-1/3">20 Spouse's Alphanumeric Tax Code (ATC)</label>
        <div class="w-2/3">
            <!-- Read-only text field for Alphanumeric Tax Code (ATC) -->
            <input type="text" name="spouse_alphanumeric_tax_code" value="{{ $spouseBackground->alphanumeric_tax_code }}" 
                   class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300" readonly>
        </div>
    </div>
    
    
    
    
    


    <!-- Spouse's Name -->
    <div class="mb-4 flex items-start">
        <label class="block text-gray-700 text-sm font-medium w-1/3">Spouse's Name</label>
        <div class="w-2/3">
            <input type="text" name="spouse_name" 
                   value="{{ $spouseBackground ? $spouseBackground->last_name . ', ' . $spouseBackground->first_name . ', ' . $spouseBackground->middle_name : '' }}" 
                   class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                   placeholder="Last Name, First Name, Middle Name">
        </div>
    </div>
    
    


    <div class="mb-4 flex items-start">
        <label class="block text-gray-700 text-sm font-medium w-1/3">13 Citizenship</label>
        <div class="w-2/3">
            <!-- Readonly text input for Spouse Citizenship -->
            <input type="text" name="spouse_citizenship" 
                   value="{{ $spouseBackground->citizenship }}" 
                   readonly class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
        </div>
    </div>
<!-- Spouse Foreign Tax Number Field (Readonly) -->
<div class="mb-4 flex items-start">
<label class="block text-gray-700 text-sm font-medium w-1/3">14 Foreign Tax Number</label>
<div class="w-2/3">
<!-- Readonly text input for Foreign Tax Number -->
<input type="text" name="spouse_foreign_tax_number" 
       value="{{ $spouseBackground->foreign_tax_number }}" 
       readonly class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
</div>
</div>
    
<!--Spouse  Claiming Foreign Tax Credits Field  (Readonly Radio Buttons) -->
<div class="mb-4 flex items-start">
<label class="block text-gray-700 text-sm font-medium w-1/3">15 Claiming Foreign Tax Credits?</label>
<div class="w-2/3">
<!-- Radio button for Yes (Readonly) -->
<label class="inline-flex items-center mr-6">
    <input type="radio" name="spouse_claiming_foreign_credits" value="1" 
           {{ $spouseBackground->claiming_foreign_credits == 1 ? 'checked' : 'disabled' }} 
           readonly class="form-radio text-blue-600">
    <span class="ml-2">Yes</span>
</label>

<!-- Radio button for No (Readonly) -->
<label class="inline-flex items-center mr-6">
    <input type="radio" name="spouse_claiming_foreign_credits" value="0" 
           {{$spouseBackground->claiming_foreign_credits ==  0 ? 'checked' : 'disabled' }} 
           readonly class="form-radio text-blue-600">
    <span class="ml-2">No</span>
</label>
</div>
</div>
 
            <!-- Spouse's Deduction Method (Disabled and Matches Individual's Selection) -->
            @if($individualTaxOptionRate && $individualTaxOptionRate->rate_type === 'graduated_rates')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Spouse's Deduction Method</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="spouse_deduction_method" value="itemized"
                                   {{ $individualTaxOptionRate->deduction_method === 'itemized' ? 'checked' : 'disabled' }}
                                   disabled class="form-radio text-blue-600">
                            <span class="ml-2">Itemized Deductions</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="spouse_deduction_method" value="osd"
                                   {{ $individualTaxOptionRate->deduction_method === 'osd' ? 'checked' : 'disabled' }}
                                   disabled class="form-radio text-blue-600">
                            <span class="ml-2">Optional Standard Deduction (OSD)</span>
                        </label>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>




    @if($individualTaxOptionRate->rate_type === 'graduated_rates')
            <div class="border-b border-t">
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <!-- Header Row -->
                    <div class="font-semibold text-gray-700 text-sm">Declaration this Quarter</div>
                    <div class="font-semibold text-gray-700 text-sm">A) Taxpayer/Filer</div>
                    <div class="font-semibold text-gray-700 text-sm">B) Spouse</div>
                </div>
                <!-- Item 36: Sales/Revenues/Receipts/Fees -->
           
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                            36. Sales/Revenues/Receipts/Fees (net of sales returns, allowances, and discounts)
                        </label>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="sales_revenues" 
                            id="sales_revenues"
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            value="{{ number_format($individualNetAmount, 2) }}" 
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_sales_revenues" 
                            id="spouse_sales_revenues" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            value="{{ number_format($spouseNetAmount, 2) }}" 
                            onchange="calculateTotals()">
                    </div>
                </div>
          
    
     
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <!-- Item 37: Less: Cost of Sales/Services (Itemized Deductions) -->
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                            37. Less: Cost of Sales/Services (if availing Itemized Deductions)
                        </label>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="cost_of_sales" 
                            id="cost_of_sales" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            value="{{ number_format($individualCOGS, 2) }}" 
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_cost_of_sales" 
                            id="spouse_cost_of_sales" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            value="{{ number_format($spouseCOGS, 2) }}" 
                            onchange="calculateTotals()">
                    </div>
                </div>
                
                <!-- Item 38: Gross Income/(Loss) from Operation -->
          
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                            38. Gross Income/(Loss) from Operation (Item 36 Less Item 37)
                        </label>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="gross_income" 
                            id="gross_income" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_gross_income" 
                            id="spouse_gross_income" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
    
                <!-- Item 39: Total Allowable Itemized Deductions -->
       
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                            39. Total Allowable Itemized Deductions
                        </label>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="total_itemized_deductions" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            value="{{ number_format($individualItemizedDeduction, 2) }}" 
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_total_itemized_deductions" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            value="{{ number_format($spouseItemizedDeduction, 2) }}" 
                            onchange="calculateTotals()">
                    </div>
                </div>
    
                <!-- Item 40: Optional Standard Deduction (OSD) -->
         
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">   
                        <label class="block text-gray-700 text-sm font-medium">
                            40. Optional Standard Deduction (OSD) (40% of Item 36)
                        </label>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="osd" 
                            id="osd"
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_osd" 
                             id="spouse_osd"
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
    
                <!-- Item 41: Net Income/(Loss) This Quarter -->
          
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                            41 Net Income/(Loss) This Quarter (If Itemized: Item 38 Less Item 39; If OSD: Item 38 Less Item 40)
                        </label>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="net_income" 
                            id="net_income" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_net_income" 
                            id="spouse_net_income" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
    
                <!-- Item 42: Taxable Income -->
          
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                            Add:    42. Taxable Income/(Loss) Previous Quarter/s
                        </label>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="taxable_income" 
                            id="taxable_income"
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_taxable_income" 
                            id="spouse_taxable_income" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                              43 Non-Operating Income (specify)
                        </label>
                        <div>
                            <input 
                                type="text" 
                                name="graduated_non_op_specify" 
                                id="taxable_income"
                                class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                                onchange="calculateTotals()">
                        </div>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="graduated_non_op" 
                            id="graduated_non_op"
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_graduated_non_op" 
                            id="spouse_graduated_non_op" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
      

                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                           44 Amount Received/Share in Income by a Partner from General Professional Partnership (GPP)
                        </label>
               
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="partner_gpp" 
                            id="partner_gpp"
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_partner_gpp" 
                            id="spouse_partner_gpp" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                            45 Total Taxable Income/(Loss) To Date (Sum of Items 41 to 44)
                        </label>
               
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="graduated_total_taxable_income" 
                            id="graduated_total_taxable_income"
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="graduated_spouse_total_taxable_income" 
                            id="graduated_spouse_total_taxable_income" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
    
        <!-- Item 46: TAX DUE (Item 45 x Applicable Tax Rate) -->
<div class="grid grid-cols-3 gap-4 pt-2">
    <div class="flex items-center font-semibold">
        <label class="block text-gray-700 text-sm font-medium">
            46. TAX DUE (Item 45 x Applicable Tax Rate)
        </label>
    </div>
    <div>
        <input 
            type="text" 
            name="graduated_tax_due" 
            id="graduated_tax_due"
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            readonly
        >
    </div>
    <div>
        <input 
            type="text" 
            name="graduated_spouse_tax_due" 
            id="graduated_spouse_tax_due" 
            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
            readonly 
        >
    </div>
</div>

    
        <!-- Schedule II - 8% IT Rate (if selected) -->
        @elseif($individualTaxOptionRate->rate_type === '8_percent')
            <div class="border-b border-t">
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <!-- Header Row -->
                    <div class="font-semibold text-gray-700 text-sm">Declaration this Quarter</div>
                    <div class="font-semibold text-gray-700 text-sm">A) Taxpayer/Filer</div>
                    <div class="font-semibold text-gray-700 text-sm">B) Spouse</div>
                </div>
                <!-- Item 47: Sales/Revenues/Receipts/Fees -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        47. Sales/Revenues/Receipts/Fees (net of sales returns, allowances, and discounts)
                    </label>
                </div>
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div>
                        <input 
                            type="text" 
                            name="sales_revenues_8" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_sales_revenues_8" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
    
                <!-- Item 48: Add: Non-Operating Income -->
                <div class="flex items-center font-semibold">
                    <label class="block text-gray-700 text-sm font-medium">
                        48. Add: Non-Operating Income (specify)
                    </label>
                </div>
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div>
                        <input 
                            type="text" 
                            name="non_operating_income" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_non_operating_income" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
    
                <!-- Item 49: Total Income for the Quarter -->
       
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div>
                        <div class="flex items-center font-semibold">
                            <label class="block text-gray-700 text-sm font-medium">
                                49. Total Income for the Quarter (Sum of Items 47 and 48)
                            </label>
                        </div>
                        <input 
                            type="text" 
                            name="total_income_8" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_total_income_8" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    
                </div>
    
                <!-- Item 53: Taxable Income To Date -->
              
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                            53. Taxable Income/(Loss) To Date (Item 51 Less Item 52)
                        </label>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="taxable_income_8" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_taxable_income_8" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
    
                <!-- Item 54: Tax Due -->
             
                <div class="grid grid-cols-3 gap-4 pt-2">
                    <div class="flex items-center font-semibold">
                        <label class="block text-gray-700 text-sm font-medium">
                            54. TAX DUE (Item 53 x 8% Tax Rate)
                        </label>
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="tax_due_8" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                    <div>
                        <input 
                            type="text" 
                            name="spouse_tax_due_8" 
                            class="w-full p-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300"
                            onchange="calculateTotals()">
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="flex items-center">
        <label class="block text-gray-700 text-sm font-medium"> Add: Penalties 22 Surcharge 


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
        <label class="block text-gray-700 text-sm font-medium"> 23 Interest .


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
        <label class="block text-gray-700 text-sm font-medium">Compromise

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
        <label class="block text-gray-700 text-sm font-medium"> 25 Total Penalties (Sum of Items 22 to 24) .


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
  
    <div>
    
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
    // Fetch the deduction method value passed from backend (Laravel Blade)
    const deductionMethod = "{{ $deductionMethod }}"; // This value comes from backend

    function calculateTaxDue() {
        const totalTaxableIncome = parseFloat(document.getElementById('graduated_total_taxable_income')?.value) || 0;
        const spouseTotalTaxableIncome = parseFloat(document.getElementById('graduated_spouse_total_taxable_income')?.value) || 0;
        
        // Calculate Tax Due for the individual
        let taxDue = 0;
        if (totalTaxableIncome <= 250000) {
            taxDue = 0;
        } else if (totalTaxableIncome > 250000 && totalTaxableIncome <= 400000) {
            taxDue = (totalTaxableIncome - 250000) * 0.15;
        } else if (totalTaxableIncome > 400000 && totalTaxableIncome <= 800000) {
            taxDue = 22500 + (totalTaxableIncome - 400000) * 0.20;
        } else if (totalTaxableIncome > 800000 && totalTaxableIncome <= 2000000) {
            taxDue = 102500 + (totalTaxableIncome - 800000) * 0.25;
        } else if (totalTaxableIncome > 2000000 && totalTaxableIncome <= 8000000) {
            taxDue = 402500 + (totalTaxableIncome - 2000000) * 0.30;
        } else if (totalTaxableIncome > 8000000) {
            taxDue = 2202500 + (totalTaxableIncome - 8000000) * 0.35;
        }

        // Update the Tax Due for the individual
        const taxDueElement = document.getElementById('graduated_tax_due');
        if (taxDueElement) {
            taxDueElement.value = taxDue.toFixed(2);
        }

        // Calculate Tax Due for the spouse (same logic)
        let spouseTaxDue = 0;
        if (spouseTotalTaxableIncome <= 250000) {
            spouseTaxDue = 0;
        } else if (spouseTotalTaxableIncome > 250000 && spouseTotalTaxableIncome <= 400000) {
            spouseTaxDue = (spouseTotalTaxableIncome - 250000) * 0.15;
        } else if (spouseTotalTaxableIncome > 400000 && spouseTotalTaxableIncome <= 800000) {
            spouseTaxDue = 22500 + (spouseTotalTaxableIncome - 400000) * 0.20;
        } else if (spouseTotalTaxableIncome > 800000 && spouseTotalTaxableIncome <= 2000000) {
            spouseTaxDue = 102500 + (spouseTotalTaxableIncome - 800000) * 0.25;
        } else if (spouseTotalTaxableIncome > 2000000 && spouseTotalTaxableIncome <= 8000000) {
            spouseTaxDue = 402500 + (spouseTotalTaxableIncome - 2000000) * 0.30;
        } else if (spouseTotalTaxableIncome > 8000000) {
            spouseTaxDue = 2202500 + (spouseTotalTaxableIncome - 8000000) * 0.35;
        }

        // Update the Tax Due for the spouse
        const spouseTaxDueElement = document.getElementById('graduated_spouse_tax_due');
        if (spouseTaxDueElement) {
            spouseTaxDueElement.value = spouseTaxDue.toFixed(2);
        }
    }
    // Function to calculate and update Gross Income from operation

    function calculateTotalTaxableIncome() {
        const netIncome = parseFloat(document.getElementById('net_income')?.value) || 0; // Item 41 (Net Income)
        const spouseNetIncome = parseFloat(document.getElementById('spouse_net_income')?.value) || 0; // Item 41 for Spouse
        const taxableIncome = parseFloat(document.getElementById('taxable_income')?.value) || 0; // Item 42 (Taxable Income)
        const spouseTaxableIncome = parseFloat(document.getElementById('spouse_taxable_income')?.value) || 0; // Item 42 for Spouse
        const nonOperatingIncome = parseFloat(document.getElementById('graduated_non_op')?.value) || 0; // Item 43 (Non-Operating Income)
        const spouseNonOperatingIncome = parseFloat(document.getElementById('spouse_graduated_non_op')?.value) || 0; // Item 43 for Spouse
        const partnerGPP = parseFloat(document.getElementById('partner_gpp')?.value) || 0; // Item 44 (Partner GPP)
        const spousePartnerGPP = parseFloat(document.getElementById('spouse_partner_gpp')?.value) || 0; // Item 44 for Spouse

        // Calculate Total Taxable Income for individual and spouse
        const totalTaxableIncome = netIncome + taxableIncome + nonOperatingIncome + partnerGPP;
        const spouseTotalTaxableIncome = spouseNetIncome + spouseTaxableIncome + spouseNonOperatingIncome + spousePartnerGPP;

        // Update the Total Taxable Income fields
        const totalTaxableIncomeElement = document.getElementById('graduated_total_taxable_income');
        if (totalTaxableIncomeElement) {
            totalTaxableIncomeElement.value = totalTaxableIncome.toFixed(2);
        }

        const spouseTotalTaxableIncomeElement = document.getElementById('graduated_spouse_total_taxable_income');
        if (spouseTotalTaxableIncomeElement) {
            spouseTotalTaxableIncomeElement.value = spouseTotalTaxableIncome.toFixed(2);
        }
    }

    function calculateGrossIncome() {
        const salesRevenues = parseFloat(document.getElementById('sales_revenues')?.value) || 0; // Item 36
        const costOfSales = parseFloat(document.getElementById('cost_of_sales')?.value) || 0; // Item 37

        // Calculate Gross Income/(Loss) from Operation
        const grossIncome = salesRevenues - costOfSales;

        // Update the Gross Income field
        const grossIncomeElement = document.getElementById('gross_income');
        if (grossIncomeElement) {
            grossIncomeElement.value = grossIncome.toFixed(2);
        }
    }

    // Function to calculate and update Spouse Gross Income from operation
    function calculateSpouseGrossIncome() {
        const spouseSalesRevenues = parseFloat(document.getElementById('spouse_sales_revenues')?.value) || 0; // Item 36 for Spouse
        const spouseCostOfSales = parseFloat(document.getElementById('spouse_cost_of_sales')?.value) || 0; // Item 37 for Spouse

        // Calculate Spouse Gross Income/(Loss) from Operation
        const spouseGrossIncome = spouseSalesRevenues - spouseCostOfSales;

        // Update the Spouse Gross Income field
        const spouseGrossIncomeElement = document.getElementById('spouse_gross_income');
        if (spouseGrossIncomeElement) {
            spouseGrossIncomeElement.value = spouseGrossIncome.toFixed(2);
        }
    }

    // Function to calculate Optional Standard Deduction (OSD) based on Item 36 (Sales Revenues)
    function calculateOSD() {
        const salesRevenues = parseFloat(document.getElementById('sales_revenues')?.value) || 0; // Item 36 (Sales Revenues)
        const spouseSalesRevenues = parseFloat(document.getElementById('spouse_sales_revenues')?.value) || 0; // Item 36 for Spouse

        if (deductionMethod === 'osd') {
            // Calculate OSD (40% of Item 36 for individual and spouse)
            const osd = (salesRevenues * 0.40).toFixed(2); // 40% of Item 36 for individual
            const spouseOsd = (spouseSalesRevenues * 0.40).toFixed(2); // 40% of Item 36 for spouse

            // Update OSD fields if they exist
            const osdElement = document.getElementById('osd');
            if (osdElement) {
                osdElement.value = osd;
            }

            const spouseOsdElement = document.getElementById('spouse_osd');
            if (spouseOsdElement) {
                spouseOsdElement.value = spouseOsd;
            }
        }
    }

    // Function to calculate Net Income/(Loss) This Quarter
    function calculateNetIncome() {
        const grossIncome = parseFloat(document.getElementById('gross_income')?.value) || 0; // Item 38
        const spouseGrossIncome = parseFloat(document.getElementById('spouse_gross_income')?.value) || 0; // Item 38 for Spouse

        let netIncome = 0;
        let spouseNetIncome = 0;

        // If OSD method, subtract Item 40 (OSD); If itemized, subtract Item 39 (deduction)
        if (deductionMethod === 'osd') {
            const osd = parseFloat(document.getElementById('osd')?.value) || 0; // Item 40 (OSD)
            const spouseOsd = parseFloat(document.getElementById('spouse_osd')?.value) || 0; // Item 40 (OSD)
            netIncome = grossIncome - osd;
            spouseNetIncome = spouseGrossIncome - spouseOsd; // Subtract same OSD for spouse
        } else {
            const deduction = parseFloat(document.getElementById('deduction')?.value) || 0; // Item 39 (Deduction)
            netIncome = grossIncome - deduction;
            spouseNetIncome = spouseGrossIncome - deduction; // Subtract same Deduction for spouse
        }

        // Update the Net Income fields
        const netIncomeElement = document.getElementById('net_income');
        if (netIncomeElement) {
            netIncomeElement.value = netIncome.toFixed(2);
        }

        const spouseNetIncomeElement = document.getElementById('spouse_net_income');
        if (spouseNetIncomeElement) {
            spouseNetIncomeElement.value = spouseNetIncome.toFixed(2);
        }
    }

    // Function to calculate totals for all fields
    function calculateTotals() {
        calculateGrossIncome();      // Calculate individual Gross Income
        calculateSpouseGrossIncome(); // Calculate spouse Gross Income
        calculateOSD();              // Calculate OSD (Optional Standard Deduction)
        calculateNetIncome();        // Calculate Net Income
        calculateTotalTaxableIncome(); // Calculate Total Taxable Income (Item 45)
        calculateTaxDue();           // Calculate Tax Due (Item 46)
    }


    // Event listener to trigger calculations after the page loads
    document.addEventListener('DOMContentLoaded', () => {
        calculateTotals(); // Trigger initial calculations

        // Watch for changes in fields
        const fieldsToWatch = [
            'sales_revenues', 
            'cost_of_sales', 
            'spouse_sales_revenues', 
            'spouse_cost_of_sales',
            'deduction', // Deduction field for itemized calculation
            'graduated_non_op', 
            'spouse_graduated_non_op', 
            'partner_gpp', 
            'spouse_partner_gpp', 
            'taxable_income',
            'spouse_taxable_income',
        ];

        fieldsToWatch.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', () => {
                    calculateTotals();  // Update all calculations on any input change
                });
            }
        });
    });
</script>
