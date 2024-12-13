<x-app-layout>
    @if ($transaction->transaction_type === 'Sales')
        <x-transaction-form-section>
            @php
                $type = request()->query('type', 'sales');
            @endphp

            <!-- Redirection Link -->
            <x-slot:redirection>
                <a href="/transactions" class="flex items-center space-x-2">
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
                    <h1 class="text-3xl font-bold text-blue-900">Sales: Inv {{ $transaction->inv_number }}</h1>
                    
            </x-slot:description>
            
            <!-- Sales Options Dropdown -->
            <x-slot:options>
                <div class="relative inline-block text-left mt-4 mb-4">
                    <button id="salesOptionsButton" onclick="toggleSalesOptions(event)" class="inline-flex justify-between w-full rounded-md border border-blue-900 shadow-sm px-4 py-2 bg-blue-900 text-sm tracking-wide font-bold text-white hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-800">
                        Sales Options
                        <svg class="-mr-1 ml-2 h-5 w-5 -rotate-90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.707l3.71-3.5a.75.75 0 111.06 1.06l-4 3.75a.75.75 0 01-1.06 0l-4-3.75a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Sales Options Menu -->
                    <div id="salesOptions" class="hidden absolute right-0 z-10 mt-2 w-44 rounded-md shadow-lg bg-white ring-opacity-5">
                        <div class="py-2 px-4" role="menu" aria-orientation="vertical" aria-labelledby="salesOptionsButton">
                            <div x-data="{
                                showPaymentModal: false,
                                transactionTotalAmount: '{{ $transaction->total_amount }}',  // Pass the total amount from backend to Alpine.js
                                paymentDate: '',
                                referenceNumber: '',
                                bankAccount: '',
                                markAsPaid() {
                                    // Make an AJAX request or send form data to the backend to mark as paid
                                    fetch('/transactions/mark-as-paid/{{ $transaction->id }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        },
                                        body: JSON.stringify({
                                            payment_date: this.paymentDate,
                                            reference_number: this.referenceNumber,
                                            bank_account: this.bankAccount,
                                            total_amount_paid: this.transactionTotalAmount  // Send total_amount_paid automatically
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.successPayment) {
                                            // Show success modal
                                            this.showPaymentModal = false;
                                            this.showSuccessPaymentModal = true;
                                            // Hide modal after a timeout (5 seconds)
                                            setTimeout(() => { this.showSuccessPaymentModal = false }, 5000);
                                        } else {
                                        
                                        }
                                    });
                                }
                            }">
         
                            <!-- Add a Payment Link (Trigger the Modal) -->
                            <a @click.prevent="showPaymentModal = true" class="block px-4 py-2 text-sm text-zinc-700 hover-dropdown">
                                Add a Payment
                            </a>
                        
                            <!-- Payment Modal -->
                            <div x-show="showPaymentModal" x-transition @click.away="showPaymentModal = false" 
                                class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50">
                        
                                <!-- Modal Content -->
                                <div class="bg-white rounded-lg shadow-lg w-full max-w-xl mx-auto h-auto z-10 overflow-hidden">
                                    <div class="relative flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto">
                                        <h1 class="text-lg font-bold text-white">Mark as Paid</h1>
                                        <button 
                                            @click="showPaymentModal = false"
                                            class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200 z-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                                                      d="M8 8L16 16M8 16L16 8" 
                                                      stroke="#1e3a8a" 
                                                      class="transition duration-200 hover:stroke-gray-600" />
                                            </svg>
                                        </button>
                                    </div>
                        
                                    <!-- Modal Body -->
                                    <div class="p-10 grid grid-cols-2 space-x-6 gap-8">
                        
                                        <!-- Left Side -->
                                        <div>
                                            <p class="text-sm font-bold text-zinc-600">
                                                You are about to pay: <span class="font-bold text-blue-900 text-xl">₱<span x-text="transactionTotalAmount"></span></span>
                                            </p>
                                            <p class="text-xs text-zinc-600 mt-4">
                                                Please enter the required fields and mark this transaction as Paid. Make sure that you are entering correct information to match your Sales/Purchases transaction. 
                                                You may also do a partial payment by entering a lower amount.
                                            </p>
                                        </div>
                        
                                        <!-- Right Side: Payment Form -->
                                        <div>
                                            <div class="mb-4">
                                                <label for="payment-date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                                                <input type="date" id="payment-date" x-model="paymentDate" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required />
                                            </div>
                                            <div class="mb-4">
                                                <label for="reference-number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                                                <input type="text" id="reference-number" x-model="referenceNumber" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required />
                                            </div>
                        
                                            <!-- Hidden Total Amount Paid -->
                                            <input type="hidden" id="total-amount-paid" :value="transactionTotalAmount" name="total_amount_paid" />
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-span-2 flex justify-end mt-6">
                                            <button @click="markAsPaid" class="px-8 py-2 bg-blue-900 text-white rounded-lg text-sm font-semibold hover:bg-blue-950">
                                                Apply Payment
                                            </button>
                                            {{-- <button @click="showPaymentModal = false" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-400">
                                                Cancel
                                            </button> --}}
                                        </div>
                                    </div>
                        
                                    
                                    </div>
                                </div>
                            </div>

                            {{-- other options --}}
                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="block px-4 py-2 text-sm text-zinc-700 hover-dropdown">Edit Sales</a>
                            <a href="{{route('transactions.mark', $transaction->id)}}" class="block px-4 py-2 text-sm text-zinc-700 hover-dropdown">Mark as Posted</a>
                        </div>
                    </div>
                </div>
            </x-slot:options>

            <!-- Transaction Details Section -->
            <x-slot:form>
                <div class="grid grid-cols-5 gap-6">
                    <!-- Customer Display -->
                    <div class="mt-5 ml-10">
                        <label class="block font-bold text-sm mb-3 text-blue-900">Customer</label>
                        <div class="mt-1 text-zinc-700">{{ $transaction->contactDetails->bus_name ?? 'N/A' }}
                            <span class="text-xs block">{{ $transaction->contactDetails->contact_tin ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <!-- Date Display -->
                    <div class="mt-5 mb-8">
                        <x-transaction-label for="date" :value="__('Invoice Date')" />
                        <div class="mt-1 text-zinc-700">{{ \Carbon\Carbon::parse($transaction->date)->format('F j, Y') }}</div>
                    </div>
                    <!-- Invoice Number Display -->
                    <div class="mt-5 mb-8">
                        <x-transaction-label for="inv_number" :value="__('Invoice Number')" />
                        <div class="mt-1 text-zinc-700">{{ $transaction->inv_number }}</div>
                    </div>
                    <!-- Reference Display -->
                    <div class="mt-5 mb-8">
                        <x-transaction-label for="reference" :value="__('Reference')" />
                        <div class="mt-1 text-zinc-700">{{ $transaction->reference }}</div>
                    </div>
                    <!-- Total Amount Display -->
                    <div class="col-span-1 bg-blue-50 p-4 rounded-tr-sm">
                        <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                        <div class="mt-1 text-blue-900 font-extrabold">{{ number_format($transaction->total_amount, 2) }}</div>
                    </div>
                </div>

                <!-- Table Section for Tax Rows -->
                <div class="mt-0">
                    <table class="table-auto w-full text-left text-sm border-neutral-300 text-neutral-600">
                        <thead class="bg-neutral-100 border-t-neutral-300 text-neutral-600">
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
                                    <td class="border px-4 py-2">
                                        @if ($row->atc)
                                            {{ $row->atc->tax_code }} ({{ $row->atc->tax_rate }}%)
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    
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
                                    @if($transaction->taxRows->isNotEmpty())
                                    @foreach($transaction->taxRows as $row)
                                        @if($row->atc && $row->atc_amount > 0) <!-- Check if ATC exists and atc_amount is greater than 0 -->
                                            <tr>
                                                <td class="px-4 py-2 taxuri-text">{{ $row->atc->tax_code }} ({{ number_format($row->atc->tax_rate, 2) }}%)</td>
                                                <td class="px-4 py-2 text-right">{{ number_format($row->atc_amount, 2) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                
                                    @if($transaction->total_amount > 0)
                                    <tr>
                                        <td colspan="2">
                                            <hr class="border-neutral-300">
                                        </td>
                                    </tr>
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

        {{-- Purchase --}}
        @elseif ($transaction->transaction_type === 'Purchase')
        <x-transaction-form-section>
            @php
                $type = request()->query('type', 'purchase'); // Default to 'purchase' if 'type' is not set
            @endphp
            <!-- Redirection Link -->
            <x-slot:redirection>
                <a href="/transactions" class="flex items-center space-x-2">
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
                <h1 class="text-3xl font-bold text-blue-900">Purchase: {{ $transaction->reference }} </h1>
            </x-slot:description>

            <x-slot:options>
                <div class="relative inline-block text-left mt-4 mb-4">
                    <button id="purchaseOptionsButton" type="button" onclick="togglePurchaseOptions(event)" class="inline-flex justify-between w-full rounded-md border border-blue-900 shadow-sm px-4 py-2 bg-blue-900 text-sm tracking-wide font-bold text-white hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-800">
                        Purchase Options
                        <svg class="-mr-1 ml-2 h-5 w-5 -rotate-90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.707l3.71-3.5a.75.75 0 111.06 1.06l-4 3.75a.75.75 0 01-1.06 0l-4-3.75a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Purchase Options Menu -->
                    <div id="purchaseOptions" class="hidden absolute right-0 z-10 mt-2 w-44 rounded-md shadow-lg bg-white ring-opacity-5">
                        <div class="py-2 px-4" role="menu" aria-orientation="vertical" aria-labelledby="purchaseOptionsButton">
                           <div x-data="{
                                showPaymentModal: false,
                                transactionTotalAmount: '{{ $transaction->total_amount }}',  // Pass the total amount from backend to Alpine.js
                                paymentDate: '',
                                referenceNumber: '',
                                bankAccount: '',
                                markAsPaid() {
                                    // Make an AJAX request or send form data to the backend to mark as paid
                                    fetch('/transactions/mark-as-paid/{{ $transaction->id }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        },
                                        body: JSON.stringify({
                                            payment_date: this.paymentDate,
                                            reference_number: this.referenceNumber,
                                            bank_account: this.bankAccount,
                                            total_amount_paid: this.transactionTotalAmount  // Send total_amount_paid automatically
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                    if (data.successPayment) {
                                                // Show success modal
                                                this.showPaymentModal = false;
                                                this.showSuccessPaymentModal = true;
                                                // Hide modal after a timeout (5 seconds)
                                                setTimeout(() => { this.showSuccessPaymentModal = false }, 5000);
                                        } else {
                                        
                                        }
                                    });
                                }
                            }">
    
    <!-- Add a Payment Link (Trigger the Modal) -->
    <a @click.prevent="showPaymentModal = true" class="block w-full px-4 py-2 text-sm text-zinc-700 hover-dropdown">
        Add a Payment
    </a>

    <!-- Payment Modal -->
    <div x-show="showPaymentModal" x-transition @click.away="showPaymentModal = false" 
         class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50">

        <!-- Modal Content -->
        <div class="bg-white rounded-lg shadow-lg w-full max-w-xl mx-auto h-auto z-10 overflow-hidden">
            <div class="relative flex bg-blue-900 justify-center rounded-t-lg items-center p-3 border-b border-opacity-80 mx-auto">
                <h1 class="text-lg font-bold text-white">Mark as Paid</h1>
                <button 
                    @click="showPaymentModal = false"
                    class="absolute right-3 top-4 text-sm text-white hover:text-zinc-200 z-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <circle cx="12" cy="12" r="10" fill="white" class="transition duration-200 hover:fill-gray-300" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                              d="M8 8L16 16M8 16L16 8" 
                              stroke="#1e3a8a" 
                              class="transition duration-200 hover:stroke-gray-600" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-10 grid grid-cols-2 space-x-6 gap-8">

                <!-- Left Side -->
                <div>
                    <p class="text-sm font-bold text-zinc-600">
                        You are about to pay: <span class="font-bold text-blue-900 text-xl">₱<span x-text="transactionTotalAmount"></span></span>
                    </p>
                    <p class="text-xs text-zinc-600 mt-4">
                        Please enter the required fields and mark this transaction as Paid. Make sure that you are entering correct information to match your Sales/Purchases transaction. 
                        You may also do a partial payment by entering a lower amount.
                    </p>
                </div>

                <!-- Right Side: Payment Form -->
                <div>
                    <div class="mb-4">
                        <label for="payment-date" class="block text-sm font-medium text-gray-700">Payment Date</label>
                        <input type="date" id="payment-date" x-model="paymentDate" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required />
                    </div>
                    <div class="mb-4">
                        <label for="reference-number" class="block text-sm font-medium text-gray-700">Reference Number</label>
                        <input type="text" id="reference-number" x-model="referenceNumber" class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" required />
                    </div>

                    <!-- Hidden Total Amount Paid -->
                    <input type="hidden" id="total-amount-paid" :value="transactionTotalAmount" name="total_amount_paid" />
                </div>

                <!-- Action Buttons -->
                <div class="col-span-2 flex justify-end mt-6">
                    <button @click="markAsPaid" class="px-8 py-2 bg-blue-900 text-white rounded-lg text-sm font-semibold hover:bg-blue-950">
                        Apply Payment
                    </button>
                    {{-- <button @click="showPaymentModal = false" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg text-sm hover:bg-gray-400">
                        Cancel
                    </button> --}}
                </div>
            </div>
        </div>
    </div>
</div>

                         
                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="block px-4 py-2 text-sm text-zinc-700 hover-dropdown">Edit Purchase</a>
                            <a href="{{route('transactions.mark', $transaction->id)}}" class="block px-4 py-2 text-sm text-zinc-700 hover-dropdown">Mark as Posted</a>
                        </div>
                    </div>
                </div>
            </x-slot:options>
            <!-- Form Fields (Vendor, Date, etc.) -->
            <x-slot:form>
                <div class="grid grid-cols-4 gap-6">
                    <!-- Vendor Display -->
                    <div class="mt-5 ml-10">
                        <label class="block font-bold text-sm mb-3 taxuri-color">Vendor</label>
                        <div class="mt-1 text-zinc-700">{{ $transaction->contactDetails->bus_name ?? 'N/A' }}
                            <span class="text-xs block">{{ $transaction->contactDetails->contact_tin ?? 'N/A' }}</span>
                        </div>
                    </div>
                    <!-- Date Display -->
                    <div class="mt-5 mb-8">
                        <x-transaction-label for="date" :value="__('Invoice Date')" />
                        <div class="mt-1 text-zinc-700">{{ \Carbon\Carbon::parse($transaction->date)->format('F j, Y') }}</div>
                    </div>
                    <!-- Reference Display -->
                    <div class="mt-5 mb-8">
                        <x-transaction-label for="reference" :value="__('Reference')" />
                        <div class="mt-1 text-zinc-700">{{ $transaction->reference ?? 'N/A' }}</div>
                    </div>
                    <!-- Total Amount Display -->
                    <div class="col-span-1 bg-blue-50 p-4 rounded-tr-sm">
                        <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                        <div class="mt-1 text-blue-900 font-extrabold">{{ number_format($transaction->total_amount, 2) }}</div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="mt-0">
                    <table class="table-auto w-full text-left text-sm border-neutral-300 text-neutral-600">
                        <thead class="bg-gray-100 border-t-neutral-300 text-neutral-600">
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
                                    <td class="border px-4 py-2">{{ $row['description'] }}</td>
                                    <td class="border px-4 py-2">{{ $row->taxType->tax_type }} ({{ $row->taxType->VAT }}%)</td>
                                    <td class="border px-4 py-2">{{ $row->atc->tax_code }} ({{ $row->atc->tax_rate }}%)</td>
                                    <td class="border px-4 py-2">{{ $row['coa'] }}</td>
                                    <td class="border px-4 py-2">{{ number_format($row['amount'], 2) }}</td>
                                    <td class="border px-4 py-2">{{ number_format($row['tax_amount'], 2) }}</td>
                                    <td class="border px-4 py-2">{{ number_format($row['net_amount'], 2) }}</td>
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
                                            <td colspan="2">
                                                <hr class="border-neutral-300">
                                            </td>
                                        </tr>
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

        {{-- Journal --}}
        @elseif ($transaction->transaction_type === 'Journal')
        <x-transaction-form-section>
            @php
                $type = 'journal'; // Set type to 'journal' for journal transactions
            @endphp
            <!-- Redirection Link -->
            <x-slot:redirection>
                <a href="/transactions" class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g></svg>
                    <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go back</span>
                </a>
            </x-slot:redirection>
            <!-- Form Header Title -->
            <x-slot:description>
                <h1 class="text-3xl font-bold text-blue-900">Journal: {{ $transaction->reference }}</h1>
            </x-slot:description>
            <x-slot:options>
                <div class="relative inline-block text-left mt-4 mb-4">
                    <button id="journalOptionsButton" onclick="toggleJournalOptions(event)" class="inline-flex justify-between w-full rounded-md border border-blue-900 shadow-sm px-4 py-2 bg-blue-900 text-sm tracking-wide font-bold text-white hover:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-blue-800">
                        Journal Options
                        <svg class="-mr-1 ml-2 h-5 w-5 -rotate-90" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06 0L10 10.707l3.71-3.5a.75.75 0 111.06 1.06l-4 3.75a.75.75 0 01-1.06 0l-4-3.75a.75.75 0 010-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <!-- Journal Options Menu -->
                    <div id="journalOptions" class="hidden absolute right-0 z-10 mt-2 w-44 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                        <div class="py-2 px-4" role="menu" aria-orientation="vertical" aria-labelledby="journalOptionsButton">
                            <a href="{{ route('transactions.edit', $transaction->id) }}" class="block px-4 py-2 text-sm text-zinc-700 hover-dropdown">Edit Journal</a>
                        </div>
                    </div>
                </div>
            </x-slot:options>
            <!-- Form Fields (Date, Reference, etc.) -->
            <x-slot:form>
                <!-- Form Header with Total Amount -->
                <div class="grid grid-cols-4 gap-6">
                     <!-- Reference Field -->
                     <div class="mt-5 px-6 border-r-neutral-300">
                        <x-transaction-label for="reference" :value="__('Reference')" />
                        <div class="mt-1 text-zinc-700">{{ $transaction->reference }}</div>
                    </div>
                    <!-- Date Field -->
                    <div class="mt-5">
                        <x-transaction-label for="date" :value="__('Invoice Date')" />
                        <div class="mt-1 text-zinc-700">{{ \Carbon\Carbon::parse($transaction->date)->format('F j, Y') }}</div>
                    </div>
                    <!-- Total Amount Field -->
                    <div class="col-span-2 bg-blue-50 p-4 rounded-tr-sm">
                        <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                        <div class="mt-1 text-blue-900 font-extrabold">{{ number_format($transaction->total_amount, 2) }}</div>
                    </div>
                </div>

                <!-- Table Section (Journal Entries) -->
                <div class="mt-0">
                    <table class="table-auto w-full text-left text-sm border-neutral-300 text-neutral-600">
                        <thead class="bg-neutral-100 border-t-neutral-300 text-neutral-600">
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
                                    <td class="border px-4 py-2">{{ $row->description }}</td>
                                    <td class="border px-4 py-2">{{ $row->coaAccount->name ?? 'N/A' }}</td>
                                    <td class="border px-4 py-2">{{ number_format($row['debit'], 2) }}</td>
                                    <td class="border px-4 py-2">{{ number_format($row['credit'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Total Amount Due Section -->
                <x-slot name="after">
                    <div class="flex justify-end mt-10 mb-20">
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
            {{-- <x-slot:actions>
                <div class="flex justify-end mt-4 mb-32">
                    <x-button type="button" class="ml-4 text-zinc-500 bg-gray-200 cursor-not-allowed" disabled>
                        {{ __('Save Journal Entry') }}
                    </x-button>
                </div>
            </x-slot:actions> --}}
        </x-transaction-form-section>
       
 
</div>

    @endif
    @if(session()->has('success'))
        <div x-data="{ showSuccessModal: true }" 
             x-init="setTimeout(() => showSuccessModal = false, 5000)" 
             x-show="showSuccessModal" 
             x-cloak 
             x-effect="document.body.classList.toggle('overflow-hidden', showSuccessModal || success)"
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-200 bg-opacity-50"
             @click.away="showSuccessModal = false">
            
            <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative">
                <!-- Close Button -->
                <button @click="showSuccessModal = false" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <div class="flex flex-col items-center">

                    <div class="flex justify-center mb-4">
                        <img src="{{ asset('images/Success.png') }}" alt="Organization Added" class="w-28 h-28">
                    </div>

                    <!-- Title -->
                    <p class="text-emerald-500 font-bold text-3xl mb-2">Mark as Posted</p>

                    <!-- Description -->
                    <p class="text-sm text-gray-600 text-center mb-6">
                        {{ session('success') }}
                    </p>

                </div>
            </div>
        </div>
    @endif

    @if(session()->has('successPayment'))
    <div x-data="{ showSuccessPaymentModal: true }" 
         x-init="setTimeout(() => showSuccessPaymentModal = false, 5000)" 
         x-show="showSuccessPaymentModal" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
         @click.away="showSuccessPaymentModal = false">
        
        <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative">
            <button 
                @click="showSuccessPaymentModal = false" 
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition"
            >
                <i class="fa fa-times"></i>
            </button>
            
            <div class="flex flex-col items-center">
                <!-- Icon -->
                <div class="mb-6">
                    <div class="flex items-center justify-center w-24 h-24 rounded-full bg-green-600">
                        <i class="fas fa-check text-white text-6xl"></i>
                    </div>
                </div>

                <!-- Title -->
                <a href="{{route('transactions.mark', $transaction->id)}}" class="block px-4 py-2 text-sm text-zinc-700 hover-dropdown">Payment Added</a>

                <!-- Description -->
                <p class="text-sm text-gray-600 text-center mb-6">
                    The payment for this transaction has been successfully added.
                </p>

                <!-- Close Button -->
                <button 
                    @click="showSuccessPaymentModal = false" 
                    class="px-5 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-sm transition w-1/2"
                >
                    OK
                </button>
            </div>
        </div>
    </div>
    @endif


    
</div>
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
