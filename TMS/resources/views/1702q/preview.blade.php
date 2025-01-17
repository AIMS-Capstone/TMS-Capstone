<x-app-layout>
    @php
$taxCodes = [
    'DOMESTIC CORPORATION' => [
        'IC010' => [
            'title' => 'In General',
            'rate' => '30%',
            'base' => 'Taxable Income from All Sources',
            'mcit' => [
                'rate' => '2%',
                'base' => 'Gross Income'
            ]
        ],
        'IC011' => [
            'title' => 'Exempt Corporation - On Exempt Activities',
            'rate' => '0%',
            'base' => 'Taxable Income from All Sources'
        ],
        'IC020' => [
            'title' => 'Taxable Partnership',
            'rate' => '30%',
            'base' => 'Taxable Income from All Sources',
            'mcit' => [
                'rate' => '2%',
                'base' => 'Gross Income'
            ]
        ],
        'IC021' => [
            'title' => 'General Professional Partnership',
            'rate' => 'exempt',
            'base' => null
        ],
        'IC030' => [
            'title' => 'Exempt Corporation - On Taxable Activities',
            'rate' => '30%',
            'base' => 'Taxable Income from All Sources'
        ],
        'IC031' => [
            'title' => 'Non-Stock, Non-Profit Hospitals',
            'rate' => '10%',
            'base' => 'Taxable Income from All Sources'
        ],
        'IC040' => [
            'title' => 'GOCC, Agencies & Instrumentalities',
            'rate' => '30%',
            'base' => 'Taxable Income from All Sources'
        ],
        'IC041' => [
            'title' => 'National Gov\'t & LGU\'s',
            'rate' => '30%',
            'base' => 'Taxable Income from Proprietary Activities'
        ],
    ],
    'SPECIAL CORPORATIONS' => [
        'IC055' => [
            'title' => 'Proprietary Educational Institutions',
            'rate' => '10%',
            'base' => 'Taxable Income from All Sources'
        ]
    ],
    'RESIDENT FOREIGN CORPORATION' => [
        'IC070' => [
            'title' => 'In General',
            'rate' => '30%',
            'base' => 'Taxable Income from within the Philippines'
        ],
        'IC080' => [
            'title' => 'International Carriers',
            'rate' => '2.5%',
            'base' => 'Gross Philippine Billing'
        ],
        'IC101' => [
            'title' => 'Regional Operating Headquarters',
            'rate' => '10%',
            'base' => 'Taxable Income'
        ],
        'IC190' => [
            'title' => 'Offshore Banking Units (OBU\'s)',
            'rate' => '10%',
            'base' => 'Gross Taxable Income on Foreign Currency Transaction'
        ],
        'IC191' => [
            'title' => 'Foreign Currency Deposit Units (FCDU\'s)',
            'rate' => '10%',
            'base' => 'Gross Taxable Income on Foreign Currency Transaction'
        ]
    ],
    'SPECIAL LAW CORPORATIONS' => [
        'IC200' => [
            'title' => 'PEZA Free Port Zones',
            'rate' => '0% / 5%',
            'base' => 'Gross Income'
        ],
        'IC210' => [
            'title' => 'Microfinance Non-government Organizations (NGOs)',
            'rate' => '2%',
            'base' => 'Gross Receipts'
        ]
    ]
];
@endphp
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <button onclick="history.back()" class="flex items-center mb-4 text-zinc-600 hover:text-zinc-800 transition duration-150">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M16 12H8m4-4l-4 4l4 4"/>
                </g>
            </svg>
            <span class="text-sm font-medium">Go Back</span>
        </button>

        <!-- Header -->
        <div class="px-6 py-4 bg-white shadow-sm sm:rounded-lg">
            <div class="container px-4">
                <div class="flex justify-between items-center">
                    <div class="flex flex-col w-full items-start space-y-1">
                        <!-- BIR Form text on top -->
                        <p class="text-sm taxuri-color">BIR Form No. 1702-Q</p>
                        <p class="font-bold text-3xl taxuri-color">Quarterly Income Tax Return</p>
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

          
            <!-- Filing Period Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6 p-4">
                <form action="{{ route('tax_return.store1702Q', ['taxReturn' => $taxReturn->id]) }}" method="POST">
                    @csrf
                    <div class="px-8 py-10">
                        <!-- Period -->
                        <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>

                        <div class="mb-2 flex items-start">
                            <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">1</span>For the</label>
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
                        <div class="mb-2 flex items-start">
                            <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">2</span>Year Ended</label>
                            <input type="month" name="year_ended" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" value="{{ old('year_ended', $yearEndedFormatted) }}">
                        </div>
                    
                        <!-- Quarter -->
                        <div class="mb-2 flex items-start">
                            <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">3</span>Quarter</label>
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
                        <div class="mb-2 flex items-start">
                            <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">4</span>Amended Return?</label>
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
                        <div class="mb-2 flex items-start">
                            <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">5</span>Alphanumeric Tax Code</label>
                            <select name="alphanumeric_tax_code" id="alphanumeric_tax_code" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" >
                                <option value="">Select a Tax Code</option>
                                @foreach($taxCodes as $category => $codes)
                                    <optgroup label="{{ $category }}">
                                        @foreach($codes as $code => $details)
                                        <option value="{{ $code }}" data-rate="{{ $details['rate'] }}">{{ $code }} - {{ $details['title'] }} - {{ $details['rate'] }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    


                <!-- Background Information Section -->
                <div class="border-b">
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
                    
                    <!-- TIN -->
                    <div class="mb-2 flex items-start">
                        <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">6</span>Taxpayer Identification Number (TIN)</label>
                        <input type="text" name="tin" placeholder="000-000-000-000" value = "{{$organization->tin;}} " class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- RDO Code -->
                    <div class="mb-2 flex items-start">
                        <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">7</span>Revenue District Office (RDO) Code</label>
                        <input type="text" name="rdo_code" placeholder="000-000-000-000" value="{{ $rdoCode; }}" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">

                    </div>

                    <!-- Taxpayer's Name -->
                    <div class="mb-2 flex items-start">
                        <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">8</span>Registered Name</label>
                        <input type="text" name="taxpayer_name" value="{{$organization->registration_name;}}" placeholder="e.g. Dela Cruz, Juan, Protacio" 
                        class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Registered Address -->
                    <div class="mb-4 flex items-start">
                        <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">9</span>Registered Address</label>
                        <input type="text" name="registered_address" value="{{ $organization->address_line . ', ' . $organization->city . ', ' . $organization->province . ', ' . $organization->region; }}" placeholder="e.g. 145 Yakal St. ESL Bldg., San Antonio Village Makati NCR" 
                        class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>

                    <!-- Zip Code -->
                    <div class="mb-4 flex items-start">
                        <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">9A</span>Zip Code</label>
                        <input type="text" name="zip_code" value="{{$organization->zip_code;}}" placeholder="e.g. 1203" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
            
                    <div class="mb-4 flex items-start">
                        <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">10</span> Contact Number</label>
                        <input type="text" name="contact_number" value="{{$organization->contact_number;}}" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                    <div class="mb-4 flex items-start">
                        <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">11</span> Email Address</label>
                        <input type="text" name="email_address"  value="{{$organization->email;}}" placeholder="pedro@gmail.com" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                    <div class="mb-4 flex items-start gap-8">
                        <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">12</span> Method of Deduction</label>
                        <div class="flex items-center space-x-4 w-2/3">
                            <label class="flex items-center">
                                <input type="radio" name="taxpayer_classification" value="itemized" class="mr-2"> Itemized Deductions [Section 34 (A-J), NIRC]
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="taxpayer_classification" value="osd" class="mr-2"> Optional Standard Deduction (OSD) – 40% of Gross Income
                            </label>
                    
                        </div>
                    </div>
                    <div class="mb-4 flex items-start gap-8">
                        <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">13</span> Are you availing of tax relief under
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
                        <label class="indent-4 block text-zinc-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">13A</span> If yes, specify            </label>
                        <input type="text" name="yes_specify" placeholder="Specified Tax Treaty" class="block w-2/3 py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                <div class="border-b ">
                    <h3 class="font-bold text-zinc-700 text-lg mb-4">Part II - Total Tax Payables</h3>
                    <div class="grid grid-cols-3 gap-4 border-t border-zinc-300 pt-2">
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"><b>14</b> Income Tax Due - Regular/Normal Rate (From Part IV - Schedule 2, Item 13) </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text"
                                id="show_income_tax_due_regular" 
                                name="show_income_tax_due_regular"
                                readonly
                                value="{{ old('income_tax_due_regular') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"><b>15</b> Less: Unexpired Excess of Prior Year's MCIT over Regular/Normal Income Tax Rate  (deductible only if the quarterly's tax due is the regular/normal rate) 
                            </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="unexpired_excess_mcit" 
                                id="unexpired_excess_mcit" 
                                value="{{ old('unexpired_excess_mcit') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"><b>16</b> Balance/Income Tax Still Due – Regular/Normal Rate (Item 14 Less item 15) 
                            </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="balance_tax_due_regular" 
                                id="balance_tax_due_regular" 
                                value="{{ old('balance_tax_due_regular') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"><b>17</b>  Add: Income Tax Due – Special Rate (From Part IV - Schedule 1, Item 13) 
                            </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="show_income_tax_due_special" 
                                id="show_income_tax_due_special" 
                                value="{{ old('income_tax_due_special') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"><b>18</b> Aggregate Income Tax Due (Sum of Items 16 and 17) 
                            </label>
                        </div>
                        <div>
                            
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="aggregate_tax_due" 
                                id="aggregate_tax_due" 
                                value="{{ old('aggregate_tax_due') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"><b>19</b> Less: Total Tax Credits/Payments (From Part IV - Schedule 4, Item 7) 
                            </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="show_total_tax_credits" 
                                id="show_total_tax_credits" 
                                value="{{ old('total_tax_credits') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"><b>20</b> Net Tax Payable / (Overpayment) (Item 18 Less Item 19)  
                            </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="net_tax_payable" 
                                id="net_tax_payable" 
                                value="{{ old('net_tax_payable') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"> Add: Penalties <b>21</b> Surcharge 
                            </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="surcharge" 
                                id="surcharge" 
                                value="{{ old('surcharge') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"> <b>22</b> Interest 
                            </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="interest" 
                                id="interest" 
                                value="{{ old('interest') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"><b>23</b> Compromise
                            </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="compromise" 
                                id="compromise" 
                                value="{{ old('compromise') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"> <b>24</b> Total Penalties (Sum of Items 22 to 24) 
                            </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="total_penalties" 
                                id="total_penalties" 
                                value="{{ old('total_penalties') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    
                        <div class="flex items-center">
                            <label class="block text-zinc-700 text-sm font-medium"><b>25</b> TOTAL AMOUNT PAYABLE / (Overpayment) (Sum of  Items 20 and 24)   
                            </label>
                        </div>
                        <div>
                        
                        </div>
                        <div>
                            <input 
                                type="text" 
                                name="total_amount_payable" 
                                id="total_amount_payable" 
                                value="{{ old('total_amount_payable') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                            >
                        </div>
                    </div>
            
            
                    <div class="border-b ">
                        <h3 class="font-semibold text-zinc-700 text-lg mb-4 pt-4">Part IV Schedules</h3>
                    </div>
                    <div class="grid grid-cols-3 gap-4 pt-2">
                        <!-- Header Row -->
                        <div class="font-semibold text-zinc-700 text-base">Schedule 1 - Declaration this quarter</div>
                        <div class="font-semibold text-zinc-700 text-base">A. Exempt</div>
                        <div class="font-semibold text-zinc-700 text-base">B. Special</div>
                    
                        <!-- 1. Sales/Receipts -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>1</b> Sales/Receipts/Revenues/Fees
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="sales_receipts_special" 
                                id="sales_receipts_special"
                                value="{{ old('sales_receipts_special','0.00') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- 2. Cost of Sales -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>2</b> Less: Cost of Sales/Services
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="cost_of_sales_special" 
                                id="cost_of_sales_special" 
                                value="{{ old('cost_of_sales_special','0.00') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- 3. Gross Income -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>3</b> Gross Income from Operation (Item 1 Less Item 2)
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="gross_income_special" 
                                id="gross_income_special" 
                                value="{{ old('gross_income_special','0.00') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- 4. Other Income -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>4</b> Add: Non-Operating and Other Taxable Income
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="other_taxable_income_special" 
                                id="other_taxable_income_special" 
                                value="{{ old('other_taxable_income_special','0.00') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- Subheader -->
                        <div class="font-semibold text-zinc-700 text-sm">Less: Allowable Input Tax</div>
                        <div class="font-semibold text-zinc-700 text-sm"></div>
                        <div class="font-semibold text-zinc-700 text-sm">B. Input Tax</div>
                    
                        <!-- 5. Total Gross Income -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>5</b> Total Gross Income (Sum of Items 3 and 4)
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="total_gross_income_special" 
                                id="total_gross_income_special" 
                                readonly 
                                value="{{ old('total_gross_income_special') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- Continue with the rest of the fields following the same pattern... -->
                        <!-- 6. Deductions -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>6</b> Less: Deductions
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="deductions_special" 
                                id="deductions_special" 
                                value="{{ old('deductions_special') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- 7. Taxable Income this Quarter -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>7</b> Taxable Income this Quarter (Item 5 less Item 6)
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="taxable_income_quarter_special" 
                                id="taxable_income_quarter_special" 
                            readonly
                            value="{{ old('taxable_income_quarter_special', '0.00') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                >
                        </div>
            
                        <!-- 8. Previous Quarter Income -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>8</b> Add: Taxable Income Previous Quarter/s
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="prev_quarter_income_special" 
                                id="prev_quarter_income_special" 
                                value="{{ old('prev_quarter_income_special', '0.00') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- 9. Total Taxable Income -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>9</b> Total Taxable Income to Date (Sum of Items 7 & 8)
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="total_taxable_income_special" 
                                id="total_taxable_income_special" 
                                readonly
                                value="{{ old('total_taxable_income_special', '0.00') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
            
                        <!-- 10. Tax Rate -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>10</b> Applicable Income Tax Rate [except minimum corporate income tax (MCIT) rate]
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="number" 
                                name="tax_rate_special" 
                                id="tax_rate_special" 
                                readonly
                                value="{{ old('tax_rate_special', '0.00') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- 11. Income Tax Due -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>11</b> Income Tax Due Other than MCIT (Item 9 x Item 10)
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="income_tax_due_special" 
                                id="income_tax_due_special" 
                                value="{{ old('income_tax_due_special') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- 12. Share of Other Agencies -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>12</b> Less: Share of Other Agencies, if remitted directly
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="other_agencies_share_special" 
                                id="other_agencies_share_special" 
                                value="{{ old('other_agencies_share_special') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- 13. Net Income Tax Due -->
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>13</b> Net Income Tax Due to National Government (Item 11 Less Item 12) (To Part II Item 17)
                            </label>
                        </div>
                        <div></div>
                        <div>
                            <input 
                                type="text" 
                                name="net_tax_due_special" 
                                id="net_tax_due_special" 
                                value="{{ old('net_tax_due_special') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>

                        <!-- Empty Cell for Alignment -->
                        <div class="font-bold text-zinc-700 text-sm col-span-2">
                            Schedule 2 – Declaration this Quarter – REGULAR/NORMAL RATE 
                        </div>
                        
                        <div class="font-semibold text-zinc-700 text-sm"></div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>1</b> Sales/Receipts/Revenues/Fees 
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="sales_receipts_regular" 
                                id="sales_receipts_regular" 
                                value="{{ old('sales_receipts_regular') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                onchange="calculateTotals()">
                        </div>
                        
                        <div class="font-semibold text-zinc-700 text-sm">
                            <b>2</b> Less: Cost of Sales/Services
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="cost_of_sales_regular" 
                                id="cost_of_sales_regular"
                                value="{{ old('cost_of_sales_regular') }}"
                            
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>3</b> Gross Income from Operation (Item 1 Less Item 2)
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="gross_income_operation_regular" 
                                id="gross_income_operation_regular"
                                value="{{ old('gross_income_operation_regular') }}"
                                readonly 
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>4</b> Add: Non-Operating and Other Taxable Income 
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="non_operating_income_regular" 
                                id="non_operating_income_regular"
                                value="{{ old('non_operating_income_regular') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>5</b> Total Gross Income (Sum of Items 3 and 4)
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="total_gross_income_regular" 
                                id="total_gross_income_regular"
                                value="{{ old('total_gross_income_regular') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>6</b> Less: Deductions
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="deductions_regular" 
                                id="deductions_regular"
                                value="{{ old('deductions_regular') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>7</b> Taxable Income this Quarter (Item 5 less Item 6)
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="taxable_income_quarter_regular" 
                                id="taxable_income_quarter_regular"
                                value="{{ old('taxable_income_quarter_regular') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>8</b> Add: Taxable Income Previous Quarter/s
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="taxable_income_previous_regular" 
                                id="taxable_income_previous_regular"
                                value="{{ old('taxable_income_previous_regular') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>9</b> Total Taxable Income to Date (Sum of Items 7 and 8)
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="total_taxable_income_regular" 
                                id="total_taxable_income_regular"
                                value="{{ old('total_taxable_income_regular') }}"
                                readonly 
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>10</b> Applicable Income Tax Rate (except MCIT rate) %
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="income_tax_rate_regular" 
                                        readonly
                                id="income_tax_rate_regular"
                                value="{{ old('income_tax_rate_regular') }}"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>11</b> Income Tax Due Other than MCIT (Item 9 multiply by Item 10)
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="income_tax_due_regular" 
                                id="income_tax_due_regular"
                                value="{{ old('income_tax_due_regular') }}"
                                readonly 
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>12</b> Minimum Corporate Income Tax (MCIT) (From Schedule 3 Item 6)
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="mcit_regular" 
                                id="mcit_regular"
                                value="{{ old('mcit_regular') }}"
                            
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        
                        <div class="flex items-center font-semibold">
                            <label class="block text-zinc-700 text-sm font-medium">
                                <b>13</b> Income Tax Due (Normal Income Tax in Item 11 or MCIT in Item 12 whichever is higher)
                                (To Part II Item 14)
                            </label>
                        </div>
                        
                        <div></div>
                        
                        <div>
                            <input 
                                type="text" 
                                name="final_income_tax_due_regular" 
                                id="final_income_tax_due_regular"
                                value="{{ old('final_income_tax_due_regular') }}"
                                readonly 
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                        </div>
            
                
                
            </div>
            <div class="grid grid-cols-3 gap-4 border-t border-zinc-300 pt-2">
                <div class="font-bold text-zinc-700 text-sm col-span-2">Schedule 3 – Computation of Minimum Corporate Income (MSIT) Tax for the Quarter/s
                </div>
                
                <div class="font-semibold text-zinc-700 text-sm"></div>
                
                <div class="flex items-center font-semibold">
                    <label class="block text-zinc-700 text-sm font-medium">
                        <b>1</b> Gross Income Regular/Normal Rate - 1st Quarter
                    </label>
                </div>
                <div>
                </div>
                
                <div>
                    <input 
                        type="text" 
                        name="gross_income_first_quarter_mcit" 
                        id="gross_income_first_quarter_mcit" 
                        value="{{ old('gross_income_first_quarter_mcit') }}"
        
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                <div class="flex items-center font-semibold">
                    <label class="block text-zinc-700 text-sm font-medium">
                        <b>2</b> Gross Income Regular/Normal Rate - 2nd Quarter
                    </label>
                </div>
                <div>
                </div>
                
                <div>
                    <input 
                        type="text" 
                        name="gross_income_second_quarter_mcit" 
                        id="gross_income_second_quarter_mcit" 
                        value="{{ old('gross_income_second_quarter_mcit') }}"
                    
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                <div class="flex items-center font-semibold">
                    <label class="block text-zinc-700 text-sm font-medium">
                        <b>3</b> Gross Income Regular/Normal Rate - 3rd Quarter
                    </label>
                </div>
                <div>
                </div>
                
                <div>
                    <input 
                        type="text" 
                        name="gross_income_third_quarter_mcit" 
                        id="gross_income_third_quarter_mcit" 
                        value="{{ old('gross_income_third_quarter_mcit') }}"
            
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                <div class="flex items-center font-semibold">
                    <label class="block text-zinc-700 text-sm font-medium">
                        <b>4</b> Total Gross Income (Sum of Items 1 to 3)
                    </label>
                </div>
                <div>
                </div>
                
                <div>
                    <input 
                        type="text" 
                        name="total_gross_income_mcit" 
                        id="total_gross_income_mcit" 
                        value="{{ old('total_gross_income_mcit') }}"
                        readonly 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                <div class="flex items-center font-semibold">
                    <label class="block text-zinc-700 text-sm font-medium">
                        <b>5</b> MCIT Rate
                    </label>
                </div>
                <div>
                </div>
                
                <div>
                    <input 
                    type="text" 
                    name="mcit_rate" 
                    id="mcit_rate" 
                    value="2" 
                    readonly 
                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                <div class="flex items-center font-semibold">
                    <label class="block text-zinc-700 text-sm font-medium">
                        <b>6</b> Minimum Corporate Income Tax (To Schedule 2 Item 12)  
                    </label>
                </div>
                <div>
                </div>
                
                <div>
                    <input 
                        type="text" 
                        name="minimum_corporate_income_tax_mcit" 
                        id="minimum_corporate_income_tax_mcit" 
                        value="{{ old('minimum_corporate_income_tax_mcit','0.00') }}"
                        readonly 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
        
                    <div class="font-bold text-zinc-700 text-sm col-span-2">Schedule 4 – Tax Credits/Payments 
                    </div>
                    
                    <div class="font-semibold text-zinc-700 text-sm"></div>
                
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-medium">
                            <b>1</b> Prior Year's Excess Credits
                        </label>
                    </div>
                    <div>
                    </div>
                
                    <div>
                        <input 
                            type="text" 
                            name="prior_year_excess_credits" 
                            id="prior_year_excess_credits" 
                            value="{{ old('prior_year_excess_credits') }}"
                    
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-medium">
                            <b>2</b> Tax payment/s for the previous quarter/s of the same taxable year other than MCIT
                        </label>
                    </div>
                    <div>
                    </div>
                
                    <div>
                        <input 
                            type="text" 
                            name="previous_quarters_tax_payments" 
                            id="previous_quarters_tax_payments" 
                            value="{{ old('previous_quarters_tax_payments') }}"
                    
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-medium">
                            <b>3</b> MCIT payment/s for the previous quarter/s of the same taxable year
                        </label>
                    </div>
                    <div>
                    </div>
                
                    <div>
                        <input 
                            type="text" 
                            name="previous_quarters_mcit_payments" 
                            id="previous_quarters_mcit_payments" 
                            value="{{ old('previous_quarters_mcit_payments') }}"
                    
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-medium">
                            <b>4</b> Creditable Tax Withheld for the previous quarter/s
                        </label>
                    </div>
                    <div>
                    </div>
                
                    <div>
                        <input 
                            type="text" 
                            name="previous_quarters_creditable_tax" 
                            id="previous_quarters_creditable_tax" 
                            value="{{ old('previous_quarters_creditable_tax') }}"
                    
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-medium">
                            <b>5</b> Creditable Tax Withheld per BIR Form No. 2307 for this quarter 
                        </label>
                    </div>
                    <div>
                    </div>
                
                    <div>
                        <input 
                            type="text" 
                            name="current_quarter_creditable_tax" 
                            id="current_quarter_creditable_tax" 
                            value="{{ old('current_quarter_creditable_tax') }}"
                    
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-medium">
                            <b>6</b> Tax paid in return previously filed if this is an amended return 
                        </label>
                    </div>
                    <div>
                    </div>
                
                    <div>
                        <input 
                            type="text" 
                            name="previously_filed_tax_payment" 
                            id="previously_filed_tax_payment" 
                            value="{{ old('previously_filed_tax_payment') }}"
                    
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                
                    <div class="font-bold text-zinc-700 text-sm col-span-2">Other Tax Credits/Payments (specify below)
                    </div>
                    
                    <div class="font-semibold text-zinc-700 text-sm"></div>
                    
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-medium">
                            <b>A</b>. Other Tax Credits/Payments (Specify)
                        </label>
                    </div>
                    <div>
                    </div>
                    
                    <div>
                        <input 
                            type="text" 
                            name="other_tax_specify" 
                            id="other_tax_specify" 
                            value="{{ old('other_tax_specify') }}"
                    
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                    
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-medium">
                            <b>A</b>. Amount
                        </label>
                    </div>
                    <div>
                    </div>
                    
                    <div>
                        <input 
                            type="text" 
                            name="other_tax_amount" 
                            id="other_tax_amount" 
                            value="{{ old('other_tax_amount') }}"
                    
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                    
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-medium">
                            <b>B</b>. Other Tax Credits/Payments (Specify)
                        </label>
                    </div>
                    <div>
                    </div>
                    
                    <div>
                        <input 
                        type="text" 
                        name="other_tax_specify2" 
                        id="other_tax_specify2" 
                        value="{{ old('other_tax_specify2') }}"
            
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                    
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-medium">
                            <b>B</b>. Amount
                        </label>
                    </div>
                    <div>
                    </div>
                    
                    <div>
                        <input 
                            type="text" 
                            name="other_tax_amount2" 
                            id="other_tax_amount2" 
                            value="{{ old('other_tax_amount2') }}"
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                    <div class="flex items-center font-semibold">
                        <label class="block text-zinc-700 text-sm font-bold">
                            <b>7</b> Total Tax Credits/Payments (Sum of Items 1 to 6b) (To Part II Item 19)
                        </label>
                    </div>
                    <div>
                    </div>
                    
                    <div>
                        <input 
                            type="text" 
                            name="total_tax_credits" 
                            id="total_tax_credits" 
                            value="{{ old('total_tax_credits') }}"
                            readonly 
                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                    </div>
                </div>
        </div>  
            

                    @if ($errors->any())
                    <div class="mb-4">
                        <ul class="list-disc list-inside text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button class="w-full bg-blue-900 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-950">
                            Proceed to Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
<script>
    // Function to calculate all totals at once
    function calculateTotals() {
        // 1. Calculate Gross Income (Sales - Cost of Sales)
        let sales = parseFloat(document.getElementById('sales_receipts_special').value) || 0;
        let cost = parseFloat(document.getElementById('cost_of_sales_special').value) || 0;
        let grossIncome = sales - cost;
        document.getElementById('gross_income_special').value = grossIncome.toFixed(2);

        // 2. Calculate Total Gross Income (Gross Income + Other Taxable Income)
        let otherIncome = parseFloat(document.getElementById('other_taxable_income_special').value) || 0;
        let totalGrossIncome = grossIncome + otherIncome;
        document.getElementById('total_gross_income_special').value = totalGrossIncome.toFixed(2);

        // 3. Calculate Taxable Income (Total Gross Income - Deductions)
        let deductions = parseFloat(document.getElementById('deductions_special').value) || 0;
        let taxableIncome = totalGrossIncome - deductions;
        document.getElementById('taxable_income_quarter_special').value = taxableIncome.toFixed(2);

        // 4. Calculate Total Taxable Income (Taxable Income + Previous Quarter Income)
        let prevQuarterIncome = parseFloat(document.getElementById('prev_quarter_income_special').value) || 0;
        let totalTaxableIncome = taxableIncome + prevQuarterIncome;
        document.getElementById('total_taxable_income_special').value = totalTaxableIncome.toFixed(2);

        // 5. Update Tax Rate based on selected Tax Code
        let taxCodeSelect = document.getElementById('alphanumeric_tax_code');
        let selectedOption = taxCodeSelect.options[taxCodeSelect.selectedIndex];
        let taxRate = selectedOption.getAttribute('data-rate') || '0.00';
        document.getElementById('tax_rate_special').value = taxRate;

        // 6. Calculate Income Tax Due (Total Taxable Income * Tax Rate)
        let taxRateValue = parseFloat(document.getElementById('tax_rate_special').value) || 0;
        let incomeTaxDue = totalTaxableIncome * (taxRateValue / 100);
        document.getElementById('income_tax_due_special').value = incomeTaxDue.toFixed(2);
           // 7. Calculate Net Income Tax Due (Income Tax Due - Share of Other Agencies)
        let incomeTaxDueValue = parseFloat(document.getElementById('income_tax_due_special').value) || 0;
        let otherAgenciesShare = parseFloat(document.getElementById('other_agencies_share_special').value) || 0;
        let netTaxDue = incomeTaxDueValue - otherAgenciesShare;
        document.getElementById('net_tax_due_special').value = netTaxDue.toFixed(2);
    }

    // Trigger the calculateTotals function whenever relevant fields change
    document.getElementById('sales_receipts_special').addEventListener('input', calculateTotals);
    document.getElementById('cost_of_sales_special').addEventListener('input', calculateTotals);
    document.getElementById('other_taxable_income_special').addEventListener('input', calculateTotals);
    document.getElementById('deductions_special').addEventListener('input', calculateTotals);
    document.getElementById('prev_quarter_income_special').addEventListener('input', calculateTotals);
    document.getElementById('alphanumeric_tax_code').addEventListener('change', calculateTotals);
      document.getElementById('other_agencies_share_special').addEventListener('input', calculateTotals);

 function calculateTotalsRegular() {
    // 1. Calculate Gross Income (Sales/Receipts/Revenues/Fees - Cost of Sales/Services)
    let sales = parseFloat(document.getElementById('sales_receipts_regular').value) || 0;
    let cost = parseFloat(document.getElementById('cost_of_sales_regular').value) || 0;
    let grossIncome = sales - cost;
    document.getElementById('gross_income_operation_regular').value = grossIncome.toFixed(2);

    // 2. Calculate Total Gross Income (Gross Income from Operations + Non-Operating and Other Taxable Income)
    let nonOperatingIncome = parseFloat(document.getElementById('non_operating_income_regular').value) || 0;
    let totalGrossIncome = grossIncome + nonOperatingIncome;
    document.getElementById('total_gross_income_regular').value = totalGrossIncome.toFixed(2);

    // 3. Calculate Taxable Income this Quarter (Total Gross Income - Deductions)
    let deductions = parseFloat(document.getElementById('deductions_regular').value) || 0;
    let taxableIncomeQuarter = totalGrossIncome - deductions;
    document.getElementById('taxable_income_quarter_regular').value = taxableIncomeQuarter.toFixed(2);

    // 4. Calculate Total Taxable Income to Date (Taxable Income this Quarter + Taxable Income from Previous Quarters)
    let prevTaxableIncome = parseFloat(document.getElementById('taxable_income_previous_regular').value) || 0;
    let totalTaxableIncome = taxableIncomeQuarter + prevTaxableIncome;
    document.getElementById('total_taxable_income_regular').value = totalTaxableIncome.toFixed(2);

    // 5. Get Applicable Income Tax Rate from the alphanumeric_tax_code
    let taxCodeSelect = document.getElementById('alphanumeric_tax_code');
    let selectedOption = taxCodeSelect.options[taxCodeSelect.selectedIndex];
    let taxRate = parseFloat(selectedOption.getAttribute('data-rate')) || 0;
    document.getElementById('income_tax_rate_regular').value = taxRate.toFixed(2);

    // 6. Calculate Income Tax Due (Total Taxable Income * Income Tax Rate)
    let incomeTaxDue = totalTaxableIncome * (taxRate / 100);
    document.getElementById('income_tax_due_regular').value = incomeTaxDue.toFixed(2);

    // 7. Get Minimum Corporate Income Tax (MCIT) from Schedule 3 Item 6
    let mcit = parseFloat(document.getElementById('minimum_corporate_income_tax_mcit').value) || 0;

    // 8. Calculate the Final Income Tax Due (Normal Income Tax or MCIT whichever is higher)
    let finalIncomeTaxDue = Math.max(incomeTaxDue, mcit);
    document.getElementById('final_income_tax_due_regular').value = finalIncomeTaxDue.toFixed(2);
}

// Trigger the calculateTotalsRegular function whenever relevant fields change
document.getElementById('sales_receipts_regular').addEventListener('input', calculateTotalsRegular);
document.getElementById('cost_of_sales_regular').addEventListener('input', calculateTotalsRegular);
document.getElementById('non_operating_income_regular').addEventListener('input', calculateTotalsRegular);
document.getElementById('deductions_regular').addEventListener('input', calculateTotalsRegular);
document.getElementById('taxable_income_previous_regular').addEventListener('input', calculateTotalsRegular);
document.getElementById('alphanumeric_tax_code').addEventListener('change', calculateTotalsRegular); // Listen for changes in tax code
document.getElementById('mcit_regular').addEventListener('input', calculateTotalsRegular);

      function calculateTotalsMCIT() {
        // 1. Get values for Gross Income for each Quarter
        let firstQuarterGross = parseFloat(document.getElementById('gross_income_first_quarter_mcit').value) || 0;
        let secondQuarterGross = parseFloat(document.getElementById('gross_income_second_quarter_mcit').value) || 0;
        let thirdQuarterGross = parseFloat(document.getElementById('gross_income_third_quarter_mcit').value) || 0;

        // 2. Calculate Total Gross Income (Sum of Items 1, 2, and 3)
        let totalGrossIncome = firstQuarterGross + secondQuarterGross + thirdQuarterGross;
        document.getElementById('total_gross_income_mcit').value = totalGrossIncome.toFixed(2);

        // 3. MCIT Rate is constant (2%)
        let mcitRate = 2;

        // 4. Calculate Minimum Corporate Income Tax (MCIT)
        let minimumCorporateIncomeTax = totalGrossIncome * (mcitRate / 100);
        document.getElementById('minimum_corporate_income_tax_mcit').value = minimumCorporateIncomeTax.toFixed(2);
    }

    // Trigger the calculateTotalsMCIT function whenever relevant fields change
    document.getElementById('gross_income_first_quarter_mcit').addEventListener('input', calculateTotalsMCIT);
    document.getElementById('gross_income_second_quarter_mcit').addEventListener('input', calculateTotalsMCIT);
    document.getElementById('gross_income_third_quarter_mcit').addEventListener('input', calculateTotalsMCIT);
    function calculateTotalsTaxCredits() {
    // 1. Prior Year's Excess Credits (This field is readonly, no need to calculate)
    let priorYearExcessCredits = parseFloat(document.getElementById('prior_year_excess_credits').value) || 0;

    // 2. Tax payment/s for the previous quarter/s of the same taxable year other than MCIT
    let previousQuartersTaxPayments = parseFloat(document.getElementById('previous_quarters_tax_payments').value) || 0;

    // 3. MCIT payment/s for the previous quarter/s of the same taxable year
    let previousQuartersMcitPayments = parseFloat(document.getElementById('previous_quarters_mcit_payments').value) || 0;

    // 4. Creditable Tax Withheld for the previous quarter/s
    let previousQuartersCreditableTax = parseFloat(document.getElementById('previous_quarters_creditable_tax').value) || 0;

    // 5. Creditable Tax Withheld per BIR Form No. 2307 for this quarter
    let currentQuarterCreditableTax = parseFloat(document.getElementById('current_quarter_creditable_tax').value) || 0;

    // 6. Tax paid in return previously filed if this is an amended return
    let previouslyFiledTaxPayment = parseFloat(document.getElementById('previously_filed_tax_payment').value) || 0;

    // 7. Other Tax Credits/Payments (Specify and Amount)
    let otherTaxSpecify = parseFloat(document.getElementById('other_tax_amount').value) || 0;
    let otherTaxSpecify2 = parseFloat(document.getElementById('other_tax_amount2').value) || 0;

    // 8. Calculate Total Tax Credits/Payments (Sum of Items 1 to 6b)
    let totalTaxCredits = priorYearExcessCredits + previousQuartersTaxPayments + previousQuartersMcitPayments 
                        + previousQuartersCreditableTax + currentQuarterCreditableTax + previouslyFiledTaxPayment 
                        + otherTaxSpecify + otherTaxSpecify2;

    // 9. Set the Total Tax Credits value
    document.getElementById('total_tax_credits').value = totalTaxCredits.toFixed(2);
}

// Trigger the calculateTotalsTaxCredits function whenever relevant fields change
document.getElementById('prior_year_excess_credits').addEventListener('input', calculateTotalsTaxCredits);
document.getElementById('previous_quarters_tax_payments').addEventListener('input', calculateTotalsTaxCredits);
document.getElementById('previous_quarters_mcit_payments').addEventListener('input', calculateTotalsTaxCredits);
document.getElementById('previous_quarters_creditable_tax').addEventListener('input', calculateTotalsTaxCredits);
document.getElementById('current_quarter_creditable_tax').addEventListener('input', calculateTotalsTaxCredits);
document.getElementById('previously_filed_tax_payment').addEventListener('input', calculateTotalsTaxCredits);
document.getElementById('other_tax_amount').addEventListener('input', calculateTotalsTaxCredits);
document.getElementById('other_tax_amount2').addEventListener('input', calculateTotalsTaxCredits);

function calculateTotalsTaxPayables() {
    // 14. Income Tax Due - Regular/Normal Rate (Matches final_income_tax_due_regular)
    let incomeTaxDueRegular = parseFloat(document.getElementById('final_income_tax_due_regular').value) || 0;
    document.getElementById('show_income_tax_due_regular').value = incomeTaxDueRegular.toFixed(2);

    // 15. Less: Unexpired Excess of Prior Year's MCIT (Only if quarterly tax due is regular rate)
    let unexpiredExcessMCIT = parseFloat(document.getElementById('unexpired_excess_mcit').value) || 0;

    // 16. Balance/Income Tax Still Due – Regular/Normal Rate (Item 14 - Item 15)
    let balanceTaxDueRegular = incomeTaxDueRegular - unexpiredExcessMCIT;
    document.getElementById('balance_tax_due_regular').value = balanceTaxDueRegular.toFixed(2);

    // 17. Add: Income Tax Due – Special Rate (Matches net_tax_due_special)
    let incomeTaxDueSpecial = parseFloat(document.getElementById('net_tax_due_special').value) || 0;
    document.getElementById('show_income_tax_due_special').value = incomeTaxDueSpecial.toFixed(2);

    // 18. Aggregate Income Tax Due (Sum of Items 16 and 17)
    let aggregateTaxDue = balanceTaxDueRegular + incomeTaxDueSpecial;
    document.getElementById('aggregate_tax_due').value = aggregateTaxDue.toFixed(2);

    // 19. Less: Total Tax Credits/Payments (Matches total_tax_credits)
    let totalTaxCredits = parseFloat(document.getElementById('total_tax_credits').value) || 0;
    document.getElementById('show_total_tax_credits').value = totalTaxCredits.toFixed(2);

    // 20. Net Tax Payable / (Overpayment) (Item 18 - Item 19)
    let netTaxPayable = aggregateTaxDue - totalTaxCredits;
    document.getElementById('net_tax_payable').value = netTaxPayable.toFixed(2);

    // 21. Add: Penalties (Surcharge)
    let surcharge = parseFloat(document.getElementById('surcharge').value) || 0;

    // 22. Interest
    let interest = parseFloat(document.getElementById('interest').value) || 0;

    // 23. Compromise
    let compromise = parseFloat(document.getElementById('compromise').value) || 0;

    // 24. Total Penalties (Sum of Items 21, 22, and 23)
    let totalPenalties = surcharge + interest + compromise;
    document.getElementById('total_penalties').value = totalPenalties.toFixed(2);

    // 25. Total Amount Payable / (Overpayment) (Sum of Item 20 and Item 24)
    let totalAmountPayable = netTaxPayable + totalPenalties;
    document.getElementById('total_amount_payable').value = totalAmountPayable.toFixed(2);
}

// Add event listeners to trigger the calculation when relevant fields are updated
document.getElementById('final_income_tax_due_regular').addEventListener('input', calculateTotalsTaxPayables);
document.getElementById('unexpired_excess_mcit').addEventListener('input', calculateTotalsTaxPayables);
document.getElementById('net_tax_due_special').addEventListener('input', calculateTotalsTaxPayables);
document.getElementById('total_tax_credits').addEventListener('input', calculateTotalsTaxPayables);
document.getElementById('surcharge').addEventListener('input', calculateTotalsTaxPayables);
document.getElementById('interest').addEventListener('input', calculateTotalsTaxPayables);
document.getElementById('compromise').addEventListener('input', calculateTotalsTaxPayables);

</script>
