@php
$organizationId = session('organization_id');
$organization = \App\Models\OrgSetup::find($organizationId);
@endphp
<x-client-layout>

    <!-- Parent div -->
    <div class="py-6 px-36">
        <!-- Quick Stats Section with Two Containers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 ">
            <!-- First Container with Two Stats -->
            <div class="p-6 bg-white rounded-lg shadow-sm flex justify-around items-center">
                <div class="flex flex-row items-center text-left space-x-3">
                    <p class="text-4xl text-blue-900 font-extrabold flex items-center justify-center">{{$filedTaxReturnCount}}</p>
                    <div class="flex flex-col">
                        <h2 class="text-zinc-600 font-bold">Total Filed</h2>
                        <p class="text-gray-500 text-xs">Total number of tax returns successfully submitted</p>
                    </div>
                </div>
                <div class="flex flex-row items-center text-left space-x-3">
                    <p class="text-4xl text-blue-900 font-extrabold flex items-center justify-center">{{ $pendingTaxReturnCount }}</p>
                    <div class="flex flex-col">
                        <h2 class="text-zinc-600 font-bold">Unfiled Taxes</h2>
                        <p class="text-gray-500 text-xs">Total number of pending or overdue tax returns</p>
                    </div>
                </div>
            </div>

            <!-- Second Container with Two Stats -->
            <div class="p-6 bg-white rounded-lg shadow-sm flex justify-around items-center">
                <div class="flex flex-row items-center text-left space-x-3">
                    <p class="text-4xl text-blue-900 font-extrabold flex items-center justify-center">{{$totalSalesTransaction}}</p>
                    <div class="flex flex-col">
                        <h2 class="text-zinc-600 font-bold">Total Sales</h2>
                        <p class="text-gray-500 text-xs">Total number of sales-related transactions</p>
                    </div>
                </div>
                <div class="flex flex-row items-center text-left space-x-3">
                    <p class="text-4xl text-blue-900 font-extrabold flex items-center justify-center">{{$totalPurchaseTransaction}}</p>
                    <div class="flex flex-col">
                        <h2 class="text-zinc-600 font-bold">Total Purchases</h2>
                        <p class="text-gray-500 text-xs">Total number of purchase-related transactions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions and Tax Reminders Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tax Reminder Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-center space-x-2 mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 36 36"><path fill="#1e3a8a" d="M32.25 6h-4v3a2.2 2.2 0 1 1-4.4 0V6H12.2v3a2.2 2.2 0 0 1-4.4 0V6h-4A1.78 1.78 0 0 0 2 7.81v22.38A1.78 1.78 0 0 0 3.75 32h28.5A1.78 1.78 0 0 0 34 30.19V7.81A1.78 1.78 0 0 0 32.25 6M10 26H8v-2h2Zm0-5H8v-2h2Zm0-5H8v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Zm6 10h-2v-2h2Zm0-5h-2v-2h2Zm0-5h-2v-2h2Z" class="clr-i-solid clr-i-solid-path-1"/><path fill="#1e3a8a" d="M10 10a1 1 0 0 0 1-1V3a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1" class="clr-i-solid clr-i-solid-path-2"/><path fill="#1e3a8a" d="M26 10a1 1 0 0 0 1-1V3a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1" class="clr-i-solid clr-i-solid-path-3"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                    <span class="font-bold text-3xl taxuri-color leading-tight">Tax Reminder</span>
                </div>
                <p class="font-normal text-sm text-zinc-600">Stay updated with essential tax deadlines and obligations. Easily keep track of important
                    filling and payment dates to ensure seamless compliance with the BIR regulations.
                </p>
                <div class="my-3"><hr /></div>
                
                <div class="mt-4 flex space-x-4 bg-gray-100 rounded-full p-2" style="width: max-content;">
                    <button id="tab-today" onclick="showTab('today')" class="tab-btn text-blue-900 text-sm font-semibold py-2 px-4 rounded-full focus:outline-none">Today</button>
                    <button id="tab-upcoming" onclick="showTab('upcoming')" class="tab-btn text-blue-900 text-sm font-semibold py-2 px-4 rounded-full focus:outline-none">Upcoming</button>
                    <button id="tab-completed" onclick="showTab('completed')" class="tab-btn text-blue-900 text-sm font-semibold py-2 px-4 rounded-full focus:outline-none">Completed</button>
                </div>
                
                <!-- List of Reminders with Scrollable Sections -->
                <ul id="today" class="mt-4 space-y-2 overflow-y-auto max-h-64 tab-content">
                    @forelse ($todayTaxReturns as $taxReturn)
                        <li class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm font-semibold text-gray-800">{{ $taxReturn->title }}</span>
                            <span class="text-sm font-semibold text-blue-700">{{ $taxReturn->created_at->format('F d, Y') }}</span>
                            <a href="#" class="text-sm text-blue-600 hover:underline">View Details</a>
                        </li>
                    @empty
                        <div class="flex flex-col items-center p-8 text-gray-500">
                            <i class="fas fa-info-circle text-3xl mb-2"></i>
                            <p class="text-lg">No tax returns due today.</p>
                        </div>
                    @endforelse
                </ul>

                <ul id="upcoming" class="mt-4 space-y-2 overflow-y-auto max-h-64 tab-content hidden">
                    @forelse ($upcomingTaxReturns as $taxReturn)
                        <li class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm font-semibold text-gray-800">{{ $taxReturn->title }}</span>
                            <span class="text-sm font-semibold text-blue-700">{{ $taxReturn->created_at->format('F d, Y') }}</span>
                            <a href="#" class="text-sm text-blue-600 hover:underline">View Details</a>
                        </li>
                    @empty
                        <div class="flex flex-col items-center p-8 text-gray-500">
                            <i class="fas fa-info-circle text-3xl mb-2"></i>
                            <p class="text-lg">No upcoming tax returns.</p>
                        </div>
                    @endforelse
                </ul>

                <ul id="completed" class="mt-4 space-y-2 overflow-y-auto max-h-64 tab-content hidden">
                    @forelse ($completedTaxReturns as $taxReturn)
                        <li class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                            <span class="text-sm font-semibold text-gray-800">{{ $taxReturn->title }}</span>
                            <span class="text-sm font-semibold text-blue-700">{{ $taxReturn->created_at->format('F d, Y') }}</span>
                            <a href="#" class="text-sm text-blue-600 hover:underline">View Details</a>
                        </li>
                    @empty
                        <div class="flex flex-col items-center p-8 text-gray-500">
                            <i class="fas fa-info-circle text-3xl mb-2"></i>
                            <p class="text-lg">No completed tax returns.</p>
                        </div>
                    @endforelse
                </ul>
            </div>

            <!-- Quick Actions Section -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <div class="flex items-center space-x-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 512 512"><path fill="#1e3a8a" d="M258.9 48C141.92 46.42 46.42 141.92 48 258.9c1.56 112.19 92.91 203.54 205.1 205.1c117 1.6 212.48-93.9 210.88-210.88C462.44 140.91 371.09 49.56 258.9 48M351 175.24l-82.24 186.52c-4.79 10.47-20.78 7-20.78-4.56V268a4 4 0 0 0-4-4H154.8c-11.52 0-15-15.87-4.57-20.67L336.76 161A10.73 10.73 0 0 1 351 175.24"/></svg>
                    <span class="font-bold text-3xl taxuri-color leading-tight">Quick Actions</span>
                </div>
                <div class="mb-4">
                    <a href="{{ route('client.forms') }}">
                        <button type="button" class="w-full border border-gray-200 text-zinc-600 hover:text-blue-900 hover:bg-slate-200 focus:ring focus:ring-blue-900focus:outline-none font-medium rounded-lg px-5 py-2.5 text-center inline-flex items-center">
                            <div class="text-left">
                                <h1 class="font-bold text-md">View Generated Forms</h1>
                                <p class="text-xs">Access a list of generated forms for easy review and submission</p>
                            </div>
                            <span class="ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8m-4 4l4-4l-4-4"/></g>                           
                                </svg>
                            </span>
                        </button>
                    </a>
                </div>
                <div class="mb-4">
                    <a href="{{ route('client.income_statement') }}">
                        <button type="button" class="w-full border border-gray-200 text-zinc-600 hover:text-blue-900 hover:bg-slate-200 focus:ring focus:ring-blue-900 focus:outline-none font-medium rounded-lg px-5 py-2.5 text-center inline-flex items-center">
                            <div class="text-left">
                                <h1 class="font-bold text-md">View Income Statement</h1>
                                <p class="text-xs">Direct access to the latest income statement for quick financial insights</p>
                            </div>
                            <span class="ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8m-4 4l4-4l-4-4"/></g>                           
                                </svg>
                            </span>
                        </button>
                    </a>
                </div>
                <div class="mb-4">
                    <a href="{{ route('client.analytics') }}">
                        <button type="button" class="w-full border border-gray-200 text-zinc-600 hover:text-blue-900 hover:bg-slate-200 focus:ring focus:ring-blue-900 focus:outline-none font-medium rounded-lg px-5 py-2.5 text-center inline-flex items-center">
                            <div class="text-left">
                                <h1 class="font-bold text-md">View Predictive Analytics</h1>
                                <p class="text-xs">Access insights to forecast future tax liabilities and revenue trends</p>
                            </div>
                            <span class="ml-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M8 12h8m-4 4l4-4l-4-4"/></g>                           
                                </svg>
                            </span>
                        </button>
                    </a>
                </div>
                <div class="mb-4">
                    <a href="{{ route('client.profile') }}">
                        <button type="button" class="w-full border border-gray-200 text-zinc-600 hover:text-blue-900 hover:bg-slate-200 focus:ring focus:ring-blue-900 focus:outline-none font-medium rounded-lg px-5 py-2.5 text-center inline-flex items-center">
                            <div class="text-left">
                                <h1 class="font-bold text-md">Account Settings</h1>
                                <p class="text-xs">Access or update account details, such as the organization’s email</p>
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
</x-client-layout>

<script>
    function showTab(tabName) {
        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(button => button.classList.remove('bg-blue-900', 'text-white'));
        
        // Show the selected tab and add active styles to the button
        document.getElementById(tabName).classList.remove('hidden');
        document.getElementById(`tab-${tabName}`).classList.add('bg-blue-900', 'text-white');
    }

    // Initialize by setting the default tab
    document.addEventListener("DOMContentLoaded", function() {
        showTab('today');
    });
</script>