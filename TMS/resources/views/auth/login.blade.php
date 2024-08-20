<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo /> <span class="ml-3 font-bold taxuri-color text-3xl">TAXURI <p class="font-medium text-sm">Taxation Management System</p></span>
        </x-slot>

        <x-validation-errors class="mb-4" />
        <p class="font-bold text-4xl text-center auth-color mt-28">Log In</p>
        <p class="font-normal mt-2 text-base text-center taxuri-text mb-6">Enter your details to sign in to your account</p>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-auth-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your Email Address" />
            </div>

            <div class="mt-4">
                <x-auth-label for="password" value="{{ __('Password') }}" />
                <div x-data="{ show: false }" class="relative">
                    <x-input :type="show ? 'text' : 'password'" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Enter your password"/>
                    <button type="button" @click="show = !show" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-bold text-gray-700 hover:text-gray-900 focus:outline-none">
                        <span x-text="show ? 'HIDE' : 'SHOW'"></span>
                    </button>
                </div>
            </div>

            {{-- <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div> --}}

            <div class="flex items-center justify-left mt-4">
                @if (Route::has('password.request'))
                    <a class="font-medium text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="flex items-center justify-center mt-4">
                <x-auth-button >
                    {{ __('Log in') }}
                </x-auth-button>
            </div>

            <p class="font-normal text-center mt-5 taxuri-text">Already have an account? <a class="font-bold underline text-sm hover:text-gray-900 focus:outline-none" href="{{ route('register') }}">
                {{ __('Register') }}
            </a>
            
        </div>
         
        </form>
    </x-authentication-card>
</x-guest-layout>