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
        {{-- For datepicker --}}
        <script src="https://cdn.jsdelivr.net/npm/flowbite@2.5.1/dist/flowbite.min.js"></script>
        {{-- For accordion --}}
        <script src="https://unpkg.com/flowbite@1.4.1/dist/flowbite.js"></script>

        <script defer src="https://cdn.jsdelivr.net/npm/@imacrayon/alpine-ajax@0.9.0/dist/cdn.min.js"></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



        @vite(['resources/css/app.css', 'resources/css/custom.css', 'resources/js/app.js'])
        <!-- Styles -->
        @livewireStyles
        
    </head>

    <body class="font-sans antialiased">
        <x-banner />
        <div class="w-full flex">
            
            {{-- Top Navbar --}}
            <div id="main-content" class="w-full  transition-all duration-300">
                <header id="navbar" class=" bg-gray-100 transition-all duration-300">
                    {{-- @livewire('navigation-menu') --}}
                    {{-- ORG SETUP AND USER MANAGEMENT NAVIGATION MENU --}}
                    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
                        <!-- Primary Navigation Menu -->
                        <div class="max-w-full mx-auto px-4 h-[70px] sm:px-6 lg:px-8">
                            <div class="flex justify-between h-[70px]">
                                <div class="flex">
                                    <!-- Taxuri Logo -->
                                    <div class="shrink-0 flex items-center">
                                        <img src="{{ asset('images/Taxuri Logo-name.png') }}" alt="logo" class='w-[160px]' />
                                    </div>
                                    
                                </div>
                                <div class="hidden sm:flex sm:items-center sm:ms-6">
                                    <!-- Teams Dropdown -->
                                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                        <div class="ms-3 relative">
                                            <x-dropdown align="right" width="60">
                                                <x-slot name="trigger">
                                                    <span class="inline-flex rounded-md">
                                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                            {{ Auth::user()->currentTeam->name }}
                    
                                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                            </svg>
                                                        </button>
                                                    </span>
                                                </x-slot>
                    
                                                <x-slot name="content">
                                                    <div class="w-60">
                                                        <!-- Team Management -->
                                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                                            {{ __('Manage Team') }}
                                                        </div>
                    
                                                        <!-- Team Settings -->
                                                        <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                                            {{ __('Team Settings') }}
                                                        </x-dropdown-link>
                    
                                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                                            <x-dropdown-link href="{{ route('teams.create') }}">
                                                                {{ __('Create New Team') }}
                                                            </x-dropdown-link>
                                                        @endcan
                    
                                                        <!-- Team Switcher -->
                                                        @if (Auth::user()->allTeams()->count() > 1)
                                                            <div class="border-t border-gray-200"></div>
                    
                                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                                {{ __('Switch Teams') }}
                                                            </div>
                    
                                                            @foreach (Auth::user()->allTeams() as $team)
                                                                <x-switchable-team :team="$team" />
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </x-slot>
                                            </x-dropdown>
                                        </div>
                                    @endif
                                    <div class="h-8 border-l border-gray-200"></div>
                                    <!-- Settings Dropdown -->
                                    <div class="ms-3 relative py-2 px-2">
                                        <x-dropdown align="right" class="w-44 py-2 px-2">
                                            <x-slot name="trigger">
                                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                                    <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                                        <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                                    </button>
                                                @else
                                                    <span class="inline-flex rounded-md">
                                                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                            {{ Auth::user()->first_name }}
                    
                                                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                            </svg>
                                                        </button>
                                                    </span>
                                                @endif
                                            </x-slot>
                                            
                                            <x-slot name="content">
                                                <!-- Account Management -->
                                                <x-dropdown-nav href="{{ route('profile.show') }}" class="mt-1 group">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 me-2" viewBox="0 0 24 24">
                                                        <g fill="none">
                                                            <path class="group-hover:fill-blue-950" d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/>
                                                            <path class="fill-zinc-600 group-hover:fill-blue-950" d="M16 14a5 5 0 0 1 4.995 4.783L21 19v1a2 2 0 0 1-1.85 1.995L19 22H5a2 2 0 0 1-1.995-1.85L3 20v-1a5 5 0 0 1 4.783-4.995L8 14zM12 2a5 5 0 1 1 0 10a5 5 0 0 1 0-10"/>
                                                        </g>
                                                    </svg>
                                                    <span>{{ __('My Profile') }}</span>
                                                </x-dropdown-nav>
                    
                                                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                                    <x-dropdown-nav href="{{ route('api-tokens.index') }}">
                                                        {{ __('API Tokens') }}
                                                    </x-dropdown-nav>
                                                @endif
                    
                                                @if (Auth::user()->role === 'Admin')
                                                <x-dropdown-nav href="{{ route('user-management.user') }}" class="group">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 me-2" viewBox="0 0 16 16"><path class="fill-zinc-600 group-hover:fill-blue-950" d="M7 14s-1 0-1-1s1-4 5-4s5 3 5 4s-1 1-1 1zm4-6a3 3 0 1 0 0-6a3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5a2.5 2.5 0 0 0 0 5"/></svg>
                                                    <span>{{ __('User Management') }}</span>
                                                </x-dropdown-nav>
                    
                                                <x-dropdown-nav href="{{ route('recycle-bin.organization.index') }}" class="group">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 me-2" viewBox="0 0 24 24">
                                                        <path class="fill-zinc-600 group-hover:fill-blue-950" d="M20 6a1 1 0 0 1 .117 1.993L20 8h-.081L19 19a3 3 0 0 1-2.824 2.995L16 22H8c-1.598 0-2.904-1.249-2.992-2.75l-.005-.167L4.08 8H4a1 1 0 0 1-.117-1.993L4 6zm-6-4a2 2 0 0 1 2 2a1 1 0 0 1-1.993.117L14 4h-4l-.007.117A1 1 0 0 1 8 4a2 2 0 0 1 1.85-1.995L10 2z"/>
                                                    </svg>
                                                    <span>{{ __('Recycle Bin') }}</span>
                                                </x-dropdown-nav>

                                                <x-dropdown-nav href="{{ route('audit_log.index') }}" class="group">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 me-2" viewBox="0 0 24 24">
                                                        <path class="fill-zinc-600 group-hover:fill-blue-950" d="M20 6a1 1 0 0 1 .117 1.993L20 8h-.081L19 19a3 3 0 0 1-2.824 2.995L16 22H8c-1.598 0-2.904-1.249-2.992-2.75l-.005-.167L4.08 8H4a1 1 0 0 1-.117-1.993L4 6zm-6-4a2 2 0 0 1 2 2a1 1 0 0 1-1.993.117L14 4h-4l-.007.117A1 1 0 0 1 8 4a2 2 0 0 1 1.85-1.995L10 2z"/>
                                                    </svg>
                                                    <span>{{ __('Audit Log') }}</span>
                                                </x-dropdown-nav>
                                                @endif
                    
                                                <div class="border-t border-gray-200 mx-8 my-2"></div>
                    
                                                <!-- Authentication -->
                                                <form method="POST" action="{{ route('logout') }}" x-data>
                                                    @csrf
                                                    <x-dropdown-nav href="{{ route('logout') }}" @click.prevent="$root.submit();" class="group mb-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 me-2" viewBox="0 0 24 24">
                                                            <path class="fill-zinc-600 group-hover:fill-blue-950" fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span>{{ __('Log Out') }}</span>
                                                    </x-dropdown-nav>
                                                </form>
                                            </x-slot>
                                        </x-dropdown>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Responsive Navigation Menu -->
                        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                            <div class="pt-2 pb-3 space-y-1">
                                <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                                    {{ __('Dashboard') }}
                                </x-responsive-nav-link>
                            </div>
                    
                            <!-- Responsive Settings Options -->
                            <div class="pt-4 pb-1 border-t border-gray-200">
                                <div class="flex items-center px-4">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <div class="shrink-0 me-3">
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        </div>
                                    @endif
                    
                                    <div>
                                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                    
                                <div class="mt-3 space-y-1">
                                    <!-- Account Management -->
                                    <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                                        {{ __('Profile') }}
                                    </x-responsive-nav-link>
                    
                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                        <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                                            {{ __('API Tokens') }}
                                        </x-responsive-nav-link>
                                    @endif
                    
                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                    
                                        <x-responsive-nav-link href="{{ route('logout') }}"
                                                       @click.prevent="$root.submit();">
                                            {{ __('Log Out') }}
                                        </x-responsive-nav-link>
                                    </form>
                    
                                    <!-- Team Management -->
                                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                                        <div class="border-t border-gray-200"></div>
                    
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Manage Team') }}
                                        </div>
                    
                                        <!-- Team Settings -->
                                        <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                                            {{ __('Team Settings') }}
                                        </x-responsive-nav-link>
                    
                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                                                {{ __('Create New Team') }}
                                            </x-responsive-nav-link>
                                        @endcan
                    
                                        <!-- Team Switcher -->
                                        @if (Auth::user()->allTeams()->count() > 1)
                                            <div class="border-t border-gray-200"></div>
                    
                                            <div class="block px-4 py-2 text-xs text-gray-400">
                                                {{ __('Switch Teams') }}
                                            </div>
                    
                                            @foreach (Auth::user()->allTeams() as $team)
                                                <x-switchable-team :team="$team" component="responsive-nav-link" />
                                            @endforeach
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </nav>
                    
                   
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
    </body>
</html>