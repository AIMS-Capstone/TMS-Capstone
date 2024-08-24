<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo /> <span class="ml-3 font-bold taxuri-color text-3xl">TAXURI <p class="font-medium text-sm">Taxation Management System</p></span>
        </x-slot>

        <p class="font-bold text-4xl text-center auth-color mt-44">One Step Away</p>
        <div class="font-normal mt-2 text-base text-center taxuri-text mb-6">
            {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div class="flex items-center justify-center mt-6 mb-6 sm:w-3/4 lg:w-80 mx-auto">
                    <x-auth-button type="submit">
                        {{ __('Resend Verification Email') }}
                    </x-auth-button>
                </div>
            </form>

            <div class="flex items-center justify-center w-full mt-10">
                {{-- <a
                    href="{{ route('profile.show') }}"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ __('Edit Profile') }}</a> --}}

                {{-- <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none ms-2">
                        {{ __('Log Out') }}
                    </button>
                </form> --}}
            </div>
        </div>
    </x-authentication-card>
</x-guest-layout>
