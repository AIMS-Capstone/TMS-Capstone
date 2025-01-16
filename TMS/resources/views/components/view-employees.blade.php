<div x-data="{ showEmp: false, employee: {}, formatDate(date) {const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString(undefined, options); } }"
    x-show="showEmp"
    @open-view-employee-modal.window="showEmp = true; employee = $event.detail.employee" 
    x-on:close-modal.window="showEmp = false"
    x-effect="document.body.classList.toggle('overflow-hidden', showEmp)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-200 opacity-10"></div>
    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-auto h-auto z-10 overflow-hidden"
        x-show="showEmp" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
        <!-- Modal header -->
        <div class="relative p-3 bg-blue-900 border-opacity-80 w-full">
            <h1 class="text-lg font-bold text-white text-center">Employee Details</h1>
            <button @click="$dispatch('close-modal')" class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 8L16 16M8 16L16 8" stroke="#1e3a8a" class="transition duration-200 hover:stroke-gray-600"/>
                </svg>
            </button>
        </div>
        <!-- Modal body -->
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

            {{-- Basic Information --}}
            <div x-show="currentStep === 1" class="px-16">
                <div class="mb-4 flex justify-between space-x-4 items-start">
                    <!-- Left Column -->
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="first_name" class="text-zinc-700 font-semibold whitespace-nowrap">
                            First Name </label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-model="employee.first_name"></span>
                        </span>
                    </div>
                    {{-- testing like on the edit --}}
                     {{-- <div class="w-2/3 pr-4 flex items-center space-x-4">
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
                                    x-bind:value="employee.first_name"
                                    disabled 
                                    readonly
                                    >
                            </div> --}}
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="date_of_birth" class="text-zinc-700 font-semibold whitespace-nowrap">
                            Date of Birth</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="formatDate(employee.date_of_birth)"></span>
                        </span>
                    </div>
                </div>

                <div class="mb-4 flex justify-between space-x-4 items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="middle_name" class="text-zinc-700 font-semibold whitespace-nowrap">Middle Name</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.middle_name"></span>
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="contact_number" class="text-zinc-700 font-semibold whitespace-nowrap">Contact Number</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.contact_number"></span> 
                        </span>
                    </div>
                </div>

                <div class="mb-4 flex justify-between space-x-4 items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="last_name" class="text-zinc-700 font-semibold whitespace-nowrap">Last Name</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.last_name"></span>
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="nationality" class="text-zinc-700 font-semibold whitespace-nowrap">Nationality</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.nationality"></span>
                        </span>
                    </div>
                </div>

                <div class="mb-4 flex justify-between space-x-4 items-start">
                    <div class="w-2/3 mt-3 pr-4 flex items-center space-x-4">
                        <label for="suffix" class="text-zinc-700 font-semibold whitespace-nowrap">Suffix</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.suffix"></span>
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="address" class="text-zinc-700 font-semibold whitespace-nowrap">Address</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.address"></span>
                        </span>
                    </div>
                </div>

                <div class="mb-4 flex justify-between space-x-4 items-start">
                    <div class="w-full pr-4 flex items-center space-x-4">
                        <label for="tin" class="text-zinc-700 font-semibold whitespace-nowrap">Tax Identification Number (TIN)</label>
                        <span 
                            class="block py-3 w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm text-zinc-800">
                            <span x-text="employee.tin"></span>
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="zip_code" class="text-zinc-700 font-semibold whitespace-nowrap">Zip Code</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.zip_code"></span> 
                        </span>
                    </div>
                </div>
            </div>

            {{-- Present Employer --}}
            <div x-show="currentStep === 2" class="px-16">
                <div class="mb-6 flex justify-between items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="employment_form" class="text-zinc-700 font-semibold whitespace-nowrap">Employment From</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.employment_from"></span> 
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="rate" class="text-zinc-700 font-semibold whitespace-nowrap">Rate</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.rate"></span> 
                        </span>
                    </div>
                </div>

                <div class="mb-6 flex justify-between items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="employment_to" class="text-zinc-700 font-semibold whitespace-nowrap">Employment To</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.employment_to"></span> 
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="rate_per_month" class="text-zinc-700 font-semibold whitespace-nowrap">Rate per Month</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.rate_per_month"></span> 
                        </span>
                    </div>
                </div>
                <div class="mb-6 flex justify-between items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="region" class="text-zinc-700 font-semibold whitespace-nowrap">Region</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.region"></span> 
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="employment_status" class="text-zinc-700 font-semibold whitespace-nowrap">Employment Status</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.employment_status"></span> 
                        </span>
                    </div>
                </div>
                <div class="mb-6 flex justify-between items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="substituted_filling" class="text-zinc-700 font-semibold whitespace-nowrap">Substituted Filing</label>
                        <div class="flex items-center space-x-4">
                            <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.substituted_filing"></span> 
                        </span>
                        </div>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="reason_for_separation" class="text-zinc-700 font-semibold whitespace-nowrap">Reason for Separation</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.reason_for_separation"></span> 
                        </span>
                    </div>
                </div>
                
                {{-- Employment Wage Status --}}
                <div class="mb-6 pr-4 flex justify-between items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <!-- Label -->
                        <label for="emplyee_wage_status" class="font-semibold text-zinc-700 whitespace-nowrap">
                            Employee Wage Status</label>
                        <!-- Radio Buttons -->
                        <div class="flex flex-col justify-between items-start space-y-2">
                            <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                                <span x-bind:value="employee.employee_wage_status"></span> 
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Previous Employer --}}
            <div x-show="currentStep === 3" class="px-16">
                <div class="mb-6 flex justify-between items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label class="font-semibold text-zinc-700 whitespace-nowrap">With Previous Employer?</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.with_prev_employer"></span> 
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label for="prev_employment_from" class="font-semibold text-zinc-700 whitespace-nowrap">Employment From</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.prev_employment_from"></span> 
                        </span>
                    </div>
                </div>
                <div class="mb-6 flex justify-between items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Tax Identification Number</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.previous_employer_tin"></span> 
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Employment To</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.prev_employment_to"></span> 
                        </span>
                    </div>
                </div>
                <div class="mb-6 flex justify-between items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Employer Name</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.employer_name"></span> 
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Employment Status</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.prev_employment_status"></span> 
                        </span>
                    </div>
                </div>
                <div class="mb-6 flex justify-between items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Address</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.prev_address"></span> 
                        </span>
                    </div>
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Reason for Separation</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.prev_reason_for_separation"></span> 
                        </span>
                    </div>
                </div>
                <div class="mb-6 flex justify-between items-start">
                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Zip Code</label>
                        <span class="peer py-3 pe-0 block w-full font-light bg-transparent border-t-transparent border-b-1 border-x-transparent border-b-gray-200 text-sm focus:border-b-gray-200">
                            <span x-bind:value="employee.prev_zip_code"></span> 
                        </span>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between space-x-2 m-6 px-10">
                <button @click="currentStep = Math.max(currentStep - 1, 1)" 
                        class="px-7 py-2 font-semibold bg-white text-blue-900 border border-blue-900 rounded-lg"
                        :class="{'opacity-50 cursor-not-allowed': currentStep === 1}">
                    Previous
                </button>
        
                <button @click="currentStep = Math.min(currentStep + 1, 3)" 
                        class="px-7 py-2 font-semibold bg-blue-900 text-white rounded-lg"
                        :class="{'opacity-50 cursor-not-allowed': currentStep === 3}">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>