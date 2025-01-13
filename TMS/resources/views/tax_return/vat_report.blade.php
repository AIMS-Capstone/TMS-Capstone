<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                {{-- Breadcrumbs --}}
                <div class="px-10 py-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">
                            Value Added Tax Return
                            </li>
                            <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('vat_return') }}" 
                                    class="ms-1 text-sm font-medium {{ Request::routeIs('vat_return') ? 'font-bold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                    2550Q
                                </a>
                            </div>
                            </li>
                            <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="" class="ms-1 text-sm font-bold text-blue-900 md:ms-2">Report</a>
                            </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <hr>

                <div class="container mx-auto">
                    <div class="flex justify-between items-center px-8 ps-10 my-4">
                        <!-- Navigation Tabs -->
                        <nav class="flex space-x-4 my-4">
                            <a href="{{ route('tax_return.slsp_data', $taxReturn->id) }}" class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('tax_return.slsp_data') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                                SLSP Data
                            </a>
                            <a href="{{ route('tax_return.summary', $taxReturn->id) }}" class="text-zinc-600 flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('summary') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }} px-3 py-2">
                                Summary
                            </a>
                            <a href="{{ route('tax_return.2550q.pdf', $taxReturn->id) }}" class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('tax_return.2550q.pdf') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }}">
                                Report
                            </a>
                        </nav>
                        <div class="flex space-x-4">
                            <a href="{{route('tax_return.2550Q.edit', $taxReturn->id)}}" 
                               class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                                Edit Report
                            </a>
                            <form action="{{ route('tax-return.mark-filed', $taxReturn->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-lg hover:bg-green-600">
                                    File Report
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="mb-12 mt-6 mx-12 overflow-hidden max-w-full">
                        <iframe src="{{ $pdfPath }}" width="100%" height="600px"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
