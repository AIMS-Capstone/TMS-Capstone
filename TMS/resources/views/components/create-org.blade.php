<x-organization-layout>
    <div class="bg-white px-20" 
         x-data="{ 
            currentTab: 0,
            successModal: false,
            formData: {
                type: '',
                registration_name: '',
                line_of_business: '',
                address_line: '',
                region: '',
                province: '',
                city: '',
                zip_code: '',
                contact_number: '',
                email: '',
                tin: '',
                rdo: '',
                tax_type: '',
                start_date: '',
                registration_date: '',
                financial_year_end: ''
            },
               validationErrors: {
                contact_number: '',
                email: '',
                tin: '',
                start_date: '',
                registration_date: '',
                financial_year_end: ''
            },
            validateDates() {
    let isValid = true;
    const today = new Date();
    
    // Start Date validation
    if (!this.formData.start_date) {
        this.validationErrors.start_date = 'Start date is required';
        isValid = false;
    } else {
        const startDate = new Date(this.formData.start_date);
        if (startDate > today) {
            this.validationErrors.start_date = 'Start date cannot be in the future';
            isValid = false;
        } else {
            this.validationErrors.start_date = '';
        }
    }
    
    // Registration Date validation
    if (!this.formData.registration_date) {
        this.validationErrors.registration_date = 'Registration date is required';
        isValid = false;
    } else {
        const registrationDate = new Date(this.formData.registration_date);
        if (registrationDate > today) {
            this.validationErrors.registration_date = 'Registration date cannot be in the future';
            isValid = false;
        } else {
            this.validationErrors.registration_date = '';
        }
    }
    
    // Cross-field validation
    if (this.formData.start_date && this.formData.registration_date) {
        const startDate = new Date(this.formData.start_date);
        const registrationDate = new Date(this.formData.registration_date);
        
        if (registrationDate < startDate) {
            this.validationErrors.registration_date = 'Registration date cannot be earlier than start date';
            isValid = false;
        }
    }
    
    return isValid;
},

                    validateTin() {
                const tinValue = this.formData.tin.replace(/-/g, '');
                const validLengths = [9, 12, 14];
                
                if (!this.formData.tin) {
                    this.validationErrors.tin = 'TIN is required';
                    return false;
                }

                if (!/^\d{3}-\d{3}-\d{3}(-\d{3}|-\d{5})?$/.test(this.formData.tin)) {
                    this.validationErrors.tin = 'Invalid TIN format. Use XXX-XXX-XXX, XXX-XXX-XXX-XXX, or XXX-XXX-XXX-XXXXX';
                    return false;
                }

                if (!validLengths.includes(tinValue.length)) {
                    this.validationErrors.tin = 'TIN must be 9, 12, or 14 digits';
                    return false;
                }

                this.validationErrors.tin = '';
                return true;
            },
     validatePhone() {
        if (!this.formData.contact_number) {
            this.validationErrors.contact_number = 'Phone number is required';
            return false;
        }
        if (!this.formData.contact_number.startsWith('09')) {
            this.validationErrors.contact_number = 'Phone number must start with 09';
            return false;
        }
        if (this.formData.contact_number.length !== 11) {
            this.validationErrors.contact_number = 'Phone number must be 11 digits';
            return false;
        }
        if (!/^09\d{9}$/.test(this.formData.contact_number)) {
            this.validationErrors.contact_number = 'Invalid phone number format';
            return false;
        }
        this.validationErrors.contact_number = '';
        return true;
    },
    validateEmail() {
        if (!this.formData.email) {
            this.validationErrors.email = 'Email is required';
            return false;
        }
        if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.formData.email)) {
            this.validationErrors.email = 'Invalid email format';
            return false;
        }
        this.validationErrors.email = '';
        return true;
    },
            tabs: [
                'Classification',
                'Background',
                'Address',
                'Contact',
                'Tax Information',
                'Financial Settings'
            ],
            logFormData() {
    console.log(this.formData);
},
             init() {
           
        window.addEventListener('region-selected', (event) => {
            if (event.detail.clearDependents) {
                this.formData.province = '';
                this.formData.city = '';
                this.formData.zip_code = '';
            }
            this.formData.region = event.detail.region;
        });
        
        window.addEventListener('province-selected', (event) => {
            if (event.detail.clearDependents) {
                this.formData.city = '';
                this.formData.zip_code = '';
            }
            this.formData.province = event.detail.province;
        });
        
        window.addEventListener('city-selected', (event) => {
            this.formData.city = event.detail;
        });
        
        window.addEventListener('zip-selected', (event) => {
            this.formData.zip_code = event.detail;
        });
    },
        
            nextTab() {
                if (this.currentTab < this.tabs.length - 1) {
                    this.currentTab++
                }
            },
            prevTab() {
                if (this.currentTab > 0) {
                    this.currentTab--
                }
            },
            isCurrentTabValid() {
                switch(this.currentTab) {
                    case 0:
                        return this.formData.type !== '';
                    case 1:
                        return this.formData.registration_name !== '' && 
                               this.formData.line_of_business !== '';
                    case 2:
                        return this.formData.address_line !== '' && 
                               this.formData.region !== '' && 
                               this.formData.province !== '' && 
                               this.formData.city !== '' && 
                               this.formData.zip_code !== '';
                   case 3:
            return this.formData.contact_number !== '' && 
                   this.formData.email !== '' && 
                   this.validatePhone() && 
                   this.validateEmail();
                    case 4:
                      return this.formData.tin !== '' && 
                               this.validateTin() && 
                               this.formData.rdo !== '' && 
                               this.formData.tax_type !== '';
                    case 5:
                      return this.formData.start_date !== '' && 
                   this.formData.registration_date !== '' && 
                   this.formData.financial_year_end !== '' &&
                   this.validateDates();
                    default:
                        return false;
                }
            },
       calculateFinancialYearEnd() {
    if (!this.formData.start_date) return '';
    
    const startDate = new Date(this.formData.start_date + '-01'); // Add day for proper date parsing
    const endDate = new Date(startDate);
    endDate.setFullYear(startDate.getFullYear() + 1);
    endDate.setDate(endDate.getDate() - 1); // Last day of the month
    
    // Format as MM-DD
    const month = String(endDate.getMonth() + 1).padStart(2, '0');
    const day = String(endDate.getDate()).padStart(2, '0');
    
    this.formData.financial_year_end = `${month}-${day}`;
},

}"
@org-created.window="successModal = true"
>

        <!-- Header Section -->
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex container my-auto pt-6 items-center justify-between relative">
                    <div class="absolute -left-16 flex items-center">
                        <a href="{{ route('org-setup') }}">
                            <button class="text-zinc-600 hover:text-zinc-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                                    <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M16 12H8m4-4l-4 4l4 4"/>
                                    </g>
                                </svg>
                                <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go back to Organizations</span>
                            </button>
                        </a>
                    </div>
                    <div class="w-full text-center">
                        <h1 class="text-3xl font-bold text-blue-900">Create Organization</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="flex justify-center border-b border-gray-200 mb-4 px-4 sm:px-6 lg:px-8">
            <div class="flex space-x-12 sm:space-x-14 lg:space-x-16">
                <template x-for="(tab, index) in tabs" :key="index">
                    <button 
                        class="py-2 px-4"
                        :class="{
                            'text-blue-900 font-semibold border-b-4 border-blue-900': currentTab === index,
                            'text-gray-500': currentTab !== index
                        }"
                        x-text="tab"
                        @click="currentTab = index"
                        :disabled="true">
                    </button>
                </template>
            </div>
        </div>

        <!-- Form Section -->
        <form 
        action="{{ route('OrgSetup.store') }}" 
        method="POST" 
        x-ref="form"
        @submit.prevent="
            fetch($refs.form.action, {
                method: 'POST',
                body: new FormData($refs.form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Helps distinguish an AJAX request
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $dispatch('org-created') // Custom event handling
                }
            })
            .catch(error => {
                console.error('Error:', error); // Handle errors gracefully
            });
        "
    >
            @csrf
            <div class="container border border-gray-200 rounded-lg p-4 my-10 text-center max-w-full h-[500px] mx-auto flex flex-col">
                
                <!-- Classification Tab -->
                <div x-show="currentTab === 0" class="space-y-6">
                    <p class="p-10 text-zinc-600 font-medium text-lg">What is your organization classification?</p>
                    <div class="flex justify-center space-x-8">
                        <template x-for="type in ['Non-Individual', 'Individual']" :key="type">
                            <label class="flex flex-col items-center cursor-pointer">
                                <input 
                                    type="radio" 
                                    name="type" 
                                    :value="type"
                                    x-model="formData.type"
                                    class="hidden peer">
                                <div class="group flex items-center justify-center w-44 h-44 rounded-full bg-gray-100 border-2 border-transparent peer-checked:bg-blue-900 peer-checked:border-blue-900">
                                    <!-- SVG icons here -->
                                </div>
                                <span class="text-blue-900 font-semibold mt-2" x-text="type"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Background Tab -->
                <div x-show="currentTab === 1" class="space-y-6">
                    <p class="p-10 text-zinc-600 font-medium text-lg">What should we call this organization?</p>
                    <div class="flex flex-col items-center space-y-4">
                        <div class="w-80">
                            <x-field-label for="registration_name" value="{{ __('Registered Name') }}" class="mb-2 text-left" />
                            <x-input 
                                type="text" 
                                name="registration_name" 
                                x-model="formData.registration_name"
                                placeholder="e.g. ABC Corp" />
                        </div>
                        <div class="w-80">
                            <x-field-label for="line_of_business" value="{{ __('Line of Business') }}" class="mb-2 text-left" />
                            <x-input 
                                type="text" 
                                name="line_of_business" 
                                x-model="formData.line_of_business"
                                placeholder="Line of Business" 
                                maxlength="50"/>
                        </div>
                    </div>
                </div>
                <div x-show="currentTab === 2" class="space-y-6">
                    <!-- Address Content -->
                    <div class="tab-content-item">
                        <p class="p-10 text-zinc-600 font-medium text-lg">Organization Address Information</p>
                        <div class="flex flex-col items-center h-full">
                            <div class="flex flex-col mb-4 w-full max-w-md">
                                <div class="flex flex-col">
                                    <x-field-label for="address_line" value="{{ __('Address Line') }}" class="mb-2 text-left" />
                                    <x-input type="text" name="address_line" id="address_line" x-model="formData.address_line" placeholder="e.g. ESI Bldg 124 Yakal Street" />
                                </div>
                            </div>
                    
                            <div 
                            x-data 
                            @region-selected.window="formData.region = $event.detail"
                            @province-selected.window="formData.province = $event.detail"
                            @city-selected.window="formData.city = $event.detail"
                            @zip-selected.window="formData.zip_code = $event.detail">
                            <livewire:location-dropdowns/>
                        </div>
                        </div>
                    </div>

               
           
                </div>
                <div x-show="currentTab === 3" class="space-y-6">
                    <div class="tab-content-item">
                        <p class="p-10 text-zinc-600 font-medium text-lg">Contact Information</p>
                        <div class="flex flex-col items-center h-full">
                            <div class="flex flex-col mb-4 w-80">
                                <div class="flex flex-col">
                                    <x-field-label for="contact_number" value="{{ __('Contact Number') }}" class="mb-2 text-left" />
                                    <x-input 
                                        type="text" 
                                        name="contact_number" 
                                        id="contact_number"
                                        x-model="formData.contact_number"
                                        @input="validatePhone()"
                                        @blur="validatePhone()"
                                        placeholder="e.g. 09123456789"
                                        :class="{'border-red-500': validationErrors.contact_number, 'border-green-500': formData.contact_number && !validationErrors.contact_number}"
                                        class="border text-gray-900 text-sm rounded-xl focus:ring-blue-900 focus:border-blue-900 block w-full p-2.5"
                                    />
                                    <span 
                                        x-show="validationErrors.contact_number" 
                                        x-text="validationErrors.contact_number"
                                        class="text-red-500 text-sm mt-1">
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex flex-col w-80">
                                <div class="flex flex-col">
                                    <x-field-label for="email" value="{{ __('Email Address') }}" class="mb-2 text-left" />
                                    <x-input 
                                        type="email" 
                                        name="email" 
                                        id="email"
                                        x-model="formData.email"
                                        @input="validateEmail()"
                                        @blur="validateEmail()"
                                        placeholder="Enter Email Address"
                                        :class="{'border-red-500': validationErrors.email, 'border-green-500': formData.email && !validationErrors.email}"
                                        class="border text-gray-900 text-sm rounded-xl focus:ring-blue-900 focus:border-blue-900 block w-full p-2.5"
                                    />
                                    <span 
                                        x-show="validationErrors.email" 
                                        x-text="validationErrors.email"
                                        class="text-red-500 text-sm mt-1">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                  <!-- Tax Information Content -->
                  <div x-show="currentTab === 4" class="space-y-6">
                    <div class="tab-content-item">
                        <div class="flex justify-between">
                            <div class="flex flex-col w-1/2 pr-4">
                                <p class="p-10 text-zinc-600 font-medium text-lg">Its TIN and RDO?</p>
                                <div class="flex flex-col mb-4 items-center">
                                    <div class="flex flex-col w-80">
                                        <x-field-label for="tin" value="{{ __('Tax Identification Number (TIN)') }}" class="mb-2 text-left" />
                                        <x-input 
                                            type="text" 
                                            name="tin" 
                                            id="tin" 
                                            x-model="formData.tin"
                                            @input="validateTin()"
                                            @blur="validateTin()"
                                            placeholder="e.g. 123-456-789" 
                                            :class="{'border-red-500': validationErrors.tin, 'border-green-500': formData.tin && !validationErrors.tin}"
                                            class="w-80"
                                        />
                                        <span 
                                            x-show="validationErrors.tin" 
                                            x-text="validationErrors.tin"
                                            class="text-red-500 text-sm mt-1">
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex flex-col items-center">
                                    <div class="flex flex-col w-80">
                                        <x-field-label for="rdo" value="{{ __('Revenue District Office (RDO)') }}" class="mb-2 text-left" />
                                        <select 
                                            name="rdo" 
                                            id="rdo" 
                                            x-model="formData.rdo"
                                            class="select2 border appearance-none rounded-xl px-4 py-2 w-80 text-sm truncate border-gray-300 placeholder:text-gray-400 placeholder:font-light placeholder:text-sm focus:border-slate-500 focus:ring-slate-500 shadow-sm cursor-pointer"
                                            required
                                        >
                                            <option value="" disabled selected>Select RDO</option>
                                            @foreach($rdos as $rdo)
                                                <option value="{{ $rdo->id }}">{{ $rdo->rdo_code }} - {{ $rdo->location }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col w-1/2 pl-4">
                                <p class="p-10 text-zinc-600 font-medium text-lg mb-4">Tax type organization complies with?</p>
                                <div class="flex flex-col gap-2 justify-center items-center">
                                    <label for="percentage_tax" class="flex w-80 min-w-[14rem] cursor-pointer items-center justify-start gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 font-medium text-slate-700">
                                        <input 
                                            type="radio" 
                                            name="tax_type" 
                                            id="percentage_tax" 
                                            x-model="formData.tax_type"
                                            value="Percentage Tax" 
                                            required
                                            class="relative h-4 w-4 appearance-none rounded-full border border-gray-200 bg-white checked:border-blue-900 checked:bg-blue-900 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-slate-800"
                                        >
                                        <span class="text-sm">Percentage Tax</span>
                                    </label>
                                    
                                    <label for="value_added_tax" class="flex w-80 min-w-[14rem] cursor-pointer items-center justify-start gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 font-medium text-slate-700">
                                        <input 
                                            type="radio" 
                                            name="tax_type" 
                                            id="value_added_tax" 
                                            x-model="formData.tax_type"
                                            value="Value-Added Tax" 
                                            required
                                            class="relative h-4 w-4 appearance-none rounded-full border border-gray-200 bg-white checked:border-blue-900 checked:bg-blue-900 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-slate-800"
                                        >
                                        <span class="text-sm">Value-Added Tax</span>
                                    </label>
                                    
                                    <label for="tax_exempt" class="flex w-80 min-w-[14rem] cursor-pointer items-center justify-start gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2 font-medium text-slate-700">
                                        <input 
                                            type="radio" 
                                            name="tax_type" 
                                            id="tax_exempt" 
                                            x-model="formData.tax_type"
                                            value="Tax Exempt" 
                                            required
                                            class="relative h-4 w-4 appearance-none rounded-full border border-gray-200 bg-white checked:border-blue-900 checked:bg-blue-900 focus:outline focus:outline-2 focus:outline-offset-2 focus:outline-slate-800"
                                        >
                                        <span class="text-sm">Tax Exempt</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="currentTab === 5" class="space-y-6">
                    <div class="tab-content-item">
                        <p class="p-10 text-zinc-600 font-medium text-lg">
                            Lastly, enter the organization's<br />Start Date and Financial Year End
                        </p>
                        <div class="flex flex-col items-center h-full">
                            
             <!-- Start Date (mm/yyyy) -->
<div class="flex flex-col mb-2 w-80">
    <div class="flex flex-col">
        <x-field-label for="start_date" value="{{ __('Start Date') }}" class="mb-2 text-left" />
        <input 
            type="month" 
            name="start_date" 
            id="start_date" 
            x-model="formData.start_date"
            @input="calculateFinancialYearEnd(); validateDates()"
            :max="new Date().toISOString().slice(0, 7)"
            class="border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-900 focus:border-blue-900 block w-full"
            :class="{'border-red-500': validationErrors.start_date}"
            placeholder="Select Start Date (MM/YYYY)">
        <span 
            x-show="validationErrors.start_date" 
            x-text="validationErrors.start_date"
            class="text-red-500 text-sm mt-1">
        </span>
    </div>
</div>

<!-- Registration Date (mm/dd/yyyy) -->
<div class="flex flex-col mb-2 w-80">
    <div class="flex flex-col">
        <x-field-label for="registration_date" value="{{ __('Registration Date') }}" class="mb-2 text-left" />
        <input 
            type="date" 
            name="registration_date" 
            id="registration_date" 
            x-model="formData.registration_date"
            @input="validateDates()"
            :max="new Date().toISOString().slice(0, 10)"
            :min="formData.start_date ? formData.start_date + '-01' : ''"
            class="border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-900 focus:border-blue-900 block w-full"
            :class="{'border-red-500': validationErrors.registration_date}"
            placeholder="Select Registration Date (MM/DD/YYYY)">
        <span 
            x-show="validationErrors.registration_date" 
            x-text="validationErrors.registration_date"
            class="text-red-500 text-sm mt-1">
        </span>
    </div>
</div>

<!-- Financial Year End (mm/dd) - Readonly -->
<div class="flex flex-col mb-2 w-80">
    <div class="flex flex-col">
        <x-field-label for="financial_year_end" value="{{ __('Financial Year End') }}" class="mb-2 text-left" />
        <input 
            type="text" 
            name="financial_year_end" 
            id="financial_year_end" 
            x-model="formData.financial_year_end"
            readonly
            class="bg-gray-100 border border-gray-300 text-gray-900 text-sm rounded-xl focus:ring-blue-900 focus:border-blue-900 block w-full"
            placeholder="MM-DD">
        <span class="text-sm text-gray-500 mt-1">Auto-calculated based on Start Date</span>
    </div>
</div>
</div>
</div>
</div>

           

                

            

                <!-- Navigation Buttons -->
                <div class="inset-x-20 bottom-auto flex justify-between mt-auto px-4">
                    <button 
                        type="button"
                        @click="prevTab"
                        :disabled="currentTab === 0"
                        class="border border-blue-900 bg-white text-blue-900 font-bold px-4 py-2 rounded-xl disabled:opacity-50">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" viewBox="0 0 16 16">
                                <path fill="#1e3a8a" fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m10.25.75a.75.75 0 0 0 0-1.5H6.56l1.22-1.22a.75.75 0 0 0-1.06-1.06l-2.5 2.5a.75.75 0 0 0 0 1.06l2.5 2.5a.75.75 0 1 0 1.06-1.06L6.56 8.75z"/>
                            </svg>
                        </span>
                        Previous
                    </button>
                    
                    <template x-if="currentTab === tabs.length - 1">
                        <button 
                            type="submit"
                            :disabled="!isCurrentTabValid()"
                            class="bg-blue-900 text-white font-semibold px-4 py-2 rounded-xl disabled:opacity-50">
                            Save
                        </button>
                    </template>

                    <template x-if="currentTab !== tabs.length - 1">
                        <button 
                            type="button"
                            @click="nextTab"
                            :disabled="!isCurrentTabValid()"
                            class="bg-blue-900 text-white font-semibold px-4 py-2 rounded-xl disabled:opacity-50">
                            Next
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5" viewBox="0 0 24 24">
                                    <g fill="none" stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M8 12h8m-4 4l4-4l-4-4"/>
                                    </g>
                                </svg>
                            </span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Success Modal -->
            <div x-show="successModal" 
                 class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50" x-cloak
                 @click.outside="successModal = false; window.location.href='{{ route('org-setup') }}'">
                <div class="bg-white rounded-lg shadow-lg p-6 text-center max-w-lg w-full">
                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('images/Success.png') }}" alt="Organization Added" class="w-28 h-28 mr-6">
                    </div>
                    <h3 class="text-emerald-500 font-extrabold text-3xl whitespace-normal mb-4">Organization Added</h3>
                    <p class="font-normal text-sm mb-4">The organization has been successfully<br> added! Go back to the Organizations to<br> open and start the session.</p>
                    <div class="flex items-center justify-center mt-4 mb-4">
                        <button type="button" 
                                @click="successModal = false; window.location.href='{{ route('org-setup') }}'" 
                                class="inline-flex items-center w-72 justify-center px-4 py-2 bg-emerald-500 border border-transparent rounded-xl font-bold text-sm text-white hover:bg-emerald-600 focus:bg-emerald-700 active:bg-emerald-700 focus:outline-none disabled:opacity-50 transition ease-in-out duration-150">
                            {{ __('Go Back to Organizations') }}
                            <div class="ml-2 w-5 h-5 flex items-center justify-center border-2 border-white rounded-full">
                                <svg class="rtl:rotate-180 w-3.5 h-3.5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
 

</x-organization-layout>
   
