<x-app-layout>
    @php
    $deductionMethod = $individualTaxOptionRate->deduction_method ?? 'default'; // Get deduction method
    @endphp
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <button onclick="history.back()" class="flex items-center mb-4 text-gray-600 hover:text-gray-800 transition duration-150">
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
                            <p class="text-sm taxuri-color">BIR Form No. 1701-Q</p>
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
                <form action="{{ route('tax_return.store1701Q', ['taxReturn' => $taxReturn->id]) }}" method="POST">
                    @csrf
                    <div class="px-8 py-10">
                        <h3 class="font-bold text-zinc-700 text-lg mb-4">Filing Period</h3>
                        <!-- Year Ended -->
                        <div x-data="{ showTooltip: false }" class="mb-4 flex items-start gap-32">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">1</span>For the year
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Select the year for which the income tax return is being filed. This is typically the tax year relevant to your filing.
                                    </div>
                                </span>
                            </label>

                            <!-- Select dropdown for years -->
                            <select name="for_the_year" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
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
                        <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">2</span>Quarter
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Select the quarter for which the income tax return is being filed. The year is typically divided into four quarters.
                                    </div>
                                </span>
                            </label>

                            <div class="flex items-center space-x-4 w-2/3">
                                <label class="flex items-center">
                                    <input type="radio" name="quarter" @if($taxReturn->month == 'Q1') checked @endif value="1st" class="mr-2"> 1st
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
                        <div x-data="{ showTooltip: false }" class="mb-4 flex items-start">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">3</span>Amended Return?
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Indicate if this is an amended return. An amended return is filed to correct errors in a previously submitted return.
                                    </div>
                                </span>
                            </label>

                            <div class="flex items-center space-x-4 w-2/3">
                                <label class="flex items-center">
                                    <input type="radio" name="amended_return" value="yes" class="mr-2"
                                        {{ old('amended_return') == 'yes' ? 'checked' : '' }}> Yes
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="amended_return" value="no" class="mr-2"
                                        {{ old('amended_return') == 'no' ? 'checked' : '' }}> No
                                </label>
                            </div>
                        </div>

                        <!-- Number of Sheets Attached -->
                        <div x-data="{ showTooltip: false }" class="mb-4 flex flex-row justify-between gap-24">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-[40%] flex items-center"><span class="font-bold mr-2">4</span>Number of Sheets Attached
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Enter the total number of additional sheets or attachments you are submitting with this return.
                                    </div>
                                </span>
                            </label>
                            <input type="number" name="sheets" placeholder="No. of Sheets" 
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                value="{{ old('sheets') }}">
                        </div>

                        <!-- Background Information Section -->
                        <div class="border-b">
                            <h3 class="font-bold text-zinc-700 text-lg mb-4">Background Information</h3>
                            
                        <!-- Taxpayer Identification Number (TIN) -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between gap-6">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-[50%] flex items-center"><span class="font-bold mr-2">5</span>Taxpayer Identification Number (TIN)
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Enter the Taxpayer Identification Number (TIN) for the business or individual.
                                    </div>
                                </span>
                            </label>
                            
                            <input type="text" name="tin" placeholder="000-000-000-000" 
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                value="{{ old('tin', $organization->tin) }}">
                        </div>

                        <!-- Revenue District Office (RDO) Code -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between gap-6">
                        <label class="indent-4 block text-gray-700 text-sm font-medium w-[50%] flex items-center"><span class="font-bold mr-2">6</span>Revenue District Office (RDO) Code
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                    Enter the RDO Code assigned to your business by the Bureau of Internal Revenue (BIR).
                                </div>
                            </span>
                        </label>

                        <input type="text" name="rdo_code" value="{{ old('rdo_code', $rdoCode) }}" readonly 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                        </div>
                    
                        <!-- Taxpayer/Filer Type -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between gap-4">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">7</span>Taxpayer/Filer Type
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Select the type of taxpayer or filer. This helps define the tax responsibilities and filing requirements for the entity.
                                    </div>
                                </span>
                            </label>
                            
                            <div class="w-2/3">
                                <!-- Text input for Filer Type (readonly) -->
                                <input type="text" name="filer_type" 
                                    value="{{ old('filer_type', $taxReturn->individualBackgroundInformation->filer_type == 'single_proprietor' ? 'Single Proprietor' : 
                                                    ($taxReturn->individualBackgroundInformation->filer_type == 'professional' ? 'Professional' : 
                                                    ($taxReturn->individualBackgroundInformation->filer_type == 'estate' ? 'Estate' : 
                                                    'Trust')) ) }}" 
                                    readonly class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                            </div>
                        </div>

                        <!-- Alphanumeric Tax Code (ATC) -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between gap-4">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">8</span>Alphanumeric Tax Code (ATC)
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Enter the Alphanumeric Tax Code (ATC) assigned to the taxpayer, which identifies the tax type and filing period.
                                    </div>
                                </span>
                            </label>

                            <div class="w-2/3">
                                <!-- Read-only text field for Alphanumeric Tax Code (ATC) -->
                                <input type="text" name="alphanumeric_tax_code" value="{{ old('alphanumeric_tax_code', $taxReturn->individualBackgroundInformation->alphanumeric_tax_code) }}" 
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                            </div>
                        </div>

                        <!-- Taxpayer's Name -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between gap-32">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">9</span>Taxpayer's Name
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Enter the full name of the taxpayer as registered, including last name, first name, and middle name (if applicable).
                                    </div>
                                </span>
                            </label>

                            <input type="text" name="taxpayer_name" value="{{ old('taxpayer_name', $organization->registration_name) }}" 
                                placeholder="e.g. Dela Cruz, Juan, Protacio" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                        </div>

                        <!-- Registered Address -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between gap-32">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">10</span>Registered Address
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Provide the complete registered address of the organization including street, city, province, and region.
                                    </div>
                                </span>
                            </label>

                            <input type="text" name="registered_address" value="{{ old('registered_address', $organization->address_line . ', ' . $organization->city . ', ' . $organization->province . ', ' . $organization->region) }}" 
                                placeholder="e.g. 145 Yakal St. ESL Bldg., San Antonio Village Makati NCR" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                        </div>

                        <!-- Zip Code -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between gap-32">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">10A</span> Zip Code
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Enter the Zip Code associated with the registered address. This helps identify the geographic location of the organization.
                                    </div>
                                </span>
                            </label>

                            <input type="text" name="zip_code" value="{{ old('zip_code', $organization->zip_code) }}" 
                                placeholder="e.g. 1203" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                        </div>

                        <!-- Date of Birth -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">11</span>Date of Birth
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Enter the date of birth for the individual taxpayer. Ensure this is in the correct date format (YYYY-MM-DD).
                                    </div>
                                </span>
                            </label>

                            <div class="w-2/3">
                                <!-- Date input for Date of Birth -->
                                <input type="date" name="date_of_birth" 
                                    value="{{ old('date_of_birth', $taxReturn->individualBackgroundInformation->date_of_birth ? \Carbon\Carbon::parse($taxReturn->individualBackgroundInformation->date_of_birth)->format('Y-m-d') : '') }}" 
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonly>
                            </div>
                        </div>

                    
                        <!-- Email Address -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">12</span>Email Address
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Enter the taxpayer's email address. Make sure the email follows the standard format (e.g., name@domain.com).
                                    </div>
                                </span>
                            </label>

                            <div class="w-2/3">
                                <!-- Email Address input -->
                                <input type="text" name="email_address" value="{{ old('email_address', $organization->email) }}" 
                                    placeholder="pedro@gmail.com" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" readonyl>
                            </div>
                        </div>

                        <!-- Citizenship -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">13</span>Citizenship
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Enter the taxpayer's citizenship status. This is typically their nationality or citizenship in a country.
                                    </div>
                                </span>
                            </label>

                            <div class="w-2/3">
                                <!-- Readonly Citizenship input -->
                                <input type="text" name="citizenship" value="{{ old('citizenship', $taxReturn->individualBackgroundInformation->citizenship) }}" 
                                    readonly class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                            </div>
                        </div>


                        <!-- Foreign Tax Number -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">14</span>Foreign Tax Number
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Enter the taxpayer's foreign tax number if applicable. This is usually a tax identification number from another country.
                                    </div>
                                </span>
                            </label>

                            <div class="w-2/3">
                                <!-- Readonly Foreign Tax Number input -->
                                <input type="text" name="foreign_tax" 
                                    value="{{ old('foreign_tax', $taxReturn->individualBackgroundInformation->foreign_tax) }}" 
                                    readonly class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                            </div>
                        </div>


                        <!-- Claiming Foreign Tax Credits -->
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">15</span>Claiming Foreign Tax Credits?
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Select whether the taxpayer is claiming foreign tax credits. This option determines if foreign tax credits should be included in the tax calculation.
                                    </div>
                                </span>
                            </label>

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
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">16</span>Choose Tax Option
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Choose between graduated tax rates or an 8% tax on gross sales/receipts. This option will determine the tax calculation for the individual taxpayer.
                                    </div>
                                </span>
                            </label>

                            <div class="w-2/3 space-x-2">
                                <!-- Graduated Rates Option -->
                                <label class="inline-flex items-center">
                                    <input type="radio" name="individual_rate_type" value="graduated_rates"
                                        {{  $individualTaxOptionRate && $individualTaxOptionRate->rate_type === 'graduated_rates' ? 'checked' : 'disabled' }}
                                        class="form-radio text-blue-600">
                                    <span class="ml-2">Graduated Rates</span>
                                </label>
                                
                                <!-- 8% Gross Sales/Receipts Option -->
                                <label class="inline-flex items-center">
                                    <input type="radio" name="individual_rate_type" value="8_percent"
                                        {{  $individualTaxOptionRate && $individualTaxOptionRate->rate_type === '8_percent' ? 'checked' : 'disabled' }}
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
                        <div x-data="{ showTooltip: false }" class="mb-2 flex flex-row justify-between">
                            <label class="indent-4 block text-gray-700 text-sm font-medium w-1/3 flex items-center"><span class="font-bold mr-2">16A</span>Choose Deduction Method
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
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-4 mt-2 w-48 text-xs font-normal text-gray-700 bg-white border border-gray-300 rounded-lg shadow-lg">
                                        Select between itemized deductions or the Optional Standard Deduction (OSD). This will determine how your deductions are calculated for tax purposes.
                                    </div>
                                </span>
                            </label>
                        
                            <div class="w-2/3 space-x-2">
                                <!-- Itemized Deductions Option -->
                                <label class="inline-flex items-center">
                                    <input type="radio" name="individual_deduction_method" value="itemized"
                                        {{ old('individual_deduction_method', $individualTaxOptionRate->deduction_method === 'itemized' ? 'checked' : 'disabled') }}
                                        class="form-radio text-blue-600">
                                    <span class="ml-2">Itemized Deductions</span>
                                </label>
                        
                                <!-- Optional Standard Deduction (OSD) Option -->
                                <label class="inline-flex items-center">
                                    <input type="radio" name="individual_deduction_method" value="osd"
                                        {{ old('individual_deduction_method', $individualTaxOptionRate->deduction_method === 'osd' ? 'checked' : 'disabled') }}
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

                        <!-- Item 26: Tax Due (From Part V, Schedule I-Item 46 OR Schedule II-Item 54) -->
                        <h3 class="font-bold text-zinc-700 mt-4 text-lg mb-4">Total Tax Payable</h3>
                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>26</b> Tax Due (From Part V, Schedule I-Item 46 OR Schedule II-Item 54) </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-cloak
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field shows the calculated tax due based on the values from Part V, Schedule I-Item 46 or Schedule II-Item 54 dependent on selected deduction.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input type="number" name="show_tax_due" step="any" id="show_tax_due"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    readonly>
                            </div>
                        </div>

                        <!-- Item 27: Less: Tax Credits/Payments (From Part V, Schedule III-Item 62) -->
                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>27</b>  Less: Tax Credits/Payments (From Part V, Schedule III-Item 62)</label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-cloak
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field shows the total tax credits or payments applied, based on values from Part V, Schedule III-Item 62.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                type="number" 
                                name="show_tax_credits_payments" 
                                id="show_tax_credits_payments"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                readonly
                                step="any"
                                >
                            </div>
                        </div>

                        <!-- Item 28: Tax Payable/(Overpayment) (Item 26 Less Item 27) -->
                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>28</b> Tax Payable/(Overpayment) (Item 26 Less Item 27)</label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-cloak
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field shows the tax payable or overpayment, which is the difference between Item 26 and Item 27.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                type="number" 
                                name="show_tax_payable" 
                                id="show_tax_payable"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                readonly
                                step="any"
                                >
                            </div>
                        </div>


                        <!-- Item 29: Add: Total Penalties (From Part V, Schedule IV-Item 67) -->
                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>29</b> Add: Total Penalties (From Part V, Schedule IV-Item 67)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-cloak
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field shows the total penalties to be added, as listed in Part V, Schedule IV-Item 67.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                type="number" 
                                name="show_total_penalties" 
                                id="show_total_penalties"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                readonly
                                step="any"
                                >
                            </div>
                        </div>

                        <!-- Item 30: Total Amount Payable/(Overpayment) (Sum of Items 28 and 29) -->
                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>30</b> Total Amount Payable/(Overpayment) (Sum of Items 28 and 29)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-cloak
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field displays the total amount payable or overpaid, calculated by summing the values from Items 28 and 29.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                type="number" 
                                name="show_total_amount_payable" 
                                id="show_total_amount_payable"
                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                readonly
                                step="any"
                                >
                            </div>
                        </div>


                        <!-- Item 31: Aggregate Amount Payable/(Overpayment) (Sum of Items 30A and 30B) -->
                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>31</b> Aggregate Amount Payable/(Overpayment) (Sum of Items 30A and 30B)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-cloak
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field shows the total amount payable or overpaid, which is the sum of Items 30A and 30B.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                    type="number" 
                                    name="aggregate_amount_payable" 
                                    id="aggregate_amount_payable"
                                    step="any"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    readonly
                                >
                            </div>
                        </div>




                            @if($individualTaxOptionRate->rate_type === 'graduated_rates')
                                    <div class="border-b mt-4 border-t">
                                        <div class="grid grid-cols-3 gap-4 pt-2">
                                            <!-- Header Row -->
                                            <div class="font-semibold text-gray-700 text-sm">Declaration this Quarter</div>
                                            <div> </div>
                                            <div class="font-semibold text-gray-700 text-sm">A) Taxpayer/Filer</div>
                                
                                        </div>
                                    <!-- Item 36: Sales/Revenues/Receipts/Fees (net of sales returns, allowances, and discounts) -->
                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>36</b> Sales/Revenues/Receipts/Fees (net of sales returns, allowances, and discounts)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-cloak
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field represents the total sales, revenues, receipts, and fees, excluding any sales returns, allowances, and discounts.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                    type="number" 
                                    name="sales_revenues" 
                                    id="sales_revenues"
                                    step="any"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    value="{{ number_format($individualNetAmount, 2) }}" 
                                    onchange="calculateTotals()">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <!-- Item 37: Less: Cost of Sales/Services (if availing Itemized Deductions) -->
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>37</b> Less: Cost of Sales/Services (if availing Itemized Deductions)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-cloak
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field represents the cost of sales or services rendered, applicable only if you are availing Itemized Deductions.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                    type="number" 
                                    name="cost_of_sales" 
                                    id="cost_of_sales" 
                                    step="any"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    value="{{ number_format($individualCOGS, 2) }}" 
                                    onchange="calculateTotals()">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <!-- Item 38: Gross Income/(Loss) from Operation (Item 36 Less Item 37) -->
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>38</b> Gross Income/(Loss) from Operation (Item 36 Less Item 37)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-cloak
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field represents the gross income or loss from your operation, which is calculated by subtracting the cost of sales from your total sales.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                    type="number" 
                                    name="gross_income" 
                                    step="any"
                                    id="gross_income" 
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    onchange="calculateTotals()">
                            </div>
                        </div>
                            
                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <!-- Item 39: Total Allowable Itemized Deductions -->
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>39</b> Total Allowable Itemized Deductions
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-cloak
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field represents the total amount of itemized deductions that you are claiming. It includes expenses such as medical expenses, mortgage interest, and charitable donations.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                    type="number" 
                                    name="total_itemized_deductions" 
                                    step="any"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    value="{{ number_format($individualItemizedDeduction, 2) }}" 
                                    onchange="calculateTotals()">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <!-- Item 40: Optional Standard Deduction (OSD) -->
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>40</b> Optional Standard Deduction (OSD) (40% of Item 36)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition-opacity duration-300"
                                        x-cloak
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field represents the Optional Standard Deduction (OSD), which is 40% of your total sales, revenues, receipts, or fees (Item 36). It is an automatic deduction without the need to itemize specific expenses.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                    type="number" 
                                    name="osd" 
                                    step="any"
                                    id="osd"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    onchange="calculateTotals()">
                            </div>
                        </div>
            
                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <!-- Item 41: Net Income/(Loss) This Quarter -->
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>41</b> Net Income/(Loss) This Quarter (If Itemized: Item 38 Less Item 39; If OSD: Item 38 Less Item 40)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-cloak
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field calculates your Net Income or Loss for the quarter. If you're using Itemized Deductions, subtract Item 39 (Total Allowable Itemized Deductions) from Item 38 (Gross Income). If using OSD, subtract Item 40 (OSD) from Item 38.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                    type="number" 
                                    name="net_income" 
                                    step="any"
                                    id="net_income" 
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    onchange="calculateTotals()">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>Add: 42</b>  Taxable Income/(Loss) Previous Quarter/s
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-cloak
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        Enter the taxable income or loss from the previous quarter(s). This figure will be used to calculate your overall taxable income for the current quarter.
                                    </div>
                                </span>
                            </div>
                            <div></div>
                            <div>
                                <input 
                                    type="number" 
                                    name="taxable_income"
                                    step="any" 
                                    value="0.00"
                                    id="taxable_income"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    onchange="calculateTotals()">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>43</b> Non-Operating Income (specify)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-cloak
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        Enter any non-operating income and specify its nature. This includes income that is not directly related to the main operations of the business, such as investment income.
                                    </div>
                                </span>
                            </div>
                            <div>
                                <input 
                                    type="text" 
                                    name="graduated_non_op_specify" 
                                    id="graduated_non_op_specify"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    onchange="calculateTotals()">
                            </div>
                            <div>
                                <input 
                                    type="number" 
                                    step="any"
                                    value="0.00"
                                    name="graduated_non_op" 
                                    id="graduated_non_op"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    onchange="calculateTotals()">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>44</b> Amount Received/Share in Income by a Partner from General Professional Partnership (GPP)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-cloak
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        Enter the amount received or share of income received by a partner from a General Professional Partnership (GPP). This is the income attributed to you as a partner in such partnerships.
                                    </div>
                                </span>
                            </div>
                            <div>
                            </div>
                            <div>
                                <input 
                                    type="number" 
                                    step="any"
                                    name="partner_gpp" 
                                    value="0.00"
                                    id="partner_gpp"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    onchange="calculateTotals()">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>45</b> Total Taxable Income/(Loss) To Date (Sum of Items 41 to 44)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-cloak
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field represents the total taxable income or loss to date, calculated as the sum of items 41 to 44. It includes your net income, taxable income from previous quarters, non-operating income, and income from a partnership.
                                    </div>
                                </span>
                            </div>
                            <div>
                            </div>
                            <div>
                                <input 
                                    type="number" 
                                    step="any"
                                    name="graduated_total_taxable_income" 
                                    id="graduated_total_taxable_income"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    onchange="calculateTotals()">
                            </div>
                        </div>

                            
                        <div class="grid grid-cols-3 gap-4 pt-2">
                            <div class="flex items-center font-semibold relative">
                                <label class="block text-gray-700 text-sm font-medium"><b>46</b> TAX DUE (Item 45 x Applicable Tax Rate)
                                </label>
                                <!-- Tooltip Icon -->
                                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                    </svg>

                                    <!-- Tooltip Content -->
                                    <div x-show="showTooltip"
                                        x-transition:enter="transition-opacity duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-cloak
                                        x-transition:leave="transition-opacity duration-300"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                        This field represents the total tax due, calculated as the product of Item 45 (total taxable income) and the applicable tax rate. The tax rate is based on your chosen tax option.
                                    </div>
                                </span>
                            </div>
                            <div>
                            </div>
                            <div>
                                <input 
                                    type="number" 
                                    step="any"
                                    name="graduated_tax_due" 
                                    id="graduated_tax_due"
                                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    readonly
                                >
                            </div>
                        </div>
            
                        <!-- Schedule II - 8% IT Rate (if selected) -->
                        @elseif($individualTaxOptionRate->rate_type === '8_percent')
                            <div class="border-b mt-2 border-t">
                                <div class="grid grid-cols-3 gap-4 pt-2">
                                    <!-- Header Row -->
                                    <div class="font-semibold text-gray-700 text-sm">Declaration this Quarter</div>
                                    <div></div>
                                    <div class="font-semibold text-gray-700 text-sm">A) Taxpayer/Filer</div>
                            
                                </div>
                                <div class="grid grid-cols-3 gap-4 pt-2">
                                    <div class="flex items-center font-semibold relative">
                                        <label class="block text-gray-700 text-sm font-medium"><b>47</b> Sales/Revenues/Receipts/Fees (net of sales returns, allowances, and discounts)
                                        </label>
                                        <!-- Tooltip Icon -->
                                        <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                            </svg>
                                
                                            <!-- Tooltip Content -->
                                            <div x-show="showTooltip"
                                                x-transition:enter="transition-opacity duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-cloak
                                                x-transition:leave="transition-opacity duration-300"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                                This field represents your total sales, revenues, receipts, and fees for the quarter. It is calculated as the total amount after subtracting sales returns, allowances, and discounts.
                                            </div>
                                        </span>
                                    </div>
                                    <div>
                                    </div>
                                    <div>
                                        <input 
                                            type="number" 
                                            step="any"
                                            name="sales_revenues_8" 
                                            value="{{ number_format($individualNetAmount, 2) }}"
                                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            onchange="calculateTotals()"
                                        >
                                    </div>
                                </div>
                                
                    
                                <div class="grid grid-cols-3 gap-4 pt-2">
                                    <div class="flex items-center font-semibold relative">
                                        <label class="block text-gray-700 text-sm font-medium"><b>48</b> Add: Non-Operating Income (specify)
                                        </label>
                                        <!-- Tooltip Icon -->
                                        <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                            </svg>
                                
                                            <!-- Tooltip Content -->
                                            <div x-show="showTooltip"
                                                x-transition:enter="transition-opacity duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-cloak
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition-opacity duration-300"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                                This field allows you to specify any additional non-operating income that needs to be included. The specified amount will be added to the total non-operating income.
                                            </div>
                                        </span>
                                    </div>
                                    <div>
                                        <input 
                                            type="text" 
                                            name="non_op_specify_8" 
                                            id="non_op_specify_8"
                                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            onchange="calculateTotals()"
                                        >
                                    </div>
                                    <div>
                                        <input 
                                            type="number" 
                                                step="any"
                                            name="non_operating_8" 
                                                value="0.00"
                                            id="non_operating_8" 
                                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            onchange="calculateTotals()"
                                        >
                                    </div>
                                </div>
                                
                    
                                <div class="grid grid-cols-3 gap-4 pt-2">
                                    <div class="flex items-center font-semibold relative">
                                        <label class="block text-gray-700 text-sm font-medium"><b>49</b> Total Income for the Quarter (Sum of Items 47 and 48)
                                        </label>
                                        <!-- Tooltip Icon -->
                                        <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                            </svg>
                                
                                            <!-- Tooltip Content -->
                                            <div x-show="showTooltip"
                                                x-transition:enter="transition-opacity duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition-opacity duration-300"
                                                x-transition:leave-start="opacity-100"
                                                x-cloak
                                                x-transition:leave-end="opacity-0"
                                                class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                                This field is the sum of the total sales/revenues (Item 47) and non-operating income (Item 48). It represents the total income for the quarter.
                                            </div>
                                        </span>
                                    </div>
                                    <div></div>
                                    <div>
                                        <input 
                                            type="number" 
                                            step="any"
                                            name="total_income_8" 
                                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            onchange="calculateTotals()"
                                        >
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-4 pt-2">
                                    <div class="flex items-center font-semibold relative">
                                        <label class="block text-gray-700 text-sm font-medium"><b>50</b> Add: Total Taxable Income/(Loss) Previous Quarter (Item 51 of previous quarter)
                                        </label>
                                        <!-- Tooltip Icon -->
                                        <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                            </svg>
                                
                                            <!-- Tooltip Content -->
                                            <div x-show="showTooltip"
                                                x-transition:enter="transition-opacity duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition-opacity duration-300"
                                                x-cloak
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                                This field is the total taxable income or loss from the previous quarter (Item 51 of the previous quarter). It should be added to the current total taxable income calculation.
                                            </div>
                                        </span>
                                    </div>
                                    <div></div>
                                    <div>
                                        <input 
                                            type="number" 
                                            step="any"
                                            name="total_prev_8" 
                                                value="0.00"
                                            id="total_prev_8" 
                                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            onchange="calculateTotals()"
                                        >
                                    </div>
                                </div>
                                
                            </div>
                                <!-- 51 Cumulative Taxable Income/(Loss) as of This Quarter (Sum of Items 49 and 50) -->
                    
                                <div class="grid grid-cols-3 gap-4 pt-2">
                                    <div class="flex items-center font-semibold relative">
                                        <label class="block text-gray-700 text-sm font-medium"><b>51</b> Cumulative Taxable Income/(Loss) as of This Quarter (Sum of Items 49 and 50)
                                        </label>
                                        <!-- Tooltip Icon -->
                                        <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                            </svg>
                                
                                            <!-- Tooltip Content -->
                                            <div x-show="showTooltip"
                                                x-transition:enter="transition-opacity duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-cloak
                                                x-transition:leave="transition-opacity duration-300"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                                This field is the cumulative taxable income or loss as of the current quarter. It is the sum of Items 49 and 50.
                                            </div>
                                        </span>
                                    </div>
                                    <div></div>
                                    <div>
                                        <input 
                                            type="number" 
                                            step="any"
                                            name="cumulative_taxable_income_8" 
                                            id="cumulative_taxable_income_8" 
                                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            onchange="calculateTotals()"
                                        >
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-4 pt-2">
                                    <div class="flex items-center font-semibold relative">
                                        <label class="block text-gray-700 text-sm font-medium"><b>52</b> Less: Allowable reduction from gross sales/receipts and other non-operating income of purely self-employed individuals
                                            and/or professionals in the amount of P 250,000
                                        </label>
                                        <!-- Tooltip Icon -->
                                        <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                            </svg>
                                
                                            <!-- Tooltip Content -->
                                            <div x-show="showTooltip"
                                                x-transition:enter="transition-opacity duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-cloak
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition-opacity duration-300"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                                This field represents the allowable reduction from gross sales/receipts and non-operating income of self-employed individuals and/or professionals, amounting to P 250,000.
                                            </div>
                                        </span>
                                    </div>
                                    <div></div>
                                    <div>
                                        <input 
                                            type="number" 
                                            step="any"
                                            name="allowable_reduction_8" 
                                                value="0.00"
                                            id="allowable_reduction_8" 
                                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            onchange="calculateTotals()"
                                        >
                                    </div>
                                </div>
                                
                    
                                <div class="grid grid-cols-3 gap-4 pt-2">
                                    <div class="flex items-center font-semibold relative">
                                        <label class="block text-gray-700 text-sm font-medium"><b>53</b> Taxable Income/(Loss) To Date (Item 51 Less Item 52)
                                        </label>
                                        <!-- Tooltip Icon -->
                                        <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                            </svg>
                                
                                            <!-- Tooltip Content -->
                                            <div x-show="showTooltip"
                                                x-transition:enter="transition-opacity duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-cloak
                                                x-transition:leave="transition-opacity duration-300"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                                This field represents the taxable income (or loss) to date, which is calculated by subtracting Item 52 from Item 51.
                                            </div>
                                        </span>
                                    </div>
                                    <div></div>
                                    <div>
                                        <input 
                                            type="number" 
                                            step="any"
                                            name="taxable_income_8" 
                                            id="taxable_income_8" 
                                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            onchange="calculateTotals()"
                                        >
                                    </div>
                                </div>
                                
                    
                                <!-- Item 54: Tax Due -->
                                <div class="grid grid-cols-3 gap-4 pt-2">
                                    <div class="flex items-center font-semibold relative">
                                        <label class="block text-gray-700 text-sm font-medium"><b>54</b> TAX DUE (Item 53 x 8% Tax Rate)
                                        </label>
                                        <!-- Tooltip Icon -->
                                        <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                                            </svg>
                                
                                            <!-- Tooltip Content -->
                                            <div x-show="showTooltip"
                                                x-transition:enter="transition-opacity duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-cloak
                                                x-transition:leave="transition-opacity duration-300"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                                                This field represents the tax due, calculated by multiplying Item 53 (Taxable Income) by the 8% tax rate.
                                            </div>
                                        </span>
                                    </div>
                                    <div></div>
                                    <div>
                                        <input 
                                            type="number" 
                                            step="any"
                                            name="tax_due_8" 
                                            id="tax_due_8"
                                            readonly
                                            step="any"
                                            class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            onchange="calculateTotals()"
                                        >
                                    </div>
                                </div>
                                
                            </div>
                            @endif
                        </div>
            </div>
                <!-- Schedule III - Tax Credits/Payments -->
            <div class="grid grid-cols-3 gap-4 pt-4">
                <!-- Item 55: Prior Year’s Excess Credits -->
                <div class="flex items-center font-semibold relative">
                    <label class="block text-gray-700 text-sm font-medium"><b>55</b> Prior Year’s Excess Credits
                    </label>
                    <!-- Tooltip Icon -->
                    <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content -->
                        <div x-show="showTooltip"
                            x-transition:enter="transition-opacity duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-cloak
                            x-transition:leave="transition-opacity duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            This field is for any excess credits carried over from the previous year.
                        </div>
                    </span>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        step="any"
                        name="prior_year_credits" 
                        value="0.00"
                        id="prior_year_credits" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        onchange="calculateTotals()"
                    >
                </div>
                
                <div class="flex items-center font-semibold relative">
                    <label class="block text-gray-700 text-sm font-medium"><b>56</b> Tax Payment/s for the Previous Quarter/s
                    </label>
                    <!-- Tooltip Icon -->
                    <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content -->
                        <div x-show="showTooltip"
                            x-transition:enter="transition-opacity duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-cloak
                            x-transition:leave="transition-opacity duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            This field is for any tax payments made in the previous quarter(s).
                        </div>
                    </span>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        step="any"
                        value="0.00"
                        name="tax_payments_prev_quarters" 
                        id="tax_payments_prev_quarters" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        onchange="calculateTotals()"
                    >
                </div>
                

                <div class="flex items-center font-semibold relative">
                    <label class="block text-gray-700 text-sm font-medium"><b>57</b> Creditable Tax Withheld for the Previous Quarter/s
                    </label>
                    <!-- Tooltip Icon -->
                    <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content -->
                        <div x-show="showTooltip"
                            x-transition:enter="transition-opacity duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-cloak
                            x-transition:leave="transition-opacity duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            Enter the total amount of creditable tax withheld in the previous quarter(s).
                        </div>
                    </span>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        step="any"
                        value="0.00"
                        name="creditable_tax_withheld_prev_quarters" 
                        id="creditable_tax_withheld_prev_quarters" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        onchange="calculateTotals()"
                    >
                </div>
                

                <div class="flex items-center font-semibold relative">
                    <label class="block text-gray-700 text-sm font-medium"><b>58</b> Creditable Tax Withheld per BIR Form No. 2307 for this Quarter
                    </label>
                    <!-- Tooltip Icon -->
                    <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content -->
                        <div x-show="showTooltip"
                            x-transition:enter="transition-opacity duration-300"
                            x-transition:enter-start="opacity-0"
                            x-cloak
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            Enter the creditable tax withheld per BIR Form No. 2307 for the current quarter.
                        </div>
                    </span>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        step="any"
                        value="0.00"
                        name="creditable_tax_withheld_bir" 
                        id="creditable_tax_withheld_bir" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        onchange="calculateTotals()"
                    >
                </div>
                

                <div class="flex items-center font-semibold relative">
                    <label class="block text-gray-700 text-sm font-medium"><b>59</b> Tax Paid in Return Previously Filed, if this is an Amended Return
                    </label>
                    <!-- Tooltip Icon -->
                    <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content -->
                        <div x-show="showTooltip"
                            x-transition:enter="transition-opacity duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-cloak
                            x-transition:leave="transition-opacity duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            Enter the tax paid in a return that was previously filed, if this is an amended return.
                        </div>
                    </span>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        step="any"
                        name="tax_paid_prev_return" 
                        value="0.00"
                        id="tax_paid_prev_return" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        onchange="calculateTotals()"
                    >
                </div>
                

                <div class="flex items-center font-semibold relative">
                    <label class="block text-gray-700 text-sm font-medium"><b>60</b> Foreign Tax Credits, if applicable
                    </label>
                    <!-- Tooltip Icon -->
                    <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content -->
                        <div x-show="showTooltip"
                            x-transition:enter="transition-opacity duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-cloak
                            x-transition:leave="transition-opacity duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            Enter the amount of foreign tax credits applicable, if any.
                        </div>
                    </span>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        step="any"
                        name="foreign_tax_credits" 
                        id="foreign_tax_credits" 
                        value="0.00"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        onchange="calculateTotals()"
                    >
                </div>
                

                <div class="flex items-center font-semibold relative">
                    <label class="block text-gray-700 text-sm font-medium"><b>61</b> Other Tax Credits/Payments (specify)
                    </label>
                    <!-- Tooltip Icon -->
                    <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content -->
                        <div x-show="showTooltip"
                            x-transition:enter="transition-opacity duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-300"
                            x-transition:leave-start="opacity-100"
                            x-cloak
                            x-transition:leave-end="opacity-0"
                            class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            Enter any other tax credits or payments that apply, if applicable.
                        </div>
                    </span>
                </div>
                <div>
                    <input 
                    type="text" 
                    name="other_tax_credits_specify" 
                    id="other_tax_credits_specify" 
                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                
                >
                </div>
                <div>
                    <input 
                        type="number" 
                        step="any"
                        value="0.00"
                        name="other_tax_credits" 
                        id="other_tax_credits" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        onchange="calculateTotals()"
                    >
                </div>
                

                <div class="flex items-center font-semibold relative">
                    <label class="block text-gray-700 text-sm font-medium"><b>62</b> Total Tax Credits/Payments (Sum of Items 55 to 61) (To Part III, Item 27)
                    </label>
                    <!-- Tooltip Icon -->
                    <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content -->
                        <div x-show="showTooltip"
                            x-transition:enter="transition-opacity duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            This field sums the total tax credits and payments from previous items, including credits for prior year's excess and foreign tax credits.
                        </div>
                    </span>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        step="any"
                        name="total_tax_credits" 
                        id="total_tax_credits" 
                        value="0.00"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        readonly>
                </div>
                

                <div class="flex items-center font-semibold relative">
                    <label class="block text-gray-700 text-sm font-medium"><b>63</b> Tax Payable/(Overpayment) (Item 46 or 54, Less Item 62) (To Part III, Item 28)
                    </label>
                    <!-- Tooltip Icon -->
                    <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content -->
                        <div x-show="showTooltip"
                            x-transition:enter="transition-opacity duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            This field calculates the tax payable or overpayment. It subtracts the total tax credits/payments (Item 62) from the tax due (Item 46 or 54).
                        </div>
                    </span>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        step="any"
                        name="tax_payable" 
                        id="tax_payable" 
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        readonly>
                </div>
                
                <div class="flex items-center font-semibold relative">
                    <label class="block text-gray-700 text-sm font-medium"><b>64</b> Surcharge
                    </label>
                    <!-- Tooltip Icon -->
                    <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                        <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                            <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                        </svg>
                        <!-- Tooltip Content -->
                        <div x-show="showTooltip"
                            x-transition:enter="transition-opacity duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-300"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                            This field is for entering any applicable surcharge amount as per the tax regulations.
                        </div>
                    </span>
                </div>
                <div></div>
                <div>
                    <input 
                        type="number" 
                        step="any"
                        name="surcharge" 
                        value="0.00"
                        id="surcharge" 
                        onchange="calculateTotals()"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                    >
                </div>
                
            <div class="flex items-center font-semibold relative">
                <label class="block text-gray-700 text-sm font-medium"><b>65</b> Interest
                </label>
                <!-- Tooltip Icon -->
                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                    </svg>
                    <!-- Tooltip Content -->
                    <div x-show="showTooltip"
                        x-transition:enter="transition-opacity duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                        This field is for entering any interest amount applicable on overdue taxes or other related financial obligations.
                    </div>
                </span>
            </div>
            <div></div>
            <div>
                <input 
                    type="number" 
                    step="any"
                    value="0.00"
                    name="interest" 
                    id="interest" 
                    onchange="calculateTotals()"
                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
            </div>

            <div class="flex items-center font-semibold relative">
                <label class="block text-gray-700 text-sm font-medium"><b>66</b> Compromise
                </label>
                <!-- Tooltip Icon -->
                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                    </svg>
                    <!-- Tooltip Content -->
                    <div x-show="showTooltip"
                        x-transition:enter="transition-opacity duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                        This field is for entering the compromise amount agreed upon for settling tax liabilities.
                    </div>
                </span>
            </div>
            <div></div>
            <div>
                <input 
                    type="number" 
                    step="any"
                    value="0.00"
                    name="compromise" 
                    id="compromise" 
                    onchange="calculateTotals()"
                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
            </div>

            <div class="flex items-center font-semibold relative">
                <label class="block text-gray-700 text-sm font-medium"><b>67</b> Total Penalties (Sum of Items 22 to 24)
                </label>
                <!-- Tooltip Icon -->
                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                    </svg>
                    <!-- Tooltip Content -->
                    <div x-show="showTooltip"
                        x-transition:enter="transition-opacity duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                        This field calculates the total penalties based on the sum of Items 22 to 24.
                    </div>
                </span>
            </div>
            <div></div>
            <div>
                <input 
                    type="number" 
                    step="any"
                    name="total_penalties" 
                    id="total_penalties" 
                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
            </div>

            <div class="flex items-center font-semibold relative">
                <label class="block text-gray-700 text-sm font-medium"><b>68</b> Total Amount Payable/(Overpayment) (Sum of Items 63 and 67) (To Part III, Item 30)
                </label>
                <!-- Tooltip Icon -->
                <span class="ml-2 cursor-pointer" x-data="{ showTooltip: false }" @click="showTooltip = !showTooltip">
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                        <path fill="#3f3f46" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2" />
                    </svg>
                    <!-- Tooltip Content -->
                    <div x-show="showTooltip"
                        x-transition:enter="transition-opacity duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="z-50 absolute left-1/2 transform -translate-x-1/2 p-2 mt-2 w-48 text-xs font-normal text-zinc-700 bg-white border border-zinc-300 rounded-lg shadow-lg">
                        This field calculates the total amount payable or overpayment based on the sum of Items 63 and 67.
                    </div>
                </span>
            </div>
            <div></div>
            <div>
                <input 
                    type="number" 
                    step="any"
                    name="total_amount_payable" 
                    id="total_amount_payable" 
                    readonly
                    class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                >
            </div>



                <div>
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
                
            
                <div class="mt-6">
                    <button class="w-56 bg-blue-900 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-950">
                        Proceed to Report
                    </button>
                </div>
                
            </div>
                    </div>




            </form>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
function calculateTotalPayable() {
    // Get Tax Payable (Item 63)
    const taxPayable = parseFloat(document.getElementById('tax_payable')?.value) || 0;
    
    // Get Total Penalties (Item 67)
    const totalPenalties = parseFloat(document.getElementById('total_penalties')?.value) || 0;
    
    // Calculate Total Amount Payable (Item 68)
    const totalAmountPayable = taxPayable + totalPenalties;
    
    // Update the Total Amount Payable field (Item 68)
    document.getElementById('total_amount_payable').value = totalAmountPayable.toFixed(2);
}

 function calculateAggregateAmountPayable() {
        // Get the values for Total Amount Payable (Item 30A)
        const totalAmountPayable = parseFloat(document.getElementById('show_total_amount_payable')?.value) || 0;
   

        // Calculate the Aggregate Amount Payable (Item 31)
        const aggregateAmountPayable = totalAmountPayable;

        // Update the Aggregate Amount Payable field
        document.getElementById('aggregate_amount_payable').value = aggregateAmountPayable.toFixed(2);
    }

    // Fetch the deduction method value passed from backend (Laravel Blade)
    const deductionMethod = "{{ $deductionMethod }}"; // This value comes from backend
    function calculateTotalAmountPayable() {
        // Get values for Tax Payable (Item 28) and Total Penalties (Item 29) for individual 
        const taxPayable = parseFloat(document.getElementById('show_tax_payable')?.value) || 0;
   
        
        const totalPenalties = parseFloat(document.getElementById('show_total_penalties')?.value) || 0;
    

        // Calculate Total Amount Payable (Item 30) for individual 
        const totalAmountPayable = taxPayable + totalPenalties;
    

        // Update the Total Amount Payable fields
        document.getElementById('show_total_amount_payable').value = totalAmountPayable.toFixed(2);

    }
    
    function calculateTaxPayable() {
        // Get the values for Tax Due (Item 26) and Tax Credits/Payments (Item 27)
        const taxDue = parseFloat(document.getElementById('show_tax_due')?.value) || 0;
      
        
        const taxCreditsPayments = parseFloat(document.getElementById('show_tax_credits_payments')?.value) || 0;
     

        // Calculate Tax Payable/(Overpayment) (Item 28) for individual and spouse
        const taxPayable = taxDue - taxCreditsPayments;


        // Update the Tax Payable/(Overpayment) fields
        document.getElementById('show_tax_payable').value = taxPayable.toFixed(2);
    
    }


    function calculatePenalties() {
    // Get values from the input fields for the individual
    const surcharge = parseFloat(document.getElementById('surcharge').value) || 0;
    const interest = parseFloat(document.getElementById('interest').value) || 0;
    const compromise = parseFloat(document.getElementById('compromise').value) || 0;


    // Calculate Total Penalties (Item 67) for individual
    const totalPenalties = surcharge + interest + compromise;


    // Update the total penalties fields
    document.getElementById('total_penalties').value = totalPenalties.toFixed(2);
    document.getElementById('show_total_penalties').value = totalPenalties.toFixed(2);

}


function calculateCredits() {
    // Get values from the input fields for both individual and spouse
    const priorYearCredits = parseFloat(document.getElementById('prior_year_credits')?.value) || 0;


    const taxPaymentsPrevQuarters = parseFloat(document.getElementById('tax_payments_prev_quarters')?.value) || 0;
   

    const creditableTaxWithheldPrevQuarters = parseFloat(document.getElementById('creditable_tax_withheld_prev_quarters')?.value) || 0;
   

    const creditableTaxWithheldBIR = parseFloat(document.getElementById('creditable_tax_withheld_bir')?.value) || 0;


    const taxPaidPrevReturn = parseFloat(document.getElementById('tax_paid_prev_return')?.value) || 0;
  

    const foreignTaxCredits = parseFloat(document.getElementById('foreign_tax_credits')?.value) || 0;
   

    const otherTaxCredits = parseFloat(document.getElementById('other_tax_credits')?.value) || 0;
  

    // Calculate Total Tax Credits (Item 62)
    const totalTaxCredits =
        (priorYearCredits + taxPaymentsPrevQuarters + creditableTaxWithheldPrevQuarters + creditableTaxWithheldBIR + 
        taxPaidPrevReturn + foreignTaxCredits + otherTaxCredits);



    // Update total tax credits fields
    document.getElementById('total_tax_credits').value = totalTaxCredits.toFixed(2);
    document.getElementById('show_tax_credits_payments').value = totalTaxCredits.toFixed(2);


    // Get the tax option rate for the individual (Graduated Rates or 8% Gross Sales/Receipts)
    const individualTaxOptionRate = document.querySelector('input[name="individual_rate_type"]:checked')?.value;

    let individualTaxDue = 0;


    // Separate calculations based on selected tax option (Graduated Rates or 8% Gross Sales/Receipts)
    if (individualTaxOptionRate === 'graduated_rates') {
        // If "Graduated Rates" is selected, use Item 46 (Graduated Tax Due)
        const graduatedTaxDue = document.getElementById('graduated_tax_due');
        if (graduatedTaxDue) {
            individualTaxDue = parseFloat(graduatedTaxDue.value) || 0;
        }
   
    } else if (individualTaxOptionRate === '8_percent') {
        // If "8% Gross Sales/Receipts" is selected, use Item 54 (Tax Due with 8% Tax Rate)
        const taxDue8 = document.getElementById('tax_due_8');
        if (taxDue8) {
            individualTaxDue = parseFloat(taxDue8.value) || 0;
        }
        
    }

    // Calculate Tax Payable/(Overpayment) (Item 63) for individual
    const individualTaxPayable = individualTaxDue - totalTaxCredits;


    // Update tax payable fields
    document.getElementById('tax_payable').value = individualTaxPayable.toFixed(2);

}



    function calculateTaxDueFlat() {
    // Get values for the individual
    const cumulativeIncomeIndividual = parseFloat(document.getElementById('cumulative_taxable_income_8').value) || 0;
    const allowableReductionIndividual = parseFloat(document.getElementById('allowable_reduction_8').value) || 0;

    // Calculate Taxable Income for Individual (Item 53)
    const taxableIncomeIndividual = cumulativeIncomeIndividual - allowableReductionIndividual;
    document.getElementById('taxable_income_8').value = taxableIncomeIndividual.toFixed(2);

    // Calculate Tax Due for Individual (Item 54)
    const taxRate = 0.08; // 8% tax rate
    const taxDueIndividual = taxableIncomeIndividual * taxRate;
    document.getElementById('tax_due_8').value = taxDueIndividual.toFixed(2);
    document.getElementById('show_tax_due').value = taxDueIndividual.toFixed(2);

  

}
    //53
    function calculateTaxableIncomeToDate() {
    // Get values for the individual
    const cumulativeIncomeIndividual = parseFloat(document.getElementById('cumulative_taxable_income_8').value) || 0;
    const allowableReductionIndividual = parseFloat(document.getElementById('allowable_reduction_8').value) || 0;

    // Calculate Taxable Income for Individual
    const taxableIncomeIndividual = cumulativeIncomeIndividual - allowableReductionIndividual;
    document.getElementById('taxable_income_8').value = taxableIncomeIndividual.toFixed(2);

 


}


    function calculateCumulativeTaxableIncome() {
    // Fetch values for the current quarter's total income (Item 49)
    const totalIncome = parseFloat(document.querySelector('[name="total_income_8"]')?.value) || 0;
  

    // Fetch values for the previous quarter's total income (Item 50)
    const prevQuarterIncome = parseFloat(document.querySelector('[name="total_prev_8"]')?.value) || 0;


    // Calculate cumulative taxable income (Sum of Items 49 and 50)
    const cumulativeIncome = totalIncome + prevQuarterIncome;


    // Update cumulative taxable income fields (Item 51)
    const cumulativeIncomeElement = document.querySelector('[name="cumulative_taxable_income_8"]');
    if (cumulativeIncomeElement) {
        cumulativeIncomeElement.value = cumulativeIncome.toFixed(2);
    }

 
}

    //Calculation for 49

    function calculateTotalIncomeForQuarter() {
    const salesRevenues = parseFloat(document.querySelector('[name="sales_revenues_8"]')?.value) || 0; // Item 47
    const nonOperatingIncome = parseFloat(document.querySelector('[name="non_operating_8"]')?.value) || 0; // Item 48
    
  

    // Calculate Total Income for the Quarter (Sum of Item 47 and Item 48)
    const totalIncome = salesRevenues + nonOperatingIncome;
   

    // Update Total Income fields (Item 49)
    const totalIncomeElement = document.querySelector('[name="total_income_8"]');
    if (totalIncomeElement) {
        totalIncomeElement.value = totalIncome.toFixed(2);
    }

   
}


    function calculateTaxDue() {
        const totalTaxableIncome = parseFloat(document.getElementById('graduated_total_taxable_income')?.value) || 0;
     
        
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
        const showTaxDueElement = document.getElementById('show_tax_due');
        if (showTaxDueElement) {
            showTaxDueElement.value = taxDue.toFixed(2);
        }

     



    }
    // Function to calculate and update Gross Income from operation

    function calculateTotalTaxableIncome() {
        const netIncome = parseFloat(document.getElementById('net_income')?.value) || 0; // Item 41 (Net Income)
    
        const taxableIncome = parseFloat(document.getElementById('taxable_income')?.value) || 0; // Item 42 (Taxable Income)
        
        const nonOperatingIncome = parseFloat(document.getElementById('graduated_non_op')?.value) || 0; // Item 43 (Non-Operating Income)
       
        const partnerGPP = parseFloat(document.getElementById('partner_gpp')?.value) || 0; // Item 44 (Partner GPP)
      

        // Calculate Total Taxable Income for individual and spouse
        const totalTaxableIncome = netIncome + taxableIncome + nonOperatingIncome + partnerGPP;
      
        // Update the Total Taxable Income fields
        const totalTaxableIncomeElement = document.getElementById('graduated_total_taxable_income');
        if (totalTaxableIncomeElement) {
            totalTaxableIncomeElement.value = totalTaxableIncome.toFixed(2);
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

  

    // Function to calculate Optional Standard Deduction (OSD) based on Item 36 (Sales Revenues)
    function calculateOSD() {
        const salesRevenues = parseFloat(document.getElementById('sales_revenues')?.value) || 0; // Item 36 (Sales Revenues)
       

        if (deductionMethod === 'osd') {
            // Calculate OSD (40% of Item 36 for individual and spouse)
            const osd = (salesRevenues * 0.40).toFixed(2); // 40% of Item 36 for individual
          

            // Update OSD fields if they exist
            const osdElement = document.getElementById('osd');
            if (osdElement) {
                osdElement.value = osd;
            }

           
        }
    }

    // Function to calculate Net Income/(Loss) This Quarter
    function calculateNetIncome() {
        const grossIncome = parseFloat(document.getElementById('gross_income')?.value) || 0; // Item 38
       

        let netIncome = 0;
  

        // If OSD method, subtract Item 40 (OSD); If itemized, subtract Item 39 (deduction)
        if (deductionMethod === 'osd') {
            const osd = parseFloat(document.getElementById('osd')?.value) || 0; // Item 40 (OSD)
            
            netIncome = grossIncome - osd;
         
        } else {
            const deduction = parseFloat(document.getElementById('deduction')?.value) || 0; // Item 39 (Deduction)
            netIncome = grossIncome - deduction;
 
        }

        // Update the Net Income fields
        const netIncomeElement = document.getElementById('net_income');
        if (netIncomeElement) {
            netIncomeElement.value = netIncome.toFixed(2);
        }

      
    }

    function calculateTotals() {
    calculateGrossIncome();              // Calculate individual Gross Income

    calculateOSD();                      // Calculate OSD (Optional Standard Deduction)
    calculateNetIncome();                // Calculate Net Income
    calculateTotalTaxableIncome();       // Calculate Total Taxable Income (Item 45)
    calculateTaxDue();                   // Calculate Tax Due (Item 46)
    calculateTotalIncomeForQuarter();    // Calculate Total Income for the Quarter (Item 49)
    calculateCumulativeTaxableIncome();  // Calculate Cumulative Taxable Income/Loss (Item 51)
    
    // Only calculate "Taxable Income to Date" (Item 53) if the "8% Gross Sales/Receipts" option is selected
    var individualRateType = document.querySelector('input[name="individual_rate_type"]:checked');
    
    if (individualRateType && individualRateType.value === "8_percent") {
        calculateTaxableIncomeToDate();  // Item 53
        calculateTaxDueFlat(); // Item 54
    }

 
    calculateCredits();
    calculatePenalties();
    calculateTaxPayable();
    calculateTotalAmountPayable();
    calculateAggregateAmountPayable();
    calculateTotalPayable();
}



    // Event listener to trigger calculations after the page loads
    document.addEventListener('DOMContentLoaded', () => {
        calculateTotals(); // Trigger initial calculations

        // Watch for changes in fields
        const fieldsToWatch = [
            'sales_revenues', 
            'cost_of_sales', 
       
            'deduction', // Deduction field for itemized calculation
            'graduated_non_op', 

            'partner_gpp', 
        
            'taxable_income',
            'tax_payable',
             'total_penalties'
         
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
