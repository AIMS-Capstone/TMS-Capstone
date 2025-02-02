<div submit="updateProfileInformation">

    <div class="flex flex-col items-center mb-12">
        <div class="relative group mb-12">
        <!-- Profile Label -->  
            <label id="profile-label" for="profile_photo_input" class="cursor-pointer relative inline-block">
                <!-- Profile photo or initial letter -->
                @if ($this->user->profile_photo_url)
                    <img src="{{ asset('storage/' . $this->user->profile_photo_path) }}" 
                        alt="Profile Picture" id="profile-picture" 
                        class="w-36 h-36 rounded-full object-cover shadow-md hover:opacity-75 transition-opacity duration-300">
                @else
                    <div class="w-36 h-36 rounded-full bg-blue-900 flex items-center justify-center text-yellow-500 text-5xl font-bold hover:opacity-75 transition-opacity duration-300">
                        {{ strtoupper(substr($this->user->name ?? 'PUP', 0, 1)) }}
                    </div>
                @endif

                <!-- Edit Button (Now correctly positioned inside the profile image) -->
                <span id="edit-icon-button" 
                    class="absolute bottom-2 right-2 bg-zinc-700 hover:bg-zinc-900 rounded-full p-2 shadow-md flex items-center justify-center w-8 h-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24">
                        <path fill="#ffff" d="m12.9 6.855l4.242 4.242l-9.9 9.9H3v-4.243zm1.414-1.415l2.121-2.121a1 1 0 0 1 1.414 0l2.829 2.828a1 1 0 0 1 0 1.415l-2.122 2.121z" />
                    </svg>
                </span>
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
        <form id="profile-photo-form" action="{{ route('user.update-profile-photo') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="profile_photo" id="profile_photo_input" class="hidden" accept="image/*" onchange="previewProfilePhoto()">
        </form>

        <!-- Action Buttons -->
        <div id="action-buttons" class="hidden mt-4 gap-4">
            <button id="cancel-button" type="button" class="text-zinc-700 hover:text-zinc-900 px-4 py-2">Cancel</button>
            <button id="update-button" type="submit" class="bg-blue-900 hover:bg-blue-950 text-white px-4 py-2 rounded-lg">Update</button>
        </div>
    </div>
    <hr>

        <div class="ml-4 flex flex-row justify-between">
            <div>
                <h2 class="text-xl font-bold text-blue-900">Profile Information</h2>
                <p class="text-zinc-500 text-sm mt-1">Update your account’s profile information and email address.</p>
            </div>
            <div class="max-w-3xl mx-auto rounded-lg shadow-sm bg-white ml-14 p-4">
                <div class="gap-6 p-6 text-zinc-700">
                    <div class="mb-5 flex justify-between space-x-4 items-start">
                        <div class="w-full">
                            <label class="block font-bold text-zinc-700">First Name</label>
                            <input type="text" class="mt-1 text-sm truncate w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $this->user->first_name ?? 'PUP' }}" disabled>
                        </div>
                        <div class="w-full">
                            <label class="block font-bold text-zinc-700">Middle Name</label>
                            <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $this->user->middle_name ?? ' ' }}" disabled>
                        </div>
                    </div>

                    <!-- Classification Field -->
                    <div class="mb-5 flex justify-between space-x-4 items-start">
                        <div class="w-2/3">
                            <label class="block font-bold text-zinc-700">Last Name</label>
                            <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $this->user->last_name ?? ' ' }}" disabled>
                        </div>
                        <div class="w-2/3">
                            <label class="block font-bold text-zinc-700">Suffix</label>
                            <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                                value="{{ $this->user->suffix ?? ' ' }}" disabled>
                        </div>
                    </div>

                    <!-- Email Address Field -->
                    <div class="mb-8">
                        <label class="block font-bold text-zinc-700">Email</label>
                        <input type="text" class="mt-1 text-sm w-full border border-gray-300 rounded-xl shadow-sm px-3 py-2 bg-white" 
                            value="{{ $this->user->email ?? ' ' }}" disabled>
                    </div>

                    <div>
                        <button class="w-full px-3 py-2 bg-blue-900 text-white hover:bg-blue-950 font-semibold rounded-xl">Edit Information</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</div>

<script>
    // JavaScript to preview the selected profile photo and manage modals
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

    // Event listeners for buttons
    document.getElementById('edit-icon-button').addEventListener('click', () => {
        document.getElementById('profile_photo_input').click();
    });

    document.getElementById('cancel-button').addEventListener('click', () => location.reload());

    document.getElementById('update-button').addEventListener('click', (e) => {
        e.preventDefault();
        document.getElementById('profile-photo-form').submit();
    });

    document.getElementById('close-modal-button').addEventListener('click', () => {
        document.getElementById('upload-restriction-modal').classList.add('hidden');
    });


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

</script>
