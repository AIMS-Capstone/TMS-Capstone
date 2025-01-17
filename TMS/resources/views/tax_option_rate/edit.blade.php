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

                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Main Content -->
                    <div x-data="{
                        selectedTab: 'input-summary',  // Default tab
                        selectedRate: '{{ $taxOptionRate->rate_type }}', // Set the selected rate type from the backend
                        selectedDeductionMethod: '{{ $taxOptionRate->deduction_method }}', // Set the selected deduction method
                        selectedSpouseRate: '{{ $spouseTaxOptionRate->rate_type ?? '' }}', // Set the spouse rate type if exists
                        selectedSpouseDeductionMethod: '{{ $spouseTaxOptionRate->deduction_method ?? '' }}', // Set the spouse deduction method if exists
                        
                        changeTab(tab) {
                            this.selectedTab = tab;
                        },
                        toggleDeductionOption() {
                            return this.selectedRate === 'graduated_rates';
                        },
                        resetDeduction() {
                            if (this.selectedRate === '8_percent') {
                                this.selectedDeductionMethod = null; // Reset deduction method when 8% is selected
                            }
                        },

                        toggleSpouseDeductionOption() {
                            return this.selectedSpouseRate === 'graduated_rates';
                        },
                        resetSpouseDeduction() {
                            if (this.selectedSpouseRate === '8_percent') {
                                this.selectedSpouseDeductionMethod = null; // Reset spouse deduction when 8% is selected
                            }
                        }
                        }" class="container mx-auto">

                        <!-- Tabs Navigation -->
                        <div class="flex justify-start space-x-8 mb-6">
                            <a 
                                href="{{route('income_return.show', ['id' => $taxReturn->id, 'type' => $taxReturn->title])}}"
                                @click="changeTab('input-summary')" 
                                :class="selectedTab === 'input-summary' ? 'font-bold text-blue-900 bg-slate-100 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900'" 
                                class="px-4 py-2 text-sm"
                            >
                                Input Summary
                            </a>
                            <a 
                                href="{{ route('tax_return.report', ['id' => $taxReturn->id]) }}" 
                                class="text-zinc-600 font-medium hover:text-blue-900 px-4 py-2 text-sm"
                            >
                                Report
                            </a>
                           
                        </div>

                        <!-- Tab Content for Input Summary -->
                        <div x-show="selectedTab === 'input-summary'" class="space-y-6">
                            <!-- Card for Tax Option Rate -->
                            <div class="p-4 bg-white rounded-lg border shadow-sm">
                                <h3 class="text-lg text-zinc-700 font-bold">Tax Option Rate</h3>
                                <p class="text-sm text-gray-500 mb-2">Set the tax option rate for your return.</p>

                                <form action="{{ route('tax_return.tax_option_rate.update', ['id' => $taxReturn->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <label class="block text-sm font-bold text-zinc-700">Choose Tax Option</label>
                                        <div class="mt-2 flex items-center space-x-4 text-zinc-700">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="rate_type" value="graduated_rates" 
                                                    x-model="selectedRate"
                                                    @change="resetDeduction" 
                                                    {{ $taxOptionRate->rate_type === 'graduated_rates' ? 'checked' : '' }}
                                                    class="form-radio text-blue-900">
                                                <span class="ml-2">Graduated Rates</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="rate_type" value="8_percent" 
                                                    x-model="selectedRate"
                                                    @change="resetDeduction" 
                                                    {{ $taxOptionRate->rate_type === '8_percent' ? 'checked' : '' }}
                                                    class="form-radio text-blue-900">
                                                <span class="ml-2">8% Gross Sales/Receipts</span>
                                            </label>
                                        </div>
                                        @error('rate_type')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Additional field when "Graduated Rates" is selected -->
                                    <div x-show="toggleDeductionOption()" class="mb-4">
                                        <label class="block text-sm font-bold text-zinc-700">Choose Deduction Method</label>
                                        <div class="mt-2 flex items-center space-x-4 text-zinc-700">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="deduction_method" value="itemized" 
                                                    x-model="selectedDeductionMethod"
                                                    {{ $taxOptionRate->deduction_method === 'itemized' ? 'checked' : '' }}
                                                    class="form-radio text-blue-900">
                                                <span class="ml-2">Itemized Deductions</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="deduction_method" value="osd" 
                                                    x-model="selectedDeductionMethod"
                                                    {{ $taxOptionRate->deduction_method === 'osd' ? 'checked' : '' }}
                                                    class="form-radio text-blue-900">
                                                <span class="ml-2">Optional Standard Deduction (OSD)</span>
                                            </label>
                                        </div>
                                        @error('deduction_method')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="mt-6">
                                        <button type="submit" class="bg-blue-900 text-white px-6 py-1.5 rounded-lg font-semibold hover:bg-blue-950">
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
