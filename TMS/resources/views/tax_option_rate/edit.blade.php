<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                            <a 
                                href="{{ route('tax_return.notes_activities', ['id' => $taxReturn->id]) }}" 
                                class="text-zinc-600 font-medium hover:text-blue-900 px-4 py-2 text-sm"
                            >
                                Notes and Activities
                            </a>
                        </div>

                        <!-- Tab Content for Input Summary -->
                        <div x-show="selectedTab === 'input-summary'" class="space-y-6">
                            <!-- Card for Tax Option Rate -->
                            <div class="p-4 bg-white rounded-lg shadow-md">
                                <h3 class="text-lg font-semibold">Tax Option Rate</h3>
                                <p class="text-sm text-gray-700 mb-2">Set the tax option rate for your return.</p>

                                <form action="{{ route('tax_return.tax_option_rate.update', ['id' => $taxReturn->id]) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Choose Tax Option</label>
                                        <div class="mt-2 flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="rate_type" value="graduated_rates" 
                                                    x-model="selectedRate"
                                                    @change="resetDeduction" 
                                                    {{ $taxOptionRate->rate_type === 'graduated_rates' ? 'checked' : '' }}
                                                    class="form-radio text-blue-600">
                                                <span class="ml-2">Graduated Rates</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="rate_type" value="8_percent" 
                                                    x-model="selectedRate"
                                                    @change="resetDeduction" 
                                                    {{ $taxOptionRate->rate_type === '8_percent' ? 'checked' : '' }}
                                                    class="form-radio text-blue-600">
                                                <span class="ml-2">8% Gross Sales/Receipts</span>
                                            </label>
                                        </div>
                                        @error('rate_type')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Additional field when "Graduated Rates" is selected -->
                                    <div x-show="toggleDeductionOption()" class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Choose Deduction Method</label>
                                        <div class="mt-2 flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="deduction_method" value="itemized" 
                                                    x-model="selectedDeductionMethod"
                                                    {{ $taxOptionRate->deduction_method === 'itemized' ? 'checked' : '' }}
                                                    class="form-radio text-blue-600">
                                                <span class="ml-2">Itemized Deductions</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="deduction_method" value="osd" 
                                                    x-model="selectedDeductionMethod"
                                                    {{ $taxOptionRate->deduction_method === 'osd' ? 'checked' : '' }}
                                                    class="form-radio text-blue-600">
                                                <span class="ml-2">Optional Standard Deduction (OSD)</span>
                                            </label>
                                        </div>
                                        @error('deduction_method')
                                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    @if ($individualBackground->civil_status === 'married')
                                        <!-- Spouse Tax Option Rate -->
                                        <div class="mt-6">
                                            <h4 class="text-lg font-semibold">Spouse's Tax Option Rate</h4>
                                            <p class="text-sm text-gray-700 mb-2">Set the tax option rate for the spouse if applicable.</p>

                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Spouse's Tax Option</label>
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="spouse_rate_type" value="graduated_rates" 
                                                            x-model="selectedSpouseRate"
                                                            @change="resetSpouseDeduction"
                                                            class="form-radio text-blue-600">
                                                        <span class="ml-2">Graduated Rates</span>
                                                    </label>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="spouse_rate_type" value="8_percent" 
                                                            x-model="selectedSpouseRate"
                                                            @change="resetSpouseDeduction"
                                                            class="form-radio text-blue-600">
                                                        <span class="ml-2">8% Gross Sales/Receipts</span>
                                                    </label>
                                                </div>
                                                @error('spouse_rate_type')
                                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <!-- Additional field for spouse when "Graduated Rates" is selected -->
                                            <div x-show="toggleSpouseDeductionOption()" class="mb-4">
                                                <label class="block text-sm font-medium text-gray-700">Spouse's Deduction Method</label>
                                                <div class="mt-2 flex items-center space-x-4">
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="spouse_deduction_method" value="itemized" 
                                                            x-model="selectedSpouseDeductionMethod"
                                                            class="form-radio text-blue-600">
                                                        <span class="ml-2">Itemized Deductions</span>
                                                    </label>
                                                    <label class="inline-flex items-center">
                                                        <input type="radio" name="spouse_deduction_method" value="osd" 
                                                            x-model="selectedSpouseDeductionMethod"
                                                            class="form-radio text-blue-600">
                                                        <span class="ml-2">Optional Standard Deduction (OSD)</span>
                                                    </label>
                                                </div>
                                                @error('spouse_deduction_method')
                                                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif

                                    <div class="mt-6">
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
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
