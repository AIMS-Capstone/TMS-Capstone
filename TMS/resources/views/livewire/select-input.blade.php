<div class="form-group" wire:ignore>
    <select 
        name="{{ $name }}" 
        class="select2 {{ $class }}" 
        id="{{ $id }}"
        @if($isGrouped) data-grouped="true" @endif
    >
        @foreach($options as $option)
            @if($isGrouped && isset($option['label']))
                <optgroup label="{{ $option['label'] }}">
                    @foreach($option['options'] as $item)
                        <option value="{{ $item[$valueKey] }}" 
                                @if(isset($item['tax_identification_number']) && $item['tax_identification_number']) 
                                    data-tax-id="{{ $item['tax_identification_number'] }}"
                                @endif
                        >
                            {{ $item[$labelKey] }}
                        </option>
                    @endforeach
                </optgroup>
            @else
                <option value="{{ $option[$valueKey] }}" 
                        @if(isset($option['tax_identification_number']) && $option['tax_identification_number']) 
                            data-tax-id="{{ $option['tax_identification_number'] }}"
                        @endif
                >
                    {{ $option[$labelKey] }}
                </option>
            @endif
        @endforeach
    </select>
</div>

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
                    if (!data.id) {
                        return data.text;
                    }
                    var $result = $('<span>' + data.text + '<br><small>' + $(data.element).data('tax-id') + '</small></span>');
                    return $result;
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
    

            // Add a click event listener for the "Use it anyway" button
            $(document).on('click', '.use-anyway-btn', function(e) {
                e.preventDefault(); // Prevent default action
                let title = 'New Contact';
                let body = `
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-3">
                            <div class="mb-4 col-span-1 mr-2">
                                <label for="contact_type" class="block text-gray-700">Contact Type</label>
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
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
                        </div>
                    </form>
                `;
                $wire.dispatch('triggerModal', { title, body }); // Trigger the modal from Livewire
            });
        });

     
    </script>
@endscript
