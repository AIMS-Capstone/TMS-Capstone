{{-- Check mail page goes after clicking the "Send Instructions" button on the forgot-password page. --}}
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo /> <span class="ml-3 font-bold taxuri-color text-3xl">TAXURI <p class="font-medium text-sm">Taxation Management System</p></span>
        </x-slot>
        
        <p class="font-bold text-4xl text-center auth-color mt-44">Check your mail</p>
        <p class="font-normal mt-2 text-base text-center taxuri-text mb-6">We have sent a reset link to your email</p>
        <x-validation-errors class="mb-2" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession
        {{-- Not sure what action should be placed here --}}
        <form method="POST" action="{{ route('password.email') }}"> 
            
            @csrf
            <div class="flex items-center justify-center mt-6">
                <x-auth-button>
                    {{ __('Open the mail app') }}
                </x-auth-button>
            </div>

            <div class="flex items-center justify-center w-full mt-10">
                <button type="button" onclick="window.location.href='{{ route('login') }}'" class="inline-flex items-center focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    <span class="text-sm font-normal text-center taxuri-text hover:text-blue-950">
                        {{ __('Back to Log in') }}
                    </span>
                </button>
            </div>
            
            <p class="font-normal text-sm text-center mt-5 taxuri-text">Did not receive the mail? Check your spam filter </p>
        </div>
        </form>
        
    </x-authentication-card>
</x-guest-layout>