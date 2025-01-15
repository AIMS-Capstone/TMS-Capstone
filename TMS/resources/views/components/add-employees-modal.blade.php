<div 
    x-data="{ step: 1, show: false }"
    x-show="show"
    x-on:open-add-employees-modal.window="show = true"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed inset-0 z-50 flex items-center justify-center px-4"
    x-cloak
    >
    <!-- Modal Background -->
    <div class="fixed inset-0 bg-gray-200 bg-opacity-50"></div>

    <!-- Modal Container -->
    <div 
        class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-auto h-auto z-10 overflow-hidden"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90"
        >

        <div id="employeeModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full overflow-hidden">
                <!-- Modal Header -->
                <div class="flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto relative">
                    <h1 class="text-white text-lg font-bold text-center">Add New Employee</h1>
                    <button 
                        x-on:click="$dispatch('close-modal')" 
                        class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200 z-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                d="M8 8L16 16M8 16L16 8" 
                                stroke="#1e3a8a" 
                                class="transition duration-200 hover:stroke-gray-600" />
                        </svg>
                    </button>
                </div>

                {{-- Stepper --}}
                <div x-data="{ currentStep: 1 }">
                    <div class="flex justify-between items-center border-b mx-20 mb-6 mt-6 px-20">
                        <template x-for="(label, index) in ['Basic Information', 'Present Employer', 'Previous Employer', 'Done']" :key="index">
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
                        <form id="employeeForm" method="POST" action="{{ route('employees.store') }}">
                            @csrf
                            <!-- Step 1: Basic Information -->
                            <div x-show="currentStep === 1" id="tab-1">
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
                                            required>
                                    </div>
                                    {{-- 15+ validation --}}
                                    <div 
                                        x-data="{ 
                                            dob: '', 
                                            error: '', 
                                            maxDate: '' 
                                        }"
                                        x-init="
                                            let today = new Date();
                                            today.setFullYear(today.getFullYear() - 15);
                                            maxDate = today.toISOString().split('T')[0];
                                        "
                                        class="w-2/3 pr-4 flex items-center space-x-4"
                                    >
                                        <label for="date_of_birth" class="text-zinc-700 font-semibold whitespace-nowrap">
                                            Date of Birth <span class="text-red-500">*</span>
                                        </label>
                                        
                                        <input 
                                            type="date" 
                                            id="date_of_birth" 
                                            name="date_of_birth" 
                                            x-model="dob"
                                            :max="maxDate"
                                            x-on:change="
                                                let today = new Date();
                                                let birthDate = new Date(dob);
                                                let age = today.getFullYear() - birthDate.getFullYear();
                                                let monthDiff = today.getMonth() - birthDate.getMonth();
                                                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                                                    age--;
                                                }
                                                if (age < 15) {
                                                    error = 'The employee must be 15 years old or older.';
                                                    dob = ''; 
                                                } else {
                                                    error = '';  // Clear error if valid
                                                }
                                            "
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            required
                                        >

                                        <!-- Error Message -->
                                        <p x-show="error" class="text-red-500 text-xs mt-1" x-text="error"></p>
                                    </div>
                                </div>

                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="middle_name" class="text-zinc-700 font-semibold whitespace-nowrap">Middle Name</label>
                                        <input type="text" id="middle_name" name="middle_name" placeholder="Middle Name" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="contact_number" class="text-zinc-700 font-semibold whitespace-nowrap">Contact Number <span class="text-red-500">*</span></label>
                                        <input type="text" id="contact_number" name="contact_number" placeholder="e.g., 09123456789" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>

                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="last_name" class="text-zinc-700 font-semibold whitespace-nowrap">Last Name <span class="text-red-500">*</span></label>
                                        <input type="text" id="last_name" name="last_name" placeholder="Last Name" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                    </div>
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="nationality" class="text-zinc-700 font-semibold whitespace-nowrap">Nationality <span class="text-red-500">*</span></label>
                                        <input type="text" id="nationality" name="nationality" placeholder="e.g., Filipino" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>

                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 mt-3 pr-4 flex items-center space-x-4">
                                        <label for="suffix" class="text-zinc-700 font-semibold whitespace-nowrap">Suffix</label>
                                        <select id="suffix" name="suffix" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                            <option value="">e.g., Jr.</option>
                                            <option value="Jr">Jr.</option>
                                            <option value="Sr">Sr.</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                        </select>
                                    </div>
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="address" class="text-zinc-700 font-semibold whitespace-nowrap">Address <span class="text-red-500">*</span></label>
                                        <input type="text" id="address" name="address" placeholder="e.g., 145 Yakal St." class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>

                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="tin" class="text-zinc-700 font-semibold whitespace-nowrap">
                                            Tax Identification Number (TIN) <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            id="tin" 
                                            name="tin" 
                                            placeholder="000-000-000-000" 
                                            maxlength="15" 
                                            pattern="^\d{3}-\d{3}-\d{3}-\d{3}$" 
                                            title="TIN must follow the format ###-###-###-###." 
                                            oninput="validateTIN(this)" 
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                            required>
                                        <p id="tinError" class="text-red-500 text-xs hidden">TIN must follow the format ###-###-###-###.</p>
                                    </div>
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="zip_code" class="text-zinc-700 font-semibold whitespace-nowrap">Zip Code <span class="text-red-500">*</span></label>
                                        <input type="text" id="zip_code" name="zip_code" placeholder="e.g., 1203" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 2: Present Employer -->
                            <div x-show="currentStep === 2" id="tab-2">
                                <div 
                                    x-data="{ 
                                        fromDate: '', 
                                        toDate: '', 
                                        today: '', 
                                        rate: 0, 
                                        ratePerMonth: 0 
                                    }" 
                                    x-init="
                                        let now = new Date();
                                        today = now.toISOString().split('T')[0];
                                    "
                                >
                                    <!-- Employment From & Rate -->
                                    <div class="mb-6 flex justify-between items-start">
                                        <div class="w-2/3 pr-4 flex items-center space-x-4">
                                            <label for="employment_from" class="text-zinc-700 font-semibold whitespace-nowrap">
                                                Employment From <span class="text-red-500">*</span>
                                            </label>
                                            <input 
                                                type="date" 
                                                name="employment_from" 
                                                x-model="fromDate"
                                                :max="today" 
                                                x-on:change="
                                                    if (toDate && fromDate > toDate) {
                                                        alert('Employment From cannot be later than Employment To.');
                                                        fromDate = '';
                                                    }
                                                " 
                                                class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                                required
                                            >
                                        </div>

                                        <div class="w-2/3 pr-4 flex items-center space-x-4">
                                            <label for="rate" class="text-zinc-700 font-semibold whitespace-nowrap">
                                                Rate <span class="text-red-500">*</span>
                                            </label>
                                            <input 
                                                type="number" 
                                                name="rate" 
                                                id="rate" 
                                                x-model="rate"
                                                x-on:input="
                                                    ratePerMonth = (rate * 22).toFixed(2)
                                                " 
                                                placeholder="0.00" 
                                                class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                                required
                                            >
                                        </div>
                                    </div>

                                    <!-- Employment To & Rate per Month -->
                                    <div class="mb-6 flex justify-between items-start">
                                        <div class="w-2/3 pr-4 flex items-center space-x-4">
                                            <label for="employment_to" class="text-zinc-700 font-semibold whitespace-nowrap">
                                                Employment To
                                            </label>
                                            <input 
                                                type="date" 
                                                name="employment_to" 
                                                x-model="toDate"
                                                :max="today" 
                                                x-on:change="
                                                    if (fromDate && toDate < fromDate) {
                                                        alert('Employment To cannot be earlier than Employment From.');
                                                        toDate = '';
                                                    }
                                                " 
                                                class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            >
                                        </div>

                                        <div class="w-2/3 pr-4 flex items-center space-x-4">
                                            <label for="rate_per_month" class="text-zinc-700 font-semibold whitespace-nowrap">
                                                Rate per Month <span class="text-red-500">*</span>
                                            </label>
                                            <input 
                                                type="number" 
                                                name="rate_per_month" 
                                                x-model="ratePerMonth"
                                                placeholder="0.00" 
                                                class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                                required
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="region" class="text-zinc-700 font-semibold whitespace-nowrap">Region</label>
                                        <select name="region" id="region" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
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
                                        <label for="employment_status" class="text-zinc-700 font-semibold whitespace-nowrap">Employment Status <span class="text-red-500">*</span></label>
                                        <select name="employment_status" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="Permanent">Permanent</option>
                                            <option value="Contractual">Contractual</option>
                                            <option value="Self-Employed">Self-Employed</option>
                                            <option value="Casual">Casual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="substituted_filling" class="text-zinc-700 font-semibold whitespace-nowrap">Substituted Filing <span class="text-red-500">*</span></label>
                                        <div class="flex items-center space-x-4">
                                            <label class="flex items-center">
                                                <input type="radio" name="substituted_filing" value="1" class="mr-2" required> Yes
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="substituted_filing" value="0" class="mr-2" required> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="reason_for_separation" class="text-zinc-700 font-semibold whitespace-nowrap">Reason for Separation</label>
                                        <input type="text" name="reason_for_separation" placeholder="Optional" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                    </div>
                                </div>
                                
                                {{-- Employment Wage Status --}}
                                <div class="mb-6 pr-[45px] flex justify-end">
                                    <!-- Label -->
                                    <label for="emplyee_wage_status" class="font-semibold text-zinc-700 whitespace-nowrap">
                                        Employee Wage Status <span class="text-red-500">*</span>
                                    </label>
                                    <!-- Radio Buttons -->
                                    <div class="flex flex-col justify-between items-start space-y-2">
                                        <label class="flex items-center">
                                            <input type="radio" name="employee_wage_status" value="Minimum Wage Earner" class="mr-2"> Minimum Wage Earner
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="employee_wage_status" value="Above Minimum Wage Earner" class="mr-2"> Above Minimum Wage Earner
                                        </label>
                                    </div>  
                                </div>
                            </div>

                            <!-- Step 3: Previous Employer -->
                            <div x-show="currentStep === 3" id="tab-3">
                                <div 
                                x-data="{ 
                                    prevFromDate: '', 
                                    prevToDate: '', 
                                    today: ''
                                }"
                                x-init="
                                    let now = new Date();
                                    today = now.toISOString().split('T')[0];
                                "
                            >
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">With Previous Employer? <span class="text-red-500">*</span></label>
                                        <div class="flex items-center space-x-4">
                                            <label class="flex items-center">
                                                <input type="radio" name="with_prev_employer" value="1" class="mr-2" required> Yes
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" name="with_prev_employer" value="0" class="mr-2" required> No
                                            </label>
                                        </div>
                                    </div>
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="prev_employment_from" class="font-semibold text-zinc-700 whitespace-nowrap">
                                            Employment From
                                        </label>
                                        <input 
                                            type="date" 
                                            name="prev_employment_from" 
                                            x-model="prevFromDate" 
                                            :max="today"
                                            x-on:change="
                                                if (prevToDate && prevFromDate > prevToDate) {
                                                    alert('Previous Employment From cannot be later than Employment To.');
                                                    prevFromDate = '';
                                                }
                                            "
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                        >
                                    </div>
                                </div>
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Tax Identification Number <span class="text-red-500">*</span></label>
                                        <input type="text" name="previous_employer_tin" placeholder="000-000-000-000" maxlength="17" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"">
                                    </div>
                                     <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label for="prev_employment_to" class="font-semibold text-zinc-700 whitespace-nowrap">
                                            Employment To <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            type="date" 
                                            name="prev_employment_to" 
                                            x-model="prevToDate" 
                                            :max="today"
                                            x-on:change="
                                                if (prevFromDate && prevToDate < prevFromDate) {
                                                    alert('Previous Employment To cannot be earlier than Employment From.');
                                                    prevToDate = '';
                                                }
                                            "
                                            class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                                            required
                                        >
                                    </div>
                                </div>
                            </div>
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Employer Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="employer_name" placeholder="Employee Name" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"" required>
                                    </div>
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Employment Status <span class="text-red-500">*</span></label>
                                        <select name="prev_employment_status" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"">
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="Permanent">Permanent</option>
                                            <option value="Contractual">Contractual</option>
                                            <option value="Self-Employed">Self-Employed</option>
                                            <option value="Casual">Casual</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Address <span class="text-red-500">*</span></label>
                                        <input type="text" name="prev_address" placeholder="e.g., 145 Yakal St." class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"">
                                    </div>
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Reason for Separation <span class="text-red-500">*</span></label>
                                        <input type="text" name="prev_reason_for_separation" placeholder="Optional" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"">
                                    </div>
                                </div>
                                <div class="mb-6 flex justify-between items-start">
                                    <div class="w-2/3 pr-4 flex items-center space-x-4">
                                        <label class="font-semibold text-zinc-700 whitespace-nowrap">Zip Code <span class="text-red-500">*</span></label>
                                        <input type="text" name="prev_zip_code" placeholder="e.g., 1203" class="block w-72 px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"">
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4: Complete -->
                            <div x-show="currentStep === 4" id="tab-4">
                                <div class="p-10 text-center border border-emerald-500 rounded bg-emerald-100">
                                    <div class="flex justify-center mb-4">
                                        <img src="{{ asset('images/Success.png') }}" alt="Employee Added" class="w-28 h-28">
                                    </div>
                                    <p class="text-2xl text-emerald-500 font-bold ">New Employee Added</p>
                                    <p class="text-zinc-700">The employee <strong>[Employee Name]</strong> has been successfully added to contacts.</p>
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between space-x-4 mt-6">
                                {{-- <button type="button" id="prevBtn" class="px-6 py-2 bg-white text-blue-900 font-semibold border border-blue-900 rounded-lg hidden">Previous</button>
                                <button type="button" id="nextBtn" class="px-6 py-2 bg-blue-900 text-white font-semibold rounded-lg">Next</button>
                                <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-900 text-white font-semibold rounded-lg hidden">Submit</button> --}}
                                <button 
                                    x-show="currentStep > 1" 
                                    @click="currentStep--" 
                                    @click.prevent="prevStep()"
                                    type="button" 
                                    id="prevBtn"
                                    class="px-6 py-2 bg-white text-blue-900 font-semibold border border-blue-900 rounded-lg">
                                    Previous
                                </button>

                                <button 
                                    x-show="currentStep < 4" 
                                    @click="currentStep++" 
                                    @click.prevent="nextStep()"
                                    type="button" 
                                    id="nextBtn"
                                    class="px-6 py-2 bg-blue-900 text-white font-semibold rounded-lg">
                                    Next
                                </button>

                                <button 
                                    x-show="currentStep === 4" 
                                    type="submit" 
                                    id="submitBtn"
                                    class="px-4 py-2 bg-blue-900 text-white font-semibold rounded-lg">
                                    Submit
                                </button>   
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.tab-content');
        const steps = document.querySelectorAll('.step-button');
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const submitBtn = document.getElementById('submitBtn');
        let currentTab = 0;  // Current step


        // Next button click event
        nextBtn.addEventListener('click', () => {
            if (currentTab < tabs.length - 1) {
                currentTab++;
                updateUI();
            }
        });

        // Previous button click event
        prevBtn.addEventListener('click', () => {
            if (currentTab > 0) {
                currentTab--;
                updateUI();
            }
        });
    });

    // Minimum wage rates ng regions
    const minimumWage = {
        "NCR": 645,
        "CAR": 400,
        "Region 1": 400,
        "Region 2": 420,
        "Region 3": 460,
        "Region 4A": 470,
        "Region 4B": 400,
        "Region 5": 365,
        "Region 6": 450,
        "Region 7": 435,
        "Region 8": 375,
        "Region 9": 356,
        "Region 10": 390,
        "Region 11": 443,
        "Region 12": 368,
        "Region 13": 350,
        "BARMM": 336
    };

    document.getElementById("submitBtn").addEventListener("click", function(event) {
        const selectedRegion = document.getElementById("region").value;
        const rateInput = document.getElementById("rate").value;

        //Krazy alert for invalid minimum rate
        if (minimumWage[selectedRegion] && rateInput < minimumWage[selectedRegion]) {
            alert(`The minimum wage for ${selectedRegion} is ₱${minimumWage[selectedRegion]}. Please enter a rate equal to or above this.`);
            event.preventDefault();  
        }
    });

    document.getElementById("region").addEventListener("change", function() {
        const selectedRegion = this.value;
        const rateInput = document.getElementById("rate");

        if (minimumWage[selectedRegion]) {
            rateInput.min = minimumWage[selectedRegion];
            rateInput.placeholder = minimumWage[selectedRegion].toFixed(2);
        } else {
            rateInput.min = 0;
            rateInput.placeholder = "0.00";
        }
    });

    //tin
    function validateTIN(input) {
        let value = input.value.replace(/\D/g, ''); // Remove all non-numeric characters
        let formattedValue = '';

        if (value.length > 3) {
            formattedValue += value.slice(0, 3) + '-';
        }
        if (value.length > 6) {
            formattedValue += value.slice(3, 6) + '-';
        }
        if (value.length > 9) {
            formattedValue += value.slice(6, 9) + '-';
        }
        if (value.length > 12) {
            formattedValue += value.slice(9, 12);
        }

        input.value = formattedValue;

        // Check if the format is valid
        const isValid = /^\d{3}-\d{3}-\d{3}-\d{3}$/.test(input.value);
        const errorMessage = document.getElementById('tinError');

        if (isValid) {
            errorMessage.classList.add('hidden');
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-200');
        } else {
            errorMessage.classList.remove('hidden');
            input.classList.add('border-red-500');
            input.classList.remove('border-gray-200');
        }
    }

</script>