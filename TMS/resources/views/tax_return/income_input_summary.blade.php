
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
                    </div>

                    
                    <!-- Tab Content for Input Summary -->
                    <div x-show="selectedTab === 'input-summary'" class="space-y-6">
             
                        <!-- Card for Background Information -->
                        <div class="p-4 bg-white rounded-lg border hover:bg-blue-50 hover:border-blue-200 cursor-pointer ease-in-out shadow-sm">
                            <h3 class="text-lg text-zinc-700 font-bold">Background Information</h3>
                            <p class="text-sm text-zinc-500 mb-2">Enter your background details.</p>

                            <!-- Link to the Background Information Setup -->
                            <a href="{{ route('tax_return.background_information', ['id' => $taxReturn->id]) }}" 
                            class="text-sm flex items-center text-blue-500 hover:underline hover:text-blue-700">
                                <span>Go to Background Information Setup</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 transition group-hover:text-blue-500" viewBox="0 0 50 50"><path fill="currentColor" d="M25 42c-9.4 0-17-7.6-17-17S15.6 8 25 8s17 7.6 17 17s-7.6 17-17 17m0-32c-8.3 0-15 6.7-15 15s6.7 15 15 15s15-6.7 15-15s-6.7-15-15-15"/><path fill="currentColor" d="m24.7 34.7l-1.4-1.4l8.3-8.3l-8.3-8.3l1.4-1.4l9.7 9.7z"/><path fill="currentColor" d="M16 24h17v2H16z"/></svg> 
                            </a>
                        </div>
                        <div class="p-4 bg-white rounded-lg border hover:bg-blue-50 hover:border-blue-200 cursor-pointer ease-in-out shadow-sm">
                            <h3 class="text-lg text-zinc-700 font-bold">Tax Option Rate</h3>
                            <p class="text-sm text-zinc-500 mb-2">Set the tax option rate for your return.</p>
                            <a href="{{ route('tax_return.tax_option_rate', ['id' => $taxReturn->id]) }}" 
                                class="text-sm flex items-center text-blue-500 hover:underline hover:text-blue-700">
                                <span>Go to Tax Option Rate Setup</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 transition group-hover:text-blue-500" viewBox="0 0 50 50"><path fill="currentColor" d="M25 42c-9.4 0-17-7.6-17-17S15.6 8 25 8s17 7.6 17 17s-7.6 17-17 17m0-32c-8.3 0-15 6.7-15 15s6.7 15 15 15s15-6.7 15-15s-6.7-15-15-15"/><path fill="currentColor" d="m24.7 34.7l-1.4-1.4l8.3-8.3l-8.3-8.3l1.4-1.4l9.7 9.7z"/><path fill="currentColor" d="M16 24h17v2H16z"/></svg>
                            </a>
                        </div>
                        <!-- Card for Sales/Revenues/Receipts/Fees -->
                        <div class="p-4 bg-white rounded-lg border hover:bg-blue-50 hover:border-blue-200 cursor-pointer ease-in-out shadow-sm">
                            <h3 class="text-lg text-zinc-700 font-bold">Sales/Revenues/Receipts/Fees</h3>
                            <p class="text-sm text-zinc-500 mb-2">Enter your Sales/Revenue/Fees details.</p>
                            <a href="{{ route('tax_return.income_show_sales', ['taxReturn' => $taxReturn->id]) }}" 
                                class="text-sm flex items-center text-blue-500 hover:underline hover:text-blue-700">
                                <span>Go to Sales/Revenue Setup</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 transition group-hover:text-blue-500" viewBox="0 0 50 50"><path fill="currentColor" d="M25 42c-9.4 0-17-7.6-17-17S15.6 8 25 8s17 7.6 17 17s-7.6 17-17 17m0-32c-8.3 0-15 6.7-15 15s6.7 15 15 15s15-6.7 15-15s-6.7-15-15-15"/><path fill="currentColor" d="m24.7 34.7l-1.4-1.4l8.3-8.3l-8.3-8.3l1.4-1.4l9.7 9.7z"/><path fill="currentColor" d="M16 24h17v2H16z"/></svg>
                            </a>
                        </div>
                        <div class="p-4 bg-white rounded-lg border hover:bg-blue-50 hover:border-blue-200 cursor-pointer ease-in-out shadow-sm">
                            <h3 class="text-lg text-zinc-700 font-bold">Itemized Deduction and Cost of Goods Sold</h3>
                            <p class="text-sm text-zinc-500 mb-2">Enter Itemized Deduction and Cost of Goods Transactions</p>
                            <a href="{{ route('tax_return.income_show_coa', ['taxReturn' => $taxReturn->id]) }}" 
                                class="text-sm flex items-center text-blue-500 hover:underline hover:text-blue-700">
                                <span>Go to Sales/Revenue Setup</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 transition group-hover:text-blue-500" viewBox="0 0 50 50"><path fill="currentColor" d="M25 42c-9.4 0-17-7.6-17-17S15.6 8 25 8s17 7.6 17 17s-7.6 17-17 17m0-32c-8.3 0-15 6.7-15 15s6.7 15 15 15s15-6.7 15-15s-6.7-15-15-15"/><path fill="currentColor" d="m24.7 34.7l-1.4-1.4l8.3-8.3l-8.3-8.3l1.4-1.4l9.7 9.7z"/><path fill="currentColor" d="M16 24h17v2H16z"/></svg>
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