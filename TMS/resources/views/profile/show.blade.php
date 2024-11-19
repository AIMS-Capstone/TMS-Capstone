<x-organization-layout>
    
    <div class="overflow-x-auto ml-20 mt-10 absolute flex items-center">
        <button onclick="history.back()" class="text-zinc-600 hover:text-zinc-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="inline-block w-5 h-5" viewBox="0 0 24 24">
                <g fill="none" stroke="#52525b" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M16 12H8m4-4l-4 4l4 4"/></g>
            </svg>
            <span class="text-zinc-600 text-sm font-normal hover:text-zinc-700">Go Back</span>
        </button>
    </div>

    <div class="py-12">
        <div class="max-w-4xl mx-auto py-10 sm:px-6 lg:px-8">

                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    @livewire('profile.update-profile-information-form')

                    <x-section-border />
                @endif

                {{-- @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.update-password-form')
                    </div>

                    <x-section-border />
                @endif --}}

                {{-- @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.two-factor-authentication-form')
                    </div>

                    <x-section-border />
                @endif --}}

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>

                {{-- @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <x-section-border />

                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.delete-user-form')
                    </div>
                @endif --}}
        </div>
    </div>
</x-organization-layout>


{{-- <x-client-layout>
    @php
        $organizationId = session('organization_id');
        $organization = \App\Models\OrgSetup::find($organizationId);
    @endphp

    <div class="max-w-4xl mx-auto py-12 px-4">
        <!-- Trigger success modals based on session status -->
        @if(session('status') === 'profile_updated')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    window.dispatchEvent(new CustomEvent('open-profile-success'));
                });
            </script>
        @endif

        @if(session('status') === 'email_updated')
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    window.dispatchEvent(new CustomEvent('open-email-success'));
                });
            </script>
        @endif

        <!-- Profile Icon and Name -->
        <div class="flex flex-col items-center mb-12">
            <!-- Profile Icon with Edit Button -->
            <div class="relative group">
                <label for="profile_photo_input" class="cursor-pointer">
                    @if($profilePhotoUrl)
                        <img src="{{ $profilePhotoUrl }}" alt="Profile Picture" id="profile-picture" class="w-36 h-36 rounded-full object-cover shadow-md hover:opacity-75 transition-opacity duration-300" style="width: 144px; height: 144px;">
                    @else
                        <div class="w-36 h-36 rounded-full bg-blue-700 flex items-center justify-center text-yellow-500 text-5xl font-bold hover:opacity-75 transition-opacity duration-300" style="width: 144px; height: 144px;">
                            {{ strtoupper(substr($organization->registration_name ?? 'PUP', 0, 2)) }}
                        </div>
                    @endif
                </label>

                <!-- Hidden File Input for Profile Picture Upload -->
                <form id="profile-photo-form" action="{{ route('client.profile.update_photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" name="profile_photo" id="profile_photo_input" class="hidden" accept="image/*" onchange="previewProfilePhoto()">
                </form>

                <!-- Edit Icon to Trigger Hover Effect -->
                <label class="absolute bottom-2 right-2 bg-gray-200 rounded-full p-2 shadow-md cursor-pointer"
                    onclick="document.getElementById('profile_photo_input').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11 15H9v-2z" />
                    </svg>
                </label>
            </div>

            <!-- Organization Name and Role -->
            <h1 class="text-2xl font-bold text-gray-800 mt-4">{{ $organization->registration_name ?? 'PUP' }}</h1>
            <p class="text-gray-500">Client</p>
        </div>

        <!-- Organization Information Section -->
        <div class="flex flex-row p-8 justify-between">
            <div>
                <h2 class="text-xl font-semibold text-blue-900 mb-4">Organization Information</h2>
                <p class="text-gray-500 mb-6">Access organization’s profile information and update email address.</p>
            </div>

            <!-- Organization Details Table -->
            <div class="max-w-3xl mx-auto rounded-lg shadow-lg bg-gray-50 p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-gray-700">
                    <!-- Name Field -->
                    <div>
                        <label class="block font-medium text-gray-600">Name</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 bg-gray-50" 
                            value="{{ $organization->registration_name ?? 'PUP' }}" disabled>
                    </div>

                    <!-- TIN Field -->
                    <div>
                        <label class="block font-medium text-gray-600">TIN</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 bg-gray-50" 
                            value="{{ $organization->tin ?? '123-456-789-012' }}" disabled>
                    </div>

                    <!-- Classification Field -->
                    <div>
                        <label class="block font-medium text-gray-600">Classification</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 bg-gray-50" 
                            value="{{ $organization->classification ?? 'Non-Individual' }}" disabled>
                    </div>

                    <!-- Account Type Field -->
                    <div>
                        <label class="block font-medium text-gray-600">Account Type</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 bg-gray-50" 
                            value="Client" disabled>
                    </div>

                    <!-- Date Account Created Field -->
                    <div>
                        <label class="block font-medium text-gray-600">Date Account Created</label>
                        <input type="text" class="mt-1 w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 bg-gray-50" 
                            value="{{ $user->created_at->format('F d, Y') }}" disabled>
                    </div>

                    <!-- Email Address Field -->
                    <div>
                        <x-email-modal :organization="$organization"/>
                        <label class="block font-medium text-gray-600">Email Address</label>
                        <div class="flex items-center mt-1">
                            <input type="text" class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 bg-gray-50" 
                                value="{{ $user->email }}" disabled>
                            <button class="ml-2 text-blue-600 underline hover:text-blue-800 font-medium focus:outline-none" 
                                    onclick="openEmailModal()">
                                Change Email Address
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="font-bold p-4">

            <div class="mt-10 sm:mt-0">
                <livewire:client-session />
            </div>


        <!-- Success Modal -->
        <div 
            x-data="{ showSuccess: false, message: '', details: '' }"
            x-show="showSuccess"
            x-transition.opacity
            x-effect="document.body.classList.toggle('overflow-hidden', show)"
            class="fixed inset-0 flex items-center justify-center m-2 px-6 z-50"
            x-cloak
            @open-profile-success.window="showSuccess = true; message = 'Profile Updated'; details = 'Your profile has been successfully updated.'"
            @open-email-success.window="showSuccess = true; message = 'Email Address Updated'; details = 'Your email address has been successfully updated.'"
        >
        <div class="fixed inset-0 bg-gray-300 opacity-20"></div>
            <div class="bg-white rounded-lg shadow-lg p-8 w-96 mx-auto text-center relative">
                <button @click="showSuccess = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                </div>
                <h2 class="text-xl font-semibold text-green-600 mb-2" x-text="message"></h2>
                <p class="text-gray-600 text-sm mb-4" x-text="details"></p>
            </div>
        </div>
        </div>

    </div>

    <script>
        function openEmailModal() {
            window.dispatchEvent(new CustomEvent('open-email-modal'));
        }

        function previewProfilePhoto() {
            const fileInput = document.getElementById('profile_photo_input');
            const form = document.getElementById('profile-photo-form');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('profile-picture').src = e.target.result;
                }

                reader.readAsDataURL(fileInput.files[0]);
                form.submit();
            }
        }
    </script>

</x-client-layout> --}}
