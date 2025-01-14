<div x-data="{ showModal: @entangle('showModal') }" 
     x-cloak 
     x-show="showModal" 
     x-effect="document.body.classList.toggle('overflow-hidden', showModal)" 
     class="fixed inset-0 flex items-center justify-center z-50">

    <div class="absolute inset-0 bg-gray-500 opacity-60"></div>

    <div 
        x-show="showModal" 
        x-transition:enter="transition ease-out duration-300" 
        x-transition:enter-start="opacity-0 transform scale-90" 
        x-transition:enter-end="opacity-100 transform scale-100" 
        x-transition:leave="transition ease-in duration-200" 
        x-transition:leave-start="opacity-100 transform scale-100" 
        x-transition:leave-end="opacity-0 transform scale-90" 
        class="modal-container bg-white w-full max-w-2xl mx-auto rounded-lg shadow-lg z-50 overflow-hidden max-h-[90vh]"
    >
        <div class="modal-header bg-blue-900 text-white flex items-center justify-center p-4 border-b relative">
            <h2 class="text-lg font-semibold mx-auto">{{ $title }}</h2>
            <button @click="showModal = false" class="text-white absolute right-4 top-1/2 transform -translate-y-1/2">&times;</button>
        </div>
        
        <div class="modal-body p-4 max-h-[70vh] overflow-y-auto">
            {!! $body !!}
        </div>
    </div>
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
