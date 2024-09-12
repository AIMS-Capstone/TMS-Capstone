<x-transaction-form-section>
    @php
        $type = 'journal'; // Set type to 'journal' for journal transactions
    @endphp
    <!-- Redirection Link -->
    <x-slot:redirection>
        <a href="/transactions" class="flex items-center space-x-2 text-blue-600">
            <span>← Go back</span>
        </a>
    </x-slot:redirection>

    <!-- Form Header Title -->
    <x-slot:description>
        <h1 class="text-3xl font-bold text-blue-900">Add New Journal Entry</h1>
    </x-slot:description>

    <!-- Form Fields (Date, Reference, etc.) -->
    <x-slot:form>
        <!-- Form Header with Total Amount -->
        <div class="grid grid-cols-4 gap-4 mb-4">
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
            <div class="col-span-2 bg-blue-50 p-4 rounded-tr-sm">
                <x-transaction-label for="total_amount" :value="__('Total Amount')" />
                <x-transaction-input id="total_amount" type="text" class="mt-1 block w-full bg-white border-0" value="{{ $totalAmount }}" wire:model.defer="totalAmount" readonly />
            </div>
        </div>

        <!-- Table Section (Journal Entries) -->
        <div class="mt-0">
            <table class="table-auto w-full text-left">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 pr-5 py-2">Accounts</th>
                        <th class="px-4 py-2">Debit</th>
                        <th class="px-4 py-2">Credit</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($journalRows as $index => $row)
                        <livewire:journal-row :key="$index" :index="$index" :journal-row="$row" :type="$type" />
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Add New Line Button and Total Amount Due Section -->
        <x-slot name="after">
            <div class="flex justify-between mt-4">
                <button type="button" wire:click="addJournalRow" class="flex items-center space-x-2 text-blue-600 hover:text-blue-800">
                    <span>➕ Add New Line</span>
                </button>

                <!-- Total Amount Due Section -->
                <div class="w-96">
                    <table class="w-96 text-left">
                        <tbody>
                            <tr>
                                <td class="border-t px-4 py-2 font-bold">Total Amount Due:</td>
                                <td class="border-t px-4 py-2">{{ $totalAmountDebit }}</td>
                                <td class="border-t px-4 py-2">{{ $totalAmountCredit }}</td>
                            <td></td>
                            </tr>
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
                {{ __('Save Journal Entry') }}
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
            if (!data.id) {
                return data.text;
            }
            var $result = $('<span>' + data.text + '</span>');
            return $result;
        },
        templateSelection: function(data) {
            if (!data.id) {
                return data.text;
            }
            var $selection = $('<span>' + data.text + '</span>');
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
        e.preventDefault();
        let title = 'New Contact';
        let body = ` 
          <form wire:submit.prevent="saveJournalEntry">
            <!-- Add new contact fields here if needed -->
          </form>
        `;
        $wire.dispatch('triggerModal', { title, body }); // Trigger modal from Livewire
    });
});
</script>
@endscript
