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
                <!-- Profile Label -->
                <label id="profile-label" for="profile_photo_input" class="cursor-pointer">
                    <!-- Profile photo or initial letter -->
                    @if($profilePhotoUrl)
                    <img src="{{ $profilePhotoUrl }}" alt="Profile Picture" id="profile-picture" class="w-36 h-36 rounded-full object-cover shadow-md hover:opacity-75 transition-opacity duration-300">
                    @else
                    <div class="w-36 h-36 rounded-full bg-blue-900 flex items-center justify-center text-yellow-500 text-5xl font-bold hover:opacity-75 transition-opacity duration-300">
                        {{ strtoupper(substr($organization->registration_name ?? 'PUP', 0, 1)) }}
                    </div>
                    @endif
                </label>
            
                <!-- Modal for Upload Restrictions -->
                <div id="upload-restriction-modal" class="fixed inset-0 bg-black bg-opacity-20 flex items-center justify-center hidden">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full relative">
                        <button id="close-modal-button" class="absolute top-4 right-4 bg-gray-200 hover:bg-gray-400 text-white rounded-full p-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-3 h-3">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div class="flex justify-start mb-4">
                            <i class="fas fa-exclamation-triangle text-amber-400 text-8xl"></i>
                        </div>
                        <h2 class="text-xl text-zinc-700 font-bold text-start mb-4">Profile Image Upload Restrictions</h2>
                        <p class="text-start mb-6 text-sm text-zinc-700">The image should meet the following criteria:</p>
                        <div class="bg-amber-100 border-l-8 border-amber-400 text-amber-500 p-6 rounded-lg mb-6">
                            <ul class="list-disc pl-5 text-[13px]">
                                <li class="pl-2"><span class="inline-block align-top">File Size: Maximum 5 MB</span></li>
                                <li class="pl-2"><span class="inline-block align-top">Image Format: JPG, JPEG, PNG only</span></li>
                                <li class="pl-2"><span class="inline-block align-top">Dimensions: Recommended 200x200 pixels or larger</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            
                <!-- Profile Photo Form -->
                <form id="profile-photo-form" action="{{ route('client.profile.update_photo') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_photo" id="profile_photo_input" class="hidden" accept="image/*" onchange="previewProfilePhoto()">
                </form>
            
                <!-- Edit Button -->
                <label id="edit-icon-button" class="absolute bottom-2 right-2 bg-zinc-700 hover:bg-zinc-900 rounded-full p-2 shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24">
                        <path fill="#ffff" d="m12.9 6.855l4.242 4.242l-9.9 9.9H3v-4.243zm1.414-1.415l2.121-2.121a1 1 0 0 1 1.414 0l2.829 2.828a1 1 0 0 1 0 1.415l-2.122 2.121z" />
                    </svg>
                </label>
            
                <!-- Action Buttons -->
                <div id="action-buttons" class="hidden mt-4 gap-4">
                    <button id="cancel-button" type="button" class="text-zinc-700 hover:text-zinc-900 px-4 py-2">Cancel</button>
                    <button id="update-button" type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-4 py-2 rounded-lg">Update</button>
                </div>
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

        // Event listener to change the profile label to a camera icon on click
        document.getElementById('edit-icon-button').addEventListener('click', function () {
            const profileLabel = document.getElementById('profile-label');
            const editButton = document.getElementById('edit-icon-button');
            const modal = document.getElementById('upload-restriction-modal');

            // Show the modal
            modal.classList.remove('hidden');

            // Replace label content with a camera icon
            profileLabel.innerHTML = `
                <div class="w-36 h-36 rounded-full bg-neutral-500 hover:bg-neutral-700 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" viewBox="0 0 16 16"><g fill="#ffff"><path d="M10.5 8.5a2.5 2.5 0 1 1-5 0a2.5 2.5 0 0 1 5 0"/><path d="M2 4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-1.172a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 9.172 2H6.828a2 2 0 0 0-1.414.586l-.828.828A2 2 0 0 1 3.172 4zm.5 2a.5.5 0 1 1 0-1a.5.5 0 0 1 0 1m9 2.5a3.5 3.5 0 1 1-7 0a3.5 3.5 0 0 1 7 0"/></g></svg>
                </div>
            `;

            // Hide the edit button
            editButton.style.display = 'none';
        });

        // Close the modal
        document.getElementById('close-modal-button').addEventListener('click', function () {
            document.getElementById('upload-restriction-modal').classList.add('hidden');
        });

        // Preview selected profile photo
        function previewProfilePhoto() {
            const fileInput = document.getElementById('profile_photo_input');
            const profilePicture = document.getElementById('profile-picture') || document.querySelector('#profile-label > div');
            const modal = document.getElementById('upload-restriction-modal');
            const actionButtons = document.getElementById('action-buttons');

            const file = fileInput.files[0];
            if (file) {
                const fileSizeMB = file.size / 1024 / 1024;
                const allowedFormats = ['image/jpeg', 'image/png'];

                if (fileSizeMB > 5 || !allowedFormats.includes(file.type)) {
                    modal.classList.remove('hidden');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    if (profilePicture.tagName === 'IMG') {
                        profilePicture.src = e.target.result;
                    } else {
                        profilePicture.style.backgroundImage = `url(${e.target.result})`;
                        profilePicture.classList.add('object-cover');
                    }
                    actionButtons.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
                modal.classList.add('hidden');
            }
        }

        // Cancel and Update button handlers
        document.getElementById('cancel-button').addEventListener('click', () => location.reload());
        document.getElementById('update-button').addEventListener('click', (e) => {
            e.preventDefault();
            document.getElementById('profile-photo-form').submit();
        });
    </script>

</x-client-layout>
