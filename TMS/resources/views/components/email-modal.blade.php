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
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-auto h-auto z-10 overflow-hidden" x-show="show" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">

        <!-- Modal header -->
        <div class="relative p-3 bg-blue-900 border-opacity-80 w-full">
            <h1 class="text-lg font-bold text-white text-center">Change Email Address</h1>
        </div>

        <!-- Modal body -->
        <div class="p-10">
            <p class="text-xs text-gray-600 mb-4">
                You are about to change the <strong>Email Address</strong> for the organization, 
                <strong>{{ $organization->registration_name ?? 'Unknown Organization' }}</strong>.
                To proceed, please enter the new email address below and confirm the organization’s password for security.
            </p>

            <!-- Form -->
            <form action="{{ route('client.profile.update_email') }}" method="POST" novalidate>
                @csrf
                <div class="mb-4">
                    <label class="block text-zinc-700 text-sm font-bold mb-2" for="email">New Email Address<span class="text-red-500">*</span></label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        x-model="email"
                        @input="validateForm"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        placeholder="Email Address"
                        required
                    />
                </div>

                <div class="mb-4 relative">
                    <label class="block text-zinc-700 text-sm font-bold mb-2" for="password">Password<span class="text-red-500">*</span></label>
                    <input 
                        :type="showPassword ? 'text' : 'password'"
                        id="password" 
                        name="password" 
                        x-model="password"
                        @input="validateForm"
                        class="block w-full py-2 px-0 text-sm text-zinc-700 bg-transparent border-0 border-b-2 border-gray-200 focus:outline-none focus:ring-0 focus:border-blue-900 peer"
                        placeholder="Password"
                        required
                    />
                    <!-- Toggle Password Visibility -->
                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-3 flex items-center text-gray-500 focus:outline-none">
                        <!-- Eye closed (password hidden) -->
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20" fill="none">
                            <path fill="#3f3f46" d="M12.49 9.94A2.5 2.5 0 0 0 10 7.5z"/>
                            <path fill="#3f3f46" d="M8.2 5.9a4.4 4.4 0 0 1 1.8-.4a4.5 4.5 0 0 1 4.5 4.5a4.3 4.3 0 0 1-.29 1.55L17 14.14A14 14 0 0 0 20 10s-3-7-10-7a9.6 9.6 0 0 0-4 .85zM2 2L1 3l2.55 2.4A13.9 13.9 0 0 0 0 10s3 7 10 7a9.7 9.7 0 0 0 4.64-1.16L18 19l1-1zm8 12.5A4.5 4.5 0 0 1 5.5 10a4.45 4.45 0 0 1 .6-2.2l1.53 1.44a2.5 2.5 0 0 0-.13.76a2.49 2.49 0 0 0 3.41 2.32l1.54 1.45a4.47 4.47 0 0 1-2.45.73"/>
                        </svg>
                        
                        <!-- Eye open (password visible) -->
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 20 20" fill="none">
                            <path fill="#3f3f46" d="M10 14.5a4.5 4.5 0 1 1 4.5-4.5a4.5 4.5 0 0 1-4.5 4.5M10 3C3 3 0 10 0 10s3 7 10 7s10-7 10-7s-3-7-10-7"/>
                            <circle cx="10" cy="10" r="2.5" fill="#3f3f46"/>
                        </svg>
                    </button>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end my-6">
                    <button 
                        type="button" 
                        @click="$dispatch('close-modal')"
                        class="mr-2 font-semibold text-zinc-600 px-3 py-1 rounded-md hover:text-zinc-900 transition">
                        Cancel
                    </button>
                    <button 
                        type="submit" 
                        class="font-semibold bg-blue-900 text-white text-center px-6 py-1.5 rounded-md  border-blue-900 hover:text-white transition"
                        :class="{ 'bg-blue-900 hover:bg-blue-950': email && password, 'bg-gray-300 cursor-not-allowed': !(email && password) }"
                        :disabled="!(email && password)">
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
