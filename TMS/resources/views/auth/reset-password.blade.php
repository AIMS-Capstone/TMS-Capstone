{{-- Reset Password page is now 'Create New Password' -> this page will show upon clicking reset link. --}}
<x-guest-layout>
    <x-auth-create-pass>
        <x-slot name="logo">
            <x-authentication-card-logo /> <span class="ml-3 font-bold taxuri-color text-3xl">TAXURI <p class="font-medium text-sm">Taxation Management System</p></span>
        </x-slot>

        <x-validation-errors class="mb-4" />
        <div class="relative w-96 border-b-2 border-blue-950/100 pb-4 mx-auto"></div>
        <p class="font-extrabold text-3xl text-center auth-color mt-2">Create New Password</p>
        <p class="font-normal mt-2 text-base text-center taxuri-text mb-6">Your new password must be different<br>from previous used passwords</p>

        <form method="POST" action="{{ route('password.update') }}">
            {{-- idk how this would interact --}}
            {{-- @csrf --}}

            {{-- <input type="hidden" name="token" value="{{ $request->route('token') }}"> --}}

            {{-- <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div> --}}

            <div class="flex flex-col items-center">
                <div class="mt-4">
                    <x-create-pass-label for="password" value="{{ __('Password') }}" />
                    <div x-data="{ show: false }" class="relative sm:w-3/4 lg:w-80">
                        <x-create-pass-input :type="show ? 'text' : 'password'" id="password" class="block mt-1 w-full pr-10 text-sm font-normal" type="password" name="password" required autocomplete="new-password" placeholder="Create your new password" maxlength="30" oninput="validatePassword()"/>
                        <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 flex items-center px-4 text-xs font-bold text-gray-500 hover:text-gray-700 focus:outline-none">
                            SHOW
                        </button>
                    </div>
                </div>

                <div id="password-requirements" class="text-sm font-normal text-gray-600 mt-2 text-left">
                    <ul class="list-disc">
                        <li id="lowercase" class="text-gray-500">At least one lowercase character</li>
                        <li id="uppercase" class="text-gray-500">At least one uppercase character</li>
                        <li id="number" class="text-gray-500">At least one number</li>
                        <li id="special" class="text-gray-500">At least one special character</li>
                        <li id="length" class="text-gray-500">8 characters minimum</li>
                    </ul>
                </div>

                <div class="mt-4">
                    <x-create-pass-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                    <div x-data="{ show: false }" class="relative sm:w-3/4 lg:w-80">
                        <x-create-pass-input :type="show ? 'text' : 'password'" id="password_confirmation" class="block mt-1 w-full pr-10 text-sm font-normal" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your new password" maxlength="30" oninput="validatePassword()"/>
                        <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 flex items-center px-4 text-xs font-bold text-gray-500 hover:text-gray-700 focus:outline-none">
                            SHOW
                        </button>
                    </div>    
                </div>
            </div>    

            <div class="flex items-center justify-center mt-6 mb-6 sm:w-3/4 lg:w-80 mx-auto">
                <x-create-pass-button id="reset-button" disabled>
                    {{ __('Reset Password') }}
                </x-create-pass-button>
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

        function validatePassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const resetButton = document.getElementById('reset-button');

            const lowercasePattern = /[a-z]/;
            const uppercasePattern = /[A-Z]/;
            const numberPattern = /[0-9]/;
            const specialPattern = /[^A-Za-z0-9]/;
            const minLength = 8;

            const hasLowercase = lowercasePattern.test(password);
            const hasUppercase = uppercasePattern.test(password);
            const hasNumber = numberPattern.test(password);
            const hasSpecial = specialPattern.test(password);
            const hasMinLength = password.length >= minLength;
            const passwordsMatch = password === confirmPassword;

            document.getElementById('lowercase').className = hasLowercase ? 'text-emerald-500' : 'text-gray-500';
            document.getElementById('uppercase').className = hasUppercase ? 'text-emerald-500' : 'text-gray-500';
            document.getElementById('number').className = hasNumber ? 'text-emerald-500' : 'text-gray-500';
            document.getElementById('special').className = hasSpecial ? 'text-emerald-500' : 'text-gray-500';
            document.getElementById('length').className = hasMinLength ? 'text-emerald-500' : 'text-gray-500';

            const isValid = hasLowercase && hasUppercase && hasNumber && hasSpecial && hasMinLength && passwordsMatch;

            document.getElementById('password').classList.toggle('border-emerald-500', isValid);
            document.getElementById('password_confirmation').classList.toggle('border-emerald-500', passwordsMatch);

            resetButton.disabled = !isValid;
        }
        </script>
    </x-auth-create-pass>
</x-guest-layout>
