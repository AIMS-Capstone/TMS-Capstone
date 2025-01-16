
<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                {{-- Breadcrumbs --}}
                <div class="px-10 py-6">
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                            <li class="inline-flex items-center text-sm font-normal text-zinc-500">Corporate Income Tax Return</li>
                            <li>
                                <div class="flex items-center">
                                    <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                    </svg>
                                    <a href="{{ route('tax_return.corporate_quarterly_pdf', $taxReturn) }}" 
                                        class="ms-1 text-sm font-medium {{ Request::routeIs('tax_return.corporate_quarterly_pdf') ? 'font-bold text-blue-900' : 'text-zinc-500' }} md:ms-2">
                                        1702Q
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
                            <a href="{{ route('tax_return.corporate_quarterly_pdf', $taxReturn) }}" 
                               class="flex h-min items-center gap-2 px-4 py-2 text-sm whitespace-nowrap {{ request()->routeIs('tax_return.corporate_quarterly_pdf') ? 'font-bold bg-slate-100 text-blue-900 rounded-lg' : 'text-zinc-600 font-medium hover:text-blue-900' }}">
                                Report
                            </a>
                        </nav>

                        <!-- Buttons -->
                        <div class="flex space-x-4">
                            <a href="{{ route('tax_return.corporate_quarterly.edit', $taxReturn) }}" 
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
                        <iframe src="{{ route('tax_return.corporate_quarterly.stream', ['tax_return_id' => $taxReturn->id]) }}" width="100%" height="600px" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div 
    x-data="{ 
        showSuccessModal: {{ session()->has('success') ? 'true' : 'false' }} 
    }"
    x-show="showSuccessModal" 
    x-cloak 
    class="fixed inset-0 z-50 flex items-center justify-center"
    x-effect="document.body.classList.toggle('overflow-hidden', showSuccessModal)"
>
    <div class="fixed inset-0 bg-gray-200 opacity-50"></div>

    <div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative" 
        x-show="showSuccessModal" 
        x-transition:enter="transition ease-out duration-300 transform" 
        x-transition:enter-start="opacity-0 scale-90" 
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200 transform" 
        x-transition:leave-start="opacity-100 scale-100" 
        x-transition:leave-end="opacity-0 scale-90"
    >
        <button @click="showSuccessModal = false" 
            class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="flex flex-col items-center">
            <!-- Icon -->
            <div class="flex justify-center align-middle mb-4">
                <img src="{{ asset('images/Success.png') }}" alt="Item(s) Posted" class="w-28 h-28">
            </div>

            <!-- Title -->
            <h2 class="text-2xl font-bold text-emerald-500 mb-4">Tax Return  Generated</h2>

            <!-- Description -->
            <p class="text-sm text-zinc-600 text-center mb-6">
                The 1702Q Quarterly Income Tax Return has been successfully generated.
            </p>
        </div>
    </div>
</div>


<div 
x-data="{ 
    showSuccessModal2: {{ session()->has('success2') ? 'true' : 'false' }} 
}"
x-show="showSuccessModal2" 
x-cloak 
class="fixed inset-0 z-50 flex items-center justify-center"
x-effect="document.body.classList.toggle('overflow-hidden', showSuccessModal)"
>
<div class="fixed inset-0 bg-gray-200 opacity-50"></div>

<div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative" 
    x-show="showSuccessModal2" 
    x-transition:enter="transition ease-out duration-300 transform" 
    x-transition:enter-start="opacity-0 scale-90" 
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200 transform" 
    x-transition:leave-start="opacity-100 scale-100" 
    x-transition:leave-end="opacity-0 scale-90"
>
    <button @click="showSuccessModal2 = false" 
        class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <div class="flex flex-col items-center">
        <!-- Icon -->
        <div class="flex justify-center align-middle mb-4">
            <img src="{{ asset('images/Success.png') }}" alt="Item(s) Posted" class="w-28 h-28">
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-emerald-500 mb-4">Tax Return Modified</h2>

        <!-- Description -->
        <p class="text-sm text-zinc-600 text-center mb-6">
            The 1702Q Quarterly Income Tax Return has been successfully edited.
        </p>
    </div>
</div>
</div>
<div 
x-data="{ 
    showSuccessModalMark: {{ session()->has('successMark') ? 'true' : 'false' }} 
}"
x-show="showSuccessModalMark" 
x-cloak 
class="fixed inset-0 z-50 flex items-center justify-center"
x-effect="document.body.classList.toggle('overflow-hidden', showSuccessModal)"
>
<div class="fixed inset-0 bg-gray-200 opacity-50"></div>

<div class="bg-white rounded-lg shadow-lg p-8 max-w-sm w-full relative" 
    x-show="showSuccessModalMark" 
    x-transition:enter="transition ease-out duration-300 transform" 
    x-transition:enter-start="opacity-0 scale-90" 
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200 transform" 
    x-transition:leave-start="opacity-100 scale-100" 
    x-transition:leave-end="opacity-0 scale-90"
>
    <button @click="showSuccessModalMark = false" 
        class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
    <div class="flex flex-col items-center">
        <!-- Icon -->
        <div class="flex justify-center align-middle mb-4">
            <img src="{{ asset('images/Success.png') }}" alt="Item(s) Posted" class="w-28 h-28">
        </div>

        <!-- Title -->
        <h2 class="text-2xl font-bold text-emerald-500 mb-4">Marked as Filed</h2>

        <!-- Description -->
        <p class="text-sm text-zinc-600 text-center mb-6">
            The Tax Return has been successfully marked as filed.
        </p>
    </div>
</div>
</div>
</x-app-layout>