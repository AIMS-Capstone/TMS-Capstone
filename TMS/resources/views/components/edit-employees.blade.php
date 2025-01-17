<div 
    x-data="{ step: 1, show: false, employee: { address: {}, employments: [{ address: {} }] } }"
    x-show="show"
    x-on:open-edit-employee-modal.window="show = true; console.log($event.detail); employee = $event.detail"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed inset-0 z-50 flex items-center justify-center bg-white bg-opacity-20 px-4 overflow-y-auto"
    x-cloak
>

    <!-- Modal Container -->
    <div 
        class="bg-white rounded-lg shadow-lg max-w-4xl w-full overflow-hidden"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
    >
        <!-- Modal Header -->
        <div class="relative bg-blue-900 text-white text-center py-4">
            <h3 class="font-bold text-lg">Edit Employee: <span x-text="employee.first_name"></span></h3>
            <button @click="$dispatch('close-modal')"    class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 8L16 16M8 16L16 8" stroke="#1e3a8a" class="transition duration-200 hover:stroke-gray-600"/>
                </svg>
            </button>
        </div>

        {{-- Stepper --}}
        <div x-data="{ currentStep: 1 }">
            <div class="flex justify-between items-center border-b mx-20 mb-6 mt-6 px-20">
                <template x-for="(label, index) in ['Basic Information', 'Present Employer', 'Previous Employer']" :key="index">
                    <div class="text-center w-1/2 relative">
                        <div class="flex justify-center items-center">
                            <!-- Current Step -->
                            <div x-show="currentStep === index + 1" class="w-8 h-8 flex my-auto items-center justify-center rounded-full bg-blue-900 text-white border-2 border-blue-900">
                                <span x-text="index + 1"></span>
                            </div>
            
                            <!-- Completed Steps -->
                            <div x-show="currentStep > index + 1" class="flex items-center justify-center text-blue-900 font-bold">
                                <span x-text="index + 1"></span>
                            </div>
            
                            <!-- Inactive Steps -->
                            <span x-show="currentStep < index + 1" class="text-blue-900">
                                <span x-text="index + 1"></span>
                            </span>
                        </div>
            
                        <!-- Label for each step -->
                        <div class="mt-4 text-sm" :class="currentStep === index + 1 ? 'text-blue-900 mb-4 font-extrabold' : 'text-gray-500'">
                            <span x-text="label"></span>
                        </div>
            
                        <!-- Active Tab Indicator -->
                        <div x-show="currentStep === index + 1" class="mt-6 absolute bottom-0 mx-auto left-0 align-center right-0 border-b-4 border-blue-900 rounded-b-md transform rotate-180"></div>
                    </div>
                </template>
            </div>

            <!-- Modal Body -->
            <div class="p-16">
                <form id="editEmployeeForm" method="POST" :action="'/employees/' +  employee.id">
                    @csrf
                    @method('PUT')

     
                    <!-- Step 1: Basic Information -->
                    <div x-show="currentStep === 1" id="edit-tab-basic-info">
                        <div class="mb-6 flex justify-between items-start">
                            <!-- Left Column -->
                            <div class="w-2/3 pr-4 flex items-center space-x-4">
                                <label for="first_name" class="text-zinc-700 font-semibold whitespace-nowrap">
                                    First Name <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="first_name" 
                                    name="first_name" 
                                    placeholder="First Name" 
                                    class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                    required
                                    x-model="employee.first_name">
                            </div>
                            <div class="w-2/3 pr-4 flex items-center space-x-4">
                                <label for="date_of_birth" class="text-zinc-700 font-semibold whitespace-nowrap">
                                    Date of Birth <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="date" 
                                    id="date_of_birth" 
                                    name="date_of_birth" 
                                    class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                    required
                                    x-model="employee.date_of_birth">
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="mb-6 flex justify-between items-start">
                            <div class="w-2/3 pr-4 flex items-center space-x-4">
                                <label for="middle_name" class="text-zinc-700 font-semibold whitespace-nowrap">Middle Name</label>
                                <input 
                                    type="text" 
                                    id="middle_name" 
                                    name="middle_name" 
                                    placeholder="Middle Name" 
                                    class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    x-model="employee.middle_name">
                            </div>
                            <div class="w-2/3 pr-4 flex items-center space-x-4">
                                <label for="contact_number" class="text-zinc-700 font-semibold whitespace-nowrap">Contact Number <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="contact_number" 
                                    name="contact_number" 
                                    placeholder="e.g., 09123456789" 
                                    class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    x-model="employee.contact_number">
                            </div>
                        </div>

                        <div class="mb-6 flex justify-between items-start">
                            <div class="w-2/3 pr-4 flex items-center space-x-4">
                                <label for="last_name" class="text-zinc-700 font-semibold whitespace-nowrap">Last Name <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="last_name" 
                                    name="last_name" 
                                    placeholder="Last Name" 
                                    class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                    required
                                    x-model="employee.last_name">
                            </div>
                            <div class="w-2/3 pr-4 flex items-center space-x-4">
                                <label for="nationality" class="text-zinc-700 font-semibold whitespace-nowrap">Nationality <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="nationality" 
                                    name="nationality" 
                                    placeholder="e.g., Filipino" 
                                    class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    x-model="employee.nationality">
                            </div>
                        </div>

                        <div class="mb-6 flex justify-between items-start">
                            <div class="w-2/3 mt-3 pr-4 flex items-center space-x-4">
                                <label for="suffix" class="text-zinc-700 font-semibold whitespace-nowrap">Suffix</label>
                                <select 
                                    id="suffix" 
                                    name="suffix" 
                                    class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    x-model="employee.suffix">
                                    <option value="">e.g., Jr.</option>
                                    <option value="Jr" :selected="employee.suffix === 'Jr'">Jr.</option>
                                    <option value="Sr" :selected="employee.suffix === 'Sr'">Sr.</option>
                                    <option value="II" :selected="employee.suffix === 'II'">II</option>
                                    <option value="III" :selected="employee.suffix === 'III'">III</option>
                                </select>
                            </div>
                            <div class="w-2/3 pr-4 flex items-center space-x-4">
                                <label for="address" class="text-zinc-700 font-semibold whitespace-nowrap">Address <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="address" 
                                    name="address" 
                                    placeholder="e.g., 145 Yakal St." 
                                    class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    x-model="employee.address.address">
                            </div>
                        </div>

                        <div class="mb-6 flex justify-between items-start">
                            <div class="w-2/3 pr-4 flex items-center space-x-4">
                                <label for="tin" class="text-zinc-700 font-semibold whitespace-nowrap">Tax Identification Number (TIN) <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="tin" 
                                    name="tin" 
                                    placeholder="000-000-000-000" 
                                    maxlength="17" 
                                    class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                    required
                                    x-model="employee.tin">
                            </div>
                            <div class="w-2/3 pr-4 flex items-center space-x-4">
                                <label for="zip_code" class="text-zinc-700 font-semibold whitespace-nowrap">Zip Code <span class="text-red-500">*</span></label>
                                <input 
                                    type="text" 
                                    id="zip_code" 
                                    name="zip_code" 
                                    placeholder="e.g., 1203" 
                                    class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                    x-model="employee.address.zip_code">
                            </div>
                        </div>
                    </div>

                    {{-- Hindi ko alam bakit hindi nag wwork yung x-model to other step. Baka hindi na ppass yung data --}}
                    <!-- Step 2: Present Employer -->
                    <div x-show="currentStep === 2" id="tab-present-employer">
                        <template x-for="(employment, index) in employee.employments" :key="index">
                            <div class="mb-6 pb-6">
                                
                                <!-- Employment From & To -->
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label>Employment From</label>
                                        <input type="date" name="employment_from" 
                                            x-model="employment.employment_from" 
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                    </div>

                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label>Employment To</label>
                                        <input type="date" name="employment_to" 
                                            x-model="employment.employment_to" 
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>

                                <!-- Rate & Rate per Month -->
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="rate">Rate</label>
                                        <input type="number" name="rate" 
                                            x-model="employment.rate" 
                                            placeholder="0.00" 
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                    </div>

                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="rate_per_month">Rate per Month</label>
                                        <input type="number" name="rate_per_month" 
                                            x-model="employment.rate_per_month" 
                                            placeholder="0.00" 
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                    </div>
                                </div>

                                <!-- Region & Employment Status -->
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="region">Region</label>
                                        <select name="region" 
                                            x-model="employment.address?.region" 
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                            <option value="" disabled selected>Select Region</option>
                                            <option value="NCR">National Capital Region (NCR)</option>
                                            <option value="CAR">Cordillera Administrative Region (CAR)</option>
                                            <option value="Region 1">Region I - Ilocos Region</option>
                                            <option value="Region 2">Region II - Cagayan Valley</option>
                                            <option value="Region 3">Region III - Central Luzon</option>
                                            <option value="Region 4A">Region IV-A - CALABARZON</option>
                                            <option value="Region 4B">Region IV-B - MIMAROPA</option>
                                            <option value="Region 5">Region V - Bicol Region</option>
                                            <option value="Region 6">Region VI - Western Visayas</option>
                                            <option value="Region 7">Region VII - Central Visayas</option>
                                            <option value="Region 8">Region VIII - Eastern Visayas</option>
                                            <option value="Region 9">Region IX - Zamboanga Peninsula</option>
                                            <option value="Region 10">Region X - Northern Mindanao</option>
                                            <option value="Region 11">Region XI - Davao Region</option>
                                            <option value="Region 12">Region XII - SOCCSKSARGEN</option>
                                            <option value="Region 13">Region XIII - Caraga</option>
                                            <option value="BARMM">Bangsamoro Autonomous Region in Muslim Mindanao (BARMM)</option>
                                        </select>
                                    </div>

                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="employment_status">Employment Status</label>
                                        <select name="employment_status" 
                                            x-model="employment.employment_status" 
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="Permanent">Permanent</option>
                                            <option value="Contractual">Contractual</option>
                                            <option value="Self-Employed">Self-Employed</option>
                                            <option value="Casual">Casual</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Substituted Filing & Reason for Separation -->
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="substituted_filing">Substituted Filing</label>
                                        <div class="flex items-center space-x-4">
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                    x-model="employment.substituted_filing" 
                                                    name="substituted_filing" value="1" class="mr-2" required> Yes
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                    x-model="employment.substituted_filing" 
                                                    name="substituted_filing" value="0" class="mr-2" required> No
                                            </label>
                                        </div>
                                    </div>

                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label>Reason for Separation</label>
                                        <input type="text" name="reason_for_separation" 
                                            x-model="employment.reason_for_separation" 
                                            placeholder="Optional" 
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>

                                <!-- Wage Status -->
                                <div class="mb-6 pr-[45px] flex justify-end">
                                    <label for="employee_wage_status">Employee Wage Status</label>
                                    <div class="flex flex-col justify-between items-start space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                x-model="employment.employee_wage_status" 
                                                name="employee_wage_status" 
                                                value="Minimum Wage" 
                                                class="mr-2"> Minimum Wage
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" 
                                                x-model="employment.employee_wage_status" 
                                                name="employee_wage_status" 
                                                value="Above Minimum Wage" 
                                                class="mr-2"> Above Minimum Wage
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Step 3: Previous Employer -->
                    <div x-show="currentStep === 3" id="tab-previous-employer">
                        <template x-for="(employment, index) in employee.employments" :key="index">
                            <div class="mb-6 pb-6">
                                
                                <!-- Previous Employer Yes/No -->
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">
                                            With Previous Employer? <span class="text-red-500">*</span>
                                        </label>
                                        <div class="flex items-center space-x-4">
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                    x-model="employment.with_previous_employer"
                                                    name="with_prev_employer"
                                                    value="1" 
                                                    class="mr-2" required> Yes
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                    x-model="employment.with_previous_employer"
                                                    name="with_prev_employer"
                                                    value="0" 
                                                    class="mr-2" required> No
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Previous Employment From -->
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="prev_employment_from" class="font-semibold text-zinc-700 whitespace-nowrap">
                                            Employment From
                                        </label>
                                        <input type="date" 
                                            x-model="employment.prev_employment_from"
                                            name="prev_employment_from"
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>

                                <!-- TIN and Employment To -->
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">
                                            Tax Identification Number <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                            x-model="employment.previous_employer_tin"
                                            name="previous_employer_tin"
                                            placeholder="000-000-000-000"
                                            maxlength="17"
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>

                                    <!-- Employment To -->
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">
                                            Employment To <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date"
                                            x-model="employment.prev_employment_to"
                                            name="prev_employment_to"
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>

                                <!-- Employer Name and Employment Status -->
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">
                                            Employer Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                            x-model="employment.employer_name"
                                            name="employer_name"
                                            placeholder="Employer Name"
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                    </div>

                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">
                                            Employment Status <span class="text-red-500">*</span>
                                        </label>
                                        <select name="prev_employment_status"
                                            x-model="employment.prev_employment_status"
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="Permanent">Permanent</option>
                                            <option value="Contractual">Contractual</option>
                                            <option value="Self-Employed">Self-Employed</option>
                                            <option value="Casual">Casual</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Address and Reason for Separation -->
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">
                                            Address <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                            x-model="employment.address.address"
                                            name="prev_address"
                                            placeholder="e.g., 145 Yakal St."
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>

                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">
                                            Reason for Separation
                                        </label>
                                        <input type="text"
                                            x-model="employment.reason_for_separation"
                                            name="reason_for_separation"
                                            placeholder="Optional"
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>

                                <!-- Zip Code -->
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">
                                            Zip Code <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text"
                                            x-model="employment.address.zip_code"
                                            name="prev_zip_code"
                                            placeholder="e.g., 1203"
                                            class="block w-72 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-6">
                        {{-- <button type="button" id="prevBtn" class="px-4 py-2 bg-gray-500 text-white rounded hidden">Previous</button>
                        <button type="button" id="nextBtn" class="px-4 py-2 bg-blue-900 text-white rounded">Next</button>
                        <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-900 text-white rounded hidden">Submit</button> --}}
                        <button 
                            x-show="currentStep > 1" 
                            @click="currentStep--" 
                            type="button" 
                            id="prevBtn"
                            class="px-6 py-2 bg-white text-blue-900 font-semibold border border-blue-900 rounded-lg">
                            Previous
                        </button>

                        <button 
                            x-show="currentStep < 3" 
                            @click="currentStep++" 
                            type="button" 
                            id="nextBtn"
                            class="px-6 py-2 bg-blue-900 text-white font-semibold rounded-lg">
                            Next
                        </button>

                        <button 
                            x-show="currentStep === 3" 
                            type="submit" 
                            id="submitBtn"
                            class="px-4 py-2 bg-blue-900 text-white font-semibold rounded-lg">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
