<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo /> <span class="ml-3 font-bold taxuri-color text-3xl">TAXURI <p class="font-medium text-sm">Taxation Management System</p></span>
        </x-slot>

        <x-validation-errors class="mb-4" />
        <p class="font-bold text-4xl text-center auth-color mt-10">Register</p>
        <p class="font-normal mt-2 text-base text-center taxuri-text mb-6">Enter the fields below to get started</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-auth-label for="first_name" value="{{ __('First Name') }}" />
                <x-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" placeholder="Enter your First Name" />
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="sm:col-span-2 mt-2">
                    <x-auth-label for="last_name" value="{{ __('Last Name') }}" />
                    <x-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required autofocus autocomplete="last_name" placeholder="Enter your Last Name" />
                </div>

                <div class="sm:col-span-1 mt-2">
                    <x-label for="suffix" value="{{ __('Suffix') }}" />
                    <x-input id="suffix" class="block mt-1 w-full" type="text" name="suffix" :value="old('suffix')" required autofocus autocomplete="suffix" placeholder="e.g. Jr." />
                </div>
            </div>

            <div class="mt-2">
                <x-auth-label for="email" value="{{ __('Email Address') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your Email Address"/>
            </div>

            <div class="mt-2">
                <x-auth-label for="password" value="{{ __('Password') }}" />
                <div x-data="{ show: false }" class="relative">
                    <x-input :type="show ? 'text' : 'password'" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Enter your password"/>
                    <button type="button" @click="show = !show" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-bold text-gray-700 hover:text-gray-900 focus:outline-none">
                        <span x-text="show ? 'HIDE' : 'SHOW'"></span>
                    </button>
                </div>
            </div>

            <div class="mt-2">
                <x-auth-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <div x-data="{ show: false }" class="relative">
                    <x-input :type="show ? 'text' : 'password'" id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password"/>
                    <button type="button" @click="show = !show" 
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-xs font-bold text-gray-700 hover:text-gray-900 focus:outline-none">
                        <span x-text="show ? 'HIDE' : 'SHOW'"></span>
                    </button>
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <x-auth-button>
                    {{ __('Create Account') }}
                </x-auth-button>
            </div>

            <p class="font-normal text-center mt-5 taxuri-text">Already have an account? <a class="font-bold underline text-sm hover:text-gray-900 focus:outline-none" href="{{ route('login') }}">
                {{ __('Login') }}
            </a>
        </form>
    </x-authentication-card>
</x-guest-layout>