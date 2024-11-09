@php
$organizationId = session('organization_id');
$organization = \App\Models\OrgSetup::find($organizationId);
@endphp
<x-client-layout>

    <!-- Parent div -->
    <div class="py-6 px-16">
        <!-- Quick Stats Section with Two Containers -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 ">
            <!-- First Container with Two Stats -->
            <div class="p-6 bg-white rounded-lg shadow-md flex justify-around items-center">
                <div class="flex flex-row items-center text-left space-x-3">
                    <p class="text-3xl text-white font-extrabold bg-blue-900 rounded-full h-16 w-24 flex items-center justify-center">{{$filedTaxReturnCount}}</p>
                    <div class="flex flex-col">
                        <p class="font-semibold text-lg text-gray-900 mt-2">Total Filed</p>
                        <p class="text-sm text-gray-500">Total number of tax returns successfully submitted</p>
                    </div>
                </div>
                <div class="flex flex-row items-center text-left space-x-3">
                    <p class="text-3xl text-white font-extrabold bg-blue-900 rounded-full h-16 w-24 flex items-center justify-center">
                        {{ $pendingTaxReturnCount }}
                    </p>
                    <div class="flex flex-col">
                        <p class="font-semibold text-lg text-gray-900 mt-2">Unified Taxes</p>
                        <p class="text-sm text-gray-500">Total number of pending or overdue tax returns</p>
                    </div>
                </div>
            </div>

            <!-- Second Container with Two Stats -->
            <div class="p-6 bg-white rounded-lg shadow-md flex justify-around items-center">
                <div class="flex flex-row items-center text-left space-x-3">
                    <p class="text-3xl text-white font-extrabold bg-blue-900 rounded-full h-16 w-24 flex items-center justify-center">{{$totalSalesTransaction}}</p>
                    <div class="flex flex-col">
                        <p class="font-semibold text-lg text-gray-900 mt-2">Total Sales</p>
                        <p class="text-sm text-gray-500">Total number of sales-related transactions</p>
                    </div>
                </div>
                <div class="flex flex-row items-center text-left space-x-3">
                    <p class="text-3xl text-white font-extrabold bg-blue-900 rounded-full h-16 w-24 flex items-center justify-center">{{$totalPurchaseTransaction}}</p>
                    <div class="flex flex-col">
                        <p class="font-semibold text-lg text-gray-900 mt-2">Total Purchases</p>
                        <p class="text-sm text-gray-500">Total number of purchase-related transactions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions and Tax Reminders Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tax Reminder Section -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="font-bold text-3xl text-blue-900 flex items-center">
                    <i class="fas fa-calendar-alt text-blue-900 mr-2"></i> Tax Reminder
                </h2>
                <p class="text-sm text-gray-500 mt-2">  
                    Stay updated with essential tax deadlines and obligations. Easily keep track of important filing and payment dates to ensure seamless compliance with the BIR regulations.
                </p>
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
            <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                <h2 class="font-bold text-xl text-blue-900 flex items-center">
                    <span class="bg-blue-900 text-white rounded-full h-8 w-8 flex items-center justify-center mr-2">
                        <i class="fas fa-location-arrow"></i>
                    </span>
                    Quick Actions
                </h2>
                <ul class="mt-4 space-y-4">
                    <li class="flex justify-between items-center p-4 border rounded-lg hover:bg-gray-100 transition">
                        <div class="flex flex-col">
                            <a href="{{ route('client.forms') }}" class="text-sm font-semibold text-gray-800">View Generated Forms
                            <p class="text-xs text-gray-500">Access a list of generated forms for easy review and submission</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li class="flex justify-between items-center p-4 border rounded-lg hover:bg-gray-100 transition">
                        <div class="flex flex-col">
                            <a href="{{ route('client.income_statement') }}" class="text-sm font-semibold text-gray-800">View Income Statement
                            <p class="text-xs text-gray-500">Direct access to the latest income statement for quick financial insights</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li class="flex justify-between items-center p-4 border rounded-lg hover:bg-gray-100 transition">
                        <div class="flex flex-col">
                            <a href="{{ route('client.analytics') }}" class="text-sm font-semibold text-gray-800">View Predictive Analytics
                            <p class="text-xs text-gray-500">Access insights to forecast future tax liabilities and revenue trends</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                    <li class="flex justify-between items-center p-4 border rounded-lg hover:bg-gray-100 transition">
                        <div class="flex flex-col">
                            <a href="{{ route('client.settings') }}" class="text-sm font-semibold text-gray-800">Account Settings
                            <p class="text-xs text-gray-500">Access or update account details, such as the organization’s email</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                        </a>
                    </li>
                </ul>
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