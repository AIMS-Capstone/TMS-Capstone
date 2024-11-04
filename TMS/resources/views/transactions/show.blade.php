<x-app-layout>
    @if ($transaction->transaction_type === 'Sales')
        <x-transaction-form-section>
            @php
                $type = request()->query('type', 'sales');
            @endphp

            <!-- Redirection Link -->
            <x-slot:redirection>
                <a href="/transactions" class="flex items-center space-x-2 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                        <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M16 12H8m4-4l-4 4l4 4"/>
                        </g>
                    </svg>
                    <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go back</span>
                </a>
            </x-slot:redirection>

            <!-- Form Header Title -->
            <x-slot:description>
                <h1 class="text-3xl font-bold text-blue-900">Transaction Details</h1>
            </x-slot:description>

            <!-- Sales Options Dropdown -->
            <x-slot:options>
                <div class="relative inline-block text-left mt-4 mb-4">
                    <button id="salesOptionsButton" onclick="toggleSalesOptions(event)" class="inline-flex justify-between w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Sales Options
                        <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.707l3.71-3.5a.75.75 0 111.06 1.06l-4 3.75a.75.75 0 01-1.06 0l-4-3.75a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Sales Options Menu -->
                    <div id="salesOptions" class="hidden absolute right-0 z-10 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="salesOptionsButton">
                            <a href="/transactions/add-collection" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add Collection</a>
                            <a href="/transactions/edit-sales" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Sales</a>
                            <a href="/transactions/mark-as-posted" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mark as Posted</a>
                        </div>
                    </div>
                </div>
            </x-slot:options>

            <!-- Transaction Details Section -->
            <x-slot:form>
                <div class="grid grid-cols-5 gap-6 mt-4">
                    <!-- Customer Display -->
                    <div class="mt-5 mb-8 ml-10">
                        <label class="block font-bold text-sm text-blue-900">Customer</label>
                        <div class="mt-4">
                            <input type="text" class="form-control w-full" value="{{ $transaction->contactDetails->bus_name ?? 'N/A' }}" readonly />
                        </div>
                    </div>

                    <!-- Date Display -->
                    <div class="mt-5 mb-8">
                        <x-transaction-label for="date" :value="__('Date')" />
                        <input id="date" type="text" class="mt-1 block w-full" value="{{ $transaction->date }}" readonly />
                    </div>

                    <!-- Invoice Number Display -->
                    <div class="mt-5 mb-8">
                        <x-transaction-label for="inv_number" :value="__('Invoice Number')" />
                        <input id="inv_number" type="text" class="mt-1 block w-full" value="{{ $transaction->inv_number }}" readonly />
                    </div>

                    <!-- Reference Display -->
                    <div class="mt-5 mb-8">
                        <x-transaction-label for="reference" :value="__('Reference')" />
                        <input id="reference" type="text" class="mt-1 block w-full" value="{{ $transaction->reference }}" readonly />
                    </div>

                    <!-- Total Amount Display -->
                    <div class="col-span-1 bg-blue-50 p-4 rounded-tr-sm">
                        <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                        <input id="total_amount" type="text" class="font-bold text-blue-900 mt-1 block w-full" value="{{ number_format($transaction->total_amount, 2) }}" readonly />
                    </div>
                </div>

                <!-- Table Section for Tax Rows -->
                <div class="mt-0">
                    <table class="table-auto w-full text-left text-sm text-neutral-600">
                        <thead class="bg-neutral-100 text-neutral-900">
                            <tr>
                                <th class="px-4 py-2">Description</th>
                                <th class="px-4 py-2">Tax Type</th>
                                <th class="px-4 py-2">ATC</th>
                                <th class="px-4 py-2">COA</th>
                                <th class="px-4 py-2">Amount (VAT Inclusive)</th>
                                <th class="px-4 py-2">Tax Amount</th>
                                <th class="px-4 py-2">Net Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taxRows as $row)
                                <tr>
                                    <td class="border px-4 py-2">{{ $row->description }}</td>
                                    <td class="border px-4 py-2">{{ $row->taxType->tax_type }} ({{ $row->taxType->VAT }}%)</td>
                                    <td class="border px-4 py-2">{{ $row->atc->tax_code }} ({{ $row->atc->tax_rate }}%)</td>
                                    <td class="border px-4 py-2">{{ $row->coa }}</td>
                                    <td class="border px-4 py-2">{{ number_format($row->amount, 2) }}</td>
                                    <td class="border px-4 py-2">{{ number_format($row->tax_amount, 2) }}</td>
                                    <td class="border px-4 py-2">{{ number_format($row->net_amount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <x-slot name="after">
                    <div class="mt-4">
                        <div class="flex justify-end">
                            <table class="table-auto">
                                <tbody>
                                    @if($transaction->vatable_sales > 0)
                                    <tr>
                                        <td class="px-4 py-2 taxuri-text">VATable Sales</td>
                                        <td class="px-4 py-2 text-right taxuri-text">{{ number_format($transaction->vatable_sales, 2) }}</td>
                                    </tr>
                                    @endif

                                    @if($transaction->vat_amount > 0)
                                    <tr>
                                        <td class="px-4 py-2 taxuri-text">VAT Amount (12%)</td>
                                        <td class="px-4 py-2 text-right taxuri-text">{{ number_format($transaction->vat_amount, 2) }}</td>
                                    </tr>
                                    @endif

                                    @if($transaction->non_vatable_sales > 0)
                                    <tr>
                                        <td class="px-4 py-2 taxuri-text">Non-VATable Sales</td>
                                        <td class="px-4 py-2 text-right">{{ number_format($transaction->non_vatable_sales, 2) }}</td>
                                    </tr>
                                    @endif

                                    @if($transaction->total_amount > 0)
                                    <tr>
                                        <td class="px-4 py-2 font-bold text-blue-900">Total Amount Due</td>
                                        <td class="px-4 py-2 text-right font-bold text-blue-900">{{ number_format($transaction->total_amount, 2) }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-slot>
            </x-slot:form>
        </x-transaction-form-section>
        @elseif ($transaction->transaction_type === 'Purchase')
        <x-transaction-form-section>
            @php
                $type = request()->query('type', 'purchase'); // Default to 'purchase' if 'type' is not set
            @endphp

            <!-- Redirection Link -->
            <x-slot:redirection>
                <a href="/transactions" class="flex items-center space-x-2 text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                        <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M16 12H8m4-4l-4 4l4 4"/>
                        </g>
                    </svg>
                    <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go back</span>
                </a>
            </x-slot:redirection>

            <!-- Form Header Title -->
            <x-slot:description>
                <h1 class="text-3xl font-bold text-blue-900">View Purchase Details</h1>
            </x-slot:description>

            <x-slot:options>
    <div class="relative inline-block text-left mt-4 mb-4">
        <button id="purchaseOptionsButton" type="button" onclick="togglePurchaseOptions(event)" class="inline-flex justify-between w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Purchase Options
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.707l3.71-3.5a.75.75 0 111.06 1.06l-4 3.75a.75.75 0 01-1.06 0l-4-3.75a.75.75 0 010-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        <!-- Purchase Options Menu -->
        <div id="purchaseOptions" class="hidden absolute right-0 z-10 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="purchaseOptionsButton">
                <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Add a Payment</a>
                <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Purchase</a>
                <a href="" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mark as Posted</a>
            </div>
        </div>
    </div>
</x-slot:options>
            <!-- Form Fields (Vendor, Date, etc.) -->
            <x-slot:form>
                <div class="grid grid-cols-4 gap-6">
                    <!-- Vendor Display -->
                    <div class="mt-4 ml-10">
                        <label class="block font-bold text-sm taxuri-color">Vendor</label>
                        <div class="mt-1 text-zinc-700">{{ $transaction->contactDetails->bus_name ?? 'N/A' }}</div>
                    </div>

                    <!-- Date Display -->
                    <div class="mt-5 mb-8">
                        <x-transaction-label for="date" :value="__('Date')" />
                        <div class="mt-1 text-zinc-700">{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</div>
                    </div>

                    <!-- Reference Display -->
                    <div class="mt-5 mb-8">
                        <x-transaction-label for="reference" :value="__('Reference')" />
                        <div class="mt-1 text-zinc-700">{{ $transaction->reference ?? 'N/A' }}</div>
                    </div>

                    <!-- Total Amount Display -->
                    <div class="col-span-1 bg-blue-50 p-4 rounded-tr-sm">
                        <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                        <div class="mt-1 text-zinc-700">{{ number_format($transaction->total_amount, 2) }}</div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="mt-0">
                    <table class="table-auto w-full text-left text-sm text-neutral-600">
                        <thead class="bg-gray-100 text-neutral-900">
                            <tr>
                                <th class="px-4 py-2">Description</th>
                                <th class="px-4 py-2">Tax Type</th>
                                <th class="px-4 py-2">ATC</th>
                                <th class="px-4 py-2">COA</th>
                                <th class="px-4 py-2">Amount (VAT Inclusive)</th>
                                <th class="px-4 py-2">Tax Amount</th>
                                <th class="px-4 py-2">Net Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taxRows as $row)
                                <tr>
                                    <td class="px-4 py-2">{{ $row['description'] }}</td>
                                    <td class="px-4 py-2">{{ $row->taxType->tax_type }} ({{ $row->taxType->VAT }}%)</td>
                                    <td class="px-4 py-2">{{ $row->atc->tax_code }} ({{ $row->atc->tax_rate }}%)</td>
                                    <td class="px-4 py-2">{{ $row['coa'] }}</td>
                                    <td class="px-4 py-2">{{ number_format($row['amount'], 2) }}</td>
                                    <td class="px-4 py-2">{{ number_format($row['tax_amount'], 2) }}</td>
                                    <td class="px-4 py-2">{{ number_format($row['net_amount'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <x-slot name="after">
                    <div class="mt-4">
                        <div class="flex justify-end">
                            <table class="table-auto">
                                <tbody>
                                    @if($transaction->vatable_purchase > 0)
                                        <tr>
                                            <td class="px-4 py-2 taxuri-text">VATable Purchase</td>
                                            <td class="px-4 py-2 text-right taxuri-text">{{ number_format($transaction->vatable_purchase, 2) }}</td>
                                        </tr>
                                    @endif

                                    @if($transaction->non_vatable_purchase > 0)
                                        <tr>
                                            <td class="px-4 py-2 taxuri-text">Non-VATable Purchase</td>
                                            <td class="px-4 py-2 text-right taxuri-text">{{ number_format($transaction->non_vatable_purchase, 2) }}</td>
                                        </tr>
                                    @endif

                                    @if($transaction->vat_amount > 0)
                                        <tr>
                                            <td class="px-4 py-2 taxuri-text">VAT Amount</td>
                                            <td class="px-4 py-2 text-right taxuri-text">{{ number_format($transaction->vat_amount, 2) }}</td>
                                        </tr>
                                    @endif

                                    @if($transaction->taxRows->isNotEmpty())
    @foreach($transaction->taxRows as $row)
        @if($row->atc) <!-- Check if ATC exists -->
            <tr>
                <td class="px-4 py-2 taxuri-text">{{ $row->atc->tax_code }} ({{ number_format($row->atc->tax_rate, 2) }}%)</td>
                <td class="px-4 py-2 text-right">{{ number_format($row->atc_amount, 2) }}</td>
            </tr>
        @endif
    @endforeach
@endif

                                    @if($transaction->total_amount > 0)
                                        <tr>
                                            <td class="px-4 py-2 font-bold taxuri-color">Total Amount Due</td>
                                            <td class="px-4 py-2 text-right font-bold taxuri-color">{{ number_format($transaction->total_amount, 2) }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </x-slot>
            </x-slot:form>

         
        </x-transaction-form-section>
        @elseif ($transaction->transaction_type === 'Journal')
<x-transaction-form-section>
    @php
        $type = 'journal'; // Set type to 'journal' for journal transactions
    @endphp
    <!-- Redirection Link -->
    <x-slot:redirection>
        <a href="/transactions" class="flex items-center space-x-2 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g></svg>
            <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go back</span>
        </a>
    </x-slot:redirection>

    <!-- Form Header Title -->
    <x-slot:description>
        <h1 class="text-3xl font-bold text-blue-900">View Journal Entry</h1>
    </x-slot:description>
    <x-slot:options>
        <div class="relative inline-block text-left mt-4 mb-4">
            <button id="journalOptionsButton" onclick="toggleJournalOptions(event)" class="inline-flex justify-between w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Journal Options
                <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.707l3.71-3.5a.75.75 0 111.06 1.06l-4 3.75a.75.75 0 01-1.06 0l-4-3.75a.75.75 0 010-1.06z" clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Journal Options Menu -->
            <div id="journalOptions" class="hidden absolute right-0 z-10 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="journalOptionsButton">
                    <a href="/transactions/edit-journal/{{ $transaction->id }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Journal</a>
                </div>
            </div>
        </div>
    </x-slot:options>

    <!-- Form Fields (Date, Reference, etc.) -->
    <x-slot:form>
        <!-- Form Header with Total Amount -->
        <div class="grid grid-cols-4 gap-6 mb-4">
            <!-- Date Field -->
            <div class="mt-5 px-6">
                <x-transaction-label for="date" :value="__('Date')" />
                <div class="mt-1 text-zinc-700">{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</div>
            </div>

            <!-- Reference Field -->
            <div class="mt-5">
                <x-transaction-label for="reference" :value="__('Reference')" />
                <div class="mt-1 text-zinc-700">{{ $transaction->reference }}</div>
            </div>

            <!-- Total Amount Field -->
            <div class="col-span-2 bg-blue-50 p-4 rounded-tr-sm">
                <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                <div class="mt-1 text-zinc-700">{{ number_format($transaction->total_amount, 2) }}</div>
            </div>
        </div>

        <!-- Table Section (Journal Entries) -->
        <div class="mt-0">
            <table class="table-auto w-full text-left text-sm text-neutral-600">
                <thead class="bg-neutral-100 text-neutral-900">
                    <tr>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 pr-5 py-2">Accounts</th>
                        <th class="px-4 py-2">Debit</th>
                        <th class="px-4 py-2">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaction->taxRows as $row)
                        <tr>
                            <td class="px-4 py-2">{{ $row['description'] }}</td>
                            <td class="px-4 py-2">{{ $row['account'] }}</td>
                            <td class="px-4 py-2">{{ number_format($row['debit'], 2) }}</td>
                            <td class="px-4 py-2">{{ number_format($row['credit'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total Amount Due Section -->
        <x-slot name="after">
            <div class="flex justify-between mt-4">
                <!-- Total Amount Due Section -->
                <div class="w-96">
                    <table class="w-96 text-left">
                        <tbody>
                            <tr>
                                <td class="border-t px-4 py-2 font-bold taxuri-color">Total Amount Due:</td>
                                <td class="border-t px-4 py-2">{{ number_format($transaction->total_amount_credit, 2) }}</td>
                                <td class="border-t px-4 py-2">{{ number_format($transaction->total_amount_debit, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>
    </x-slot:form>

    <!-- Save Button Slot (hidden or disabled for view mode) -->
    <x-slot:actions>
        <div class="flex justify-end mt-4 mb-32">
            <x-button type="button" class="ml-4 text-gray-500 bg-gray-200 cursor-not-allowed" disabled>
                {{ __('Save Journal Entry') }}
            </x-button>
        </div>
    </x-slot:actions>
</x-transaction-form-section>

    @endif
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Toggle Sales Options dropdown
        function toggleSalesOptions(event) {
            event.preventDefault(); // Prevent default action to avoid page refresh
            const options = document.getElementById('salesOptions');
            if (options) options.classList.toggle('hidden');
        }

        // Toggle Purchase Options dropdown
        function togglePurchaseOptions(event) {
            event.preventDefault();
            const dropdown = document.getElementById('purchaseOptions');
            if (dropdown) dropdown.classList.toggle('hidden');
        }

        // Toggle Journal Options dropdown
        function toggleJournalOptions(event) {
            event.preventDefault();
            const journalOptions = document.getElementById('journalOptions');
            if (journalOptions) journalOptions.classList.toggle('hidden');
        }

        // Handle clicks outside of dropdowns to close them
        document.addEventListener('click', function (event) {
            const salesButton = document.getElementById('salesOptionsButton');
            const salesOptions = document.getElementById('salesOptions');
            const purchaseButton = document.getElementById('purchaseOptionsButton');
            const purchaseOptions = document.getElementById('purchaseOptions');
            const journalButton = document.getElementById('journalOptionsButton');
            const journalOptions = document.getElementById('journalOptions');
            
            // Close Sales Options if click is outside
            if (salesOptions && !salesOptions.classList.contains('hidden')) {
                if (!salesButton.contains(event.target) && !salesOptions.contains(event.target)) {
                    salesOptions.classList.add('hidden');
                }
            }

            // Close Purchase Options if click is outside
            if (purchaseOptions && !purchaseOptions.classList.contains('hidden')) {
                if (!purchaseButton.contains(event.target) && !purchaseOptions.contains(event.target)) {
                    purchaseOptions.classList.add('hidden');
                }
            }

            // Close Journal Options if click is outside
            if (journalOptions && !journalOptions.classList.contains('hidden')) {
                if (!journalButton.contains(event.target) && !journalOptions.contains(event.target)) {
                    journalOptions.classList.add('hidden');
                }
            }
        });

        // Add event listeners to toggle buttons
        const salesOptionsButton = document.getElementById('salesOptionsButton');
        if (salesOptionsButton) salesOptionsButton.onclick = toggleSalesOptions;

        const purchaseOptionsButton = document.getElementById('purchaseOptionsButton');
        if (purchaseOptionsButton) purchaseOptionsButton.onclick = togglePurchaseOptions;

        const journalOptionsButton = document.getElementById('journalOptionsButton');
        if (journalOptionsButton) journalOptionsButton.onclick = toggleJournalOptions;
    });
</script>
