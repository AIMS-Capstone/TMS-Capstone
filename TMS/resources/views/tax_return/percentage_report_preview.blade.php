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
                <div class="mb-4 flex items-start" x-data="{ showTooltip: false }">
                    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                        <span class="font-bold">1</span> For the
                        <span class="relative ml-2">
                            <!-- Tooltip Icon with click-to-toggle behavior using Alpine.js -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                            </svg>
                            <!-- Tooltip Content, visibility controlled by Alpine.js -->
                            <div  x-cloak x-show="showTooltip" x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg ">
                                The reporting period can be either:
                                <br> - <b>Calendar</b>: Ends on December 31 and starts on January 1.
                                <br> - <b>Fiscal</b>: Ends on any date other than December 31.
                                <br><br>This is automatically set based on your organization's setup.
                            </div>
                        </span>
                    </label>
                
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
               <div class="mb-4 flex items-start" x-data="{ showTooltip: false }">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <!-- Clearer 'Year Ended' label with more definition -->
                    <span class="font-bold text-lg mr-2">2</span><span >Year Ended</span>
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content, visibility controlled by Alpine.js -->
                        <div x-show="showTooltip"
                        x-cloak
                        x-transition:enter="transition-opacity duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                       <div><b>Year Ended:</b></div>
                       <ul class="list-disc pl-4 mt-1">
                           <li>Refers to the Financial Year End set during the organization's creation.</li>
                           <li>Determined by the Tax Return date selected when generating the return.</li>
                           <li>This value is automatically set based on the date chosen during tax return creation.</li>
                       </ul>
                   </div>
                        
                    </span>
                </label>
                
                <!-- Input for Year Ended (Month selector) -->
                <input type="month" name="year_ended" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" value="{{ old('year_ended', $yearEndedFormatted) }}">
            </div>
            
            

                <!-- Quarter -->
                <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                        <!-- Clearer 'Quarter' label with more definition -->
                        <span class="font-bold mr-2">3</span>Quarter
                
                        <span class="relative ml-2">
                            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                            </svg>
                
                            <!-- Tooltip Content with smooth transition effects using Alpine.js -->
                           
                                
                                 <div x-show="showTooltip"
                                 x-cloak
                                 x-transition:enter="transition-opacity duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition-opacity duration-300"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                <p><b>Quarter Information:</b> The quarter is based on the reporting period:</p>
                                <ul class="list-disc pl-4">
                                    <li><b>Calendar Year:</b> Divided into four quarters (1st, 2nd, 3rd, 4th).</li>
                                    <li><b>Fiscal Year:</b> Quarters are based on your organization's fiscal year-end.</li>
                                </ul>
                            </div>
                           
                        </span>
                    </label>
                
                    <!-- Radio Buttons for selecting Quarter -->
                    <div class="flex items-center space-x-4 w-2/3">
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" value="1st" class="mr-2" @if($taxReturn->month == 'Q1') checked @endif> 1st
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" value="2nd" class="mr-2" @if($taxReturn->month == 'Q2') checked @endif> 2nd
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" value="3rd" class="mr-2" @if($taxReturn->month == 'Q3') checked @endif> 3rd
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" value="4th" class="mr-2" @if($taxReturn->month == 'Q4') checked @endif> 4th
                        </label>
                    </div>
                </div>
                
                        

            <!-- Amended Return? -->
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <!-- Clearer 'Amended Return' label with more definition -->
                    <span class="font-bold mr-2">4</span>Amended Return?
            
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with smooth transition effects using Alpine.js -->
                        <div x-show="showTooltip"
                        x-cloak
                        x-transition:enter="transition-opacity duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                       <p><b>Yes:</b> If correcting an error or omission in a previously filed return.</p>
                       <p><b>No:</b> If this is your original filing or no corrections are needed.</p>
                   </div>
                    </span>
                </label>
            
                <!-- Radio Buttons for selecting Amended Return -->
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="amended_return" value="yes" class="mr-2" {{ old('amended_return') == 'yes' ? 'checked' : '' }}> Yes
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="amended_return" value="no" class="mr-2"  {{ old('amended_return') == 'no' ? 'checked' : '' }}> No
                    </label>
                </div>
            </div>

            <!-- Number of Sheets Attached -->
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">5</span>Number of Sheets Attached
            
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with smooth transition effects using Alpine.js -->
                        <div x-show="showTooltip"
                        x-cloak
                             x-transition:enter="transition-opacity duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            Enter the total number of sheets or documents attached to the tax return.
                        </div>
                    </span>
                </label>
            
                <input type="number"  name="sheets_attached" value="{{ old('sheets_attached') }}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
        </div>

        <!-- Background Information Section -->
        <div class="border-b">
            <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
            
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">6</span>Taxpayer Identification Number (TIN)
                    <span class="text-red-500 ml-1">*</span> <!-- Required field indicator -->
            
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with explanation of TIN -->
                        <div x-show="showTooltip"
                             x-cloak
                             x-transition:enter="transition-opacity duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            The TIN (Taxpayer Identification Number) is a unique identifier assigned to individuals or entities for tax purposes. It is automatically generated when your organization is set up and is required for all tax filings.
                        </div>
                    </span>
                </label>
            
                <!-- Display the TIN automatically fetched from the organization setup (no user input) -->
                <input type="text" name="tin" placeholder="TIN: 000-000-000-000" value="{{ $organization->tin }}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
            </div>
            
            

            <!-- RDO Code -->
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold">7</span>RDO
                    <span class="text-red-500 ml-1">*</span> <!-- Required field indicator -->
            
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with explanation of RDO -->
                        <div x-show="showTooltip"
                             x-cloak
                             x-transition:enter="transition-opacity duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            The RDO (Revenue District Office) Code is assigned to your organization based on its registered location with the Bureau of Internal Revenue (BIR). This code is used in all tax filings to identify the BIR office responsible for the organization.
                        </div>
                    </span>
                </label>
            

                <input type="text" name="rdo_code" placeholder="000-000-000-000" value="{{ $rdoCode }}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            

            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">8</span>Taxpayer's Name
                    <span class="text-red-500 ml-1">*</span> <!-- Required field indicator -->
            
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with clarification on the Taxpayer's Name -->
                        <div x-show="showTooltip"
                             x-cloak
                             x-transition:enter="transition-opacity duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            This is automatically populated from your organization's registration details. It refers to either the individual taxpayer's name or the name of the corporation (for non-individual taxpayers).
                        </div>
                    </span>
                </label>
            
                <!-- Display the Taxpayer's Name automatically fetched from the organization setup -->
                <input type="text" name="taxpayer_name" placeholder="e.g. Dela Cruz, Juan, Protacio" value="{{ $organization->registration_name }}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            

            <!-- Registered Address -->
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">9</span>Registered Address
                    <span class="text-red-500 ml-1">*</span> <!-- Required field indicator -->
            
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with explanation of Registered Address -->
                        <div x-show="showTooltip"
                             x-cloak
                             x-transition:enter="transition-opacity duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            This is the official address of your business, as registered with the government. It should reflect your company's legal location. <strong>This is automatically set through your organization's setup.</strong>
                        </div>
                    </span>
                </label>
            
                <!-- Display the Registered Address automatically fetched from the organization setup -->
                <input type="text" name="registered_address" value="{{ $organization->address_line . ', ' . $organization->city . ', ' . $organization->province . ', ' . $organization->region }}" placeholder="e.g. 145 Yakal St. ESL Bldg., San Antonio Village Makati NCR" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            

            <!-- Zip Code -->
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">9A</span>Zip Code
                    <span class="text-red-500 ml-1">*</span> <!-- Required field indicator -->
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with explanation of Zip Code -->
                        <div x-show="showTooltip"
                             x-cloak
                             x-transition:enter="transition-opacity duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            The Zip Code represents the postal code of the organization's registered address.
                            <br><br>This is automatically set based on the organization's setup and cannot be manually entered.
                        </div>
                    </span>
                </label>
            
                <!-- Display the Zip Code automatically fetched from the organization setup -->
                <input type="text" name="zip_code" value="{{ $organization->zip_code }}" placeholder="e.g. 1203" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
     
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">10</span>Contact Number
                    <span class="text-red-500 ml-1">*</span> <!-- Required field indicator -->
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with explanation of Contact Number -->
                        <div x-show="showTooltip"
                             x-cloak
                             x-transition:enter="transition-opacity duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            This is the contact number associated with the organization for official communication.
                            <br><br>This is automatically fetched from your organization's setup and cannot be manually entered.
                        </div>
                    </span>
                </label>
            
                <!-- Display the Contact Number automatically fetched from the organization setup -->
                <input type="number" name="contact_number" value="{{ $organization->contact_number }}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">11</span>Email Address
                    <span class="text-red-500 ml-1">*</span> <!-- Required field indicator -->
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with explanation of Email Address -->
                        <div x-show="showTooltip"
                             x-cloak
                             x-transition:enter="transition-opacity duration-300"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            This is the email address associated with the organization for official communication and notifications.
                            <br><br>This is automatically fetched from your organization's setup and cannot be manually entered.
                        </div>
                    </span>
                </label>
            
                <!-- Display the Email Address automatically fetched from the organization setup -->
                <input type="text" name="email_address" value="{{ $organization->email }}" placeholder="pedro@gmail.com" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">12</span>Are you availing of tax relief under Special Law or International Tax Treaty?
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with explanation of Tax Relief -->
                        <div x-show="showTooltip"
                             x-transition:enter="transition-opacity duration-300"
                             x-cloak
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            Select "Yes" if your organization is availing of any tax relief under special laws or international tax treaties.
                            <br><br>This field is necessary for proper classification of tax relief.
                        </div>
                    </span>
                </label>
            
                <!-- Radio options for tax relief selection -->
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="tax_relief" value="yes" class="mr-2" {{ old('tax_relief') == 'yes' ? 'checked' : '' }}> Yes
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="tax_relief" value="no" class="mr-2" {{ old('tax_relief') == 'no' ? 'checked' : '' }}> No
                    </label>
                </div>
            </div>
            
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-12 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">12A</span>If yes, specify
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with explanation -->
                        <div x-show="showTooltip"
                             x-transition:enter="transition-opacity duration-300"
                             x-cloak
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            If you selected "Yes" for tax relief under special laws or international tax treaties, provide the specific tax treaty or law that applies to your organization (e.g., "Philippine-US Tax Treaty").
                        </div>
                    </span>
                </label>
            
                <!-- Input field to specify the tax relief -->
                <input type="text" name="yes_specify" value="{{ old('yes_specify') }}"placeholder="Specified Tax Treaty" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">13</span>Only for individual taxpayers whose sales/receipts are subject to Percentage Tax under Section 116 of the Tax Code, as amended:
                    What income tax rates are you availing? (choose one) <br>(To be filled out only on the initial quarter of the taxable year)
                    <span class="relative ml-2">
                        <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
            
                        <!-- Tooltip Content with explanation -->
                        <div x-show="showTooltip"
                             x-transition:enter="transition-opacity duration-300"
                             x-cloak
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition-opacity duration-300"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            Select the tax rate you are availing under the Percentage Tax (Section 116 of the Tax Code). Choose between the graduated income tax rate (based on net taxable income) or the flat 8% rate on gross sales/receipts/others.
                            This section is only relevant for the initial quarter of the taxable year.
                        </div>
                    </span>
                </label>
            
                <!-- Radio buttons for tax rate selection -->
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="availed_tax_rate" value="Graduated" {{ old('availed_tax_rate') == 'Graduated' ? 'checked' : '' }} class="mr-2"> Graduated income tax rate on net taxable income 
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" name="availed_tax_rate" value="Flat_rate" {{ old('availed_tax_rate') == 'Flat_rate' ? 'checked' : '' }} class="mr-2"> 8% income tax rate on gross sales/receipts/others
                    </label>
                </div>
            </div>
            
    </div>
    <div class="border-b">
        <h3 class="font-semibold text-zinc-700 text-lg mb-4">Total Tax Payable</h3>
    
        <!-- Total Tax Due (Sum of tax_amount from Schedule 1) -->
        <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
            <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                <span class="font-bold mr-2">14</span>Total Tax Due (From Schedule 1 Item 7)
                <span class="relative ml-2">
                    <!-- Tooltip Icon with clickable toggle using Alpine.js -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                    </svg>
        
                    <!-- Tooltip Content with explanation -->
                    <div x-show="showTooltip"
                         x-transition:enter="transition-opacity duration-300"
                         x-cloak
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         x-transition:leave="transition-opacity duration-300"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                        This field displays the total tax due, derived automatically from Schedule 1, Item 7. It represents the computed tax liability for the reporting period. This value is read-only and cannot be edited manually.
                    </div>
                </span>
            </label>
        
            <!-- Read-only field for total tax due -->
            <input 
                type="number" 
                name="tax_due" 
                id="tax_due" 
                value="{{ number_format($summaryData->sum('tax_due'), 2, '.', '') }}" 
                readonly 
                class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
        </div>
        
    
       <!-- Creditable Percentage Tax Withheld -->
<div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
        <span class="font-bold mr-2">15</span>Creditable Percentage Tax Withheld per BIR Form No. 2307
        <span class="relative ml-2">
            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
            </svg>

            <!-- Tooltip Content with explanation -->
            <div x-show="showTooltip"
                 x-transition:enter="transition-opacity duration-300"
                 x-cloak
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                Enter the total amount of creditable percentage tax withheld based on the Certificate of Tax Withheld at Source (BIR Form No. 2307). 
                - **Required**: If withholding taxes have been applied. 
                - **Optional**: If no tax was withheld. 
                - Defaults to **0.00** for clarity when no amount has been withheld.
            </div>
        </span>
    </label>

    <!-- Input field for creditable tax -->
    <input 
    type="number" 
    name="creditable_tax" 
    id="creditable_tax" 
    value="{{ old('creditable_tax', '0.00') }}" 
    placeholder="e.g. 1000.00" 
    class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>


    

    <!-- Tax Paid in Return Previously Filed -->
<div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
        <span class="font-bold mr-2">16</span>Tax Paid in Return Previously Filed, if this is an Amended Return
        <span class="relative ml-2">
            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
            </svg>

            <!-- Tooltip Content with explanation -->
            <div x-show="showTooltip"
                 x-transition:enter="transition-opacity duration-300"
                 x-cloak
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                Enter the total tax amount paid as stated in the previously filed return if this is an amended return. 
                - **Optional**: This field can be left empty if this is not an amended return. 
                - If applicable, this value may be fetched from the previously filed return or manually entered.
                - Defaults to **0.00** for clarity if no prior tax has been paid.
            </div>
        </span>
    </label>

    <!-- Input field for amended tax -->
    <input 
        type="number" 
        name="amended_tax" 
        id="amended_tax" 
        value="{{ old('amended_tax', '0.00') }}" 
        placeholder="e.g. 500.00" 
        class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

        
    
<!-- Other Tax Credit/Payment -->
<div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
        <span class="font-bold mr-2">17</span>Other Tax Credit/Payment (specify)
        <span class="relative ml-2">
            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
            </svg>

            <!-- Tooltip Content -->
            <div x-show="showTooltip"
                 x-transition:enter="transition-opacity duration-300"
                 x-cloak
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                Provide details of any other tax credit or payment applied to this return, such as excess credits from prior periods. 
                - If applicable, describe the source of the credit in the first field and enter the corresponding amount in the second field.
                - These fields are **optional** but must be filled out if you are claiming an additional tax credit or payment.
                - Amount defaults to **0.00** if no additional credit is applied.
            </div>
        </span>
    </label>

    <!-- Input fields for other tax credit/payment -->
    <input 
        type="text" 
        name="other_tax_specify" 
        id="other_tax_specify" 
        value="{{ old('other_tax_specify') }}" 
        placeholder="Specify source" 
        class="w-1/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
    <input 
        type="number" 
        name="other_tax_amount" 
        id="other_tax_amount" 
        value="{{ old('other_tax_amount', '0.00') }}" 
        placeholder="e.g. 1000.00" 
        class="w-1/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

        
    
  <!-- Total Tax Credits/Payments -->
<div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
        <span class="font-bold mr-2">18</span>Total Tax Credits/Payments (Sum of Items 15 to 17)
        <span class="relative ml-2">
            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
            </svg>

            <!-- Tooltip Content -->
            <div x-show="showTooltip"
                 x-transition:enter="transition-opacity duration-300"
                 x-cloak
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                This field displays the total tax credits or payments by adding up values from Items 15 (Creditable Percentage Tax Withheld), 16 (Tax Paid in Return Previously Filed, if amended), and 17 (Other Tax Credit/Payment). 
                - This is **automatically calculated** and **readonly**. 
                - Ensure the fields for Items 15 to 17 are correctly filled to update this total.
            </div>
        </span>
    </label>

    <!-- Read-only field for total tax credits -->
    <input 
        type="number" 
        name="total_tax_credits" 
        id="total_tax_credits" 
        value="{{ old('total_tax_credits', '0.00') }}" 
        readonly 
        class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

 <!-- Tax Still Payable/(Overpayment) -->
<div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
        <Tax class="mr-2">19 Tax Still Payable/(Overpayment) (Item 14 Less Item 18)
        <span class="relative ml-2">
            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
            </svg>

            <!-- Tooltip Content -->
            <div x-show="showTooltip"
                 x-transition:enter="transition-opacity duration-300"
                 x-cloak
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                This field shows the remaining tax payable or overpayment. It is calculated by subtracting the total tax credits or payments (Item 18) from the total tax due (Item 14).
                - This is <b>automatically calculated</b> and <b>readonly</b>.
                - If the result is positive, you still owe tax. If negative, you have overpaid.
            </div>
        </span>
    </label>

    <!-- Read-only field for tax payable or overpayment -->
    <input 
        type="number" 
        name="tax_still_payable" 
        id="tax_still_payable" 
        value="{{ old('tax_still_payable', '0.00') }}" 
        readonly 
        class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

    
     <!-- Surcharge -->
<div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
        <b class="mr-2">20</b>Surcharge
        <span class="relative ml-2">
            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
            </svg>

            <!-- Tooltip Content -->
            <div x-show="showTooltip"
                 x-transition:enter="transition-opacity duration-300"
                 x-cloak
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                Enter the surcharge amount applicable, if any, based on the tax return. This is typically imposed as an additional fee when tax is not paid by the due date. 
                - This field is <b>optional</b> but should be filled if applicable.
            </div>
        </span>
    </label>

    <!-- Input field for surcharge -->
    <input 
        type="number" 
        name="surcharge" 
        id="surcharge" 
        value="{{ old('surcharge', '0.00') }}" 
        class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

   <!-- Interest -->
<div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
        <b class="mr-2">21</b>Interest
        <span class="relative ml-2">
            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
            </svg>

            <!-- Tooltip Content -->
            <div x-show="showTooltip"
                 x-transition:enter="transition-opacity duration-300"
                 x-cloak
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                Enter the total interest accrued due to the late payment of taxes. 
                This field is <b>optional</b> but should be filled if applicable.
            </div>
        </span>
    </label>

    <!-- Input field for interest -->
    <input 
        type="number" 
        name="interest" 
        id="interest" 
        value="{{ old('interest', '0.00') }}" 
        placeholder="e.g. 50.00" 
        class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

<!-- Compromise -->
<div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
        <b class="mr-2">22</b>Compromise
        <span class="relative ml-2">
            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
            </svg>

            <!-- Tooltip Content -->
            <div x-show="showTooltip"
                 x-transition:enter="transition-opacity duration-300"
                 x-cloak
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                Enter the total amount of any compromise settlement with the tax authorities. 
                This field is <b>optional</b> but should be filled if applicable.
            </div>
        </span>
    </label>

    <!-- Input field for compromise -->
    <input 
        type="number" 
        name="compromise" 
        id="compromise" 
        value="{{ old('compromise', '0.00') }}" 
        placeholder="e.g. 200.00" 
        class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>
<!-- Total Penalties -->
<div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
        <b class="mr-2">23</b>Total Penalties (Sum of Items 20 to 22)
        <span class="relative ml-2">
            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
            </svg>
        
            <!-- Tooltip Content -->
            <div x-show="showTooltip"
                 x-transition:enter="transition-opacity duration-300"
                 x-cloak
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                Enter the total penalties that apply to this return, such as surcharge and interest penalties.
                This field is <b>readonly</b> and the value is calculated automatically.
            </div>
        </span>
    </label>

    <input 
        type="number" 
        name="total_penalties" 
        id="total_penalties" 
        value="{{ old('total_penalties', '0.00') }}" 
        readonly 
        class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

<!-- Total Amount Payable/(Overpayment) -->
<div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
    <label class="indent-4 font-bold block text-zinc-700 text-sm w-1/3 flex items-center">
        <b class="mr-2">24</b>TOTAL AMOUNT PAYABLE/(Overpayment) (Sum of Items 19 and 23)
        <span class="relative ml-2">
            <!-- Tooltip Icon with clickable toggle using Alpine.js -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" class="cursor-pointer" @click="showTooltip = !showTooltip">
                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
            </svg>
        
            <!-- Tooltip Content -->
            <div x-show="showTooltip"
                 x-transition:enter="transition-opacity duration-300"
                 x-cloak
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                This field shows the total amount payable or overpaid, calculated from the sum of the tax still payable and penalties.
                It is <b>readonly</b> and will update automatically.
            </div>
        </span>
    </label>

    <input 
        type="number" 
        name="total_amount_payable" 
        id="total_amount_payable" 
        value="{{ old('total_amount_payable', '0.00') }}" 
        readonly 
        class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>



<div>
    @if($summaryData->isNotEmpty())
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
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif



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
    