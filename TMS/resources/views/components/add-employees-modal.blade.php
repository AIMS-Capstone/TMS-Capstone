<div 
    x-data="{ step: 1, show: false }"
    x-show="show"
    x-on:open-add-employees-modal.window="show = true"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4"
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

        <div id="employeeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4">
            <div class="bg-white rounded-lg shadow-lg max-w-4xl w-full overflow-hidden">
                <!-- Modal Header -->
                <div class="bg-blue-900 text-white text-center py-4">
                    <h2 class="text-lg font-bold">Add New Employee</h2>
                </div>

                <!-- Step Progress -->
                <div class="flex justify-between items-center px-6 py-4">
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
                <div class="p-6">
                    <form id="employeeForm" method="POST" action="{{ route('employees.store') }}">
                        @csrf

                        <!-- Step 1: Basic Information -->
                        <div class="tab-content hidden" id="edit-tab-basic-info">
                            <div class="grid grid-cols-2 gap-6">
                                <!-- Left Column -->
                                <div>
                                    <label for="first_name">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="first_name" name="first_name" placeholder="First Name" class="w-full border rounded px-3 py-2" required>
                                </div>
                                <div>
                                    <label for="middle_name">Middle Name</label>
                                    <input type="text" id="middle_name" name="middle_name" placeholder="Middle Name" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label for="last_name">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" id="last_name" name="last_name" placeholder="Last Name" class="w-full border rounded px-3 py-2" required>
                                </div>
                                <div>
                                    <label for="suffix">Suffix</label>
                                    <select id="suffix" name="suffix" class="w-full border rounded px-3 py-2">
                                        <option value="">e.g., Jr.</option>
                                        <option value="Jr">Jr.</option>
                                        <option value="Sr">Sr.</option>
                                        <option value="II">II</option>
                                        <option value="III">III</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="tin">Tax Identification Number (TIN) <span class="text-red-500">*</span></label>
                                    <input type="text" id="tin" name="tin" placeholder="000-000-000-000" class="w-full border rounded px-3 py-2" required>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="grid grid-cols-2 gap-6 mt-6">
                                <div>
                                    <label for="date_of_birth">Date of Birth <span class="text-red-500">*</span></label>
                                    <input type="date" id="date_of_birth" name="date_of_birth" class="w-full border rounded px-3 py-2" required>
                                </div>
                                <div>
                                    <label for="contact_number">Contact Number</label>
                                    <input type="text" id="contact_number" name="contact_number" placeholder="e.g., 09123456789" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label for="nationality">Nationality</label>
                                    <input type="text" id="nationality" name="nationality" placeholder="e.g., Filipino" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label for="address">Address</label>
                                    <input type="text" id="address" name="address" placeholder="e.g., 145 Yakal St." class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label for="zip_code">Zip Code</label>
                                    <input type="text" id="zip_code" name="zip_code" placeholder="e.g., 1203" class="w-full border rounded px-3 py-2">
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Present Employer -->
                        <div class="tab-content hidden" id="tab-present-employer">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label>Employment From <span class="text-red-500">*</span></label>
                                    <input type="date" name="employment_from" class="w-full border rounded px-3 py-2" required>
                                </div>
                                <div>
                                    <label>Employment To</label>
                                    <input type="date" name="employment_to" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label>Region</label>
                                    <select name="region" class="w-full border rounded px-3 py-2">
                                        <option value="" disabled selected>Select Region</option>
                                        <option value="Region 1">Region 1</option>
                                        <option value="Region 2">Region 2</option>
                                        <option value="Region 3">Region 3</option>
                                    </select>
                                </div>
                                <div>
                                    <label>Rate <span class="text-red-500">*</span></label>
                                    <input type="number" name="rate" placeholder="0.00" class="w-full border rounded px-3 py-2" required>
                                </div>
                                <div>
                                    <label>Rate per Month <span class="text-red-500">*</span></label>
                                    <input type="number" name="rate_per_month" placeholder="0.00" class="w-full border rounded px-3 py-2" required>
                                </div>
                                <div>
                                    <label>Employment Status <span class="text-red-500">*</span></label>
                                    <select name="employment_status" class="w-full border rounded px-3 py-2">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="Permanent">Permanent</option>
                                        <option value="Contractual">Contractual</option>
                                        <option value="Probationary">Probationary</option>
                                    </select>
                                </div>
                                <div>
                                    <label>Reason for Separation</label>
                                    <input type="text" name="reason_for_separation" placeholder="Optional" class="w-full border rounded px-3 py-2">
                                </div>
                                <div>
                                    <label>Employee Wage Status <span class="text-red-500">*</span></label>
                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="employee_wage_status" value="Minimum Wage" class="mr-2"> Minimum Wage
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="employee_wage_status" value="Above Minimum Wage" class="mr-2"> Above Minimum Wage
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <label>Substituted Filing <span class="text-red-500">*</span></label>
                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="substituted_filing" value="1" class="mr-2" required> Yes
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="substituted_filing" value="0" class="mr-2" required> No
                                        </label>
                                    </div>
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
                        <div class="flex justify-between mt-6">
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
