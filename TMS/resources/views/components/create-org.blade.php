<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Taxuri') }}</title>
        <link rel="icon" href="{{ asset('images/favicon.ico') }}">

        <!-- Fonts -->

        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <!-- Scripts -->
        <script defer src="https://cdn.jsdelivr.net/npm/@imacrayon/alpine-ajax@0.9.0/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>

        @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
        <!-- Styles -->
        @livewireStyles
        
    </head>

    <body class="font-sans antialiased">
        <!-- Page Header -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex container mx-auto my-auto pt-6 items-center justify-between">
                    <!-- Left side with button -->
                    <div class="flex items-center">
                        <a href="{{ route('org-setup') }}">
                            <button class="text-sky-900 hover:text-sky-700 text-2xl font-bold mr-4">
                                &lt; <!-- This will display the "<" character -->
                            </button>
                        </a>
                    </div>

                    <!-- Centered Title -->
                    <div class="flex-grow text-center">
                        <h1 class="text-xl font-bold text-sky-900">Create Organization</h1>
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

                            <select wire:model="regio" name="region" id="region" class="border rounded px-4 py-2 w-64 mb-4">
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

                    <button type ="submit">Submit</button>
                </form>

        </div>

        <!-- Navigation Buttons -->
        <div class="flex justify-between p-6">
            <button 
                x-show="step > 1"
                x-on:click="step--"
                class="px-4 py-2 bg-gray-500 text-white rounded-md"
            >
                Previous
            </button>

            <button 
                x-show="step < 6"
                x-on:click="nextStep()"
                class="px-4 py-2 bg-sky-500 text-white rounded-md float-right"
                :disabled="!isStepValid"
            >
                Next
            </button>

            <!-- Show Save button only on the last step -->
            <button 
                type="submit"
                x-show="step === 6" 
                class="px-4 py-2 bg-green-500 text-white rounded-md float-right"
                :disabled="!isStepValid"
            >
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
            lineOfBusiness: '',
            addressLine: '',
            region: '',
            city: '',
            zipCode: '',
            contactNumber: '',
            emailAddress: '',
            TIN: '',
            RDO: '',
            taxType: [],
            startDate: '',
            registeredDate: '',
            financialYearEnd: '',
            isStepValid: false,

            // Automatically validate the step whenever inputs change
            init() {
                this.$watch('step', () => this.validateStep());
                this.$watch('type', () => this.validateStep());
                this.$watch('registration_name', () => this.validateStep());
                this.$watch('lineOfBusiness', () => this.validateStep());
                this.$watch('addressLine', () => this.validateStep());
                this.$watch('region', () => this.validateStep());
                this.$watch('city', () => this.validateStep());
                this.$watch('zipCode', () => this.validateStep());
                this.$watch('contactNumber', () => this.validateStep());
                this.$watch('emailAddress', () => this.validateStep());
                this.$watch('TIN', () => this.validateStep());
                this.$watch('RDO', () => this.validateStep());
                this.$watch('taxType', () => this.validateStep());
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
                        this.isStepValid = !!this.registration_name && !!this.lineOfBusiness;
                        break;
                    case 3:
                        this.isStepValid = !!this.addressLine && !!this.region && !!this.city && !!this.zipCode;
                        break;
                    case 4:
                        this.isStepValid = !!this.contactNumber && !!this.emailAddress &&
                                          this.contactNumber.length === 11 && this.validateEmail(this.emailAddress);
                        break;
                    case 5:
                        this.isStepValid = !!this.TIN && !!this.RDO && this.taxType.length > 0;
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
                    const fullAddress = `${this.addressLine} ${this.region} ${this.city} ${this.zipCode}`;
                    // Example of form submission via AJAX or you can use a form post
                    console.log('Form Data:', {
                        type: this.type,
                        registration_name: this.registration_name,
                        lineOfBusiness: this.lineOfBusiness,
                        address: fullAddress, // Send combined address
                        contactNumber: this.contactNumber,
                        emailAddress: this.emailAddress,
                        TIN: this.TIN,
                        RDO: this.RDO,
                        taxType: this.taxType,
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

    </body>
</html>