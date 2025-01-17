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
                <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                        <b class="mr-2">1</b>For the
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
                                Select whether the return is for a Calendar or Fiscal year period. This choice affects the reporting period for your VAT return.
                            </div>
                        </span>
                    </label>
                
                    <div class="flex items-center space-x-4 w-2/3">
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="period" value="calendar" class="mr-2"
                                @if($tax2550q->period == 'calendar') checked @endif 
                                @if($tax2550q->period != 'calendar') disabled @endif> Calendar
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="period" value="fiscal" class="mr-2"
                                @if($tax2550q->period == 'fiscal') checked @endif 
                                @if($tax2550q->period != 'fiscal') disabled @endif> Fiscal
                        </label>
                    </div>
                </div>
                
            
                <!-- Year Ended -->
                <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                        <span class="font-bold mr-2">2</span>Year Ended
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
                                Select the end month and year of the reporting period. This field is required to determine the applicable VAT return period.
                            </div>
                        </span>
                    </label>
                
                    <input type="month" name="year_ended" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" value="{{ old('year_ended', $tax2550q->year_ended) }}">
                </div>
                
                <!-- Quarter -->
                <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                        <span class="font-bold mr-2">3</span>Quarter
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
                                Select the quarter corresponding to the reporting period for the VAT return. This determines the applicable quarter for the tax report.
                            </div>
                        </span>
                    </label>
                
                    <div class="flex items-center space-x-4 w-2/3">
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" value="1st" class="mr-2" 
                                @if($taxReturn->month == 'Q1') checked @endif> 1st
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" value="2nd" class="mr-2" 
                                @if($taxReturn->month == 'Q2') checked @endif> 2nd
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" value="3rd" class="mr-2" 
                                @if($taxReturn->month == 'Q3') checked @endif> 3rd
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="radio" name="quarter" value="4th" class="mr-2" 
                                @if($taxReturn->month == 'Q4') checked @endif> 4th
                        </label>
                    </div>
                </div>
                
                        

                <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                    <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                        <span class="font-bold mr-2">4</span>Return Period (From-To)
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
                                Specify the start and end dates of the VAT return period. This defines the time frame for the taxable period covered in the return.
                            </div>
                        </span>
                    </label>
                
                    <div class="flex items-center space-x-4 w-2/3">
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="date" 
                                   name="return_from" 
                                   value="{{ old('return_from',$tax2550q->return_from) }}" 
                                   class="mr-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"> 
                        </label>
                        <label class="flex items-center text-zinc-700 text-sm">
                            <input type="date" 
                                   name="return_to" 
                                   value="{{ old('return_to', $tax2550q->return_to) }}" 
                                   class="mr-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"> 
                        </label>
                    </div>
                    
                </div>
                

            <!-- Amended Return? -->
            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">5</span>Amended Return?
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
                            Indicate if this is an amended return. Choose "Yes" if you are filing a correction or revision of a previously submitted return.
                        </div>
                    </span>
                </label>
            
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" 
                               name="amended_return" 
                               value="yes" 
                               class="mr-2" 
                               {{ old('amended_return', $tax2550q->amended_return) == 'yes' ? 'checked' : '' }}> Yes
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" 
                               name="amended_return" 
                               value="no" 
                               class="mr-2" 
                               {{ old('amended_return', $tax2550q->amended_return) == 'no' ? 'checked' : '' }}> No
                    </label>
                </div>
                
            </div>
            

            <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">6</span>Short Period Return?
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
                            Select whether this is a short period return. Choose "Yes" if the tax period is shorter than the usual reporting period.
                        </div>
                    </span>
                </label>
            
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" 
                               name="short_period_return" 
                               value="yes" 
                               class="mr-2" 
                               {{ old('short_period_return', $tax2550q->short_period_return) == 'yes' ? 'checked' : '' }}> Yes
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" 
                               name="short_period_return" 
                               value="no" 
                               class="mr-2" 
                               {{ old('short_period_return', $tax2550q->short_period_return) == 'no' ? 'checked' : '' }}> No
                    </label>
                </div>
                
            </div>
            

        <!-- Background Information Section -->
        <div class="border-b">
            <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
            
            <!-- TIN -->
            <div x-data="{ showTooltip: false }" class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">7</span>Taxpayer Identification Number (TIN)
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
                            Enter the taxpayer’s unique identification number (TIN) issued by the Bureau of Internal Revenue (BIR).
                        </div>
                    </span>
                </label>
            
                <input readonly type="text" name="tin" placeholder="000-000-000-000" value="{{$tax2550q->tin}}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            

            <div x-data="{ showTooltip: false }" class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">8</span>RDO
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
                            Enter the RDO Code (Revenue District Office Code) assigned to your tax payer registration.
                        </div>
                    </span>
                </label>
            
                <input type="text" name="rdo_code" placeholder="000-000-000-000" value="{{ $tax2550q->rdo_code }}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            

            <!-- Taxpayer's Name -->
            <div x-data="{ showTooltip: false }" class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">9</span>Taxpayer's Name
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
                            Enter the full legal name of the taxpayer, formatted as: Last Name, First Name, Middle Name (if applicable).
                        </div>
                    </span>
                </label>
            
                <input type="text" name="taxpayer_name" value="{{$tax2550q->taxpayer_name}}" placeholder="e.g. Dela Cruz, Juan, Protacio" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            

            <!-- Registered Address -->
            <div x-data="{ showTooltip: false }" class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">10</span>Registered Address
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
                            Enter the full registered address of the taxpayer, including street, building, city, province, and region.
                        </div>
                    </span>
                </label>
            
                <input type="text" name="registered_address" value="{{ $tax2550q->registered_address}}" placeholder="e.g. 145 Yakal St. ESL Bldg., San Antonio Village Makati NCR" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            

            <!-- Zip Code -->
            <div x-data="{ showTooltip: false }" class="mb-2 flex items-start">
                <label class="indent-12 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">10A</span>Zip Code
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
                            Enter the Zip Code of the taxpayer's registered address.
                        </div>
                    </span>
                </label>
            
                <input type="text" name="zip_code" value="{{$tax2550q->zip_code}}" placeholder="e.g. 1203" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
     
            <div x-data="{ showTooltip: false }" class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">11</span>Contact Number
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
                            Enter the contact number for the taxpayer, including area code.
                        </div>
                    </span>
                </label>
            
                <input type="text" name="contact_number" value="{{ $tax2550q->contact_number }}" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
            <div x-data="{ showTooltip: false }" class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">12</span>Email Address
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
                            Enter a valid email address for the taxpayer.
                        </div>
                    </span>
                </label>
            
                <input type="text" name="email_address" value="{{ $tax2550q->email_address }}" placeholder="pedro@gmail.com" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
            <div x-data="{ showTooltip: false }" class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">13</span>Taxpayer Classification
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
                            Select the appropriate classification of the taxpayer based on size.
                        </div>
                    </span>
                </label>
            
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" 
                               name="taxpayer_classification" 
                               value="Micro" 
                               class="mr-2" 
                               {{ old('taxpayer_classification', $tax2550q->taxpayer_classification) == 'Micro' ? 'checked' : '' }}> Micro
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" 
                               name="taxpayer_classification" 
                               value="Small" 
                               class="mr-2" 
                               {{ old('taxpayer_classification', $tax2550q->taxpayer_classification) == 'Small' ? 'checked' : '' }}> Small
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" 
                               name="taxpayer_classification" 
                               value="Medium" 
                               class="mr-2" 
                               {{ old('taxpayer_classification', $tax2550q->taxpayer_classification) == 'Medium' ? 'checked' : '' }}> Medium
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" 
                               name="taxpayer_classification" 
                               value="Large" 
                               class="mr-2" 
                               {{ old('taxpayer_classification', $tax2550q->taxpayer_classification) == 'Large' ? 'checked' : '' }}> Large
                    </label>
                </div>
                
            </div>
            
            <div x-data="{ showTooltip: false }" class="mb-2 flex items-start">
                <label class="indent-4 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">14</span>Are you availing of tax relief under
                    Special Law or International Tax Treaty?
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
                            Select whether you are claiming tax relief under special laws or an international tax treaty.
                        </div>
                    </span>
                </label>
            
                <div class="flex items-center space-x-4 w-2/3">
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" 
                               name="tax_relief" 
                               value="yes" 
                               class="mr-2" 
                               {{ old('tax_relief', $tax2550q->tax_relief) == 'yes' ? 'checked' : '' }}> Yes
                    </label>
                    <label class="flex items-center text-zinc-700 text-sm">
                        <input type="radio" 
                               name="tax_relief" 
                               value="no" 
                               class="mr-2" 
                               {{ old('tax_relief', $tax2550q->tax_relief) == 'no' ? 'checked' : '' }}> No
                    </label>
                </div>
                
            </div>
            
            <div x-data="{ showTooltip: false }" class="mb-2 flex items-start">
                <label class="indent-12 block text-zinc-700 text-sm w-1/3 flex items-center">
                    <span class="font-bold mr-2">14A</span>If yes, specify
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
                            Specify the tax treaty or special law under which you are claiming tax relief, if applicable.
                        </div>
                    </span>
                </label>
            
                <input type="text" name="yes_specify" value="{{$tax2550q->yes_specify}}" placeholder="Specified Tax Treaty" class="w-2/3 p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            

        <div class="border-b">
            <h3 class="font-bold text-zinc-700 text-lg mb-4">Total Tax Payables</h3>
            <div class="grid grid-cols-3 gap-4 border-t mb-4 border-zinc-300 pt-2">
                <!-- Header Row -->
               
        
        <!-- VATable Sales -->
        <div x-data="{ showTooltip: false }" class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">15</span>Net VAT Payable/(Excess Input Tax) (From Part IV, Item 61)
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
                        Enter the total amount of VAT payable or excess input tax carried forward from Part IV, Item 61. This field reflects the result of your VAT calculation.
                    </div>
                </span>
            </label>
        </div>
        
        <div>
        
        </div>
        <div>
            
            <input 
                type="text"
                id="net_vat_payable" 
                value="{{ number_format($tax2550q->net_vat_payable, 2) }}" 
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
        </div>
        <div x-data="{ showTooltip: false }" class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">16</span>Creditable VAT Withheld (From Part V - Schedule 3, Column D)
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
                        Enter the amount of VAT withheld that is creditable, as shown in Part V, Schedule 3, Column D of your form. This amount can offset your VAT payable.
                    </div>
                </span>
            </label>
        </div>
        
        <div>
        </div>
        
        <div>
            <div>
                <input 
                    type="number" 
                    name="creditable_vat_withheld" 
                    id="creditable_vat_withheld" 
                    value="{{ old('creditable_vat_withheld', number_format($tax2550q->net_vat_payable, 2)) }}"

                    placeholder="e.g. 1000.00" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
            </div>
        </div>
        
        
        <div x-data="{ showTooltip: false }" class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">17</span>Advance VAT Payments (From Part V - Schedule 4)
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
                        Enter the total amount of advance VAT payments made as per Part V, Schedule 4 of your form. This amount is credited against your VAT liabilities.
                    </div>
                </span>
            </label>
        </div>
        
        <div>
        </div>
        
        <div>
            <div>
                <input 
                    type="text" 
                    name="advance_vat_payment" 
                    id="advance_vat_payment" 
                            value="{{ old('advance_vat_payment', number_format($tax2550q->advance_vat_payment, 2)) }}"
                    placeholder="e.g. 1000.00" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
            </div>
        </div>
        
        <div x-data="{ showTooltip: false }" class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">18</span>VAT paid in return previously filed, if this is an amended return
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
                        If this is an amended return, enter the amount of VAT that was paid in the previously filed return. This helps reconcile the amended VAT payable.
                    </div>
                </span>
            </label>
        </div>
        
        <div>
        </div>
        
        <div>
            <div>
                <input 
                    type="text" 
                    name="vat_paid_if_amended" 
                    id="vat_paid_if_amended" 
                      value="{{ old('vat_paid_if_amended', number_format($tax2550q->vat_paid_if_amended, 2)) }}"
                    placeholder="e.g. 500.00" 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
            </div>
        </div>
        
        <div x-data="{ showTooltip: false }" class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">19</span>Other Credits/Payment (Specify)
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
                        Specify the type of other credits or payments and enter the corresponding amount. This includes any other eligible credits that can be applied to your VAT liability.
                    </div>
                </span>
            </label>
        </div>
        
        <div>
            <input 
                type="text" 
                name="other_credits_specify" 
                value="{{ $tax2550q->other_credits_specify }}"
                id="other_credits_specify" 
                placeholder="Specify other credits"
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        
        <div>
            <input 
                type="text" 
                name="other_credits_specify_amount" 
                id="other_credits_specify_amount" 
                placeholder="e.g. 1000.00"
                  value="{{ old('other_credits_specify_amount', number_format($tax2550q->other_credits_specify_amount, 2)) }}"
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        
        <div x-data="{ showTooltip: false }" class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">20</span>Total Tax Credits/Payment (Sum of Items 16 to 19)
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
                        This field represents the total of all tax credits and payments from Items 16 to 19. Ensure the sum is accurate to reflect the correct total tax credits/payment.
                    </div>
                </span>
            </label>
        </div>
        
        <div>
        </div>
        
        <div>
            <input 
                type="text" 
                name="total_tax_credits" 
                id="total_tax_credits" 
                placeholder="Automatically calculated"
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        
        <div x-data="{ showTooltip: false }" class="indent-4 flex items-center">
            <label class="block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">21</span>Tax Still Payable/(Excess Credits) (Item 15 Less Item 20)
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
                        This field represents the tax still payable or excess credits. It is calculated by subtracting Item 20 from Item 15. Ensure this reflects the correct amount for accurate tax reporting.
                    </div>
                </span>
            </label>
        </div>
        
        <div>
        </div>
        
        <div>
            <input 
                type="text" 
                name="tax_still_payable" 
                id="tax_still_payable" 
                placeholder="Automatically calculated"
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        
        <div class="indent-4">
            <!-- "Add: Penalties" as a standalone line -->
            <div class="col-span-3">
                <label class="block text-zinc-700 text-sm mb-2">Add: Penalties</label>
            </div>
            
            <!-- "Surcharge" label with number and tooltip -->
            <div x-data="{ showTooltip: false }" class="flex items-center">
                <span class="font-bold mr-2">22</span>
                <label class="block text-zinc-700 text-sm flex items-center">
                    Surcharge
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
                            Enter the surcharge amount due for penalties. This field is used to input any additional charges applicable due to late payments or other penalties.
                        </div>
                    </span>
                </label>
            </div>
        </div>
        
        <div>
        </div>
        
        <div>
            <input 
                type="text" 
                name="surcharge" 
                id="surcharge" 
                value="{{ old('surcharge', number_format($tax2550q->surcharge, 2)) }}"
                placeholder="e.g. 500.00"
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        
        
        <div x-data="{ showTooltip: false }" class="flex items-center">
            <label class="indent-4 block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">23</span>Interest
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
                        Enter the interest amount applicable for any outstanding tax liabilities. This field accounts for interest accrued on unpaid taxes.
                    </div>
                </span>
            </label>
        </div>
        
        <div>
        </div>
        
        <div>
            <input 
                type="text" 
                name="interest" 
                id="interest" 
                 value="{{ old('interest', number_format($tax2550q->interest, 2)) }}"
                placeholder="e.g. 200.00"
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        
        
        <div x-data="{ showTooltip: false }" class="flex items-center">
            <label class="indent-4 block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">24</span>Compromise
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
                        Enter the amount for any compromise payments agreed upon with tax authorities. This field is used for negotiated settlements.
                    </div>
                </span>
            </label>
        </div>
        
        <div>
        </div>
        
        <div>
            <input 
                type="text" 
                name="compromise" 
                id="compromise" 
                   value="{{ old('compromise', number_format($tax2550q->compromise, 2)) }}"
                placeholder="e.g. 300.00"
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        

        <div class="flex items-center" x-data="{ showTooltip: false }">
            <label class="indent-4 block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">25</span>Total Penalties (Sum of Items 22 to 24)
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
                        Enter the total amount of penalties, which includes the surcharge, interest, and any compromise payments calculated from Items 22 to 24.
                    </div>
                </span>
            </label>
        </div>
        <div>
        </div>
        <div>
            <input 
                type="text" 
                name="total_penalties" 
                id="total_penalties" 
                  value="{{ old('total_penalties', number_format($tax2550q->total_penalties, 2)) }}"
                class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
            >
        </div>
        <div class="flex items-center" x-data="{ showTooltip: false }">
            <label class="indent-4 block text-zinc-700 text-sm flex items-center">
                <span class="font-bold mr-2">26</span>TOTAL AMOUNT PAYABLE/(Excess Credits) (Sum of Items 21 and 25)
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
                        Enter the total amount payable or excess credits, which is the result of adding the total penalties from the tax still payable, or the excess of credits over taxes due.
                    </div>
                </span>
            </label>
        </div>
        <div>
        </div>
        <div>
            <input 
                type="text" 
                name="total_amount_payable" 
                id="total_amount_payable" 
                 value="{{ old('total_amount_payable', number_format($tax2550q->total_amount_payable, 2)) }}"
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
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">31</span>VATable Sales
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
                            Enter the total VATable sales, which includes sales on goods and services subject to VAT.
                        </div>
                    </span>
                </label>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="vatable_sales" 
                    id="vatable_sales" 
                    value="{{ old('vatable_sales', number_format($tax2550q->vatable_sales, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="vatable_sales_tax_amount" 
                    id="vatable_sales_tax_amount" 
                  value="{{ old('vatable_sales_tax_amount', number_format($tax2550q->vatable_sales_tax_amount, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            

            <!-- Zero-Rated Sales -->
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">32</span>Zero-Rated Sales
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
                            Enter the total amount of zero-rated sales, which includes sales on goods and services that are exempt from VAT under specific conditions, such as exports or sales to international organizations.
                        </div>
                    </span>
                </label>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="zero_rated_sales" 
                    id="zero_rated_sales" 
                    value="{{ old('zero_rated_sales', number_format($tax2550q->zero_rated_sales, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            
            <div>
            </div>
            
            <!-- Exempt Sales -->
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">33</span>Exempt Sales
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
                            Enter the total amount of exempt sales, which includes sales of goods and services that are exempt from VAT under specific regulations.
                        </div>
                    </span>
                </label>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="exempt_sales" 
                    id="exempt_sales" 
                    value="{{ old('exempt_sales', number_format($tax2550q->exempt_sales, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            
            <div>
            </div>
            

            <!-- Total Sales & Output Tax Due -->
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">34</span>Total Sales & Output Tax Due (Sum of Items 31A to 33A) / (Item 31B)
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
                            Enter the total of sales and output tax due, which includes the sum of the sales and taxes calculated for VATable, Zero-Rated, and Exempt Sales.
                        </div>
                    </span>
                </label>
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
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">35</span>Less: Output VAT on Uncollected Receivables
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
                            Enter the output VAT amount related to receivables that have not been collected. This reduces the total output VAT due.
                        </div>
                    </span>
                </label>
            </div>
            
            <div></div>
            
            <div>
                <input 
                    type="text" 
                    name="uncollected_receivable_vat" 
                    id="uncollected_receivable_vat" 
                    value="{{ old('uncollected_receivable_vat', number_format($tax2550q->uncollected_receivable_vat, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateAdjustedOutputTax()">
            </div>
            

            <!-- Add: Output VAT on Recovered Uncollected Receivables Previously Deducted -->
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">36</span>Add: Output VAT on Recovered Uncollected Receivables Previously Deducted
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
                            Enter the output VAT amount for receivables that were previously uncollected and deducted but have now been recovered.
                        </div>
                    </span>
                </label>
            </div>
            
            <div></div>
            
            <div>
                <input 
                    type="text" 
                    name="recovered_uncollected_receivables" 
                    id="recovered_uncollected_receivables" 
                      value="{{ old('recovered_uncollected_receivables', number_format($tax2550q->recovered_uncollected_receivables, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateAdjustedOutputTax()">
            </div>
            
            <!-- Total Adjusted Output Tax Due -->
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">37</span>Total Adjusted Output Tax Due (Item 34B Less Item 35B Add Item 36B)
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
                            This field shows the total adjusted output tax due. It is calculated as Item 34B minus Item 35B plus Item 36B.
                        </div>
                    </span>
                </label>
            </div>
            
            <div></div>
            
            <div>
                <input 
                    type="text" 
                    name="total_adjusted_output_tax" 
                    id="total_adjusted_output_tax" 
                       value="{{ old('total_adjusted_output_tax', number_format($tax2550q->total_adjusted_output_tax, 2)) }}"
                    readonly 
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            

                <div class="indent-4 font-semibold text-zinc-700 text-sm">Less: Allowable Input Tax</div>
                <div class="font-semibold text-zinc-700 text-sm"></div>
                <div class="font-semibold text-zinc-700 text-sm">B. Input Tax</div>
                <!-- 38 Input Tax Carried Over from Previous Quarter -->
                <div class="flex items-center" x-data="{ showTooltip: false }">
                    <label class="block text-zinc-700 text-sm flex items-center">
                        <span class="font-bold mr-2">38</span>Input Tax Carried Over from Previous Quarter
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
                                This field shows the input tax carried over from the previous quarter. It represents any excess input tax that can be applied to the current quarter.
                            </div>
                        </span>
                    </label>
                </div>
                
                <div></div>
                
                <div>
                    <input 
                        type="text" 
                        name="input_carried_over" 
                        id="input_carried_over" 
          
                          value="{{ old('input_carried_over', number_format($tax2550q->input_carried_over, 2)) }}"
                        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                </div>
                
                
             <!-- 39 Input Tax Deferred on Capital Goods -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">39</span>Input Tax Deferred on Capital Goods Exceeding P1 Million from Previous Quarter (From Part V - Schedule 1 Col E)
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
                Enter the input tax deferred on capital goods exceeding P1 million from the previous quarter, as specified in Part V - Schedule 1, Column E.
            </div>
        </span>
    </label>
</div>

<div></div>

<div>
    <input 
        type="text" 
        name="input_tax_deferred" 
        id="input_tax_deferred"  
          value="{{ old('input_tax_deferred', number_format($tax2550q->input_tax_deferred, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

                
           <!-- 40 Transitional Input Tax -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">40</span>Transitional Input Tax
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
                Enter the transitional input tax applicable for the reporting period. This tax is used to offset VAT on initial inventories when transitioning to a VAT system.
            </div>
        </span>
    </label>
</div>

<div></div>

<div>
    <input 
        type="text" 
        name="transitional_input_tax" 
        id="transitional_input_tax" 
        value="{{ old('transitional_input_tax', number_format($tax2550q->transitional_input_tax, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

                
         <!-- 41 Presumptive Input Tax -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">41</span>Presumptive Input Tax
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
                Enter the presumptive input tax for the reporting period. This tax is a fixed input tax credit applied to certain industries or businesses.
            </div>
        </span>
    </label>
</div>

<div></div>

<div>
    <input 
        type="text" 
        name="presumptive_input_tax" 
        id="presumptive_input_tax" 
        value="{{ old('presumptive_input_tax', number_format($tax2550q->presumptive_input_tax, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

                
             <!-- 42 Others -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">42</span>Others (specify)
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
                Specify any other input taxes not categorized above. This field allows for additional input tax items.
            </div>
        </span>
    </label>
</div>

<div>
    <input 
        type="text" 
        name="other_specify" 
        id="other_specify" 
        placeholder="Other sources"
        value="{{ old('other_specify', $tax2550q->other_specify) }}" 
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

<div>
    <input 
        type="text" 
        name="other_input_tax" 
        id="other_input_tax" 
        value="{{ old('other_input_tax', number_format($tax2550q->other_input_tax, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

                
               <!-- 43 Total Input Tax -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">43</span>Total (Sum of Items 38B to 42B)
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
                This field calculates the total input tax by summing up all input tax items from 38B to 42B.
            </div>
        </span>
    </label>
</div>

<div></div>

<div>
    <input 
        type="text" 
        name="total_input_tax" 
        id="total_input_tax" 
        readonly 
               value="{{ old('total_input_tax', number_format($tax2550q->total_input_tax, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>

             <!-- Empty Cell for Alignment -->
             <div class="font-semibold text-zinc-700 text-sm">Current Transactions</div>
             <div class="font-semibold text-zinc-700 text-sm">A. Purchases</div>
             <div class="font-semibold text-zinc-700 text-sm">B. Input Tax</div>
             
         <!-- 44. Domestic Purchases (Dynamic Field) -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">44</span>Domestic Purchases
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
                This field calculates the total VAT on domestic purchases, including both goods and services.
            </div>
        </span>
    </label>
</div>

<div>
    <input 
        type="text" 
        name="domestic_purchase" 
        id="domestic_purchase" 
             value="{{ old('domestic_purchase', number_format($tax2550q->domestic_purchase, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
        onchange="calculateTotals()">
</div>

             <!-- 44B. Domestic Purchases Input Tax (Dynamic Field) -->
             <div>
                 <input 
                     type="text" 
                     name="domestic_purchase_input_tax" 
                     id="domestic_purchase_input_tax" 
                       value="{{ old('domestic_purchase_input_tax', number_format($tax2550q->domestic_purchase_input_tax, 2)) }}"
                     class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                     onchange="calculateTotals()">
             </div>
             
         <!-- 45. Services Rendered by Non-Residents (Dynamic Field) -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">45</span>Services Rendered by Non-Residents
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
                This field calculates VAT for services rendered by non-residents.
            </div>
        </span>
    </label>
</div>

<div>
    <input 
        type="text" 
        name="services_non_resident" 
        id="services_non_resident" 
           value="{{ old('services_non_resident', number_format($tax2550q->services_non_resident, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
        onchange="calculateTotals()">
</div>

<!-- 45B. Services Non-Resident Input Tax (Dynamic Field) -->
<div>
    <input 
        type="text" 
        name="services_non_resident_input_tax" 
        id="services_non_resident_input_tax" 
             value="{{ old('services_non_resident_input_tax', number_format($tax2550q->services_non_resident_input_tax, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
        onchange="calculateTotals()">
</div>

             
        <!-- 46. Importations (Dynamic Field) -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">46</span>Importations
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
                This field calculates VAT on importations of goods.
            </div>
        </span>
    </label>
</div>

<div>
    <input 
        type="text" 
        name="importations" 
        id="importations" 
        value="{{ old('importations', number_format($tax2550q->importations, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
        onchange="calculateTotals()">
</div>

<!-- 46B. Importations Input Tax (Dynamic Field) -->
<div>
    <input 
        type="text" 
        name="importations_input_tax" 
        id="importations_input_tax" 
         value="{{ old('importations_input_tax', number_format($tax2550q->importations_input_tax, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
        onchange="calculateTotals()">
</div>

             
          <!-- 47. Others (Specify) (Manual Input Field) -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">47</span>Others (Specify)
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
                Enter any other specific purchase type not covered by the other categories.
            </div>
        </span>
    </label>

    <input 
        type="text" 
        name="purchases_others_specify" 
        id="purchases_others_specify" 
        placeholder="Specify purchase"
        value="{{ old('purchases_others_specify', $tax2550q->purchases_others_specify) }}" 
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
        onchange="calculateTotals()">
</div>

<!-- 47B. Others (Specify Amount) (Manual Input Field) -->
<div>
    <input 
        type="text" 
        name="purchases_others_specify_amount" 
        id="purchases_others_specify_amount" 
            value="{{ old('purchases_others_specify_amount', number_format($tax2550q->purchases_others_specify_amount, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
        onchange="calculateTotals()">
</div>

<!-- 47C. Others (Specify Input Tax) (Manual Input Field) -->
<div>
    <input 
        type="text" 
        name="purchases_others_specify_input_tax" 
        id="purchases_others_specify_input_tax" 
           value="{{ old('purchases_others_specify_input_tax', number_format($tax2550q->purchases_others_specify_input_tax, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
        onchange="calculateTotals()">
</div>

             
 <!-- 48. Domestic Purchases with No Input Tax (Dynamic Field) -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">48</span>Domestic Purchases with No Input Tax
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
                Enter the value for domestic purchases that do not qualify for input tax.
            </div>
        </span>
    </label>
</div>
<div>
    <input 
        type="text" 
        name="domestic_no_input" 
        id="domestic_no_input" 
         value="{{ old('domestic_no_input', number_format($tax2550q->domestic_no_input, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
        onchange="calculateTotals()">
</div>
<div>
</div>
<!-- 49. VAT-Exempt Importation -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">49</span>VAT-Exempt Importation
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
                Enter the value for VAT-exempt importations, including goods or services that are not subject to VAT.
            </div>
        </span>
    </label>
</div>
<div>
    <input 
        type="text" 
        name="tax_exempt_importation" 
        id="tax_exempt_importation" 
          value="{{ old('tax_exempt_importation', number_format($tax2550q->tax_exempt_importation, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
        onchange="calculateTotals()">
</div>
<div>
</div>


             
           <!-- 50. Total Current Purchases/Input Tax (Calculated Field) -->
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">50</span>Total Current Purchases/Input Tax (Sum of Items 44A to 49A)/(Sum of Items 44B to 47B)
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
                 class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-64 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                Enter the total current purchases/input tax, calculated as the sum of items from 44A to 49A for purchases and items from 44B to 47B for input tax.
            </div>
        </span>
    </label>
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

             
<div class="flex items-center" x-data="{ showTooltip: false }">
    <label class="block text-zinc-700 text-sm flex items-center">
        <span class="font-bold mr-2">51</span>Total Available Input Tax (Sum of Items 43B and 50B)
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
                This field represents the total available input tax, which is the sum of the input tax from domestic purchases and importations as defined in Items 43B and 50B of the VAT 2550Q form. It reflects the tax you can claim against your output tax for the period.
            </div>
        </span>
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
          value="{{ old('total_available_input_tax', number_format($tax2550q->total_available_input_tax, 2)) }}"
        class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
</div>


            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">52</span>Input Tax on Purchases/Importation of Capital Goods exceeding P1 Million deferred for the succeeding period
                    (From Part V Schedule 1, Column I)
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
                            Enter the deferred input tax for capital goods purchases/importation exceeding P1 million, as required under Section 110 of the Tax Code. This input tax is to be recognized in future periods.
                        </div>
                    </span>
                </label>
            </div>
            <div>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="importation_million_deferred_input_tax" 
                    id="importation_million_deferred_input_tax" 
                       value="{{ old('importation_million_deferred_input_tax', number_format($tax2550q->importation_million_deferred_input_tax, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">53</span>Input Tax Attributable to VAT Exempt Sales (From Part V - Schedule 2)
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
                            This field is used to report the input tax that is attributable to VAT-exempt sales, as defined in Part V - Schedule 2 of the VAT 2550Q form. It is used for calculating the amount of input tax that is not eligible for credit due to exempt transactions.
                        </div>
                    </span>
                </label>
            </div>
            <div>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="attributable_vat_exempt_input_tax" 
                    id="attributable_vat_exempt_input_tax" 
                       value="{{ old('attributable_vat_exempt_input_tax', number_format($tax2550q->attributable_vat_exempt_input_tax, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">54</span>VAT Refund/TCC Claimed
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
                            This field refers to the VAT refund or Tax Credit Certificate (TCC) that the taxpayer claims for overpaid VAT. This is part of the process for VAT-registered businesses to recover excess input tax from the BIR.
                        </div>
                    </span>
                </label>
            </div>
            <div>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="vat_refund_input_tax" 
                    id="vat_refund_input_tax" 
                          value="{{ old('vat_refund_input_tax', number_format($tax2550q->vat_refund_input_tax, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">55</span>Input VAT on Unpaid Payables
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
                            This field is for input VAT that is attributable to payables which remain unpaid. It's used to recognize VAT that has been incurred on purchases but has not been settled.
                        </div>
                    </span>
                </label>
            </div>
            <div>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="unpaid_payables_input_tax" 
                    id="unpaid_payables_input_tax" 
                      value="{{ old('unpaid_payables_input_tax', number_format($tax2550q->unpaid_payables_input_tax, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">56</span>Others (specify)
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
                            Enter any other deductions that are not specified in the other fields.
                        </div>
                    </span>
                </label>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="other_deduction_specify" 
                    id="other_deduction_specify" 
                    value="{{ old('other_deduction_specify', $tax2550q->other_deduction_specify) }}"  
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="other_deduction_specify_input_tax" 
                    id="other_deduction_specify_input_tax" 
                        value="{{ old('other_deduction_specify_input_tax', number_format($tax2550q->other_deduction_specify_input_tax, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    onchange="calculateTotals()">
            </div>
            
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">57</span>Total Deductions from Input Tax (Sum of Items 52B to 56B)
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
                            Enter the total amount of deductions from input tax, calculated from the sum of Items 52B through 56B.
                        </div>
                    </span>
                </label>
            </div>
            <div>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="total_deductions_input_tax" 
                    id="total_deductions_input_tax" 
                             value="{{ old('total_deductions_input_tax', number_format($tax2550q->total_deductions_input_tax, 2)) }}"
                    readonly
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">58</span>Add: Input VAT on Settled Unpaid Payables Previously Deducted
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
                            Enter the input VAT on unpaid payables that were previously deducted but have now been settled.
                        </div>
                    </span>
                </label>
            </div>
            <div>
            </div>
            
            
            <div>
                <input 
                    type="text" 
                    name="settled_unpaid_input_tax" 
                    id="settled_unpaid_input_tax" 
                        value="{{ old('settled_unpaid_input_tax', number_format($tax2550q->settled_unpaid_input_tax, 2)) }}"
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">59</span>Adjusted Deductions from Input Tax (Sum of Items 57B and 58B)
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
                            Enter the adjusted deductions from input tax, which is the sum of previously deducted input tax that has been adjusted.
                        </div>
                    </span>
                </label>
            </div>
            <div>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="adjusted_deductions_input_tax" 
                    id="adjusted_deductions_input_tax" 
                    value="{{ old('adjusted_deductions_input_tax', number_format($tax2550q->adjusted_deductions_input_tax, 2)) }}" 
                    readonly
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">60</span>Total Allowable Input Tax (Item 51B Less Item 59B)
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
                            Enter the total allowable input tax, which is the result of subtracting item 59B (Adjusted Deductions) from item 51B (Total Available Input Tax).
                        </div>
                    </span>
                </label>
            </div>
            <div>
            </div>
            
            <div>
                <input 
                    type="text" 
                    name="total_allowable_input_Tax" 
                    id="total_allowable_input_Tax" 
                    value="{{ old('total_allowable_input_Tax', number_format($tax2550q->total_allowable_input_Tax, 2)) }}" 
                    readonly
                    class="w-full p-2 block px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
            </div>
            
            <div class="flex items-center" x-data="{ showTooltip: false }">
                <label class="block text-zinc-700 text-sm flex items-center">
                    <span class="font-bold mr-2">61</span>Net VAT Payable/(Excess Input Tax) (Item 37B Less Item 60B) (To Part II, Item 15)
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
                            Enter the net VAT payable or excess input tax, which is the result of subtracting item 60B (Total Allowable Input Tax) from item 37B (Total Output Tax). This amount is reported in Part II, Item 15 of the tax return.
                        </div>
                    </span>
                </label>
            </div>
            <div>
            </div>
            <div>
                <input 
                    type="text" 
                    name="excess_input_tax" 
                    id="excess_input_tax" 
                    value="{{ old('excess_input_tax', number_format($tax2550q->excess_input_tax, 2)) }}" 
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
        @if ($errors->any())
        <div x-data="{ show: true }" 
             x-show="show" 
             x-transition:enter="transform transition-transform duration-300"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             class="fixed top-5 right-5 z-50 w-full max-w-md">
            <div class="bg-white rounded-lg shadow-lg border border-red-500 p-4">
                <div class="flex justify-between items-center mb-3">
                    <h4 class="text-red-500 font-medium">Please fix the following errors:</h4>
                    <button @click="show = false" 
                    type="button"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="h-5 w-5" 
                             viewBox="0 0 20 20" 
                             fill="currentColor">
                            <path fill-rule="evenodd" 
                                  d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" 
                                  clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <ul class="space-y-2">
                    @foreach ($errors->all() as $error)
                        <li class="text-red-500 text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
        
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
        const tax_exempt_importation = parseFloat(document.getElementById('tax_exempt_importation').value) || 0;
    
        const othersSpecifyAmount = parseFloat(document.getElementById('purchases_others_specify_amount').value) || 0;
        const othersSpecifyInputTax = parseFloat(document.getElementById('purchases_others_specify_input_tax').value) || 0;
    
        // Calculate total purchases and total input tax
        const totalPurchases = domesticPurchase + servicesNonResident + importations + othersSpecifyAmount + domesticNoInput + tax_exempt_importation;
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
    