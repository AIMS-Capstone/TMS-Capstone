<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 bg-white border-b border-gray-200">
                       <!-- Tabs Navigation -->
                       <div class="flex justify-start space-x-8 mb-6">
                        <a 
                        href="{{route('income_return.show', ['id' => $taxReturn->id, 'type' => $taxReturn->title])}}"
                          
                            class="px-4 py-2 text-sm font-bold text-blue-900 bg-slate-100 rounded-lg"
                        >
                            Input Summary
                        </a>
                        <a 
                            href="{{ route('tax_return.report', ['id' => $taxReturn->id]) }}" 
                            class="text-zinc-600 font-medium hover:text-blue-900 px-4 py-2 text-sm"
                        >
                            Report
                        </a>
                        <a 
                            href="{{ route('tax_return.notes_activities', ['id' => $taxReturn->id]) }}" 
                            class="text-zinc-600 font-medium hover:text-blue-900 px-4 py-2 text-sm"
                        >
                            Notes and Activities
                        </a>
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

       
                    
<div class="flex justify-start space-x-8 mb-6">
    <button @click="step = 1" class="px-4 py-2 text-sm" :class="step === 1 ? 'font-bold text-blue-900' : 'text-zinc-600'">Date of Birth</button>
    <button @click="step = 2" class="px-4 py-2 text-sm" :class="step === 2 ? 'font-bold text-blue-900' : 'text-zinc-600'">Filer Type</button>
    <button @click="step = 3" class="px-4 py-2 text-sm" :class="step === 3 ? 'font-bold text-blue-900' : 'text-zinc-600'">ATC</button>
    <button @click="step = 4" class="px-4 py-2 text-sm" :class="step === 4 ? 'font-bold text-blue-900' : 'text-zinc-600'">Civil Status</button>
    <button @click="step = 5" class="px-4 py-2 text-sm" :class="step === 5 ? 'font-bold text-blue-900' : 'text-zinc-600'">Other Info</button>
</div>

<form action="{{ route('tax_return.background_information.update', ['id' => $taxReturn->id]) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Step 1: Date of Birth -->
    <div x-show="step === 1" class="mb-4 grid grid-cols-12 gap-4">
        <div class="col-span-4 text-sm font-medium text-gray-700">
            <label>Date of Birth</label>
            <div class="mt-1 text-sm text-gray-600">
                A taxpayer’s Date of Birth (11) is one of the basic requirements in filing this form. To start entering your birth date, simply click the textbox and a calendar will show up, asking you for the Month, Date, and Year of your birth. (NOTE: This field will be disabled once the form is “Filed”) Format of date should be in MM/DD/YYYY.
            </div>
        </div>
        <div class="col-span-8">
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $backgroundInfo->date_of_birth) }}" 
                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            @error('date_of_birth')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
            <div class="mt-4">
                <button type="button" @click="nextStep()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Next</button>
            </div>
        </div>
    </div>
<!-- Step 2: Filer Type -->
<div x-show="step === 2" class="mb-4 grid grid-cols-12 gap-4">
    <div class="col-span-4 text-sm font-medium text-gray-700">
        <label>Filer Type</label>
        <div class="mt-1 text-sm text-gray-600">
            Please select the appropriate radio button to determine in which file type does the taxpayer belong. Choose between Single Proprietor, Professional, Estate, or Trust.
        </div>
    </div>
    <div class="col-span-8">
        <div class="space-y-4">
            <label class="flex items-center">
                <input type="radio" name="filer_type" value="single_proprietor" 
                    {{ old('filer_type', $backgroundInfo->filer_type) == 'single_proprietor' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                Single Proprietor
            </label>
            <label class="flex items-center">
                <input type="radio" name="filer_type" value="professional" 
                    {{ old('filer_type', $backgroundInfo->filer_type) == 'professional' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                Professional
            </label>
            <label class="flex items-center">
                <input type="radio" name="filer_type" value="estate" 
                    {{ old('filer_type', $backgroundInfo->filer_type) == 'estate' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                Estate
            </label>
            <label class="flex items-center">
                <input type="radio" name="filer_type" value="trust" 
                    {{ old('filer_type', $backgroundInfo->filer_type) == 'trust' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                Trust
            </label>
        </div>
        @error('filer_type')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>
    <div class="mt-4 flex space-x-2">
        <button type="button" @click="prevStep()" class="bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-700">Back</button>
        <button type="button" @click="nextStep()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Next</button>
    </div>
</div>


<!-- Step 3: Alphanumeric Tax Code -->
<div x-show="step === 3" class="mb-4 grid grid-cols-12 gap-4">
    <div class="col-span-4 text-sm font-medium text-gray-700">
        <label>Alphanumeric Tax Code</label>
        <div class="mt-1 text-sm text-gray-600">
            ATC or Alphanumeric Tax Code (8) is a system of figures that the Taxpayer/Filer uses for his/her business. Choose between Compensation (011), Business (012) and Mixed Income (013), depending on what category the business falls at.
        </div>
    </div>
    <div class="col-span-8">
        <div class="space-y-4">
            <label class="flex items-center">
                <input type="radio" name="alphanumeric_tax_code" value="011" 
                    {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '011' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                    II011 Compensation Income
            </label>
            <label class="flex items-center">
                <input type="radio" name="alphanumeric_tax_code" value="012" 
                    {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '012' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                    II012 Business Income-Graduated IT Rates
            </label>
            <label class="flex items-center">
                <input type="radio" name="alphanumeric_tax_code" value="013" 
                    {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '013' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                    II013 Mixed Income - Graduated IT Rates
            </label>
            <label class="flex items-center">
                <input type="radio" name="alphanumeric_tax_code" value="014" 
                    {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '014' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                    II014 Income from Profession-Graduated IT Rates
            </label>
            <label class="flex items-center">
                <input type="radio" name="alphanumeric_tax_code" value="015" 
                    {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '015' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                    II015 Business Income - 8% IT Rate 
            </label>
            <label class="flex items-center">
                <input type="radio" name="alphanumeric_tax_code" value="016" 
                    {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '016' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                    II016 Mixed Income - 8% IT Rate
            </label>
            <label class="flex items-center">
                <input type="radio" name="alphanumeric_tax_code" value="017" 
                    {{ old('alphanumeric_tax_code', $backgroundInfo->alphanumeric_tax_code) == '017' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                    II017 Income from Profession - 8% IT Rate
            </label>
        </div>
        @error('alphanumeric_tax_code')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>
    <div class="mt-4 flex space-x-2">
        <button type="button" @click="prevStep()" class="bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-700">Back</button>
        <button type="button" @click="nextStep()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Next</button>
    </div>
</div>
<!-- Step 4: Civil Status -->
<div x-show="step === 4" class="mb-4 grid grid-cols-12 gap-4">
    <!-- Civil Status -->
    <div class="col-span-4 text-sm font-medium text-gray-700">
        <label>Civil Status</label>
        <div class="mt-1 text-sm text-gray-600">
            Choose whether the Taxpayer/Filer is Single or Married. If Married, it will enable the next step to determine the Spouse’s Employment Status.
        </div>
    </div>
    <div class="col-span-8">
        <div class="space-x-4">
            <label class="inline-flex items-center">
                <input type="radio" name="civil_status" value="single" 
                    {{ old('civil_status', $backgroundInfo->civil_status) == 'single' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                Single
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="civil_status" value="married" 
                    {{ old('civil_status', $backgroundInfo->civil_status) == 'married' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                Married
            </label>
        </div>
        @error('civil_status')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

 <!-- Spouse’s Employment Status -->
<div class="col-span-4 text-sm font-medium text-gray-700 mt-4">
    <label>Spouse’s Employment Status</label>
    <div class="mt-1 text-sm text-gray-600">
        Specify your spouse’s employment distinction. If employed, additional spouse details will be required.
    </div>
</div>
<div class="col-span-8">
    <div class="space-x-4">
        <label class="inline-flex items-center">
            <input type="radio" name="spouse_employment_status" value="employed" 
                {{ old('spouse_employment_status', $spouseInfo?->employment_status) == 'employed' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
            Employed
        </label>
        <label class="inline-flex items-center">
            <input type="radio" name="spouse_employment_status" value="unemployed" 
                {{ old('spouse_employment_status', $spouseInfo?->employment_status) == 'unemployed' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
            Unemployed
        </label>
    </div>
    @error('spouse_employment_status')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>

<!-- Spouse’s Information -->
<div class="col-span-12 mt-6">
    <p class="text-sm text-gray-600">
        Spouse’s Information: Please make sure that all fields are properly filled with the correct data before you hit Save. All information provided will reflect in the Spouse’s Background Information of the form.
    </p>
</div>

<!-- TIN and RDO Code -->
<div class="col-span-6">
    <label class="block text-sm font-medium text-gray-700">Spouse’s Tax Identification Number</label>
    <input type="text" name="spouse_tin" value="{{ old('spouse_tin', $spouseInfo?->tin) }}" 
           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
</div>
<div class="col-span-6">
    <label class="block text-sm font-medium text-gray-700">RDO Code</label>
    <input type="text" name="spouse_rdo" value="{{ old('spouse_rdo', $spouseInfo?->rdo) }}" 
           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
</div>

<!-- Spouse's Name -->
<div class="col-span-12 mt-4">
    <label class="block text-sm font-medium text-gray-700">Spouse's Name (Last Name, First Name, Middle Name)</label>
    <input type="text" name="spouse_name" value="{{ old('spouse_name', $spouseInfo ? implode(', ', [$spouseInfo->last_name, $spouseInfo->first_name, $spouseInfo->middle_name]) : '') }}" 
           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
    @error('spouse_name')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>

<!-- Filer's Spouse Type -->
<div class="col-span-12 text-sm font-medium text-gray-700 mt-4">
    <label>Filer’s Spouse Type</label>
    <div class="mt-1 text-sm text-gray-600">
        Choose the appropriate Filer's Spouse Type. Select from Single Proprietorship, Professional, or Compensation Owner.
    </div>
</div>

<div class="col-span-12">
    <div class="space-x-4">
        <label class="inline-flex items-center">
            <input type="radio" name="spouse_type" value="single_proprietor" 
                {{ old('spouse_type', $spouseInfo?->filer_type) == 'single_proprietor' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
            Single Proprietorship
        </label>
        <label class="inline-flex items-center">
            <input type="radio" name="spouse_type" value="professional" 
                {{ old('spouse_type', $spouseInfo?->filer_type) == 'professional' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
            Professional
        </label>
        <label class="inline-flex items-center">
            <input type="radio" name="spouse_type" value="compensation_owner" 
                {{ old('spouse_type', $spouseInfo?->filer_type) == 'compensation_owner' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
            Compensation Owner
        </label>
    </div>
    @error('spouse_type')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>
<div class="col-span-12 text-sm font-medium text-gray-700 mt-4">
    <label>Spouse's Alphanumeric Tax Code</label>
    <div class="mt-1 text-sm text-gray-600">
        Choose the appropriate Alphanumeric Tax Code that matches for the individual's spouse.
    </div>
</div>

<div class="col-span-12">
    <div class="space-y-4">
        <label class="flex items-center">
            <input type="radio" name="spouse_alphanumeric_tax_code" value="011" 
                {{ old('spouse_alphanumeric_tax_code', $spouseInfo->alphanumeric_tax_code) == '011' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                II011 Compensation Income
        </label>
        <label class="flex items-center">
            <input type="radio" name="spouse_alphanumeric_tax_code" value="012" 
                {{ old('spouse_alphanumeric_tax_code', $spouseInfo->alphanumeric_tax_code) == '012' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                II012 Business Income-Graduated IT Rates
        </label>
        <label class="flex items-center">
            <input type="radio" name="spouse_alphanumeric_tax_code" value="013" 
                {{ old('spouse_alphanumeric_tax_code', $spouseInfo->alphanumeric_tax_code) == '013' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                II013 Mixed Income - Graduated IT Rates
        </label>
        <label class="flex items-center">
            <input type="radio" name="spouse_alphanumeric_tax_code" value="014" 
                {{ old('spouse_alphanumeric_tax_code', $spouseInfo->alphanumeric_tax_code) == '014' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                II014 Income from Profession-Graduated IT Rates
        </label>
        <label class="flex items-center">
            <input type="radio" name="spouse_alphanumeric_tax_code" value="015" 
                {{ old('spouse_alphanumeric_tax_code', $spouseInfo->alphanumeric_tax_code) == '015' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                II015 Business Income - 8% IT Rate 
        </label>
        <label class="flex items-center">
            <input type="radio" name="spouse_alphanumeric_tax_code" value="016" 
                {{ old('spouse_alphanumeric_tax_code', $spouseInfo->alphanumeric_tax_code) == '016' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                II016 Mixed Income - 8% IT Rate
        </label>
        <label class="flex items-center">
            <input type="radio" name="spouse_alphanumeric_tax_code" value="017" 
                {{ old('spouse_alphanumeric_tax_code', $spouseInfo->alphanumeric_tax_code) == '017' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                II017 Income from Profession - 8% IT Rate
        </label>
    </div>
    @error('spouse_alphanumeric_tax_code')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>
<div class="col-span-12">
    <label class="block text-sm font-medium text-gray-700">Spouse’s Citizeship</label>
    <input type="text" name="spouse_citizenship" value="{{ old('spouse_citizenship', $spouseInfo?->citizenship) }}" 
           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
</div>
<div class="col-span-12">
    <label class="block text-sm font-medium text-gray-700">Spouse’s Foreign Tax Number</label>
    <input type="text" name="spouse_foreign_tax_number" value="{{ old('spouse_foreign_tax_number', $spouseInfo?->foreign_tax_number) }}" 
           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
</div>
<div class="col-span-4 text-sm font-medium text-gray-700 mt-4">
    <label>Claiming Foreign Tax Credits</label>
    <div class="mt-1 text-sm text-gray-600">
        Are you claiming foreign tax credits? (Yes/No)
    </div>
</div>
<div class="col-span-8">
    <div class="space-x-4">
        <label class="inline-flex items-center">
            <input type="radio" name="spouse_foreign_tax_credits" value="Yes" 
                {{ old('spouse_foreign_tax_credits', $spouseInfo->claiming_foreign_credits) == '1' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
            Yes
        </label>
        <label class="inline-flex items-center">
            <input type="radio" name="spouse_foreign_tax_credits" value="No" 
                {{ old('spouse_foreign_tax_credits', $spouseInfo->claiming_foreign_credits) == '0' ? 'checked' : '' }}
                class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
            No
        </label>
    </div>
    @error('spouse_foreign_tax_credits')
        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
    @enderror
</div>

<!-- Buttons -->
<div class="mt-4 flex space-x-2">
    <button type="button" @click="prevStep()" class="bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-700">Back</button>
    <button type="button" @click="nextStep()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Next</button>
</div>

</div>


<!-- Step 5: Other Information -->
<div x-show="step === 5" class="mb-4 grid grid-cols-12 gap-4">
    <!-- Citizenship -->
    <div class="col-span-4 text-sm font-medium text-gray-700">
        <label>Citizenship</label>
        <div class="mt-1 text-sm text-gray-600">
            Please specify your citizenship status (e.g., Filipino, Dual Citizen, etc.).
        </div>
    </div>
    <div class="col-span-8">
        <input type="text" name="citizenship" value="{{ old('citizenship', $backgroundInfo->citizenship) }}" 
               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        @error('citizenship')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Foreign Tax (Text Field) -->
    <div class="col-span-4 text-sm font-medium text-gray-700 mt-4">
        <label>Foreign Tax</label>
        <div class="mt-1 text-sm text-gray-600">
            Specify any foreign tax information here.
        </div>
    </div>
    <div class="col-span-8">
        <input type="text" name="foreign_tax" value="{{ old('foreign_tax', $backgroundInfo->foreign_tax) }}" 
               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        @error('foreign_tax')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Claiming Foreign Tax Credits -->
    <div class="col-span-4 text-sm font-medium text-gray-700 mt-4">
        <label>Claiming Foreign Tax Credits</label>
        <div class="mt-1 text-sm text-gray-600">
            Are you claiming foreign tax credits? (Yes/No)
        </div>
    </div>
    <div class="col-span-8">
        <div class="space-x-4">
            <label class="inline-flex items-center">
                <input type="radio" name="foreign_tax_credits" value="Yes" 
                    {{ old('foreign_tax_credits', $backgroundInfo->claiming_foreign_credits) == '1' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                Yes
            </label>
            <label class="inline-flex items-center">
                <input type="radio" name="foreign_tax_credits" value="No" 
                    {{ old('foreign_tax_credits', $backgroundInfo->claiming_foreign_credits) == '0' ? 'checked' : '' }}
                    class="mr-2 border-gray-300 text-blue-500 focus:ring-blue-500">
                No
            </label>
        </div>
        @error('foreign_tax_credits')
            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    <!-- Save Button -->
    <div class="mt-4 flex space-x-2 col-span-12">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Save Changes</button>
    </div>
</div>

</form>

                    
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
