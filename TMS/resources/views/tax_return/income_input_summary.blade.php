
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                <!-- Main Content -->
                <div x-data="{
                    selectedTab: 'input-summary',  // Default tab
                    changeTab(tab) {
                        this.selectedTab = tab;
                    }
                }" class="container mx-auto">

                    <!-- Tabs Navigation -->
                    <div class="flex justify-start space-x-8 mb-6">
                        <button 
                            @click="changeTab('input-summary')" 
                            :class="selectedTab === 'input-summary' ? 'font-bold text-blue-900 bg-slate-100 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900'" 
                            class="px-4 py-2 text-sm"
                        >
                            Input Summary
                        </button>
                        <a 
                            href="{{ route('income_return.report', ['id' => $taxReturn->id]) }}" 
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
                        <!-- Card for Background Information -->
<div class="p-4 bg-white rounded-lg shadow-md">
    <h3 class="text-lg font-semibold">Background Information</h3>
    <p class="text-sm text-gray-700 mb-2">Enter your background details.</p>

    <!-- Link to the Background Information Setup -->
    <a href="{{ route('tax_return.background_information', ['id' => $taxReturn->id]) }}" 
       class="text-blue-500 hover:text-blue-700">
        Go to Background Information Setup
    </a>
</div>
                        <div class="p-4 bg-white rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold">Tax Option Rate</h3>
                            <p class="text-sm text-gray-700 mb-2">Set the tax option rate for your return.</p>
                            <a href="{{ route('tax_return.tax_option_rate', ['id' => $taxReturn->id]) }}" 
                               class="text-blue-500 hover:text-blue-700">
                                Go to Tax Option Rate Setup
                            </a>
                        </div>



              

                        <!-- Card for Sales/Revenues/Receipts/Fees -->
                        <div class="p-4 bg-white rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold">Sales/Revenues/Receipts/Fees</h3>
                            <p class="text-sm text-gray-700 mb-2">Enter your sales/revenue/fees details.</p>
                            <a href="{{ route('tax_return.income_show_sales', ['taxReturn' => $taxReturn->id]) }}" 
                               class="text-blue-500 hover:text-blue-700">
                                Go to Sales/Revenue Setup
                            </a>
                        </div>
                        <div class="p-4 bg-white rounded-lg shadow-md">
                            <h3 class="text-lg font-semibold">Itemized Deduction and Cost of Goods Sold</h3>
                            <p class="text-sm text-gray-700 mb-2">Enter Itemized Deduction and Cost of Goods Transactions</p>
                            <a href="{{ route('tax_return.income_show_coa', ['taxReturn' => $taxReturn->id]) }}" 
                               class="text-blue-500 hover:text-blue-700">
                                Go to Sales/Revenue Setup
                            </a>
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