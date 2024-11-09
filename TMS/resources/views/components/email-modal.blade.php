{{-- Use to make the organization reachable --}}
@props(['organization'])

<div 
    x-data="{ show: false, email: '', password: '', showPassword: false }"
    x-show="show"
    x-on:open-email-modal.window="show = true"
    x-on:close-modal.window="show = false"
    x-effect="document.body.classList.toggle('overflow-hidden', show)"
    class="fixed z-50 inset-0 flex items-center justify-center m-2 px-6"
    x-cloak
>
    <!-- Modal background -->
    <div class="fixed inset-0 bg-gray-300 opacity-20"></div>

    <!-- Modal container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden">

        <!-- Modal header -->
        <div class="relative p-3 bg-blue-900 border-opacity-80 w-full">
            <h1 class="text-lg font-bold text-white text-center">Change Email Address</h1>
        </div>

        <!-- Modal body -->
        <div class="p-6">
            <p class="text-sm text-gray-600 mb-4">
                You are about to change the <strong>Email Address</strong> for the organization, 
                <strong>{{ $organization->registration_name ?? 'Unknown Organization' }}</strong>.
                To proceed, please enter the new email address below and confirm the organization’s password for security.
            </p>

            <!-- Form -->
            <form action="{{ route('client.profile.update_email') }}" method="POST" novalidate>
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">New Email Address<span class="text-red-500">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        x-model="email"
                        @input="validateForm"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Email Address"
                        required
                    />
                </div>

                <div class="mb-4 relative">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password<span class="text-red-500">*</span></label>
                    <input 
                        :type="showPassword ? 'text' : 'password'"
                        id="password" 
                        name="password" 
                        x-model="password"
                        @input="validateForm"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Password"
                        required
                    />
                    <!-- Toggle Password Visibility -->
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-3 flex items-center text-gray-500 focus:outline-none">
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19.5a10.05 10.05 0 01-1.875-.675m0 0A10.012 10.012 0 016.55 16.25m5.325 2.575a2.25 2.25 0 002.25-2.25v-1.5a2.25 2.25 0 00-2.25-2.25H9.75a2.25 2.25 0 00-2.25 2.25v1.5a2.25 2.25 0 002.25 2.25h1.125zm3.2-2.575A10.05 10.05 0 0118.825 13.5m0 0A10.05 10.05 0 0119.5 12a10.05 10.05 0 01-.675-1.875m0 0A10.012 10.012 0 0116.25 6.55M16.5 12.75v-3M12 5.5a7.013 7.013 0 00-5.303 2.25M16.5 12.75H8.25M16.5 12.75h-3m0 0a2.25 2.25 0 00-2.25-2.25m4.5 2.25H12"/>
                        </svg>
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 01-2.225 2.88m.97-4.195a2.977 2.977 0 00.5 2.315m-1.47 1.87a2.985 2.985 0 00-4.598-.09m0-4.176a2.977 2.977 0 01-.5 2.315M12 4.5a10.012 10.012 0 00-7.25 3.25m10.5 10.5a10.012 10.012 0 01-7.25 3.25M4.5 12a10.012 10.012 0 017.25-3.25m0 0a10.012 10.012 0 017.25 3.25m-10.5-3.25H8.25m0 0h3"/>
                        </svg>
                    </button>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between mt-6">
                    <button 
                        type="button" 
                        @click="$dispatch('close-modal')"
                        class="text-gray-600 hover:text-gray-800 text-sm font-semibold"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="bg-blue-500 text-white font-semibold px-4 py-2 rounded transition duration-200"
                        :class="{ 'bg-blue-500 hover:bg-blue-700': email && password, 'bg-gray-300 cursor-not-allowed': !(email && password) }"
                        :disabled="!(email && password)"
                    >
                        Update Email Address
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    function validateForm() {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        return email && password;
    }
</script>
