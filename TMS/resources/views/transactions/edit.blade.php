<x-app-layout>
    
    <livewire:dynamic-modal />
    @php

    $transaction = $transaction; // Get the transaction passed from the controller
   
@endphp

    @if($type === 'Sales')
    <livewire:edit-sales-transaction :transactionId="$transaction->id" />
        @elseif($type === 'Purchase')
        <livewire:edit-purchase-transaction :transactionId="$transaction->id" />

        @elseif($type === 'Journal')
        <livewire:edit-journal-transaction  :transactionId="$transaction->id" />
    @else
        <div>
            <p>Select a transaction type to proceed.</p>
            <!-- Links or buttons to navigate -->
            <a href="?type=sales" class="btn btn-primary">Sales Transaction</a>
            <a href="?type=purchase" class="btn btn-secondary">Purchase Transaction</a>
        </div>
    @endif
</x-app-layout>
