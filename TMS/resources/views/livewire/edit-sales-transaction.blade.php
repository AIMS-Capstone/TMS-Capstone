
<x-transaction-form-section>
    @php
    $type = request()->query('type', 'sales');
    $mode = 'edit';
    @endphp
    <x-slot:redirection>
        <a href="/transactions" class="flex items-center space-x-2 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24"><g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g></svg>
            <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go back</span>
        </a>
    </x-slot:redirection>

    <x-slot:description>
        <h1 class="text-3xl font-bold text-blue-900">Edit Sale</h1>
    </x-slot:description>

    <x-slot:form>
        <div class="grid grid-cols-5 gap-6">
            <!-- Customer Field -->
            <div class="mt-5 mb-8 ml-10">
                <label for="select-contact" class="block font-bold text-sm text-blue-900">Customer</label>
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
                <x-transaction-label for="date" :value="__('Date')" />
                <x-transaction-input id="date" type="date" class="mt-1 block w-full" wire:model="date" />
            </div>

            <!-- Invoice Number Field -->
            <div class="mt-5 mb-8">
                <x-transaction-label for="inv_number" :value="__('Invoice Number')" />
                <x-transaction-input id="inv_number" type="text" class="mt-1 block w-full" wire:model="inv_number" />
            </div>

            <!-- Reference Field -->
            <div class="mt-5 mb-8">
                <x-transaction-label for="reference" :value="__('Reference')" />
                <x-transaction-input id="reference" type="text" class="mt-1 block w-full" wire:model.defer="reference" />
            </div>

            <!-- Total Amount Field (readonly) -->
            <div class="col-span-1 bg-blue-50 p-4 rounded-tr-sm">
                <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                <x-transaction-input id="total_amount" type="text" class="font-bold text-blue-900 mt-1 block w-full" value="{{ $totalAmount }}" wire:model.defer="totalAmount" readonly />
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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($taxRows as $index => $row)
                    <livewire:tax-row 
                    :index="$index" 
                    :taxRow="$row" 
                    :type="$type" 
                    :key="$index" 
                    :mode="$mode"
                />
                    @endforeach
                </tbody>
                
            </table>
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

    </script>
@endscript