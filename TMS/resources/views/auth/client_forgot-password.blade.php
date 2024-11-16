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
        <div class="mb-4 text-sm mt-5">
            <p class="font-extrabold text-4xl text-center auth-color">Forgot Password</p>
            <p class="font-normal mt-2 text-base text-center taxuri-text mb-10">
                No worries, just let us know your email address and we will email you a password reset link.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('client.password.email') }}">
            @csrf

            <div class="block">
                <x-auth-label for="email" value="{{ __('Email Address') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="Enter your Email Address"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-auth-button id="forgotpass-button" disabled>
                    {{ __('Send Instructions') }}
                </x-auth-button>
            </div>

            <div class="flex items-center justify-center w-full mt-10">
                <button type="button" onclick="window.location.href='{{ route('client.login') }}'" class="inline-flex items-center focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    <span class="text-sm font-normal text-center taxuri-text hover:text-blue-950">
                        {{ __('Back to Log in') }}
                    </span>
                </button>
            </div>
        </form>

        <script>
            function checkForm() {
                const email = document.getElementById('email').value.trim();
                const forgotpassButton = document.getElementById('forgotpass-button');
                forgotpassButton.disabled = email === '';
            }
            document.getElementById('email').addEventListener('input', checkForm);
            document.addEventListener('DOMContentLoaded', checkForm);
        </script>
    </x-authentication-card>
</x-guest-layout>
