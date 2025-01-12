<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo /> <span class="ml-3 font-extrabold taxuri-color text-3xl">TAXURI <p class="font-medium text-sm">Taxation Management System</p></span>
        </x-slot>

        <p class="font-extrabold text-3xl text-center auth-color mt-3">Register</p>
        <p class="font-normal mt-2 text-base text-center taxuri-text mb-4">Enter the fields below to get started</p>
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-auth-label for="first_name" value="{{ __('First Name') }}" />
                <x-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus autocomplete="first_name" placeholder="Enter your First Name" oninput="validateForm()" maxlength="50em"/>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div class="sm:col-span-2 mt-2">
                    <x-auth-label for="last_name" value="{{ __('Last Name') }}" />
                    <x-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" autofocus autocomplete="last_name" placeholder="Enter your Last Name" oninput="validateForm()" maxlength="30em"/>
                </div>

                <div class="sm:col-span-1 mt-2">
                    <x-label for="suffix" value="{{ __('Suffix') }}" />
                    <x-input id="suffix" class="block mt-1 w-full" type="text" name="suffix" :value="old('suffix')" autofocus autocomplete="suffix" placeholder="e.g. Jr." maxlength="3em"/>
                </div>
            </div>

            <div class="mt-1">
                <x-auth-label for="email" value="{{ __('Email Address') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your Email Address" oninput="validateForm()" maxlength="30em"/>
            </div>

            <div class="mt-1">
                <x-auth-label for="password" value="{{ __('Password') }}" />
                <div x-data="{ show: false }" class="relative">
                    <x-input :type="show ? 'text' : 'password'" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" 
                    placeholder="Enter your password" maxlength="30" oninput="validateForm(); validatePassword()"/>
                    <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 flex items-center px-4 text-xs font-bold text-gray-500 hover:text-gray-700 focus:outline-none">
                        SHOW
                    </button>
                </div>
            </div>
            
            <div id="password-requirements" class="text-sm font-normal text-gray-600 mt-2 text-left ml-6 hidden">
                <ul class="list-disc">
                    <li id="lowercase" class="text-gray-500">At least one lowercase character</li>
                    <li id="uppercase" class="text-gray-500">At least one uppercase character</li>
                    <li id="number" class="text-gray-500">At least one number</li>
                    <li id="special" class="text-gray-500">At least one special character</li>
                    <li id="length" class="text-gray-500">8 characters minimum</li>
                </ul>
            </div>

            <div class="mt-1">
                <x-auth-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <div x-data="{ show: false }" class="relative">
                    <x-input :type="show ? 'text' : 'password'" id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required 
                    autocomplete="new-password" placeholder="Confirm your password" maxlength="30" oninput="validateForm(); validatePassword()"/>
                    <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 flex items-center px-4 text-xs font-bold text-gray-500 hover:text-gray-700 focus:outline-none">
                        SHOW
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

            <div class="flex items-center justify-end mt-3">
                <x-auth-button id="register-button" disabled>
                    {{ __('Create Account') }}
                </x-auth-button>
            </div>
            {{-- add a redirection/route to register-success-page.blade.php --}}

            {{-- <p class="font-normal text-sm text-center mt-3 taxuri-text">Already have an account? <a class="font-bold text-sm hover:text-gray-900 focus:outline-none" href="{{ route('login') }}">
                {{ __('Login') }}
            </a> --}}
        </form>
        <script>
            function togglePassword(id) {
                const passwordField = document.getElementById(id);
                const toggleButton = passwordField.nextElementSibling;
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                toggleButton.textContent = type === 'password' ? 'SHOW' : 'HIDE';
            }
            function validateForm() {
                const fields = ['first_name', 'last_name', 'email', 'password', 'password_confirmation'];
                const registerButton = document.getElementById('register-button');
                let allFilled = true;
                fields.forEach(field => {
                    if (document.getElementById(field).value === '') {
                        allFilled = false;
                    }
                });
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation').value;
                const passwordsMatch = password === confirmPassword;
                registerButton.disabled = !(allFilled && passwordsMatch);
            }
            function validatePassword() {
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password_confirmation')?.value || '';
                const registerButton = document.getElementById('register-button');
        
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
                if (confirmPassword) {
                    document.getElementById('password_confirmation').classList.toggle('border-emerald-500', passwordsMatch);
                }
        
                const passwordField = document.getElementById('password');
                const passwordRequirements = document.getElementById('password-requirements');
                passwordField.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        passwordRequirements.classList.remove('hidden');
                    } else {
                        passwordRequirements.classList.add('hidden');
                    }
                });
                passwordField.addEventListener('focus', function() {
                    if (this.value.length > 0) {
                        passwordRequirements.classList.remove('hidden');
                    }
                });
                passwordField.addEventListener('blur', function() {
                    if (this.value.length === 0) {
                        passwordRequirements.classList.add('hidden');
                    }
                });
        
                validateForm();
            }
        </script>
    </x-authentication-card>
</x-guest-layout>