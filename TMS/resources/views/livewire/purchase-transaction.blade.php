
<x-transaction-form-section>
    @php
    $type = request()->query('type', 'purchase'); // Default to 'purchase' if 'type' is not set
    @endphp
    <!-- Redirection Link -->
    <x-slot:redirection>
        <a href="/transactions" class="flex items-center space-x-2 text-blue-600">
            <span>← Go back</span>
        </a>
    </x-slot:redirection>

    <!-- Form Header Title -->
    <x-slot:description>
        <h1 class="text-3xl font-bold text-blue-900">Add New Purchase</h1>
    </x-slot:description>

    <!-- Form Fields (Customer, Date, etc.) -->
    <x-slot:form>
        <div class="grid grid-cols-4 gap-4">
            <!-- Customer Select -->
            <div class="mt-4 ml-10">
                <label for="select-contact" class="block font-medium text-sm text-gray-700">Vendor</label>
                <div class="mt-1">
                    <livewire:select-input
                        name="select_contact"
                        labelKey="name"
                        valueKey="value"
                        class="form-control w-full select2-input"
                        id="select_contact"
                        :type="$type"
                    />
                </div>
            </div>

            <!-- Date Field -->
            <div class="mt-5">
                <x-transaction-label for="date" :value="__('Date')" />
                <x-transaction-input id="date" type="date" class="mt-1 block w-full" wire:model.defer="date" />
            </div>


            <!-- Reference Field -->
            <div class="mt-5">
                <x-transaction-label for="reference" :value="__('Reference')" />
                <x-transaction-input id="reference" type="text" class="mt-1 block w-full" wire:model.defer="reference" />
            </div>

            <!-- Total Amount Field -->
            <div class="col-span-1 bg-blue-50 p-4 rounded-tr-sm">
                <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                <x-transaction-input id="total_amount" type="text" class="mt-1 block w-full bg-white border-0" value="{{ $totalAmount }}" wire:model.defer="total_amount" readonly />
            </div>
        </div>

        <!-- Table Section -->
        <div class="mt-0">
            <table class="table-auto w-full text-left">
                <thead class="bg-gray-100">
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
                <button type="button" wire:click="addTaxRow" class="flex items-center space-x-2 text-blue-600 hover:text-blue-800">
                    <span>➕ Add New Line</span>
                </button>
                
            </div>
            <div class="mt-4">
                <div class="flex justify-end">
                    <table class="table-auto">
                        <tbody>
                            @if($vatablePurchase > 0)
                            <tr>
                                <td class="px-4 py-2">VATable Purchase</td>
                                <td class="px-4 py-2 text-right">{{ number_format($vatablePurchase, 2) }}</td>
                            </tr>
                            @endif
    
                            @if($nonVatablePurchase > 0)
                            <tr>
                                <td class="px-4 py-2">Non-VATable Purchase</td>
                                <td class="px-4 py-2 text-right">{{ number_format($nonVatablePurchase, 2) }}</td>
                            </tr>
                            @endif
    
                            @if($vatAmount > 0)
                            <tr>
                                <td class="px-4 py-2">VAT Amount</td>
                                <td class="px-4 py-2 text-right">{{ number_format($vatAmount, 2) }}</td>
                            </tr>
                            @endif
                            @if(count($appliedATCs) > 0)
                            @foreach($appliedATCs as $atc)
                            <tr>
                                <td class="px-4 py-2 font-bold">{{ $atc['code'] }} ({{ number_format($atc['rate'], 2) }}%)</td>
                                <td class="px-4 py-2 text-right">{{ number_format($atc['tax_amount'], 2) }}</td>
                            @endforeach
                            </tr>
                            @endif
    
                            @if($totalAmount > 0)
                            <tr>
                                <td class="px-4 py-2 font-bold">Total Amount Due</td>
                                <td class="px-4 py-2 text-right font-bold">{{ number_format($totalAmount, 2) }}</td>
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
        
  
        
     
        <div class="flex justify-end mt-4">
            <x-button type="submit" class="ml-4 bg-blue-500 text-white px-4 py-2 rounded shadow-md">
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
        let title = 'New Contact';
        let body = `
          <form wire:submit.prevent="savePurchase">
    <!-- Contact Information -->
    <h2 class="text-xl font-bold mb-4">Contact Information</h2>
    
    <div class="grid grid-cols-2 gap-4">
        <!-- Type (Corporation/Individual) -->
        <input type="hidden" name="contact_role" wire:model.defer="newContactPurchase.contact_role" value="vendor">
        <div class="col-span-1">
            <label for="contact_type" class="block text-gray-700">Type</label>
            <select id="contact_type" wire:model.defer="newContactPurchase.contact_type" name="contact_type" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="Individual">Individual</option>
                <option value="Non-Individual">Non-Individual</option>
            </select>
        </div>

        <!-- Corporation Name -->
        <div class="col-span-1">
            <label for="bus_name" class="block text-gray-700">Corporation Name</label>
            <input type="text" id="bus_name" wire:model.defer="newContactPurchase.bus_name" name="bus_name" placeholder="Corporation Name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
        </div>

        <!-- Tax Identification Number -->
        <div class="col-span-1">
            <label for="contact_tin" class="block text-gray-700">Tax Identification Number</label>
            <input type="text" id="contact_tin" wire:model.defer="newContactPurchase.contact_tin" name="contact_tin" placeholder="000-000-000-000" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
        </div>

        <!-- Email -->
        <div class="col-span-1">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" id="email" wire:model.defer="newContactPurchase.email" name="email" placeholder="Enter Email Address" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
        </div>

        <!-- Phone -->
        <div class="col-span-1">
            <label for="phone" class="block text-gray-700">Phone</label>
            <input type="text" id="phone" wire:model.defer="newContactPurchase.phone" name="phone" placeholder="e.g 09123456789" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
        </div>

        <!-- Address Details -->
        <div class="col-span-1">
            <label for="contact_address" class="block text-gray-700">Address</label>
            <input type="text" id="contact_address" wire:model.defer="newContactPurchase.contact_address" name="contact_address" placeholder="Address" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
        </div>

        <!-- City -->
        <div class="col-span-1">
            <label for="contact_city" class="block text-gray-700">City</label>
            <input type="text" id="contact_city" wire:model.defer="newContactPurchase.contact_city" name="contact_city" placeholder="City" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
        </div>

        <!-- Postal/Zip Code -->
        <div class="col-span-1">
            <label for="contact_zip" class="block text-gray-700">Postal</label>
            <input type="text" id="contact_zip" wire:model.defer="newContactPurchase.contact_zip" name="contact_zip" placeholder="e.g 1203" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
        </div>
    </div>

    <!-- Default Tax Information -->
    <h2 class="text-xl font-bold mb-4">Default Tax Information</h2>
    <div class="grid grid-cols-2 gap-4">
        <!-- Revenue Section -->
        <div>
            <h3 class="text-lg font-semibold mb-2">Revenue</h3>

            <label for="revenue_tax_type" class="block text-gray-700">Tax Type</label>
            <select id="revenue_tax_type" wire:model.defer="newContactPurchase.revenue_tax_type" name="revenue_tax_type" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">Nothing Selected</option>
                <!-- Add options for Tax Type here -->
            </select>

            <label for="revenue_atc" class="block text-gray-700 mt-4">ATC</label>
            <select id="revenue_atc" wire:model.defer="newContactPurchase.revenue_atc" name="revenue_atc" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">Nothing Selected</option>
                <!-- Add options for ATC here -->
            </select>

            <label for="revenue_chart_accounts" class="block text-gray-700 mt-4">Chart of Accounts</label>
            <select id="revenue_chart_accounts" wire:model.defer="newContactPurchase.revenue_chart_accounts" name="revenue_chart_accounts" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">Nothing Selected</option>
                <!-- Add options for Chart of Accounts here -->
            </select>
        </div>

        <!-- Expense Section -->
        <div>
            <h3 class="text-lg font-semibold mb-2">Expense</h3>

            <label for="expense_tax_type" class="block text-gray-700">Tax Type</label>
            <select id="expense_tax_type" wire:model.defer="newContactPurchase.expense_tax_type" name="expense_tax_type" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">Nothing Selected</option>
                <!-- Add options for Tax Type here -->
            </select>

            <label for="expense_atc" class="block text-gray-700 mt-4">ATC</label>
            <select id="expense_atc" wire:model.defer="newContactPurchase.expense_atc" name="expense_atc" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">Nothing Selected</option>
                <!-- Add options for ATC here -->
            </select>

            <label for="expense_chart_accounts" class="block text-gray-700 mt-4">Chart of Accounts</label>
            <select id="expense_chart_accounts" wire:model.defer="newContactPurchase.expense_chart_accounts" name="expense_chart_accounts" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">Nothing Selected</option>
                <!-- Add options for Chart of Accounts here -->
            </select>
        </div>
    </div>

    <!-- Save Button -->
    <div class="mt-6">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
        <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Cancel</button>
    </div>
</form>

        `;
        $wire.dispatch('triggerModal', { title, body }); // Trigger the modal from Livewire
    });
});

</script>
@endscript
