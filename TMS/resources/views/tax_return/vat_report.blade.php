<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Navigation Tabs -->
                    <nav class="flex space-x-4 my-4">
                        <a href="{{ route('tax_return.slsp_data', $taxReturn->id) }}" class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('tax_return.slsp_data') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                            SLSP Data
                        </a>
                        <a href="{{ route('tax_return.summary', $taxReturn->id) }}" class="text-zinc-600 flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('summary') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                            Summary
                        </a>
                        <a href="{{ route('tax_return.report', $taxReturn->id) }}" class="text-zinc-600 flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('tax_return.report') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                            Report
                        </a>
                        <a href="{{ route('notes_activity') }}" class="text-zinc-600 flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('notes_activity') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                            Notes & Activity
                        </a>
                    </nav>

                    <h1>VAT Report for {{ $taxReturn->name }}</h1>
                    <iframe src="{{ $pdfPath }}" width="100%" height="600px"></iframe>

</x-app-layout>
