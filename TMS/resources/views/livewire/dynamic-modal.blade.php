<div>
    @if($showModal)
        <div class="fixed inset-0 flex items-center justify-center z-50">
            <div class="modal-overlay absolute inset-0 bg-gray-500 opacity-75"></div>
            <div class="modal-container bg-white w-full max-w-2xl mx-auto rounded-lg shadow-lg z-50 overflow-y-auto">
                <div class="modal-header bg-blue-900 text-white flex items-center justify-center p-4 border-b relative">
                    <h2 class="text-lg font-semibold mx-auto">{{ $title }}</h2>
                    <button class="text-white absolute right-4 top-1/2 transform -translate-y-1/2" wire:click="closeModal">&times;</button>
                </div>
                <div class="modal-body p-4">
                    {!! $body !!}
                </div>
            </div>
        </div>
    @endif
</div>

@script
    <script>
        
        // Listening for the 'triggerModal' event
        $wire.on('triggerModal', (data) => {
            console.log('triggerModal event received', data);
            // Custom logic to display modal content if needed
        });

        // Handling dropdown refresh
      
        $wire.on('refreshDropdown', (data) => {
            console.log('refreshDropdown event triggered:', data);

            let optionsArray = data[0].options;
            let options = [];

            $.each(optionsArray, function(key, option){
        options.push({
            id: option.value,
            text: option.name,
            tax_id: option.tin
        });
    });

    $('#select_contact')
    .empty() // Clear existing options

    .select2({
        data: options, // New data options
        templateResult: function(data) {
            // Custom formatting for the dropdown list
            if (!data.id) {
                return data.text;
            }
            var $result = $(
                '<span>' + data.text + 
                '<small style="display:block; font-size:15px; line-height:1; margin-top:0; padding-top:0;">' + 
                data.tax_id + '</small></span>' // Assuming tax_id comes from data object
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
                '<small style="display:block; font-size:15px; line-height:1; margin-top:0; padding-top:0;">' + 
                data.tax_id + '</small></span>' // Assuming tax_id comes from data object
            );
            return $selection;
        },
        escapeMarkup: function (markup) {
            return markup; // Allow custom HTML
        }
    });

console.log('Select2 dropdown updated with new options.');
        });
        $wire.on('refreshPurchaseDropdown', (data) => {
            console.log('refreshDropdown event triggered:', data);

            let optionsArray = data[0].options;
            let options = [];

            $.each(optionsArray, function(key, option){
        options.push({
            id: option.value,
            text: option.name,
            tax_id: option.tin
        });
    });

    $('#select_contact')
    .empty() // Clear existing options

    .select2({
        data: options, // New data options
        templateResult: function(data) {
            // Custom formatting for the dropdown list
            if (!data.id) {
                return data.text;
            }
            var $result = $(
                '<span>' + data.text + 
                '<small style="display:block; font-size:15px; line-height:1; margin-top:0; padding-top:0;">' + 
                data.tax_id + '</small></span>' // Assuming tax_id comes from data object
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
                '<small style="display:block; font-size:15px; line-height:1; margin-top:0; padding-top:0;">' + 
                data.tax_id + '</small></span>' // Assuming tax_id comes from data object
            );
            return $selection;
        },
        escapeMarkup: function (markup) {
            return markup; // Allow custom HTML
        }
    });
});
        
    </script>
@endscript
