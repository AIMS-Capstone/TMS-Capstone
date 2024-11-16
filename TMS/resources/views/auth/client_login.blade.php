<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo /> 
            <span class="ml-3 font-extrabold taxuri-color text-3xl">TAXURI 
                <p class="font-medium text-sm">Taxation Management System</p>
            </span>
        </x-slot>

        <!-- "Client" label with reduced margin -->
        <div class="flex justify-center">
            <span class="bg-yellow-100 mt-32 text-yellow-600 font-bold text-xs px-3 pt-1 rounded-full">Client</span>
        </div>

        <!-- "Log In" with a negative margin to move closer to "Client" -->
        <p class="font-extrabold text-2xl sm:text-4xl text-center auth-color">Log In</p>

        <p class="font-normal mt-2 text-sm sm:text-base text-center taxuri-text mb-4 sm:mb-6">
            Enter your details to sign in to your client account
        </p>
        <x-validation-errors class="mb-2" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('client.login') }}">
            @csrf
            <div>
                <x-auth-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your Email Address" oninput="checkForm()" />
            </div>

            <div class="mt-4">
                <x-auth-label for="password" value="{{ __('Password') }}" />
                <div x-data="{ show: false }" class="relative">
                    <x-input :type="show ? 'text' : 'password'" id="password" class="block mt-1 w-full" type="password" name="password" required 
                    autocomplete="new-password" placeholder="Enter your password" maxlegnth="30" oninput="checkForm()"/>
                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 flex items-center px-4 text-xs font-bold text-gray-500 hover:text-gray-700 focus:outline-none">
                        SHOW
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-left mt-4">
                @if (Route::has('client.password.request'))
                    <a class="font-medium text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none" href="{{ route('client.password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="flex items-center justify-center mt-6">
                <x-auth-button id="login-button" disabled>
                    {{ __('Log in') }}
                </x-auth-button>
            </div>
        </form>

        <script>
            function togglePassword(id) {
                const passwordField = document.getElementById(id);
                const toggleButton = passwordField.nextElementSibling;
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                toggleButton.textContent = type === 'password' ? 'SHOW' : 'HIDE';
            }

            function checkForm() {
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value.trim();
                const loginButton = document.getElementById('login-button');

                loginButton.disabled = email === '' || password === '';
            }

            document.addEventListener('DOMContentLoaded', checkForm);
        </script>
    </x-authentication-card>
</x-guest-layout>