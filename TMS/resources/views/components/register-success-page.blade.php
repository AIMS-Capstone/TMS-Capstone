{{-- This page will be seen after clicking "Create Account" in the register page. --}}
<x-guest-layout>
    <div class="min-h-screen flex flex-col items-center justify-center bg-sky-50">
        <div class="size-1/5">
            <img src="images/Key-pana.png" alt="Successfully Registered">
        </div>
        <p class="mt-2 text-center text-emerald-500 font-bold text-3xl">Successfully Registered!</p>
        <p class="text-center">You can now access your<br>account by logging into the<br>Taxuri login page.</p>
        {{-- <div class="flex items-center justify-end mt-4">
            <a href="{{ route('login') }}" class="inline-flex items-center w-48 justify-center py-2 bg-emerald-500 border border-transparent rounded-xl font-bold text-sm text-white tracking-widest hover:bg-emerald-600 focus:bg-emerald-700 active:bg-emerald-700 focus:outline-none disabled:opacity-50 transition ease-in-out duration-150">
                {{ __('Log In') }} 
                <div class="ms-2 w-5 h-5 flex items-center justify-center border-2 border-white rounded-full">
                    <svg class="rtl:rotate-180 w-3.5 h-3.5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                    </svg>
                </div>
            </a>
        </div> --}}
        {{-- Choose na lang whichever you prefer na gamitin --}}
        <div class="flex items-center justify-end mt-4">
            <button type="button" onclick="window.location.href='{{ route('login') }}'" class="inline-flex items-center w-48 justify-center py-2 bg-emerald-500 border border-transparent rounded-xl font-bold text-sm text-white tracking-widest hover:bg-emerald-600 focus:bg-emerald-700 active:bg-emerald-700 focus:outline-none disabled:opacity-50 transition ease-in-out duration-150">
                {{ __('Log In') }} 
                <div class="ms-2 w-5 h-5 flex items-center justify-center border-2 border-white rounded-full">
                    <svg class="rtl:rotate-180 w-3.5 h-3.5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                    </svg>
                </div>
            </button>
        </div>
    </div>
</x-guest-layout>