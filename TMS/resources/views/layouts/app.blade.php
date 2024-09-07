<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Taxuri') }}</title>

        <!-- Fonts -->

        <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <!-- Scripts -->
        <script defer src="https://cdn.jsdelivr.net/npm/@imacrayon/alpine-ajax@0.9.0/dist/cdn.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>

        @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
        <!-- Styles -->
        @livewireStyles
        
    </head>

    <body class="font-sans antialiased">
        <x-banner />
        <div class="w-full flex">
            {{-- Sidebar --}}
            <nav id="sidebar" class="bg-white h-screen 
            sidebar-open py-6 pr-4 border-r-2 shadow-sm overfow-hidden transition-all duration-500">
                <div class="relative flex justify-center items-center">
                    <a href="{{ route('dashboard') }}" class="text-center content">
                        <img src="https://readymadeui.com/readymadeui.svg" alt="logo" class='w-[160px]' />
                    </a>
                    <button id="toggleSidebar" class="absolute -right-8 -top-2 h-8 w-8 p-[6px] border-gray-300 cursor-pointer bg-slate-50 flex items-center justify-center transition-all rounded-full drop-shadow-md">
                        <svg id="sidebarArrow" class="w-3 h-3 transform rotate-90 transition-transform duration-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1l4 4 4-4"/>
                        </svg>
                    </button>
                </div>
                <div class="content overflow-auto py-2 h-full">
                    <ul class="space-y-1 my-8 flex-1">
                        <li class="pl-0">
                            <a href="{{ route('dashboard') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in duration-500 transition-all 
                            {{ request()->routeIs('dashboard') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" viewBox="0 0 24 24"><path fill="#172554" fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6l2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2z" clip-rule="evenodd"/></svg>
                                <span class="sidebar-text">Home</span>
                            </a>
                        </li>
                        <li class="pl-0">
                            <a href="{{ route('transactions') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in duration-500 transition-all
                            {{ request()->routeIs('transactions') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 256 256"><path fill="#172554" d="M216 40H40a16 16 0 0 0-16 16v152a8 8 0 0 0 11.58 7.15L64 200.94l28.42 14.21a8 8 0 0 0 7.16 0L128 200.94l28.42 14.21a8 8 0 0 0 7.16 0L192 200.94l28.42 14.21A8 8 0 0 0 232 208V56a16 16 0 0 0-16-16m-40 104H80a8 8 0 0 1 0-16h96a8 8 0 0 1 0 16m0-32H80a8 8 0 0 1 0-16h96a8 8 0 0 1 0 16"/></svg>
                                <span class="sidebar-text">Transactions</span>
                            </a>
                        </li>
                        <li x-data="{ open: false }">
                            <button @click="open = !open" type="button"
                                :class="{
                                    'flex items-center w-full p-2 text-[14px] ease-in transition-all duration-500 rounded-r-full hover:font-bold px-6 py-2 group': true,
                                    'text-blue-950 bg-slate-100 border-l-[8px] border-blue-950 font-bold': open,
                                    'text-gray-950 dark:text-white': !open
                                }">
                                <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 sidebar-icon ease-in transition-all"
                                :class="open ? 'text-blue-950 dark:text-white' : 'text-gray-500'"
                                aria-hidden="true" viewBox="0 0 24 24"><g fill="#172554"><path d="m12 2l.117.007a1 1 0 0 1 .876.876L13 3v4l.005.15a2 2 0 0 0 1.838 1.844L15 9h4l.117.007a1 1 0 0 1 .876.876L20 10v9a3 3 0 0 1-2.824 2.995L17 22H7a3 3 0 0 1-2.995-2.824L4 19V5a3 3 0 0 1 2.824-2.995L7 2z"/><path d="M19 7h-4l-.001-4.001z"/></g></svg>
                                <span class="flex-1 ms-3 text-left rtl:text-right text-blue-950 whitespace-nowrap">Tax Return</span>
                                <div class="flex justify-end pl-4">
                                    <svg :class="open ? 'transform rotate-0' : 'transform rotate-[-90deg]'" class="w-2.5 h-2.5 border-blue-950 transition-transform duration-200"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m1 1 4 4 4-4"/>
                                    </svg>
                                </div>
                            </button>
                            <ul x-show="open" x-transition class="py-1 space-y-1 taxuri-color">
                                <li>
                                    <a href="#" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group">
                                        Percentage Tax
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group">
                                        Income Tax Return
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group">
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
                        <li x-data="{ open: {{ request()->routeIs('general-ledger', 'sales-book', 'purchase-book', 'cash-receipt', 'cash-disb', 'general-journal') ? 'true' : 'false' }} }">
                            <button @click="open = !open" type="button"
                                :class="{
                                    'flex items-center w-full p-2 text-[14px] ease-in transition-all duration-500 rounded-r-full hover:font-bold px-6 py-2 group': true,
                                    'text-blue-950 bg-slate-100 border-l-[8px] border-blue-950 font-bold': open,
                                    'text-gray-950 dark:text-white': !open
                                }">
                                <svg xmlns="http://www.w3.org/2000/svg"class="flex-shrink-0 sidebar-icon ease-in transition-all"
                                :class="open ? 'text-blue-950 dark:text-white' : 'text-gray-500 dark:text-gray-400'"
                                aria-hidden="true" viewBox="0 0 36 36"><path fill="#172554" d="M10 5.2h18v1.55H10z" class="clr-i-solid clr-i-solid-path-1"/><path fill="#172554" d="M29 8H9.86A1.89 1.89 0 0 1 8 6a2 2 0 0 1 1.86-2H29a1 1 0 1 0 0-2H9.86A4 4 0 0 0 6 6a4 4 0 0 0 0 .49a1 1 0 0 0 0 .24V30a4 4 0 0 0 3.86 4H29a1 1 0 0 0 1-1V9.07A1.07 1.07 0 0 0 29 8" class="clr-i-solid clr-i-solid-path-2"/><path fill="none" d="M0 0h36v36H0z"/></svg>
                                <span class="flex-1 ms-3 text-left rtl:text-right text-blue-950 whitespace-nowrap">Books of Accounts</span>
                                <div class="flex justify-end pl-4">
                                    <svg :class="open ? 'transform rotate-0' : 'transform rotate-[-90deg]'" class="w-2.5 h-2.5 border-blue-950 transition-transform duration-200"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                                    {{ request()->routeIs('sales-book') ? 'sidebar-submenu-active' : '' }}">
                                        Sales Book Journal
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('purchase-book') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('purchase-book') ? 'sidebar-submenu-active' : '' }}">
                                        Purchase Book Journal
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('cash-receipt') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('cash-receipt') ? 'sidebar-submenu-active' : '' }}">
                                        Cash Receipt Journal
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('cash-disb') }}" class="flex items-center w-full p-2 text-[14px] ease-in transition-all rounded-lg pl-11 group
                                    {{ request()->routeIs('cash-disb') ? 'sidebar-submenu-active' : '' }}">
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
                            <a href="{{ route('coa') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in transition-all
                            {{ request()->routeIs('coa') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 24 24"><path fill="#172554" d="M19 17h-7c-1.103 0-2 .897-2 2s.897 2 2 2h7c1.103 0 2-.897 2-2s-.897-2-2-2m0-7h-7c-1.103 0-2 .897-2 2s.897 2 2 2h7c1.103 0 2-.897 2-2s-.897-2-2-2m0-7h-7c-1.103 0-2 .897-2 2s.897 2 2 2h7c1.103 0 2-.897 2-2s-.897-2-2-2"/><circle cx="5" cy="19" r="2.5" fill="#172554"/><circle cx="5" cy="12" r="2.5" fill="#172554"/><circle cx="5" cy="5" r="2.5" fill="#172554"/></svg>                                <span class="sidebar-text">Chart of Accounts</span>
                            </a>
                        </li>
                        <li class="pl-0">
                            <a href="{{ route('financial-reports') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in transition-all
                            {{ request()->routeIs('financial-reports') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 24 24"><g fill="none"><path fill="#172554" d="M21 7c0 2.21-4.03 4-9 4S3 9.21 3 7s4.03-4 9-4s9 1.79 9 4"/><path stroke="#172554" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 7c0 2.21-4.03 4-9 4S3 9.21 3 7m18 0c0-2.21-4.03-4-9-4S3 4.79 3 7m18 0v5M3 7v5m18 0c0 2.21-4.03 4-9 4s-9-1.79-9-4m18 0v5c0 2.21-4.03 4-9 4s-9-1.79-9-4v-5"/></g></svg>
                                <span class="sidebar-text">Financial Reports</span>
                            </a>
                        </li>
                        <li class="pl-0">
                            <a href="{{ route('predictive-analytics') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in transition-all
                            {{ request()->routeIs('predictive-analytics') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 24 24"><path fill="#172554" d="M14 9q-.425 0-.712-.288T13 8V4q0-.425.288-.712T14 3h6q.425 0 .713.288T21 4v4q0 .425-.288.713T20 9zM4 13q-.425 0-.712-.288T3 12V4q0-.425.288-.712T4 3h6q.425 0 .713.288T11 4v8q0 .425-.288.713T10 13zm10 8q-.425 0-.712-.288T13 20v-8q0-.425.288-.712T14 11h6q.425 0 .713.288T21 12v8q0 .425-.288.713T20 21zM4 21q-.425 0-.712-.288T3 20v-4q0-.425.288-.712T4 15h6q.425 0 .713.288T11 16v4q0 .425-.288.713T10 21z"/></svg>
                                <span class="sidebar-text">Predictive Analytics</span>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 24 24"><path fill="#172554" d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0a4.125 4.125 0 0 1-8.25 0m9.75 2.25a3.375 3.375 0 1 1 6.75 0a3.375 3.375 0 0 1-6.75 0M1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63a13.07 13.07 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63zm15.75.003l-.001.144a2.25 2.25 0 0 1-.233.96q.302.018.609.018c1.596 0 3.107-.37 4.451-1.029a.75.75 0 0 0 .42-.642l.004-.204a4.875 4.875 0 0 0-6.961-4.407a8.6 8.6 0 0 1 1.71 5.157z"/></svg>
                                <span class="sidebar-text">Organization Setup</span>
                            </a>
                        </li>
                        <li class="pl-0">
                            <a href="{{ route('add-user') }}"
                            class="flex items-center rounded-r-full px-6 py-2 ease-in transition-all
                            {{ request()->routeIs('add-user') ? 'sidebar-active' : '' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="sidebar-icon mr-2" viewBox="0 0 20 20"><path fill="#172554" d="M11 9V5H9v4H5v2h4v4h2v-4h4V9zm-1 11a10 10 0 1 1 0-20a10 10 0 0 1 0 20"/></svg>
                                <span class="sidebar-text">Add User Account</span>
                            </a>
                        </li>
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