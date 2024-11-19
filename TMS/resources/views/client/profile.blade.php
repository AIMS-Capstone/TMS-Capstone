 <x-client-layout>
    @php
        $organizationId = session('organization_id');
        $organization = \App\Models\OrgSetup::find($organizationId);
    @endphp

    <div class="max-w-4xl py-12 px-4 mx-auto sm:px-6 lg:px-8">
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
                        <div class="w-36 h-36 rounded-full bg-blue-900 flex items-center justify-center text-yellow-500 text-5xl font-bold hover:opacity-75 transition-opacity duration-300" style="width: 144px; height: 144px;">
                            {{ strtoupper(substr($organization->registration_name ?? 'PUP', 0, 1)) }}
                        </div>
                    @endif
                </label>

                <!-- Hidden File Input for Profile Picture Upload -->
                <form id="profile-photo-form" action="{{ route('client.profile.update_photo') }}" method="POST" enctype="multipart/form-data" class="hidden">
                    @csrf
                    <input type="file" name="profile_photo" id="profile_photo_input" class="hidden" accept="image/*" onchange="previewProfilePhoto()">
                </form>

                <!-- Edit Icon to Trigger Hover Effect -->
                <label class="absolute bottom-2 right-2 bg-zinc-700 hover:bg-zinc-900 rounded-full p-2 shadow-md cursor-pointer"
                    onclick="document.getElementById('profile_photo_input').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24"><path fill="#ffff" d="m12.9 6.855l4.242 4.242l-9.9 9.9H3v-4.243zm1.414-1.415l2.121-2.121a1 1 0 0 1 1.414 0l2.829 2.828a1 1 0 0 1 0 1.415l-2.122 2.121z"/></svg>
                </label>
            </div>

            <!-- Organization Name and Role -->
            <h1 class="text-2xl font-bold text-zinc-700 mt-4">{{ $organization->registration_name ?? 'PUP' }}</h1>
            <p class="text-zinc-500">Client</p>
        </div>

        <!-- Organization Information Section -->
        <div class="flex flex-row justify-between">
            <div>
                <h2 class="text-xl font-bold text-blue-900">Organization Information</h2>
                <p class="text-zinc-500 text-sm mt-1">Access organization’s profile information and update email address.</p>
            </div>

            <!-- Organization Details Table -->
            <div class="max-w-3xl mx-auto rounded-lg shadow-sm bg-white ml-10 p-4">
                <div class="gap-6 p-6 text-zinc-700">
                    <div class="mb-5 flex justify-between space-x-4 items-start">
                        <div class="w-full">
                            <label class="block font-bold text-zinc-700">Name</label>
                            <input type="text" class="mt-1 text-sm truncate w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $organization->registration_name ?? 'PUP' }}" disabled>
                        </div>
                        <div class="w-full">
                            <label class="block font-bold text-zinc-700">TIN</label>
                            <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $organization->tin ?? '123-456-789-012' }}" disabled>
                        </div>
                    </div>

                    <!-- Classification Field -->
                    <div class="mb-5 flex justify-between space-x-4 items-start">
                        <div class="w-2/3">
                            <label class="block font-bold text-zinc-700">Classification</label>
                            <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $organization->classification ?? 'Non-Individual' }}" disabled>
                        </div>
                        <div class="w-2/3">
                            <label class="block font-bold text-zinc-700">Account Type</label>
                            <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="Client" disabled>
                        </div>
                    </div>

                    <!-- Date Account Created Field -->
                    <div class="mb-5">
                        <label class="block font-bold text-zinc-700">Date Account Created</label>
                        <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                            value="{{ $user->created_at->format('F d, Y') }}" disabled>
                    </div>

                    <!-- Email Address Field -->
                    <div>
                        <x-email-modal :organization="$organization" />
                        <div class="flex items-center justify-between">
                            <label class="block font-bold text-zinc-700">Email Address</label>
                            <button class="text-sm text-zinc-600 hover:text-blue-600 underline focus:text-blue-800 font-medium focus:outline-none" 
                                    onclick="openEmailModal()">
                                Change Email Address
                            </button>
                        </div>
                        <div class="flex items-center mt-1">
                            <input type="text" class="w-full text-sm border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $user->email }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <x-section-border />

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

</x-client-layout>
