  {{-- Current create org --}}
  <x-organization-layout>
    <div class="bg-white">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex container mx-auto my-auto pt-6 items-center justify-between">
                    <!-- Left side with button -->
                    <div class="flex items-center">
                        <a href="{{ route('org-setup') }}">
                            <button class="text-zinc-600 hover:text-zinc-700 r-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g></svg>
                                <span class="text-zinc-600 text-sm font-nromal hover:text-zinc-700">Go back to portal</span>
                            </button>
                        </a>
                    </div>
                    <div class="flex-grow text-center">
                        <h1 class="text-3xl font-bold text-sky-900">Create Organization</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Multi-step Form Container -->
        <div class="container mx-auto">
            <!-- Progress Bar -->
            <div class="flex justify-around py-3 border-b border-gray-200">
                <div :class="{ 'text-sky-900': step === 1 }" class="flex flex-col items-center">
                    <span class="font-semibold">1</span>
                    <span>Classification</span>
                </div>
                <div :class="{ 'text-sky-900': step === 2 }" class="flex flex-col items-center">
                    <span class="font-semibold">2</span>
                    <span>Background</span>
                </div>
                <div :class="{ 'text-sky-900': step === 3 }" class="flex flex-col items-center">
                    <span class="font-semibold">3</span>
                    <span>Address</span>
                </div>
                <div :class="{ 'text-sky-900': step === 4 }" class="flex flex-col items-center">
                    <span class="font-semibold">4</span>
                    <span>Contact</span>
                </div>
                <div :class="{ 'text-sky-900': step === 5 }" class="flex flex-col items-center">
                    <span class="font-semibold">5</span>
                    <span>Tax Information</span>
                </div>
                <div :class="{ 'text-sky-900': step === 6 }" class="flex flex-col items-center">
                    <span class="font-semibold">6</span>
                    <span>Financial Settings</span>
                </div>
            </div>
            <!-- Form Steps -->
            <form action="{{'OrgSetup.store'}}" method="POST">
                @csrf  
                <div class="p-6 m-auto h-[300px]"> 

                    <div class="flex flex-col justify-center items-center h-full">
                        <h2 class="font-semibold text-lg text-center mb-6">What is your organization classification?</h2>
                        <select name="type" id="type" wire:model="type" class="border rounded px-4 py-2 w-64">
                            <option value="">Select Classification</option>
                            <option value="non-individual">Non-Individual</option>
                            <option value="individual">Individual</option>
                        </select>
                        <span class="text-red-500" x-show="!classification && isStepValid">* Required</span>
                    </div>

                    <div class="flex flex-col justify-center items-center h-full">
                        <h2 class="font-semibold text-lg">Background Information</h2>
                        <input type="text" name="registration_name" id="registration_name" wire:model="registration_name" placeholder="Registered Name" class="border rounded px-4 py-2 w-64 mb-4">
                        <span class="text-red-500" x-show="!registeredName && isStepValid">* Required</span>
                        <input type="text" name="line_of_business" id="line_of_business" wire:model="line_of_business" placeholder="Line of Business" class="border rounded px-4 py-2 w-64">
                        <span class="text-red-500" x-show="!lineOfBusiness && isStepValid">* Required</span>
                    </div>

                    <div class="flex flex-col justify-center items-center h-full">
                        <input type="text" name="address_line" id="address_line" wire:model="address_line" placeholder="Address Line" class="border rounded px-4 py-2 w-64 mb-4">
                        <span class="text-red-500" x-show="!addressLine && isStepValid">* Required</span>

                        <select wire:model="region" name="region" id="region" class="border rounded px-4 py-2 w-64 mb-4">
                            <option value="">Select Region</option>
                            <option value="Region 4-A">Region 4-A</option>
                        </select>

                        <span class="text-red-500" x-show="!region && isStepValid">* Required</span>
                        <select wire:model="city" name = "city" id="city" class="border rounded px-4 py-2 w-64 mb-4">
                            <option value="">Select City</option>
                            <option value="Santa Rosa" >Santa Rosa</option>
                            <!-- Add more cities based on region -->
                        </select>

                        <span class="text-red-500" x-show="!city && isStepValid">* Required</span>
                        <input type="text" name="zip_code" id="zip_code" wire:model="zip_code" placeholder="Zip Code" class="border rounded px-4 py-2 w-64">
                        <span class="text-red-500" x-show="!zipCode && isStepValid">* Required</span>
                    </div>


                    <div class="flex flex-col justify-center items-center h-full">
                        <input type="text" name="contact_number" id="contact_number" wire:model="contact_number" placeholder="Contact Number" class="border rounded px-4 py-2 w-64 mb-4">
                        <span class="text-red-500" x-show="(!contactNumber || contactNumber.length !== 11) && isStepValid">* Required (11 digits)</span>
                        <input type="email" name="email" id="email" x-model="emailAddress" placeholder="Email Address" class="border rounded px-4 py-2 w-64">
                        <span class="text-red-500" x-show="!validateEmail(emailAddress) && isStepValid">* Invalid Email</span>
                    </div>

                    <div class="flex flex-col justify-center items-center h-full">
                        <input type="text" name="tin" id="tin" wire:model="tin" placeholder="TIN (000-000-000-000)" class="border rounded px-4 py-2 w-64 mb-4">
                        <span class="text-red-500" x-show="!TIN && isStepValid">* Required</span>

                        <select wire:model="rdo" name="rdo" id="rdo" class="border rounded px-4 py-2 w-64 mb-4">
                            <option value="">Select RDO</option>
                            <option value="Santa Rosa Branch">Santa Rosa Branch</option>
                            <!-- Add RDO options here -->
                        </select>
                        <span class="text-red-500" x-show="!RDO && isStepValid">* Required</span>

                        <div class="flex items-center space-x-4">
                            <label><input type="radio" name = "tax_type" id="percentage_tax" wire:model="tax_type" value="Percentage Tax"> Percentage Tax</label>
                            <label><input type="radio" name = "tax_type" id="value_added_tax" wire:model="tax_type" value="Value-Added Tax"> Value-Added Tax</label>
                            <label><input type="radio" name = "tax_type" id="tax_exempt" wire:model="tax_type" value="Tax Exempt"> Tax Exempt</label>
                        </div>
                        <span class="text-red-500" x-show="taxType.length === 0 && isStepValid">* At least one tax type required</span>
                    </div>

                    <div class="flex flex-col justify-center items-center h-full">
                        <input type="date" name = "start_date" id="start_date" wire:model="start_date" class="border rounded px-4 py-2 w-64 mb-4">
                        <span class="text-red-500" x-show="!startDate && isStepValid">* Required</span>

                        <input type="date" name = "registration_date" id="registration_date" wire:model="registration_date" class="border rounded px-4 py-2 w-64 mb-4">
                        <span class="text-red-500" x-show="!registeredDate && isStepValid">* Required</span>

                        <input type="date"  name = "financial_year_end" id="financial_year_end" wire:model="financial_year_end" class="border rounded px-4 py-2 w-64 mb-4">
                        <span class="text-red-500" x-show="!financialYearEnd || startDate >= financialYearEnd && isStepValid">* Required (must be later than Start Date)</span>
                    </div>
                </div>
        </div>
                <!-- Navigation Buttons -->
                <div class="flex justify-between p-6">
                    <button 
                        x-show="step > 1"
                        x-on:click="step--"
                        class="px-4 py-2 bg-gray-500 text-white rounded-md">
                        Previous
                    </button>

                    <button 
                        x-show="step < 6"
                        x-on:click="nextStep()"
                        class="px-4 py-2 bg-sky-500 text-white rounded-md float-right"
                        :disabled="!isStepValid">
                        Next
                    </button>

                    <!-- Show Save button only on the last step -->
                    <button 
                        type="submit"
                        x-show="step === 6" 
                        class="px-4 py-2 bg-green-500 text-white rounded-md float-right"
                        :disabled="!isStepValid">
                        Save
                    </button>
                </div>
            </form>
    </div>

   <!-- Alpine.js Script -->
    <script>
        function formSteps() {
            return {
                step: 1,
                type: '',
                registration_name: '',
                line_of_business: '',
                address_line: '',
                region: '',
                city: '',
                zip_code: '',
                contact_number: '',
                email: '',
                tin: '',
                rdo: '',
                tax_type: [],
                startDate: '',
                registeredDate: '',
                financialYearEnd: '',
                isStepValid: false,

                // Automatically validate the step whenever inputs change
                init() {
                    this.$watch('step', () => this.validateStep());
                    this.$watch('type', () => this.validateStep());
                    this.$watch('registration_name', () => this.validateStep());
                    this.$watch('line_of_business', () => this.validateStep());
                    this.$watch('address_line', () => this.validateStep());
                    this.$watch('region', () => this.validateStep());
                    this.$watch('city', () => this.validateStep());
                    this.$watch('zip_code', () => this.validateStep());
                    this.$watch('contact_number', () => this.validateStep());
                    this.$watch('email', () => this.validateStep());
                    this.$watch('tin', () => this.validateStep());
                    this.$watch('rdo', () => this.validateStep());
                    this.$watch('tax_type', () => this.validateStep());
                    this.$watch('startDate', () => this.validateStep());
                    this.$watch('registeredDate', () => this.validateStep());
                    this.$watch('financialYearEnd', () => this.validateStep());
                },

                // Validate current step inputs
                validateStep() {
                    switch (this.step) {
                        case 1:
                            this.isStepValid = !!this.type;
                            break;
                        case 2:
                            this.isStepValid = !!this.registration_name && !!this.line_of_business;
                            break;
                        case 3:
                            this.isStepValid = !!this.address_line && !!this.region && !!this.city && !!this.zip_code;
                            break;
                        case 4:
                            this.isStepValid = !!this.contact_number && !!this.email &&
                                            this.contact_number.length === 11 && this.validateEmail(this.email);
                            break;
                        case 5:
                            this.isStepValid = !!this.tin && !!this.rdo && this.tax_type.length > 0;
                            break;
                        case 6:
                            this.isStepValid = !!this.startDate && !!this.registeredDate && !!this.financialYearEnd &&
                                            new Date(this.startDate) < new Date(this.financialYearEnd);
                            break;
                    }
                },

                // Move to the next step
                nextStep() {
                    if (this.isStepValid && this.step < 6) {
                        this.step++;
                        this.isStepValid = false; // Reset for the next step
                    }
                },

                // Save the form data
                saveForm() {
                    if (this.isStepValid) {
                        // Combine address fields into a single address string
                        const fullAddress = `${this.address_line} ${this.region} ${this.city} ${this.zip_code}`;
                        // Example of form submission via AJAX or you can use a form post
                        console.log('Form Data:', {
                            type: this.type,
                            registration_name: this.registration_name,
                            line_of_business: this.line_of_business,
                            address_line: fullAddress, // Send combined address
                            contact_number: this.contact_number,
                            email: this.email,
                            tin: this.tin,
                            rdo: this.rdo,
                            tax_type: this.tax_type,
                            startDate: this.startDate,
                            registeredDate: this.registeredDate,
                            financialYearEnd: this.financialYearEnd,
                        });
                        alert('Form submitted successfully!');
                    }
                },

                // Email validation function
                validateEmail(email) {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return re.test(email);
                }
            };
        }
    </script>

</x-organization-layout>
    {{-- </body>
</html> --}}