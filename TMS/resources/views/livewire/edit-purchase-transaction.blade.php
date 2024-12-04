<x-transaction-form-section>
    @php
    $type = request()->query('type', 'purchase'); // Default to 'purchase' if 'type' is not set
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
        <h1 class="text-3xl font-bold text-blue-900">Edit Purchase</h1>
    </x-slot:description>

    <!-- Form Fields (Customer, Date, etc.) -->
    <x-slot:form>
        <div class="grid grid-cols-4 gap-6">
            <!-- Customer Select -->
            <div class="mt-4 ml-10">
                <label for="select-contact" class="block font-bold text-sm taxuri-color">
                    Vendor
                    <span class="relative group items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block ml-1 w-4 h-4 text-blue-900" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M13 9h-2V7h2m0 10h-2v-6h2m-1-9A10 10 0 0 0 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/>
                        </svg>
                        <div class="hidden group-hover:block absolute left-1/2 transform -translate-x-1/2 mt-2 w-64 bg-white text-zinc-700 font-normal text-sm rounded-lg shadow-lg p-2 overflow-hidden z-50">
                            Enter your Vendor name here. If the Vendor is not a part of your contacts, add them by clicking Add as New Contact.
                        </div>
                    </span>
                </label>
                <div class="mt-4">
                    <livewire:select-input
                    name="select_contact"
                    labelKey="name"
                    valueKey="value"
                    class="form-control w-full select2-input"
                    id="select_contact"
                    :type="$type"
                    :selectedValue="$selectedContact" 
                />
                </div>
            </div>

            <!-- Date Field -->
            <div class="mt-5 mb-8">
                <x-transaction-label for="date" :value="__('Invoice Date')" />
                <x-transaction-input id="date" type="date" class="mt-1 block w-full" wire:model.defer="date" />
            </div>


            <!-- Reference Field -->
            <div class="mt-5 mb-8">
                <x-transaction-label for="reference" :value="__('Reference')" />
                <x-transaction-input id="reference" type="text" class="mt-1 block w-full" wire:model.defer="reference" />
            </div>

            <!-- Total Amount Field -->
            <div class="col-span-1 bg-blue-50 p-4 rounded-tr-sm">
                <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                <x-transaction-input id="total_amount" type="text" class="mt-1 text-blue-900 font-bold block w-full bg-blue-50 border-0" value="{{ $totalAmount }}" wire:model.defer="total_amount" readonly />
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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($taxRows as $index => $row)
                    <livewire:tax-row :key="$index" :index="$index" :tax-row="$row" :type="$type" />
                @endforeach
                
                </tbody>
            </table>
        </div>
        
        <x-slot name="after">
            <div class="mt-4">
                <button type="button" wire:click="addTaxRow" class="group flex items-center space-x-2 border rounded-lg px-3 py-2 text-zinc-600 hover:font-bold hover:text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 32 32">
                        <path class="fill-[#949494] group-hover:fill-blue-900" d="M16 3C8.832 3 3 8.832 3 16s5.832 13 13 13s13-5.832 13-13S23.168 3 16 3m0 2c6.087 0 11 4.913 11 11s-4.913 11-11 11S5 22.087 5 16S9.913 5 16 5m-1 5v5h-5v2h5v5h2v-5h5v-2h-5v-5z"/>
                    </svg>
                    <span>Add New Line</span>
                </button>
            </div>
            <div class="mt-4">
                <div class="flex justify-end">
                    <table class="table-auto">
                        <tbody>
                            @if($vatablePurchase > 0)
                            <tr>
                                <td class="px-4 py-2 taxuri-text">VATable Purchase</td>
                                <td class="px-4 py-2 text-right taxuri-text">{{ number_format($vatablePurchase, 2) }}</td>
                            </tr>
                            @endif
    
                            @if($nonVatablePurchase > 0)
                            <tr>
                                <td class="px-4 py-2 taxuri-text">Non-VATable Purchase</td>
                                <td class="px-4 py-2 text-right taxuri-text">{{ number_format($nonVatablePurchase, 2) }}</td>
                            </tr>
                            @endif
    
                            @if($vatAmount > 0)
                            <tr>
                                <td class="px-4 py-2 taxuri-text">VAT Amount</td>
                                <td class="px-4 py-2 text-right taxuri-text">{{ number_format($vatAmount, 2) }}</td>
                            </tr>
                            @endif
                            @if(count($appliedATCs) > 0)
                            @foreach($appliedATCs as $atc)
                            <tr>
                                <td class="px-4 py-2 taxuri-text">{{ $atc['code'] }} ({{ number_format($atc['rate'], 2) }}%)</td>
                                <td class="px-4 py-2 text-right ">{{ number_format($atc['tax_amount'], 2) }}</td>
                            @endforeach
                            </tr>
                            @endif
    
                            @if($totalAmount > 0)
                            <tr>
                                <td class="px-4 py-2 font-bold taxuri-color">Total Amount Due</td>
                                <td class="px-4 py-2 text-right font-bold taxuri-color">{{ number_format($totalAmount, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </x-slot>
    </x-slot:form>
    <!-- Save Button -->
    <x-slot:actions>
        <div class="flex justify-end mt-4 mb-10">
            <x-button type="submit" class="ml-4 text-white px-4 py-2 rounded-lg shadow-md">
                {{ __('Save Transaction') }}
            </x-button>
        </div>
    </x-slot:actions>
</x-transaction-form-section>

@assets
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endassets

@script
<script>
$(document).ready(function() {

    $('#select_contact').select2({
templateResult: function(data) {
// Custom formatting for the dropdown list
if (!data.id) {
    return data.text;
}
var $result = $(
    '<span>' + data.text + 
    '<small style="display:block; font-size:15px; line-height:1; margin-top:0; padding-top:0;">' + 
    $(data.element).data('tax-id') + '</small></span>'
);
return $result;
},
templateSelection: function(data) {
// Custom formatting for the selected item
if (!data.id) {
    return data.text;
}
var $selection = $(
    '<span>' + data.text + 
    '<span style="display:block; font-size:15px; line-height:1; margin-top:0; padding-top:0;">' + 
    $(data.element).data('tax-id') + '</span></span>'
);
return $selection;
},
language: {
noResults: function() {
    return "<a href='#' class='btn btn-danger use-anyway-btn'>Add a new contact</a>";
}
},
escapeMarkup: function (markup) {
return markup;
}
});

$('#select_contact').on('change', function() {
        $wire.set('selectedContact', $(this).val()); // Update Livewire component
    });


    // Add a click event listener for the "Use it anyway" button
    $(document).on('click', '.use-anyway-btn', function(e) {
        e.preventDefault(); // Prevent default action
        let title = 'Add New Contact';
        let body = `
        <div class="p-10">
            <form wire:submit.prevent="savePurchase">
                    <!-- Contact Information -->
                    <h2 class="text-xl taxuri-color font-bold mb-2">Contact Information</h2>
            
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Left Section -->
                        <div class="col-span-1 space-y-4">
                            <p class="text-sm mb-4">Basic Information</p>
                            <!-- Type (Corporation/Individual) -->
                            <div>
                                <label for="contact_type" class="block text-gray-700 text-sm font-bold">Type</label>
                                <select id="contact_type" wire:model.defer="newContactPurchase.contact_type" name="contact_type" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                                    <option value="" disabled selected>Select Business Type</option>
                                    <option value="Individual">Individual</option>
                                    <option value="Non-Individual">Non-Individual</option>
                                </select>
                            </div>

                            <!-- Corporation Name -->
                            <div>
                                <label for="bus_name" class="block text-gray-700 text-sm font-bold">Corporation Name</label>
                                <input type="text" id="bus_name" wire:model.defer="newContactPurchase.bus_name" name="bus_name" placeholder="Corporation Name" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            </div>

                            <!-- Tax Identification Number -->
                            <div>
                                <label for="contact_tin" class="block text-gray-700 text-sm font-bold">Tax Identification Number</label>
                                <input type="text" id="contact_tin" wire:model.defer="newContactPurchase.contact_tin" name="contact_tin" placeholder="000-000-000-000" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            </div>

                            <p class="text-sm my-4">Basic Information</p>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-gray-700 text-sm font-bold">Email</label>
                                <input type="email" id="email" wire:model.defer="newContactPurchase.email" name="email" placeholder="Enter Email Address" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-gray-700 text-sm font-bold">Phone</label>
                                <input type="text" id="phone" wire:model.defer="newContactPurchase.phone" name="phone" placeholder="e.g 09123456789" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            </div>
                        </div>

                        <!-- Right Section -->
                        <div class="col-span-1 space-y-4">
                            <p class="text-sm mb-4">Address Details</p>
                            <!-- Address -->
                            <div>
                                <label for="contact_address" class="block text-gray-700 text-sm font-bold">Address</label>
                                <input type="text" id="contact_address" wire:model.defer="newContactPurchase.contact_address" name="contact_address" placeholder="Address" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            </div>

                            <!-- City -->
                            <div>
                                <label for="contact_city" class="block text-gray-700 text-sm font-bold">City</label>
                                <input type="text" id="contact_city" wire:model.defer="newContactPurchase.contact_city" name="contact_city" placeholder="City" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            </div>

                            <!-- Postal/Zip Code -->
                            <div>
                                <label for="contact_zip" class="block text-gray-700 text-sm font-bold">Postal</label>
                                <input type="text" id="contact_zip" wire:model.defer="newContactPurchase.contact_zip" name="contact_zip" placeholder="e.g 1203" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            </div>
                        </div>
                    </div>

                    <!-- Default Tax Information -->
                    <h2 class="text-xl taxuri-color font-bold my-4">Default Tax Information</h2>
                    <div class="grid grid-cols-2 gap-4">
                    <!-- Revenue Section -->
                    <div>
                        <p class="text-sm mb-4">Revenue</p>

                        <label for="revenue_tax_type" class="block text-gray-700 text-sm font-bold">Tax Type</label>
                        <select id="revenue_tax_type" wire:model.defer="newContactPurchase.revenue_tax_type" name="revenue_tax_type" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            <option value="">Nothing Selected</option>
                            <!-- Add options for Tax Type here -->
                        </select>

                        <label for="revenue_atc" class="block text-gray-700 text-sm font-bold mt-4">ATC</label>
                        <select id="revenue_atc" wire:model.defer="newContactPurchase.revenue_atc" name="revenue_atc" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            <option value="">Nothing Selected</option>
                            <!-- Add options for ATC here -->
                        </select>

                        <label for="revenue_chart_accounts" class="block text-gray-700 text-sm font-bold mt-4">Chart of Accounts</label>
                        <select id="revenue_chart_accounts" wire:model.defer="newContactPurchase.revenue_chart_accounts" name="revenue_chart_accounts" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            <option value="">Nothing Selected</option>
                            <!-- Add options for Chart of Accounts here -->
                        </select>
                    </div>

                    <!-- Expense Section -->
                    <div>
                        <p class="text-sm mb-4">Expense</p>

                        <label for="expense_tax_type" class="block text-gray-700 text-sm font-bold">Tax Type</label>
                        <select id="expense_tax_type" wire:model.defer="newContactPurchase.expense_tax_type" name="expense_tax_type" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            <option value="">Nothing Selected</option>
                            <!-- Add options for Tax Type here -->
                        </select>

                        <label for="expense_atc" class="block text-gray-700 text-sm font-bold mt-4">ATC</label>
                        <select id="expense_atc" wire:model.defer="newContactPurchase.expense_atc" name="expense_atc" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                            <option value="">Nothing Selected</option>
                            <!-- Add options for ATC here -->
                        </select>

                        <label for="expense_chart_accounts" class="block text-gray-700 text-sm font-bold mt-4">Chart of Accounts</label>
                        <select id="expense_chart_accounts" wire:model.defer="newContactPurchase.expense_chart_accounts" name="expense_chart_accounts" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peerm">
                            <option value="">Nothing Selected</option>
                            <!-- Add options for Chart of Accounts here -->
                        </select>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end items-center mt-6 gap-4">
                    <button type="button" class="text-gray-600 font-semibold" wire:click="closeModal">Cancel</button>
                    <button type="submit" class="bg-blue-900 text-white py-2 px-6 font-bold text-md rounded-lg">Save</button>
                </div>
            </form>
        </div>

        `;
        $wire.dispatch('triggerModal', { title, body }); // Trigger the modal from Livewire
    });
});

</script>
@endscript
