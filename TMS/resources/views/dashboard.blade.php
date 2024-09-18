<x-app-layout>
    <div class="h-full bg-blue-900 p-10 mx-auto sm:px-6 lg:px-8">
    <div class="relative">
        <h1 class="text-amber-400 text-3xl font-bold">{{ $organization->registration_name }}</h1>
        <p class="mt-2 text-white text-sm">
            All transactions, activities, and reports are directly linked to this organization. 
            You will find a<br />comprehensive overview of the organization’s financial activities and 
            tax-related information here.
        </p>
    </div>
</div>

<div class="py-6 h-full">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 px-8 whitespace-nowrap text-wrap">
        <!-- Left Metric Card -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between">
            <!-- Total Filed -->
            <div class="flex items-center space-x-4">
                <div class="bg-blue-900 text-white rounded-full h-12 w-12 flex items-center justify-center text-lg font-bold leading-none aspect-w-1 aspect-h-1">22</div>
                <div>
                    <h2 class="text-zinc-600 font-bold">Total Filed</h2>
                    <p class="text-gray-500 text-xs">Total number of tax returns successfully submitted</p>
                </div>
            </div>

            <div class="h-12 border-l border-gray-200 mx-6"></div>

            <!-- Unfiled Taxes -->
            {{-- <div class="flex items-center space-x-4">
                <div class="bg-blue-900 text-white rounded-full h-12 w-12 flex items-center justify-center text-lg font-bold leading-none aspect-w-1 aspect-h-1">13</div>
                <div>
                    <h2 class="text-zinc-600 font-bold">Unfiled Taxes</h2>
                    <p class="text-gray-500 text-xs">Total number of pending or overdue tax returns</p>
                </div>
            </div> --}}
        </div>

        <!-- Right Metric Card -->
        <div class="bg-white shadow-md rounded-lg p-6 flex items-center justify-between">
            <!-- Total Sales -->
            <div class="flex items-center space-x-4">
                <div class="bg-blue-900 text-white rounded-full h-12 w-12 flex items-center justify-center text-lg font-bold leading-none aspect-w-1 aspect-h-1">36</div>
                <div>
                    <h2 class="text-zinc-600 font-bold">Total Sales</h2>
                    <p class="text-gray-500 text-xs">Total number of sales-related transactions</p>
                </div>
            </div>

            <div class="h-12 border-l border-gray-200 mx-6"></div>
            
            <!-- Total Purchases -->
            {{-- <div class="flex items-center space-x-4">
                <div class="bg-blue-900 text-white rounded-full h-12 w-12 flex items-center justify-center text-lg font-bold leading-none aspect-w-1 aspect-h-1">87</div>
                <div>
                    <h2 class="text-zinc-600 font-bold">Total Purchases</h2>
                    <p class="text-gray-500 text-xs">Total number of purchase-related transactions</p>
                </div>
            </div> --}}
        </div>
    </div>
</div>

<div class="py-4 h-full">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-4 gap-4 whitespace-nowrap text-wrap">
            <div class="p-6 col-span-2 bg-white border-gray-200 rounded-lg">
                <div class="flex items-center space-x-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 36 36"><path fill="#1e3a8a" d="M32.25 6h-4v3a2.2 2.2 0 1 1-4.4 0V6H12.2v3a2.2 2.2 0 0 1-4.4 0V6h-4A1.78 1.78 0 0 0 2 7.81v22.38A1.78 1.78 0 0 0 3.75 32h28.5A1.78 1.78 0 0 0 34 30.19V7.81A1.78 1.78 0 0 0 32.25 6M10 26H8v-2h2Zm0-5H8v-2h2Zm0-5H8v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Z" class="clr-i-solid clr-i-solid-path-1"/><path fill="#172554" d="M10 10a1 1 0 0 0 1-1V3a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1" class="clr-i-solid clr-i-solid-path-2"/><path fill="#172554" d="M26 10a1 1 0 0 0 1-1V3a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1" class="clr-i-solid clr-i-solid-path-3"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                    <span class="font-bold text-2xl taxuri-color leading-tight">Tax Reminder</span>
                </div>
                <p class="font-normal text-xs">Stay updated with essential tax deadlines and obligations. Easily keep track of important
                    filling and payment dates to ensure seamless compliance with the BIR regulations.
                </p>
                <div class="my-3"><hr /></div>

                <div class="flex">
                    <div class="flex bg-gray-100 rounded-full transition p-1">
                        <nav class="flex gap-x-1" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                            <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm text-zinc-500 transition duration-300 focus:outline-none font-bold rounded-full disabled:opacity-50 disabled:pointer-events-none"
                            id="tab-today" aria-selected="true" role="tab" onclick="activateTab('tab-today')">
                                Today
                            </button>
                            <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm text-zinc-500 transition duration-300 focus:outline-none font-bold rounded-full disabled:opacity-50 disabled:pointer-events-none"
                            id="tab-upcoming" aria-selected="false" role="tab" onclick="activateTab('tab-upcoming')">
                                Upcoming
                            </button>
                            <button type="button" class="py-3 px-4 inline-flex items-center gap-x-2 text-sm text-zinc-500 transition duration-300 focus:outline-none font-bold rounded-full disabled:opacity-50 disabled:pointer-events-none"
                            id="tab-completed" aria-selected="false" role="tab" onclick="activateTab('tab-completed')">
                                Completed
                            </button>
                        </nav>
                    </div>
                </div>
                
                {{-- TODAY TAB CONTENT --}}
                <div id="tab-today-content" role="tabpanel" aria-labelledby="tab-today" class="overflow-x-auto px-2">
                    {{-- Accordion 1 --}}
                    <div id="accordion-flush-1" data-accordion="collapse" data-active-classes="bg-white text-gray-900" data-inactive-classes="text-gray-500">
                    <h2 id="accordion-flush-heading-1">
                        <button type="button" id="accordion-button-1" class="flex items-center justify-between w-full py-5 font-medium rtl:text-right text-blue-900 border-b border-gray-200 gap-3" data-accordion-target="#accordion-flush-body-1" aria-expanded="false" aria-controls="accordion-flush-body-1">
                        <span>September 11, 2024</span>
                        <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                        </svg>
                        </button>
                    </h2>
                    <div id="accordion-flush-body-1" class="hidden" aria-labelledby="accordion-flush-heading-1">
                        <div class="py-5 border-b border-gray-200 max-h-40 overflow-y-auto">
                        <b class="text-blue-900">e-FILING</b>
                        <p class="mb-2 text-gray-500 text-sm">
                        <br/>BIR Forms 1601-C (Monthly Remittance Return of Income Taxes Withheld on Compensation) and/or 0619-E (Monthly Remittance Form of Creditable Income Taxes Withheld-Expanded) and/or 0619-F (Monthly Remittance Form of Final Income Taxes Withheld) – eFPS Filers under Group E.  <b>Month of August 2024</b></p>
                        </div>
                    </div>
                    </div>
                </div>

                {{-- UPCOMING TAB CONTENT --}}
                <div id="tab-upcoming-content" role="tabpanel" aria-labelledby="tab-upcoming" class="hidden overflow-x-auto px-2">
                    {{-- Accordion 2 --}}
                    <div id="accordion-flush-2" data-accordion="collapse" data-active-classes="bg-white text-gray-900" data-inactive-classes="text-gray-500">
                        <h2 id="accordion-flush-heading-2">
                            <button type="button" id="accordion-button-2" class="flex items-center justify-between w-full py-5 font-medium rtl:text-right text-blue-900 border-b border-gray-200 gap-3" data-accordion-target="#accordion-flush-body-2" aria-expanded="false" aria-controls="accordion-flush-body-2">
                                <span>September 12, 2024</span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-flush-body-2" class="hidden" aria-labelledby="accordion-flush-heading-2">
                            <div class="py-5 border-b border-gray-200 max-h-40 overflow-y-auto">
                                <b class="text-blue-900">e-FILING</b>
                                <p class="mb-2 text-gray-500 text-sm">
                                <br/>BIR Forms 1601-C (Monthly Remittance Return of Income Taxes Withheld on Compensation) and/or 0619-E (Monthly Remittance Form of Creditable Income Taxes Withheld-Expanded) and/or 0619-F (Monthly Remittance Form of Final Income Taxes Withheld) – eFPS Filers under Group D.  <b>Month of August 2024</b></p>
                            </div>
                        </div>
                    </div>
                    {{-- ACCORDION 3 --}}
                    <div id="accordion-flush-3" data-accordion="collapse" data-active-classes="bg-white text-gray-900" data-inactive-classes="text-gray-500">
                        <h2 id="accordion-flush-heading-3">
                            <button type="button" id="accordion-button-3" class="flex items-center justify-between w-full py-5 font-medium rtl:text-right text-blue-900 border-b border-gray-200 gap-3" data-accordion-target="#accordion-flush-body-3" aria-expanded="false" aria-controls="accordion-flush-body-3">
                                <span>September 13, 2024</span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-flush-body-3" class="hidden" aria-labelledby="accordion-flush-heading-3">
                            <div class="py-5 border-b border-gray-200 max-h-40 overflow-y-auto">
                                <b class="text-blue-900">e-FILING</b>
                                <p class="mb-2 text-gray-500 text-sm">
                                <br/>BIR Forms 1601-C (Monthly Remittance Return of Income Taxes Withheld on Compensation) and/or 0619-E (Monthly Remittance Form of Creditable Income Taxes Withheld-Expanded) and/or 0619-F (Monthly Remittance Form of Final Income Taxes Withheld) – eFPS Filers under Group C.<b>Month of August 2024</b></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- COMPLETED TAB CONTENT --}}
                <div id="tab-completed-content" role="tabpanel" aria-labelledby="tab-completed" class="hidden overflow-x-auto px-10">
                    <p class="text-gray-500">
                       
                    </p>
                </div>
                  
            </div>

            <div class="p-6 col-span-2 bg-white border-gray-200 rounded-lg">
                <div class="flex items-center space-x-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 512 512"><path fill="#1e3a8a" d="M258.9 48C141.92 46.42 46.42 141.92 48 258.9c1.56 112.19 92.91 203.54 205.1 205.1c117 1.6 212.48-93.9 210.88-210.88C462.44 140.91 371.09 49.56 258.9 48M351 175.24l-82.24 186.52c-4.79 10.47-20.78 7-20.78-4.56V268a4 4 0 0 0-4-4H154.8c-11.52 0-15-15.87-4.57-20.67L336.76 161A10.73 10.73 0 0 1 351 175.24"/></svg>
                    <span class="font-bold text-2xl taxuri-color leading-tight">Quick Actions</span>
                </div>
                <div class="mb-4">
                    <button type="button" class="w-full border border-gray-200 text-zinc-600 hover:text-blue-900 hover:bg-slate-200 focus:ring-4 focus:outline-none font-medium rounded-lg px-5 py-2.5 text-center inline-flex items-center">
                        <div class="text-left">
                            <h1 class="font-bold text-md">File a New Tax Return</h1>
                            <p class="text-xs">Start the process of filing a new tax return for any category</p>
                        </div>
                        <span class="ml-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8m-4 4l4-4l-4-4"/></g>                           
                            </svg>
                        </span>
                    </button>
                </div>
        
                <div class="mb-4">
                    <a href="{{ route('transactions') }}">
                        <button type="button" class="w-full border border-gray-200 text-zinc-600 hover:text-blue-900 hover:bg-slate-200 focus:ring-4 focus:outline-none font-medium rounded-lg px-5 py-2.5 text-center inline-flex items-center">
                            <div class="text-left">
                                <h1 class="font-bold text-md">Add Transaction</h1>
                                <p class="text-xs">Quickly log a new financial transaction</p>
                            </div>
                            <span class="ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8m-4 4l4-4l-4-4"/></g>                           
                                </svg>
                            </span>
                        </button>
                    </a>
                </div>

                <div class="mb-4">
                    <a href="{{ route('predictive-analytics') }}">
                        <button type="button" class="w-full border border-gray-200 text-zinc-600 hover:text-blue-900 hover:bg-slate-200 focus:ring-4 focus:outline-none font-medium rounded-lg px-5 py-2.5 text-center inline-flex items-center">
                            <div class="text-left">
                                <h1 class="font-bold text-md">View Predictive Analytics</h1>
                                <p class="text-xs">Access insights to forecast future tax liabilities and revenue trends</p>
                            </div>
                            <span class="ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 stroke-current" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8m-4 4l4-4l-4-4"/></g>
                                </svg>
                            </span>
                        </button>
                    </a>
                </div>

                <div class="mb-4">
                    <a href="{{ route('org-setup') }}">
                        <button type="button" class="w-full border border-gray-200 text-zinc-600 hover:text-blue-900 hover:bg-slate-200 focus:ring-4 focus:outline-none font-medium rounded-lg px-5 py-2.5 text-center inline-flex items-center">
                            <div class="text-left">
                                <h1 class="font-bold text-md">Select New Organization</h1>
                                <p class="text-xs"> Switch between different organizations easily</p>
                            </div>
                            <span class="ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8m-4 4l4-4l-4-4"/></g>
                                </svg>
                            </span>
                        </button>
                    </a>
                </div>
            </div>
        </div>                
    </div>
</div>

<script>
    function activateTab(tabId) {
        document.querySelectorAll('[role="tabpanel"]').forEach(function(panel) {
            panel.classList.add('hidden');
        });

        document.querySelectorAll('button[role="tab"]').forEach(function(tab) {
            tab.classList.remove('active-tab-dashboard', 'font-bold'); // Remove active class
            tab.classList.add('text-zinc-500'); // Add default text color for inactive tab
            tab.setAttribute('aria-selected', 'false'); // Set aria-selected to false
        });

        document.getElementById(tabId + '-content').classList.remove('hidden');

        const activeTab = document.getElementById(tabId);
        activeTab.classList.add('active-tab-dashboard', 'font-bold'); // Add active class
        activeTab.classList.remove('text-zinc-500'); // Remove default text color for active tab
        activeTab.setAttribute('aria-selected', 'true'); // Set aria-selected to true
    }

    document.addEventListener('DOMContentLoaded', function() {
        activateTab('tab-today'); // Default tab on page load
    });

    document.querySelectorAll('[id^="accordion-button"]').forEach(button => {
        button.addEventListener('click', function() {
            // Get the accordion body
            var targetId = this.getAttribute('data-accordion-target');
            var accordionBody = document.querySelector(targetId);
            var isExpanded = this.getAttribute('aria-expanded') === 'true';

            // Toggle visibility of the accordion body
            if (isExpanded) {
                accordionBody.classList.add('hidden');  // Hide
                this.setAttribute('aria-expanded', 'false');  // Update aria-expanded
            } else {
                accordionBody.classList.remove('hidden');  // Show
                this.setAttribute('aria-expanded', 'true');  // Update aria-expanded
            }

            // Toggle the icon rotation for visual feedback
            var icon = this.querySelector('svg');
            icon.classList.toggle('rotate-180');
        });
    });
</script>
</x-app-layout>
