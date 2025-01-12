<!-- resources/views/tax/1701q_report.blade.php -->
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                {{-- Breadcrumbs --}}
                <div class="px-10 py-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">Income Tax Return</li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ route('income_return') }}" 
                                        class="ms-1 text-sm font-medium {{ Request::routeIs('income_return') ? 'font-bold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                        1701Q
                                    </a>
                                </div>
                            </li>
                            <li aria-current="page">
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <span class="ms-1 text-sm font-bold text-blue-900 md:ms-2">Report</span>
                                </div>
                            </li>
                        </ol>
                    </nav>
                </div>

                <hr>
                <div class="container mx-auto">
                    <div class="flex justify-between items-center px-8 ps-10 my-4">
                        <!-- Navigation Tabs -->
                        <nav class="flex space-x-4">
                            <a href="{{ route('tax_return.income_input_summary', $taxReturn->id) }}" 
                               class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('tax-returns.income-summary') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }}">
                                Summary
                            </a>
                            <a href="{{ route('income_return.reportPDF', $taxReturn->id) }}" 
                               class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('income_return.reportPDF') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }}">
                                Report
                            </a>
                        </nav>

                        <!-- Buttons -->
                        <div class="flex space-x-4">
                            <a href="" 
                               class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600">
                                Edit Report
                            </a>
                            <a href="" 
                               class="px-4 py-2 text-sm font-medium text-white bg-green-500 rounded-lg hover:bg-green-600">
                                Download PDF
                            </a>
                        </div>
                    </div>

                    <div class="mb-12 mt-6 mx-12 overflow-hidden max-w-full">
                        <iframe src="{{ route('income_return.stream', $taxReturn->id) }}" width="100%" height="600px" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>