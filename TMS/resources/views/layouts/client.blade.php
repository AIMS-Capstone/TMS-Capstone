@php
$organizationId = session('organization_id');
$organization = \App\Models\OrgSetup::find($organizationId);
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    <style>
        /* Ensure the footer sticks to the bottom */
        body, html {
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }
        main {
            flex: 1;
        }
        .active-link {
            background-color: #E7B900; /* Yellow background */
            color: #273C75; /* Blue text */
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

<!-- Header -->
<header class="bg-[#273C75] text-white py-4">
    <div class="container w-full flex justify-between space-x-4 items-center px-4">
        <!-- Logo and Site Name -->
        <a href="{{ route('client.dashboard') }}" class="flex items-center">
            <img src="{{ asset('images/Taxuri Logo (Client).png') }}" alt="Taxuri Logo" class="h-8 mr-2">
        </a>
        
        <!-- Navigation Links -->
        <nav class="flex items-center space-x-8">
            <a href="{{ route('client.dashboard') }}" class="nav-link hover:font-bold transition text-sm px-4 py-2 rounded-full">Dashboard</a>
            <a href="{{ route('client.forms') }}" class="nav-link hover:font-bold transition ease-in-out text-sm px-4 py-2 rounded-full">Generated Forms</a>
            <a href="{{ route('client.income_statement') }}" class="nav-link hover:font-bold transition ease-in-out text-sm px-4 py-2 rounded-full">Income Statement</a>
            <a href="{{ route('client.analytics') }}" class="nav-link hover:font-bold transition ease-in-out text-sm px-4 py-2 rounded-full">Predictive Analytics</a>
        </nav>
        
        <!-- User Info and Dropdown -->
        <div class="relative flex items-center space-x-4">
            <div class="h-8 border-l border-gray-200"></div>
            <div class="flex items-center space-x-2 cursor-pointer" id="user-menu-toggle">
                @php
                    $user = Auth::guard('client')->user();
                    $profilePhoto = $user && $user->profile_photo_path 
                                    ? asset('storage/' . $user->profile_photo_path) 
                                    : null;
                    $registrationName = $user && $user->orgSetup ? $user->orgSetup->registration_name : 'Organization';
                    $firstLetter = strtoupper(substr($registrationName, 0, 1));
                @endphp
            
                @if($profilePhoto)
                    <img src="{{ $profilePhoto }}" 
                         alt="User Icon" class="h-8 w-8 rounded-full">
                @else
                    <div class="h-8 w-8 rounded-full bg-[#E7B900] flex items-center justify-center text-[#273C75] font-bold">
                        {{ $firstLetter }}
                    </div>
                @endif
                
                <div class="flex flex-col">
                    <span class="text-sm font-semibold">
                        {{ $registrationName }}
                    </span>
                    <span class="text-xs text-white">Client</span>
                </div>
                <i class="fas fa-chevron-down text-yellow-400"></i>
            </div>
            <!-- Dropdown Menu -->
            <div class="ms-3 relative py-2">
                <div id="user-menu" class="hidden absolute right-0 top-full mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg py-2 px-3 z-50">
                    <a href="{{ route('client.profile') }}" class="block px-4 py-2 text-sm hover-dropdown group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 me-2" viewBox="0 0 24 24">
                            <g fill="none">
                                <path class="group-hover:fill-blue-950" d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/>
                                <path class="fill-zinc-600 group-hover:fill-blue-950" d="M16 14a5 5 0 0 1 4.995 4.783L21 19v1a2 2 0 0 1-1.85 1.995L19 22H5a2 2 0 0 1-1.995-1.85L3 20v-1a5 5 0 0 1 4.783-4.995L8 14zM12 2a5 5 0 1 1 0 10a5 5 0 0 1 0-10"/>
                            </g>
                        </svg>My Profile
                    </a>

                    <div class="border-t border-gray-200 mx-8 my-2"></div>

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm hover-dropdown group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4 me-2" viewBox="0 0 24 24">
                            <path class="fill-zinc-600 group-hover:fill-blue-950" fill-rule="evenodd" d="M6 2a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5a3 3 0 0 0-3-3zm10.293 5.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-4 4a1 1 0 0 1-1.414-1.414L18.586 13H10a1 1 0 1 1 0-2h8.586l-2.293-2.293a1 1 0 0 1 0-1.414" clip-rule="evenodd"/>
                        </svg>Logout
                    </a>
                    <form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>

<!-- Main Content -->
<main class="container mx-auto py-8 px-4">
    {{ $slot }}
</main>

<!-- Footer -->
<footer class="bg-[#CFD3E5] pt-6 ">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-4 text-justify p-6">
        <!-- Taxuri Section (50% of container) -->
        <div class="ps-10">
            <h3 class="font-extrabold text-3xl text-blue-900 mb-1">TAXURI</h3>
            <p class="text-sm text-zinc-600">
                Taxuri is a Taxation Management System developed exclusively for De Guzman, Pascual, and Associates, integrating predictive analytics to optimize tax preparation and compliance. Designed to support both the accounting firm and its clients, Taxuri ensures efficient pre-filing processes and tracks crucial tax deadlines. This streamlined solution empowers users to manage their tax obligations with greater accuracy and peace of mind.
            </p>
        </div>
        
        <!-- Site Links and Follow Us (Split within the second half) -->
        <div class="ps-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Site Links -->
            <div>
                <h3 class="font-bold text-lg text-blue-900 mb-2">SITE LINKS</h3>
                <ul class="text-sm text-zinc-600 space-y-1">
                    <li><a href="{{ route('client.dashboard') }}" class="hover:underline">Dashboard</a></li>
                    <li><a href="{{ route('client.forms') }}" class="hover:underline">Generated Forms</a></li>
                    <li><a href="{{ route('client.income_statement') }}" class="hover:underline">Income Statement</a></li>
                    <li><a href="{{ route('client.analytics') }}" class="hover:underline">Predictive Analytics</a></li>
                </ul>
            </div>
            
            <!-- Follow Us and Contact Us -->
            <div class="-ps-10">
                <h3 class="font-bold text-lg text-blue-900 mb-2">FOLLOW US</h3>
                <p class="text-sm text-zinc-600 mb-4">
                    <a href="https://www.facebook.com/dgpacpas" target="_blank" class="flex items-center space-x-2 hover:text-blue-600">
                        <i class="fab fa-facebook-square text-zinc-600 text-lg"></i>
                        <span>De Guzman, Pascual & Associates, CPAs</span>
                    </a>
                </p>
                <h3 class="font-bold text-lg text-blue-900 mb-2">CONTACT US</h3>
                <p class="text-sm text-zinc-600 flex items-center space-x-2">
                    <i class="fas fa-phone-alt text-zinc-600"></i>
                    <span>0919 001 8952 | (044) 530 8561</span>
                </p>
                <p class="text-sm text-zinc-600 flex items-center space-x-2">
                    <i class="fas fa-envelope text-zinc-600"></i>
                    <a href="mailto:askdgp@dgpcpa.org" class="text-zinc-600 hover:underline hover:text-blue-600">askdgp@dgpcpa.org</a>
                </p>
            </div>
        </div>

    </div>
    <!-- Copyright Section -->
    <div class="mt-6 text-center text-sm text-[#CFD3E5] bg-[#666E84] py-4">
        © {{ date('Y') }} Taxuri | All Rights Reserved
    </div>
</footer>

@livewireScripts

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const links = document.querySelectorAll(".nav-link");
        const currentUrl = window.location.href;

        links.forEach(link => {
            // Check if the current URL includes the link's href
            if (currentUrl.includes(link.href)) {
                link.classList.add("active-link");
            } else {
                link.classList.remove("active-link");
            }
        });

        // Toggle the dropdown menu
        const userMenuToggle = document.getElementById("user-menu-toggle");
        const userMenu = document.getElementById("user-menu");

        userMenuToggle.addEventListener("click", function () {
            userMenu.classList.toggle("hidden");
        });

        // Close the dropdown when clicking outside of it
        document.addEventListener("click", function (event) {
            if (!userMenuToggle.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add("hidden");
            }
        });
    });
</script>

</body>
</html>
