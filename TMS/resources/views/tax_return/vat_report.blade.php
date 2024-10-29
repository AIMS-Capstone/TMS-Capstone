<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Navigation Tabs -->
                    <nav class="flex space-x-4 mb-6 border-b border-gray-200 pb-2">
                        <a href="{{ route('tax_return.slsp_data', $taxReturn->id) }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('tax_return.slsp_data') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            SLSP Data
                        </a>
                        <a href="{{ route('summary') }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('summary') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            Summary
                        </a>
                        <a href="{{ route('tax_return.report', $taxReturn->id) }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('tax_return.report') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                           Report
                        </a>
                        <a href="{{ route('notes_activity') }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('notes_activity') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            Notes & Activity
                        </a>
                        <a href="{{ route('transactions') }}" class="text-gray-600 hover:text-blue-500 {{ request()->routeIs('transactions') ? 'border-b-2 border-blue-500' : '' }} px-3 py-2">
                            Transactions
                        </a>
                    </nav>

                    <h1>VAT Report for {{ $taxReturn->name }}</h1>
                    <iframe src="{{ $pdfPath }}" width="100%" height="600px"></iframe>

</x-app-layout>
