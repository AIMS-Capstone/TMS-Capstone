<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Page Main -->
                <div class="px-10 py-6" 
                    x-data="{ selectedTab: '1701Q', init() { this.selectedTab = (new URL(window.location.href)).searchParams.get('type') || '1701Q'; } }" 
                    x-init="init">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">
                                Income Tax Return
                            </li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                                    </svg>
                                    <a href="{{ route('income_return')}}"
                                    class="ms-1 text-sm font-medium md:ms-2" 
                                    :class="selectedTab ? 'text-zinc-500' : 'text-zinc-500'">
                                        <span x-text="selectedTab"></span>
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="" class="ms-1 text-sm font-bold text-blue-900 md:ms-2">Input Summary</a>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>
                <hr>

                <div class="p-8 bg-white border-b border-gray-200">
                    <!-- Tabs Navigation -->
                    <div class="flex justify-start pl-4 space-x-4 mb-6">
                        <a href="{{route('income_return.show', ['id' => $taxReturn->id, 'type' => $taxReturn->title])}}"
                            class="px-4 py-2 text-sm font-bold text-blue-900 bg-slate-100 rounded-lg">
                            Input Summary
                        </a>
                        <a href="{{ route('income_return.report', ['id' => $taxReturn->id]) }}" 
                            class="text-zinc-600 font-medium hover:text-blue-900 px-4 py-2 text-sm">
                            Report
                        </a>
                    </div>

                    <div class="px-4 grid grid-cols-12 gap-5 mb-6">
                        <div class="col-span-12 border">
                            <div class="font-bold text-sm text-left bg-neutral-100 text-neutral-700 p-2 flex items-center justify-between">
                                <span>Background Information</span>
                                <button onclick="history.back()" class="flex items-center text-zinc-600 hover:text-zinc-800 font-normal transition duration-150">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10" /><path d="M16 12H8m4-4l-4 4l4 4" /></g></svg>
                                    <span class="text-sm hover:text-zinc-900">Back to Input Summary</span>
                                </button>
                            </div>

                            <div x-data="{
                                step: 1,  // Track the current step
                                nextStep() {
                                    if (this.step < 5) {
                                        this.step++;
                                    }
                                },
                                prevStep() {
                                    if (this.step > 1) {
                                        this.step--;
                                    }
                                }
                                }" class="container mx-auto">

                                <div class="border border-blue-900 rounded-lg p-2 m-4">
                                    <span class="text-sm font-bold text-blue-900">NOTE:</span>
                                    <span class="text-sm text-neutral-700">
                                      Please make sure that all required fields are filled out correctly, for any changes would affect tax computation.
                                    </span>
                                </div>
        
                                <div class="flex justify-center gap-8 border-neutral-300">
                                    <button @click="step = 1" :aria-selected="step === 1" :tabindex="step === 1 ? '0' : '-1'" :class="step === 1 
                                            ? 'font-bold text-blue-900 relative' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                        class="h-min py-2 text-base" type="button" role="tab" aria-controls="tabpanel-dob">
                                        <span class="block">Date of Birth</span>
                                        <span :class="step === 1  ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]'  : 'hidden'">
                                        </span>
                                    </button>
                                    <button @click="step = 2" :aria-selected="step === 2" :tabindex="step === 2 ? '0' : '-1'" :class="step === 2 
                                            ? 'font-bold text-blue-900 relative' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                        class="h-min py-2 text-base" type="button" role="tab" aria-controls="tabpanel-filer">
                                        <span class="block">Filer Type</span>
                                        <span :class="step === 2 ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]'  : 'hidden'">
                                        </span>
                                    </button>
                                    <button @click="step = 3" :aria-selected="step === 3" :tabindex="step === 3 ? '0' : '-1'" :class="step === 3 
                                            ? 'font-bold text-blue-900 relative' 
                                            : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                        class="h-min py-2 text-base" type="button" role="tab" aria-controls="tabpanel-atc">
                                        <span class="block">ATC</span>
                                        <span :class="step === 3 ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                        </span>
                                    </button>

                                    <button @click="step = 4" :aria-selected="step === 4" :tabindex="step === 4 ? '0' : '-1'" 
                                        :class="step === 4  ? 'font-bold text-blue-900 relative' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                        class="h-min py-2 text-base" type="button" role="tab" aria-controls="tabpanel-civil">
                                        <span class="block">Civil Status</span>
                                        <span :class="step === 4  ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                        </span>
                                    </button>

                                    <button @click="step = 5" :aria-selected="step === 5" :tabindex="step === 5 ? '0' : '-1'" 
                                        :class="step === 5 ? 'font-bold text-blue-900 relative' : 'text-neutral-600 font-normal hover:border-b-blue-900 hover:text-blue-900 hover:font-bold'" 
                                        class="h-min py-2 text-base" type="button" role="tab" aria-controls="tabpanel-other">
                                        <span class="block">Other Info</span>
                                        <span :class="step === 5 ? 'block bg-blue-900 border-blue-900 border-b-4 w-[120%] rounded-b-md transform rotate-180 absolute bottom-0 left-[-10%]' : 'hidden'">
                                        </span>
                                    </button>
                                </div>
                                <hr>
        
                                <div class="p-8">
                                    <form action="{{ route('tax_return.background_information.update', ['id' => $taxReturn->id]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
            
                                        <!-- Step 1: Date of Birth -->
                                        <div x-show="step === 1" class="mb-4 grid grid-cols-12 gap-4 p-8 items-center">
                                            <div class="col-span-4 text-sm font-medium text-zinc-700">
                                                <label class="font-bold text-zinc-700">Select Date of Birth<span class="text-red-600">*</span></label>
                                                <div class="mt-1 text-sm text-zinc-600">
                                                    A taxpayer’s Date of Birth (11) is one of the basic requirements in filing this form. To start entering your birth date, 
                                                    simply click the textbox and a calendar will show up, asking you for the Month, Date, and Year of your birth. <strong>(NOTE: This field will be disabled once the form is “Filed”)</strong> Format of date should be in MM/DD/YYYY.
                                                </div>
                                            </div>
                                            <div class="col-span-8 w-2/3">
                                                <label class="font-bold text-sm text-blue-900">Date of Birth<span class="text-red-600">*</span></label>
                                                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $backgroundInfo->date_of_birth) }}" 
                                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                                @error('date_of_birth')
                                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                                @enderror
                                                <div class="mt-4">
                                                    <button type="button" @click="nextStep()" class="bg-blue-900 text-white px-6 py-1.5 rounded-lg font-semibold hover:bg-blue-950">Next</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Step 2: Filer Type -->
                                        <div x-show="step === 2" class="mb-4 grid grid-cols-12 gap-4 p-8 items-center">
                                            <div class="col-span-4 text-sm font-medium text-zinc-700">
                                                <label class="font-bold text-zinc-700">Filer Type<span class="text-red-600">*</span></label>
                                                <div class="mt-1 text-sm text-zinc-600">
                                                    Please select the appropriate radio button to determine in which file type does the taxpayer belong. This is located on <strong>line 7</strong> of <strong>Part I - Background Information on TAXPAYER/FILER.</strong>
                                                    <p>Choose between <strong>Single Proprietor, Professional, Estate, or Trust.</strong></p>
                                                </div>
                                            </div>
                                            <div class="col-span-8">
                                                <div class="space-y-4 text-sm text-zinc-700">
                                                    <label class="flex items-center">
                                                        <input type="radio" name="filer_type" value="single_proprietor" 
                                                            {{ old('filer_type', $backgroundInfo->filer_type) == 'single_proprietor' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                        Single Proprietor
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="filer_type" value="professional" 
                                                            {{ old('filer_type', $backgroundInfo->filer_type) == 'professional' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                        Professional
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="filer_type" value="estate" 
                                                            {{ old('filer_type', $backgroundInfo->filer_type) == 'estate' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                        Estate
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="filer_type" value="trust" 
                                                            {{ old('filer_type', $backgroundInfo->filer_type) == 'trust' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                        Trust
                                                    </label>
                                                </div>
                                                @error('filer_type')
                                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mt-4 flex space-x-2 justify-between items-end">
                                                <button type="button" @click="prevStep()" class="border border-blue-900 text-blue-900 px-6 py-1.5 rounded-lg font-semibold hover:text-blue-950">Back</button>
                                                <button type="button" @click="nextStep()" class="bg-blue-900 text-white px-6 py-1.5 rounded-lg font-semibold hover:bg-blue-950">Next</button>
                                            </div>
                                        </div>
            
            
                                        <!-- Step 3: Alphanumeric Tax Code -->
                                        <div x-show="step === 3" class="mb-4 grid grid-cols-12 gap-4 p-8 items-center">
                                            <div class="col-span-4 text-sm font-medium text-zinc-700">
                                                <label class="font-bold text-zinc-700">Alphanumeric Tax Code<span class="text-red-600">*</span></label>
                                                <div class="mt-1 text-sm text-zinc-600">
                                                    ATC or Alphanumeric Tax Code <b>(8)</b> is a system of figures that the Taxpayer/Filer uses for his/her business. Choose between Compensation (011), Business (012) and Mixed Income (013), depending on what category the business falls at.
                                                </div>
                                            </div>
                                            <div class="col-span-8">
                                                <div class="space-y-4 text-sm text-zinc-700">
                                                    <label class="flex items-center">
                                                        <input type="radio" name="alphanumeric_tax_code" value="011" 
                                                            {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '011' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                            II011 Compensation Income
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="alphanumeric_tax_code" value="012" 
                                                            {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '012' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                            II012 Business Income-Graduated IT Rates
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="alphanumeric_tax_code" value="013" 
                                                            {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '013' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                            II013 Mixed Income - Graduated IT Rates
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="alphanumeric_tax_code" value="014" 
                                                            {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '014' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                            II014 Income from Profession-Graduated IT Rates
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="alphanumeric_tax_code" value="015" 
                                                            {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '015' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                            II015 Business Income - 8% IT Rate 
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="alphanumeric_tax_code" value="016" 
                                                            {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '016' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                            II016 Mixed Income - 8% IT Rate
                                                    </label>
                                                    <label class="flex items-center">
                                                        <input type="radio" name="alphanumeric_tax_code" value="017" 
                                                            {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '017' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                            II017 Income from Profession - 8% IT Rate
                                                    </label>
                                                </div>
                                                @error('alphanumeric_tax_code')
                                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            <div class="mt-4 flex space-x-2 justify-between items-end">
                                                <button type="button" @click="prevStep()" class="border border-blue-900 text-blue-900 px-6 py-1.5 rounded-lg font-semibold hover:text-blue-950">Back</button>
                                                <button type="button" @click="nextStep()" class="bg-blue-900 text-white px-6 py-1.5 rounded-lg font-semibold hover:bg-blue-950">Next</button>
                                            </div>
                                        </div>
                                        <!-- Step 4: Civil Status -->
                                        <div x-show="step === 4" class="mb-4 grid grid-cols-12 gap-4 p-8 items-center">
                                            <!-- Civil Status -->
                                            <div class="col-span-12 text-sm text-zinc-600">
                                                This section asks for the Taxpayer’s civil status. This will affect the Spouse section of the Income Tax Return form. Once <b>Married</b>, the filer needs to indicate the spouse’s employment status and determine whether the filer needs to enter the Spouse’s Information.
                                            </div>
                                            <div class="col-span-4 text-sm font-medium text-zinc-700">
                                                <label class="font-bold text-zinc-700">Civil Status<span class="text-red-600">*</span></label>
                                                <div class="mt-1 text-sm text-zinc-600">
                                                    Choose whether the Taxpayer/Filer is Single or Married. If Married, it will enable the next step to determine the <b>Spouse’s Employment Status.</b>
                                                </div>
                                            </div>
                                            <div class="col-span-8">
                                                <div class="space-x-4 text-sm text-zinc-700">
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="civil_status" value="single" 
                                                            {{ old('civil_status', $backgroundInfo->civil_status) == 'single' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                        Single
                                                    </label>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="civil_status" value="married" 
                                                            {{ old('civil_status', $backgroundInfo->civil_status) == 'married' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                        Married
                                                    </label>
                                                </div>
                                                @error('civil_status')
                                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                            
                                            <!-- Buttons -->
                                            <div class="mt-4 flex space-x-2 justify-between items-end">
                                                <button type="button" @click="prevStep()" class="border border-blue-900 text-blue-900 px-6 py-1.5 rounded-lg font-semibold hover:text-blue-950">Back</button>
                                                <button type="button" @click="nextStep()" class="bg-blue-900 text-white px-6 py-1.5 rounded-lg font-semibold hover:bg-blue-950">Next</button>
                                            </div>
            
                                        </div>
            
            
                                        <!-- Step 5: Other Information -->
                                        <div x-show="step === 5" class="mb-4 grid grid-cols-12 gap-4 p-8 items-center">
                                            <!-- Citizenship -->
                                            <div class="col-span-4 text-sm font-medium text-gray-700">
                                                <label class="font-bold text-zinc-700">Citizenship</label>
                                                <div class="mt-1 text-sm text-zinc-600">
                                                    Please specify your citizenship status (e.g., Filipino, Dual Citizen, etc.).
                                                </div>
                                            </div>
                                            <div class="col-span-8">
                                                <input type="text" name="citizenship" value="{{ old('citizenship', $backgroundInfo->citizenship) }}" 
                                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                                @error('citizenship')
                                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
            
                                            <!-- Foreign Tax (Text Field) -->
                                            <div class="col-span-4 text-sm font-medium text-gray-700 mt-4">
                                                <label class="font-bold text-zinc-700">Foreign Tax</label>
                                                <div class="mt-1 text-sm text-zinc-600">
                                                    Specify any foreign tax information here.
                                                </div>
                                            </div>
                                            <div class="col-span-8">
                                                <input type="text" name="foreign_tax" value="{{ old('foreign_tax', $backgroundInfo->foreign_tax) }}" 
                                                class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer">
                                                @error('foreign_tax')
                                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
            
                                            <!-- Claiming Foreign Tax Credits -->
                                            <div class="col-span-4 text-sm font-medium text-gray-700 mt-4">
                                                <label class="font-bold text-zinc-700">Claiming Foreign Tax Credits</label>
                                                <div class="mt-1 text-sm text-zinc-600">
                                                    Are you claiming foreign tax credits? (Yes/No)
                                                </div>
                                            </div>
                                            <div class="col-span-8">
                                                <div class="space-x-4 text-sm text-zinc-700">
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="foreign_tax_credits" value="Yes" 
                                                            {{ old('foreign_tax_credits', $backgroundInfo->claiming_foreign_credits) == '1' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                        Yes
                                                    </label>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="foreign_tax_credits" value="No" 
                                                            {{ old('foreign_tax_credits', $backgroundInfo->claiming_foreign_credits) == '0' ? 'checked' : '' }}
                                                            class="mr-2 border-gray-300 text-blue-900 focus:ring-blue-900">
                                                        No
                                                    </label>
                                                </div>
                                                @error('foreign_tax_credits')
                                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
            
                                            <!-- Save Button -->
                                            <div class="mt-4 flex space-x-2 col-span-12">
                                                <button type="submit" class="bg-blue-900 text-white px-6 py-1.5 rounded-lg font-semibold hover:bg-blue-950">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@if(session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif