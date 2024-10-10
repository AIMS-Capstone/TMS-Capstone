<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <nav class="text-sm font-medium text-gray-600 dark:text-slate-300 mb-6" aria-label="breadcrumb">
                <ol class="flex flex-wrap items-center gap-1">
                    <li class="flex items-center gap-1">
                        <a href="{{ route('dashboard') }}" class="hover:text-black dark:hover:text-white">Home</a>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </li>
                    <li class="flex items-center gap-1">
                        <a href="{{ route('general-ledger') }}" class="hover:text-blue-950 dark:hover:text-white {{ request()->routeIs('general-ledger') ? 'breadcumb-active' : '' }}">General Ledger Listing</a>
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg> --}}
                    </li>
                    {{-- <li class="flex items-center text-black gap-1 font-bold dark:text-white" aria-current="page">Breadcrumb</li> --}}
                </ol>
            </nav>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <x-general-ledger-main />
            </div>
        </div>
    </div>
</x-app-layout>