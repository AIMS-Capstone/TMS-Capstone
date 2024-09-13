
<x-transaction-form-section>
    @php
    $type = request()->query('type', 'sales');
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
            <h1 class="text-3xl font-bold text-blue-900">Add New Sale</h1>
        </x-slot:description>
    
        <!-- Form Fields (Customer, Date, etc.) -->
        <x-slot:form>
            <div class="grid grid-cols-5 gap-6">
                <!-- Customer Select -->
                <div class="mt-5 mb-8 ml-10">
                    <label for="select-contact" class="block font-bold text-sm text-blue-900">Customer</label>
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
                <div class="mt-5 mb-8">
                    <x-transaction-label for="date" :value="__('Date')" />
                    <x-transaction-input id="date" type="date" class="mt-1 block w-full" wire:model.defer="date" />
                </div>
    
                <!-- Invoice Number Field -->
                <div class="mt-5 mb-8">
                    <x-transaction-label for="inv_number" :value="__('Invoice Number')" />
                    <x-transaction-input id="inv_number" type="text" class="mt-1 block w-full" wire:model.defer="inv_number" />
                </div>
    
                <!-- Reference Field -->
                <div class="mt-5 mb-8">
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
                                
                                @if($vatableSales > 0)
                                <tr>
                                    <td class="px-4 py-2 taxuri-text">VATable Sales</td>
                                    <td class="px-4 py-2 text-right taxuri-text">{{ number_format($vatableSales, 2) }}</td>
                                </tr>
                                @endif
            
                                @if($vatAmount > 0)
                                <tr>
                                    <td class="px-4 py-2 taxuri-text">VAT Amount (12%)</td>
                                    <td class="px-4 py-2 text-right taxuri-text">{{ number_format($vatAmount, 2) }}</td>
                                </tr>
                                @endif
            
                                @if($nonVatableSales > 0)
                                <tr>
                                    <td class="px-4 py-2 taxuri-text">Non-VATable Sales</td>
                                    <td class="px-4 py-2 text-right">{{ number_format($nonVatableSales, 2) }}</td>
                                </tr>
                                @endif
            
                                @if($totalAmount > 0)
                                <tr>
                                    <td class="px-4 py-2 font-bold text-blue-900">Total Amount Due</td>
                                    <td class="px-4 py-2 text-right font-bold text-blue-900">{{ number_format($totalAmount, 2) }}</td>
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
                let title = 'New Contact';
                let body = `
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-3">
                            <div class="mb-4 col-span-1 mr-2">
                                <label for="contact_type" class="block text-gray-700 ">Contact Type</label>
                                <input type="text" id="contact_type" wire:model.defer="newContact.contact_type" name="contact_type" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4 col-span-2">
                                <label for="contact_tin" class="block text-gray-700">Tax Identification Number</label>
                                <input type="text" id="contact_tin" wire:model.defer="newContact.contact_tin" name="contact_tin" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="mb-4 col-span-2">
                            <label for="bus_name" class="block text-gray-700">Name</label>
                            <input type="text" id="bus_name" wire:model.defer="newContact.bus_name" name="bus_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div class="grid grid-cols-3">
                            <div class="mb-4 col-span-1 mr-2">
                                <label for="contact_address" class="block text-gray-700">Address</label>
                                <input type="text" id="contact_address" wire:model.defer="newContact.contact_address" name="contact_address" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4 col-span-1 mr-2">
                                <label for="contact_city" class="block text-gray-700">City</label>
                                <input type="text" id="contact_city" wire:model.defer="newContact.contact_city" name="contact_city" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="mb-4 col-span-1">
                                <label for="contact_zip" class="block text-gray-700">Zip</label>
                                <input type="text" id="contact_zip" wire:model.defer="newContact.contact_zip" name="contact_zip" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="bg-blue-900 text-white px-4 py-2 rounded">Add</button>
                        </div>
                    </form>
                `;
                $wire.dispatch('triggerModal', { title, body }); // Trigger the modal from Livewire
            });
        });

    </script>
@endscript
