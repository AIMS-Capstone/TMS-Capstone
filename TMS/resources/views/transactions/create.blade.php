<x-app-layout>
    
    <livewire:dynamic-modal />

    @if(request()->query('type') == 'sales')
        <livewire:sales-transaction />
    @elseif(request()->query('type') == 'purchase')
        <livewire:purchase-transaction />
        @elseif(request()->query('type') == 'journal')
        <livewire:journal-transaction />
    @else
        <div>
            <p>Select a transaction type to proceed.</p>
            <!-- Links or buttons to navigate -->
            <a href="?type=sales" class="btn btn-primary">Sales Transaction</a>
            <a href="?type=purchase" class="btn btn-secondary">Purchase Transaction</a>
        </div>
    @endif
</x-app-layout>
