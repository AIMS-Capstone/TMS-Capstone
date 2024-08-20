<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo /> <span class="ml-3 font-bold taxuri-color text-3xl">TAXURI <p class="font-medium text-sm">Taxation Management System</p></span>
        </x-slot>

        <div class="mb-4 text-sm mt-10">
            <p class="font-bold text-4xl text-center auth-color mt-28">Forgot Password</p>
            <p class="font-normal mt-2 text-base text-center taxuri-text mb-10">No worries, just let us know your email address and we will email you a password reset link.</p>
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-auth-label for="email" value="{{ __('Email Address') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your Email Address"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-auth-button>
                    {{ __('Send Instructions') }}
                </x-auth-button>
            </div>

            <p class="font-normal text-center mt-10 taxuri-text"><a class="font-normal text-center text-sm hover:text-gray-900 focus:outline-none" href="{{ route('login') }}">
                {{ __('Back to Log in') }}
            </a>
        </form>
    </x-authentication-card>
</x-guest-layout>