<x-app-layout>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
                <x-transaction-main 
                :transactions="$transactions"
                :purchaseCount="$purchaseCount" 
                :salesCount="$salesCount"
                :journalCount="$journalCount"
                :allTransactionsCount="$allTransactionsCount" />
            </div>
        </div>
    </div>
    
</x-app-layout>
