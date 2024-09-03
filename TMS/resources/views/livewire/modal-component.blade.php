<div>
    <button wire:click="openModal" class="bg-blue-500 text-white px-4 py-2">Open Modal</button>

    @if($isOpen)
    <div class="fixed inset-0 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-xl font-bold">Modal Title</h2>
            <p class="mt-4">This is the modal content.</p>
            <button wire:click="closeModal" class="bg-red-500 text-white px-4 py-2 mt-4">Close Modal</button>
        </div>
        <div class="fixed inset-0 bg-gray-900 opacity-50"></div>
    </div>
    @endif
</div>

<script>
    $wire.on('refresh', (data) => {
       console.log('refreshDropdown event triggered:', data);

       let options = [];

       // Assuming data.options is the array passed from the Livewire component
       $.each(data.options, function(key, option){
           options.push({
               id: option.value,
               text: option.name
           });
       });

       // Update the Select2 dropdown
       $('#' + data.id)
           .empty()
           .append('<option selected value="null">Select parent</option>')
           .select2({
               data: options
           });

       console.log('Select2 dropdown updated with new options.');
       });

   </script>