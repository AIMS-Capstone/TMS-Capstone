
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
            <div class="flex justify-between items-center">
                <!-- Title -->
                <h1 class="text-3xl font-bold text-blue-900">Add New Sale</h1>
            </div>
            
        </x-slot:description>
        <x-slot:wildcard>
            <div class="flex flex-col text-sm px-1">
                <div class="flex justify-end items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-400" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M5 21V5q0-.825.588-1.412T7 3h10q.825 0 1.413.588T19 5v16l-7-3zm2-3.05l5-2.15l5 2.15V5H7zM7 5h10z"/>
                    </svg>
                    <span class="text-amber-400">Drafted</span>
                </div>
                <p class="text-zinc-500 text-xs italic mt-1 text-end">Transactions are automatically saved as Draft.</p>
            </div>
        </x-slot:wildcard>

        <!-- Form Fields (Customer, Date, etc.) -->
        <x-slot:form>
            <div class="grid grid-cols-5 gap-6">
                <!-- Customer Select -->
                <div class="mt-5 mb-8 ml-10">
                    <label for="select-contact" class="block font-bold text-sm text-blue-900">Customer <span class="text-red-700">*</span></label>
                    <div class="mt-4">
                        <livewire:select-input
                            name="select_contact"
                            labelKey="name"
                            valueKey="value"
                            class="form-control w-full select2-input"
                            id="select_contact"
                            :type="$type"
                        />
                        @if($this->getErrorBag()->has('selectedContact'))
                        @foreach($this->getErrorBag()->get('selectedContact') as $error)
                        <span class="text-red-700">{{ $error }}</span>
                    @endforeach
                    @endif
                    </div>
                    
                    <span id="contact-error-message" class="text-red-500"></span>
                </div>
    
                <!-- Date Field -->
    
                <div class="mt-5 mb-8">
                    <x-transaction-label for="date" :value="__('Invoice Date')"  required="true"/>
                    <x-transaction-input id="date" type="date" class="mt-4 text-sm w-full" wire:model.defer="date" />
                    @if($this->getErrorBag()->has('date'))
                    @foreach($this->getErrorBag()->get('date') as $error)
                    <span class="text-red-700">{{ $error }}</span>
                @endforeach
                @endif
    
                </div>
           
          
                <!-- Invoice Number Field -->
                <div class="mt-5 mb-8">
                    <x-transaction-label for="inv_number" :value="__('Invoice Number')"   required="true"/>
                    <x-transaction-input id="inv_number" type="text" class="mt-4 text-sm w-full" wire:model.defer="inv_number" />
                    @if($this->getErrorBag()->has('inv_number'))
                    @foreach($this->getErrorBag()->get('inv_number') as $error)
                    <span class="text-red-700">{{ $error }}</span>
                @endforeach
                @endif
                </div>
    
                <!-- Reference Field -->
                <div class="mt-5 mb-8">
                    <x-transaction-label for="reference" :value="__('Reference')" />
                    <x-transaction-input id="reference" type="text" class="mt-4 text-sm w-full" wire:model.defer="reference" />
                    @if($this->getErrorBag()->has('reference'))
                    @foreach($this->getErrorBag()->get('reference') as $error)
                    <span class="text-red-700">{{ $error }}</span>
                @endforeach
                @endif
                </div>
    
                <!-- Total Amount Field -->
                <div class="col-span-1 bg-blue-50 p-4 rounded-tr-sm">
                    <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                    <x-transaction-input id="total_amount" type="text" class="font-bold text-blue-900 bg-blue-50 mt-4  w-full" value="{{ $totalAmount }}" wire:model.defer="total_amount" readonly />
                </div>
            </div>
    
            <!-- Table Section -->
            <div class="mt-0">
                <table class="table-auto w-full text-left text-sm text-neutral-600">
                    <thead class="bg-neutral-100 text-neutral-700">
                        <tr>
                            <th class="px-3 py-2">Description <span class="text-red-700">*</span></th>
                            <th class="px-3 py-2">Tax Type <span class="text-red-700">*</span></th>
                            <th class="px-3 py-2">Alphanumeric Tax Code (ATC)<span class="text-red-700">*</span></th>
                            <th class="px-3 py-2">Chart of Account (COA)<span class="text-red-700">*</span></th>
                            <th class="px-3 py-2">Amount (VAT Inclusive)<span class="text-red-700">*</span></th>
                            <th class="px-3 py-2">Tax Amount <span class="text-red-700">*</span></th>
                            <th class="px-3 py-2">Net Amount <span class="text-red-700">*</span></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($taxRows as $row)
                        <livewire:tax-row 
                            :key="$row['id']" 
                            :index="$row['id']" 
                            :tax-row="$row" 
                            :type="$type" 
                        />
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
            <div class="flex items-center justify-between w-full">
                <!-- Error Section on the Left -->
                <div class="text-left">
                    @if($this->getErrorBag()->any())
                        <div class="inline-block bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops! There are some errors:</strong>
                            <ul class="list-disc list-inside mt-2">
                                @foreach($this->getErrorBag()->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <!-- Empty placeholder to maintain layout -->
                        <div class="h-12"></div>
                    @endif
                </div>
        
                <!-- Save Button on the Right -->
                <div class="text-right">
                    <x-button type="submit" class="ml-4 text-white px-4 py-2 rounded-lg shadow-md">
                        {{ __('Save Transaction') }}
                    </x-button>
                </div>
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
            document.addEventListener('livewire:load', () => {
        @this.on('contactError', data => {
            const errorContainer = document.getElementById('contact-error-message');
            if (errorContainer && data.errors.contact) {
                // Display the first error message
                errorContainer.textContent = data.errors.contact[0];
            }
        });
    });

        // In your main JS file or layout
document.addEventListener('livewire:initialized', function () {
    // Initialize Select2
    $('.select2').select2({
        placeholder: 'Select a contact',
        allowClear: true,
        width: '100%'
    });

    // Handle error styling
    Livewire.on('parentComponentErrorBag', function(data) {
        if (data.index === 'select_contact') {
            const select2Container = $('#select_contact').next('.select2-container');
            select2Container.find('.select2-selection').addClass('border-red-500');
        }
    });

    // Clear error styling on change
    $('.select2').on('change', function() {
        $(this).next('.select2-container')
            .find('.select2-selection')
            .removeClass('border-red-500');
    });
});
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
            return "<a href='#' class='btn btn-danger use-anyway-btn'>Add a new customer</a>";
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
                <form wire:submit.prevent="save" class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="contact_type" class="block text-gray-700 font-semibold">Contact Type<span class="text-red-500">*</span></label>
                            <select id="contact_type" wire:model.defer="newContact.contact_type" name="contact_type" class="block w-full px-0 py-2 text-sm text-neutral-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 appearance-none peer">
                                <option value="" class="text-gray-500"disabled selected>Contact Type</option>
                                <option class="hover:bg-blue-100 hover:text-blue-950 hover:font-bold hover:rounded-full">Individual</option>
                                <option class="hover:bg-blue-100 hover:text-blue-950 hover:font-bold hover:rounded-full">Non-Individual</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="contact_tin" class="block text-gray-700 font-semibold">Tax Identification Number (TIN)<span class="text-red-500">*</span></label>
                            <input type="text" id="contact_tin" wire:model.defer="newContact.contact_tin" name="contact_tin" class="block w-full py-2 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" placeholder="000-000-000-000">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="bus_name" class="block text-gray-700 font-semibold">Name<span class="text-red-500">*</span></label>
                        <input type="text" id="bus_name" wire:model.defer="newContact.bus_name" name="bus_name" class="block w-full py-2 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" placeholder="Customer Name">
                    </div>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="mb-4">
                            <label for="contact_address" class="block text-gray-700 font-semibold">Address<span class="text-red-500">*</span></label>
                            <input type="text" id="contact_address" wire:model.defer="newContact.contact_address" name="contact_address" class="block w-full py-2 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" placeholder="Address">
                        </div>
                        <div class="mb-4">
                            <label for="contact_city" class="block text-gray-700 font-semibold">City<span class="text-red-500">*</span></label>
                            <input type="text" id="contact_city" wire:model.defer="newContact.contact_city" name="contact_city" class="block w-full py-2 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" placeholder="City">
                        </div>
                        <div class="mb-4">
                            <label for="contact_zip" class="block text-gray-700 font-semibold">Zip Code<span class="text-red-500">*</span></label>
                            <input type="text" id="contact_zip" wire:model.defer="newContact.contact_zip" name="contact_zip" class="block w-full py-2 px-0 text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer" placeholder="e.g 1446" maxlength="4">
                        </div>
                    </div>
                    <div class="flex justify-end items-center mt-6 gap-4">
                        <button type="button" class="font-semibold text-zinc-700 px-3 py-1 rounded-md hover:text-zinc-900 transition" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="font-semibold bg-blue-900 text-white text-center px-6 py-1.5 rounded-md hover:bg-blue-950 border-blue-900 hover:text-white transition">Save</button>
                    </div>
                </form>
            `;
            $wire.dispatch('triggerModal', { title, body }); // Trigger the modal from Livewire
        });
    });
    document.addEventListener('livewire:initialized', function () {
    // Initialize Select2 with error handling
    $('.select2').select2({
        placeholder: 'Select a contact',
        allowClear: true,
        width: '100%'
    }).on('select2:open', function() {
        // Remove error styling when dropdown opens
        $(this).next('.select2-container')
            .find('.select2-selection')
            .removeClass('border-red-500 border-2');
    });

    // Listen for error events from the parent component
    Livewire.on('parentComponentErrorBag', function(data) {
        if (data.index === 'select_contact') {
            // Add error styling to Select2
            const select2Container = $('.select2-container');
            select2Container.find('.select2-selection')
                .addClass('border-red-500 border-2');
            
            // Add error message below the select
            let errorDiv = $('#select-contact-error');
            if (!errorDiv.length) {
                errorDiv = $('<div id="select-contact-error" class="text-red-500 text-sm mt-1"></div>');
                select2Container.after(errorDiv);
            }
            errorDiv.text(data.errors.contact[0]);
        }
    });

    // Clear error styling on change
    $('.select2').on('change', function() {
        $(this).next('.select2-container')
            .find('.select2-selection')
            .removeClass('border-red-500 border-2');
        $('#select-contact-error').remove();
    });
});

    </script>
@endscript