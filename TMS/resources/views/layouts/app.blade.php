@php
$organizationId = session('organization_id');
$organization = \App\Models\OrgSetup::find($organizationId);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Taxuri') }}</title>
        <link rel="icon" href="{{ asset('images/favicon.ico') }}">

        <!-- Fonts -->

        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <!-- Scripts -->
        <script defer src="https://cdn.jsdelivr.net/npm/@imacrayon/alpine-ajax@0.9.0/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
        <!-- Styles -->
        @livewireStyles
    </head>

    <body class="font-sans antialiased">
        @php
        $organizationId = session('organization_id');
        $organization = \App\Models\OrgSetup::find($organizationId);
    @endphp

    <header>


    </header>
        <x-banner />
        <div class="w-full flex">
            {{-- Sidebar --}}
            <nav id="sidebar" class="bg-white h-screen 
            sticky top-0 z-50 sidebar-open py-6 pr-4 border-r-2 shadow-sm overfow-hidden transition-all duration-500">
                <div class="relative flex justify-center items-center">
                    <a href="{{ route('dashboard') }}" class="text-center content">
                        <img src="{{ asset('images/Taxuri Logo-name.png') }}" alt="logo" class='w-[160px]' />
                    </a>
                    <button id="toggleSidebar" class="absolute -right-8 -top-2 h-8 w-8 p-[6px] z-50 border-gray-300 cursor-pointer bg-slate-50 flex items-center justify-center transition-all rounded-full drop-shadow-md">
                        <svg id="sidebarArrow" class="w-3 h-3 transform rotate-90 transition-transform duration-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l4 4 4-4"/>
                        </svg>
                    </button>
                </div>
                <div class="content overflow-auto py-2 h-full">
                    <ul class="space-y-1 my-8 flex-1">
                        <li class="pl-0">
                            <a href="{{ route('dashboard') }}"
                            class="flex items-center rounded-r-full px-6 py-2  
                            {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 1024 1024"><path fill="#273C75" d="M946.5 505L534.6 93.4a31.93 31.93 0 0 0-45.2 0L77.5 505c-12 12-18.8 28.3-18.8 45.3c0 35.3 28.7 64 64 64h43.4V908c0 17.7 14.3 32 32 32H448V716h112v224h265.9c17.7 0 32-14.3 32-32V614.3h43.4c17 0 33.3-6.7 45.3-18.8c24.9-25 24.9-65.5-.1-90.5"/></svg>                               
                             <span class="sidebar-text ease-in duration-500 transition-all">Home</span>
                            </a>
                        </li>
                        <li class="pl-0">
                            <a href="{{ route('transactions') }}"
                            class="flex items-center rounded-r-full px-6 py-2 
                            {{ request()->routeIs('transactions') || request()->routeIs('transactions.create') || request()->routeIs('transactions.show') || request()->routeIs('transactions.edit') 
                            || request()->routeIs('transactions.upload') || request()->routeIs('receipts.uploadForm') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 256 256"><path fill="#273C75" d="M216 40H40a16 16 0 0 0-16 16v152a8 8 0 0 0 11.58 7.15L64 200.94l28.42 14.21a8 8 0 0 0 7.16 0L128 200.94l28.42 14.21a8 8 0 0 0 7.16 0L192 200.94l28.42 14.21A8 8 0 0 0 232 208V56a16 16 0 0 0-16-16m-40 104H80a8 8 0 0 1 0-16h96a8 8 0 0 1 0 16m0-32H80a8 8 0 0 1 0-16h96a8 8 0 0 1 0 16"/></svg>
                                <span class="sidebar-text ease-in duration-500 transition-all">Transactions</span>
                            </a>
                        </li>
                        <li class="pl-0">
                            <a href="{{ route('contacts', 'employees') }}"
                            class="flex items-center rounded-r-full px-6 py-2 
                            {{ request()->routeIs('contacts') || request()->routeIs('employees') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-3" viewBox="0 0 14 14"><path fill="#273C75" fill-rule="evenodd" d="M8 3a3 3 0 1 1-6 0a3 3 0 0 1 6 0m2.75 4.5a.75.75 0 0 1 .75.75V10h1.75a.75.75 0 0 1 0 1.5H11.5v1.75a.75.75 0 0 1-1.5 0V11.5H8.25a.75.75 0 0 1 0-1.5H10V8.25a.75.75 0 0 1 .75-.75M5 7c1.493 0 2.834.655 3.75 1.693v.057h-.5a2 2 0 0 0-.97 3.75H.5A.5.5 0 0 1 0 12a5 5 0 0 1 5-5" clip-rule="evenodd"/></svg>
                                <span class="sidebar-text ease-in duration-500 transition-all">Contacts</span>
                            </a>
                        </li>

                        <li x-data="{ open: {{ request()->routeIs('income_return', 'vat_return', 'percentage_return', 'percentage_return.slsp_data') ? 'true' : 'false' }} }">
                            <button @click="open = !open" type="button"
                                :class="{
                                    'flex items-center w-full p-2 text-[14px] ease-in transition-all duration-500 rounded-r-full hover:font-bold px-6 py-2 group': true,
                                    'text-blue-950 bg-slate-100 border-l-[8px] border-blue-900 font-bold': open,
                                    'text-gray-950 dark:text-white': !open
                                }">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 sidebar-icon ease-in transition-all"
                                :class="open ? 'text-blue-950 dark:text-white' : 'text-gray-500'"
                                aria-hidden="true" viewBox="0 0 24 24"><g fill="#273C75"><path d="m12 2l.117.007a1 1 0 0 1 .876.876L13 3v4l.005.15a2 2 0 0 0 1.838 1.844L15 9h4l.117.007a1 1 0 0 1 .876.876L20 10v9a3 3 0 0 1-2.824 2.995L17 22H7a3 3 0 0 1-2.995-2.824L4 19V5a3 3 0 0 1 2.824-2.995L7 2z"/><path d="M19 7h-4l-.001-4.001z"/></g></svg>
                                <span class="flex-1 ms-3 text-left rtl:text-right text-blue-950 whitespace-nowrap">Tax Return</span>
                                <div class="flex justify-end pl-4">
                                    <svg :class="open ? 'transform rotate-0' : 'transform rotate-[-90deg]'" class="w-2.5 h-2.5 border-blue-900 transition-transform duration-200"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="#273C75" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m1 1 4 4 4-4"/>
                                    </svg>
                                </div>
                            </button>
                            <ul x-show="open" x-transition class="py-1 space-y-1 taxuri-color">
                                <li>
                                    <a href="{{ route('percentage_return') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('percentage_return') || request()->routeIs('percentage_return.slsp_data') ? 'sidebar-submenu-active' : '' }}">
                                        Percentage Tax Return
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('income_return') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('income_return') ? 'sidebar-submenu-active' : '' }}">
                                        Income Tax Return
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('vat_return') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('vat_return') ? 'sidebar-submenu-active' : '' }}">
                                        Value Added Tax Return
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group">
                                        Withholding Tax Return
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group">
                                        Final Withholding Tax Return
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li x-data="{ open: {{ request()->routeIs('general-ledger', 'sales-book', 'salesPosted', 'purchase-book', 'purchasePosted', 'cash-receipt',
                            'receiptPosted', 'cash-disbursement', 'disbPosted', 'general-journal') ? 'true' : 'false' }} }">
                            <button @click="open = !open" type="button"
                                :class="{
                                    'flex items-center w-full p-2 text-[14px] ease-in transition-all duration-500 rounded-r-full hover:font-bold px-6 py-2 group': true,
                                    'text-blue-950 bg-slate-100 border-l-[8px] border-blue-900 font-bold': open,
                                    'text-gray-950 dark:text-white': !open
                                }">
                                <svg xmlns="http://www.w3.org/2000/svg"class="flex-shrink-0 sidebar-icon ease-in transition-all"
                                :class="open ? 'text-blue-950 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                                aria-hidden="true" viewBox="0 0 36 36"><path fill="#273C75" d="M10 5.2h18v1.55H10z" class="clr-i-solid clr-i-solid-path-1"/><path fill="#273C75" d="M29 8H9.86A1.89 1.89 0 0 1 8 6a2 2 0 0 1 1.86-2H29a1 1 0 1 0 0-2H9.86A4 4 0 0 0 6 6a4 4 0 0 0 0 .49a1 1 0 0 0 0 .24V30a4 4 0 0 0 3.86 4H29a1 1 0 0 0 1-1V9.07A1.07 1.07 0 0 0 29 8" class="clr-i-solid clr-i-solid-path-2"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                                <span class="flex-1 ms-3 text-left rtl:text-right text-blue-950 whitespace-nowrap">Books of Accounts</span>
                                <div class="flex justify-end pl-4">
                                    <svg :class="open ? 'transform rotate-0' : 'transform rotate-[-90deg]'" class="w-2.5 h-2.5 border-blue-900 transition-transform duration-200"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="#273C75" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m1 1 4 4 4-4"/>
                                    </svg>
                                </div>
                            </button>
                            <ul x-show="open" x-transition class="py-1 space-y-1 taxuri-color">
                                <li>
                                    <a href="{{ route('general-ledger') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('general-ledger') ? 'sidebar-submenu-active' : '' }}">
                                        General Ledger Listing
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('sales-book') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('sales-book') || request()->routeIs('salesPosted') ? 'sidebar-submenu-active' : '' }}">
                                        Sales Book Journal
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('purchase-book') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('purchase-book') || request()->routeIs('purchasePosted') ? 'sidebar-submenu-active' : '' }}">
                                        Purchase Book Journal
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('cash-receipt') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('cash-receipt') || request()->routeIs('receiptPosted') ? 'sidebar-submenu-active' : '' }}">
                                        Cash Receipt Book
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('cash-disbursement') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('cash-disbursement') || request()->routeIs('disbPosted') ? 'sidebar-submenu-active' : '' }}">
                                        Cash Disbursement Book
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('general-journal') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('general-journal') ? 'sidebar-submenu-active' : '' }}">
                                        General Journal
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="pl-0">
                            <a href="{{ route('coa', 'archive') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in transition-all
                            {{ request()->routeIs('coa') || request()->routeIs('archive') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 24 24"><path fill="#273C75" d="M19 17h-7c-1.103 0-2 .897-2 2s.897 2 2 2h7c1.103 0 2-.897 2-2s-.897-2-2-2m0-7h-7c-1.103 0-2 .897-2 2s.897 2 2 2h7c1.103 0 2-.897 2-2s-.897-2-2-2m0-7h-7c-1.103 0-2 .897-2 2s.897 2 2 2h7c1.103 0 2-.897 2-2s-.897-2-2-2"/><circle cx="5" cy="19" r="2.5" fill="#172554"/><circle cx="5" cy="12" r="2.5" fill="#172554"/><circle cx="5" cy="5" r="2.5" fill="#172554"/></svg>                                
                                <span class="sidebar-text ease-in duration-500 transition-all">Chart of Accounts</span>
                            </a>
                        </li>
                        <li class="pl-0">
                            <a href="{{ route('financial-reports') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in transition-all
                            {{ request()->routeIs('financial-reports') ? 'sidebar-active' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 24 24"><path fill="#273C75" d="M12 3.25c-5.59 0-10 2.82-10 6.46v4.58c0 3.62 4.39 6.46 10 6.46s10-2.84 10-6.46V9.72c0-3.63-4.43-6.47-10-6.47m8.5 11c0 2.69-3.89 5-8.5 5s-8.5-2.27-8.5-5V13.1c1.78 1.87 5 3 8.5 3s6.73-1.18 8.5-3z"/></svg>
                                <span class="sidebar-text ease-in duration-500 transition-all">Financial Reports</span>
                            </a>
                        </li>
                        <li class="pl-0">
                            <a href="{{ route('predictive-analytics') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in transition-all
                            {{ request()->routeIs('predictive-analytics') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 24 24"><path fill="#273C75" d="M14 9q-.425 0-.712-.288T13 8V4q0-.425.288-.712T14 3h6q.425 0 .713.288T21 4v4q0 .425-.288.713T20 9zM4 13q-.425 0-.712-.288T3 12V4q0-.425.288-.712T4 3h6q.425 0 .713.288T11 4v8q0 .425-.288.713T10 13zm10 8q-.425 0-.712-.288T13 20v-8q0-.425.288-.712T14 11h6q.425 0 .713.288T21 12v8q0 .425-.288.713T20 21zM4 21q-.425 0-.712-.288T3 20v-4q0-.425.288-.712T4 15h6q.425 0 .713.288T11 16v4q0 .425-.288.713T10 21z"/></svg>
                                <span class="sidebar-text ease-in duration-500 transition-all">Predictive Analytics</span>
                            </a>
                        </li>
                    </ul>
                    <div class="pl-4 pr-4 my-3">
                        <hr />
                    </div>
                    <ul class="space-y-1 my-8 flex-1">
                        <li class="pl-0">
                            <a href="{{ route('org-setup') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in transition-all
                            {{ request()->routeIs('org-setup') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 24 24"><path fill="#273C75" d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0a4.125 4.125 0 0 1-8.25 0m9.75 2.25a3.375 3.375 0 1 1 6.75 0a3.375 3.375 0 0 1-6.75 0M1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63a13.07 13.07 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63zm15.75.003l-.001.144a2.25 2.25 0 0 1-.233.96q.302.018.609.018c1.596 0 3.107-.37 4.451-1.029a.75.75 0 0 0 .42-.642l.004-.204a4.875 4.875 0 0 0-6.961-4.407a8.6 8.6 0 0 1 1.71 5.157z"/></svg>
                                <span class="sidebar-text ease-in duration-500 transition-all">Organization Setup</span>
                            </a>
                        </li>
                        {{-- <li class="pl-0">
                            <a href="{{ route('add-user') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in transition-all
                            {{ request()->routeIs('add-user') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 20 20"><path fill="#273C75" d="M11 9V5H9v4H5v2h4v4h2v-4h4V9zm-1 11a10 10 0 1 1 0-20a10 10 0 0 1 0 20"/></svg>
                                <span class="sidebar-text">Add User Account</span>
                            </a>
                        </li> --}}
                    </ul>
                </div>
            </nav>
            {{-- Top Navbar --}}
            <div id="main-content" class="w-full  transition-all duration-300">
                <header id="navbar" class=" bg-gray-100 transition-all duration-300">
                    @livewire('navigation-menu')

                    <!-- Page Heading -->
                    @if (isset($header))
                        <header class="bg-white shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <!-- Page Content -->
                    <main class="transition-all h-full duration-300">
                        {{ $slot }}
                    </main>
                </header>
            </div>
            
            
        </div>
        @stack('modals')

        @livewireScripts
        <script>
            document.getElementById('toggleSidebar').addEventListener('click', function () {
                const sidebar = document.getElementById('sidebar');
                const mainContent = document.getElementById('main-content');
                const navbar = document.getElementById('navbar');
                if (sidebar.classList.contains('md:min-w-[250px]')) {
                } else {
                }
            });
            document.addEventListener('DOMContentLoaded', function () {
                const sidebar = document.getElementById('sidebar');
                const toggleButton = document.getElementById('toggleSidebar');
                const arrow = document.getElementById('sidebarArrow');
                const toggleSidebarButton = document.getElementById('toggleSidebar');
                const navbar = document.getElementById('navbar');
                let sidebarOpen = false;
                toggleButton.addEventListener('click', function () {
                    if (sidebarOpen) {
                        sidebar.classList.add('sidebar-open');
                        sidebar.classList.remove('sidebar-closed');
                        // sidebar.style.transform = 'translateX(-85%)'; // Hide sidebar
                        arrow.style.transform = 'rotate(90deg)'; // open state
                    } else {
                        sidebar.classList.add('sidebar-closed');
                        sidebar.classList.remove('sidebar-open');
                        // sidebar.style.transform = 'translateX(0)'; // Show sidebar
                        arrow.style.transform = 'rotate(-90deg)'; // close state
                    }
                    sidebarOpen = !sidebarOpen;
                });
            });
            
        </script>
    </body>
</html>