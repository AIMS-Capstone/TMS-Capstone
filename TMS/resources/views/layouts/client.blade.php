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
            background-color: #fbbf24; /* Yellow background */
            color: #1e3a8a; /* Blue text */
            font-weight: bold;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

<!-- Header -->
<header class="bg-blue-900 text-white py-4">
    <div class="container mx-4 flex justify-between items-center px-4">
        <!-- Logo and Site Name -->
        <a href="{{ route('client.dashboard') }}" class="flex items-center">
            <img src="{{ asset('images/Taxuri Logo (Client).png') }}" alt="Taxuri Logo" class="h-8 mr-2">
        </a>
        
        <!-- Navigation Links -->
        <nav class="flex items-center space-x-6">
            <a href="{{ route('client.dashboard') }}" class="nav-link px-4 py-1 rounded-full">Dashboard</a>
            <a href="{{ route('client.forms') }}" class="nav-link px-4 py-1 rounded-full">Generated Forms</a>
            <a href="{{ route('client.income_statement') }}" class="nav-link px-4 py-1 rounded-full">Income Statement</a>
            <a href="{{ route('client.analytics') }}" class="nav-link px-4 py-1 rounded-full">Predictive Analytics</a>
        </nav>
        
        <!-- User Info and Dropdown -->
        <div class="relative flex items-center space-x-4">
            <div class="h-8 border-l border-gray-200"></div>
            <div class="flex items-center space-x-2 cursor-pointer" id="user-menu-toggle">
                <img src="{{ Auth::guard('client')->user() && Auth::guard('client')->user()->profile_photo_path ? asset('storage/' . Auth::guard('client')->user()->profile_photo_path) : asset('images/user-icon.png') }}" alt="User Icon" class="h-8 w-8 rounded-full">
                <span class="text-sm font-semibold">
                    {{ Auth::guard('client')->user() && Auth::guard('client')->user()->orgSetup ? Auth::guard('client')->user()->orgSetup->registration_name : 'Organization' }}
                </span>
                <i class="fas fa-chevron-down text-yellow-400"></i>
            </div>
            <!-- Dropdown Menu -->
            <div id="user-menu" class="hidden absolute right-0 mt-10 w-48 bg-white text-gray-800 rounded-lg shadow-lg py-2 z-50">
                <a href="{{ route('client.profile') }}" class="block px-4 py-2 hover:bg-gray-100">My Profile</a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                <form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>

    </div>
</header>

<!-- Main Content -->
<main class="container mx-auto py-8 px-4">
    {{ $slot }}
</main>

<!-- Footer -->
<footer class="bg-gray-300 pt-6 ">
    <div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-4 text-justify p-6">
        <!-- Taxuri Section (50% of container) -->
        <div>
            <h3 class="font-bold text-2xl text-blue-900">TAXURI</h3>
            <p class="text-sm text-gray-700">
                Taxuri is a Taxation Management System developed exclusively for De Guzman, Pascual, and Associates, integrating predictive analytics to optimize tax preparation and compliance. Designed to support both the accounting firm and its clients, Taxuri ensures efficient pre-filing processes and tracks crucial tax deadlines. This streamlined solution empowers users to manage their tax obligations with greater accuracy and peace of mind.
            </p>
        </div>
        
        <!-- Site Links and Follow Us (Split within the second half) -->
        <div class="ps-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Site Links -->
            <div>
                <h3 class="font-bold text-lg text-blue-900 mb-2">SITE LINKS</h3>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li><a href="{{ route('client.dashboard') }}" class="hover:underline">Dashboard</a></li>
                    <li><a href="{{ route('client.forms') }}" class="hover:underline">Generated Forms</a></li>
                    <li><a href="{{ route('client.income_statement') }}" class="hover:underline">Income Statement</a></li>
                    <li><a href="{{ route('client.analytics') }}" class="hover:underline">Predictive Analytics</a></li>
                </ul>
            </div>
            
            <!-- Follow Us and Contact Us -->
            <div>
                <h3 class="font-bold text-lg text-blue-900 mb-2">FOLLOW US</h3>
                <p class="text-sm text-gray-700 mb-4">
                    <a href="https://www.facebook.com/dgpacpas" target="_blank" class="flex items-center space-x-2 hover:text-blue-600">
                        <i class="fab fa-facebook-square text-gray-700 text-lg"></i>
                        <span>De Guzman, Pascual & Associates, CPAs</span>
                    </a>
                </p>
                <h3 class="font-bold text-lg text-blue-900 mb-2">CONTACT US</h3>
                <p class="text-sm text-gray-700 flex items-center space-x-2">
                    <i class="fas fa-phone-alt text-gray-700"></i>
                    <span>0919 001 8952 | (044) 530 8561</span>
                </p>
                <p class="text-sm text-gray-700 flex items-center space-x-2">
                    <i class="fas fa-envelope text-gray-700"></i>
                    <a href="mailto:askdgp@dgpcpa.org" class="text-blue-600 hover:underline">askdgp@dgpcpa.org</a>
                </p>
            </div>
        </div>

    </div>
    <!-- Copyright Section -->
    <div class="mt-6 text-center text-sm text-white bg-gray-800 py-4">
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
