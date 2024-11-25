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
        class="bg-white rounded-lg shadow-lg max-w-4xl w-full overflow-hidden"
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

                <!-- Step Progress -->
                <div class="flex justify-between items-center px-10 py-4">
                    <div class="steps flex justify-between items-center w-full relative">
                        <progress id="progressBar" value="0" max="100" class="absolute w-full z-5 h-2.5 bg-blue-900"></progress>
                        <div class="step-item text-center z-10">
                            <div class="step-button w-12 h-12 rounded-full bg-gray-400 text-white">1</div>
                            <div class="step-title">Basic Info</div>
                        </div>
                        <div class="step-item text-center z-10">
                            <div class="step-button w-12 h-12 rounded-full bg-gray-400 text-white">2</div>
                            <div class="step-title">Present Employer</div>
                        </div>
                        <div class="step-item text-center z-10">
                            <div class="step-button w-12 h-12 rounded-full bg-gray-400 text-white">3</div>
                            <div class="step-title">Previous Employer</div>
                        </div>
                        <div class="step-item text-center z-10">
                            <div class="step-button w-12 h-12 rounded-full bg-gray-400 text-white">4</div>
                            <div class="step-title">Complete</div>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-16">
                    <form id="employeeForm" method="POST" action="{{ route('employees.store') }}">
                        @csrf
                        <!-- Step 1: Basic Information -->
                        <div class="tab-content hidden" id="edit-tab-basic-info">
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
                                <div class="w-2/3 pr-4 flex items-center space-x-4">
                                    <label for="date_of_birth" class="text-zinc-700 font-semibold whitespace-nowrap">
                                        Date of Birth <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="date" 
                                        id="date_of_birth" 
                                        name="date_of_birth" 
                                        class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" 
                                        required>
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
                                    <label for="tin" class="text-zinc-700 font-semibold whitespace-nowrap">Tax Identification Number (TIN) <span class="text-red-500">*</span></label>
                                    <input type="text" id="tin" name="tin" placeholder="000-000-000-000" maxlength="17" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                </div>
                                <div class="w-2/3 pr-4 flex items-center space-x-4">
                                    <label for="zip_code" class="text-zinc-700 font-semibold whitespace-nowrap">Zip Code <span class="text-red-500">*</span></label>
                                    <input type="text" id="zip_code" name="zip_code" placeholder="e.g., 1203" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Present Employer -->
                        <div class="tab-content hidden" id="tab-present-employer">
                            <div class="mb-6 flex justify-between items-start">
                                <div class="w-2/3 pr-4 flex items-center space-x-4">
                                    <label for="employment_form" class="text-zinc-700 font-semibold whitespace-nowrap">Employment From <span class="text-red-500">*</span></label>
                                    <input type="date" name="employment_from" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                </div>
                                <div class="w-2/3 pr-4 flex items-center space-x-4">
                                    <label for="rate" class="text-zinc-700 font-semibold whitespace-nowrap">Rate <span class="text-red-500">*</span></label>
                                    <input type="number" name="rate" placeholder="0.00" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                </div>
                            </div>

                            <div class="mb-6 flex justify-between items-start">
                                <div class="w-2/3 pr-4 flex items-center space-x-4">
                                    <label for="employment_to" class="text-zinc-700 font-semibold whitespace-nowrap">Employment To</label>
                                    <input type="date" name="employment_to" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                </div>
                                <div class="w-2/3 pr-4 flex items-center space-x-4">
                                    <label for="rate_per_month" class="text-zinc-700 font-semibold whitespace-nowrap">Rate per Month <span class="text-red-500">*</span></label>
                                    <input type="number" name="rate_per_month" placeholder="0.00" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required>
                                </div>
                            </div>
                            <div class="mb-6 flex justify-between items-start">
                                <div class="w-2/3 pr-4 flex items-center space-x-4">
                                    <label for="region" class="text-zinc-700 font-semibold whitespace-nowrap">Region</label>
                                    <select name="region" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <option value="" disabled selected>Select Region</option>
                                        <option value="Region 1">Region 1</option>
                                        <option value="Region 2">Region 2</option>
                                        <option value="Region 3">Region 3</option>
                                    </select>
                                </div>
                                <div class="w-2/3 pr-4 flex items-center space-x-4">
                                    <label for="employment_status" class="text-zinc-700 font-semibold whitespace-nowrap">Employment Status <span class="text-red-500">*</span></label>
                                    <select name="employment_status" class="block w-full px-0 text-xs text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="Permanent">Permanent</option>
                                        <option value="Contractual">Contractual</option>
                                        <option value="Probationary">Probationary</option>
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
                                        <input type="radio" name="employee_wage_status" value="Minimum Wage" class="mr-2"> Minimum Wage
                                    </label>
                                    <label class="flex items-center">
                                        <input type="radio" name="employee_wage_status" value="Above Minimum Wage" class="mr-2"> Above Minimum Wage
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Previous Employer -->
                        <div class="tab-content hidden" id="tab-previous-employer">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label>With Previous Employer? <span class="text-red-500">*</span></label>
                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="with_prev_employer" value="1" class="mr-2" required> Yes
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="with_prev_employer" value="0" class="mr-2" required> No
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label>Previous Employer TIN</label>
                                    <input type="text" name="previous_employer_tin" placeholder="000-000-000-000" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label>Employer Name <span class="text-red-500">*</span></label>
                                    <input type="text" name="employer_name" class="w-full border rounded px-3 py-2" required>
                                </div>
                                <div>
                                    <label>Address <span class="text-red-500">*</span></label>
                                    <input type="text" name="prev_address" placeholder="e.g., 145 Yakal St." class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label>Zip Code <span class="text-red-500">*</span></label>
                                    <input type="text" name="prev_zip_code" placeholder="e.g., 1203" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label>Employment From</label>
                                    <input type="date" name="prev_employment_from" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label>Employment To</label>
                                    <input type="date" name="prev_employment_to" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label>Employment Status</label>
                                    <select name="prev_employment_status" class="w-full border rounded px-3 py-2">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="Permanent">Permanent</option>
                                        <option value="Contractual">Contractual</option>
                                        <option value="Probationary">Probationary</option>
                                    </select>
                                </div>
                                <div>
                                    <label>Reason for Separation</label>
                                    <input type="text" name="prev_reason_for_separation" placeholder="Optional" class="w-full border rounded px-3 py-2">
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Complete -->
                        <div class="tab-content hidden" id="tab-complete">
                            <div class="text-center text-green-500">
                                <h3>Employee Successfully Added!</h3>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex float-end justify-between my-6">
                            <button type="button" id="prevBtn" class="px-4 py-2 bg-gray-500 text-white rounded hidden">Previous</button>
                            <button type="button" id="nextBtn" class="px-4 py-2 bg-blue-900 text-white rounded">Next</button>
                            <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-900 text-white rounded hidden">Submit</button>
                        </div>
                    </form>
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
        const progressBar = document.getElementById('progressBar');
        let currentTab = 0;

        function updateUI() {
            tabs.forEach((tab, index) => {
                tab.classList.toggle('hidden', index !== currentTab);
            });

            steps.forEach((step, index) => {
                if (index <= currentTab) {
                    step.classList.add('bg-blue-900');
                    step.classList.remove('bg-gray-400');
                } else {
                    step.classList.add('bg-gray-400');
                    step.classList.remove('bg-blue-900');
                }
            });

            prevBtn.classList.toggle('hidden', currentTab === 0);
            nextBtn.classList.toggle('hidden', currentTab === tabs.length - 1);
            submitBtn.classList.toggle('hidden', currentTab !== tabs.length - 1);

            progressBar.value = (currentTab / (tabs.length - 1)) * 100;
        }

        nextBtn.addEventListener('click', () => {
            if (currentTab < tabs.length - 1) {
                currentTab++;
                updateUI();
            }
        });

        prevBtn.addEventListener('click', () => {
            if (currentTab > 0) {
                currentTab--;
                updateUI();
            }
        });

        updateUI();
    });
</script>
